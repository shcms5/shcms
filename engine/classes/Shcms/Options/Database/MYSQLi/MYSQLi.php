<?php
/**
 * Библиотека для работы с Расширенной базой MYSQLi
 * 
 * 
 * @package      Classes
 * @author       Shamsik
 * @link         http://shcms.ru
 */
namespace Shcms\Options\Database\MYSQLi;

class MYSQLi extends \Exception implements MYSQLiInterface
{
	var $db_id = false;
	var $query_num = 0;
	var $query_list = array();
	var $mysql_error = '';
	var $mysql_version = '';
	var $mysql_error_num = 0;
	var $mysql_extend = "MySQLi";
	var $MySQL_time_taken = 0;
	var $query_id = false;

	/**
         * Соединение с базой данных
         * 
         * @param $db_user Имя пользователя для базы
         * @param $db_pass Пароль для соединение с базой
         * @param $db_name Название для базы
         * @param $db_localhost Хост для базы (по умолчанию Localhost)
         * @param $show_error 1
         */
	public function connect($db_user, $db_pass, $db_name, $db_location = 'localhost', $show_error=1)
	{
		$db_location = explode(":", $db_location);

		if (isset($db_location[1])) {

			$this->db_id = @mysqli_connect($db_location[0], $db_user, $db_pass, $db_name, $db_location[1]);

		} else {

			$this->db_id = @mysqli_connect($db_location[0], $db_user, $db_pass, $db_name);

		}

		if(!$this->db_id) {
			if($show_error == 1) {
				$this->display_error(mysqli_connect_error(), '1');
			} else {
				return false;
			}
		} 

		$this->mysql_version = mysqli_get_server_info($this->db_id);

		if(!defined('COLLATE'))
		{ 
			define ("COLLATE", "utf-8");
		}

		mysqli_query($this->db_id, "SET NAMES '" . COLLATE . "'");

		return true;
	}
	
        /**
         * Вывод таблиц из базы
         * mysqli_query()
         * 
         * @param $query Параметры для вывода
         * @param $show_error
         */
	public function query($query, $show_error=true)
	{
		$time_before = $this->get_real_time();

		if(!$this->db_id) $this->connect(DBUSER, DBPASS, DBNAME, DBHOST);

		if(!($this->query_id = mysqli_query($this->db_id, $query) )) {

			$this->mysql_error = mysqli_error($this->db_id);
			$this->mysql_error_num = mysqli_errno($this->db_id);

			if($show_error) {
				$this->display_error($this->mysql_error, $this->mysql_error_num, $query);
			}
		}
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;
		
//			$this->query_list[] = array( 'time'  => ($this->get_real_time() - $time_before), 
//										 'query' => $query,
//										 'num'   => (count($this->query_list) + 1));
		
		$this->query_num ++;

		return $this->query_id;
	}
        
	/**
         * Возвращает ряд результата запроса в качестве ассоциативного массива
         * mysqli_fetch_assoc()
         * 
         * @param $query_id
         */
	public function get_row($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysqli_fetch_assoc($query_id);
	}
        
	/**
         * Возвращает число затронутых прошлой операцией рядов
         * mysqli_affected_rows()
         * 
         * @return string
         */        
	public function get_affected_rows()
	{
		return mysqli_affected_rows($this->db_id);
	}
        
	/**
         * Обрабатывает ряд результата запроса, возвращая ассоциативный массив, 
         * численный массив или оба
         * mysqli_fetch_array()
         * 
         * @param $query_id
         */    
	public function get_array($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysqli_fetch_array($query_id);
	}
        
	/**
         * Обрабатывает ряд результата запроса и возвращает массив 
         * с числовыми индексами
         * mysqli_fetch_row()
         * 
         * @param $query_id
         */          
	public function fetch_row($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysqli_fetch_row($query_id);
	}	
	
	/**
         * Возвращает информацию о сервере MySQL
         * mysqli_get_server_info()
         * 
         * @return string
         */ 
	public function get_server_info() {
	    return mysqli_get_server_info($this->db_id);
	}
        
	/**
         * Возвращает информацию о соединении с MySQL
         * mysqli_get_host_info()
         * 
         * @return string
         */         
	public function get_host_info() {
	    return mysqli_get_host_info($this->db_id);
	}	
	
        /**
         * Возвращает данные по запросу
         * super_query($query)
         * 
         * @param $query Запрос для подключение таблицы
         * @param $multi = false
         */
	public function super_query($query, $multi = false)
	{

		if(!$multi) {

			$this->query($query);
			$data = $this->get_row();
			$this->free();			
			return $data;

		} else {
			$this->query($query);
			
			$rows = array();
			while($row = $this->get_row()) {
				$rows[] = $row;
			}

			$this->free();			

			return $rows;
		}
	}
        
	/**
         * Возвращает количество рядов результата запроса
         * mysqli_num_rows()
         * 
         * @param $query_id
         */
	public function num_rows($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysqli_num_rows($query_id);
	}
        
