<?php
			        
//Если пользователь не является автором файла ему остальные функции ограничены
if($id_user != $view_file['id_user']) {
    echo engine::error(Lang::__('Доступ ограничен'));
    echo engine::home(array('Назад','view.php?id='.$id.''));
    exit;
}		


if (!empty($_FILES['screen'])) {
    if ($_FILES['screen']['error'])
       	echo engine::error(Lang::__('Ошибка при загрузке'));
    elseif (!$_FILES['screen']['size'])
        echo engine::error(Lang::__('Содержимое файла пусто')); 
    else {
        $info = pathinfo($_FILES['screen']['name']);
				
	switch (strtolower($info['extension'])) {
	    //JPG
            case 'jpg':
                $screen = @imagecreatefromjpeg($_FILES['screen']['tmp_name']);
            break;
            //JPEG
	    case 'jpeg':
                $screen = @imagecreatefromjpeg($_FILES['screen']['tmp_name']);
            break;
	    //GIF
            case 'gif':
                $screen = @imagecreatefromgif($_FILES['screen']['tmp_name']);
            break;
            //PNG
            case 'png':
                $screen = @imagecreatefrompng($_FILES['screen']['tmp_name']);
            break;
		
            //По умолчанию
            default:
                echo engine::error(Lang::__('Расширение файла не опознано'));
            break;
        }
				
        if (!empty($screen)) {
	    //Создаем $trans для объекта Totranslit
	    $trans = new \Shcms\Component\Translation\Translation();
	    //Категория куда попадет скриншот
            $uploaddir = H.'upload/download/screen/';
	    //Название транслированное
	    $end_name = $trans->replace(engine::format_r($_FILES["screen"]["name"],'.'));				
		//Выполняем добавление
		$handle = new upload($_FILES['screen']);
		//если скрин доступен выполняем следующее ....
                if ($handle->uploaded) {
		    //даем название
                    $handle->file_new_name_body   = $end_name;
		    //размеры
                    $handle->image_resize         = true;
                    $handle->image_x              = 640;
                    $handle->image_ratio_y        = true;
		    //Конвертируем все изображение в jpg для качественности
		    $handle->image_convert = 'jpg';									
		    //Водяной знак
		    $handle->image_text            = 'SHCMS Engine'; //Временно не менять 
                    $handle->image_text_opacity    = 80;
		    //Установка цвета к водяному знаку
                    $handle->image_text_color      = '#0000FF';
                    $handle->image_text_background = '#FFFFFF';
		    //Установим значем в какой угол пойдет знак
                    $handle->image_text_x          = -5;
                    $handle->image_text_y          = -5;
                    $handle->image_text_padding    = 5;
		    //Если загрузилась то выводит 
                    $handle->process($uploaddir);
                        if ($handle->processed) {
                            //echo engine::success(Lang::__('Скриншот успешно загружен'));
                            $handle->clean();
                        } else {
			    //При ошибке
                            echo 'error : ' . $handle->error;
                        }
                }
								
	//Добавляем путь к скриншоту в базу
	if($view_file['screen'] == false) {
            $db->query("UPDATE `files` SET `screen` = '".$db->safesql($end_name).".jpg' WHERE `id` = '".$id."'");
	}elseif($view_file['screen_2'] == false) {
	    $db->query("UPDATE `files` SET `screen_2` = '".$db->safesql($end_name).".jpg' WHERE `id` = '".$id."'");								
	}elseif($view_file['screen_3'] == false) {
	    $db->query("UPDATE `files` SET `screen_3` = '".$db->safesql($end_name).".jpg' WHERE `id` = '".$id."'");								
	}else {
	    echo engine::info('Загрузили максимальное количество!');
	}
	
            //Если скриншот сохранился то выводит это
            header('Location: ?act=screen&id='.$id.'');
	    exit;
            
        }else {
            header('Location: ?act=screen&id='.$id.'');
            exit;
	}
    }
}
		
			
//Если скриншоты отсутствуют	
if($view_file['screen'] == false) {
    echo engine::warning(Lang::__('Скриншотов нет!'));
}else {
    //Если имеется хотя бы один скриншот выведится это функция и дополнительные скриншоты тоже
    for ($i = 0; $i < 1; $i++) {
	if($view_file['screen'] == true) { 
	    $count = '<div class="subpost">Скриншот №('.($i + 1).')
		<span class="time"><a href="view.php?act=screen&id='.$id.'&delete=1"><img src="/engine/template/icons/delete.png"></a></span>
		<br/><img width="45" title="Скриншот № '.($i + 1).'" src="/upload/download/screen/'.$view_file['screen'].'"></div>';
	}if($view_file['screen_2'] == true) {
	    $count .= '<div class="subpost">Скриншот №('.($i + 2).')
		<span class="time"><a href="view.php?act=screen&id='.$id.'&delete=2"><img src="/engine/template/icons/delete.png"></a></span>
		<br/><img width="45" title="Скриншот № '.($i + 2).'" src="/upload/download/screen/'.$view_file['screen_2'].'"></div>';	
	}if($view_file['screen_3'] == true) {
	    $count .= '<div class="subpost">Скриншот №('.($i + 3).')
		<span class="time"><a href="view.php?act=screen&id='.$id.'&delete=3"><img src="/engine/template/icons/delete.png"></a></span>
		<br/><img width="45" title="Скриншот № '.($i + 3).'" src="/upload/download/screen/'.$view_file['screen_3'].'"></div>';
	}
    //Выведит по назначению
    echo $count;

    }
}
			
