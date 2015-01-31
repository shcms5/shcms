<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
$templates->template('Друзья');

//Если не авторизован пользователь то переадресация на главную
if(empty($id_user)) {
    header("Location: /index.php");
    exit;
}
            
//Вывод всех данных
$all_friend = $db->get_array($db->query("SELECT * FROM `friends`"));

//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();

switch($act):
    //По умолчанию 
    default:
        //Выводим счетчик постов
        $row = $db->get_array($db->query("SELECT COUNT(*) FROM `friends` WHERE `id_friends` = '".$id_user."' AND `approved` = '0'"));
	echo '<div class="btn-margin">';	    
        echo '<a class="btn btn-info disabled" href="">'.Lang::__('Друзья').'</a>&nbsp;';
	echo '<a class="btn btn-info" href="/modules/profile.php?act=notificationlog&do=friends">'.Lang::__('Друзья в ожидании утверждения').' '.$row[0].'</a>';
    echo '</div>';
            echo '<div class="mainname">'.Lang::__('Мои друзья').'</div>';
	    echo '<ul class="mainpost">';
                //Выводим счетчик постов
    		$row = $db->get_array($db->query("SELECT COUNT(*) FROM `friends` WHERE `id_user` = '".$users['id']."' AND `approved` = '1' "));
                //Получаем навигацию
                $newlist = new Navigation($row[0],10, true); 
        
		//Если друзей больше 1 выводит из базы данные
                if($row[0] > 0) {
                    $friends = $db->query('SELECT * FROM `friends` WHERE `id_user` = "'.$users['id'].'" AND `approved` = "1" ORDER BY `id` DESC '. $newlist->limit().'');
		}else {
		    echo engine::error(Lang::__('У вас нет друзей!'));
		    echo '</div>';
	            exit;
		}	
	        
                //Выводим всех ваших друзей если есть
                while($friend = $db->get_array($friends)) {
		    //Определим id друга
                    $frend = $db->get_array($db->query('SELECT * FROM `users` WHERE `id` = "'.$friend['id_friends'].'"'));
	            //Ник Друга
	            $nick = $user->users($frend['id'],array('nick'),true);
                            
                    echo '<li class="row3">';
			//Выводим путь к профилю друга
	                echo '<b><a href="/modules/profile.php?id='.$frend['id'].'">'.$nick.'</a></b>';
			//Действие с другом
	                echo '<span style="margin-top:4px;" class="right_images">';
	                    echo '<a class="btn btn-small btn-success" title="'.Lang::__('Написать личное сообщение').'"  href="messaging.php?act=newSend&userID='.$frend['id'].'">';
                            echo '<i class="glyphicon glyphicon-envelope"></i></a>&nbsp;';
	                    echo '<a class="btn btn-small btn-danger" title="'.Lang::__('Удалить из друзей').'" href="?act=delete_frend&id='.$frend['id'].'">';
                            echo '<i class="glyphicon glyphicon-remove"></i></a>';
	                echo '</span>';
							
                        //Дата регистрации
	                echo '<br/><div class="left">'.$tdate->make_date($frend['reg_date']).'</div><br/>';
	            echo '</li>';
                }
        echo '</ul>';   
	    //Вывод навигации
            echo $newlist->pagination(); 
    break; 

    //Удаление из друзей 
    case 'delete_frend':
        include_once(H.'modules/friends/delete_frend.php');
    break;
	
 
 endswitch;
