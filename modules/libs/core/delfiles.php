<?
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

	$id = intval($_GET['id']);
	$lib = $db->super_query( "SELECT * FROM `libs_files` WHERE `id` = '".$id."'" );
						    
		if($users['group'] == 15 or $lib['id_user'] == $id_user) 
		{	 
							
			//Если статья не существует или удалена выводит ошибку
			if( $lib['id'] != $id ) 
			{
							
				echo engine::error('Статья не существует или удалена');
				echo engine::home(array('Назад','index.php'));	 
				exit;
								
			}else 
			{
				
				$db->query( "DELETE FROM `libs_files` WHERE `id` = '{$id}'" );
				$db->query( "DELETE FROM `libs_comment` WHERE `id_libs` = '{$id}'" )
				header("Location: index.php");
			
			}
		
		}else 
		{
		
				header("Location: index.php");
				exit;
		
		}
		
		
		
		
?>