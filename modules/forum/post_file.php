<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$id = intval($id);

//Если $id выдаст false 
if (!isset($id) || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}
    
switch($act):
	
    default:
        
	//Вытаскиваем из таблицы `forum_post` все данные
        $posts = $db->get_array($db->query('SELECT * FROM `forum_post` WHERE `id_top` = '.$id.' ORDER BY `id` DESC'));
	//Название стараницы
	$templates->template('Прикрепить файл к вашему посту');
	
        $files = $db->query("SELECT * FROM `forum_file` WHERE `id_them` = '".intval($id)."' and `id_post` = '".intval($posts['id'])."'");

            if($db->num_rows($files) < 1) {
                echo engine::error(Lang::__('Вы еще не добавили файлы:'));
            }else {
                echo '<div class="mainname">'.Lang::__('Прикрепленные файлы:').'</div>';

                echo '<ul class="mainpost">';
       
	            while($files_gl = $db->get_array($files)) {
                        echo '<li class="row3">';
                        echo '<img src="../../engine/template/down/'.$files_gl['type'].'.png">';
                        echo $files_gl['text'].'<span class="time">'.engine::filesize($files_gl['size']).'</span>';
                        echo '</li>';
	            }
                echo '</ul>';
            }	

        echo '<div class="mainname">'.Lang::__('Прикрепить файл').'</div>';
        echo '<div class="mainpost">';

        	// установить переменные
	$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : H.'upload/forum/files/');
	$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
        
		if(isset($_POST['submit'])) {
    		// как это несколько добавления, мы будем анализировать массив $ _FILES реорганизовать его в $ files
    		$file = $_FILES['filename'];

        		// Мы создании экземпляра класса для каждого элемента $file
        		$handle = new Upload($file);
        		// То мы проверяем, если файл был загружен правильно 
        		if ($handle->uploaded) {

            		// Теперь, мы начинаем «процесс» загрузки. То есть, чтобы скопировать загруженный файл 
            		$handle->Process($dir_dest);

            			// мы проверяем, если все прошло ОК
            			if ($handle->processed) {
			            //Записываем новый данные файла в базуhandle->file_dst_name
				    $file_text = "<a class='btn btn-small btn-success' href='post.php?id=".intval($id)."&act=download&file=".$handle->file_dst_name."'>".$handle->file_dst_name."</a>";
				    //Данные из поста
                                    $dbfile = $db->get_array($db->query("SELECT * FROM `forum_post` ORDER BY `id` DESC"));
				   //Заливаем данные файла в базу
                                    $db->query("INSERT INTO `forum_file` (`id_post`,`id_them`,`text`,`type`,`size`,`time`,`name`) VALUES ('".intval($dbfile['id'])."','".intval($id)."','".$db->safesql($file_text)."','".engine::format($handle->file_dst_name)."','".filesize($handle->file_dst_pathname)."','".time()."','".$db->safesql($handle->file_dst_name)."')");
				        //Доп. описание
					$name = '<b>Файл:</b> <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a>';
                			$name .=  '(' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB)';
					header('Location: ?id='.$id.'');
            			} else {
                		    // Если файл загружен не в нужной месте
				    echo  engine::error(Lang::__('<b>Файл не загружен на разыскиваемого месте</b>'),'<b>Ошибка:</b> '.$handle->error);
            			}

        		} else {
            		    // если мы здесь, загрузка файлов на сервер не удалось по ряду причин
            		    echo  engine::error(Lang::__('<b>Файл не загружен на сервер</b>'),'<b>Ошибка:</b> '.$handle->error);
        		}
    		
		}
                        
        //Форма загрузки файлов
				
            $form = new form('?id='.$id.'','','','enctype="multipart/form-data"');
	    //Выводит форма добавление файлов 
                $form->text('<li class="clear clearfix">');
                $form->text('<span class="row_title regstyle">'.Lang::__('Выбираем файл').':</span>');
                $form->text('<span class="row_data">');
		$form->input2(false,'filename','file',false,'class="filestyle" data-input="false"');
                $form->text('</span></li>');
		//Отправляем данные
                $form->text('<br/><div class="modal-footer">');
		$form->submit('Прикрепить','submit',false,'btn btn-success');
                $form->text('<a class="btn" href="post.php?id='.$id.'">'.Lang::__('Отменить').'</a>');
                $form->text('</div>');
		$form->display();
        echo '</div>';	
		
	break;
endswitch;	
	
?>