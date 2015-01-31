<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

//Обрабатываем $_GET
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
/* @var $id type intval */
$id = intval($id);

    //Если нужные параметры не доступны
    if (!isset($id) || !is_numeric($id)) {
        header('Location: index.php');
        exit;
    }

//Выводит папки где $id = true
$view_file = $db->get_array( $db->query( "SELECT * FROM `files` WHERE `id` = '".$id."'" ) ); 
$db->query( "UPDATE `files` SET `count` = '".($view_file['count']+1)."' WHERE `id` = '".$id."'" ); 

$string = new Shcms\Component\String\StringProcess;	
    //Получаем таблицу из базы
    $view = $db->query("SELECT * FROM `files` WHERE `id` = '".$id."'" );
	//Если нет данных то выводим текст ошибки	
	if($db->num_rows($view) == 0) {
            header('Location: index.php');
            exit;
	}
        //Если старндартная название не изменена
	if($view_file['name'] == false) {
    	    $templates->template('Файл: '.Lang::__($view_file['files']),$view_file['desc'],$view_file['key']);
	}else {
    	    $templates->template('Файл: '.Lang::__($view_file['name']),$view_file['desc'],$view_file['key']);		
	}	
        $dirv = $db->get_array($db->query("SELECT * FROM `files_dir` WHERE `id` = '{$view_file['id_dir']}'"));
        
    $cfile = new Shcms\Files\Download\Files();
        
                //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Загрузки</a>
            <a href="dir.php?id='.$dirv['id'].'" class="btn btn-default">'.$dirv['name'].'</a>
            <a href="#" class="btn btn-default disabled">'.$string->sub($view_file['files'],0,30,'utf-8').'</a>
        </div>';
	    switch($act):
		//По умолчанию
		default:
                    include_once('inc/view/view.php');
	        break;
                //Комментария
		case 'comment':
		    include_once('inc/view/comment.php');
		break;
                //Редактров файла
		case 'edit_file':
		    include_once('inc/edit_file.php');
		break;
		//Скриншот	
		case 'screen':
		    include_once('inc/screen.php');
		break;
		//Удаляем файлы, папки	
		case 'delete':
		    include_once('inc/delete.php');					
		break;
		//Скачивание файла	
		case 'download':
		    include_once('inc/download.php');		
		break;
			
	    endswitch;