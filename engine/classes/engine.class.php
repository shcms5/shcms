<?php
 /**
  * Основной класс для работы с SHCMS Engine
  * Все обязательные параметры находятся тут
  * 
  * @package Classes
  * @author Shamsik
  * @link http://shcms.ru
  */
class engine {

     /**
      * Проверка версии PHP 
      * 
      */
    public static function php_version() {
        version_compare(PHP_VERSION, '5.4', '>=') or die('Для запуска движка испольуйте версию PHP 5.4 и выше');
    }
    /**
     * Фильтруем строки 
     * 
     * @param type $list
     * @return type
     */
    public static function inspect($list) {
	if (function_exists('iconv')) {
            $list = iconv("UTF-8", "UTF-8", $list);
        }
            $list= preg_replace('/[^\P{C}\n]+/u', '', $list);
        
        return trim($list);
    }
	
    /**
     * Получаем ICQ 
     * 
     * @param $icq
     */
    public static function icq($icq) {
	    if (preg_match('#[0-9]{5,9}#', $icq, $str)) {
            return $str[0];
        } else {
            return NULL;
        }
    }
    
    /**
     * Библиотека Angular
     * 
     * @param Null
     */    
    public static function AngularJS() {
        
        $tassets = new Shcms\Component\Templating\Path\Assets;
        return $tassets->js(array(
            '/engine/template/module/AngularJS/angular.min.js',
            '/engine/template/module/AngularJS/angular-animate.min.js',
            '/engine/template/module/AngularJS/angular-aria.min.js',
            '/engine/template/module/AngularJS/angular-cookies.min.js',
            '/engine/template/module/AngularJS/angular-loader.min.js',
            '/engine/template/module/AngularJS/angular-messages.min.js',  
            '/engine/template/module/AngularJS/angular-resource.min.js',
            '/engine/template/module/AngularJS/angular-route.min.js',
            '/engine/template/module/AngularJS/angular-sanitize.min.js',
            '/engine/template/module/AngularJS/angular-touch.min.js',
            '/engine/template/module/AngularJS/shcms_angular.js'         
        ));
    }
    
    /**
     * Плеер Video
     * 
     * @param CSS JS
     */
    public static function VideoJS() {
        $videojs = new \Shcms\Component\Templating\Path\Assets;
        
        $return = $videojs->js([
            '/engine/template/module/html5/video/video.js'
        ]);
        
        $return .= $videojs->css([
            '/engine/template/module/html5/video/video-js.css'
        ]);
        
        return $return;
    }

    /**
     * Плеер Audio
     * 
     * @param CSS JS
     */
    public static function AudioJS() {
        $audiojs = new \Shcms\Component\Templating\Path\Assets;
        
        $return = $audiojs->js([
            '/engine/template/module/html5/audio/audiojs/audio.min.js'
        ]);

        return $return;
    }    
    
    /**
     * Кодировка файлов
     * @param $fng
     * @return mb_convert_encoding()
     */
    public static function file_get_contents_utf8($fn) { 
     $content = file_get_contents($fn); 
	 
      return mb_convert_encoding($content, 'UTF-8', 
          mb_detect_encoding($content, 'UTF-8, Windows-1251', true)); 
    } 
	
	
    public static function admin($number) {
	
	if($number != 15) {
	    $admin = engine::error('У вас нет прав администратора!');
	    exit;
	}
		
	return $admin;
    }
    /**
     * Выделение найденной части
     * 
     * @param $search
     * @param $text
     */
    public static function search_text($search, $txt) {
        $r = preg_split( '((>)|(<))', $txt, - 1, PREG_SPLIT_DELIM_CAPTURE );
            for($i = 0; $i < count( $r ); $i ++) {
                if( $r[$i] == "<" ) {
                    $i ++;
                    continue;
                }
                    $r[$i] = preg_replace( "#($search)#i", "<span style='background-color:yellow;'><font color='red'>\\1</font></span>", $r[$i] );
            }
        return join( "", $r );
    }
	
