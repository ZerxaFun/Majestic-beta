<?php


namespace Core\Services\Routing;


use Core\Services\Http\Header;
use Core\Services\Http\ResponseCode;
use Core\Services\Routing\Response\ConditionModule;
use JsonException;

class APIController
{
    public static int $code = 200;

    public static array $date = [];

    public static array $error = [];

    /**
     * @var array
     */
    public array $data = [];

    private string $json;

    public static function setData(array $date = [], array $error = [], int $code = 200): APIController
    {

        self::$date = array_merge(self::$date, $date);

        self::$error = array_merge(self::$error, $error);

        self::$code = $code;

        return new self();
    }


    public static function make(): APIController
    {
        $name           = static::class;
        $class          = new $name;
        if($_ENV['cleanJsonArray'] === true) {
            $date = empty(self::$date) ? null :  self::$date;
            $error = empty(self::$error) ? null :  self::$error;
        } else {
            $date = self::$date;
            $error = self::$error;
        }


        ConditionModule::setCodeStatus(self::$code);

        $class->data  = [
            'response'  => $date,
            'error'     => $error,
            'code'      => ConditionModule::getCodeStatus()
        ];


        # Возвращение нового объекта.
        return $class;
    }
    
    /**
     * @throws JsonException
     */
    private function encode(): APIController
    {
        $this->json = json_encode($this->data, JSON_THROW_ON_ERROR);

        return $this;
    }

    /**
     * @throws JsonException
     */
    final public function json(): string
    {
        self::header();
        return $this->encode()->json;
    }

    final public static function header(): void
    {
        Header::header('json');
    }
}
