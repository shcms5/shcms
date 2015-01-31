<?php
//Настройки
switch($act):

//По Умолчанию
default:
    echo '<div class="header"><h1 class="page-title">Настроки</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Настройки</li>
        </ul></div>';
	//Вывод данных из json
        $string = file_get_contents(H.'engine/widget/filejson/admin_system.json');
        $jsond = $json->decode($string);
        
	$view .= '<div class="widget">
            <ul class="cards list-group not-bottom no-sides">';
        
		foreach($jsond as $key => $value) 
		{
		    $view .= '<li class="list-group-item" style="float: left;width: 50%; ">';				
		    $view .= '<i class="fa-2x padding-top-small padding-bottom padding-right-small fa '.$value['icon'].' pull-left text-info"></i>';
		    $view .= '<h4><a href="/admin/system/'.$value['path'].'">'.$key.'</a></h4>';
		    $view .= ' <span class="info small">'.$value['text'].'</span>';
		    $view .= '</li>';
		}	

			$view .= '</ul></div>';
    echo  $view;	        
        
break;

//Функция общих настроек
case 'all_setting':
    echo '<div class="header"><h1 class="page-title">Общие настройки</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li><a href="index.php?do=setting">Настройки</a></li>
            <li class="active">Общие настройки</li>
        </ul></div>';

if(isset($_POST['submit'])) {

     // * all function $_POST
    if(empty($_POST['name_site']) or empty($_POST['description']) or empty($_POST['keywords'])) {
	    
		//Проверяем введена ли Название сайта
	    if(empty($_POST['name_site'])) {
	        echo engine::error(Lang::__('Введите название сайта'));
		}
		    //Проверяем введена ли Описание сайта
		    if(empty($_POST['description'])) {
		        echo engine::error(Lang::__('Введите описание сайта'));
		    } 
			    //Проверяем введены ли ключевые слова сайта
		        if(empty($_POST['keywords'])) {
		            echo engine::error(Lang::__('Введите ключевые слова сайта'));
		        }
				
                $editor = intval($_POST['editor']);
				    echo '</div>';
		            echo engine::home(array(Lang::__('Назад'),'index.php?do=setting&act=all_setting'));
		            exit;
	}else  {
	    //Заливаем данные в базу
	    $ok_query = $db->query("UPDATE `system_settings` SET `editor` = '".intval($editor)."',`name_site` = '".$db->safesql($_POST['name_site'])."', `description` = '".$db->safesql($_POST['description'])."', `keywords` = '".$db->safesql($_POST['keywords'])."'");
	        //Если все правильно выводит функцию ниже
		    if($ok_query == true) {
			    echo engine::success(Lang::__('Параметры приняты!'));
				header('Location: index.php?do=setting&act=all_setting');
			}else {
			//А если жи есть ошибки выводит функцию ниже
			    echo engine::error(Lang::__('Параметры не приняты!'));
				header('Location: index.php?do=setting&act=all_setting');
			}
	
	}
	
	

}
//Форма HTML
 //Передача данных через POST
$form = new form('index.php?do=setting&act=all_setting','','','class="form-horizontal"');

//Название сайта
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название сайта').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name_site','text',$glob_core['name_site'],'class="form-control"','',false); //Название сайта
$form->text('<span class="desc descl">'.Lang::__('Используется в навигационном меню, заголовке страницы').'</span>');//Описание
$form->text('</div></div>');

//Описание сайта
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Описание сайта').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'description',$glob_core['description'],'','','form_control');
//Описание
$form->text('<span class="desc descl">'.Lang::__('Краткое описание сайта').'</span>');
$form->text('</div></div>');

//Ключевые слова
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Ключевые слова').'</label>');
$form->text('<div class="col-sm-10">');
 //Ключевые слова для сайта
$form->textarea2(false,'keywords',$glob_core['keywords'],'','','form_control');
 //Находим сколько же ключевых слов использовано
$key_count = count(explode(',',$glob_core['keywords']));
//Показывает все ключевые слова
$form->text('<span class="desc descl">'.Lang::__('Всего ключевых слов в keywords %s',$key_count).'</span><br/>');
//Описание;ы
$form->text('<span class="desc descl">'.Lang::__('После каждой фразы ставить &Prime;<b>,</b>&Prime; без кавычек').'</span>');
$form->text('</div></div>');

