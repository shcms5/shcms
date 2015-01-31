<?php
echo '<div class="panel panel-default">
            <a href="#widget1container" class="panel-heading" data-toggle="collapse">Подробнее о шаблонах </a>
            <div id="widget1container" class="panel-body collapse in">';

    //Подключаем необходимые стили для работы с подвеской
    echo '<link rel="stylesheet" href="../../engine/skins/mirror/codemirror.css">
    <link rel=stylesheet href="../../engine/skins/mirror/lib/codemirror.css">
    <script src="../../engine/skins/mirror/lib/codemirror.js"></script>
    <script src="../../engine/skins/mirror/mode/xml/xml.js"></script>
    <script src="../../engine/skins/mirror/mode/javascript/javascript.js"></script>
    <script src="../../engine/skins/mirror/mode/css/css.js"></script>
    <script src="../../engine/skins/mirror/mode/htmlmixed/htmlmixed.js"></script>
    <script src="../../engine/skins/mirror/addon/edit/matchbrackets.js"></script>
    <style>
        .CodeMirror { width: 99%;height: 463px; border: solid 1px #BBB; }
        .CodeMirror pre { padding-left: 7px; line-height: 1.25; }
    </style>';
    
        //Если нажата кнопка сохранение ....
        if($_POST['submit']){
		    //Проверяем есть ли название
            if($_POST['filename']) { 
                $data = $_POST['fileentry']; //Парамерт []
                $data = get_magic_quotes_gpc() ? stripslashes($data) : $data;
                $fp = fopen($_POST['filename'], 'w'); //Обновляем файл выбранный
                    fwrite($fp, $data);
                    fclose($fp);
                echo engine::success(Lang::__('Файл успешно сохранен')); // Уведомление
		echo '</div>';
		echo engine::home(array(Lang::__('Назад'),'')); // переадресация
		exit;
            } 
        }
        
 //Пункт работы с шаблонами      
 switch($act):
	
    // По умолчанию
    default:
	// Путь папки
        if($_GET['path'])  {
	    $path = $_GET['path'];
        }else {
	    $path=$_GET['path']='../../templates';
        }   
		
        //Вывод
	if(strlen($_SERVER['DOCUMENT_ROOT'])<strlen($path)){
    	    echo '<p class="posts"><a href="?path='.substr($path, 0, strrpos($path, '/')).'"><strong>../'.Lang::__('назад').'</strong></a></p>'; 
	}
	
        //Получанием данные из подкаталогов
        echo '<div class="widget"><ul class="cards list-group not-bottom no-sides">';
        foreach(glob($path.'/*') as $obj) { 
            $filename=substr($obj, strlen($path)+1, strlen($obj)-strlen($path)); 
		//Если доступны папки выводим их
                if(is_dir($obj)) {
                    echo '<li class="list-group-item">';
                    echo ' <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-folder-open-o pull-left text-info"></i>';
                    echo '<a href="?do=templates&path='.$obj.'"><h4>'.$filename.'/</h4></a>';
                    echo '<p class="info small">Для смена шаблона ставьте права 777 к папке</p>';
                    echo '</li>';
		}else { 
                    if($_GET['path']) {
			//Убираем названия остовляем только расщирение файлов (Картинок)
			$file_img = engine::format($filename);
			//Если имеются такие расщирения png,gif,jpg
			if($file_img == 'png' or $file_img == 'gif' or $file_img == 'jpg') {
			    // То советую  не заполнять данный пункт!
			}elseif($file_img == 'js') { //Если есть js то выводим ее
                            
                            echo '<li class="list-group-item">';
                            echo ' <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-folder-open-o pull-left text-info"></i>';
                            echo '<a href="?do=templates&act=edit&path='.$_GET['path'].'&file='.$obj.'"><h4>'.$filename.'</h4></a>';
                            echo '<p class="info small">JS параметры</p>';
                            echo '</li>';
			
                        }elseif($file_img == 'css') { //Если имеется css файлы то выводим их
                            
                            echo '<li class="list-group-item">';
                            echo ' <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-file-text-o pull-left text-info"></i>';
                            echo '<a href="?do=templates&act=edit&path='.$_GET['path'].'&file='.$obj.'"><h4>'.$filename.'</h4></a>';
                            echo '<p class="info small">Стили шаблона</p>';
                            echo '</li>';
			
                        }elseif($filename == 'template.ini') { //Попытка вывода  template.ini файла
			    //Не нужно выводит ini параметр
			}else { //Если из выше перечисленных нету то выводим этот параметр где будут указываться все необходимы поля
			    
                            echo '<li class="list-group-item">';
                            echo ' <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-file-o pull-left text-info"></i>';
                            echo '<a href="?do=templates&act=edit&path='.$_GET['path'].'&file='.$obj.'"><h4>'.$filename.'</h4></a>';
                            echo '<p class="info small">Файл редактирования</p>';
                            echo '</li>';
			
                        }
		    }else {
			echo '<div class="row"><a href="?file='.$obj.'"></a></div>'; 
                    } 
                }
	}
	echo '</ul></div>';	            
	echo engine::home(array(Lang::__('Назад'),'index.php'));
            
    break;
            
    //Начинаем редактирование шаблона
    case 'edit':
			    
        //Получает категорию
        if($_GET['path']){
    	    echo '<form action="?do=templates&act=edit&path='.$_GET['path'].'&file='.$_GET['file'].'" method=post>'; 
	}else {
	    echo '<form action="?do=templates" method="post">'; 
        }
	 
        //ЕСЛИ файл имеется выводим 
        if($_GET['file']) { 
            $filename = $_GET['file']; 
		//Путь файла
                echo engine::success(Lang::__('Путь файла:').'<b>'.$filename.'</b>'); 
		//Небольше описание
		echo engine::success(Lang::__('Для поиска по шаблону используйте горячие клавиши: Ctrl-F начать поиск, Ctrl-G продолжить поиск.'));
                //Необходимый скрытый параметр
		echo '<input type=hidden name=filename value="'.$filename.'">'; 
        } else { 
	    echo Lang::__('Ничего не выбрано'); 
	}
	
        //Выводим в текстовое поле полученный текст
        echo '<textarea id="code" name="fileentry" class="form-control">'; 
            // Text true
	    if($_GET['file']) { 
                $filerows = file($filename); 
                    foreach($filerows as $value) { 
                        echo htmlspecialchars($value); 
                    } 
            } 
        echo '</textarea>'; 
	//Кнопка сохранения
        echo '<div style="margin-top:4px;" class="row"></div>';
        echo '<center><div class="form-actions">';     
        echo '<input class="btn btn-success" type=submit value="'.Lang::__('Сохранить').'" name="submit">';
        echo ' <a class="btn btn-warning" href="index.php?do=templates">Отмена</a>'; 
        echo '</div></center></form>';
	    //Если не понимаете для чего параметр (советую вообще ее не трогать чтобы не испортить модуль)	
	    echo '<script>
                var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                    lineNumbers: true,
                    mode: "text/html",
	                mode: "text/css",  
	                lineNumbers: true,
                    matchBrackets: true,
                    continueComments: "Enter",
                    extraKeys: {"Ctrl-Q": "toggleComment"}
                });
            </script>';
	        
        break;

        endswitch;
        
        echo '</div></div>';