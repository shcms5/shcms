<?php
switch($act):
    
    default:
        
        $error = array();
        
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
    
        //Обработка формы публикации
        $addnews = filter_input(INPUT_POST,'addnews',FILTER_SANITIZE_NUMBER_INT);
        $addnews = intval($addnews);
        //Обработка добавление комментариев
        $addcomm = filter_input(INPUT_POST,'addcomm',FILTER_SANITIZE_NUMBER_INT);
        $addcomm = intval($addcomm);
        //Обработка на ограничение добавлений
        $noall = filter_input(INPUT_POST,'noall',FILTER_SANITIZE_NUMBER_INT);
        $noall = intval($noall);
        //Обработка Автора 
        $anonim = filter_input(INPUT_POST,'anonim',FILTER_SANITIZE_NUMBER_INT);
        $anonim = intval($anonim);        
        //Обработка даты
        $date = filter_input(INPUT_POST,'date',FILTER_SANITIZE_STRING);
	
if(isset($submit)) {
    
    //Проверяем существует ли название	
    if (!empty($_FILES['image'])) {
    	if ($_FILES['image']['error'])
       	    $error['image'][] = Lang::__('Ошибка при загрузке');
    	elseif (!$_FILES['image']['size'])
            $error['image'][] = Lang::__('Содержимое файла пусто'); 
	else {
            $info = pathinfo($_FILES['image']['name']);
		switch (strtolower($info['extension'])) {
		    //JPG
                    case 'jpg':
                        $image = @imagecreatefromjpeg($_FILES['image']['tmp_name']);
                    break;
                    //JPEG
		    case 'jpeg':
                        $image = @imagecreatefromjpeg($_FILES['image']['tmp_name']);
                    break;
		    //GIF
                    case 'gif':
                        $image = @imagecreatefromgif($_FILES['image']['tmp_name']);
                    break;
		    //PNG
                    case 'png':
                        $image = @imagecreatefrompng($_FILES['image']['tmp_name']);
                    break;
		    //По умолчанию
                    default:
                        $error['image'][] = Lang::__('Расширение файла не опознано');
                    break;
                }
		    
            if (!empty($image)) {
		//Категория куда попадет скриншот
                $uploaddir = H.'upload/news/image/';
		//Название транслированное
		$end_name = engine::format_r($_FILES["image"]["name"],'.');				
		//Выполняем добавление
		$handle = new upload($_FILES['image']);
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
                                $handle->clean();
                            } else {
				//При ошибке
                                echo 'error : ' . $handle->error;
                            }
                    }else {
                        header('Location: index.php');
                        exit;
	            }
                    
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
                    $db->query("INSERT INTO `news` (`id_user`,`id_cat`,`title`,`image`,`text`,`cr_news`,`date`,`time`,`addnews`,`addcomm`,`noall`,`anonim`) VALUES ('".$id_user."','".intval($category)."','".$db->safesql($name)."','".$db->safesql($end_name).".jpg','".$db->safesql($text)."','".$db->safesql($cr_news)."','{$date}','".time()."','{$addnews}','{$addcomm}','{$noall}','{$anonim}')");
                    header('Location: index.php');
                    exit;	
                }
	}
    }
    }
}

        echo '<div class="form-horizontal">';
        //Форма публикация новостей
	$form = new form('index.php?do=nnews','','','enctype="multipart/form-data"');
        
        $form->text('<br/><div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Заголовок').'</label>');
        $form->text('<div class="col-sm-10">');
        $form->input2(false,'name','text',$name,'class="form-control"');
        
                if(isset($error['name'])) {
                    $form->text('<span style="color:red"><small>' . implode('<br />', $error['name']) . '</small></span>');
                }
                
        $form->text('</div></div>');
        
        $form->text('<br/><div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Картинка').'</label>');
        $form->text('<div class="col-sm-10">');
        $form->text('<input id="input-2" name="image" type="file" class="file" multiple="true" data-show-upload="false" data-show-caption="true">');
        
                if(isset($error['image'])) {
                    $form->text('<span style="color:red"><small>' . implode('<br />', $error['image']) . '</small></span>');
                }
                
        $form->text('</div></div>');        
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Дата').'</label>');
        $form->text('<div class="col-sm-10">');
        $form->text("<div class='input-group date' id='datetimepicker1'>");
        $form->text('<input name="date" type="text" class="form-control" size="20" data-date-format="DD.MM.YYYY"/>');
        $form->text('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>');
        $form->text('</span></div>');
        $form->text('</div></div>');

        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Категория').'</label>');
        $form->text('<div class="col-sm-10">');
        $form->select2(false,'category','*','news_category','id','name','class="form-control"');
        $form->text('</div></div>');

        $form->text('<div class="form-group">');
           
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Полное описание').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->textarea(false,'text',$text,'','','form_control');
            
            if(isset($error['text'])) {
                    $form->text('<span style="color:red"><small>' . implode('<br />', $error['text']) . '</small></span>');
                }
        $form->text('</div></div>');
        
	$form->text('<div class="form-group">');
           
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Краткое описание').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->textarea(false,'cr_news',$cr_news,'','','form_control','2');
            
            if(isset($error['cr_news'])) {
                $form->text('<span style="color:red"><small>' . implode('<br />', $error['cr_news']) . '</small></span>');
            }    
        $form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Дополнение').'</label>');
        $form->text('<div class="col-sm-10">');
        
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox1">');
            $form->text('<input name="addnews" class="sr-only" type="checkbox" value="1"> <span class="checkbox-label">Публиковать новость</span>');
            $form->text('</label></div>');
            
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox2">');
            $form->text('<input name="addcomm" class="sr-only" type="checkbox" value="1"> <span class="checkbox-label">Не добавлять комментарии</span>');
            $form->text('</label></div>');
            
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox3">');
            $form->text('<input name="noall" class="sr-only" type="checkbox" value="1"> <span class="checkbox-label">Не добавлять в Общий раздел</span>');
            $form->text('</label></div><br/><br/>');
            
            $form->text('<div class="col-lg-3"><label class="checkbox-custom checkbox-inline" data-initialize="checkbox" id="myCheckbox4gy">');
            $form->text('<input name="anonim" class="sr-only" type="checkbox" value="1"> <span class="checkbox-label">Опубликовал Автор/Аноним</span>');
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