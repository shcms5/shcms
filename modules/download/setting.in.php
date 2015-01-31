<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Настройка Папок')); //Название страницы
    
//Если у тебя права 15 то ты можешь приступить к работе
if($groups->setAdmin($user_group) !=  15) {
        header('Location: index.php');
	exit;	
 }
       
    //Если папки нет то выведит эту ошибку
    $cat = $db->query('SELECT * FROM `files_dir`');
	if(!$db->num_rows($cat)){
            header('Location: index.php');
            exit;
    }

    //Делаем облегчение создаем 2разных фукнции в одной станице
    switch($act):
        //По умолчанию
        default:
            include_once('inc/settings/default.php');
	break;
        //Удаляем папку
        case 'delete_cat':
	    include_once('inc/settings/cdelete.php');
        break;
    endswitch;