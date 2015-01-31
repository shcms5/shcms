<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Создание новой темы')); //Название страницы

//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();

    //Отключения форума
    $off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
	if($off_forum['off_forum'] == 1) {
	    echo engine::error(Lang::__('Форум приостановлен с ').$tdate->make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
	    echo engine::home(array('Назад','/index.php'));	 
	    exit;
	}
	
//Если пользователь не авторизован и захочет создать тему то выведит ошибку
if($id_user == false) {
    echo engine::error('Тему могут создать авторизованные пользователи');
    header('Refresh: 1; url=index.php');
    exit;
}

//Если вместо id num попытаются вставить текст то выводит ошибку
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $templates->template(Lang::__('Ошибка при создании темы')); //Название страницы
    header('Refresh: 1; url=index.php');
    engine::error('Произошла ошибка при выборе раздела'); //При ошибке
    exit;
}
//Из $_GET в обычную 
$id = (int) $_GET['id'];


	//Если файла из выбранного $id
        $view = $db->query('SELECT * FROM `forum_subsection` WHERE `id` = '.$id.'');
		if(!$db->num_rows($view)){
		    header('Refresh: 1; url=index.php');
            engine::error(Lang::__('Произошла ошибка не найден раздел')); //При ошибке
            exit;
		}		

//Вызываем данные из базы
$section = $db->get_array($db->query("SELECT * FROM `forum_subsection` WHERE `id` = '".$id."'"));


if(isset($_POST['name']) and isset($_POST['topic']) and isset($_POST['submit'])) {
    //Обрабатываем название
    $name = engine::proc_name($_POST['name']);
	//Обрабатывает описание
    $topic = $_POST['topic'];

	//Проверяет введена ли название
	$nametop = $db->query("SELECT * FROM `forum_topics` WHERE `name` = '".$db->safesql($name)."'");
	if($db->get_array($nametop) != 0) {
	    echo engine::error(Lang::__('Такое название уже существует'));
	}elseif(!$name) {
	    echo engine::error(Lang::__('Введите название темы'));
	}elseif(!$topic) {
	//Проверяет введена ли описание
	    echo engine::error(Lang::__('Введите описание темы'));
	}else {

		    //Добавлям данные в базу
	        $db->query("INSERT INTO `forum_topics` (`id_sec`,`id_cat`,`id_user`,`name`,`text`,`time`) VALUES ('".$section['id']."','".$section['id_cat']."','".$id_user."','".$db->safesql($name)."','".$db->safesql($topic)."','".time()."')");
	        $top_id = $db->get_array($db->query("SELECT * FROM `forum_topics` WHERE `id_sec` = '".$section['id']."' AND `id_user` = '".$id_user."' AND `name` = '".$db->safesql($name)."'"));
			$db->query("INSERT INTO `forum_post` (`id_cat`,`id_sec`,`id_top`,`id_user`,`time`,`text`) VALUES ('".intval($section['id_cat'])."','".intval($section['id'])."','".$top_id['id']."','".$id_user."','".time()."','".$db->safesql($topic)."')");
			
			echo engine::success(Lang::__('Тема успешно создана')); // Успешно
		    echo engine::home(array(Lang::__('Создать еще'),'new.topic.php?id='.$id.'')); //Переадресация на пред. страницу
	        echo engine::home(array('Назад','section.php?id='.$id.'')); //Переадресация на пред. стараницу
			exit;
	    }
	
}



    //Форма
	echo '<div class="mainname">'.Lang::__('Создание новой темы').' в <font color="green">'.$section['name'].'</font></div>';
    echo '<div class="mainpost">';
        $form = new form('?id='.$id.'','','','class="form-horizontal"');
//Наименование Категории
$form->text('<br/><div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Название:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',false,'class="form-control"');
    //При ошибке
    if(isset($error['name'])) {
        $form->text('<p class="text-danger small">'.implode('<br/>', $error['name']).'</p>');
    }  
$form->text('</div></div>');  
//Описание категории
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Описание').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea(false,'topic','','','','form-control');
    //При ошибке
    if(isset($error['desc'])) {
        $form->text('<p class="text-danger small">'.implode('<br/>', $error['desc']).'</p>');
    }
$form->text('</div></div>');   
$form->text('<div class="modal-footer">');
$form->submit(Lang::__('Создать'),'submit',false,'btn btn-success');
$form->text('<a class="btn btn-default" href="index.php">Отмена</a>');
$form->text('</div>');
        $form->display();
    echo '</div>';

echo engine::home(array(Lang::__('Назад'),'section.php?id='.$id.'')); //Переадресация на пред. страницу
