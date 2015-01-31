<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
 //Название страницы
$templates->template(Lang::__('Новый раздел'));

//Массив ошибок
$error = array();
//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();

    //Отключения форума
    $off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
	if($off_forum['off_forum'] == 1) {
	    echo engine::error(Lang::__('Форум приостановлен с ').$tdate->make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
	    echo engine::home(array('Назад','/index.php'));	 
	    exit;
	}
	

    if($groups->setAdmin($user_group) != 15) {
        header('Location: index.php');
        exit;
    }
    
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Форум</a>
            <a href="#" class="btn btn-default disabled">Новый раздел</a>
        </div>';
    
    //Обработка данных
    //Обработка названия
    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
    //Обработка описания
    $desc = filter_input(INPUT_POST,'desc',FILTER_SANITIZE_STRING);
    $desc = engine::input_text($desc);
    //Обработка кнопки
    $submit = filter_input(INPUT_POST,'submit');
    //Обработка категории
    $category = filter_input(INPUT_POST,'category',FILTER_SANITIZE_NUMBER_INT);

//Выполняет функцию если уже введена название описание и выбрали путь в категорию и нажата кнопка "Создать"
if(isset($submit)) {
	
        //Если введенная названия раздела  уже есть в базе данных то выводит ошибку		
        $check = $db->query("SELECT * FROM `forum_subsection` WHERE `name`='" . $db->safesql($name) ."'");
	//Проверяет введена ли название
    if(empty($name)) {
	    $error['name'][] = Lang::__('Введите название');
	}elseif(empty($desc)) {
	    $error['desc'][] = Lang::__('Введите описание раздела');
	}elseif(empty($category)) {
	    //Проверяет выбрали ли категорию в которую пойдет раздел
	    $error['category'][] = Lang::__('Выберите хотя бы одну категорию');
	}elseif ($db->get_array($check) != 0) {
	    //Если раздел существует
            $error['category'][] = Lang::__('Введенный раздел уже существует');
	}elseif(empty($error)) {
            //Добавлям данные в базу
	    $db->query("INSERT INTO `forum_subsection` (`id_cat`,`name`,`text`,`time`) VALUES ('".$_POST['category']."','".$db->safesql($name)."','".$db->safesql($desc)."','".time()."')");
	    $id_cat = $db->insert_id();
	        echo engine::success(Lang::__('Раздел успешно создан')); // Успешно
		header('Location: index.php');
		exit;
	    }
}

//Форма
echo '<div class="mainname">'.Lang::__('Новый раздел').'</div>';
echo '<div class="mainpost">';
$form = new form('?','','','class="form-horizontal"');
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
$form->textarea2(false,'desc','','','','form-control');
    //При ошибке
    if(isset($error['desc'])) {
        $form->text('<p class="text-danger small">'.implode('<br/>', $error['desc']).'</p>');
    }
$form->text('</div></div>');   

//Выбираем категории
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Выберите категорию').'</label>');
$form->text('<div class="col-sm-10">');
$form->select2(false,'category','*','forum_category','id','name','class="form-control"');
    //При ошибке
    if(isset($error['category'])) {
        $form->text('<p class="text-danger small">'.implode('<br/>', $error['catogory']).'</p>');
    }
$form->text('</div></div>');   

//Пункты создании и отмены разделы
$form->text('<div class="modal-footer">');
$form->submit(Lang::__('Создать'),'submit',false,'btn btn-success');
$form->text('<a class="btn btn-default" href="index.php">Отмена</a>');
$form->text('</div>');
$form->display();
echo '</div>';
