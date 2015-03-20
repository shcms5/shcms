<?php
/*
 * Основные компоненты
 * SHCMS Engine
*/
if(!defined('SHCMS_ENGINE')) {
    die ('Ошибка в доступе');
}
    
//Стартует сессия
session_name('SHCMS_Engine'); 
session_start();
	
/**
 * @const H Путь на основную страницу
 */
define("H", $_SERVER["DOCUMENT_ROOT"].'/');
/**
 * @const MODULE путь к модулям скрипта
 */
define("MODULE", '/modules/');
/**
 * @const PROFILE Путь к профилю
 */
define("PROFILE",'/modules/profile.php');


//Автоматическая загрузка классов
$autoload = opendir(H.'engine/classes/');
    while($_autoload = readdir($autoload)) {
        if(preg_match('/\.php$/',$_autoload)) {
            //Вытаскиваем все классы $classname.class.php
            include_once(H.'engine/classes/'.$_autoload);
	}
    }
   
//Работа с Шаблонами    
include_once (H .'engine/classes/Templating/iTemplate.php');  
//Работа с Текстами и Ссылками
include_once (H .'engine/classes/Shcms/Component/Function/Taken/Utf8/Utf8.php');  

//Автозагрузка Классов
require_once (H.'engine/system/Autoload.php');

$loader = new SplLoader('Shcms', H.'engine/classes'); 
$loader2 = new SplLoader('DBDriver', H.'engine/classes');
$loader->register();
$loader2->register();

//Проверка версии PHP
echo \engine::php_version();

//Путь к подключению базы данных
$filename = H.'engine/config/dbconfig.php';

    //Проверяем есть ли соединение с базой
    if (file_exists($filename)) {
	include_once H.'engine/config/dbconfig.php';
    } else {
        header("Location: /install.php");
    }
         
//Установка времени
date_default_timezone_set('ETC/GMT'.-3);
//Текущее время
$_date = time();

    function clean_url($url) {
	if ($url == '') {
        return;
    }
    $url = \str_replace( "http://", "", \strtolower( $url ) );
	    $url = str_replace( "https://", "", $url );
            if( substr($url, 0, 4) == 'www.') {
        $url = substr($url, 4);
    }
    $url = explode( '/', $url );
	    $url = reset( $url );
	    $url = explode( ':', $url );
	    $url = reset( $url );
	
	return $url;
    }

        
//Отключаем Индификатор пользователя    
$id_user = false;
//Отключаем Логин
$user_ps = false;

$cookiep = \filter_input(\INPUT_COOKIE,'cpassword_shcms',\FILTER_SANITIZE_STRING);
$cookieu = \filter_input(\INPUT_COOKIE,'cuser_id_shcms',\FILTER_SANITIZE_STRING);

//Если В сессии не пуста поле ID и Пароль
if(isset($_SESSION['user_id_shcms'])&& isset($_SESSION['password_shcms'])) {
    $id_user = abs(intval($_SESSION['user_id_shcms']));
    $user_ps = $_SESSION['password_shcms'];
    
//Авторизуемся через Кукки    
}elseif(isset($cookieu) && isset($cookiep)) {
    //Получаем срезанный, обработанный индификатор авторизованного
    $id_user = abs(intval(base64_decode(engine::trim($cookieu))));
    //Так-же в сессию сохраняем индификатор
    $_SESSION['user_id_shcms'] = $id_user;
    //И сохраняем времменно пароль
    $user_ps = \engine::shgen(\engine::trim($cookiep));
    $_SESSION['password_shcms'] = $user_ps;
}

//Если ID и Пароль введены		
if ($id_user && $user_ps) {
    //Вытаскиваем из базы данные по ID
    $req = $dbase->query("SELECT * FROM `users` WHERE `id` = ? LIMIT 1",array($id_user));
    	if ($req->num_rows == true) {
            $datauser = $req->fetchArray();
                //Авторизуемся и получаем данные
        	if ($user_ps === $datauser['password']) {
            	    $login = $datauser['nick'];
        	}else {
                    //Удаляем ссессию ID
            	    unset ($_SESSION['user_id_shcms']);
                    //Удаляем сессию Пароля
            	    unset ($_SESSION['password_shcms']);
           	    setcookie('user_id_shcms', '');
           	    setcookie('password_shcms', '');
           		$id_user = false;
          		$user_ps = false;
                }
    	}else {
            //Удаляем ссессию ID
            unset ($_SESSION['user_id_shcms']);
            //Удаляем сессию Пароля
            unset ($_SESSION['password_shcms']);
       	    setcookie('user_id_shcms', '');
       	    setcookie('password_shcms', '');
      		$id_user = false;
       		$user_ps = false;
    	}
}
	
require_once H.'engine/classes/Shcms/Options/Conductor/autoload.php';


//Выводим из базы системные настройки
$glob_core = $db->get_array($db->query("SELECT * FROM `system_settings`"));
//Выводим из базы социальные настройки
$social = $db->get_array($db->query("SELECT * FROM `social`"));
//Выводим данные об определенном  пользователе
$users = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$id_user."'"));
//Объявляем класс User
$user = new user;
//Ограничение на авторизации
user::limit_auth($glob_core['un_limit']);
//Даем авторизованному права Посетитель или Администратор
$user_group = $user->users($id_user,array('group'));
//Если пользователь в сети обновляем онлайн время
$db->query('UPDATE `users` SET `lastdate` = "'.time().'" WHERE `id` = "'.$users['id'].'"');	
//Мультиязычность По умолчанию Русский
Lang::setLang('ru');	


if (!isset($act) AND isset($_REQUEST['act'])) {
    $act = engine::totranslit($_REQUEST['act']);
} elseif (isset($act)) {
    $act = engine::totranslit($act);
} else {
    $act = "";
}
if (!isset($do) AND isset($_REQUEST['do'])) {
    $do = engine::totranslit($_REQUEST['do']);
} elseif (isset($do)) {
    $do = engine::totranslit($do);
} else {
    $do = "";
}
if (!isset($active) AND isset($_REQUEST['active'])) {
    $active = engine::totranslit($_REQUEST['active']);
} elseif (isset($active)) {
    $active = engine::totranslit($active);
} else {
    $active = "";
}

//Выводим данные о забаненом
$banq = $db->query( "SELECT * FROM `ban` WHERE `id_user` = '{$id_user}'" );
    //Если пользователь забанен
    if($db->num_rows($banq) > 0){    
	$banl = $db->get_array($banq);
            //И если его время не вышло то не выпускаем из одной пути
	    if (strtotime(date('d-m-Y')) < strtotime($banl['time']))  {
		include_once(H.'modules/ban.php');
		exit;
	    }else {
                //Если время бана окончена то удаляем
		$db->query("DELETE FROM `ban` WHERE `id_user` = '{$id_user}'");
	    }
    }	
		 
//Включение буферизации вывода		 
ob_start();
// Выключение неявных сбросов   
ob_implicit_flush(0);


