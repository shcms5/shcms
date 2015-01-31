<?php
 /**
  * Класс для работы с cтроками
  * 
  * @package Classes
  * @author Shamsik
  * @link http://shcms.ru
  */
namespace Shcms\Component\String;

class StringProcess {
    
    /** @var: Кодировка UTF-8 */
    protected $encoding = 'UTF-8';

    /**
     * Приведение строки к нижнему регистру
     *
     * @param   string  $str       required
     * @param   string  $encoding  default UTF-8
     * @return  string
    */
    public static function lower($str, $encoding = null) {
	$encoding or $encoding = $this->encoding;

	return function_exists('mb_strtolower')
		? mb_strtolower($str, $encoding)
		: strtolower($str);
    }

    /**
     * Приведение строки к верхнему регистру
     *
     * @param   string  $str       required
     * @param   string  $encoding  default UTF-8
     * @return  string
    */
    public static function upper($str, $encoding = null) {
        $encoding or $encoding = $this->encoding;

	return function_exists('mb_strtoupper')
		? mb_strtoupper($str, $encoding)
		: strtoupper($str);
    }

    /**
     * Преобразует первый символ строки в нижний регистр
     *
     * @param   string  $str       required
     * @param   string  $encoding  default UTF-8
     * @return  string
    */
    public static function lcfirst($str, $encoding = null) {
	$encoding or $encoding = $this->encoding;

	return function_exists('mb_strtolower')
		? mb_strtolower(mb_substr($str, 0, 1, $encoding), $encoding).
			mb_substr($str, 1, mb_strlen($str, $encoding), $encoding)
		: lcfirst($str);
    }

    /**
     * Преобразует первый символ строки в верхний регистр
     *
     * @param   string  $str       required
     * @param   string  $encoding  default UTF-8
     * @return  string
    */
    public static function ucfirst($str, $encoding = null) { 
        $encoding or $encoding = $this->encoding;
        
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($str)) { 
            $str = mb_strtolower($str, $encoding); 
            $upper = mb_strtoupper($str, $encoding); 
            preg_match('#(.)#us', $upper, $matches); 
            $str = $matches[1] . mb_substr($str, 1, mb_strlen($str, $encoding), $encoding); 
        } else { 
            $str = ucfirst($str); 
        } 
        return $str; 
    } 

    /**
     * Разбивает строку на части с использованием explode()
     *
     * @param   string  $str       required
     * @param   string  $encoding  default UTF-8
     * @return  string
    */    
    public static function stringparts($parts,$times){
      $chunks = explode(",", $parts, $times);
      
          for($i = 0; $i < count($chunks); $i++){
            return "$i = $chunks[$i] <br />";
        }
    }

    /**
     * Умная обрезка строки
     * 
     * @param string $str - исходная строка
     * @param int $lenght - желаемая длина результирующей строки
     * @param string $end - завершение длинной строки
     * @param string $charset - кодировка
     * @param string $token - символ усечения
     * @return string - обрезанная строка
     * 
     */	
	public static function sub($str, $start, $length = null, $encoding = null)
	{
		$encoding or $encoding = $this->encoding;

		// substr functions don't parse null correctly
		$length = is_null($length) ? (function_exists('mb_substr') ? mb_strlen($str, $encoding) : strlen($str)) - $start : $length;

		return function_exists('mb_substr')
			? mb_substr($str, $start, $length, $encoding)
			: substr($str, $start, $length);
	}   
}