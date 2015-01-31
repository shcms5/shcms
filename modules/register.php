<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
//Заголовок
$templates->template('Регистрация посетителя');

//Если пользователь уже вошел на сайт то FALSE
if($id_user == true) {
    header('Location: /index.php');
}

echo engine::AngularJS();


switch($do):
    default:
    //Получение IP адреса гостя    
    $ip = engine::get_ip();
    //Создаем массив ошибок
    $error = array();
    //Обработка кнопки
    $submit = filter_input(INPUT_POST,'submit');
        //Если нажата кнопка
        if(isset($submit)) {
            //Обработа Полученного логина
	    $nick = filter_input( INPUT_POST, 'login', FILTER_SANITIZE_STRING );
            //Обработка полученного E-mail`a
            $email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
            $email = filter_var( $email, FILTER_VALIDATE_EMAIL );
            //Обработка полученного пароля
	    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	    $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
                //Если введенные пароли не совпадают
	        if( $password != $password2 ) {
                    $error['password'][] = 'Введенные пароли должны быть одинаковы';
                }
                //Если введенный пароль меньше 5 символов
	        if( strlen($password) < 5 ) {
                    $error['password'][] = 'Пароль должен быть выше 5 символов';
                }
                //Если Ник превышает 30 символов
	        if( engine::strlen_shcms( $nick,'utf-8' ) > 30 or engine::strlen_shcms( trim( $nick ), 'utf-8' ) < 3) {
                    $error['login'][] = 'Недопустимая длина ника';
                }
                //Проверка на недоступных символов
	        if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $nick ) ) {
                    $error['login'][] = 'Ваш ник недопустим к регистрации';
                }
                //Проверяет введено ли в начале Буквы а не Номера
		if( preg_match('/[0-9]+/',engine::format_r($nick))) {
                    $error['login'][] = 'В начале запрещено использовать числовые значение'; 
                }
                //Проверяем введенные Email
		if( empty( $email ) or strlen( $email ) > 50 OR @count( explode( '@',$email ) ) != 2 ) {
                    $error['email'][] =  'Введенный Email неверный';
                }
                //Проверяем Ник
	        if( strpos( strtolower( $nick ), '.php' ) !== false ) {
                    $error['login'][] =  'Введенный ник недопустим';
                }
                //Проверяем Ник на ограниченные ошибки
		if( stripos( urlencode($nick), '%AD') !== FALSE) {
		    $error['login'][] = 'Введенный ник недопустим';
		}
                //Срезаем ненужные пустые значение из Ника
		if( function_exists('mb_strtolower') ) {
		    $nick = mb_strtolower($nick, 'utf-8');
		} else {
		    $nick = strtolower( $nick );
		}
            //Проверям На свободность ника    
            $cnick = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE nick = '$nick'" );
                //Выводим параметр
		if( $cnick['count'] ) {
                    $error['login'][] =  'Введенный Логин уже существует';
                }
            //Проверям на свободность почты    
	    $row = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE email = '$email'" );
                //Выводим параметр
		if( $row['count'] ) {
                    $error['email'][] =  'Введенный Email уже существует';
                }
            //Проверяем была ли у вас раньше Регистрация на сайте    

            //Проверяем е
	    if(empty($error)) {
                //Генереруем пароль для безопастности
               $shgen = engine::shgen($password);
                //Добавляем данные в базу
	        $db->query( "INSERT INTO `users` (`nick`,`password`,`email`,`reg_date`,`lastdate`,`group`,`logged_ip`) VALUES ('".$db->safesql($nick)."','".$shgen."','".$db->safesql($email)."','$_date','$_date','1','$ip')" );
	        //Получаем последний id
                $id = $db->insert_id();
                //Запускаем Куки и добавляем данные
		setcookie( "user_id_shcms", $id, 365 );
		setcookie( "password_shcms", $shgen, 365 );
                //Запуск Сессии и добавляем данные
		$_SESSION['user_id_shcms'] = $id;
		$_SESSION['password_shcms'] = $shgen;
		    header("Location: ../index.php");
	    }
	}
        
