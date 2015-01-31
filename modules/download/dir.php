<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
    
    //Обрабатываем полученный $_GET[id]
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    //Проверяем есть нумеровые символы
    $id = intval($id);

    //Если вместо id num попытаются вставить текст то выводит ошибку
    if (!isset($id) || !is_numeric($id)) {
        header('Location: index.php');
        exit;
    }
	
    //Выводит папки где $id = true
    $dir_file = $db->get_array($db->query("SELECT * FROM `files_dir` WHERE `id` = '".$id."'")); 
    //Название страницы
    $templates->template(Lang::__('Загрузки - '.$dir_file['name']));
    //Если файла из выбранного $id
    $view = $db->query('SELECT * FROM `files_dir` WHERE `id` = '.$id.'');
	if(!$db->num_rows($view)){
	    header('Location: index.php');
            exit;
	}		
        
    switch($act):
	//По умолчанию default
	default:
	    include_once('inc/subfolder/default.php');
        break;
        
	//Новую папку создаем
        case 'new_dir':	
            //Навигация Вверхняя
            echo '<div class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="index.php" class="btn btn-default">'.Lang::__('Загрузки').'</a>
                <a href="#" class="btn btn-default disabled">'.Lang::__('Создать папку').'</a>
            </div>';
	    include_once('inc/subfolder/newdir.php');
            
	break;
		
    endswitch;
    
//Переадресация
echo engine::home(array(Lang::__('Назад'),'index.php'));