<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

    $templates->template('Фотогаллерея'); //Название страницы	
		
    $userdir = $db->get_array($db->query( "SELECT * FROM `gallery_dir`  WHERE `id_user` = '".$users['id']."'" ));
	
    //Класс Времени
    $tdate = new Shcms\Component\Data\Bundle\DateType();
    
    switch($do):
	
	//Все пользователи
    	default:
            include_once('inc/default.php');
	break;
	    
	//Добавление новой папки
	case 'newdir':    
	    include_once('inc/newdir.php');	
	break;
		
	//Добавление новой изображении
	case 'newphoto':    
	    include_once('inc/newphoto.php');	
	break;	

        case 'editdir':		
	    include_once('inc/editdir.php');
	break;
		
	case 'deletedir':
            include_once('inc/deletedir.php');	
        break;
		
	case 'delete':
	    include_once('inc/deleteimage.php');
        break;
		
	//Папки пользователя %user
        case 'user';
	    include_once('inc/user.php');
	break;
	
	//Фотоальбомы пользователя %user
	case 'photo':
	    include_once('inc/photo.php');
	break;
		
	case 'view':
	    include_once('inc/view.php');
	break;		
	
	
    endswitch;	
