<?
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

	$id = intval($_GET['id']);
	$editfiles = $db->super_query( "SELECT * FROM `libs_files` WHERE `id` = '".$id."'" );
                        
		if( $users['group'] == 15 or $editfiles['id_user'] == $id_user ) 
		{
						
	      	if(isset($_POST['submit'])) 
			{
			
			    $name = engine::proc_name($db->safesql(stripslashes($_POST['name'])));
			    $mtext = $db->safesql($_POST['mtext']);
			    $text = $db->safesql($_POST['text']);
			            
					if( empty( $name ) ) 
					{
						
						echo engine::error(Lang::__('Введите название'));
						echo engine::home(array('Назад','index.php?do=editfiles&id='.$id.''));	 
						exit;
							
					}
						
					if( empty( $mtext) ) 
					{
						
						echo engine::error(Lang::__('Введите краткий текст'));
						echo engine::home(array('Назад','index.php?do=editfiles&id='.$id.''));	 
						exit;
							
					}	
						
					if( empty( $text ) ) 
					{
						
						echo engine::error(Lang::__('Введите текст статьи'));
						echo engine::home(array('Назад','index.php?do=editfiles&id='.$id.''));	 
						exit;
							
					}
						
				$dbyes = $db->query("UPDATE `libs_files` SET `name` = '".$name."', `mtext` = '".$mtext."',`text` = '".$text."',`time` = '".time()."' WHERE `id` = '".$id."'");
						
					if( $dbyes == true ) 
					{
						
						echo engine::success(Lang::__('Статья успешно обновлена'));
						echo engine::home(array('Назад','index.php?do=libs&id='.$editfiles['id_lib'].''));	 
						exit;
							
					}else 
					{
						
						header("Location: index.php?do=editfiles&id=".$id."");
							
					}				
			}
			
			
	    
		    echo '<div class="mainname">'.Lang::__('Изменить статью').'</div>';
			echo '<div class="mainpost">';
			
                $form = new form('?do=editfiles&id='.$id.'');
				$form->input(Lang::__('Название:'),'name','text',$editfiles['name']);
				$form->textarea(Lang::__('Краткое описание:'),'text',$editfiles['mtext']);
				$form->textarea(Lang::__('Полное описание:'),'text',$editfiles['text']);
				$form->submit(Lang::__('Изменить'),'submit');
				$form->display();
			
			echo '</div>';
			echo engine::home(array('Назад','index.php?do=libs&id='.$editfiles['id_lib'].''));	 
				
		}else 
		{
				
			header('Location: index.php?do=libs&id='.$editfiles['id_lib'].'');
			exit;
					
		}
				
?>