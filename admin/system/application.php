<?php
    echo '<div class="header"><h1 class="page-title">Установленные приложения</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Настройки</a></li>
            <li class="active">Приложения</li>
        </ul></div>';
switch($act):

    default:
    
    echo '<div style="text-align:right;margin-bottom:5px;">';
    echo '<a class="btn btn-default" href="index.php?do=application&act=add_application">';
    echo '<img src="../icons/application/add.png">&nbsp;'.Lang::__('Добавить приложение').'</a></div>';
    echo '<div class="row"></div>';
		    
			$app = $db->query("SELECT * FROM `application` ORDER BY `posi` ASC");
        
                        echo '<div class="widget">
                        <ul class="cards list-group not-bottom no-sides">';
				while( $apps = $db->get_array( $app ) ) 
				{
			    	echo '<li class="list-group-item""><i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-cog pull-left text-info"></i>';
				
                                    echo '<h4>'.$apps['name'].'</h4>';	
                                        //Если модули от Разработчика SHCMS
					if($apps['official'] == 3) {
				    	    echo '<span class="label label-success pull-down">Официальные</span>&nbsp;';
					}
                                        //Управление
			    		echo '<span class="time">';
				
					if($apps['app_on'] == 2) 
					{
				    	echo '<span style="color:red;"><img title="'.Lang::__('Модуль отключен').'" src="../icons/application/off_app.png"></span>&nbsp;';
					}
				
					if($apps['official'] == 3) 
					{
						//Уведомляет о том что модуль разработат создателем Системы SHCMS Engine
						echo '<img title="Подтверждение о том что модуль разработан Создателем Шамсик Сердеров aka (Shamsik)" src="../icons/application/tick.png">';
					
					}elseif($apps['official'] == 0) {
				    	//Удаляет неадминистрирвоанные приложение
						echo '<a href="index.php?do=application&act=delete_application&id_delete='.$apps['id'].'"><img title="Удаляем приложение" src="../icons/application/delete.png"></a>&nbsp;';
					}
						echo ' <a href="index.php?do=application&act=down&id='.$apps['id'].'"><img title="Вниз перенести" src="../icons/application/down.png"></a>
							 <a  href="index.php?do=application&act=up&id='.$apps['id'].'"><img title="Поднять на вверх" src="../icons/application/up.png"></a> 
							 <a  href="index.php?do=application&act=editor&id_editor='.$apps['id'].'"><img src="../icons/application/pencil.png"></a>
						     </span>';
						echo '<p  style="margin-top:3px;" class="desc descl">'.$apps['text'].'</p>';
						echo '</li>';
				}	
			echo '</ul></div>';
		
	
	break;
	
	case 'up':
        
		$id = intval($_GET['id']);
		$menu = $db->get_array($db->query("SELECT * FROM `application` WHERE `id` = '{$id}'"));
		
		$db->query("UPDATE `application` SET `posi` = '".($menu['posi'])."' WHERE `posi` = '".($menu['posi']-1)."' LIMIT 1");
		$db->query("UPDATE `application` SET `posi` = '".($menu['posi']-1)."' WHERE `id` = '".$id."' LIMIT 1");
    		header('Location: index.php?do=application&act=application');
			break;

	case'down':
        
		$id = intval($_GET['id']);
		$menu = $db->get_array($db->query("SELECT * FROM `application` WHERE `id` = '{$id}'"));
		
		$db->query("UPDATE `application` SET `posi` = '".($menu['posi'])."' WHERE `posi` = '".($menu['posi']+1)."' LIMIT 1");
		$db->query("UPDATE `application` SET `posi` = '".($menu['posi']+1)."' WHERE `id` = '".$id."' LIMIT 1");
    		header('Location: index.php?do=application&act=application');
	
	break;
	
	case 'add_application':
	    $add_app = $db->get_array($db->query("SELECT * FROM `application` ORDER BY `posi` DESC"));
		
				//Если нажата кнопка то делаем следующие .....
				if(isset($_POST['submit'])) {
				//Обработка название
                $name = htmlspecialchars($_POST['name']); // Преобразуем специальные символы в HTML
				//Если название пусто но вставляем пустое значение
				if(empty($name)) {
				    $name = 'Без названия';  //Если пустое название выводит
				}
				//Обработка текста 
				$text = htmlspecialchars($_POST['text'] , ENT_QUOTES);// Преобразуем специальные символы в HTML
				$text = $db->safesql($text);
				$action = $db->safesql(intval($_POST['action'])); //Целочисленное значение
		        $version = htmlspecialchars($_POST['version']);// Преобразуем специальные символы в HTML
				$author = htmlspecialchars($_POST['author']);// Преобразуем специальные символы в HTML
				$dir = $_POST['dir']; //Папка где будет находится сам модуль
				$icon = $_POST['icon'];
				    //Если путь не введен то выводим ошибку
			        if(empty($dir)) {
					    echo engine::error(Lang::__('Введите путь к модулю')); // Текст
						echo '</div>'; // Закрытие div 
                        echo engine::home(array(Lang::__('Назад'),'index.php?do=application&act=editor&id_editor='.$id.'')); //Переадресация
		                exit;//Дальше доступ закрываем 
					}
					//Обновляем необходимые таблицы в базе данных
					$edit = $db->query("INSERT INTO `application` SET `posi` = '".($add_app['posi']+1)."',`icon` = '".$icon."',`name` = '".$db->safesql($name)."', `text` = '".$db->safesql($text)."', `version` = '".$version."',`author` = '".$author."',`dir` = '".$dir."',`app_on` = '".intval($action)."'");
	                    //Если все правильно выводит функцию ниже
		                if($edit == true) {
			                echo engine::success(Lang::__('Параметры приняты!'));
				        header('Location: index.php?do=application&act=application');
			        }else {
			            //А если жи есть ошибки выводит функцию ниже
			            echo engine::error(Lang::__('Параметры не приняты!'));
				        header('Location: index.php?do=application&act=add_application');
			        }
				}
	//Сама форма	
        $form = new form('index.php?do=application&act=add_application','','','class="form-horizontal"');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Имя приложения:').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->input2(false,'name','text',false,'class="form-control"','',false);
	$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Имя приложения не должно оставаться пустым').'</div>');//Описание			
	$form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Описание приложения:').'</label>');
        $form->text('<div class="col-sm-10">');
        $form->textarea2(false,'text',false,'','','form_control');
        $form->text('</div></div>');

        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Включить?').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->select(false,'action',array(Lang::__('Да') => 1, Lang::__('Нет') => 2),2,'','','','','class="form-control"');
        $form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Версия приложения:').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->input2(false,'version','text',false,'class="form-control"','',false);
        $form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Автор приложения:').'</label>');
        $form->text('<div class="col-sm-10">');        
	$form->input2(false,'author','text',false,'class="form-control"','',false);
        $form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Выбираем иконку:').'</label>');
        $form->text('<div class="col-sm-10">');  
        
            $icons_menu = opendir('../icons/module');
        while ($icon_menu = readdir($icons_menu)) {
            if (($icon_menu!= 'Thumbs.db' and $icon_menu!= '.' and $icon_menu!= '..')) {
                            $form->text('<div class="btn btn-default">');
			    $form->input3('<img src="../icons/module/'.$icon_menu.'">&nbsp;','icon','radio',$icon_menu);
                            $form->text('</div>');
	    }
        }
        closedir($icons_menu);
	
        $form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Директория приложения:').'</label>');
        $form->text('<div class="col-sm-10">');  
	$form->input2(false,'dir','text',false,'class="form-control"','',false);
	$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Эта директория должна существовать в /modules/').'</div>');//Описание
	$form->text('</div></div>');
        
        $form->text('<div class="row"></div>');
        $form->text('<center><div class="form-actions">');
        $form->submit(Lang::__('Установить'),'submit',false,'btn btn-success');
        $form->text('<a class="btn btn-warning" href="index.php?do=application&act=application">Отмена</a>');
        $form->text('</div></center>');
        $form->display();
	
	break;
	
	
	//Удаление приложений
	case 'delete_application':
	    $id_delete = intval($_GET['id_delete']);
		$app = $db->get_array($db->query("SELECT * FROM `application` WHERE `id` = '".intval($id_delete)."'"));
		if($id_delete == true and $app['official'] != 3) {
	    $db->query("DELETE FROM `application` WHERE `id` = '".intval($id_delete)."'");
		header('Location: index.php?do=application&act=application');
		}else {
		header('Location: index.php?do=application&act=application');		
		}
	break;
	//Редактирование приложений
	case 'editor':
	    //Выводим $id в нумерном значение
	    $id = (int) $_GET['id_editor'];
		    //Выводим все данные из базы по определенному $id
	        $app = $db->get_array($db->query("SELECT * FROM `application` WHERE `id` = '".$id."'"));
		        
				//Если нажата кнопка то делаем следующие .....
				if(isset($_POST['submit'])) {
				//Обработка название
                $name = htmlspecialchars($_POST['name']); // Преобразуем специальные символы в HTML
				//Если название пусто но вставляем пустое значение
				if(empty($name)) {
				    $name = 'Без названия';  //Если пустое название выводит
				}
				//Обработка текста 
				$text = htmlspecialchars($_POST['text'] , ENT_QUOTES);// Преобразуем специальные символы в HTML
				$text = $db->safesql($text);
				$action = $db->safesql(intval($_POST['action'])); //Целочисленное значение
		        $version = htmlspecialchars($_POST['version']);// Преобразуем специальные символы в HTML
				$author = htmlspecialchars($_POST['author']);// Преобразуем специальные символы в HTML
				$dir = $_POST['dir']; //Папка где будет находится сам модуль
				$icon = $_POST['icon'];
				    //Если путь не введен то выводим ошибку
			        if(!$dir) {
					    echo engine::error(Lang::__('Введите путь к модулю')); // Текст
						echo '</div>'; // Закрытие div 
                        echo engine::home(array(Lang::__('Назад'),'index.php?do=application&act=editor&id_editor='.$id.'')); //Переадресация
		                exit;//Дальше доступ закрываем 
					}
					//Обновляем необходимые таблицы в базе данных
					$edit = $db->query("UPDATE `application` SET `icon` = '".$icon."',`name` = '".$db->safesql($name)."', `text` = '".$db->safesql($text)."', `version` = '".$version."',`author` = '".$author."',`dir` = '".$dir."',`app_on` = '".intval($action)."' WHERE `id` = '".$id."'");
	                    //Если все правильно выводит функцию ниже
		                if($edit == true) {
			                echo engine::success(Lang::__('Параметры приняты!'));
				        header('Location: index.php?do=application&act=editor&id_editor='.$id.'');
			        }else {
			            //А если жи есть ошибки выводит функцию ниже
			            echo engine::error(Lang::__('Параметры не приняты!'));
				        header('Location: index.php?do=application&act=editor&id_editor='.$id.'');
			        }
				}
			//Сама форма	
        $form = new form('index.php?do=application&act=editor&id_editor='.$id.'');
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Имя приложения:').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->input2(false,'name','text',$app['name'],'class="form-control"','',false);
	$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Имя приложения не должно оставаться пустым').'</div>');//Описание			
	$form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Описание приложения:').'</label>');
        $form->text('<div class="col-sm-10">');
        $form->textarea2(false,'text',$app['text'],'','','form_control');
        $form->text('</div></div>');

        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Включить?').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->select(false,'action',array(Lang::__('Да') => 1, Lang::__('Нет') => 2),$app['app_on'],'','','','','class="form-control"');
        $form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Версия приложения:').'</label>');
        $form->text('<div class="col-sm-10">');
	$form->input2(false,'version','text',$app['version'],'class="form-control"','',false);
        $form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Автор приложения:').'</label>');
        $form->text('<div class="col-sm-10">');        
	$form->input2(false,'author','text',$app['author'],'class="form-control"','',false);
        $form->text('</div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Выбираем иконку:').'</label>');
        $form->text('<div class="col-sm-10">');  
      
            $icons_menu = opendir('../icons/module');
        while ($icon_menu = readdir($icons_menu)) {
            if (($icon_menu!= 'Thumbs.db' and $icon_menu!= '.' and $icon_menu!= '..')) {
                            $form->text('<div class="btn btn-default">');
			    $form->input3('<img src="../icons/module/'.$icon_menu.'">&nbsp;','icon','radio',$icon_menu,$app['icon'] == $icon_menu ? "checked='checked'" : NULL);
                            $form->text('</div>');
	    }
        }
        closedir($icons_menu);
	
        $form->text('<br/><br/></div></div>');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Директория приложения:').'</label>');
        $form->text('<div class="col-sm-10">');  
	$form->input2(false,'dir','text',$app['dir'],'class="form-control"','',false);
	$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Эта директория должна существовать в /modules/').'</div>');//Описание
	$form->text('</div></div>');
        
        $form->text('<div class="row"></div>');
        $form->text('<center><div class="form-actions">');
        $form->submit(Lang::__('Изменить'),'submit',false,'btn btn-success');
        $form->text('<a class="btn btn-warning" href="index.php?do=application&act=application">Отмена</a>');
        $form->text('</div></center>');
        $form->display();
		
	break;
endswitch;