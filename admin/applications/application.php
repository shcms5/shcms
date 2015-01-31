<?
switch($act):
    
	default:
        echo '<div class="mainpost">';
        echo '<div class="subpost"><img src="'.ICON_ADMIN.'application/new_user.png">&nbsp;<a href="'.CP_DERICTORY.'applications/index.php?do=application&act=new_reg">Новые пользователи</a></div>';
        echo '</div>';
           echo engine::home(array(Lang::__('Назад'),'index.php?do=applications')); //Переадресация
    break;

	case 'new_reg':

        echo '<div class="mainname">'.Lang::__('Новых пользователей за день').'</div>';
            echo '<div class="mainpost">';
                $user = $db->query("SELECT * FROM `users` WHERE `reg_date` > ".(time()-86400)." ORDER BY `reg_date` DESC");
                    while($users = $db->get_array($user)) {
                        echo '<div style="border: 1px green solid;"  class="subpost">';
                        echo '<a href="/modules/profile.php?id='.$users['id'].'">'.$users['nick'].'</a><br/>';
                        echo Lang::__('Регистрация:').'&nbsp;'.date::make_date($users['reg_date']);
                        echo '</div>';
                    }
            echo '</div>';

        echo '<div class="mainname">'.Lang::__('Новых пользователей за неделю').'</div>';
            echo '<div class="mainpost">';
                $user1 = $db->query("SELECT * FROM `users` WHERE `reg_date` > ".(time()-604800)." ORDER BY `reg_date` DESC");
                    while($users1 = $db->get_array($user1)) {
                        echo '<div style="border: 1px #F0E68C solid;" class="subpost">';
                        echo '<a href="/modules/profile.php?id='.$users1['id'].'">'.$users1['nick'].'</a><br/>';
                        echo Lang::__('Регистрация:').'&nbsp;'.date::make_date($users1['reg_date']);
                        echo '</div>';
                    }
            echo '</div>';


        echo engine::home(array(Lang::__('Назад'),'index.php?do=application')); //Переадресация
    break;
	
endswitch;

?>