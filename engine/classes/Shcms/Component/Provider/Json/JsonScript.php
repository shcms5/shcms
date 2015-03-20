<?php
 /**
   * Класс для чтения и записи json файлов
   * 
   * @package Classes
   * @author Shamsik
   * @link http://shcms.ru
   */ 

namespace Shcms\Component\Provider\Json;

class JsonScript {
    
    function json_utf8($json_str) { 
        $cyr_chars = array ( 
             '\u0430' => 'а', '\u0410' => 'А', 
             '\u0431' => 'б', '\u0411' => 'Б', 
             '\u0432' => 'в', '\u0412' => 'В', 
             '\u0433' => 'г', '\u0413' => 'Г', 
             '\u0434' => 'д', '\u0414' => 'Д', 
             '\u0435' => 'е', '\u0415' => 'Е', 
             '\u0451' => 'ё', '\u0401' => 'Ё', 
             '\u0436' => 'ж', '\u0416' => 'Ж', 
             '\u0437' => 'з', '\u0417' => 'З', 
             '\u0438' => 'и', '\u0418' => 'И', 
             '\u0439' => 'й', '\u0419' => 'Й', 
             '\u043a' => 'к', '\u041a' => 'К', 
             '\u043b' => 'л', '\u041b' => 'Л', 
             '\u043c' => 'м', '\u041c' => 'М', 
             '\u043d' => 'н', '\u041d' => 'Н', 
             '\u043e' => 'о', '\u041e' => 'О', 
             '\u043f' => 'п', '\u041f' => 'П', 
             '\u0440' => 'р', '\u0420' => 'Р', 
             '\u0441' => 'с', '\u0421' => 'С', 
             '\u0442' => 'т', '\u0422' => 'Т', 
             '\u0443' => 'у', '\u0423' => 'У', 
             '\u0444' => 'ф', '\u0424' => 'Ф', 
             '\u0445' => 'х', '\u0425' => 'Х', 
             '\u0446' => 'ц', '\u0426' => 'Ц', 
             '\u0447' => 'ч', '\u0427' => 'Ч', 
             '\u0448' => 'ш', '\u0428' => 'Ш', 
             '\u0449' => 'щ', '\u0429' => 'Щ', 
             '\u044a' => 'ъ', '\u042a' => 'Ъ', 
             '\u044b' => 'ы', '\u042b' => 'Ы', 
             '\u044c' => 'ь', '\u042c' => 'Ь', 
             '\u044d' => 'э', '\u042d' => 'Э', 
             '\u044e' => 'ю', '\u042e' => 'Ю', 
             '\u044f' => 'я', '\u042f' => 'Я', 
             '\r' => '', '\n' => '<br />', '\t' => '' 
        ); 

        foreach ($cyr_chars as $cyr_char_key => $cyr_char) { 
            $json_str = str_replace($cyr_char_key, $cyr_char, $json_str); 
        }
        
        return $json_str; 
    } 
	
    /**
     * Сериализация произвольных типов данных в JSON
     * У объектов берутся только публичные свойства
     * @param $mixed
     * @param bool $unescape_unicode Не экранировать юникод
     * @return string
     */
    public function encode($mixed, $unescape_unicode = true)
    {
        if ($unescape_unicode) {
            if (defined('JSON_UNESCAPED_UNICODE')) return json_encode($mixed, JSON_UNESCAPED_UNICODE);
            else
                    return preg_replace_callback('/((\\\u[01-9a-fA-F]{4})+)/', array('json', 'prepareUTF8'),
                    json_encode($mixed)
                );
        }
        return json_encode($mixed);
    }

    /**
     * Парсинг JSON строки в массив или объект
     * @param $string
     * @param bool $as_array true - вернется ассоциаливный массив, false - объект
     * @throws \Exception
     * @return array|mixed
     */
    public function decode($string, $as_array = true)
    {
        $result = json_decode($string, $as_array);

        $err = json_last_error();
        switch ($err) {
            case JSON_ERROR_DEPTH:
                throw new \Exception('Максимальная глубина стека превысили');
            case JSON_ERROR_CTRL_CHAR:
                throw new \Exception('Неожиданный символ управления нашли');
            case JSON_ERROR_SYNTAX:
                throw new \Exception('Синтаксическая ошибка, неверный формат JSON');
            case JSON_ERROR_NONE:
                return $result;
        }
    }

    /**
     * Кодировка файла
     */
    public function prepareUTF8($matches)
    {
        return json_decode('"'.$matches[1].'"');
    }
	
}
