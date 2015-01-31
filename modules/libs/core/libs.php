<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
//Обработка полученного $ID
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$id = intval($id);
	    
        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Библиотека</a>
            <a href="index.php" class="btn btn-default disabled">Статьи</a>
        </div>';
        
	//Проверяем есть ли права у пользователя
        if( $users['group'] == 15 ) {
            echo '<div class="right">';
	    echo '<a class="btn btn-info" href="index.php?do=newfiles&id='.$id.'">Написать статью</a>';
            echo '</div>';
        }	    

    //Получение всех статей
    echo  '<div class="mainname">Все статьи<span class="time">'.$aview.'</span></div>';
        //счетчик статей
	$lcount = $db->get_array( $db->query( "SELECT COUNT(*) FROM `libs_files` WHERE `id_lib` = '".intval($id)."'" ));
        //Объявляем навигацию
	$newlist = new Navigation($lcount[0], 25, true); 
            //Если статей больше 1
	    if( $lcount[0] > 0 ){
		$file = $db->query( "SELECT * FROM `libs_files` WHERE `id_lib` = '".$id."' ORDER BY `id` DESC ". $newlist->limit()."" );
	    }else {
		$file = $db->query( "SELECT * FROM `libs_files` WHERE `id_lib` = '".$id."' " );
	    }
            //Если статей больше 0
	    if( $db->num_rows( $file ) == 0 ) {
                //Если нет статей
		echo  engine::warning(Lang::__('Статей не найдено')); 
                echo '</div></div>';  
            }else {
                //Выводим их 
		while( $files = $db->get_array( $file ) ){
		    //Определяем ник
		    $nick = $user->users($files['id_user'],array('nick'),false);
		    //Определяем id
                    $id_users = $user->users($files['id_user'],array('id'));
                    
                    //Действие для админа и для создателя статьи
		     if( $users['group'] == 15 or $files['id_user'] == $id_user ){
			$group = '<a href="index.php?do=editfiles&id='.$files['id'].'">';
			$group .= '<img src="/engine/template/icons/edit.png"></a>&nbsp;';
			$group .= '<a href="index.php?do=delfiles&id='.$files['id'].'">';
			$group .= '<img src="/engine/template/icons/delete.png"></a>';
		    } 
                        echo '<table class="posts_gl"><tbody><tr class="">';
                        echo '<td class="c_icon"><img src="/engine/template/icons/article.png"></td>';
                        //Название статьи
			echo '<td class="c_forum"><b><a href="index.php?do=view&id='.$files['id'].'">'.$files['name'].'</a></b><span class="width-1"> '.$group.'</span><br/>';
                        //Дополнительные данные о статье
                        echo '<div class="details">';
			echo '<span><img src="/engine/template/icons/author.png">&nbsp;<a style="color:#1E90FF;" href="'.MODULE.'profile.php?act=view&id='.$id_users.'">'.$nick.'</a></span>';
                        echo '<span><img src="/engine/template/icons/date.png">&nbsp;'.$tdate->make_date($files['time']).'</span>';
		        echo '<span><img src="/engine/template/icons/eye.png">&nbsp;<font color="#778899">'.$files['view'].'</font></span></div>';
			//Сама статья
                        echo '<p class="desc row2">'.engine::input_text(engine::substr($files['mtext'],500)).'</p></td>';
                        echo '</tr></tbody></table>';
		}
                //Выводим по навигациям
		 echo $newlist->pagination('do=libs&id='.$id.'');
	    } 
