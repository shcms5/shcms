<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');    
//Название страницы
$templates->template(Lang::__('Список пользователей'));

//Загружаем пользовательский контроллер
include_once 'user/controller.php';

$userc = new controller;
        $row = $db->get_array($db->query("SELECT COUNT(*) FROM `users` WHERE `lastdate` > '".(time()-600)."'"));
        //Выводим счетчик постов
    	$rowq = $db->get_array($db->query("SELECT COUNT(*) FROM `users`"));
        
switch($do):
	
    //Весь список пользователей
    default:
        //Объявление навигации
        echo $userc->navigation();
        //Заголовки разделов
        echo $userc->name();
        echo '<div class="mainpost">';
        //Поиск пользователей
        echo $userc->search();
        echo '<ul id="searchlist2" class="List_withminiphoto Pad_list">';
        //Все пользователи 
        echo $userc->view('all');
        echo '</ul></div>';
        //Используем навигацию
        echo $userc->navigation_end();
        
	
    break;

	
    //Пользователи в онлайне
    case'online':
	//Навигационная система
        echo $userc->navigation();
        //Заголовки разделов
        echo $userc->onlinename();
        echo '<div class="mainpost">';
        //Поиск пользователей
        echo $userc->search();
        echo '<ul id="searchlist2" class="List_withminiphoto Pad_list">';
        //Пользователей в онлайне
        echo $userc->view('online');
        echo '</ul></div>';
        //Используем навигацию
        echo $userc->navigation_end(); 
	
    break;

endswitch;