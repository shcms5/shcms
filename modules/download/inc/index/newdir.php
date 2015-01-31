<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$id = intval($id);
    //Создаем массив ошибок
    $error = array();
    //Обработка кнопки
    $submit = filter_input(INPUT_POST,'submit');
    //Обработка названия
    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
    //Обработка checkbox`a
    $new_file = filter_input(INPUT_POST,'new_file',FILTER_SANITIZE_NUMBER_INT);
    //Обработка текста
    $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
	if(isset($submit)) {
			
		//Проверка на нуммерование
		$new_file = intval($new_file);		
               //Обрабатываем текст	
	       $text = $db->safesql($text);
                //Проверка существует ли название
                if(empty($name)) {
	            $error['name'][] = Lang::__('Введите название папки');		
	        }
                //Проверка существует ли описание
                if(empty($text)){
                    $error['text'][] = Lang::__('Введите описание');
                }elseif(empty($error)) {		
		    if($new_file != 2) {
			$new_file = 1;
		    }					
                    //Добавлям данные в базу
	            $db->query("INSERT INTO `files_dir` (`name`,`text`,`time`,`dir`,`load`) VALUES ('".$db->safesql($name)."','{$text}','".time()."','0','".$new_file."')");
	            header('Location: index.php');
	            exit;	
		}
			
	}

		echo '<div class="mainname">'.Lang::__('Создание новой папки').'</div>';
		echo '<div class="mainpost">';
                
	            
//Форма Создания папки
$form = new form('index.php?act=new_dir','','','class="form-horizontal"');
                    
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
$form->text('</div></div><div class="row_g"></div>');   

//Дополнение
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Дополнение').'</label>');
$form->text('<div class="col-sm-10">');
//На выбор открытие закрытие папки
$form->text('<div class="col-sm-5">');
$form->input2(false,'dir_open','checkbox','1','checked="checked"',Lang::__('Папка открыта?'));
//На выбор добавление
$form->text('</div><div class="col-sm-5">');
$form->input2(false,'new_file','checkbox','2','checked="checked"',Lang::__('Разрешение на добавление файлов'));
$form->text('</div></div></div>');  
                    
//Кнопка
$form->text('<div class="modal-footer">');
$form->submit('Отправить','submit',false,'btn btn-success');
$form->text('<a class="btn btn-default" href="index.php">Отмена</a>');
$form->text('</div>');
$form->display();
			
echo '</div>';
