<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
			
$dir_action = $db->get_array($view);
        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Загрузки</a>
            <a href="#" class="btn btn-default disabled">'.$dir_file['name'].'</a>
        </div>';

    if($dir_action['load'] == 2 or $groups->setAdmin($user_group) == 15) {
	if($id_user == true) {
                echo '<div class="btn-group right">';            
	    if($groups->setAdmin($user_group) == 15) {
		echo '<a class="btn btn-success" href="dir.php?act=new_dir&id='.$id.'">';
		echo '<i class="glyphicon glyphicon-plus"></i>&nbsp;'.Lang::__('Новая папка').'</a>';
	    }
		echo '<a class="btn btn-info" href="upload.php?id='.$id.'">';
		echo '<i class="glyphicon glyphicon-download-alt"></i>&nbsp;'.Lang::__('Добавить файл').'</a>';
                echo '</div>';
	}
    }
//Загружаем в $upload данные из базы 
 $upload = $db->query("SELECT * FROM `files_dir` WHERE `dir` = '".$id."'");
 
echo '<div class="mainname"><img src="/engine/template/icons/download.png">&nbsp;'.Lang::__('Файлы и Папки').'</div>';
echo '<div class="mainpost">';

    if($db->num_rows($upload) > 0) {
	//Выводим все данные где `upload` = 1	
        while($file = $db->get_array($upload)) {
            //Выводим счетчик файлов
            $counts = $db->get_array($db->query("SELECT COUNT(*) FROM `files` WHERE `id_dir` = '{$file['id']}'"));
            //Все существующие файлы
            echo  '<table class="itable"><tbody><tr class="">';	
	    echo  '<td class="c_icon"><img src="/engine/template/icons/dir3.png"></td>';					
	    echo  '<td class="c_forum"><b><a href="dir.php?id='.$file['id'].'">'.$file['name'].'</a></b>
	    <p class="desc">'.$file['text'].'</p></td>';
            echo '<td class="c_stats"><ul>';
            echo '<li><b>'. $counts[0].'</b> Файлов</li>';
            echo '</ul></td></tr></tbody></table>';
        }
    }
	$rowc = $db->get_array($db->query("SELECT COUNT(*) FROM `files` WHERE `id_dir` = '{$id}'"));
	    //Определяем навигацию и лимит постов
            $newlist = new Navigation($rowc[0],10, true); 
		if($db->num_rows($upload) < 1 and $rowc[0] < 1) {
		    echo engine::error('Ничего не найдено!');
                    echo '</div></div>';
                    echo engine::home(array('Назад','/index.php'));	
	            exit;
                }	
            if($rowc[0] > 0) {			
		$uploadf = $db->query("SELECT * FROM `files` WHERE `id_dir` = '".$id."' ORDER BY `id` DESC ". $newlist->limit()."");
	    }		    	
					
                while($uploadu = $db->get_array($uploadf)) {
		    //Если существует название
		    if($uploadu['name'] == true) {
			$name_files = $uploadu['name'];
		   }else {
		    //Загруженное название 
		       $name_files = $uploadu['files'];
		    }			
                    $comment = $db->get_array($db->query("SELECT COUNT(*) as count FROM `down_comment` WHERE `id_file` = '{$uploadu['id']}'"));
			//Файл выводит
                        echo  '<table class="itable"><tbody><tr class="">';
			echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/down/'.engine::format($uploadu['files']).'.png"></td>';
			echo '<td class="c_forum"><b><a href="view.php?id='.$uploadu['id'].'">'.$name_files.'</a></b>';
			//Описание
			echo '<p class="desc">';	
			    if(!$uploadu['text2']) {
				echo Lang::__('Не добавлено описание к файлу');
			    }else {
				echo engine::input_text(engine::string($uploadu['text2'],500));
			    }
										
			echo '</p></td>';
                        echo '<td class="c_stats"><ul>';
                        echo '<li><b>'.engine::number($uploadu['count']).'</b> Просмотров</li>';
                        echo '<li><b>'.engine::number($comment['count']).'</b> Комментариев</li>';
                        echo '</ul></td>';
                        echo '</tr></tbody></table>';	
					
		}
	//Вывод навигации
	echo '</div>'; 	
        
    if($rowc[0] > 0) {
	echo $newlist->pagination();
    }	 
