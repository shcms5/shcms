<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

$tdate = new Shcms\Component\Data\Bundle\DateType();

switch($do):
	    
    //По умолчанию
    default:
	//Уведомления
        echo '<div class="mainname">'.Lang::__('Уведомления').'</div>';
			
	//Выводим счетчик постов
        $row = $db->get_array($db->query("SELECT COUNT(*) FROM `friends` WHERE `id_friends` = '".$id_user."' AND `approved` = '0'"));
	
	//Выводим счетчик новых регистраций
        $row1 = $db->get_array($db->query("SELECT COUNT(*) FROM `users` WHERE `reg_date` > '".(time()-86400)."'"));
        //Счетчик новых писем
        $mail = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging` WHERE `id_post` = '".$id_user."' AND `action` = '0'"));

	//Вывод данных из json
        $string = file_get_contents(H.'engine/widget/filejson/notification.json');
        $jsond = $json->decode($string);
   
            //Выводим данные
	    foreach($jsond as $key => $value) {
                
                //Если Раздел Друзей то добавляем счетчик
		if($key == 'Уведомления в друзья') {
		    $count = '<span class="time">'.$row[0].'</span>';
		}elseif($key == 'Новые сообщения'){
		    $count = '<span class="time">'.$mail[0].'</span>';
		}
		//Новую регистрацию видят только администраторы
                if($key == 'Новые регистрации') {
		    if($users['group'] == 15 and $glob_core['notify_reg'] == 1) {
			echo '<div class="posts_gl">';			
			echo '<table cellspacing="0" callpadding="0" width="100%">';
			echo '<tr><td class="icons"><img src="/engine/template/icons/'. $value['icon'] .'"></td>';
		    	echo '<td class="name" colspan="10"><b><a href="'.$value['path'].'">'.$key.'</a><span class="time">'.$row1[0].'</span></td></tr>';
			echo '<tr><td class="content" colspan="10">'. $value['text'] .'</td></tr>';
			echo '</table></div>';
                    }
		}else {
		    echo '<div class="posts_gl">';			
		    echo '<table cellspacing="0" callpadding="0" width="100%">';
		    echo '<tr><td class="icons"><img src="/engine/template/icons/'. $value['icon'] .'"></td>';
		    echo '<td class="name" colspan="10"><b><a href="'.$value['path'].'">'.$key.'</a>'.$count.'</td></tr>';
		    echo '<tr><td class="content" colspan="10">'. $value['text'] .'</td></tr>';
		    echo '</table></div>';
		}			
	    }
	//Переадресация
        echo engine::home(array(Lang::__('Назад'),'/modules/menu.php'));
	
    break;
		
    //Уведомления о новых регистрациях пользователей
    case 'new_reg':
	if($users['group'] == 15 and $glob_core['notify_reg'] == 1) {
	    echo '<div class="mainname">'.Lang::__('Новые пользователи').'</div>';
            
                $usern = $db->query("SELECT * FROM `users` WHERE `reg_date` > '".(time()-86400)."' ORDER BY `reg_date` DESC");
                    
                    while($userr = $db->get_array($usern)) {
			echo '<div class="posts_gl">';
                        echo '<table cellspacing="0" callpadding="0" width="100%"><tr>';
			echo  '<td class="icons"><img src="/engine/template/icons/user.png"></td>';
			echo '<td class="name" colspan="10"><b><a href="/modules/profile.php?id='.$userr['id'].'">'.$userr['nick'].'</a></b>';
			echo '<span class="time">'.date::make_date($userr['reg_date']).'</span></td>';	
			echo '</tr><tr><td class="content" colspan="10">'.Lang::__('Последний визит').'&nbsp;:&nbsp;'.date::make_date($userr['lastdate']).'</td>';
			echo  '</tr></table></div>';
		    }
			echo engine::home(array(Lang::__('Назад'),'/modules/profile.php?act=notificationlog'));	
                
	}else {
		 header('Location: /modules/profile.php?act=notificationlog');
        }			
		
    break;
		
    //Уведомления в друзьях
    case 'friends':
		//Навигация перехода	
		echo '<div class="btn-margin">';
		echo '<a class="btn btn-info" href="/modules/friends.php">'.Lang::__('Друзья').'</a>&nbsp;&nbsp;';
		echo '<a class="btn btn-info disabled" href="/modules/profile.php?act=notificationlog&do=friends">';
                echo Lang::__('Друзья в ожидании утверждения').'</a></div>';
                
                        
                echo '<div class="mainname">'.Lang::__('Уведомления в друзьях').'</div>';
                echo '<ul class="mainpost">'; 
			
		//Проверка если существует $_GET['accept']
		if(isset($_GET['accept']) and is_numeric($_GET['accept'])) {
                    
		    //Преобразуем из $_GET  в обычную $
		    $accept = (int) $_GET['accept'];
		        
		    //Выводим данные по выбранному индификатору
		    $new_insert = $db->get_array($db->query("SELECT * FROM `friends` WHERE `id` = '".$accept."'"));
			
                        //Добавляем друга
		        $db->query("INSERT INTO `friends` (`id_friends`,`id_user`,`approved`,`time`) VALUES ('".$new_insert['id_user']."','".$new_insert['id_friends']."','1','".time ()."')");
		        //Обновления о том что пользователь согласился дружить с вами
		        $db->query("UPDATE `friends` SET `approved` = '1' WHERE `id` = '".$accept."'");
		        //Переадресация на предыдущую страницу
			header("Location: /modules/profile.php?act=notificationlog&do=friends");
		}
		
		//Отклонения на добавления в друзья
		if(isset($_GET['cancel']) and is_numeric($_GET['cancel'])) {
		    
		    //Преобразуем из $_GET  в обычную $
		    $cancel = (int) $_GET['cancel'];
		    //Если вы не согласились добавить в друзья то данные о дружбе удаляются
		    $db->query("DELETE FROM `friends` WHERE `id` = '".$cancel."'");
		    //Переадресация на предыдущую страницу
		    header("Location: /modules/profile.php?act=notificationlog&do=friends");
		}
		
		//Выведим всех пользователей ожидающих дружбу
		$new = $db->query("SELECT * FROM `friends` WHERE `id_friends` = '".$id_user."' ORDER BY `approved`");
		        
                    //Проверка существуют ли новые заявки
		    if($db->num_rows($new) > 0) {
		        while($friend = $db->get_array($new)) {
                            
			    //Ник пользователя
			    $nick = $user->users($friend['id_user'],array('nick'),true);
			    //Параметры
			    echo '<li class="row3">';
                            
			    //Проверка если у вас есть не необработанные уведомления то выводим их
			    if($friend['id_friends'] == $id_user AND $friend['approved'] == 0) {
				//Информация
				echo engine::warning('<b><a href="?id='.$friend['id_user'].'">'.$nick.'</b></a>
                                - Предлагает вам дружбу <span class="time">'.$tdate->make_date($friend['time']).'</span>');
                                
                                echo'<div class="text-right">'; 
				//Соглашение на дружбу
				echo '<a class="btn btn-success" href="?act=notificationlog&do=friends&accept='.$friend['id'].'">';
                                echo '<i class="glyphicon glyphicon-ok"></i>&nbsp;'.Lang::__('Принять').'</a>&nbsp;';
				
                                //Отклонение
				echo '<a class="btn btn-danger" href="?act=notificationlog&do=friends&cancel='.$friend['id'].'">';
                                echo '<i class="glyphicon glyphicon-remove"></i>&nbsp;'.Lang::__('Отклонить').'</a>';
                                echo '</div>';
                                
                            }else {
				//Добавленным выведит пост ниже
				echo engine::success('<b><a href="?act=view&id='.$friend['id_user'].'">'.$nick.'</b></a>
                                    - Добавлен в друзья <span class="time">'.$tdate->make_date($friend['time']).'</span>');
			    }	
			    
                            echo '</li>';
			}    
		    }else {
			//Если нет заявок
                        echo engine::error(Lang::__('Новых заявок на дружбу у вас отсутствуют'));
                        echo '</div>';
			echo engine::home(array(Lang::__('Назад'),'/modules/profile.php?act=notificationlog'));
			exit; 
		    }
		echo '</ul>';
	//Переадресация		
	echo engine::home(array(Lang::__('Назад'),'/modules/profile.php?act=notificationlog'));

        break;

endswitch;