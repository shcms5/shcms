<?

if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
    $id = intval($_GET['id']);
	engine::nullid($id);
	    
		$dfile = $db->super_query( "SELECT * FROM `gallery_files` WHERE `id` = '".$id."'" );
		
	    if($dfile['id_user'] != $id_user) 
		{
		    header("Location: index.php");
		}

	    if(isset($_POST['yes'])) 
		{
		    
			$db->query( "DELETE FROM `gallery_files` WHERE `id` = '".$id."'" );
			
			header("Location: index.php");
			
		}elseif(isset($_POST['no'])) 
		{
		    
			header( "Location: index.php?do=photo&id=".$id."" );
		
		}
		
		
		echo '<div class="mainname">Подтверждение</div>';	
		echo '<div class="mainpost">';
		echo 'Вы действительно хотите удалить выбранную фотографию? Данное действие невозможно будет отменить.<hr/>';
		echo '<div style="text-align:right;">';
        
			$form = new form('index.php?do=delete&id='.$id.'');		
			$form->submit('Да','yes');
        	$form->submit('Нет','no');		
        	$form->display();
		
		echo '</div></div>';
		
?>