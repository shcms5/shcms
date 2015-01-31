<?php
/**
 * Класс для работы с пользователями
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
class user {

    /**
     * Выдаем пользователю баллы за активность
     * 
     * @return string
     */
    public static function points(){
        global $users,$db,$id_user;
        
        $db->query("UPDATE `users` SET `points` = '".($users['points']+1)."' WHERE `id` = '{$id_user}'");
    }
    /**
     * Понятно использование группы пользователей
     * 
     * @param $id Индификатор пользователя
     */
    public static function group($id){
        global $db;
        $guser = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '{$id}'"));
            
            if($guser['group'] == 15) {
                $groups  = '<font color="green">Администратор</font>';
            }else{
                $groups = '<font color="red">Пользователь</font>';
            }
        return $groups;    
    }
    /**
     * Получение данных сразу нескольких пользователей и нескольних полей*
     * @param array $id_user - Индефикатор, $data - Данные которых нужно выводить
     * @param $data  = array('nick','id','email',и.т.д) Через запятую выводим поля
     */
    public static function users($id_user,$data,$icon = false) {
	global $db;
            //Если существует пользователь
	    if($id_user == true) {
                //Выводим данные по пользователю
                $query = $db->query("SELECT * FROM `users` WHERE `id` = '".$id_user."' OR `nick` = '".$id_user."'");
                    while( $user_data = $db->get_array( $query ) ) {
			foreach ( $data as $key ){
                            //Если выдает ник то выводим ее
 			    if( $key == 'nick' ){
                                //Если с иконкой то выводим
				if( $icon == true ) {
                                    //Пол не указан
				    if($user_data['pol'] == 1) {
					$glob_user = '<img title="Не определился пол" src="/engine/template/icons/user/1.png">&nbsp;';
                                    //Мужской пол    
                                    }elseif($user_data['pol'] == 2){
					$glob_user = '<img title="Мужской пол" src="/engine/template/icons/user/2.png">&nbsp;';
                                    //Женский пол
                                    }elseif($user_data['pol'] == 3) {
					$glob_user = '<img title="Женский пол" src="/engine/template/icons/user/3.png">&nbsp;';
                                        
                                    }
				}	
				$glob_user .= $user_data['nick'];
			    			
			    }elseif($key == 'id') {
			    	$glob_user .= $user_data['id'];
				    		
			    }elseif($key == 'group'){
				$glob_user .= $user_data['group'];
			    }elseif($key == 'time') {
				$glob_user .= $user_data['time'];
			    }elseif($key == 'war_balls') {
				$glob_user .= $user_data['war_balls'];
			    }
			$x ++; 
			} 
				  
        	        return $glob_user;
                    }
			
	    }else {
		foreach ($data as $key) { 
		    if( $key == 'nick' ){
			$glob_user = 'Системный бот';
		    } elseif( $key == 'id' ) {
			$glob_user .= 0;
		    }
			$x ++; 
		} 
		//Вывод
        	return $glob_user;
	    }
    }
	
    /**
     * Получить возраст по дате рождения
     * 
     * @param $y, $m, $d дата рождения в формате 'YYYY-MM-DD'
     * @return int
     */
    public static function age($y, $m, $d)
    {
   if($m > date('m') || $m == date('m') && $d > date('d'))
      return (date('Y') - $y - 1).' лет'; // если ДР в этом году не было, то ещё -1
    else
      return (date('Y') - $y).' лет'; // если ДР в этом году был, то отнимаем от этого года год рождения
  }
 
    /**
     *  Условие отображение даты и времени
     * 
     * @param $realtime
     */
    public static function plural($n, $plurals) {
	$plural =
	    ($n % 10 == 1 && $n % 100 != 11 ? 0 :
            ($n % 10 >= 2 && $n % 10 <= 4 &&
	    ($n % 100 < 10 or $n % 100 >= 20) ? 1 : 2));
	    
       return $plurals[$plural];
    }

    /**
     * Человеское отображение даты и времени
     * 
     * @var plural
     */
    public static function realtime($dt, $precision = 2) 
    {
	
	    $times = array(
		    365*24*60*60    =>  array("год", "года", "лет"),
	    	30*24*60*60     =>  array("месяц", "месяца", "месяцев"),
	    	7*24*60*60      =>  array("неделю", "недели", "недель"),
	    	24*60*60        =>  array("день", "дня", "дней"),
	    	60*60           =>  array("час", "часа", "часов"),
	    	60              =>  array("минуту", "минуты", "минут"),
	    );

	    $diff = time() - $dt;
			
			if($diff < 3)  $output = '';
                elseif($diff < 30)  $output = 'только что'; 
	            elseif($diff < 60)	$output = 'меньше минуты';
	        else {
		    $output = array();
		    $exit = 0;
                        foreach($times as $period => $name) {
                            if( $exit >= $precision || ($exit > 0 && $period < 60 ) ) break;
				$result = floor($diff / $period);
                            if ( $result > 0 ){
				if($result == 1) $output[] = user::plural($result, $name);
				else $output[] = $result . ' ' . user::plural($result, $name);
                                    $diff -= $result * $period;
				    $exit++;
			    } else if ($exit > 0) $exit++;
		        }
		    if ($precision < 3) $sep = " и "; else $sep = ", ";
                        $output = implode($sep, $output);
	        
		}
	return $output;	
    }
 	
	/**
	 * Неудачные попытки авторизации
         * 
	*/
	public static function limit_auth( $glob ) 
	{
	    global $db;
			
	    $auth = $db->query("SELECT * FROM `users`");
		if( $db->num_rows( $auth ) > 1 ) {
		    while( $aut = $db->get_array( $auth ) ) {
			if( $aut['limit_auth'] >= $glob ) {
			    if(time() > $aut['limit_time']) {
                                $db->query("UPDATE `users` SET `limit_auth` = '0' ,`limit_time` = '0' WHERE `id` = '".$aut['id']."'");
			    } 
			}
		   }
		} 
        } 
    
	/**
	 * Проверка на доступность пользователя
	 */
	public static function userd ($var) 
	{
	    global $db; 
		
		$vars = $db->super_query( "SELECT `id` FROM `users` WHERE `id` = '{$id}'" );
		if($db->num_rows($vars) != $var ){
		    echo engine::error(Lang::__('Данных пользователь не существует'));
		    echo engine::home( array( Lang::__('Назад') , 'index.php' ) );
		    exit;
		}

	} 
 
}
