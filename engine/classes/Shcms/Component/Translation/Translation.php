<?php
/**
 * Класс для Перевода строк
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
namespace Shcms\Component\Translation;

class Translation {

	public $map;
	public $charset;
	
	function __construct() {
	
		$this->map = array( 'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'з' => 'z', 'и' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'x', 'ы' => 'y', 'э' => 'e', 'А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'e', 'З' => 'z', 'И' => 'i', 'Й' => 'j', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u', 'Ф' => 'f', 'Х' => 'x', 'Ы' => 'y', 'Э' => 'e', 'ж' => 'zh', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ъ' => '', 'ю' => 'yu', 'я' => 'ya', 'Ж' => 'zh', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch', 'Ь' => '', 'Ъ' => '', 'Ю' => 'yu', 'Я' => 'ya', 'ї' => 'i', 'Ї' => 'i', 'ґ' => 'g', 'Ґ' => 'g', 'є' => 'е', 'Є' => 'е', 'І' => 'i', 'і' => 'i');
		
		$this->charset = 'utf-8';
		
	}
	
	function chicken_dick($chicken, $dick = '/'){

		$chicken = preg_replace('/^(['.preg_quote($dick, '/').']+)/', '', $chicken);
		$chicken = preg_replace('/(['.preg_quote($dick, '/').']+)/', $dick, $chicken);
		$chicken = preg_replace('/(['.preg_quote($dick, '/').']+)$/', '', $chicken);

		return $chicken;
	}
	
	function replace($text, $that = '-'){
        //Необязательный параметр включить в особых случаях
		#$text = preg_replace ("/[^a-zA-Zа-яА-Я0-9-_.]/","",$text);
		$text = mb_strtolower (strip_tags(html_entity_decode($text)), $this->charset);
		$text = strtr($text, $this->map);
		$text = preg_replace('/\W/', $that, strip_tags($text));
		$text = $this->chicken_dick($text, $that);

		return $text;
	}
}