//Редактор страниц
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Редактор страниц').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'editor',array('Стандартный' => 1),$glob_core['editor'],'','','','','class="form-control"');
//Описание
$form->text('<span class="desc descl">'.Lang::__('Выберите редактор страниц').'</span>');
$form->text('</div></div>');
//Кнопка сохранения
$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
 //Передаем при нажатие на кнопку
$form->submit(Lang::__('Применить'),'submit',false,'btn btn-success');
//Если хотите отменить
$form->text('<a class="btn btn-warning" href="index.php?do=setting">'.Lang::__('Отменить').'</a></div></center>');
$form->display();
break;

//Отключение форума и чата 
case 'off_module':
    
    echo '<div class="header"><h1 class="page-title">Отключение форума и чата</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li><a href="index.php?do=setting">Настройки</a></li>
            <li class="active">Отключение форума и чата</li>
        </ul></div>';
    
//Отключение форума
//Вывод всех данных из таблицы off_modules
$off_modul = $db->get_array($db->query("SELECT * FROM `off_modules`"));
//Если нажата кнопка обрабатываем переданные данные
if(isset($_POST['submit1'])) {
    
	//Обрабатывает html select проверяем действительно ли там находится цифра
    $off_forum = (int) $_POST['off_forum'];
    
	//Обрабатываем текст и убираем лишние символы, значение
	$text = htmlspecialchars($_POST['text']);
    
	//Если база пуста , то создаем там раздел
	if($off_modul['off_forum'] == false and $off_modul['off_chat'] == false) {
	    //Передача данных в базу .......
		$off_for = $db->query("INSERT INTO `off_modules` (`off_forum`,`text_forum`,`time_forum`) VALUES ('".intval($off_forum)."','".$db->safesql($text)."','".time()."')");
	    //Если все верно то переадресуем на предыдущую страницу
		if($off_for == true) {
			header('Location: index.php?do=setting&act=off_module');
			exit;
			//Если нет выводит ошибку
		} else {
		    echo engine::error(Lang::__('Ошибка при отключении форума!'));
			header('Refresh: 1; url= index.php?do=setting&act=off_module');
			exit;
		}
		//Если же в базе есть таблица то это функция обновляет данные в базе
	}elseif($off_modul['off_forum'] == true) { 
	    //Обновляем ... И и заменяем нужные параметры
		$off_for = $db->query("UPDATE `off_modules` SET `off_forum` = '".intval($off_forum)."',`text_forum` = '".$db->safesql($text)."',`time_forum` = '".time()."'");
	    if($off_for == true) {
		//Если  все правильно
		    echo engine::success(Lang::__('Отключение форума прошла успешно!'));
			header('Location: index.php?do=setting&act=off_module');
			exit;
		} else {
		//Если есть ошибки то выводим ошибку ... 
		    echo engine::error(Lang::__('Ошибка при отключении форума!'));
			header('Location: index.php?do=setting&act=off_module');
			exit;
		}
	}
}

//Форма HTML
$form = new form('index.php?do=setting&act=off_module','','','class="form-horizontal"');
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Отключение форума').'</label>');
$form->text('<div class="col-sm-10">');
//Отключить не отключать форум
$form->select(false,'off_forum',array('Да' => 1,'Нет' => 2),$off_modul['off_forum'],'','','','','class="form-control"');
$form->text('</div></div>');
//Причина отключения
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Причина').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'text',$off_modul['text_forum'],'','','form_control');
$form->text('</div></div>');
//Применяем параметры
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Применить настройки'),'submit1',true,'btn btn-success');
$form->text('</div></center><br/><div class="row"></div>');
$form->display();



