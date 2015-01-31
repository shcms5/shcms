<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
			    
	$id = intval($_GET['id']);
	$fview = $db->super_query( "SELECT * FROM `libs_files` WHERE `id` = '".$id."'" );
	$count = $db->get_array($db->query("SELECT COUNT(*) FROM `libs_comment` WHERE `id_libs` = '".$id."'"));
					
		//Счетчик обновляем
		$db->query("UPDATE `libs_files` SET `view` = '".($fview['view']+1)."' WHERE `id` = '".$id."'");
		                
		//Вывод ника
        $nick = $user->users($fview['id_user'],array('nick'),false);
							
		//Вывод id
        $id_users = $user->users($fview['id_user'],array('id'));
					
			$view  = '<div class="mainname"><center>'.$fview['name'].'</center></div>';
			$view .= '<div class="mainpost">';
			$view .= '<div class="details">';
			$view .= '<span><img src="/engine/template/icons/author.png"> <a href="/modules/profile.php?id='.$id_user.'">'.$nick.'</a></span>';
			$view .= '<span><img src="/engine/template/icons/date.png"> '.$tdate->make_date($fview['time']).'</span>'; 
			$view .= '<span><img src="/engine/template/icons/comment.png"><a href="index.php?do=comment&id='.$id.'"><b>Комментрий: '.$count[0].'</b></a></span>';
			$view .= '</div>';
			$view .=  '<div class="row2">'.engine::input_text($fview['text']).'</div>';
			$view .=  '</div>';
					
			    $view .=  engine::home(array('Назад','index.php?do=libs&id='.$fview['id_lib'].''));
		
        echo $view;		
