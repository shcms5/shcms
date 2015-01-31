<?php  
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
//Создаем массив
$error = array();
//Обработка кнопки
$submit = filter_input(INPUT_POST,'submit',FILTER_DEFAULT);

    
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="?id='.$id_user.'" class="btn btn-default">Профиль</a>
            <a href="profile.php?act=edit_profile" class="btn btn-default">Изменить данные</a>
            <a href="#" class="btn btn-default disabled">Изменение Ника</a>
        </div>';
    
if(isset($submit)) {
    //Если пользователь сделал уже Изменение Логина
    if($users['restrict'] > time()) {
	echo engine::error('Следующая смена имени доступна '.$tdate->make_date($users['restrict']));
	echo engine::home(array(Lang::__('Назад')),'?act=edit_profile');
	exit;	

    }else {
	$db->query("UPDATE `users` SET `changes` = '0' WHERE `id` = '".$users['id']."'");
    } 
    
    //Обработка переданного пароля
    $password = $db->safesql(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) );
    //Хэшируем пароль
    $cpassword = engine::shgen($password);
    //Обработка логина
    $name = $db->safesql(filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING));
		
        //Проверяем введен ли пароль
	if(empty($cpassword)) {
            $error['password'][] = 'Не введен пароль';
	}
        //Проверяем введен ли имя
	if(empty($name)) {
	    $error['name'][] = 'Не введено Имя пользователя';
	}
		
    //Проверка На Доступность Логина
    $myname = $db->query("SELECT * FROM `users` WHERE `nick` = '{$name}'");
        if($db->num_rows($myname) > 0) {
            $error['name'][] = 'Данный Логин уже действует, выберите другой';
        }

	//Проверка на правильность пароля
	if($cpassword != $users['password']) {
            $error['password'][] = 'Неверный текущий пароль';
	}    
        
        //Проверка на недоступных символов
	if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $name ) ) {
            $error['name'][] = 'Ваш ник недопустим к регистрации';
        }
        
        //Проверяет введено ли в начале Буквы а не Номера
	if( preg_match('/[0-9]+/',engine::format_r($nick))) {
            $error['name'][] = 'В начале запрещено использовать числовые значение'; 
        }		
		
	    
    if(empty($error)) {
        $mysql = $db->query("UPDATE `users` SET `nick` = '{$name}', `changes` = '".($users['changes']+1)."',`time_name` = '".time()."',`restrict` = '".(time() + 2419200)."' WHERE `id` = '".$users['id']."'");
        header('Location: profile.php?act=playname');
        exit;
    }
}


    
	echo '<div class="mainname">'.Lang::__('Изменить имя пользователя').'</div>';
            echo '<div class="mainpost">';
            if(empty($users['time_name'])){
                $timen = 'не произходило';
            }else {
                $timen = 'c '.$tdate->make_date($users['time_name']);
            }
            

            
    	echo engine::warning('Вы сделали <b>'.$users['changes'].'</b> из <b>'.$glob_core['name_kol'].'</b>
        изменений имени пользователя '.$timen.'.<br/>
        Вам разрешено делать не более '.$glob_core['name_kol'].' изменений каждые 30 дней.');
        
        if($users['restrict'] > time()) {
	    echo engine::warning('<b>Внимание!</b> Следующая смена имени доступна '.$tdate->make_date($users['restrict']));
	    echo '<div class="modal-footer">';
            echo '<a class="btn" href="profile.php?act=edit_profile">Отмена</a>';
            echo '</div>';
        }else {
        $form = new form('?act=playname','','','class="form-horizontal"');
		
        //Действующий пароль пользователя
        $form->text('<br/><div class="form-group">'); 
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Новое имя пользователя').'</b></label>');
        $form->text('<div class="col-sm-10">');
            //Введите имя пользователя
            $form->input2(false,'name','text',false,'class="form-control"');
                //Выводятся если найдутся ошибки
                if(isset($error['name'])) {
                   $form->text('<p style="font-size:11px;" class="text-danger">'.implode('<br/>', $error['name']).'</p>');
                }else {
                    $form->text('<p class="text-mirron small">'.Lang::__('Запрещенные символы:').' [ ] | , ; </p>');
                }      
            $form->text('</div></div><div class="row_g"></div>');
	
        //Новый пароль
        $form->text('<br/><div class="form-group">'); 
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Текущий пароль').'</b></label>');
        $form->text('<div class="col-sm-10">');
            //Введите пароль пользователя
            $form->input2(false,'password','password',false,'class="form-control"');
                //Выводятся если есть ошибки
                if(isset($error['password'])) {
                   $form->text('<p style="font-size:11px;" class="text-danger">'.implode('<br/>', $error['password']).'</p>');
                }else {
                      $form->text('<p class="text-mirron small">'.Lang::__('Это гарантирует безопасность вашей учетной записи').'</p>');
                }      
            $form->text('</div></div><div class="row"></div>');
            
                
        //Кнопка Сохранения
            $form->text('<div class="modal-footer">');
            $form->submit(Lang::__('Применить'),'submit',false,'btn btn-success');
            $form->text('<a class="btn btn-default" href="profile.php?act=edit_profile">Отмена</a>');
            $form->text('</div>'); 
        
        //Выводим полную обработанную форму    
        $form->display();
            
    }
    
    echo '</div>';