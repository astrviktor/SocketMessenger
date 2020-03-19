<?php
namespace Astrviktor\Training\SocketMessenger;

// класс клиента
class SocketClient
{
    protected $socketname;
    protected $user;

    const START_MSG = "Напишите сообщение (Для выхода нажмите CTRL+C)";
    const ERROR_MSG = "Проблемы с сокетом" . PHP_EOL;

    // Стартовое сообщение
    public function startmsg()
    {
        return self::START_MSG . " [" . $this->user . ']: ';
    }

    // Инициализация
    private function init()
    {   
        $config = parse_ini_file('config.ini', true);
        $this->socketname = $config['main']['socket'];
    }

    // Конструктор
    public function __construct($user = "DefaultUser")
    {
        $this->init();
        $this->user = $user;
    }

    // Главный метод клиента
    public function run()
    {   

        $message = trim(fgets(STDIN));

        try {
            $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
            if ($socket === false) {
                return self::ERROR_MSG;
            }

            $connect = socket_connect($socket, $this->socketname);

            if ($connect === false) {
                return self::ERROR_MSG;
            } else {
                socket_write($socket, "$this->user:	$message");
            }
        } catch (Exception $e) {
            return "Произошла ошибка: " . $e->getMessage() . PHP_EOL;
        }

    }

}