	/**
         * Возвращает текущую строку результирующего набора в виде объекта
         * mysqli_fetch_object()
         * 
         * @param $query_id
         */
	public function fetch_object($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysqli_fetch_object($query_id);
	}
                
        
	/**
         * Возвращает идентификатор, сгенерированный при последнем INSERT-запросе
         * mysqli_insert_id()
         * 
         * @return string
         */ 	
	public function insert_id()
	{
		return mysqli_insert_id($this->db_id);
	}
        
	/**
         * Возвращает информацию о колонке из результата запроса в виде объекта
         * mysqli_fetch_field()
         * 
         * @param $query_id
         */ 
	public function get_result_fields($query_id = '') {

		if ($query_id == '') $query_id = $this->query_id;

		while ($field = mysqli_fetch_field($query_id))
		{
            $fields[] = $field;
		}
		
		return $fields;
   	}
        
	/**
         * Экранирует специальные символы в строках для использования в выражениях SQL
         * mysqli_real_escape_string()
         * 
         * @param $source Текст который будет экранирован
         */ 
	public function safesql($source)
	{
		if(!$this->db_id) $this->connect(DBUSER, DBPASS, DBNAME, DBHOST);

		if ($this->db_id) return mysqli_real_escape_string ($this->db_id, $source);
		else return addslashes($source);
	}
        
	/**
         * Проверяет соединение с сервером и пересоединяется при необходимости
         * mysqli_ping()
         * 
         * @return string
         */ 
	public function ping() {
            return mysqli_ping($this->db_id);
	}
        
	/**
         * Возвращает текст ошибки последней операции с MySQL
         * mysqli_error()
         * 
         * @return string
         */ 	
	function error_sql() {
	    return mysqli_error($this->db_id);
	}

	/**
         * Освобождает память от результата запроса
         * mysqli_free_result()
         * 
         * @param $query_id
         */ 	        
	public function free($query_id = '') {

		if ($query_id == '') $query_id = $this->query_id;

		    mysqli_free_result($query_id);
	}
        
        
        
	/**
         * Закрывает соединение с сервером MySQL
         * mysqli_close()
         * 
         * @return string
         */ 
	public function close() {
		mysqli_close($this->db_id);
	}
        
	/**
         * Обработка времени
         * 
         * @return string
         */ 
        
	public function get_real_time()
	{
		list($seconds, $microSeconds) = explode(' ', microtime());
		return ((float)$seconds + (float)$microSeconds);
	}	
        
	/**
         * Если в таблице есть ошибки то выполнится данное функция
         * 
         * @param $error Текст ошибки
         * @param $error_num Номер строки
         * @param $query И сам запрос
         */ 
	public function display_error($error, $error_num, $query = '')
	{
		if($query) {
			// Safify query
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query); // Hides all hashes
		}

		$query = htmlspecialchars($query, ENT_QUOTES, 'ISO-8859-1');
		$error = htmlspecialchars($error, ENT_QUOTES, 'ISO-8859-1');

		$trace = debug_backtrace();

		$level = 0;
		if ($trace[1]['function'] == "query" ) $level = 1;
		if ($trace[2]['function'] == "super_query" ) $level = 2;

		$trace[$level]['file'] = str_replace(ROOT_DIR, "", $trace[$level]['file']);

		echo <<<HTML
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ошибка MYSQL</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<style type="text/css">
<!--
body {
	font-family: Tahoma, sans-serif;
	font-size: 11px;
	font-style: normal;
	color: #000000;
}
.top {
  color: #ffffff;
  font-size: 15px;
  font-weight: bold;
  padding-left: 20px;
  padding-top: 10px;
  padding-bottom: 10px;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
  background-color: #AB2B2D;
  background-image: -moz-linear-gradient(top, #87CEEB, #1E90FF);
  background-image: -ms-linear-gradient(top, #87CEEB, #1E90FF);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#87CEEB), to(#1E90FF));
  background-image: -webkit-linear-gradient(top, #87CEEB, #1E90FF);
  background-image: -o-linear-gradient(top, #87CEEB, #1E90FF);
  background-image: linear-gradient(top, #87CEEB, #1E90FF);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#87CEEB', endColorstr='#1E90FF',GradientType=0 ); 
  background-repeat: repeat-x;
  border-bottom: 1px solid #ffffff;
}
.box {
	margin: 10px;
	padding: 4px;
	background-color: #EFEDED;
	border: 1px solid #DEDCDC;

}
-->
</style>
</head>
<body>
	<div style="width: 630px;margin: 20px; border: 1px solid #D9D9D9;-moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; -moz-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3); -webkit-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3); box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);" >
		<div class="top" >Ошибка MYSQL!</div>
		<div class="box" ><b>Ошибка MYSQL</b><br/> Файл ошибки: <b>{$trace[$level]['file']}</b><br/> Линия: <b>{$trace[$level]['line']}</b></div>
		<div class="box" >Произошла ошибка в номере: <b>{$error_num}</b></div>
		<div class="box" >Обнаруженная ошибка:<br /> <b>{$error}</b></div>
		<div class="box" ><b>Запрос (SQL):</b><br />{$query}</div>
		</div>		
</body>
</html>
HTML;
		
	exit();
    }

}