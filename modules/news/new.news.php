<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Опубликовать новость')); //Название страницы
$error  = array();    
    //Если у тебя права 15 то ты можешь приступить к работе
    if($groups->setAdmin($user_group) !=  15) {
        header('Location: index.php');
        exit;
    }
    //Обработка кнопки добавления
    $submit = filter_input(INPUT_POST, 'submit');
    //Обработки названия новости
    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
    //Обработка категории куда попадет новость
    $category = filter_input(INPUT_POST,'category',FILTER_SANITIZE_NUMBER_INT);
    //Обработка Краткого описания
    $cr_news = filter_input(INPUT_POST, 'cr_news',FILTER_SANITIZE_STRING);
    // Полного описания
    $text = filter_input(INPUT_POST,'text', FILTER_DEFAULT);
	
if(isset($submit)) {
    //Проверяем существует ли название
    if(empty($name)) {
	$error['name'][] = Lang::__('Введите заголовок');
    }
    //Проверяем существует ли краткое описание
    if(empty($cr_news)) {
	$error['cr_news'][] = Lang::__('Введите краткое описание');
    }
    //Проверяем существует ли полное описание
    if(empty($text)) {
	$error['text'][] = Lang::__('Введите полное описание');
    }
    //Проверяем существует ли категории
    if(empty($category)) {
	echo  engine::error(Lang::__('Выберите категорию'));
        exit;
    }
    elseif(empty($error)) {
        //Если все введено начинаем заности в базу данных данные
	$db->query("INSERT INTO `news` (`id_user`,`id_cat`,`title`,`text`,`cr_news`,time) VALUES ('".$id_user."','".intval($category)."','".$db->safesql($name)."','".$db->safesql($text)."','".$db->safesql($cr_news)."','".time()."')");
	header('Location: index.php');
	exit;	
    }	
}

        echo '<div class="form-horizontal">';
        //Форма публикация новостей
	$form = new form('index.php?do=nnews');
        
        $form->text('<br/><div class="control-group">');
        $form->text('  <label class="control-label" for="inputEmail">'.Lang::__('Заголовок').'</label>');
        $form->text('<div class="controls">');
        $form->input2(false,'name','text',$name,'class="form-control"','');
        
                if(isset($error['name'])) {
                    $form->text('<span style="color:red"><small>' . implode('<br />', $error['name']) . '</small></span>');
                }
                
        $form->text('</div></div>');
        $form->text('<div class="control-group">');
           
        $form->text('  <label class="control-label" for="inputEmail">'.Lang::__('Полное описание').'</label>');
        $form->text('<div class="controls">');
	$form->textarea(false,'text',$text,'','','');
            
            if(isset($error['text'])) {
                    $form->text('<span style="color:red"><small>' . implode('<br />', $error['text']) . '</small></span>');
                }
        $form->text('</div></div>');
        
	$form->text('<div class="control-group">');
           
        $form->text('<label class="control-label" for="inputEmail">'.Lang::__('Краткое описание').'</label>');
        $form->text('<div class="controls">');
	$form->textarea(false,'cr_news',$cr_news,'','','','2');
            
            if(isset($error['cr_news'])) {
                $form->text('<span style="color:red"><small>' . implode('<br />', $error['cr_news']) . '</small></span>');
            }    
        $form->text('</div></div>');

        $form->select2('Категория','category','*','news_category','id','name');
	$form->text('</div><div class="submit">');
	$form->submit('Опубликовать','submit');
	$form->text('или <a class="cancel" href="index.php">Отменить</a></div>');
	$form->display();
 		
        echo '</div>';
        