    /**
     * Статусы для пользователей
     * 
     * @param $currentPoints
     */	
    public static function getStatus($currentPoints){
 
        $statuses = array(
                   70 => 'Чаттер',    
                   40 => 'Активный',
                   10 => 'Писанин',
                   1 => 'Обычный',);
 
 
            foreach($statuses as $value=>$status){
                if($currentPoints>=$value){
                    return $status;
                }
            }
 
        return array_pop($statuses);
    }
	
    /**
     * Удаляем ссылки при добавлении
     * 
     * @param $str
     */	
    public static function removing($str) {
            
        $remov = $str;
        $remov = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $remov);
        
	return $remov;
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
    public static function substr($str, $lenght = 100, $end = '...', $charset = 'UTF-8', $token = '~') {
   		
	$str = strip_tags($str);
    	if (mb_strlen($str, $charset) >= $lenght) {
            $wrap = wordwrap($str, $lenght, $token);
            $str_cut = mb_substr($wrap, 0, mb_strpos($wrap, $token, 0, $charset), $charset);    
        	return $str_cut .= $end;
    		
	} else {
        	return $str;
    	}
    } 
	
    /**
     * Я хотел построить на том, что капут и колючий способствовали таким образом,
     * что я думаю, что это немного более понятным 
     * (например, пусть Sprintf делать всю двоичный преобразования и отступы,
     *  и пусть substr_compare сделать обрезку и сравнения):
     * 
     * @param $ip = Адрес
     * @param $net_addr = Действующий адрес
     * @param $net_mask = Маска
     */	
    public static function ip_in_network($ip, $net_addr, $net_mask){ 
        if($net_mask <= 0){ return false; } 
            $ip_binary_string = sprintf("%032b",ip2long($ip)); 
            $net_binary_string = sprintf("%032b",ip2long($net_addr)); 
        return (substr_compare($ip_binary_string,$net_binary_string,0,$net_mask) === 0); 
    } 
	
    /**
     * Включаем антиреклму
     * 
     * @param $str = Ссылка
     */
    public static function antilink($str){
        $str = preg_replace('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-z\.-]+\.[0-9a-z]{2,6}[0-9a-zA-Z/\?\.\~&amp;_=/%-:#]*)~', '[b]Ссылки запрещены[/b]', $str);
        $replace = array(
            '.ru' => '**',
            '.com' => '***',
            '.biz' => '***',
            '.cn' => '***',
            '.in' => '**',
            '.net' => '***',
            '.org' => '***',
            '.info' => '***',
            '.mobi' => '***',
            '.wen' => '***'
        );
        return strtr($str, $replace);
    }	
	
    /**
     * Функция проверки существования и доступности другой функции PHP
     * 
     * @param $function - строка имени функции
     * 
     */
    public static function function_enabled($function) {
        $function = strtolower(trim($function));
            if ($function == '') return false;
                // Получить список функций, отключенных в php.ini
                $disabled = explode(",",@ini_get("disable_functions"));
                    if (empty($disabled)) {
                        $disabled = array();
                }
            else {
                // Убрать пробелы и привести названия к нижнему регистру
                    $disabled = array_map('trim',array_map('strtolower',$disabled));
            }
        // Проверить доступность функции разными способами
        return (function_exists($function) && is_callable($function) &&
                !in_array($function,$disabled));
    }	
	
    /**
     * Используйте регулярные выражения для извлечения URL-адреса от произвольного содержания
     * 
     * @param string $content
     * @return array URL-адреса находятся в переданной строке
     */
    public static function xtract_urls( $content ) {
	preg_match_all("#((?:[\w-]+://?|[\w\d]+[.])[^\s()<>]+[.](?:\([\w\d]+\)|(?:[^`!()\[\]{};:'\".,<>?«»“”‘’\s]|(?:[:]\d+)?/?)+))#",
		$content,
		$post_links
	);
	$post_links = array_unique( array_map( 'html_entity_decode', $post_links[0] ) );

	return array_values( $post_links );
    }
	
