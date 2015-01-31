<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
    $id = intval($_GET['id']);
	engine::nullid($id);
        
    if($id_user != true) {
	header( "Location: index.php" );
    }
    
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Фотоальбомы</a>
            <a href="#" class="btn btn-default disabled">Создать новый альбом</a>
        </div>';	
		
    echo '<div class="mainname">'.Lang::__('Создать альбом').'</div>';
	echo '<div class="mainpost">';
	
	    if(isset($_POST['submit'])) {
		
		    $name = engine::proc_name($_POST['name']);
			$text = $_POST['desc'];
			
			
			    if( empty( $name ) ) 
				{
				
				    echo engine::error(Lang::__('Введите название папки'));
				    echo engine::home( array( Lang::__('Назад') , '/index.php' ) );	
					exit;
				}
				
			$new = $db->query( "INSERT INTO `gallery_dir` (`id_user`,`name`,`text`) VALUES ('{$id}','".$db->safesql($name)."','".$db->safesql($text)."')" );	
		     
			    if( $new == true ) 
				{
				
					header('Location: index.php?do=user&id='.$id.'');
					exit;
				
				}else 
				{
				
				    echo engine::error(Lang::__('Папка не добавлена'));
				    echo engine::home( array( Lang::__('Назад') , 'index.php?do=user&id='.$id.'' ) );	
					exit;
				
				}
			 
			 
		}
    
$form = new form('?do=newdir&id='.$id.'','','','class="form-horizontal"');

$form->text('<br/><div class="form-group">');
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Название файла:').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',$view_file['name'],'class="form-control"');
$form->text('</div></div><div class="row_g"></div>'); 

//Описание категории
$form->text('<div class="form-group">');
$form->text('<label class="col-sm-2 control-label">'.Lang::__('Описание').'</label>');
$form->text('<div class="col-sm-10">');
$form->textarea2(false,'desc',false,'','','form-control');
$form->text('</div></div>');  

//Кнопка
$form->text('<div class="modal-footer">');
$form->submit('Добавить','submit',false,'btn btn-success');
$form->text('<a class="btn btn-default" href="index.php">Отмена</a>');
$form->text('</div>');
	    
$form->display();
	
	echo '</div>';
	