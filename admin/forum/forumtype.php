<?php

switch($act):
    
default:
 echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-folder-open-o pull-left text-info"></i>
                        <a href="index.php?do=forumtype&act=type"><h4>'.Lang::__('Типы прикрепляемых файлов').'</h4></a>
                        <p class="info small">Все типы загруженных файлов</p>
                    </li>   
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-recycle pull-left text-info"></i>
                        <a href="index.php?do=forumtype&act=stats"><h4>'.Lang::__('Статистика прикрепленных файлов').'</h4></a>
                        <p class="info small">Полная статистика файлов</p>
                    </li>   
                </ul>
            </div>';

break;
	
case 'type':	
    
echo '<div class="panel panel-default">
        <div class="panel-heading">'.Lang::__('Существующие типы файлов').'</div>
                    <table class="table table-bordered table-first-column-check table-hover">
                        <thead>
                            <tr>
                              <th class="col-md-1">'.Lang::__('Иконка').'</th>
                              <th class="col-md-2">'.Lang::__('Расширение').'</th>
                              <th class="col-md-8">'.Lang::__('Всего').'</th>
                            </tr>
                        </thead>
                        <tbody>';
            
                    $files = $db->query("SELECT DISTINCT `type` FROM `forum_file`");
			while($file = $db->get_array($files)) {
                            
                            $row = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_file` WHERE `type` = '".$file['type']."'"));
                            
                            echo'<tr>
                              <td style="text-align:center;"><img src="/engine/template/down/'.$file['type'].'.png"></td>
                              <td style="text-align:center;">'.$file['type'].'</td>
                              <td style="text-align:center;">'.$row[0].'</td>
                              <td style="text-align:center;">'.$row['Default'].'</td>
                            </tr>';
                        }   
                        
                    echo '</tbody></table></div>';           
				//Переадресация
				echo engine::home(array(Lang::__('Назад'),'index.php?do=forumtype'));					
break;
	
case 'stats':
    
	echo '<center style="padding-bottom: 5px;"><b>'.Lang::__('Статистика прикрепленных файлов').'</b></center>'; 
        echo '<div class="row"></div>';
            $size = $db->get_array($db->query("SELECT SUM(size) FROM `forum_file`"));
	    $count = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_file`"));

    echo '<div class="form-horizontal">';
    
	//Вывод статистики необходимой
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Общий размер').'</b></label>';
    echo '<div class="col-sm-10">'.engine::filesize($size[0]).'</div></div>';
    
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Всего файлов').'</b></label>';
    echo '<div class="col-sm-10">'.$count[0].'</div></div>';
    
    echo '</div>';
        echo '<div class="row"></div>';
	echo '<center style="padding-bottom: 5px;"><b>'.Lang::__('Последние 5 файлов форума').'</b></center>'; 
        echo '<div class="row"></div>';
		                echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
                            echo "<tr>
							        <th>".Lang::__('Иконка')." </th> 
				                    <th>".Lang::__('Файл')." </th> 
                                    <th>".Lang::__('Размер')."</th> 
									<th>".Lang::__('Добавлен')."</th> 
				                </tr>";				
		                            $files = $db->query("SELECT * FROM `forum_file` ORDER BY `id` DESC LIMIT 5");
										while($file = $db->get_array($files)) {
											echo '<tr class="even">';		
											echo '<td><img src="/engine/template/down/'.$file['type'].'.png"></td>';
											echo '<td>'.$file['name'].'</td>';
											echo '<td>'.engine::filesize($file['size']).'</td>';
											echo '<td>'.date::make_date($file['time']).'</td>';
											echo '</tr>';
										}
    	                echo "</table>";	
		            echo '</div>';
				echo '</div>';	
				//Переадресация
				echo engine::home(array(Lang::__('Назад'),'index.php?do=forumtype'));	
	break;
endswitch;

?>