<?php
define('SHCMS_ENGINE',true);
include_once('admin/install/controller.php');
include_once('admin/install/template/header.php');

include_once(H.'admin/install/setup.php');

$setup = new \ShcmsSetup();

    if(file_exists(\H.'engine/config/dbconfig.php') == true) {
		include_once \H.'engine/system/core.php';
        include_once \H.'admin/install/setup/sql.php';
    }
    
switch($step):

    
default:    

    echo '<h3 class="page-header">Выбор Языка</h3>';

    echo '<form id="setup" method="post" action="?step=welcome" class="form-horizontal">';
        echo '<select name="language" id="lang" multiple class="form-control">';
            echo '<option value="ru" selected="selected" lang="ru" data-continue="Продолжить" data-installed="1">Русский</option>';
        echo '</select>';    
    echo '<div class="text-right"><br/>';
    echo '<input type="submit" value="Продолжить" class="btn btn-default">';    
    echo '</div>';
    echo '</form>';    

break;

case 'welcome':
     echo <<<HTML
<h3 class="page-header">Начало Установки</h3>
    
   <p>Добро пожаловать. Прежде чем мы начнём, потребуется информация о базе данных. 
       Вот что вам необходимо знать до начала процедуры установки.</p>
    <ol>
	<li>Имя базы данных</li>
	<li>Имя пользователя базы данных</li>
	<li>Пароль к базе данных</li>
	<li>Адрес сервера базы данных</li>
    </ol>
   <p>Скорее всего, эти данные были предоставлены вашим хостинг-провайдером. 
       Если у вас нет этой информации, свяжитесь с их службой поддержки. А если есть…</p>
    
   <p class="text-right modal-footer"> 
   <a href="install.php?step=tables" class="btn btn-default">Вперёд!</a></p>

HTML;
break; 

case 'tables':
    
    echo '<h3 class="page-header">Подключение к базе данных</h3>';
    
    $submit = filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
    $error = array();
    
    if($submit == true) {
        $host = filter_input(INPUT_POST,'server',FILTER_SANITIZE_STRING);		
        $dbname = filter_input(INPUT_POST,'dbname',FILTER_SANITIZE_STRING);	
        $uname = filter_input(INPUT_POST,'uname',FILTER_SANITIZE_STRING);	
        $pwd = filter_input(INPUT_POST,'pwd',FILTER_SANITIZE_STRING);
        
        if(empty($host)) {
            $error['host'][] = 'Введите Сервер базы данных';
        }
        if(empty($dbname)) {
            $error['dbname'][] = 'Введите Имя базы данных';
        }
        if(empty($uname)) {
            $error['uname'][] = 'Введите Имя пользователя MySQL';
        }
        if(empty($pwd)) {
            $error['pwd'][] = 'Введите Пароль пользователя MySQL';
        }  
			
        $host = str_replace ('"', '\"', str_replace ("$", "\\$", $host) );
	$dbname = str_replace ('"', '\"', str_replace ("$", "\\$", $dbname) );
	$uname = str_replace ('"', '\"', str_replace ("$", "\\$", $uname) );
	$pwd = str_replace ('"', '\"', str_replace ("$", "\\$", $pwd) );
	$mysqli = new mysqli($host, $uname, $pwd, $dbname); 
	    if ($mysqli->connect_error) {
		$error['dberror'][] = 'Ошибка подключения (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
            }else {
                $dbconfig = 
<<<HTML
<?php

define ("DBHOST", "{$host}"); 
define ("DBNAME", "{$dbname}");
define ("DBUSER", "{$uname}");
define ("DBPASS", "{$pwd}");  
define ("COLLATE", "utf8");
define ("SHCMS_PROJECT", "SHCMS Engine (version: 5.x)");

\$db = new Shcms\Options\Database\MYSQLi\MYSQLi;

\$dbase = DBDriver\DMYSQLi\DMYSQLi::getInstance(DBHOST, DBUSER, DBPASS, DBNAME);
HTML;

                $con_file = fopen("engine/config/dbconfig.php", "w+") or 
                        die("Невозможно создать файл <b>.engine/config/dbconfig.php");
                
                fwrite($con_file, $dbconfig);
                fclose($con_file);
                @chmod("engine/config/dbconfig.php", 0666);
				
                echo engine::success('Соединение установлено.. '.$mysqli->host_info.'');	
                echo '<a class="btn btn-default right" href="install.php?step=mysqli">Продолжить</a><br/>';
		exit;
            }        
        
    }
    
    $form = new Shcms\Component\Form\Form();
    
    if(isset($error['dberror'])) {
        echo engine::error(implode('<br/>', $error['dberror']));
    }else {
        echo engine::warning('Заполните информацию о подключении к базе mysql. Если вы в ней не уверены, свяжитесь с хостинг-провайдером.');
    }
        
    echo $form->open(array('action' => 'install.php?step=tables','class' => 'form-horizontal'));
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Тип Базы</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'type','value' => 'MYSQL','class' => 'form-control','disabled' => ''));
    echo '</div>';
    echo '</div>';
    
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Имя базы данных</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'dbname','value' => 'shcms','class' => 'form-control'));
    if(isset($error['dbname'])) {
        echo '<p class="text-danger small">'.implode('<br/>', $error['dbname']).'</p>';
    }
    echo '</div>';
    echo '<div class="col-sm-4">Имя базы данных, в которую вы хотите установить SHCMS.';
    echo '</div></div>';    
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Имя пользователя</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'uname','value' => 'user','class' => 'form-control'));
    if(isset($error['uname'])) {
        echo '<p class="text-danger small">'.implode('<br/>', $error['uname']).'</p>';
    }
    echo '</div>';
    echo '<div style="margin-top:5px;" class="col-sm-4">Имя пользователя MySQL';
    echo '</div></div>';  
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Пароль</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'pwd','value' => 'password','class' => 'form-control'));
    if(isset($error['pwd'])) {
        echo '<p class="text-danger small">'.implode('<br/>', $error['pwd']).'</p>';
    }
    echo '</div>';
    echo '<div style="margin-top:5px;" class="col-sm-4">Пароль пользователя MySQL';
    echo '</div></div>';      
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Сервер базы данных</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'server','value' => 'localhost','class' => 'form-control'));
    if(isset($error['host'])) {
        echo '<p class="text-danger small">'.implode('<br/>', $error['host']).'</p>';
    }
    echo '</div>';
    echo '<div class="col-sm-4">Если localhost не работает, нужно узнать правильный адрес в службе поддержки хостинг-провайдера.';
    echo '</div></div>';   
    
    echo '<div class="modal-footer">';
    echo $form->submit(array('name' => 'submit','value' => 'Отправить','class' => 'btn btn-default'));
    echo '</div>';
    
    echo $form->close();
