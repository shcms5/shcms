<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    $id = intval($id);	
    
    //Авторизация 
    if($id_user == false) {
        header('Location: index.php');	
    }
    //Если вместо id num попытаются вставить текст то выводит ошибку
    if (!isset($id) || !is_numeric($id)) {
        header('Location: index.php');
        exit;
    }
        //Выводит папки где $id = true
        $dir_file = $db->get_array($db->query("SELECT * FROM `files_dir` WHERE `id` = '".$id."'")); 
	//Название страницы
        $templates->template(Lang::__('Добавления файла в   '.$dir_file['name']));
                //Если количество файлов будет TRUE
		if(isset($_POST['kol_files'])){ 
		    $kol_files = intval($_POST['kol_files']);
		}elseif (isset($_SESSION['kol_files'])) {
		    $kol_files = intval($_SESSION['kol_files']);
		}else {
                    $kol_files = 1;
		    //Закидываем в сессию счетчик загружаемых файлов
                   $_SESSION['kol_files'] = $kol_files;
		}	
	
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Загрузки</a>
            <a href="#" class="btn btn-default disabled">Новый раздел</a>
            <a href="#" class="btn btn-default disabled">Добавление файла</a>
        </div>';
	
	// установить переменные
	// установить переменные
	$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : H.'upload/download/files/');
	$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
        $submit = filter_input(INPUT_POST,'submit');
	    if(isset($submit)) {
    		//Создаем массив и обрабатываем
    		$files = array();
    		    foreach ($_FILES['files'] as $k => $l) {
        		foreach ($l as $i => $v) {
            		    if (!array_key_exists($i, $files))
               			$files[$i] = array();
            			$files[$i][$k] = $v;
        		   }
    			}
    		// Теперь мы можем пройтись по $files, и кормить каждый элемент в класс
    		foreach ($files as $file) {
                    // Мы создании экземпляра класса для каждого элемента $file
        	    $handle = new Upload($file);
        	    // То мы проверяем, если файл был загружен правильно 
       		    // в временного расположение в сервере (часто это / TMP)
        	if ($handle->uploaded) {
            	    // Теперь, мы начинаем «процесс» загрузки. То есть, чтобы скопировать загруженный файл 
            	    // Из временной папки в нужное место 
            	    // It could be something like $handle->Process('/home/www/my_uploads/');
            	    $handle->Process($dir_dest);
                        // мы проверяем, если все прошло ОК
            		if ($handle->processed) {
                	    // все было хорошо!
			    //Записываем новый данные файла в базу
			    $db->query("INSERT INTO `files` (`id_dir`,`idir`,`name`,`files`,`time`,`id_user`,`filesize`) VALUES ('".$id."','".$dir_file['dir']."','".$db->safesql($file['name'])."','".$handle->file_dst_name."','".time()."','".$id_user."','".filesize($handle->file_dst_pathname)."')");
			    //Доп. описание
			    $name = '<b>Файл:</b> <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a>';
                	    $name .=  '(' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB)';
                	    //Успешно
			    header('Location: dir.php?id='.$id.'');
            		} else {
                	    // Если файл загружен не в нужной месте
			    //header('Location: dir.php?id='.$id.'');
                            exit;
            		}

        	    } else {
            		// если мы здесь, загрузка файлов на сервер не удалось по ряду причин
            		// т.е. сервер не получить файл
            		header('Location: upload.php?id='.$id.'');
                        exit;
        	    }
    		}
	    }
	
        //Форма загрузки файлов			
            $form = new form('upload.php?id='.$id.'','','','class="form-horizontal" enctype="multipart/form-data"');
            //Количество загружаемых файлов
            $form->text('<div class="mainpost" style="padding: 12px;"><div class="form-group">');

            $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Файлов:').'</label>');
            $form->text('<div class="col-sm-10">');
            
            $form->text('<div class="col-sm-6">');
            $form->text('Количество загружаемых файлов:<br/>');
	    $form->input3(false,'kol_files','text',$kol_files,'class="form-control"');
            $form->text('</div><div class="col-sm-2">');
            $form->text('<div class="modal-footer">');
	    $form->submit('Получить формы','kol_file',false,'btn btn-info');
	    $form->text('</div></div></div></div>');
            $form->text('<div class="row_g"></div>');
	    //Выводит форма добавление файлов 
	    for ($num = 0;$num < $kol_files; $num++) {
		$form->text('<span class="time">'.($num + 1).'</span>');
		$form->input3(false,'files[]','file',false,'class="filestyle" data-input="false" ');	
                $form->text('<div class="row_g"></div>');
	    }
		//Отправляем данные
                $form->text('<div class="modal-footer">');
		$form->submit('Добавить','submit',false,'btn btn-success');
                $form->text('<a class="btn btn-default" href="index.php">Отмена</a>');
                $form->text('</div></div>');
		$form->display();	
		

