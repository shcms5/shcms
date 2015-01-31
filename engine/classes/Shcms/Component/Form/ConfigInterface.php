<?php
/**
 * Интерфейс ConfigInterface класса Form
 * 
 * @package     Classes
 * @author      Shamsik
 * @link        http://shcms.ru
 */

namespace Shcms\Component\Form;


interface ConfigInterface
{
    /** @var Подключение CSS JS файлов */
    public function __construct();
    /** @var Функция для открытия формы <form *> */    
    public function open($params = array());
    /** @var Функция для закрытия формы </form> */    
    public function close();
    /** @var Функция Текстового Элемента с Параметрами */
    public function textbox($params = array());
    /** @var Функция Поле для ввода элемента с Параметрами */    
    public function input($params = array());
    /** @var Функция Select Элемента с Параметрами */
    public function select($params = array());
    /** @var Функция Мульти Флажков Элемента с Параметрами */    
    public function checkbox($params = array());
    /** @var Функция Радио кнопки Элемента с Параметрами */    
    public function radio($params = array());
    /** @var Функция <Button *> кнопки с Параметрами */    
    public function button($params = array());
    /** @var Функция <input submit *> кнопки с Параметрами */       
    public function submit($params = array());
    /** @var Функция Скрытых полей с Параметрами */   
    public function hidden($params = array());
}