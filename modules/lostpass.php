<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
engine::auth();
$templates->template(Lang::__('Восстановление пароля')); //Название
   
            
//Если авторизован пользователь то переадресация на главную
if($id_user == true) { //$id_user - id пользователя 
    header("Location: ../index.php"); //Переадресация
    exit; //Закрыть дальнейщее действие
}       

   
//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();

//Функция для работы с несколькими страницами
switch($do):

    default:
	//Если нажата кнопка submit то выполняем действие
        if(isset($_POST['submit'])) {
            //Если в $_POST найден логин то переобрабатываем в обычную $login
	    $login = filter_input(INPUT_POST,'login',FILTER_SANITIZE_STRING);
	        
	    //Проверяем введен ли логин
	    if(empty($login)) {
	        echo engine::error('<b>Обнаружены следующие ошибки:</b><br/>Вы не ввели логин'); //Ошибка
		echo engine::home(array(Lang::__('Назад'),'lostpass.php')); //Переадресация 
                exit;
	    }
			
            //Получаем необходимые данные из users
	    $lostpass = $db->get_array($db->query("SELECT * FROM `users` WHERE `nick`='" . $db->safesql($login) ."' OR `email` = '".$db->safesql($login)."'"));
            //Проверяем существует ли введенный логин
	    if ($lostpass['nick'] == false OR $lostpass['email'] == false) {
                echo engine::error('Введенный логин: не существует!'); //Ошибка
		//Если введенный ник найден в базе то 
	    }elseif($lostpass['nick'] == true) {	   
                //Выводит текст о том что найден ник введенный
                echo '<div class="mainname">Введенный Ник найден в базе!</div>';
		 //Получаем ник по id		     
                $nick = $user->users($lostpass['id'],array('nick'),true);
					 
		//Выводит необходимые данные на страницу
		echo '<div class="mainpost"><ul class="list_data clearfix">';
                	
                echo '<li class="clear clearfix">';
		echo '<span class="row_title">'.Lang::__('Логин').':</span>';
		echo '<span class="row_data"><a href="'.PROFILE.'?id='.$lostpass['id'].'">'.$nick.'</a></span><br/>';
		echo '</li>';
                
        	echo '<li class="clear clearfix">';
		echo '<span class="row_title">'.Lang::__('Последний визит').':</span>';
		echo '<span class="row_data">'.$tdate->make_date($lostpass['lastdate']).'</span><br/>';
		echo '</li>';
                
        	echo '<li class="clear clearfix">';
		echo '<span class="row_title">'.Lang::__('Эл. почта').':</span>';
		echo '<span class="row_data">'.engine::email_block($lostpass['email']).'</span><br/>';
		echo '</li>';
		    
                echo '</ul></div>';
					
                //Подтверждение о том что вы являетесь создателем профиля			
                echo '<div class="mainname">Пройдите инструкцию ниже указанное!</div>';		
                //Текст с формой
		echo '<div class="mainpost">';
		    //Форма
		    $form = new form('?do=my_profile&id='.$lostpass['id'].'');
		    $form->text(engine::warning('Подтвержению что создателем профиля <a href="'.PROFILE.'?id='.$lostpass['id'].'"><b>'.$nick.'</b></a> являюсь я!'));
		    $form->text('<div class="modal-footer">');
                    $form->submit('Подтвержение','submit',false,'btn btn-success'); //Подтверждение
		    $form->text('<a class="btn" href="?do=cancel">Отмена</a>'); //При отмене
                    $form->text('</div>');
		    $form->display();
		echo '</div>';
	    }			
	}else {
	    //Описание
            echo '<div class="mainname">Восстановление пароля</div>';
            echo '<div class="mainpost">';
            echo engine::warning('При помощи данной формы вы можете сбросить ваш текущий пароль.
                <br/>Введите ваш логин и e-mail адрес в соответствующие поля формы. <br/>Данные не чувствительны к регистру букв.<br/> 
                После отправки формы, вы получите письмо с просьбой о подтверждении запроса, <br/>а так же ссылкой на дальнейшие инструкции.');
            echo '</div>';

            //Восстановление пароля
            echo '<div class="mainname">Восстановление пароля</div>';
            echo '<div class="mainpost">';
                //Форма 
                $form = new form('?','','','class="form-horizontal"');
                //Вводите логин
                $form->text('<br/><div class="form-group">');
                $form->text('<label class="col-sm-3 control-label">Логин или E-mail</label>');
                $form->text('<div class="col-sm-8">');
                $form->input2(false,'login','text',htmlspecialchars($login),'class="form-control" placeholder="Введите логин или E-mail"');   
                $form->text('<div class="text-info">Для восстановления введите ваш Логин или E-mail</div>');
                $form->text('</div></div>');
  
                //Отправляем данные
                $form->text('<div class="modal-footer">');
                $form->submit('Напомнить пароль','submit',false,'btn btn-primary');
                $form->text('<a class="btn" href="/modules/auth.php">Отменить</a>');
                $form->text('</div></div>');
                $form->display();

            echo'</div>';
        }

        echo '</div>';

    break;
	
	
	
    //Отправка данных на Email по выбору
    case 'my_profile':
	
        //Обрабатываем $_GET id
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
         //Проверяем есть ли в $id номерованные числа
        $id = intval($id);
	
	//Получаем данные из базы users
        $profile = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$id."'"));
	    
	//Передаем данные по методу Введения нового пароля вручную
	if($glob_core['method_pass'] == 1) {
	    //Если существует Email
	    if($profile['email'] == true) {
                //Если все правильно введено и текст отправлен на Email
		$mail = new mail($glob_core['from_email']); //Email Сайта
                $mail->setFromName($glob_core['name_site']); // Устанавливаем имя в обратном адресе
				
                if($glob_core['html_email'] == 1) {
		    $email = $mail->send_html($profile['email'], "Восстановление пароля", "Чтобы восстановить ваш пароль перейдите по ссылке: <hr/>http://$_SERVER[HTTP_HOST]/modules/lostpass.php?do=change&id=" .$id. "&code=" . session_id() . "");
		}elseif($glob_core['html_email'] == 2) {
		    $email = $mail->send($profile['email'], "Восстановление пароля", "Чтобы восстановить ваш пароль перейдите по ссылке: <hr/>http://$_SERVER[HTTP_HOST]/modules/lostpass.php?do=change&id=" .$id. "&code=" . session_id() . "");}
				
                    if($email){
		        //Обновляем данные и создаем сессию для ввода нового пароля к ограничению
			$db->query("UPDATE `users` SET `lostpass_n` = '" . session_id() . "', `lostpass_t` = '".time()."' WHERE `id` = '" .$id."'");

			//Текст о том что данные отправлены на почту
                        echo engine::success(Lang::__('Все данные были отправлены на ваш E-mail зайдите на вашу почту и закончите процесс.'));
			echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php')); //Переадресация
	            }else {
		        //Если же на почту ничего не отправили то выводит ошибку ниже
                        echo engine::error(Lang::__('Возникла ошибка при восстановление пароля, пожалуйста повторите попытку!')); //Описание
			echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php')); //Переадресация
	            }		
		}
	// 2 метов получения нового пароля по генерации автоматически				
	}elseif($glob_core['method_pass'] == 2) {
            //Получаем один сгенерированный пароль
	    $generate = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$id."'"));
	    //Если введена email	
	    if($profile['email'] == true) {
		//Обновляем базу и добавляем один генерированный пароль
		$db->query("UPDATE `users` SET `generate` = '".engine::generate(6)."' WHERE `id` = '".$id."'"); 
		    //Заголовок
 		    $subject = Lang::__('Восстановление пароля');
		    //Описание
            	    $mail .= Lang::__("Вы успешно сменили пароль.")." 
		    Ваш новый пароль: ".$generate['generate']."\n\n";
		    $adds = "From: SHCMS Engine <".$glob_core['from_email'].">\r\n" . 
            	    $adds .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
			
                    //Если все правильно введено и текст отправлен на Email
		    if (mail($profile['email'], $subject, $mail, $adds)) {
			//Если все правильно то обновляем пароль и удалем старый временно-сгенерированный пароль
			$password = $db->query("UPDATE `users` SET `generate` = '',`lostpass_n` = '', `password` = '" . md5(md5($generate['generate'])) . "' WHERE `id` = '".$id."'");
			//Уведомление
			if($password == true) {
			    echo engine::success(Lang::__('Пароль успешно изменен'),Lang::__('Новый пароль отправлен вам на эл. ящик')); //Текст при успешно смене
			    echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php')); //Переадресация
            		    exit;			
			}else {
			    echo engine::error(Lang::__('Ошибка при смене пароля')); //Если ошибки
			    echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php')); //Переадресация			
			}
		    }else {
			//Если не отправлены данные то выводит ошибку
			echo '<div class="mainname">'.Lang::__('Ошибка').'</div>'; //Заголовок
                    	echo '<div class="subpost">'.Lang::__('Возникла ошибка при восстановление пароля, пожалуйста повторите попытку!').'</div>'; //Текст
			echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php')); //Переадресация
	            }               
            }
        }
        
	
    break;
	
	//Если вы выбрали метод смена пароля вручную то переадрисуюет по почте сюда
	case 'change':
	
	
	$id = (int) $_GET['id']; //Проверяем есть ли в $id номерованные числа
	
	//Из _POST в обычную $
	if(isset($_GET['code'])) {
	    $code = $_GET['code'];
	}
	//Текст при смене
	echo '<div class="mainname">'.Lang::__('Смена пароля').'</div>';
	echo '<div class="mainpost">';
	    //Получаем данные базы users
	    $new = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$id."'"));
	    
		//Если предаствленная вами сессия окончена то выводит ошибку
		if ($new['lostpass_t'] < (time()-3600)) {
			    echo engine::error(Lang::__('Время восстановления пароля исчерпано'));
                $db->query("UPDATE `users` SET `lostpass_n` = '', `lostpass_t` = '' WHERE `id` = '".$id."'");
				echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php')); //Переадресация
				exit;
		}
		
		//Если сгенерированный код не получен
		if($new['lostpass_n'] != $code) {
			    echo engine::error(Lang::__('Смена пароля невозможно'));
				echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php')); //Переадресация
				exit;		
		}
		
    //Если нажата кнопка
	if(isset($_POST['submit'])) {
	    //Из _POST в обычную $
            $password1 = filter_input(INPUT_POST,'password1',FILTER_SANITIZE_STRING);
	    $password2 = filter_input(INPUT_POST,'password2',FILTER_SANITIZE_STRING);
            
	    //Если не совпадают пароли
	    if($password1 != $password2) {
		echo engine::error(Lang::__('Введенные пароли не совпадают'));
		echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php?do=change&id='.$id.'&code='.$code.'')); //Переадресация
		exit;		
	    }
	    //Если не введен пароль
	    if(empty($password1)) {
		echo engine::error(Lang::__('Не введен пароль'));
		echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php?do=change&id='.$id.'&code='.$code.'')); //Переадресация
		exit;		
	    }
	    //Если ввденные пароли превышают ограничение по введению то выведит ошибку
	    if(mb_strlen($password1) < 2 || mb_strlen($password1) > 30) {
		echo engine::error(Lang::__('Недопустимая длина пароля')); //Текст
		echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php?do=change&id='.$id.'&code='.$code.'')); //Переадресация
		exit;			   
	    }

            //Если все правильно то обновляем старный пароль и удалем сессию
		$password = $db->query("UPDATE `users` SET `lostpass_n` = '', `password` = '" .engine::shgen($password1). "' WHERE `id` = '".$id."'");
		    //Если все правильно
		    if($password == true) {
			echo engine::success(Lang::__('Пароль успешно изменен')); //Текст
			echo engine::home(array(Lang::__('Назад'),'/modules/auth.php')); //Переадресация
                        exit;			
		    }else {
			echo engine::error(Lang::__('Ошибка при смене пароля')); //Текст
			echo engine::home(array(Lang::__('Назад'),'/modules/lostpass.php?do=change&id='.$id.'&code='.$code.'')); //Переадресация			
		    }
	}
	 
	//Форма обновления пароля
	$form = new form('?do=change&id='.$id.'&code='.$code.'','','','class="form-horizontal"');
        //Введите пароль
        $form->text('<div class="form-group">');
        $form->text('<label class="col-sm-3 control-label">Пароль</label>');
        $form->text('<div class="col-sm-8">');
        $form->input2(false,'password1','text',false,'class="form-control" placeholder="Введите пароль"');
        $form->text('</div></div><div class="row_g"></div>');
    
        //Введите пароль
        $form->text('<div class="form-group">');
        $form->text('<label class="col-sm-3 control-label">Подтверждение</label>');
        $form->text('<div class="col-sm-8">');
        $form->input2(false,'password2','text',false,'class="form-control" placeholder="Подтвердите новый пароль"'); 
        $form->text('</div></div>');
        //Кнопка
        $form->text('<div class="modal-footer">');
        $form->submit('Сменить пароль','submit',false,'btn btn-info');
        $form->text('<a class="btn" href="/modules/auth.php">Отмена</a>');
        $form->display();

    echo '</div></div>';
	
	
	break; //exit 
endswitch;	 //exit module lostpass