    /**
     * Проверяем на существенность параметра $id
     * 
     * @param $id = Проверка на true
     */
    public static function nullid( $id ) {
	    if( empty( $id ) ) 
		{
		    $param  = engine::error(Lang::__('У вас отсутствуют важный параметр'),'<a href="">'.Lang::__('Назад').'</a>');	
			exit;
		} 
		
		return $param;
	
    }
	
	
    /**
     * Сгенерированный пароли для пользователей
     * 
     * @param $string
     * @param $options = array()
     */	
    public static function shgen($string,$options=array(
	    array('size' => 5, 'position' => 8 ),
	    array('size' => 6, 'position' => 0 ),
	    array('size' => 5, 'position' => 30 ),
	    array('size' => -1, 'position' => 20 ),
        ))
	{
	    $sha1 = sha1($string);
	    $md5 = md5($string);
	    $chunks = array();
	        foreach($options as $item){
		        if($item['size']==-1) $chunk=$md5;
		            else {
			            $chunk = substr($md5,0,$item['size']);
			            $md5 = substr_replace($md5,'',0,$item['size']);
		            }
		        $chunks[$item['position']]=$chunk;
	        }
	    $keys = array_keys($chunks);
	    sort($keys);
	    $delta = 0;
	        foreach($keys as $key){
		        $sha1 = substr($sha1,0,$key+$delta).$chunks[$key].substr($sha1,$key+$delta);
		         $delta+=strlen($chunks[$key]);
	        }
	    return sha1($sha1);
    }
    /**
     * Функция для удаление пробелов
     * 
     * @param $str = Источник 
     * @param $charlist = Список символов, которые будут обрезаны от источника
     * Стандартный метод trim() не работал пришлось написать самому
     */	
    public static function trim($str, $charlist = '') {
        $result = '';
        /* список запрещенных символов для подрезки */
        $forbidden_list = array(" ", "\t", "\r", "\n", "\0", "\x0B");
        
        if (empty($charlist)) {
            for ($i = 0; $i < strlen($str); $i++) {
                if (($str[$i] != $forbidden_list[0]) &&
                    ($str[$i] != $forbidden_list[1]) &&
                    ($str[$i] != $forbidden_list[2]) &&
                    ($str[$i] != $forbidden_list[3]) &&
                    ($str[$i] != $forbidden_list[4]) &&
                    ($str[$i] != $forbidden_list[5])) {
                    $result .= $str[$i];
                }
            }
        }
        else if (!empty($charlist)) {
            $is_not_same = true;
            
            for ($i = 0; $i < strlen($str); $i++) {
                for ($j = 0; $j < strlen($charlist); $j++) {
                    if ($str[$i] != $charlist[$j]) {
                        $is_not_same = true;
                    }
                    else if ($str[$i] == $charlist[$j]) {
                        $is_not_same = false;
                        break;
                    }
                }
                
                if ($is_not_same == true) {
                    $result .= $str[$i];
                }
            }
        }
        
        return ($result);
    }
    /**
     * Определение расположения по IP
     * 
     * @param $id в качестве параметра, и возвращает данные о расположении.
     * Если локация не будет обнаружена, то в ответ будет получен параметр unknown. 
     */
    public static function detect_city($ip) {

        $default = 'UNKNOWN';

        if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost')
            $ip = '8.8.8.8';

        $curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';

        $url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode($ip);
        $ch = curl_init();

        $curl_opt = array(
            CURLOPT_FOLLOWLOCATION  => 1,
            CURLOPT_HEADER      => 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_USERAGENT   => $curlopt_useragent,
            CURLOPT_URL       => $url,
            CURLOPT_TIMEOUT         => 1,
            CURLOPT_REFERER         => 'http://' . $_SERVER['HTTP_HOST'],
        );

        curl_setopt_array($ch, $curl_opt);

        $content = curl_exec($ch);

        if (!is_null($curl_info)) {
            $curl_info = curl_getinfo($ch);
        }

        curl_close($ch);

        if ( preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs) )  {
            $city = $regs[1];
        }
        if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) )  {
            $state = $regs[1];
        }

        if( $city!='' && $state!='' ){
          $location = $city . ', ' . $state;
          return $location;
        }else{
          return $default;
        }

    }  

    /**
     * Функция для скрытия некоторых символов в email
     * 
     * @param $text = Адрес почты
     */	
    public static function email_block($text) {
	$mail = $text;

	preg_match("/^(.*)(@.*)+/", $mail, $res);
		$mail = $res[1][0];
		for ($i = 1; $i < strlen($res[1]); $i++) {
        		$mail .= "*";
		}
		$mail .= $res[2];
	
        return $mail;
    }

    /**
     * Возвращает длину строки
     * 
     * @param $values = Текст
     * @param $charset = Кодировка
     */
    public static function strlen_shcms($values, $charset = 'utf-8' ) {

        if ( strtolower($charset) == "utf-8") return iconv_strlen($values, "utf-8");
	        else return strlen($values);

    }
 
    /**
     *  Возвращает подстроку
     * 
     * @param $list = Текст
     * @param $start = Начало
     * @param $length = Номер
     * @param $charset = Кодировка
     */
    public static function substr_shcms($list, $start, $length, $charset = 'utf-8') {

	    if ( strtolower($charset) == "utf-8") return iconv_substr($list, $start, $length, "utf-8");
	        else return substr($list, $start, $length);

    }
	
	
    /**
     * Проверка на точное использование ника
     * 
     * @param $nick = Ник пользователя
     */	
    public static function nick($nick) {
        // проверка на длину логина и возможные символы
        if (!preg_match("#^[a-zа-яё][a-zа-яё0-9\-\_\ ]{2,31}$#ui", $nick)) {
            return false;
        }
        // запрещаем одновременное использование русского и английского алфавилов
        if (preg_match("#[a-z]+#ui", $nick) && preg_match("#[а-яё]+#ui", $nick)) {
            return false;
        }
        // пробелы вначале или конце ника недопустимы
        if (preg_match("#(^\ )|(\ $)#ui", $nick)) {
            return false;
        }
        return true;
    }
 	
    /**
     * Проверяем на правильность логина в скайпе
     * 
     * @param $skype = Логин скайпа
     */	
    public static function skype($skype) {
        if (preg_match("#^[a-z][a-z0-9_\-\.]{5,31}$#ui", $skype))
            return true;
    }
	

    /**
     * Для удачных случаев
     * 
     * @param $msg = Основная описание
     * @param @mess = Дополнительный текст
     */
    public static function success($msg,$mess = false) {
        if(!empty($msg)) {
	    $message  = '<div class="alert alert-success"><img src="/engine/template/icons/success.png">&nbsp;';
            $message .= '<button type="button" class="close" data-dismiss="alert">&times;</button>'.$msg.'</div>';
		if($mess == true) {
	            $message .= '<div class="success">'.$mess.'</div>';
		}
	    echo $message;
	}else {
            echo '<div class="error">Неизвестная ошибка</div>';		
	}
    }
    
    /**
     * Для предупреждений
     * 
     * @param случаев $msg = Основная описание
     * @param @mess = Дополнительный текст
     */
    public static function warning($msg) {
        if(!empty($msg)) {
	    $warning  = '<div style="margin-bottom: 10px;" class="alert alert-info"><img src="/engine/template/icons/warning.png">&nbsp;';
            $warning .= '<button type="button" class="close" data-dismiss="alert">&times;</button>'.$msg.'</div>';
	    echo $warning;
	}else {
            echo '<div class="error">Неизвестная ошибка</div>';		
	}
    }    
    
    /**
     * Для информации
     * 
     * @param случаев $msg = Основная описание
     * @param @mess = Дополнительный текст
     */
    public static function info($msg) {
        if(!empty($msg)) {
	    $warning  = '<div style="margin-bottom: 10px;" class="alert alert-warning"><img src="/engine/template/icons/warning.png">&nbsp;';
            $warning .= '<button type="button" class="close" data-dismiss="alert">&times;</button>'.$msg.'</div>';
	    echo $warning;
	}else {
            echo '<div class="error">Неизвестная ошибка</div>';		
	}
    }        

    /**
     * Для неудачных случаев
     * 
     * @param = $error = Основной текст
     * @param = $err = Дополнительный текст
     */
    public static function error($error,$err = false) {
	if(!empty($error)) {
	    $errno = '<div class="alert alert-danger"><img src="/engine/template/icons/error.png">&nbsp;';
            $errno .= '<button type="button" class="close" data-dismiss="alert">&times;</button>'.$error.'</div>';
		if($err == true) {
		    $errno .= '<div class="error">'.$err.'</div>';
		}
	    echo $errno;
	}else {
	    echo '<div class="error">Неизвестная ошибка</div>';		
	}
    }
	
    /**
     * ucfirst UTF-8 известная функция
     * 
     * @param string $string 
     * @return string 
    */ 
    public static function ucfirst($string, $e ='utf-8') { 
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) { 
            $string = mb_strtolower($string, $e); 
            $upper = mb_strtoupper($string, $e); 
            preg_match('#(.)#us', $upper, $matches); 
            $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e); 
        } else { 
            $string = ucfirst($string); 
        } 
        return $string; 
    } 

    /**
     * Переадресации на другие страницы
     * 
     * @param $link = Cсылки
     */
    public static function home($link){
        foreach ($link as $v1) {
            
            return '<ul class="pager"><li class="previous">
                    <a href="'.$link[1].'">&larr; '.$link[0].'</a></li></ul>';
        }
        
    }
	
	
    /**
     * Определение размера файлов
     * 
     * @param $bt = Номеровый
     */
    public static function filesize($bt) {
            $s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
            $e = floor(log($bt)/log(1024));
			
        return sprintf('%.2f '.$s[$e], ($bt/pow(1024, floor($e))));
    }
	
    /**
     * Функция Оброботка текста  (0 - 999)
     * 
     * @param $name
     */		
    public static function format($name) {
        $f1 = strrpos($name, ".");
        $f2 = substr($name, $f1 + 1, 999);
        $fname = strtolower($f2);
        return $fname;
    }
	
    /**
     * Функция Оброботка текста  (999 - -4)
     * 
     * @param $name
     */	
    public static function format_r($name) {
        $f1 = strrpos($name, ".");
        $f2 = substr($name, $f1 - 999, -4);
        $fname = \utf8_strtolower($f2);
    return $fname;
    }
	
    /**
     * Выводит данные по пользователю
     * 
     * @param $user1 = $id_user
     */
    public static function user($user1) {
	global $user;
		$nick = $user->users($user1,array('nick'));
		$id = $user->users($user1,array('id'));
				
	return '<a href="'.MODULE.'profile.php?act=view&id='.$id.'">'.$nick.'</a>';
    }
	
    /**
     * Функция для обработки текстов и смайликов
     * 
     * @param $str = Текст
     */
    public static function input_text($str) {
        // обработка входящего текста
        $str = (string) $str;
		
		$pak = file(H.'engine/template/smilies/smiles.pak');
        $smiles = array();
            foreach ( $pak as $val ) {
                $val = trim($val);
                    if (! $val || '#' == $val{0}) { continue; }
                        list($gif,$alt,$symbol) = explode('=+:',$val);
                        $smiles[$symbol] = '<img src="/engine/template/smilies/'.htmlspecialchars($gif).'" alt="'.htmlspecialchars($alt).'" />';
            }
        $inputbbcode = new bbcode($str);
		// Задаем набор смайликов
        $str = $inputbbcode -> mnemonics = $smiles;
        $str = $inputbbcode->get_html();
		
		$str = preg_replace('#\[user=([^\]]+)\]#ies', 'engine::user("\\1")', $str);
        return $str;
    }
	

    /**
     * Функция для генерации случайной строки
     * 
     * @param $length = Число 
     */
    public static function generate($length=6) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;  
            while (strlen($code) < $length) {
                $code .= $chars[mt_rand(0,$clen)];  
            }
        return $code;
    }

    /**
     * Функция обрабатывает числовые данные
     * 
     * @param $num = Счетчик обработки
     */
    public static function number($num) {
		$number = $num;
	    return number_format($number, 0, '', ' '); // 1 500 000
    }
	
    /**
     * Транслит данных
     * 
     * @param = $var
     * @param = $lower
     * @param = $punkt
     */
    public static function totranslit($var, $lower = true, $punkt = true) {
	global $langtranslit;
	
	if ( is_array($var) ) return "";

	$var = str_replace(chr(0), '', $var);

	if (!is_array ( $langtranslit ) OR !count( $langtranslit ) ) {
		$var = trim( strip_tags( $var ) );

		if ( $punkt ) $var = preg_replace( "/[^a-z0-9\_\-.]+/mi", "", $var );
		else $var = preg_replace( "/[^a-z0-9\_\-]+/mi", "", $var );

		$var = preg_replace( '#[.]+#i', '.', $var );
		$var = str_ireplace( ".php", ".ppp", $var );

		if ( $lower ) $var = strtolower( $var );

		return $var;

	}
	
	$var = trim( strip_tags( $var ) );
	$var = preg_replace( "/\s+/ms", "-", $var );
	$var = str_replace( "/", "-", $var );

	$var = strtr($var, $langtranslit);
	
	if ( $punkt ) $var = preg_replace( "/[^a-z0-9\_\-.]+/mi", "", $var );
	else $var = preg_replace( "/[^a-z0-9\_\-]+/mi", "", $var );

	$var = preg_replace( '#[\-]+#i', '-', $var );
	$var = preg_replace( '#[.]+#i', '.', $var );

	if ( $lower ) $var = strtolower( $var );

	$var = str_ireplace( ".php", "", $var );
	$var = str_ireplace( ".php", ".ppp", $var );
	
	if( strlen( $var ) > 200 ) {
		
		$var = substr( $var, 0, 200 );
		
		if( ($temp_max = strrpos( $var, '-' )) ) $var = substr( $var, 0, $temp_max );
	
	}
	
	return $var;
}

    /**
     * Обработка названии 
     * 
     * @param = $name
     * @string = Функция устарела
     */
    public static function proc_name($name) {
            return trim(preg_replace('#[^\pL0-9\=\?\!\@\\\%/\#\$^\*\(\)\-_\+ ,\.:;]+#ui', '', $name));
    }

    /**
     * Возвращает реальный ip пользователя
     * 
     * @return string
     */
    public static function get_ip(){

        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_X_REAL_IP"])) {
            return $_SERVER["HTTP_X_REAL_IP"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }
	

    /**
     * Обратный отсчет времени 
     * 
     * @param $timediff = time()
     */	
    public static function timeq($timediff){
        $oneMinute=60;
        $oneHour=60*60;
        $oneDay=60*60*24;
            $dayfield = floor($timediff/$oneDay);
            $hourfield = floor(($timediff-$dayfield*$oneDay)/$oneHour);
            $minutefield = floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute);
            $secondfield = floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute));
            $time_1="$hourfield ч. $minutefield м. $secondfield c";
        return $time_1;
    }	
	

    /**
     * Проверяет авторизован ли пользователь
     * 
     * @return string
     */	
    public static function auth() {
        global $id_user;
	        
	    if($id_user == true) {
		header('Location: /');
	    }else {
		return false;
	    }
    }

}
