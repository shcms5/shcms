<?php
/**
 * Класс Exception  является контейнер, из которого все остальные исключения 
 * 
 * 
 * @package      Classes
 * @author       Shamsik
 * @link         http://shcms.ru
 */
namespace Shcms\Options\Database\MYSQLi;

abstract class Exception extends \Exception
{
    /**
     * Первоначальное сообщение, перед побегом
     */
    public $originalMessage;

    /**
     * Создает новый ezcBaseException с $message
     *
     * @param string $message
     */
    public function __construct( $message )
    {
        $this->originalMessage = $message;

        if ( php_sapi_name() == 'cli' )
        {
            parent::__construct( $message );
        }
        else
        {
            parent::__construct( htmlspecialchars( $message ) );
        }
    }
}