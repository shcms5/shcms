<?php
/**
 * Класс для работы с HTML формой
 * Данный класс считается устаревщим 
 * используйте formn.class.php
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
class form {
	public $form = ''; 
	public $act; 
	public $method; 
	public $formname; 
	public $hiden = array(); 
	public $formid; 
	public $legend; 
	public $enctype;
        
        /**
         * Объявляем класс и выводим начальную форму
         * <form action="" method="">
         * 
         * @param $act action="Путь"
         * @param $method method="Метов(POST/GET)"
         * @param $name name Название
         * @param $id id Индификатор формы
         * @param $hidden hidden Скрытые поля
         * @param $legendsd
         */
	function __construct($act, $method = '', $name = '',$enctype = '', $id = '', $hidden = '', $legend = '') {
		$this -> act = $act; 
		$this -> method = $method ? $method : 'post'; 
		$this -> name = $name ? $name : ''; 
		$this -> id = $id ? $id : ''; 
		$this -> hidden = $hidden ? $hidden : ''; 
		$this -> legend = $legend ? $legend : ''; 
		$this -> enctype = $enctype ? $enctype : '';
			$form = '<form '.$this->enctype.' action="' . $this->act . '" method="' . $this->method . '" '; 
		$form .= $this->id ? 'id="' . $this->id . '"  ' : '';  
		$form .= $this->name ? 'name="' . $this->name . '">' : '>'; 
		return $this -> form = $form . PHP_EOL; 
 	} 
	
	 
	/**
         * Получение input
         * <input type="param" name="name" id="id">
         * 
         * @param $text Название поля
         * @param $name Значимое название 
         * @param $type Тип поля (text,password,number)
         * @param $value Первоначальное значение
         * @param $post Названия поля с конц
         * @param $size Количество символов
         * @param $br true вниз false оставляет как есть
         * @param $max мах значение
         * @param $names 
         * @param $readonly 0 - 1
         */
	function input($text = '', $name, $type = 'text', $value = '',$post = '', $size = 20,$br = true, $max = '', $names = '', $readonly = 0,$radio_id = false,$value2 = false) { 

		$form = ''; 
		$form .= $text ?   $text  .'<br/>' : ''; 
		$form .= '<input type="' . $type . '" name="' . $name . '" id="'.$radio_id.'" value="' . $value . '" '.$value2.' size="' . $size . '" '; 
		$form .= $readonly ? 'readonly="readonly" ' : ''; 
		$form.= $max ? 'maxlength="' . $max . '" />'.($br ? '<br />' : null).'' : '/>  '.$names.''.($br ? '<br />' : null).''; 
		$form .= $post ?  $post : '';
		return $this->form .= PHP_EOL . $form . PHP_EOL; 
	}  
	/**
         * Получение input Расширение
         * <input type="param" name="name">
         * 
         * @param $text Название поля
         * @param $name Значимое название 
         * @param $type Тип поля
         * @param $value Первоначальное значение
         * @param $value2 Дополнительный параметр
         * @param $names Название с конца
         * @param $br = true
         */
	function input2($text = '', $name, $type = 'text',$value = '',$value2 = '',$names = '',$br = true) {
	
		$form = ''; 
		$form .= $text ?   $text  .'<br/>' : ''; 	
		$form .= '<input  type="' . $type . '" name="' . $name . '" value="' . $value . '"' . $value2 . '/>&nbsp;'.$names.'' .($br ? '<br />' : null); 	
        
        return $this->form .= PHP_EOL . $form . PHP_EOL; 		
	}
	/**
         * Получение input3 Mini-Расширение
         * <input type="param" name="name">
         * 
         * @param $text Название поля
         * @param $name Значимое название 
         * @param $type Тип поля
         * @param $value Первоначальное значение
         * @param $value2 Дополнительный параметр
         * @param $names Название с конца
         */	
	function input3($text = '', $name, $type = 'text',$value = '',$value2 = '',$names = '') {
	
		$form = ''; 
		$form .= $text ?   $text  .'' : ''; 	
		$form .= '<input  type="' . $type . '" name="' . $name . '" value="' . $value . '"' . $value2 . '/>&nbsp;'.$names.''; 	
        
        return $this->form .= PHP_EOL . $form . PHP_EOL; 		
	}	
	/**
         * Текстовое поле без расширений
         * <texrarea name="name" id="false">value</texrare>
         * 
         * @param $text Название поля
         * @param $name Значение 
         * @param $value Описание
         * @param $post Название с конца
         * @param $readonly = 0:1
         */
	function textarea ($text = '', $name, $value = '',$post = '', $readonly = 0,$values2 = '',$id = false) { 
		$form = ''; 
		$form .= $text ? '' . $text . '<br/>' : ''; 
		$form .= '<textarea id="editor'.$id.'" class="lined '.$values2.'" name="' . $name . '"' . ($readonly ? 'readonly="readonly"' : '') . '>'; 
		$form .= $value ? $value : ''; 
		$form .= '</textarea><br/>'; 
		$form .= $post ?  $post : '';
		return $this->form .= PHP_EOL . $form . PHP_EOL; 
	} 
	/**
         * Текстовое поле без расширений
         * <texrarea name="name" id="false">value</texrare>
         * 
         * @param $text Название поля
         * @param $name Значение 
         * @param $value Описание
         * @param $post Название с конца
         * @param $readonly = 0:1
         */
	function textarea2 ($text = '', $name, $value = '',$post = '', $readonly = 0,$values2 = '') { 
		$form = ''; 
		$form .= $text ? '' . $text . '<br/>' : ''; 
		$form .= '<textarea class="lined '.$values2.'" name="' . $name . '"' . ($readonly ? 'readonly="readonly"' : '') . '>'; 
		$form .= $value ? $value : ''; 
		$form .= '</textarea><br/>'; 
		$form .= $post ?  $post : '';
		return $this->form .= PHP_EOL . $form . PHP_EOL; 
	}         
        

	/**
         * Поле Селекторов без Расщирений
         * <select name="name"><option value="num">desc</option></select>
         * 
         * 
         * @param $text = Название селектра
         * @param $name = Значение 
         * @param $param = параметр array()
         * @param $selected (0/1)
         * @param $optgroup Дополнитерный параметр
         * @param $optgroup2 Дополнительный параметр с конца
         * @param $style Стиль для Селекторов
         */
        function select ($text, $name, $param, $selected = 1,$optgroup = false, $optgroup2 = false,$style = false,$multi = false,$class = false) {
	
		if (!is_array($param)) { 
			$form = ''; 
		} else { 
			$form = ($text ? '' . $text . '<br/>' : null) . '<select '.$class.'  name="' . $name . '" '.$multi.'>' . PHP_EOL;
            $form .= $optgroup;			
			$x = 1; 
			foreach ($param as $k => $v) { 
				$form .= '<option '.$style.' value="' . $v . '" ' . ($x==$selected ? 'selected="selected"' : '') . '>' . $k . '</option>' . PHP_EOL; 
				$x ++; 
			} 
			$form .= $optgroup2;	
			$form .= '</select><br/>'; 
		} 
		return $this->form .= $form . PHP_EOL; 
	}  
	
        /**
         * Объявление любого текст
         * name = Вывод любых значений
         * 
         * @param $text  Тексты поля разные
         */
	public function text($text) {
	    $form = $text;
		return $this->form .= $form . PHP_EOL; 	    
	}
        
        /**
         * Вывод селекторов через базу 
         * <select name="name"><option value="num">desc</option></select>
         * 
         * @param $text Название поля
         * @param $name Значение
         * @param $select Какие поля выводить (*) все
         * @param $from Название таблицы
         * @param $value Скрытое значение value
         * @param $option Текст значение
         */
	public function select2($text,$name,$select,$from,$value,$option,$style = false) {
	global $db;
	    
            $form = ($text ? $text . '<br/>' : null) . '<select '.$style.' name="' . $name . '">' . PHP_EOL; 
            $selecting = $db->query("SELECT $select FROM `$from`");
                
            while($selects = $db->get_array($selecting)) {
                    $form.= '<option value="'.$selects[$value].'">'.$selects[$option].'</option>';
            }
            
                $form.= '</select><br/>';
		return $this->form .= $form . PHP_EOL; 
	}	
	
        /**
         * Работа с input radio
         * 
         * @param $label Название
         * @param $name Значение 
         * @param $values Описание 
         */
	public function html_radio($label, $name, $values){
            
            $form = $label.'<br />';
            foreach($values as $key => $value){
                    $form .= '<input type="radio" name="'.$name.'" value="'.$key.'"><label for="xforward_matching_yes">'.$value.'</label>&nbsp;&nbsp;';
            }
  
	    return $this->form .= $form . PHP_EOL; 
        }
        /**
         * Работа с кнопкой input_submit
         * 
         * @param $value Имя кнопки
         * @param $name Значение кнопки
         * @param $br true
         * @param $class CSS Класс для кнопки
         */
	public function submit ($value = 'Отправить',$name = 'submit', $br = false,$class = 'Button') { 
		$form = '<input  class="'.$class.'" type="submit" name="' . $name . '" value="' . $value . '" />' .($br ? '<br />' : null); 
				return $this->form .= $form . PHP_EOL; 

	}
	
        /**
         * Объявляем полные данные полей 
         * 
         * @return string
         */
        public function display() {
		echo  $this -> form .  '</form>';
        }
}