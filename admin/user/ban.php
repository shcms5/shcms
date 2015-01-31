<?
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
        $id = intval($_GET['id']);
		engine::nullid($id);
		$nick = $user->users($id,array('nick'),false);
		
    switch($act):
	
	    default:
		
		    $ban = $db->get_array($db->query( "SELECT * FROM `ban` WHERE `id_user` = '{$id}'" ) );
		        
				if($ban['id_user'] == true) 
				{
				    $bans = '<span style="color:red;" class="time">Уже заблокирован</span>';
				}
				
		    echo '<div class="mainname"><img src="'.ICON_ADMIN.'user/blocku.png"> '.Lang::__('Категория банов').'&nbsp;для <b>'.$nick.'</b></div>';
			    echo '<div class="mainpost">';
				    echo '<div class="row"><a href="index.php?do=ban&act=banrest&id='.$id.'"><img src="'.ICON_ADMIN.'user/problem.png"> '.Lang::__('Бан с ограничением').'</a>'.$bans .'</div>';
				echo '</div>';
		
		    echo engine::home( array( Lang::__('Назад') , 'index.php?do=user&act=office' ) );
			
		break;
	
	    //Бан с ограничением
		case 'banrest':	        
			        
					$group = $user->users($id,array('group'));
					
					if( $group == intval(15) ) 
					{
					    echo engine::error(Lang::__('Вы не можете заблокировать администратора'));
					    echo engine::home( array( Lang::__('Назад') , 'index.php?do=user&act=office' ) );
						exit;
					}
					
					$lban = $db->query( "SELECT * FROM `ban` WHERE `id_user` = '{$id}'" );
					    
						if( $db->num_rows($lban) > 0 ) 
						{
						    
							$sban = $db->get_array($lban);
							
							$block = '<span class="time"><a href="index.php?do=ban&act=bandelete&id='.$id.'"><img src="/engine/template/icons/delete.png"></a></span>';
							echo engine::error('Вы уже забанили пользователя '.$block.'','Окончание бана '.$sban['time'].'');
							echo engine::home( array( Lang::__('Назад') , 'index.php?do=user&act=office' ) );
							exit;
						
						}
					
			    $arg = array('Клонирование ников','Неуважение администрации','Мат, Флуд, Спам');
			    $param = array(1,2,3);
		
				if(isset($_POST['submit'])) 
				{
				    
					$reason = intval($_POST['reason']);
				    $time = $_POST['stop'];
					$text = $_POST['text'];
					
					    if( empty($time) ) 
						{
						    echo engine::error(Lang::__('Обязательно нужно вводить дату блокировки'));
							echo engine::home( array( Lang::__('Назад') , 'index.php?do=ban&act=banrest&id='.$id.'' ) );
							exit;
						}
						
						if( empty($text) ) 
						{
						    echo engine::error(Lang::__('Обязательно нужно вводить дату блокировки'));
							echo engine::home( array( Lang::__('Назад') , 'index.php?do=ban&act=banrest&id='.$id.'' ) );
							exit;
						}
						
						$base = $db->query( "INSERT INTO `ban` (`id_user`,`id_admin`,`reason`,`text`,`time`) VALUES ('{$id}','{$id_user}','{$reason}','{$text}','{$time}')" );
						    
							if($base == true) 
							{
							    echo engine::success(Lang::__('Пользователь успешно заблокирован'));
								echo engine::home( array( Lang::__('Назад') , 'index.php?do=ban&act=banrest&id='.$id.'' ) );
								exit;  
							}
				
				
				}
			echo '<div class="mainname">Блокировать '.$nick.'</div>';
            echo '<div class="mainpost">';			
			
				$form = new form('index.php?do=ban&act=banrest&id='.$id.'');
				$form->select(Lang::__('Правила:'),'reason',array_combine( $arg, $param));
				$form->input(Lang::__('Срок бана:'),'stop','text','dd.mm.YYYY',false,false,true,false,false,false,'datepicker2');
				$form->textarea('Причина бана','text');
				$form->submit('Забанить','submit');
				$form->display();
			
			echo '</div>';
		
        echo engine::home( array( Lang::__('Назад') , 'index.php?do=user&act=office' ) );		
			
	    break;
		
		case 'bandelete':
		    
			$db->query("DELETE FROM `ban` WHERE `id_user` = '".$id."'");
			header('Location: index.php?do=ban&act=banrest&id='.$id.'');
		
		break;
		
	endswitch;

?>