//Отключение чата
//Если нажата кнопка обрабатываем переданные данные
if(isset($_POST['submit2'])) {
    
    
		//Обрабатывает html select проверяем действительно ли там находится цифра
    $off_chat = (int) $_POST['off_chat'];
    
	//Обрабатываем текст и убираем лишние символы, значение
	$text_chat = htmlspecialchars($_POST['text_chat']);
    
	//Если база пуста , то создаем там раздел
	if($off_modul['off_forum'] == false and $off_modul['off_chat'] == false) {
	    //Передача данных в базу .......
		$off_for = $db->query("INSERT INTO `off_modules` (`off_chat`,`text_chat`,`time_chat`) VALUES ('".intval($off_chat)."','".$db->safesql($text_chat)."','".time()."')");
	    //Если все верно то переадресуем на предыдущую страницу
		if($off_for == true) {
			header('Location: index.php?do=setting&act=off_module');
			exit;
			//Если нет выводит ошибку
		} else {
		    echo engine::error(Lang::__('Ошибка при отключении чата!'));
			header('Refresh: 1; url= index.php?do=setting&act=off_module');
			exit;
		}
		//Если же в базе есть таблица то это функция обновляет данные в базе
	}elseif($off_modul['off_chat'] == true) { 
	    //Обновляем ... И и заменяем нужные параметры
		$off_for = $db->query("UPDATE `off_modules` SET `off_chat` = '".intval($off_chat)."',`text_chat` = '".$db->safesql($text_chat)."',`time_chat` = '".time()."'");
	    if($off_for == true) {
		//Если  все правильно
		    echo engine::success(Lang::__('Отключение чата прошла успешно!'));
			header('Refresh: 1; url= index.php?do=setting&act=off_module');
			exit;
		} else {
		//Если есть ошибки то выводим ошибку ... 
		    echo engine::error(Lang::__('Ошибка при отключении чата!'));
			header('Refresh: 1; url= index.php?do=setting&act=off_module');
			exit;
		}
	}
}

//Форма HTML
$form = new form('index.php?do=setting&act=off_module','','','class="form-horizontal"');
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Отключение чата').'</label>');
$form->text('<div class="col-sm-10">');
//Отключить не отключать форум
$form->select(false,'off_chat',array('Да' => 1,'Нет' => 2),$off_modul['off_chat'],'','','','','class="form-control"');
$form->text('</div></div>');
//Причина отключения
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Причина').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'text_chat',$off_modul['text_chat'],'','','form_control');
$form->text('</div></div>');
//Применяем параметры
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Применить настройки'),'submit2',true,'btn btn-success');
$form->text('</div></center><br/><div class="row"></div>');
$form->display();

break;


//Настройка Безопастности
case 'security':

    echo '<div class="header"><h1 class="page-title">Настройка безопасности</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li><a href="index.php?do=setting">Настройки</a></li>
            <li class="active">Настройка безопасности</li>
        </ul></div>';

    if(isset($_POST['submit'])) {
	    //Метод восстановления пароля
	        $method_pass = (int) $_POST['method_pass'];
				//Количество неудачных попыток авторизоваться
				$kol_auth = (int) $_POST['kol_auth'];
		
					//Если в параметре ничего не указана то по значение будет сохранятся 0
					if($kol_auth == false) {
 		    			$kol_auth = 0; // 0 
					}
	        			//Уведомлять при регистрации нового пользователя	
						 $reg_new = (int) $_POST['reg_new'];
							//Отключить регистрацию
	 						$not_reg = (int) $_POST['not_reg'];
							
			$mysql_ok = $db->query("UPDATE `system_settings` SET `un_auth` = '".intval($kol_auth)."', `notify_reg` = '".intval($reg_new)."', `off_reg` = '".intval($not_reg)."', `method_pass` = '".intval($method_pass)."'");				
		        if($mysql_ok == true) {
		        //Если  все правильно
		            echo engine::success(Lang::__('Параметры приняты!'));
			        header('Location: index.php?do=setting&act=security');
			        exit;
		        } else {
		        //Если есть ошибки то выводим ошибку ... 
		            echo engine::error(Lang::__('Параметры не приняты!'));
			        header('Location: index.php?do=setting&act=security');
			        exit;
		        }
	}
	
