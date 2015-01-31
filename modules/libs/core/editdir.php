<?
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
	echo engine::admin($users['group']);
	
	    $id = intval($_GET['id']);
		$libs = $db->super_query( "SELECT * FROM `libs` WHERE `id` = '".$id."'" );
	      	
			if(isset($_POST['submit'])) 
			{
			
			    $name = engine::proc_name($db->safesql(stripslashes($_POST['name'])));
			    $text = $db->safesql($_POST['text']);
			            
					if( empty( $name ) ) 
					{
						    
						echo engine::error(Lang::__('Введите название'));
						echo engine::home(array('Назад','index.php?do=editdir&id='.$id.''));	 
						exit;
							
					}
						
					if( empty( $text ) ) 
					{
						
						echo engine::error(Lang::__('Введите описание'));
						echo engine::home(array('Назад','index.php?do=editdir&id='.$id.''));	 
						exit;
							
					}	
						
						
				$dbyes = $db->query("UPDATE `libs` SET `name` = '".$name."', `text` = '".$text."',`time` = '".time()."' WHERE `id` = '".$id."'");
							
					if( $dbyes == true ) 
					{
						    
						echo engine::success(Lang::__('Раздел успешно обновлен'));
						echo engine::home(array('Назад','index.php'));	 
						exit;
					
					}else 
					{
					
						header("Location: index.php?do=editdir");
					
					}				
			}
			
				
		//Изменяем раздел	
	    echo '<div class="mainname">'.Lang::__('Редактировать раздел').'</div>';
	    echo '<div class="mainpost">';
			
			$form = new form('?do=editdir&id='.$id.''); //Передача по $_POST
			$form->input(Lang::__('Название:'),'name','text',$libs['name']); //Имя
			$form->textarea(Lang::__('Описание:'),'text',$libs['text']); //Описание
			$form->submit(Lang::__('Изменить'),'submit'); //Передача
			$form->display(); //Вывод
				
		echo '</div>'; 
			 
	echo engine::home(array('Назад','index.php'));	 
				
				
?>