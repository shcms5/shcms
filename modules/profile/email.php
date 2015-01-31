<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="?id='.$id_user.'" class="btn btn-default">Профиль</a>
            <a href="profile.php?act=edit_profile" class="btn btn-default">Изменить данные</a>
            <a href="#" class="btn btn-default disabled">Изменение E-mail и пароль</a>
        </div>';

    //Обработка EMAIL Адреса
	if(isset($_POST['submit_email'])) {
            $nemail = filter_input( INPUT_POST, 'new_email', FILTER_SANITIZE_EMAIL );
            $nemail = filter_var( $nemail, FILTER_VALIDATE_EMAIL );
            $aemail = filter_input( INPUT_POST, 'act_email', FILTER_SANITIZE_EMAIL );
            $aemail = filter_var( $aemail, FILTER_VALIDATE_EMAIL );	
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);			
		
		//Проверка на совпадений email`a
		if($nemail != $aemail) {
		    echo engine::error(Lang::__('Введенные e-mail адреса не совпадают.'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');
			exit;
		}
		if( empty( $aemail ) or strlen( $aemail ) > 50 OR @count( explode( '@',$aemail ) ) != 2 ) { 
		    
			echo engine::error(Lang::__('Введенный Email неверный'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');
			exit;
		}
		
	    //Проверяем введен ли пароль
	    if(empty($password)) {
		    echo engine::error(Lang::__('Не введен пароль'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');
			exit;
	    }
		
		if(strlen($password) < 5) {
		
		    echo engine::error(Lang::__('Пароль должен быть выше 5 символов'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');
			exit;
			
		} 
			$row = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE email = '$aemail'" );
		
			if( $row['count'] ) { 
		        echo engine::error(Lang::__('Введенный Email уже существует'));
			    echo engine::home(array(Lang::__('Назад')),'?act=email');
			    exit;
            }			
		//Кадировка
		$shgen = engine::shgen($password);

		//Проверка на правильность пароля
		if($shgen != $users['password']) {
		    echo engine::error(Lang::__('Неверный текущий пароль'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');
		    exit;
		}

			$mysql = $db->query("UPDATE `users` SET `new_email` = '".$db->safesql($aemail)."' WHERE `id` = ".$id_user."");
			if($mysql == true) {
       			$mail = new Mail($users['email']); // Создаём экземпляр класса
            	$mail->setFromName("SHCMS Engine"); // Устанавливаем имя в обратном адресе
            	$mail->send($users['email'], "Информация о смене Email адреса",
				"Здравствуйте, ".$users['nick']."!<br/>
				Вы получили это письмо от  http://".$_SERVER['HTTP_HOST'].", потому что запросили изменение E-mail.<br/>
				<hr/>
				Инструкция по активации
				<hr/>
				Вам необходимо будет активировать изменение e-mail адреса, это необходимо для проверки того, что это действие сделали именно вы. Также это требуется для защиты от нежелательных злоупотреблений и спама.<br/>
				Введите указанные ниже ID пользователя и код активации (не пароль!) в соответствующие поля.<hr/>
				
				<b>ID пользователя:</b> ".$users['id']."<br/>
				<b>Ключ активации: </b> ".$users['key']."<br/>
				
				");
                header('Location: ?act=act_email');
				exit;
			}else {
				echo  engine::error(Lang::__('Ошибка при смене E-mail адреса'));
			    echo engine::home(array(Lang::__('Назад')),'?act=email');	
                exit;				
			}
	}
	//Обработка пароля 
	if(isset($_POST['submit_pass'])) {
            
			$password1 = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
			$password = filter_input(INPUT_POST, 'new_pass', FILTER_SANITIZE_STRING);
			$password2 = filter_input(INPUT_POST, 'act_pass', FILTER_SANITIZE_STRING);		 
		
		//Проверяем введен ли пароль
	    if(empty($password1)) {
		    echo engine::error(Lang::__('Не введен пароль'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');
			exit;
	    }
		//Кадировка в md5
		$shgen1 = engine::shgen($password1);

		//Проверка на правильность пароля
		if($shgen1 != $users['password']) {
		    echo engine::error(Lang::__('Неверный текущий пароль'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');
		    exit;
		}
		
		//Сравниваем на совпадение паролей
		if($password != $password2) {
		    echo engine::error(Lang::__('Введенные пароли должны быть одинаковы'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');
			exit;		
		}
		
		//Проверка пароля на допустимость символов
	    if (preg_match("/[^\da-zA-Z_]+/", $password)){
	   		echo engine::error(Lang::__('В пароле присутствуют недопустимые символы'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');			
            exit;
	    }
		//Проверяем на допустимость органиченных символов
		if(mb_strlen($password) < 5) {
	   		echo engine::error(Lang::__('Недопустимая длина пароля'));
			echo engine::home(array(Lang::__('Назад')),'?act=email');			
            exit;
	    }
		
		$shgenk = engine::shgen($password);
	
	    $mysql = $db->query("UPDATE `users` SET `new_pass` = '".$db->safesql($shgenk)."' WHERE `id` = '".$users['id']."'");
			if($mysql == true) {
       			$mail = new Mail($users['email']); // Создаём экземпляр класса
            	$mail->setFromName("SHCMS Engine"); // Устанавливаем имя в обратном адресе
            	$mail->send($users['email'], "Информация о смене Пароля",
				"Здравствуйте, ".$users['nick']."!<br/>
				Вы получили это письмо от  http://".$_SERVER['HTTP_HOST'].", потому что запросили изменение Пароля.<br/>
				<hr/>
				Инструкция по активации
				<hr/>
				Вам необходимо будет активировать изменение пароля, это необходимо для проверки того, что это действие сделали именно вы. Также это требуется для защиты от нежелательных злоупотреблений и спама.<br/>
				Введите указанные ниже код активации (не пароль!) в соответствующие поля.<hr/>
				
				<b>Ключ активации: </b> ".$users['key']."<br/>
				
				");
                header('Location: ?act=act_pass');
				exit;
			}else {
				echo  engine::error(Lang::__('Ошибка при смене Пароля'));
			    echo engine::home(array(Lang::__('Назад')),'?act=email');	
                exit;				
			}		
		
	}
    
        //Информация необходимаЯ
    echo engine::warning('<b>Внимание!</b> Действующий email адрес <b>'.$users['email'].'</b>');

    echo '<div class="mainname">'.Lang::__('Изменение e-mail адреса');
    echo '</div>';
    
    echo '<div class="mainpost">';
	if(!$users['key']) {
	    echo engine::error(Lang::__('Для смени Email адреса необходим "Ключ активации"'));
		echo '<div class="subpost">Ключ дается сразу после регистрации на сайте если он у вас отсутствует то обратитесь к администратору сайта!</div>';
		echo engine::home(array(Lang::__('Назад')),'?act=edit_profile');
		exit;
	}else {
    
        $form = new form('?act=email','','','class="form-horizontal"');
		
            //Новый email адрес
            $form->text('<br/><div class="form-group">'); 
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Новый e-mail:').'</b></label>');
            $form->text('<div class="col-sm-10">');
            $form->input2(false,'new_email','text',false,'class="form-control"');
            $form->text('</div></div><div class="row_g"></div>');
	
            //Подтвержение email адреса
            $form->text('<div class="form-group">'); 
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Повторите e-mail').'</b></label>');
            
            $form->text('<div class="col-sm-10">');
            $form->input2(false,'act_email','text',false,'class="form-control"');
            $form->text('</div></div><div class="row_g"></div>');
            
            //Действующий пароль пользователя
            $form->text('<div class="form-group">'); 
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Текущий пароль').'</b></label>');
            $form->text('<div class="col-sm-10">');
                $form->input2(false,'password','password',false,'class="form-control"');
                $form->text('</div></div>');
                
        //Кнопка Сохранения
            $form->text('<div class="modal-footer">');
            $form->submit(Lang::__('Применить'),'submit_email',false,'btn btn-success');
            $form->text('<a class="btn btn-default" href="profile.php?act=edit_profile">Отмена</a>');
            $form->text('</div>');
        
        //Выводим полную обработанную форму    
        $form->display();
            
	}	
        echo '</div>';
    //Новый пароль
    echo '<div class="mainname">'.Lang::__('Новый пароль').'</div>';
    echo '<div class="mainpost">';
    
        $form = new form('?act=email','','','class="form-horizontal"');
		
        //Действующий пароль пользователя
        $form->text('<br/><div class="form-group">'); 
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label">Текущий пароль</b></label>');
        $form->text('<div class="col-sm-10">');
            $form->input2(false,'password','password',false,'class="form-control"');
            $form->text('</div></div><div class="row"></div>');
	
        //Новый пароль
        $form->text('<br/><div class="form-group">'); 
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label">НОВЫЙ пароль</b></label>');
        $form->text('<div class="col-sm-10">');
            $form->input2(false,'new_pass','password',false,'class="form-control"');
            $form->text('</div></div><div class="row"></div>');
            
        //Подтвержение нового пароля
            $form->text('<br/><div class="form-group">'); 
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Подтверждение пароля').'</b></label>');
            $form->text('<div class="col-sm-10">');
                $form->input2(false,'act_pass','password',false,'class="form-control"');
                $form->text('</div></div>');
                
        //Кнопка Сохранения
            $form->text('<div class="modal-footer">');
            $form->submit(Lang::__('Применить'),'submit_pass',false,'btn btn-success');
            $form->text('<a class="btn btn-default" href="profile.php?act=edit_profile">Отмена</a>');
            $form->text('</div>');
        
        //Выводим полную обработанную форму    
        $form->display();
            
    echo '</div>';
	
	echo engine::home(array(Lang::__('Назад'),'/modules/profile.php?act=edit_profile'));