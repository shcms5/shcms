<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Новая категория')); //Название страницы
//Массив ошибок
$error = array();

//Если у тебя права 15 то ты можешь приступить к работе
if($groups->setAdmin($user_group) !=  15) {
    header('Location: index.php');
    exit;
}

    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Форум</a>
            <a href="#" class="btn btn-default disabled">Новая категория</a>
        </div>';

//Отключения форума
$off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
    
if($off_forum['off_forum'] == 1) {
    echo engine::error(Lang::__('Форум приостановлен с ').$tdate->make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
    echo engine::home(array('Назад','/index.php'));	 
    exit;
}

$submit = filter_input(INPUT_POST,'submit');
$name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);

//Выполняет функцию если уже введена название и нажата кнопка "Создать"
if(isset($submit)) {
    //Если введенная категория уже есть в базе данных то выводит ошибку		
    $check = $db->query("SELECT * FROM `forum_category` WHERE `name`='" . $db->safesql($name) ."'");
    //Проверяет введена ли название
    if(empty($name)) {
	$error['name'][] = Lang::__('Введите название');
    }elseif ($db->get_array($check) != 0) {
        $error['name'][] = Lang::__('Введенная категория уже существует');
    }elseif(empty($error)) {
	//Добавляем данные в базу
	$db->query("INSERT INTO `forum_category` (`name`,`time`) VALUES ('".$db->safesql($name)."','".time()."')");
        //Получение последной id
	$id_cat = $db->insert_id();
        //Уведомление о добавление
	echo engine::success(Lang::__('Категория успешно создана')); // Успешно 
	header('Location: index.php');
        exit;
    }
}

echo '<div class="mainname">'.Lang::__('Новая категория').'</div>';
echo '<div class="mainpost">';
//Форма HTML.
$form = new form('?','','','class="form-horizontal"');
$form->text('<div class="form-group"><br/>');
//Название
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Название').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',false,'class="form-control"');
    //При ошибке
    if(isset($error['name'])) {
        $form->text('<p class="text-danger">'.implode('<br/>', $error['name']).'</p>');
    }else {
        $form->text('<p class="text-muted small">'.Lang::__('Введите название категории').'</p>');
    }       
$form->text('</div></div>');   
$form->text('<div class="modal-footer">');
$form->submit(Lang::__('Создать'),'submit',false,'btn btn-success');
$form->text('<a class="btn" href="index.php">'.Lang::__('Отменить').'</a>');
$form->text('</div>');
$form->display();

echo '</div>';
