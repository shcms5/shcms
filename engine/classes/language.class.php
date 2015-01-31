<?php 
/**
 * Класс для Мультиязычности на сайте
 * 
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */

class Lang {
    static private $data = array();
    static private $languageName = 'ru';
    static private $languagesList = array('ua', 'ru', 'en');
    
    /**
     * Обьявляем язык для сайта
     * 
     * @param $value Язык сайта (ru/en)
     */
    private function __construct($value) {
        if (!in_array($value, self :: $languagesList)) {
            $value = self :: $languageName;
        } else {
            self::$languageName = $value;
        }
        $languageFileLocation = H.'engine/language/' . $value . '/russian.lng';
        include_once $languageFileLocation;
        self::$data = $lang;
    }
    
    public static function setLang($value) {
        new self($value);
    }
    
    /**
     * Обработка текста для мультиязычности
     * 
     * @param $value Текст перевода
     * @param $var Дополнительный параметр
     */
    static public function __($value,$var = false) {
        if (isset(self::$data[$value])) { 
            $repl = array('%s','%s'); 
            $replon = array('%s',$var);
            return str_replace($repl,$replon,self::$data[$value]);
        } else {
            return $value;
        }
    }
    static public function getLanguageName() {
        return self::$languageName;
    }
}

   @ob_start(); //Включение буферизации вывода
   @ob_implicit_flush(0); // Выключение неявных сбросов