$form = new form('index.php?do=setting&act=security','','','class="form-horizontal"');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Забыли пароль').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'method_pass',array('Разрешить пользователю ввести новый пароль' => 1, 'Выслать по почте случайно сгенерированный пароль' => 2),$glob_core['method_pass'],'','','','','class="form-control"');
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Рекомендуем использовать метод "Выслать по почте случайно сгенерированный пароль", поскольку это самый безопасный метод.').'</div>');//Описание;
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Неудачные попытки входа').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'kol_auth','text',$glob_core['un_auth'],'class="form-control"','',false);
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Количество неудавшихся попыток авторизоваться. <br/><b>Совет:</b> Установите 0, чтобы отключить функцию.').'</div>');//Описание;
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('При регистрации').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'reg_new',array(Lang::__('Да') => 1,Lang::__('Нет') => 2),$glob_core['notify_reg'],'','','','','class="form-control"');
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Включение опции позволяет уведомлять администрацию о каждой новой регистрации пользователя.').'</div>');//Описание;
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Отключить регистрацию?').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'not_reg',array(Lang::__('Да') => 1,Lang::__('Нет') => 2),$glob_core['off_reg'],'','','','','class="form-control"');
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Включение этой настройки полностью отключает возможность регистрации посетителей.').'</div>');//Описание;
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Версии SHCMS?').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'version',array(Lang::__('Да') => 'yes'),false,'','','','','class="form-control"');
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Показывает версии релизов SHCMS отключить нельзя').'</div>');//Описание;

$form->text('</div></div>');
$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Применить'),'submit',true,'btn btn-success');
$form->text('</div></center>');
$form->display();


break;

//Настройка Защиты
case 'security_spam':

    echo '<div class="header"><h1 class="page-title">Настройка защиты</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li><a href="index.php?do=setting">Настройки</a></li>
            <li class="active">Настройка защиты</li>
        </ul></div>';

if(isset($_POST['submit'])) {
    //Включить Антимат  
    $antimat = (int) $_POST['antimat'];	
	    //Включить Антирекламу
        $antiadv = (int) $_POST['antiadv'];
		    //Удаление ссылок из текста
            $antilink = (int) $_POST['antilink'];
		
		///Отправляем запрос в базу данных и обновляем выбранные данные
		$ok_mysql = $db->query("UPDATE `system_settings` SET `antimat` = '".intval($antimat)."',`antiadv` = '".intval($antiadv)."',`antilink` = '".intval($antilink)."'");
		        if($ok_mysql == true) {
		        //Если  все правильно
		            echo engine::success(Lang::__('Параметры приняты!'));
                            header('Location: index.php?do=setting&act=security_spam');
			    exit;
		        } else {
		        //Если есть ошибки то выводим ошибку ... 
		            echo engine::error(Lang::__('Параметры не приняты!'));
                            header('Location: index.php?do=setting&act=security_spam');
			    exit;
		        }		
}
$form = new form('index.php?do=setting&act=security_spam','','','class="form-horizontal"');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Включить Антирекламу:').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'antiadv',array(Lang::__('Да') => 1, Lang::__('Нет') => 2),$glob_core['antiadv'],'','','','','class="form-control"');
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Запрещает использование рекламы в постах.').'</div>');//Описание;
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Включить Антимат:').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'antimat',array(Lang::__('Да') => 1, Lang::__('Нет') => 2),$glob_core['antimat'],'','','','','class="form-control"');
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Запрещает использование цензурных слов в постах.').'</div>');//Описание;
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Ссылки в текстах:').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'antilink',array(Lang::__('Удалить') => 1, Lang::__('Оставить') => 2),$glob_core['antilink'],'','','','','class="form-control"');
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('Удаляет ссылки из постов если они недопустимы').'</div>');//Описание;
$form->text('</div></div>');
$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Применить'),'submit',true,'btn btn-success');
$form->text('</div></center>');
$form->display();

break;

case 'message_options':

    echo '<div class="header"><h1 class="page-title">Настройки личных сообщений</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li><a href="index.php?do=setting">Настройки</a></li>
            <li class="active">Настройки личных сообщений</li>
        </ul></div>';

if(isset($_POST['submit'])) {

    $ls_message = (int) $_POST['list_message'];
        if($ls_message == false) {
		    $ls_message = 10;
		}
	$on_mail = (int) $_POST['on_mail'];	
	
		///Отправляем запрос в базу данных и обновляем выбранные данные
		$ok_mysql = $db->query("UPDATE `system_settings` SET `on_mail` = '".intval($on_mail)."',`ls_message` = '".intval($ls_message)."'");
		        if($ok_mysql == true) {
		        //Если  все правильно
		            echo engine::success(Lang::__('Параметры приняты!'));
                            header('Location: index.php?do=setting&act=message_options');
			    exit;
		        } else {
		        //Если есть ошибки то выводим ошибку ... 
		            echo engine::error(Lang::__('Параметры не приняты!'));
                            header('Location: index.php?do=setting&act=message_options');
			    exit;
		        }	
}

