<?php
namespace Astrviktor\Training\SocketMessenger;

// класс сервера
class SocketServer
{
    protected $socketname;
    protected $socket;

    const START_MSG = "Ожидание сообщений (Для выхода нажмите CTRL+C)..." . PHP_EOL;
    const ERROR_MSG = "Проблемы с сокетом" . PHP_EOL;

    // Стартовое сообщение
    public function startmsg()
    {
        return self::START_MSG;
    }

    // Инициализация
    private function init()
    {   
        $config = parse_ini_file('config.ini', true);

        if ($config) {
            $this->socketname = $config['main']['socket'];
            return;
        }

        $this->$ocketname = 'myserver.sock';

        $inistr = ';переменная сокета'. PHP_EOL . '[main]' . PHP_EOL . 'socket="myserver.sock"';
        
        $handle = fopen("config.ini", "w+");
        fwrite($handle, $inistr); 
        fclose($handle);
    }

    // Конструктор
    public function __construct()
    {
        $this->init();

        @unlink($this->socketname);
        
        $this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (socket_bind($this->socket, $this->socketname) === false) {
            return self::ERROR_MSG;
        }
        
        $result = socket_listen($this->socket);
        if (!$result) {
            return self::ERROR_MSG;
        }

        return;
    }

    // Главный метод сервера
    public function run()
    {   
        $connection = socket_accept($this->socket);
        if (!$connection) {
            return self::ERROR_MSG;
        }
        $input = socket_read($connection, 1024);
        $client = $input;
        socket_close($connection);

        return $client . PHP_EOL;
    }

}