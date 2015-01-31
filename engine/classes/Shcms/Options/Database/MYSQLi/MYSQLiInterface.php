<?php
/**
 * Класс MYSQLi Интерфейса
 * 
 * 
 * @package      Classes
 * @author       Shamsik
 * @link         http://shcms.ru
 */
namespace Shcms\Options\Database\MYSQLi;

interface MYSQLiInterface {
    
    /** @var Соединение с базой данных */
    public function connect($db_user, $db_pass, $db_name, $db_location = 'localhost', $show_error=1);
    /** @var Данные о выводе таблиц */    
    public function query($query, $show_error=true);
    /** @var Получаем Ассоцированный массив  */    
    public function get_row($query_id = '');
    /** @var Получаем число ранних операций */    
    public function get_affected_rows();
    /** @var Получение результата о выводе данных */    
    public function get_array($query_id = '');
    /** @var Обрабатываем и получаем массив */    
    public function fetch_row($query_id = '');
    /** @var Получение информации о сервере */        
    public function get_server_info();
    /** @var Получаем информацию о соединение с сервером */        
    public function get_host_info();
    /** @var Получение массивного запроса */        
    public function super_query($query, $multi = false);
    /** @var Получение кол. рядом запроса */        
    public function num_rows($query_id = '');
    /** @var Получение строки результат */        
    public function fetch_object($query_id = '');
    /** @var Получение последнего индификатора */        
    public function insert_id();
    /** @var Получаем результат о колонке запроса */        
    public function get_result_fields($query_id = '');
    /** @var Экранирование спец. символов */        
    public function safesql($source);    
    /** @var Проверка соединение с сервером */     
    public function ping();
    /** @var Возвращает ошибку об отправленной mysql операции */     
    public function error_sql();
    /** @var Освобождает память от результатов */     
    public function free($query_id = '');    
    /** @var Закрываем соединение */     
    public function close();
    /** @var Обработка Базовой Времени */     
    public function get_real_time();
    /** @var Получение ошибок при запросах */     
    public function display_error($error, $error_num, $query = '');   
    
    
}