$form = new form('index.php?do=setting&act=message_options','','','class="form-horizontal"');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Количество ЛС на странице').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'list_message','text',$glob_core['ls_message'],'class="form-control"','',false);
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('По умолчанию - 10, 0 - Не допускается').'</div>');//Описание;
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Включить почту:').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'on_mail',array(Lang::__('Да') => 1, Lang::__('Нет') => 2),$glob_core['on_mail'],'','','','','class="form-control"');
$form->text('<div style="color: #969a9d;" class="desc">'.Lang::__('<b>Совет:</b> Не отключать почту').'</div>');//Описание;
$form->text('</div></div>');
$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Применить'),'submit',true,'btn btn-success');
$form->text('</div></center>');
$form->display();


break;

case 'advertisements':
    
    echo '<div class="header"><h1 class="page-title">Настройка рекламы</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li><a href="index.php?do=setting">Настройки</a></li>
            <li class="active">Настройка рекламы</li>
        </ul></div>';
    
if(!isset($_GET['add_advertisements'])) {
echo '<div style="text-align:right;margin-bottom:3px;">';
echo '<a class="btn btn-default" href="index.php?do=setting&act=advertisements&add_advertisements&active=add"><img src="../icons/system/add.png">&nbsp;'.Lang::__('Добавить рекламу').'</a>';

echo '</div>';


echo '<div class="row">';
echo '<div class="col-sm-6 col-md-6">';
echo '<div class="panel panel-default">';
    echo '<div class="panel-heading no-collapse">Активная реклама</div>';
        echo '<table class="table table-bordered table-striped"><thead>';
        echo '<tr>';
            echo '<th>Название</th>';
            echo '<th>Адрес</th>';
            echo '<th>Действие</th>';
        echo '</tr></thead>';
        echo '<tbody>';
    $advs = $db->query("SELECT * FROM `advertisement`");
        while($adv = $db->get_array($advs)) {
            if (strtotime(date('d.m.Y')) < strtotime($adv['stop'])) {
            echo '<tr class="active">
                  <td>'.$adv['name'].'</td>
                  <td>'.$adv['link'].'</td>
                  <td><center>
                  <a href="index.php?do=setting&act=advertisements&add_advertisements&active=editor_adv&id='.$adv['id'].'"><img src="/engine/template/icons/editor.png"></a>
                  <a href="index.php?do=setting&act=advertisements&add_advertisements&active=delete&id='.$adv['id'].'"><img src="/engine/template/icons/delete.png"></a>    
                </center></td>
                </tr>';
        }
        }
        echo '</tbody></table>';
echo '</div></div>';

//Неактивные рекламы
echo '<div class="col-sm-6 col-md-6">
        <div class="panel panel-default">';
echo '<div class="panel-heading no-collapse">Срок действия истек</div>';
    echo '<table class="table table-bordered table-striped"><thead>';
    echo '<tr>';
        echo '<th>Название</th>';
        echo '<th>Адрес</th>';
        echo '<th>Действие</th>';
    echo '</tr></thead>';
    
    echo '<tbody>';
    $advl = $db->query("SELECT * FROM `advertisement`");
        while($advn = $db->get_array($advl)) {
            if (strtotime(date('d.m.Y')) >= strtotime($advn['stop'])) {    
                echo '<tr class="warning">
                  <td>'.$advn['name'].'</td>
                  <td>'.$advn['link'].'</td>
                  <td><center>
                  <a href="index.php?do=setting&act=advertisements&add_advertisements&active=editor_adv&id='.$advn['id'].'"><img src="/engine/template/icons/editor.png"></a>
                  <a href="index.php?do=setting&act=advertisements&add_advertisements&active=delete&id='.$advn['id'].'"><img src="/engine/template/icons/delete.png"></a>    
                </center></td>
                </tr>';
            }
        }    
    echo '</tbody></table>';
    
echo '</div></div>';
echo '</div>';

						
}elseif(isset($_GET['add_advertisements'])) {

switch($active):

case 'editor_adv':

//Проверяем находился ли в $_GET номер или другое значение если номер то пускаем дальше
$id = (int) $_GET['id'];

$adv_html = $db->get_array($db->query("SELECT * FROM `advertisement` WHERE `id` = '".intval($id)."'"));

if(isset($_POST['submit'])) {

     //Обрабатываем название
    $name = engine::proc_name($_POST['name']);
     //Обрабатываем ссылку
    $link = engine::input_text($_POST['link']);	
	//Обработает только числовое значение
	$group_rekl = (int) $_POST['group_rekl'];
	//Обработает только числовое значение
	$activation = (int) $_POST['activation'];
	if(isset($_POST['start'])) {
	    $start = $_POST['start'];
	}
	if(isset($_POST['stop'])) {
	    $stop = $_POST['stop'];
	}		
	//Проверяем имеется ли пусты пункты
    if(empty($name) or empty($link) or  empty($start) or empty($stop)) {
	    
		//Название обрабатываем
	    if(empty($name)) {
		    echo engine::error(Lang::__('Введите название ссылки'));
		}
		//HTML Code обрабатываем
		if(empty($link)) {
		    echo engine::error(Lang::__('Введите адрес сайта'));
		}
		//Время добавления обрабатываем
	    if(empty($start)) {
		    echo engine::error(Lang::__('Введите дату начала рекламы'));
		}
		//Время отключения обрабатываем
		if(empty($stop)) {
		    echo engine::error(Lang::__('Введите дату закрытия рекламы'));
		}
 		    echo '</div>'; //До закрытия закрываем div
		    echo engine::home(array(Lang::__('Назад'),'index.php?do=setting&act=advertisements&add_advertisements&active=editor_adv&id='.$id.'')); //Переадресация
		    exit;	//Дальше закрыт доступ
	
	
	}
	
   $ok_mysql = $db->query("UPDATE `advertisement` SET `name` = '".$db->safesql($name)."', `link` = '".$link."',`start` = '".$start."',`stop` = '".$stop."', `group_rekl` = '".intval($group_rekl)."', `active` = '".$activation."', `alt` = '".$db->safesql($preg_alt[1])."' WHERE `id` = '".intval($id)."'");    
		    
		    if($ok_mysql == true) {
			    echo engine::success(Lang::__('Реклама успешно добавлено!'));
				header('Location: index.php?do=setting&act=advertisements&add_advertisements&active=editor_adv&id='.$id.'');
			}else {
			    echo engine::error(Lang::__('Реклама не добавлено!'));
				header('Location: index.php?do=setting&act=advertisements&add_advertisements&active=editor_adv&id='.$id.'');
			}
			
			}


$form = new form('index.php?do=setting&act=advertisements&add_advertisements&active=editor_adv&id='.$adv_html['id'],'','','class="form-horizontal"');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название ссылки:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',$adv_html['name'],'class="form-control"','',false);
$form->text('<div class="desc" style="color:#969a9d;">'.Lang::__('Вводите название рекламы').'</div>');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Адрес сайта:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'link','text',$adv_html['link'],'class="form-control"','',false);
$form->text('<div class="desc" style="color:#969a9d;">'.Lang::__('Вводите адрес сайта для переадресации').'</div>');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Рекламу увидят:').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'group_rekl',array(Lang::__('Все') => 0,Lang::__('Администраторы') => 1,Lang::__('Пользователи') => 2,Lang::__('Гости') => 3),$adv_html['group_rekl'],'<optgroup label="'.Lang::__('Выберите пункт').'">','</optgroup>','','','class="form-control"');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Активировать').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'activation',array(Lang::__('Да') => 1,Lang::__('Нет') => 2),$adv_html['active'],'','','','','class="form-control"');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Начала работы:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'start','text',$adv_html['start'],'class="form-control"','',false);
$form->text('<div class="desc" style="color:#969a9d;">'.Lang::__('Дата началы рекламы').'</div>');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Окончание работы:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'stop','text',$adv_html['stop'],'class="form-control"','',false);
$form->text('<div class="desc" style="color:#969a9d;">'.Lang::__('Дата отключения рекламы').'</div>');
$form->text('</div></div>');
$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Применить'),'submit',false,'btn btn-success');
$form->text('<a class="btn btn-warning" href="index.php?do=setting&act=advertisements">Отмена</a>');
$form->text('</div></center>');
$form->display();


