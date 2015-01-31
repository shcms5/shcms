<?php
/**
 * SQL Класс для Отправки запросов в базу
 * 
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
class sql_show
{
    public $sql_show = array();

    function __construct(){
	        global $db;
        $tab = $db->query('SHOW TABLES');
            while ($table = $db->get_array($tab)) {
                $this->sql_show[] = $table[0];
            }
        }
    /**
     * Отправка запроса в базу
     * 
     * @param $table Таблица для вставки
     */    
    function get_data($table, $c_ins = 2000){
	global $db;
        $sql = '';
        $num_row_all = $db->get_array($db->query("SELECT COUNT(*) FROM `" . $db->safesql($table) . "`"));
        $start = 0;

        if ($num_row_all[0]) {
            $sql .= "/* Данные таблицы `$table` */\r\n";
            $table_keys = @implode("`, `", @array_keys($db->get_array($db->query("SELECT * FROM `" . $db->safesql($table) . "` LIMIT 1"))));
            while ($start < $num_row_all[0]) {
                $res = $db->query("SELECT * FROM `$table` LIMIT " . $start . ", " . $c_ins);

                if ($num_row_all[0] > $c_ins) $sql .= "/* блок записей $start - " . ($start + $c_ins) . " */\r\n";

                $sql .= "INSERT INTO `" . $db->safesql($table) . "` (`$table_keys`) VALUES \r\n";
                $num_row = $db->num_rows($res);
                $counter = 0;
                while (($row = @$db->get_array($res))) {
                    $values = @array_values($row);

                    foreach ($values as $k => $v) {
                        $values[$k] = "'" . preg_replace("#(\n|\r)+#", '\n', $db->safesql($v)) . "'";
                    }
                    $values_string = @implode(', ', $values);
                    $counter++;
                    $sql .= "($values_string)" . ($counter == $num_row ? ";\r\n" : ", \r\n");
                }
                $start = $start + $c_ins;
            }
        } else $sql .= "/* Таблица `$table` пуста */\r\n";

        return $sql;
    }

    /**
     * Сохряняем запрос
     */
    function save_data($path, $table, $c_ins = 2000)
    {
	global $db;
        return @file_put_contents($path, $this->get_data($table, $c_ins));
    }
}