<?
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

	echo engine::admin($users['group']);
	
	    $id = intval($_GET['id']);
		$lib = $db->super_query( "SELECT * FROM `libs` WHERE `id` = '".$id."'" );
						
			//Если папка не существует или удалена выводит ошибку
			if( $lib['id'] != $id ) 
			{
				echo engine::error('Папка не существует или удалена');
				echo engine::home(array('Назад','index.php'));	 
				exit;
							
			}else 
			{
						
				$db->query( "DELETE FROM `libs` WHERE `id` = '{$id}'" ); //Удаление
				$comment = $db->super_query( "SELECT * FROM `libs_files` WHERE `id_lib` = '{$id}'" );
				$db->query( "DELETE FROM `libs_comment` WHERE `id_libs` = '{$comment[id]}'" );
				$db->query( "DELETE FROM `libs_files` WHERE `id_lib` = '{$id}'" );
				header("Location: index.php");
			
			}		
?>