break;
//Удаляем рекламу
case 'delete':

//Проверяем находился ли в $_GET номер или другое значение если номер то пускаем дальше
$id = (int) $_GET['id'];

//Удаление ...
if(isset($id)) {

    $db->query("DELETE FROM `advertisement` WHERE `id` = '".intval($id)."'"); //Удаляем из базы рекламу
 	header("Location: index.php?do=setting&act=advertisements"); //Идет переадресации на предыдущую страницу
}

break;


case 'add3': 

if(isset($_POST['submit'])) {

     //Обрабатываем название
    $name = engine::proc_name($_POST['name']);
     //Обрабатываем ссылку
    $link = engine::input_text($_POST['link']);	
	//Обработает только числовое значение
	$group_rekl = (int) $_POST['group_rekl'];
	//Обработает только числовое значение
	$activation = (int) $_POST['activation'];
		if(isset($_POST['start'])) {
	        $start = $_POST['start'];
	}
	if(isset($_POST['stop'])) {
	    $stop = $_POST['stop'];
	}	
	//Проверяем имеется ли пусты пункты
    if(empty($name) or empty($link) or  empty($start) or empty($stop)) {
	    
		//Название обрабатываем
	    if(empty($name)) {
		    echo engine::error(Lang::__('Введите название ссылки'));
		}
		//HTML Code обрабатываем
		if(empty($link)) {
		    echo engine::error(Lang::__('Введите адрес сайта'));
		}
		//Время добавления обрабатываем
	    if(empty($start)) {
		    echo engine::error(Lang::__('Введите дату начала рекламы'));
		}
		//Время отключения обрабатываем
		if(empty($stop)) {
		    echo engine::error(Lang::__('Введите дату закрытия рекламы'));
		}
		    echo engine::home(array(Lang::__('Назад'),'index.php?do=setting&act=advertisements&add_advertisements&active=add3')); //Переадресация
		    exit;	//Дальше закрыт доступ
	
	
	}
	

			$ok_mysql = $db->query('INSERT INTO `advertisement` (`name`,`images`,`link`,`start`,`stop`,`group_rekl`,`active`,`alt`) VALUES ("'.$db->safesql($name).'","","'.$db->safesql($link).'","'.$start.'","'.$stop.'","'.intval($group_rekl).'","'.intval($activation).'","'.$db->safesql($preg_alt[1]).'")');
                
		    if($ok_mysql == true) {
			    echo engine::success(Lang::__('Реклама успешно добавлено!'));
				header('Location: index.php?do=setting&act=advertisements');
			}else {
			    echo engine::error(Lang::__('Реклама не добавлено!'));
				header('Location: index.php?do=setting&act=advertisements&add_advertisements&active=add');
			}
			
			}

