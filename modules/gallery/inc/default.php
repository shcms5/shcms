<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Фотоальбомы</a>
            <a href="#" class="btn btn-default disabled">Пользователи</a>
        </div>';


if($id_user == true){
    echo '<div class="right">';
    echo '<a class="btn btn-info" href="?do=newdir&id='.$id_user.'">'.Lang::__('Новый альбом').'</a>';
    echo '</div>';
}
		
echo '<div class="mainname">'.Lang::__('Фотоальбомы пользователей').'</div>';

    echo '<div class="mainpost">';
	$gallery = $db->query( "SELECT DISTINCT(`id_user`) FROM `gallery_dir`" );
	  
        if( $db->num_rows( $gallery ) > 0 ) {
            
            echo '<div class="row">';
            //Пользователи у которых есть альбомы
	    while($photo = $db->get_array($gallery)) {
                //Данные о пользователе
		$pusers = $db->super_query( "SELECT * FROM `users` WHERE `id` = '{$photo[id_user]}'" );
                //Счетчик альбомов
		$cdir = $db->get_array($db->query( "SELECT COUNT(*) FROM `gallery_dir` WHERE `id_user` = '{$photo[id_user]}'" ));
		//Счетчик фотогарфий
                $cphoto = $db->get_array($db->query("SELECT COUNT(*) FROM `gallery_files` WHERE `id_user` = '{$photo[id_user]}'"));
                
		echo '<div class="col-sm-6 col-md-4 col-xs-4">';
		echo '<div class="thumbnail">';
                echo '<div class="caption">';
		echo '<h4><a href="index.php?do=user&id='.$pusers['id'].'">'.$pusers['nick'].'</a></h4>';
		echo '<p class="text-muted">Альбомов: '.$cdir[0].'<br/>Фотографии: '.$cphoto[0].'</p>';
		echo '</div></div></div>';		
	    } 
            echo '</div>';
	}else {
	    echo engine::warning(Lang::__('Фотогаллерея пуста'));
            echo '</div>';
	}
	
    
    echo '</div>';
   