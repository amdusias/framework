<?php

namespace Framework\Base;

/**
 * Class FatalError
 */
class FatalError
{
    /** @var integer $number код ошибки */
    protected static $number = 0;
    /** @var string $message сообщение ошибки */
    protected static $message = '';
    /** @var string $file путь к файлу ошибки */
    protected static $file = '';
    /** @var int $line текущая строка */
    protected static $line = 0;
    /** @var array $context контекст выполняемой программы */
    protected static $context = [];
    /** @var array $trace вызов трассировки */
    protected static $trace = [];

    /**
     * Регистрируем обработчик ошибок
     *
     * @return void
     */
    public static function register()
    {
        set_error_handler(['\Framework\Base\FatalError', 'handle']);
    }

    /**
     * Исключение
     *
     * @param $number
     * @param $message
     * @param $file
     * @param $line
     * @param array $context
     * @return void
     */
    public static function handle($number = 0, $message = '', $file = '', $line = 0, array $context = [])
    {
        self::$context = $context;
        self::$message = $message;
        self::$number = $number;
        self::$trace = debug_backtrace();
        self::$file = $file;
        self::$line = $line;

        $level = ob_get_level();
        if ($level > 0) {
            for ($i = ob_get_level(); $i >= 0; $i--) {
                ob_clean();
            }
        }

        print('cli' === php_sapi_name() ? static::doCli() : static::doRun());
    }

    /**
     * Выводим ошибку в консоль
     *
     * @return string
     */
    protected static function doCli()
    {
        return static::$number.' - '.static::$message.' on '.static::$file.':'.static::$line;
    }

    /**
     * Выводим ошибки
     *
     * @return string
     */
    protected static function doRun()
    {
        $str = '<div class="error" style="width: 100%;">';
        $str .= '<h2>FatalError '.static::$number.' - '.static::$message.' on '.static::$file.':'.static::$line.'</h2>';

        $str .= '<table width="100%" style="width: 100%">';
        $str .= '<tr>';
        $str .= '<th width="100px">Context</th>';
        $str .= '<td style="vertical-align: top; height: 300px">';
        $str .= '<textarea disabled style="width:100%; height: 100%">'.print_r(static::$context,
                true).'</textarea>';
        $str .= '</td>';
        $str .= '</tr>';
        $str .= '<tr>';
        $str .= '<th width="100px">Debug trace</th>';
        $str .= '<td style="vertical-align: top; height: 300px">';
        $str .= '<textarea disabled style="width: 100%; height: 100%">'.print_r(static::$trace, true).'</textarea>';
        $str .= '</td>';
        $str .= '</tr>';
        $str .= '</table>';
        $str .= '</div>';

        return $str;
    }
}