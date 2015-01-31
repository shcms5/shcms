<?php
/**
 * Класс для работы с 
 * Подключением интерфейсных файлов CSS , JS
 * 
 * @package Classes 
 * @author Shamsik
 * @link http://shcms.ru
 */
namespace Shcms\Component\Templating\Path;

class Assets {
	
	/**
	 * @var шаблоны массив активов
	 */
	protected static $templates = array (
		'js'  => '<script src="%s" type="text/javascript"></script>',
		'css' => '<link href="%s" rel="stylesheet" type="text/css"/>'
	);
	
	/**
	 * Общие шаблоны для активов.
	 *
	 * @param string|array $files
	 * @param string       $template
	 */
	protected static function resource ($files, $template) {
		$template = self::$templates[$template];
		
		if (is_array($files)) {

			foreach ($files as $file) {
				echo sprintf($template, $file) . "\n";
			}

		} else {
			echo sprintf($template, $files) . "\n";
		}
	}
	
	/**
	 * Вывод сценарий
	 * 
	 * @param array|string $file
	 */
	public static function js ($files) {
		static::resource($files, 'js');
	}
	
	/**
	 * Вывод стилей
	 * 
	 * @param string $file
	 */
	public static function css ($files) {
		static::resource($files, 'css');
	}

}