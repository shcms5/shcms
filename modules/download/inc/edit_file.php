<?php
  //Если пользователь не является автором файла ему остальные функции ограничены
    if($id_user != $view_file['id_user']) {
	echo engine::error(Lang::__('Доступ ограничен'));
	header('Location: view.php?id='.$id.'');
	exit;
    }
    
    $submit = filter_input(INPUT_POST,'submit');
    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
    $text1 = filter_input(INPUT_POST,'text1',FILTER_SANITIZE_STRING);
    $text2 = filter_input(INPUT_POST,'text2',FILTER_SANITIZE_STRING);
    $desc = filter_input(INPUT_POST,'desc',FILTER_SANITIZE_STRING);	
    $key = filter_input(INPUT_POST,'key',FILTER_SANITIZE_STRING);	
    
    if(isset($submit)) {		    

	    if(!$view_file['files']) {
		echo engine::error(Lang::__('В не можете добавить данные так как не был загружен файл'));	
	    }else {
		//Добавлям данные в базу
	        $db->query("UPDATE `files` SET `name` = '".$db->safesql($name)."', `text1` = '".$db->safesql($text1)."', `text2` = '".$db->safesql($text2)."', `desc` = '".$db->safesql($desc)."', `key` = '".$db->safesql($key)."', `update` = '".time()."' WHERE `id` = '".$id."'");
		header('Location: view.php?id='.$id.'');
	        exit;		
	    }
					
    }
			
			
    //Форма редактирования
    echo '<div class="mainpost">';
	$form = new form('?act=edit_file&id='.$id.'','','','class="form-horizontal"');

$form->text('<br/><div class="form-group">');
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Название файла:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',$view_file['name'],'class="form-control"');
$form->text('</div></div><div class="row_g"></div>'); 

//Описание категории
$form->text('<div class="form-group">');
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Краткое описание').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'text1',$view_file['text1'],'','','form-control');
$form->text('</div></div><div class="row_g"></div>');  

//Описание категории
$form->text('<div class="form-group">');
$form->text('<label  class="col-sm-2 control-label">'.Lang::__('Полное описание').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'text2',$view_file['text2'],'','','form-control');
$form->text('</div></div><div class="row_g"></div>');  

//Описание категории
$form->text('<div class="form-group">');
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Описание').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'desc',$view_file['desc'],'','','form-control');
$form->text('</div></div><div class="row_g"></div>');  

//Описание категории
$form->text('<div class="form-group">');
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Ключевые слова').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'key',$view_file['key'],'','','form-control');
$form->text('</div></div>');  

//Кнопка
$form->text('<div class="modal-footer">');
$form->submit('Применить','submit',false,'btn btn-success');
$form->text('<a class="btn btn-default" href="?id='.$id.'">Отмена</a>');
$form->text('</div>');
        
$form->display();
			
    echo '</div>';
		
			