<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Файловая система')); //Название страницы
     
    switch($act):
	//По умолчанию выводит функции внизу
    	default:
            include_once('inc/index/default.php');
	break;
	//Форма поиска
	case 'search_file':
            include_once('inc/index/searchf.php');
	break;
	//Топ скачиваемых файлов
	case 'topfiles':
	    include_once('inc/index/topfiles.php');
        break;
	//Поиск
	case 'search':
	    include_once('inc/index/search.php');  
        break;
	//Новая папка
    	case 'new_dir':	
            include_once('inc/index/newdir.php');  
	break;
    endswitch;