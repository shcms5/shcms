<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
    $id = intval($_GET['id']);
	engine::nullid($id);
	
	    $dirs = $db->super_query( "SELECT * FROM `gallery_dir` WHERE `id` = '".$id."'" );
		
	    if($dirs['id_user'] != $id_user) 
		{
		    header("Location: index.php");
		}
                
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Фотоальбомы</a>
            <a href="index.php?do=photo&id='.$dirs['id'].'" class="btn btn-default">Альбом '.$dirs['name'].'</a>
            <a href="index.php" class="btn btn-default disabled">Загрузить фотографию</a>   
        </div>';	
    
    if(isset($_POST['submit'])) 
	{
	        $text = $_POST['desc']; 
			
    		if ( $_FILES['image']['error'] )
       			echo engine::error(Lang::__('Ошибка при загрузке'));
    		elseif (!$_FILES['image']['size'])
        		echo engine::error(Lang::__('Содержимое файла пусто')); 
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
                        echo engine::error(Lang::__('Расширение файла не опознано'));
                    break;
                }
				if (!empty($image)) {
				        //Создаем $trans для объекта Totranslit
						$trans = new Shcms\Component\Translation\Translation();
						    //Категория куда попадет скриншот
                            $uploaddir = H.'upload/gallery/max/';					
							//Выполняем добавление
		                    $handle = new upload($_FILES['image']);
							    //если скрин доступен выполняем следующее ....
                                if ($handle->uploaded) {
									//размеры
                                    $handle->image_resize         = true;
                                    $handle->image_x = 500;
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
                                                $handle->image_resize = true;
                                                $handle->image_x = 120;
                                                $handle->image_ratio_y = true;
                                                $handle->image_convert = 'jpg';	
                                                $handle->process(H.'upload/gallery/mini/');
												
										if ($handle->processed) {		
										
										
										} else {
										    //При ошибке
                                            echo 'error : ' . $handle->error;
                                        }
                                            
                                        } else {
										    //При ошибке
                                            echo 'error : ' . $handle->error;
                                        }
									$handle->clean();	
                                }
								$name =  engine::format_r($handle->file_src_name).'.jpg';
								//Добавляем путь к скриншоту в базу
								    $time = TIME();
								    $db->query("INSERT INTO `gallery_files` (`id_user`,`id_dir`,`text`,`images`,`images2`,`time`) VALUES 
									      ('{$users[id]}','{$id}','{$text}','{$name}','{$name}','{$time}')");

                                header("Location: index.php?do=photo&id=".$id."");
								exit;
                            }
                            else {
                                engine::error(Lang::__('Изображение не добавлено'));
								echo engine::home(array(Lang::__('Назад'),'index.php?do=newphoto&id='.$id.''));
                                exit;
							}
			}	
	}	
	
echo '<div class="mainname">Загрузить фотографию</div>';	
 echo '<div class="mainpost"><ul class="list_data clearfix">';
 
    $form = new form('index.php?do=newphoto&id='.$id.'','','','class="form-horizontal" enctype="multipart/form-data"');
    $form->text('<br/><div class="form-group">');
    $form->text('<label class="col-sm-2 control-label">'.Lang::__('Выберите фото:').'</label>');
    $form->text('<div class="col-sm-10">');
    $form->text('<input id="input-2" name="image" type="file" class="file" multiple="true" data-show-upload="false" data-show-caption="true">');
    $form->text('</div></div><div class="row_g"></div>'); 
    
    $form->text('<div class="form-group">');
    $form->text('<label class="col-sm-2 control-label">'.Lang::__('Описание:').'</label>');
    $form->text('<div class="col-sm-10">');
    $form->textarea(false,'desc',false,'','','form-control');
    $form->text('</div></div>');
 
    $form->text('<div class="modal-footer">');
    $form->submit('Загрузить','submit',false,'btn btn-success');
    $form->text('<a class="btn btn-default" href="index.php?do=photo&id='.$id.'">Отмена</a>');
    $form->display();    

                
echo '</div></ul></div>';	