<?php

define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');

//Обработка полученного $_GET
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
//Проверяем нумероно ли полученный символ
$id = intval($id);

//Обращение к базы для полученния данных о пользователе
$pruser = $db->get_array($db->query("SELECT COUNT(*) FROM `users` WHERE `id` = '{$id}'"));

//Если $pruser false то назад
/*
if ($pruser[0] != 1 and $act == '') {
    header('Location: /index.php');
    exit;
}
  */      
//Если не авторизован пользователь то переадресация на гланую
if(!$id_user and $act != '' and $act != 'friend_list') {
    header("Location: /index.php");
    exit; 
}
    
//Вывод всех данных из базы по $luser[parametr]
$luser = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$id."'"));
$iduser = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$id_user."'"));

//Название страницы
if($act == false) {
    $title = ''.$luser['nick'].' - Просмотр профиля';
}elseif($act == 'warnings') {
    $title = 'Просмотр профиля: - Предупреждения';
}else {
    $title = 'Профиль пользователя';
}
                        
$templates->template($title);
        
use Shcms\Component\Provider\Json\JsonScript;

$json = new JsonScript;	
		
//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();

    switch($act):
		
	default:
	    include_once(H.'/modules/profile/view.php');
	break;

	case 'view':
	    include_once(H.'/modules/profile/view.php');		    
	break;
		
	case 'edit_profile':
		include_once(H.'/modules/profile/edit.php');		
	break;
		
	case 'core':
            include_once(H.'/modules/profile/core.php');		
	break;		
		
	case 'email':
	    include_once(H.'/modules/profile/email.php');			
	break;
		
	case 'act_email':
	    include_once(H.'/modules/profile/actemail.php');			
	break;
		
	case 'act_pass':
	    include_once(H.'/modules/profile/actpass.php');		
	break;
		
	case 'playname':
	    include_once(H.'/modules/profile/playname.php');		
	break;
		
	case 'ignoredusers':
	    include_once(H.'/modules/profile/ignoredusers.php');		
	break;			
		
	case 'attachments':
	    include_once(H.'modules/profile/attachments.php');
	break;
		
	case 'notificationlog':
	    include_once(H.'modules/profile/notificationlog.php');		
	break;
		
	case 'friend_list':
	    include_once(H.'modules/profile/friend_list.php');			
	break;
		
	case 'warnings':
	    include_once(H.'modules/profile/warnings.php');	
	break;
		
   endswitch;