//Удаление скриншотов $_GET[delete]
    if(isset($_GET['delete'])) {
	//Удаляем основной скрин			
	if($_GET['delete'] == 1) {
	    if(@unlink(H.'upload/download/screen/'.$view_file['screen'].'')) {
		$db->query("UPDATE `files` SET  `screen` =  '' WHERE `id` = '".$id."'");
		header('Location: ?act=screen&id='.$id.'');
                exit;					
	    }
	}
				
        //Удаляем второй скрин
	if($_GET['delete'] == 2) {
	    if(@unlink(H.'upload/download/screen/'.$view_file['screen_2'].'')) {
		$db->query("UPDATE `files` SET  `screen_2` =  '' WHERE `id` = '".$id."'");
		header('Location: ?act=screen&id='.$id.'');		
                exit;					
	    }
	}
				
        //Удаляем третий скрин				
	if($_GET['delete'] == 3) {
	    if(@unlink(H.'upload/download/screen/'.$view_file['screen_3'].'')) {
		$db->query("UPDATE `files` SET  `screen_3` =  '' WHERE `id` = '".$id."'");
                header('Location: ?act=screen&id='.$id.'');
                exit;					
	    }
	}				
    }
			
			    
//Если все скриншоты загружены то выведит это
if($view_file['screen']== true and $view_file['screen_2']== true and $view_file['screen_3'] == true) {
    echo engine::info(Lang::__('Загружено максимальное количество скриншотов'));	
    echo engine::home(array(Lang::__('Назад'),'view.php?id='.$id.'')); //Переадресация	
    exit;
}

//Форма загрузки скриншотов
echo '<div class="mainpost">';	
	
    $form = new form('?act=screen&id='.$id.'','','','enctype="multipart/form-data"');
	//Выводит форма добавление скриншотов
	if($view_file['screen'] == false) {
            $form->text('Выберите скриншот:<input id="input-2" name="screen" type="file" class="file form-control" multiple="true" data-show-upload="false" data-show-caption="true">');
	} elseif($view_file['screen_2'] == false) {			
            $form->text('Выберите скриншот:<input id="input-2" name="screen" type="file" class="file form-control" multiple="true" data-show-upload="false" data-show-caption="true">');
	}elseif($view_file['screen_3'] == false) {			
            $form->text('Выберите скриншот:<input id="input-2" name="screen" type="file" class="file form-control" multiple="true" data-show-upload="false" data-show-caption="true">');
	}
    		
    //Отправляем данные	
    $form->text('<br/><div class="modal-footer">');
    $form->submit('Отправить','submit',false,'btn btn-success');
    $form->text('<a class="btn" href="?id='.$id.'">'.Lang::__('Отменить').'</a>');
    $form->text('</div>');
    $form->display();
            
echo '</div>';		

