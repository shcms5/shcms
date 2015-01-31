<?php
switch($act):
    
    default:
     
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($id);
        
        $editl = $db->query("SELECT * FROM `news` WHERE `id` = '{$id}'");
        //Чтобы не было Непредвиденных ситуаций
        if($db->num_rows($edit1) < 1) {
            header('Location: /');
        }else {
            $edit = $db->get_array($editl);
        }
        //Создаем массив ошибок
        $error = array();
        
    //Обработка кнопки добавления
    $submit = filter_input(INPUT_POST, 'submit');
    //Обработки названия новости
    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);

    //Обработка Краткого описания
    $cr_news = filter_input(INPUT_POST, 'cr_news',FILTER_SANITIZE_STRING);

    // Полного описания
    $text = filter_input(INPUT_POST,'text', FILTER_DEFAULT);

    
        //Обработка формы публикации
        $addnews = filter_input(INPUT_POST,'addnews',FILTER_SANITIZE_NUMBER_INT);
        $addnews = intval($addnews);
        //Обработка добавление комментариев
        $addcomm = filter_input(INPUT_POST,'addcomm',FILTER_SANITIZE_NUMBER_INT);
        $addcomm = intval($addcomm);
        //Обработка Фиксации новостя
        $editm = filter_input(INPUT_POST,'editm',FILTER_SANITIZE_NUMBER_INT);
        $editm = intval($editm);        
        //Обработка на ограничение добавлений
        $noall = filter_input(INPUT_POST,'noall',FILTER_SANITIZE_NUMBER_INT);
        $noall = intval($noall);
        //Обработка Автора 
        $anonim = filter_input(INPUT_POST,'anonim',FILTER_SANITIZE_NUMBER_INT);
        $anonim = intval($anonim);        
        //Обработка даты
        $date = time();
        
        if($editm == 1) {
            $textm = 'Новость Отредактировал: '.$users['nick'].'';
        }else {
            $textm = '';
        }
	
if(isset($submit)) {

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
		elseif(empty($error)) {
                    $db->query("UPDATE `news` SET `title` = '{$name}',`text` = '{$text}',`cr_news` = '{$cr_news}',`addnews` = '{$addnews}', `addcomm` = '{$addcomm}',`noall` = '{$noall}', `anonim` = '{$anonim}', `editm` = '{$editm}',`timem` = '{$date}',`textm` = '{$textm}' WHERE `id` = '{$id}'");
                    header('Location: index.php');
                    exit;	
                }
	
    }

        echo '<div class="form-horizontal">';
        //Форма публикация новостей
	$form = new form('index.php?do=enews&id='.$id.'','','','enctype="multipart/form-data"');
        
        $form->text('<br/><div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Заголовок').'</label>');
        $form->text('<div class="col-sm-10">');
        $form->input2(false,'name','text',$edit['title'],'class="form-control"');
        
                if(isset($error['name'])) {
                    $form->text('<span style="color:red"><small>' . implode('<br />', $error['name']) . '</small></span>');
                }
                
        $form->text('</div></div>');

        $form->text('<div class="form-group">');
           
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Полное описание').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->textarea(false,'text',$edit['text'],'','','form_control');
            
            if(isset($error['text'])) {
                    $form->text('<span style="color:red"><small>' . implode('<br />', $error['text']) . '</small></span>');
                }
        $form->text('</div></div>');
        
	$form->text('<div class="form-group">');
           
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Краткое описание').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->textarea(false,'cr_news',$edit['cr_news'],'','','form_control','2');
            
            if(isset($error['cr_news'])) {
                $form->text('<span style="color:red"><small>' . implode('<br />', $error['cr_news']) . '</small></span>');
            }    
        $form->text('</div></div>');
        
   $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Изменение').'</label>');
        $form->text('<div class="col-sm-10">');
        
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox1">');
            $form->text('<input name="editm" class="sr-only" type="checkbox" value="1"' .($edit['editm']?'checked="checked"':'') . ' /> <span class="checkbox-label">Зафиксировать изменение</span>');
            $form->text('</label></div>');
                     
            
        $form->text('</div></div><hr/>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Дополнение').'</label>');
        $form->text('<div class="col-sm-10">');
        
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox1">');
            $form->text('<input name="addnews" class="sr-only" type="checkbox" value="1"' .($edit['addnews']?'checked="checked"':'') . ' /> <span class="checkbox-label">Публиковать новость</span>');
            $form->text('</label></div>');
            
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox2">');
            $form->text('<input name="addcomm" class="sr-only" type="checkbox" value="1"' .($edit['addcomm']?'checked="checked"':'') . ' /> <span class="checkbox-label">Не добавлять комментарии</span>');
            $form->text('</label></div>');
            
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox3">');
            $form->text('<input name="noall" class="sr-only" type="checkbox" value="1"' .($edit['noall']?'checked="checked"':'') . ' /> <span class="checkbox-label">Не добавлять в Общий раздел</span>');
            $form->text('</label></div><br/><br/>');
            
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox4gy">');
            $form->text('<input name="anonim" class="sr-only" type="checkbox" value="1"' .($edit['anonim']?'checked="checked"':'') . ' /> <span class="checkbox-label">Опубликовал Автор/Аноним</span>');
            $form->text('</label></div>');            
            
        $form->text('</div></div>');
        
            $form->text('<div class="row"></div>');
            
            $form->text('<center><div class="form-actions">');
	    $form->submit(Lang::__('Опубликовать'),'submit',false,'btn btn-success');	
            $form->text('<a class="btn btn-warning" href="index.php">Отмена</a>');
            $form->text('</div></center>');
	    $form->display(); //Обработка форма и вывод полей;
 		
        echo '</div>';
        
    break;    
    
    
endswitch;