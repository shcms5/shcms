<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
    echo '<div class="header"><h1 class="page-title">Предупреждение</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Предупреждение</li>
        </ul></div>';
    
switch($act):

default:

 echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-cubes pull-left text-info"></i>
                        <a href="index.php?do=warnings&act=reason"><h4>'.Lang::__('Причина').'</h4></a>
                        <p class="info small">Причины при бана пользователей</p>
                    </li>                   
                </ul>
            </div>';

break;


case 'reason':
    
    echo '<div style="text-align:right;margin-bottom:4px;">';
    echo '<a class="btn btn-default" href="index.php?do=warnings&act=new_reason">Добавить новую</a>';
    echo '</div>';
    echo '<div class="row"></div>';
    
		echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
                echo "<tr>
				<th>".Lang::__('Название:')." </th> 
                    <th>".Lang::__('Баллы:')."</th>  
				</tr>";
					
						$reas = $db->query("SELECT * FROM `warnings`");
							while($reason = $db->get_array($reas)) {
								echo '<tr class="even">';
                            		echo '<td>'.$reason['name'].'</td>'; 
									echo '<td>'.engine::number($reason['points']).'</td>'; 	
                                    echo '<td  style="padding: 1px;">
									<a title="'.Lang::__('Редактировать').'" class="Button_secondary"href="index.php?do=warnings&act=edit_reason&id='.$reason['id'].'"><img src="../icons/user/edit_status.png"></a>
									<a title="'.Lang::__('Удалить').'" class="Button_secondary"href="index.php?do=warnings&act=delete_reason&id='.$reason['id'].'"><img src="../icons/user/editdelete.png"></a>
									</td>';									
								echo '</tr>';
							}			
    	echo "</table>";		 
		echo '</div>';
echo engine::home(array('Назад','index.php?do=warnings'));
break;

//Добавление причины
case 'new_reason':

			if(isset($_POST['new_reason'])) {
			    $name = engine::proc_name($_POST['name']);
				$points = intval($_POST['points']);
				
			    if(empty($name)) {
				    echo engine::error(Lang::__('Введите названия'));
					header('Location: index.php?do=warnings&act=new_reason');	
					exit;
				}
                                
			    if(empty($points)) {
				    echo engine::error(Lang::__('Введите количество баллов'));
					header('Location: index.php?do=warnings&act=new_reason');	
					exit;
				}				
				
				$new_reason = $db->query("INSERT INTO `warnings` (`name`,`points`) VALUES ('".$db->safesql($name)."','".intval($points)."')");
			        if($new_reason == true) {
	    				echo engine::success('Параметры успешно изменены'); 
					header('Location: index.php?do=warnings&act=new_reason');
					exit;								
				}else {
	    				echo engine::error('Ошибка при изменение параметр');
					header('Location: index.php?do=warnings&act=new_reason');	
					exit;									
				}	
			}

	    $form = new form('index.php?do=warnings&act=new_reason','','class="form-horizontal"');
               
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'name','text',$reason['name'],'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Количество баллов').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'points','text',$reason['points'],'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Добавить'),'new_reason',false,'btn btn-success');	
                $form->text('<a class="btn btn-warning" href="index.php?do=warnings&act=reason">Отмена</a>');
                $form->text('</div></center>');	
                
	    $form->display();        

break;

//Редактирование причины
case 'edit_reason':
   $id = intval($_GET['id']);
            $reason = $db->get_array($db->query("SELECT * FROM `warnings` WHERE `id` = '".intval($id)."'"));
			
			if(isset($_POST['edit_reason'])) {
			    $name = engine::proc_name($_POST['name']);
				$points = intval($_POST['points']);
				
			    if(empty($name)) {
				    echo engine::error(Lang::__('Введите названия'));
					echo engine::home(array('Назад','index.php?do=warnings&act=edit_reason&id='.$reason['id'].''));	
					exit;
				}
			    if(empty($points)) {
				    echo engine::error(Lang::__('Введите количество баллов'));
					echo engine::home(array('Назад','index.php?do=warnings&act=edit_reason&id='.$reason['id'].''));	
					exit;
				}				
				
				$edit_reason = $db->query("UPDATE `warnings` SET `name` = '".$db->safesql($name)."',`points` = '".intval($points)."' WHERE `id` = '".intval($id)."'");
			        
                                if($edit_reason == true) {
	    				echo engine::success('Параметры успешно изменены'); 
                                        header('Location: index.php?do=warnings&act=reason');
					exit;								
				}else {
	    				echo engine::error('Ошибка при изменение параметр');
					header('Location: index.php?do=warnings&act=reason');
                                        exit;									
				}	
			}

	    $form = new form('index.php?do=warnings&act=edit_reason&id='.$reason['id'].'','','class="form-horizontal"');
               
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'name','text',$reason['name'],'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Количество баллов').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'points','text',$reason['points'],'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Изменить'),'edit_reason',false,'btn btn-success');	
                $form->text('<a class="btn btn-warning" href="index.php?do=warnings&act=reason">Отмена</a>');
                $form->text('</div></center>');	
                
	    $form->display();              

break;

	//Удаление причины
	case 'delete_reason':
	    $id = intval($_GET['id']); //Проверка на нумерное значение в $id
		
		//Удаление причины
		$db->query("DELETE FROM `warnings` WHERE `id` = '".intval($id)."'");
		//Переадресация
		header("Location: index.php?do=warnings&act=reason");
	
	break;
endswitch;
