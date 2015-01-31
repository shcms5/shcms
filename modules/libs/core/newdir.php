<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

echo engine::admin($users['group']);
//Массив ошибок
$error = array();


$submit = filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
        
if(isset($submit)) {
    //Обработка названия
    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
    $name = $db->safesql($name);
    //Обработка описания
    $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
    $text = $db->safesql($text);

    //Проверка введена ли название
    if(empty($name)){
	$error['name'][] = Lang::__('Введите название');	 
    }	
    //Проверка введена ли описание
    if(empty($text)) {
	$error['text'][] = Lang::__('Введите описание');
    }
	
    if(empty($error)) {
        $dbyes = $db->query("INSERT INTO `libs` (`name`,`text`,`time`) VALUES ('".$name."','".$text."','".time()."')");		
	    if( $dbyes == true ){
		header('Location: index.php');
            }else {
		header("Location: index.php?do=editdir");
	    }
	
    }
}
			
echo '<div class="mainname">'.Lang::__('Создать раздел').'</div>';
echo '<div class="mainpost">';
                
	            
//Форма Создания папки
$form = new form('?do=newdir','','','class="form-horizontal"');
                    
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
$form->textarea2(false,'text',$text,'','','form-control');
    //При ошибке
    if(isset($error['text'])) {
        $form->text('<p class="text-danger small">'.implode('<br/>', $error['text']).'</p>');
    }
$form->text('</div></div>');   
                    
//Кнопка
$form->text('<div class="modal-footer">');
$form->submit('Отправить','submit',false,'btn btn-success');
$form->text('<a class="btn btn-default" href="index.php">Отмена</a>');
$form->text('</div>');
$form->display();
			
echo '</div>';     
