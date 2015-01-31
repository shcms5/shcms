<?php
/**
 * Класс для резервных копирований базы данных
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */

define('MSB_NL', "\r\n");
define('MSB_STRING', 0);
define('MSB_DOWNLOAD', 1);
define('MSB_SAVE', 2);

class MySQL_Backup {

    var $characters = '';
    var $backup_dir = '';
    var $returnSQL = '';
    

    function Execute() 
	{
	    global $db;	
		
            return $this->_active();
			
    }

    function _active()
	{
	    global $db;
		
        $return = $this->_Query('SHOW TABLES');
		
            while($catch = $db->get_array($return)){
			
                $value[] = $catch['Tables_in_'.$this->database];
				
            }
			
            $this->_createTable($value);
             
		if($t12ake = $this->_SaveToFile())
		{
		
            echo $this->returnSQL;
			
        }
		
        else 
		{
		
            die(Lang::__('Не могу сохранить файл, не стоят права на папку.'));
        
		}
 
    }

    function _createTable($val) 
	{
	    global $db; 
		    
			foreach($val as $a){
            
			    @$create = $db->get_array($this->_Query("SHOW CREATE TABLE `{$a}`"));
                
				$this->returnSQL .= '
                --
                -- Структура таблицы `' . $a . '`;
                --'.MSB_NL;
				
                $this->returnSQL .= 'DROP TABLE IF EXISTS `' . $a . '`;'.MSB_NL;
                $this->returnSQL .= $create[1].';'.MSB_NL.MSB_NL.MSB_NL;
                $this->returnSQL .= $this->createInsert($a);
            
			}
    }

    function createInsert($table) 
	{
	    global $db;
		    
			$value = '';
            
			    if (!($result = $this->_Query( "SELECT * FROM  `{$table}`" ) ) ) 
				{
                    
					return false;
					
                }
				
            while ($row = $db->fetch_row($result))
			{
            
			    $values = '';
                    
					foreach ($row as $data){ 
                    
					    $values .= '\'' . addslashes($data) . '\', ';
                
				    }
					
                $values = substr($values, 0, -2);
                $value .= 'INSERT INTO ' . $table . ' VALUES (' . $values . ');' . MSB_NL;
				
            }
		
        return $value;
    
	}


    /**
     * Функция соединение запросов
     */
    function _Connect() 
	{
	
	    global $db;
		
            $db->query('SET NAMES '.$this->characters);
			
    }

    function _Query($sql)
	{
	
	    global $db;
            
			$result = $db->query($sql);
			
        return $result;
    
	}

	
    /**
     * Сохранение бэкапа на хосте
     */    
    function _SaveToFile()
	{ 
	
        global $db;
		
            $fname = $this->backup_dir.''.time().'_'.DBNAME.'.sql';
            
			    if (!($f = fopen($fname, 'w+')))
                    return false;
 
                    fwrite($f, $this->returnSQL);
                    fclose($f);
					
                    $this->_DownloadFile($fname);
					
        return $fname;
		
    }

    /**
     * Скачивание бэкапа
     * 
     * @param $fname Название таблицы
     */
    function _DownloadFile($fname) 
	{
	
            echo engine::success(Lang::__('Бекап сайта успешно сохранено в папке sql/'));
            echo engine::home(array(Lang::__('Назад'),'index.php?do=sql&act=backup'));		
            
			exit;
			
        return true;
		
    }

}
