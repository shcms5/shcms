<?php

if(!defined('SHCMS_ENGINE')) {
    die( "Неправильное действие" );
}

//Выводим данные всех друзей и пользователей по значениям $id and $id_user
if($id_user == true) {
    $all_friend = $db->get_array($db->query("SELECT * FROM `friends` WHERE (`id_user` = '".$id."' AND `id_friends` = '".$id_user."') OR (`id_user` = '".$id_user."' AND `id_friends` = '".$id."')"));

    switch($do):
	//Добавление пользователя в друзья
        case 'new_friend':
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($id);    
        
            //Если существует $ID  то выполнит следующие действие
            if(isset($id) and is_numeric($id)) {
	    	//Если захотите добавить самого себя то выведит сразу ошибку
		if($id_user == $id) {
		    echo engine::error(Lang::__('Данного пользователя запрещено добавить в друзья'));
		    echo engine::home(array(Lang::__('Назад'),'?id='.$id.''));
		    exit;					
		}
					
		//Проверка  прошло ли время с момента удаление друга
		$add_frend = $db->get_array($db->query("SELECT * FROM `friends_flood` WHERE `id_friends` = '".$id."' AND `id_user` = '".$id_user."'"));
						
		//Выполнение функции со временем это обязательный параметр
                // советую не убирать для точного добавления выбранного пользователя
		if(isset($add_frend['time'])) {
		    if($add_frend['time'] > time()) {
		        echo engine::error(Lang::__('В течение 10 минут вы не сможете добавить только что удаленного пользователя в друзья'));
			echo engine::home(array(Lang::__('Назад'),'?id='.$id.''));
			exit;
		    }
		}	
			
		//Проверка введенного $id
                $insert = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$id."'"));
		            
		//Проверка добавлен ли пользователь в друзья
		$select  = $db->get_array($db->query("SELECT * FROM `friends` WHERE `id_friends` = '".$id."' AND `id_user` = '".$id_user."'"));
						
		//Проверка если пользователь уже иммется в друзьях то выводит ошибку ниже
		if($select['id_friends'] == $id) {
		    echo engine::error(Lang::__('Пользователь уже у вас друзьях'));
		    echo engine::home(array(Lang::__('Назад'),'?id='.$id.''));
		    exit;
		}
					    
		//Если под введенным $id отсутствует пользователь то выведит ошибку ниже указанный
		if($id != $insert['id']) {
                     //Ошибка
		    engine::error(Lang::__('Произошли ошибки при удаление'),Lang::__('Пользователь не найден'));
                     //Переадресация 
		    echo engine::home(array(Lang::__('Назад'),'friends.php'));
		    exit;
		}
	              
                //Проверяем если пользователь не в друзьях то выводим ему кнопку добавить в друзья
	        if($all_friend['id_friends'] != $id and $id_user != $id) {
		    if($all_friend['approved'] == 0) {
			//Если же все правильно введено и нет ошибок 
                        //выполняется добавления в друзья пользователя с обеих сторон 
	                $db->query("INSERT INTO `friends` (`id_user`,`id_friends`,`approved`,`time`) VALUES ('".$id_user."','".$id."','0','".time()."')");
                        header("Location: /modules/profile.php?id=$id"); //Переадресует на переходящую страницу
		    }		
		}	
            }
        break;
	
	//Удаление пользователя из друзей
	case 'delete_friend':
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($id);    
        
            //Если существует $ID  то выполнит следующие действие
            if(isset($id) and is_numeric($id)) {
		//Проверка введенного $id
                $delete = $db->get_array($db->query("SELECT * FROM `friends` WHERE `id_friends` = '".$id."' AND `id_user` = '".$id_user."'"));
		        
		//Если под введенным $id отсутствует пользователь то выведит ошибку ниже указанный
		if($id != $delete['id_friends']) {
                    //Ошибка
		    engine::error(Lang::__('Произошли ошибки при удаление'),Lang::__('Пользователь не найден у вас в друзьях'));
		    //Переадресация 
                    echo engine::home(array(Lang::__('Назад'),'friends.php'));
	            exit;
		}
		//Если заявка отправлена		
                if($all_friend['id_friends'] == $id and $all_friend['id_user'] == $id_user) {
	            if($all_friend['approved'] == 0) { 
			echo engine::error(Lang::__('Заявка уже отправлено Дождитесь ответа'));
			exit;
		    }
		}	
	                 
                //Если же все правильно введено и нет ошибок
                // выполняется удаление пользователя с обеих сторон 
	        $db->query("DELETE FROM `friends` WHERE `id_user` = '".$id_user."' AND `id_friends` = '".$id."' OR `id_user` = '".$id."' AND `id_friends` = '".$id_user."'");
		    //Удаленного пользователя и друга добавим в список запоминаний
		    $db->query("DELETE FROM `friends_flood` WHERE `id_user` = '".$id_user."' AND `id_friends` = '".$id."' OR `id_user` = '".$id."' AND `id_friends` = '".$id_user."'");
		    $db->query("INSERT INTO `friends_flood` (`id_user`,`id_friends`,`time`) VALUES ('".$id_user."','".$id."','".(time()+600)."')");
                     //Переадресует на переходящую страницу
                    header("Location: /modules/profile.php?id=$id");  
            }
	
        break;

    endswitch;


    echo '<div style="margin-left:40%;" class="privice">';
        
	//Проверяем если пользователь не в друзьях то выводим ему кнопку добавить в друзья
	if($all_friend['id_friends'] != $id and $id_user != $id) {
            //Если нету дружбы между пользователями
            if($all_friend['approved'] == 0) {
    		echo '<a href="?do=new_friend&id='.$id.'" class="btn btn-default">';
                echo '<img src="/engine/template/icons/user_add.png">&nbsp;'.Lang::__('Добавить в друзья').'</a>&nbsp;';
            }else {
		echo '<a href="?do=delete_friend&id='.$id.'" class="btn btn-default">';
                echo '<img src="/engine/template/icons/user_add.png">&nbsp;'.Lang::__('Удалить из друзей').'</a>&nbsp;';
	    }
	}elseif($all_friend['id_friends'] == $id and $all_friend['id_user'] == $id_user) {
            if($all_friend['approved'] == 0) {
	        echo '<a class="btn btn-default disabled" href="#">Заявка отправлена</a>&nbsp;';
	    }else {   
     	 	echo '<a href="?do=delete_friend&id='.$id.'" class="btn btn-default">';
                echo '<img src="/engine/template/icons/user_add.png">&nbsp;'.Lang::__('Удалить из друзей').'</a>&nbsp;';
	    }
	}
		
        //Проверяем если пользователь является подходящий id с вашим то выведит ошибку
	if($id_user != $id) {
            //Отправка личного сообщения пользователю
	    echo '<a href="/modules/messaging.php?act=newsend&id='.$id.'" class="btn btn-info">';
            echo '<img src="/engine/template/icons/email_open1.png">&nbsp;'.Lang::__('Отправить в ЛС').'</a>&nbsp;';
        }
    echo '</div>';
    
}