$form = new form('index.php?do=setting&act=advertisements&add_advertisements&active=add3','','','class="form-horizontal"');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название ссылки:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',false,'class="form-control"','',false);
$form->text('<div class="desc" style="color:#969a9d;">'.Lang::__('Вводите название рекламы').'</div>');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Адрес сайта:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'link','text','http://','class="form-control"','',false);
$form->text('<div class="desc" style="color:#969a9d;">'.Lang::__('Вводите адрес сайта для переадресации').'</div>');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Рекламу увидят:').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'group_rekl',array(Lang::__('Все') => 0,Lang::__('Администраторы') => 1,Lang::__('Пользователи') => 2,Lang::__('Гости') => 3),'','<optgroup label="'.Lang::__('Выберите пункт').'">','</optgroup>','','','class="form-control"');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Активировать').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'activation',array(Lang::__('Да') => 1,Lang::__('Нет') => 2),false,'','','','','class="form-control"');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Начала работы:').'</label>');
$form->text('<div class="col-sm-10">');
$form->text("<div class='input-group date' id='datetimepicker1'>");
$form->text('<input name="start" type="text" class="form-control" data-date-format="DD.MM.YYYY"/>');
$form->text('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>');
$form->text('</span></div>');
$form->text('<div class="desc" style="color:#969a9d;">'.Lang::__('Дата включения рекламы').'</div>');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Окончание работы:').'</label>');
$form->text('<div class="col-sm-10">');
$form->text("<div class='input-group date' id='datetimepicker2'>");
$form->text('<input name="stop" type="text" class="form-control" data-date-format="DD.MM.YYYY"/>');
$form->text('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>');
$form->text('</span></div>');
$form->text('<div class="desc" style="color:#969a9d;">'.Lang::__('Дата отключения рекламы').'</div>');
$form->text('</div></div>');

