<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

echo engine::admin($users['group']);
//Массив ошибок
$error = array();

$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$id = intval($id);

$submit = filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
        
        
//Навигация Вверхняя
echo '<div class="btn-group btn-breadcrumb margin-bottom">
    <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
    <a href="index.php?do=libs&id='.$id.'" class="btn btn-default">Статьи</a>
    <a href="index.php" class="btn btn-default disabled">Новая статья</a>            
</div>';

if(isset($submit)) {
    //Обработка названия
    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
    $name = $db->safesql($name);
    //Обработка описания
    $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
    $text = $db->safesql($text);
    //Обработка описания краткого
    $mtext = filter_input(INPUT_POST,'mtext',FILTER_SANITIZE_STRING);
    $mtext = $db->safesql($mtext);    

    //Проверка введена ли название
    if(empty($name)){
	$error['name'][] = Lang::__('Введите название');	 
    }	
    //Проверка введена ли описание
    if(empty($text)) {
	$error['text'][] = Lang::__('Введите полное описание');
    }
    //Проверка введена ли краткое описание
    if(empty($mtext)) {
	$error['mtext'][] = Lang::__('Введите краткое описание');
    }
    
    if(empty($error)) {
        $dbyes = $db->query("INSERT INTO `libs_files` (`id_lib`,`id_user`,`name`,`text`,`mtext`,`time`) VALUES ('".$id."','".$id_user."','".$name."','".$text."','".$mtext."','".time()."')");			
	    if( $dbyes == true ){
		header('Location: index.php?do=libs&id='.$id.'');
            }else {
		header('Location: index.php?do=libs&id='.$id.'');
	    }
	
    }
}
			
echo '<div class="mainname">'.Lang::__('Написать новую статью').'</div>';
echo '<div class="mainpost">';
                
	            
//Форма Создания папки
$form = new form('?do=newfiles&id='.$id.'','','','class="form-horizontal"');
                    
//Наименование Категории
$form->text('<br/><div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Название:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',$name,'class="form-control"');
    //При ошибке
    if(isset($error['name'])) {
        $form->text('<p class="text-danger small">'.implode('<br/>', $error['name']).'</p>');
    }  
$form->text('</div></div>'); 

//Описание категории
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Описание').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea(false,'text',$text,'','','form-control');
    //При ошибке
    if(isset($error['text'])) {
        $form->text('<p class="text-danger small">'.implode('<br/>', $error['text']).'</p>');
    }
$form->text('</div></div>');   

//Краткое категории
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Краткое Описание').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea(false,'mtext',$mtext,'','','form-control','2');
    //При ошибке
    if(isset($error['text'])) {
        $form->text('<p class="text-danger small">'.implode('<br/>', $error['mtext']).'</p>');
    }
$form->text('</div></div>');   
                    
//Кнопка
$form->text('<div class="modal-footer">');
$form->submit('Отправить','submit',false,'btn btn-success');
$form->text('<a class="btn btn-default" href="index.php">Отмена</a>');
$form->text('</div>');
$form->display();
			
echo '</div>';     