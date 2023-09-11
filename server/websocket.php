<?php


use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;

class WebSocket 
{
    protected int $port;
    protected string $url;
    private Server $server;

    public function __construct($url = "http://localhost", $port = 8080) 
    {
        $this->url = $url;
        $this->port = $port;

        // Inicia uma instancia de servidor de sockets
        $this->server = new Server($this->url, $this->port);
    }

    public function start() 
    {
        // Seta o evento de inicialização e notifica no console
        $this->add_event("start", function(Server $server)
        {
            echo "OpenSwoole WebSocket Server is started at ".$this->url.":".$this->port."\n";
        });

        // Exibe no console sempre que uma conexão é aberta
        $this->add_event("open", function(Server $server, OpenSwoole\Http\Request $request)
        {
            echo "connection open: {$request->fd}\n";
        
            $server->tick(1000, function() use ($server, $request)
            {
                $server->push($request->fd, json_encode(["hello", time()]));
            });
        });

        // Exibe no console sempre que uma conexão é fechada
        $this->add_event("close", function(Server $server, int $fd)
        {
            echo "connection close: {$fd}\n";
        });

        // Adiciona o método que exibe quando alguém se conecta
        $this->add_event("connect", function(Server $server, int $fd)
        {
            echo "connection connected: {$fd}\n";
        });

        // Adiciona o método que exibe quando alguém se desconecta
        $this->add_event("disconnect", function(Server $server, int $fd)
        {
            echo "connection disconnect: {$fd}\n";
        });

        // $this->add_event("message", function(Server $server, Frame $frame)
        // {
        //     echo "received message: {$frame->data}\n";
        //     $server->push($frame->fd, json_encode(["hello", time()]));
        // });

        $this->server->start();
    }

    public function add_event(string $name, $callback) 
    {
        $this->server->on($name, $callback);
    }
}