$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Разместить рекламу'),'submit',false,'btn btn-success');
$form->text('<a class="btn btn-warning" href="index.php?do=setting&act=advertisements">Отмена</a>');
$form->text('</div></center>');
$form->display();

echo '</div>';

break;

case 'add':

if(isset($_POST['submit'])) {

    if($_POST['type_rekl'] == 1) {
	    header("Location: index.php?do=setting&act=advertisements&add_advertisements&active=add2");
	}elseif($_POST['type_rekl'] == 2) {
	    header("Location: index.php?do=setting&act=advertisements&add_advertisements&active=add3");	
	}

}
$form = new form('index.php?do=setting&act=advertisements&add_advertisements&active=add','','','class="form-horizontal"');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('<b>Тип рекламы</b>').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'type_rekl',array(Lang::__('Обычная реклама') => 2),false,'','','','','class="form-control"');
$form->text('<div class="desc" style="color: #969a9d;">При выборе <b>Обычная реклама</b>, вы входите в раздел добавления простой рекламы (Баннеры-счетчики не действуют).</div>');
$form->text('</div></div>');
$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Продолжить ').'&rarr;','submit',false,'btn btn-success');
$form->text('<a class="btn btn-warning" href="index.php?do=setting&act=advertisements">Отмена</a>');
$form->text('</div></center>');
$form->display();


break;
endswitch;


}

break;


case 'email':

    echo '<div class="header"><h1 class="page-title">Настройка Email</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li><a href="index.php?do=setting">Настройки</a></li>
            <li class="active">Настройка Email</li>
        </ul></div>';

if(isset($_POST['submit'])) {

    $html_email = (int) $_POST['html_email'];
  // * all function $_POST
		//Проверяем правильно ли введен Email
		$valid_email = filter_var($_POST['email_p'], FILTER_VALIDATE_EMAIL);
		$valid_email2 = filter_var($_POST['from_email'], FILTER_VALIDATE_EMAIL);
	   		if($valid_email === false and $valid_email === false) {
				echo  engine::error(Lang::__('Некорректный E-mail адрес'));
				echo '</div>';
		        echo engine::home(array(Lang::__('Назад'),'index.php?do=setting&act=email'));
		        exit;	
	}else  {
	
	    $ok_query = $db->query("UPDATE `system_settings` SET `html_email` = '".intval($html_email)."', `email_p` = '".$db->safesql($_POST['email_p'])."', `from_email` = '".$db->safesql($_POST['from_email'])."'");
	    
		    if($ok_query == true) {
			    echo engine::success(Lang::__('Параметры приняты!'));
				header('Location: index.php?do=setting&act=email');
			}else {
			    echo engine::error(Lang::__('Параметры не приняты!'));
				header('Location: index.php?do=setting&act=email');
			}
	
	}

}

$form = new form('index.php?do=setting&act=email','','','class="form-horizontal"');
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Метод отправки писем').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'method_email',array('Встроенный' => 1),false,'','','','','class="form-control"');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Отправка письма в HTML').'</label>');
$form->text('<div class="col-sm-10">');
$form->select(false,'html_email',array(Lang::__('Да') => 1, Lang::__('Нет') => 2),$glob_core['html_email'],'','','','','class="form-control"');
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Email адрес для писем').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'email_p','text',$glob_core['email_p'],'class="form-control"','',false);
$form->text('</div></div>');

$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Email адрес для поля От').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'from_email','text',$glob_core['from_email'],'class="form-control"','',false);
$form->text('</div></div>');


$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
$form->submit(Lang::__('Применить'),'submit',false,'btn btn-success');
$form->text('<a class="btn btn-warning" href="index.php?do=setting">Отмена</a>');
$form->text('</div></center>');
$form->display();


break;
endswitch;
