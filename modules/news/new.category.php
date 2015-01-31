<?
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Создание новой категории')); //Название страницы

	//Если у тебя права 15 то ты можешь приступить к работе
    if($groups->setAdmin($user_group) !=  15) {
        echo engine::error('У вас нет прав для доступа');
        header('Refresh: 1; url=index.php');
        exit;
    }

//Выполняет функцию если уже введена название и нажата кнопка "Создать"
if(isset($_POST['name']) and isset($_POST['submit'])) {

   //Обработка названии категории
    $name = engine::proc_name($_POST['name']);
	//Если введенная категория уже есть в базе данных то выводит ошибку		
            $check = $db->query("SELECT * FROM `news_category` WHERE `name`='" . $db->safesql($name) ."'");
    //Проверяет введена ли название
	
	if(!$name) {
	engine::error(Lang::__('Введите название'));
	}elseif ($db->get_array($check) != 0) {
	//Выводит это если существует название категории
             echo engine::error(Lang::__('Введенная категория уже существует'));
	    }else {
		
		//Добавляем данные в базу
	    $db->query("INSERT INTO `news_category` (`name`,`time`) VALUES ('".$db->safesql($name)."','".time()."')");
		$id_cat = $db->insert_id();
	    echo engine::success(Lang::__('Категория успешно создана')); // Успешно 
		echo engine::home(array('Создать еще','new.category.php')); //Переадресация на пред. стараницу
	    exit;
	}
}

//Форма
echo '<div class="mainpost">';
$form = new form('?');
$form->input(Lang::__('Название:'),'name','text');
$form->submit(Lang::__('Создать'),'submit');
$form->display();
echo '</div>';
echo engine::home(array('Назад','index.php'));
?>