<?
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
         
    //Название страницы
    $templates->template(Lang::__('Доступ ограничен'));
	    
		$ban = $db->query( "SELECT * FROM `ban` WHERE `id_user` = '{$id_user}'" );
		    
			if($db->num_rows($ban) > 0) 
			{ 
			    $bans = $db->get_array($ban);
			    $nick = $user->users($bans['id_user'],array('nick'),false);
			    $admin = $user->users($bans['id_admin'],array('nick'),false);
				
				    $arg = array('Клонирование ников','Неуважение администрации','Мат, Флуд, Спам');
				    $param = array(1,2,3);
				    $aban = array_combine($param,$arg);					
					
			        echo '<div class="mainname"><img src="/engine/template/icons/info.png">&nbsp;'.Lang::__('Информация о блокировки').'&nbsp;<b>'.$nick.'</b></div>';
			        echo '<div class="mainpost">';
					
					    echo '<div class="row">Заблокировал: '.$admin.'</div>';
						echo '<div class="row">Причина: '.$aban[$bans['reason']].'</div>';
						echo '<div class="row">Описание причины: '.$bans['text'].'</div>';
						echo '<div class="row">Окончание бана: '.$bans['time'].'</div>';
					
					echo '</div>';
				
				
			}
	
?>	