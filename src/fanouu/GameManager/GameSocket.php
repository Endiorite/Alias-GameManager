<?php

namespace fanouu\GameManager;

use Alias\packets\AcceptRequestConnection;
use fanouu\GameManager\packets\handlers\PacketHandler;
use fanouu\GameManager\packets\handlers\RemoveMatchHandler;
use fanouu\GameManager\packets\handlers\RequestMatchMakingHandler;
use fanouu\GameManager\packets\handlers\RequestPlayerStatusHandler;
use fanouu\GameManager\packets\handlers\UpdatePlayerStatusHandler;
use fanouu\GameManager\packets\Packet;
use fanouu\GameManager\packets\PacketId;
use fanouu\GameManager\packets\ServerRequestConnection;
use fanouu\GameManager\packets\TransferPlayerQueue;
use fanouu\GameManager\servers\GameServer;
use fanouu\GameManager\servers\ServerManager;

class GameSocket
{

    private Server $server;
    private \Socket $socket;

    public function __construct(Server $server)
    {
        Server::getInstance()->getLogger()->notice("Initing Socket");
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_set_option($this->socket, SOL_SOCKET, SO_BROADCAST, 1);
        socket_set_option($this->socket, SOL_SOCKET, SO_SNDBUF, 1024 * 1024 * 8);
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVBUF, 1024 * 1024 * 8);
        Server::getInstance()->getLogger()->notice("Socket is ready");
        Server::getInstance()->getLogger()->notice("GameSocket is ready");

        $this->run();
    }

    public function run()
    {
        socket_bind($this->socket, "", 2323);

        while (true){
            $data = b'';
            $error = @socket_recvfrom($this->socket, $data, 65535, 0, $address, $port);

            if (is_null($address) or is_null($port)){
                continue;
            }

            $packetId = ord($data[0]);

            if (!$error === false){
                if ($packetId === PacketId::SERVERREQUESTCONNECTION){
                    $pk = new ServerRequestConnection($data);
                    $pk->decode();
                    $serverName = $pk->server_name;

                    $addressStr = implode(":", [$address, $port]);
                    $this->server->getLogger()->warning("$addressStr | $serverName requested connection");

                    if (in_array($addressStr, Server::WHITELIST)){
                        $this->server->getLogger()->warning("$addressStr | $serverName initing server class");
                        $server = GameServer::setUp($serverName, $address, $port);

                        $pk = new AcceptRequestConnection();
                        $server->sendPacket($pk);

                        $this->server->getLogger()->warning("$addressStr | $serverName connection accepted");
                    }else $this->server->getLogger()->warning("$addressStr | $serverName not in whitelist");
                }

                $server = ServerManager::getInstance()->getServerByAddress($address, $port);

                if (isset(PacketId::PACKETS[$packetId])){
                    $class = PacketId::PACKETS[$packetId];
                    $packet = new $class($data);

                    if ($packet instanceof Packet){
                        $packet->decode();

                        foreach ($this->getHandlers() as $handler){
                            $packet->handle($handler, $server);
                        }
                    }
                }
            }

            $this->server->getMakingManager()->updateMatch();
        }
    }

    public function sendTo(Packet $packet, GameServer $server): void{
        $packet->encode();
        socket_sendto($this->socket, $packet->getBuffer(), strlen($packet->getBuffer()), 0, $server->getAddress(), $server->getPort());

        $server->lastSentPacket();
    }

    /**
     * @return PacketHandler[] array
     */
    public function getHandlers(): array
    {
        return [
            new RequestPlayerStatusHandler(),
            new UpdatePlayerStatusHandler(),
            new RemoveMatchHandler(),
            new TransferPlayerQueue(),
            new RequestMatchMakingHandler()
        ];
    }

}