echo '<div class="mainname">';
echo '<img src="/engine/template/icons/info.png">&nbsp;';
echo Lang::__('Зарегистрироваться на этом сайте');
echo '</div>';
echo '<div class="mainpost"><br/>';
    
    $form = new Shcms\Component\Form\Form();

    //Открываем форму
    echo $form->open(array('name' => 'myForm','ng' => 'FormController','action' => '?','class' => 'form-horizontal','id' => 'passwordForm'));
    
    echo '<div class="form-group">';
    echo '<label class="col-sm-3 control-label">Логин</label>';
    echo '<div class="col-sm-8">';
    echo $form->input(array('name' => 'login','value' => htmlspecialchars($nick),'class' => 'form-control','ng' => 'ng-model="form.login" ng-minlength="3" ng-maxlength="26"','required' => '','placeholder' => 'Введите Логин'));  
    //Ошибки при регистрации Логина
    if(isset($error['login'])) {
        echo '<span class="text-danger small">'.implode('<br/>', $error['login']).'</span>';
    }else {
        echo '<span class="text-muted small">От 3 до <span id="chars_login" class="charsl">26</span> символов</span>';
        echo '<div class="text-danger right" ng-messages="myForm.login.$error">
            <div ng-message="required">Логин пустой</div>
            <div ng-message="minlength">Логин больше 3 символов</div>
            <div ng-message="maxlength">Логин меньше 26 символов</div>';
        echo '</div>';
        
    }
    echo '</div></div><div class="row_g"></div>';
   
    
    echo '<div class="form-group">';
    echo '<label class="col-sm-3 control-label">Email</label>';
    echo '<div class="col-sm-8">';
    echo $form->input(array('name' => 'email','type' => 'email' ,'value' => htmlspecialchars($email),'class' => 'form-control','ng' => 'ng-model="form.email"','required' => ''));
    //Ошибки при регистрации почты
    if(isset($error['email'])) {
        echo '<p class="text-danger small">'.implode('<br/>', $error['email']).'</p>';
    }else {
        echo '<span class="text-muted small">E-mail необходим для восстановления пароля</span>';
        echo '<div class="text-danger right" ng-messages="myForm.email.$error">
            <div ng-message="required">Введите E-mail</div>
            <div ng-message="email">Неверный E-mail</div>';
        echo '</div>';
                
    }
    echo '</div></div><div class="row_g"></div>';
    

    
    echo '<div class="form-group">';
    echo '<label class="col-sm-3 control-label">Пароль</label>';
    echo '<div class="col-sm-8">';
    echo '<div class="input-group">';
    echo $form->input(array('name' => 'password','type' => 'password','class' => 'form-control pwd','id' => 'password1','autocomplete' => 'autocomplete','ng' => 'ng-model="form.password"','required' => ''));
    echo '<span class="input-group-btn">';
    echo '<button  style="margin-bottom:20px;"  class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>';
    echo '</span>  </div>';
    //Ошибки при регистрации пароля
    if(isset($error['password'])) {
        echo '<p class="text-danger small">'.implode('<br/>', $error['password']).'</p>';
    }else {
        //Первый блок
        echo '<div class="col-md-6">';
        echo '<span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 символов<br></span>';
        echo '<span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Одну заглавную букву<br/></span>';
        echo '</div>';
        //Второй блок
        echo '<div class="col-md-6">';
        echo '<span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Одну строчную букву<br></span>';
        echo '<span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Номерной символ</span>';
        echo '</div>';
        
    }   
    echo '</div></div><div class="row_g"></div>';  
    
    
    //Подтверждение пароля
    echo '<div class="form-group">';
    echo '<label class="col-sm-3 control-label">Подтверите</label>';
    echo '<div class="col-sm-8">';
    echo $form->input(array('name' => 'password2','type' => 'password','class' => 'form-control','id' => 'password2','ng' => 'ng-model="form.password2"','required' => ''));
    if(isset($error['password'])) {
        echo '<p class="text-danger small">'.implode('<br/>', $error['password2']).'</p>';
    }else {
        echo '<div class="col-md-6">';
        echo '<span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Совпадение паролей</span>';
        echo '</div>';
    }
    
    
    //Кнопка регистрации
    echo '<div class="modal-footer">'; 
    echo $form->submit(array('name' => 'submit' ,'class' =>'btn btn-success','value' => 'Регистрация','ng' => 'ng-disabled="myForm.$invalid"'));
    echo '<a class="btn" href="/index.php">Отмена</a>';
    echo $form->close();
       
    echo '</div></div>';
    break;
    
endswitch;