break;    

case 'mysqli':

        $res = $db->query("SHOW TABLES");
            while($cRow = $db->get_array($res)) {
                $query = "DROP TABLE `".$cRow[0]."`";
                $db->query($query);
            }    
    
    $sql = \SQLParser::getQueriesFromFile('shcms.sql') ;
    
    foreach ($sql as $value => $key) {
        if($db->query($key) == true) {
            $error[] = 'Всё в порядке! Вы успешно прошли эту часть установки.
            SHCMS Engine теперь может подключиться к вашей базе данных.';
        }else {
            $error[] = 'Возникли ошибки при установки таблиц';
        }
    }
    
    echo '<h3 class="page-header">Установка Таблиц</h3>';
    echo engine::warning($error[0]);
    echo '<br/><div class="modal-footer">';
    echo '<a class="btn btn-default" href="install.php?step=profiles">Запустить установку</a>';
    echo '</div>';
break;

case 'profiles':
    
    echo '<h3 class="page-header">Добро пожаловать</h3>';
    echo '<p style="font-size:14px;">Добро пожаловать в пятиминутную установку SHCMS Engine! Просто заполните информацию ниже — и вперёд, к использованию  гибкой персональной платформы для публикаций в мире!</p>';

    echo '<h3 class="page-header">Требуется информация</h3>';

    echo '<p style="font-size:14px;">Пожалуйста, укажите следующую информацию. Не переживайте, потом вы всегда сможете изменить эти настройки.</p><br/>';
    
    $submit = filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
    $error = array();
    if($submit == true) {
        
        $site = filter_input(INPUT_POST,'site',FILTER_SANITIZE_STRING);
        $login = filter_input(INPUT_POST,'login',FILTER_SANITIZE_STRING);
        $pwd = filter_input(INPUT_POST,'pwd',FILTER_SANITIZE_STRING);
        $pwd2 = filter_input(INPUT_POST,'pwd2',FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST,'email',FILTER_SANITIZE_STRING);
        $support = filter_input(INPUT_POST,'support',FILTER_SANITIZE_STRING);
        
                //Название сайта не должно быть пустым
                if(empty($site)) {
                    $error['site'][] = 'Введите название вашего сайта';
                }
                
                if(empty($support)) {
                    $error['support'][] = 'Введите системный E-mail для сайта';
                }
                //Если введенные пароли не совпадают
	        if( $pwd != $pwd2 ) {
                    $error['password'][] = 'Введенные пароли должны быть одинаковы';
                }
                //Если введенный пароль меньше 5 символов
	        if( strlen($pwd) < 5 ) {
                    $error['password'][] = 'Пароль должен быть выше 5 символов';
                }
                //Если Ник превышает 30 символов
	        if( engine::strlen_shcms( $login,'utf-8' ) > 30 or engine::strlen_shcms( trim( $login ), 'utf-8' ) < 3) {
                    $error['login'][] = 'Недопустимая длина ника';
                }
                //Проверка на недоступных символов
	        if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $login ) ) {
                    $error['login'][] = 'Ваш ник недопустим к регистрации';
                }
                //Проверяет введено ли в начале Буквы а не Номера
		if( preg_match('/[0-9]+/',engine::format_r($login))) {
                    $error['login'][] = 'В начале запрещено использовать числовые значение'; 
                }
                //Проверяем введенные Email
		if( empty( $email ) or strlen( $email ) > 50 OR @count( explode( '@',$email ) ) != 2 ) {
                    $error['email'][] =  'Введенный Email неверный';
                }
                //Проверяем Ник
	        if( strpos( strtolower( $login ), '.php' ) !== false ) {
                    $error['login'][] =  'Введенный ник недопустим';
                }
                //Проверяем Ник на ограниченные ошибки
		if( stripos( urlencode($login), '%AD') !== FALSE) {
		    $error['login'][] = 'Введенный ник недопустим';
		}
                
            if(empty($error)) {
                $shgen = engine::shgen($pwd);
                $db->query("UPDATE `system_settings` SET `name_site` = '{$db->safesql($site)}',`from_email` = '{$db->safesql($support)}'");
                $mysql = $db->query("INSERT INTO `users` (`nick`,`password`,`email`,`reg_date`,`lastdate`,`group`,`key`) VALUES ('".$db->safesql($login)."','".$shgen."','".$db->safesql($email)."','".time()."','".time()."','".intval(15)."','".engine::generate(7)."')");	
            
	        if($mysql == true) {
		    header('Location: install.php?step=finish');
                    exit;
		}else {
		    header('Location: install.php?step=profiles');
                    exit;
		}
            }    
        
    }
    
   
    $form = new \Shcms\Component\Form\Form();
    $core = $db->get_array($db->query("SELECT * FROM `system_settings`"));
    
    echo $form->open(array('action' => 'install.php?step=profiles','class' => 'form-horizontal'));
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Название сайта</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'site','value' => $core['name_site'],'class' => 'form-control'));
    if(isset($error['site'])) {
        echo '<p class="text-danger">'.implode('<br/>', $error['site']).'</p>';
    }
    echo '</div>';
    echo '</div>';
 
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Имя Пользователя</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'login','placeholder' => 'Введите Имя Пользователя','value' => $login,'class' => 'form-control'));
    if(isset($error['login'])) {
        echo '<p class="text-danger">'.implode('<br/>', $error['login']).'</p>';
    }
    echo '</div>';
    echo '<div style="margin-top:5px;" class="col-sm-4">Логин больше 3 символов</div>';
    echo '</div>';    
    
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Пароль дважды</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'pwd','placeholder' => 'Введите Пароль','class' => 'form-control'));
    echo '<div style="margin-top:3px"></div>';
    echo $form->input(array('name' => 'pwd2','placeholder' => 'Введите Повторно пароль','class' => 'form-control'));    
    if(isset($error['password'])) {
        echo '<p class="text-danger">'.implode('<br/>', $error['password']).'</p>';
    }
    echo '</div>';
        echo '<div style="margin-top:5px;" class="col-sm-4">Введите сложный пароль<br/>Чтобы обезопасить Администраторскую<br/>Пример:&nbsp;&nbsp;<b>'.engine::generate(10).'</b></div>';
    echo '</div>';    
        
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Ваш Email</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'email','placeholder' => 'Введите E-mail','class' => 'form-control'));
    if(isset($error['email'])) {
        echo '<p class="text-danger">'.implode('<br/>', $error['email']).'</p>';
    }
    echo '</div>';
    echo '<div style="margin-top:5px;" class="col-sm-4">Внимательно проверьте адрес электронной почты, <br/>перед тем как продолжить.</div>';
    echo '</div>';  
    
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-3 control-label col-font-2"><b>Уведомление</b></label>';
    echo '<div class="col-sm-5">';
    echo $form->input(array('name' => 'support','value' => $core['from_email'],'class' => 'form-control'));
    if(isset($error['support'])) {
        echo '<p class="text-danger">'.implode('<br/>', $error['support']).'</p>';
    }
    echo '</div>';
    echo '<div style="margin-top:5px;" class="col-sm-4">Это системная почта для уведомлений</div>';
    echo '</div>';       
    
    
    echo '<br/><div class="modal-footer">';
    echo $form->submit(array('name' => 'submit','value' => 'Установить SHCMS Engine','class' => 'btn btn-default'));
    echo '</div>';
    
break;    

case 'finish':

    echo '<h3 class="page-header">Успешная установка SHCMS Engine</h3>';
    
    echo '<p style="font-size:15px;">Поздравляем SHCMS Engine успешно установлен. Ожидали больше шагов? Извините, что разочаровали :)</p>';
    
    echo '<p style="text-align:center;">';
    echo '<a class="btn btn-default" href="/modules/auth.php">Войти</a>';
    echo '</p>';
    
    echo engine::warning('<b>Внимание!!!</b> Для полной безопастности удалите файл install.php в корне вашего сайта');
    
break;    
endswitch;
include_once('admin/install/template/footer.php');