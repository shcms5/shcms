<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
    $id = intval($_GET['id']);
	engine::nullid($id);
	
	    $editdir = $db->super_query( "SELECT * FROM `gallery_dir` WHERE `id` = '{$id}'" );

	    if($editdir['id_user'] != $id_user) 
		{
		    header("Location: index.php");
		}
		
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Фотоальбомы</a>
                <a href="index.php?do=user&id='.$id.'" class="btn btn-default">Альбомы</a>
            <a href="#" class="btn btn-default disabled">Редактор: '.$editdir['name'].'</a>
        </div>';	
    
    echo '<div class="mainname">'.Lang::__('Редактирование').': <b>'.$editdir['name'].'</b></div>';
	echo '<div class="mainpost">';
	
	    if(isset($_POST['submit'])) 
		{
		
		    $name = engine::proc_name($_POST['name']);
			$text = $_POST['desc'];
			
			
			    if( empty( $name ) ) 
				{
				
				    echo engine::error(Lang::__('Введите название папки'));
				    echo engine::home( array( Lang::__('Назад') , 'index.php?do=user&id='.$id.'' ) );	
					exit;
				}
				
			$new = $db->query( "UPDATE `gallery_dir` SET `name` = '".$db->safesql($name)."',`text` = '".$db->safesql($text)."' WHERE `id` = '{$id}'" );	
		     
			    if( $new == true ) {
				header('Location: index.php?do=photo&id='.$id.'');
				exit;
			    }else {
				header('Location: index.php?do=editdir&id='.$id.'');	
				exit;
			    }
			 
			 
		}
                
$form = new form('?do=editdir&id='.$id.'','','','class="form-horizontal"');

$form->text('<br/><div class="form-group">');
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Название:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',$editdir['name'],'class="form-control"');
$form->text('</div></div><div class="row_g"></div>'); 

//Описание категории
$form->text('<div class="form-group">');
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Описание').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'desc',$editdir['text'],'','','form-control');
$form->text('</div></div>');  

//Кнопка
$form->text('<div class="modal-footer">');
$form->submit('Применить','submit',false,'btn btn-success');
$form->text('<a class="btn btn-default" href="index.php?do=user&id='.$id.'">Отмена</a>');
$form->text('</div>');

$form->display();
	
echo '</div>';
