<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

	     
$id = intval($_GET['id']);
$dir = $db->super_query( "SELECT * FROM `gallery_dir` WHERE `id` = '{$id}'" );
$nick = $user->users($dir['id_user'],array('nick'),false);
		
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Фотоальбомы</a>
            <a href="index.php" class="btn btn-default">Пользователи</a>
            <a href="index.php?do=user&id='.$dir['id_user'].'" class="btn btn-default">Альбомы '.$nick.'</a>
                 <a href="#" class="btn btn-default disabled">'.$dir['name'].'</a>
        </div>';
    
if($dir['id_user'] == $id_user){
    echo '<div class="right">';
    echo '<a class="btn btn-info" href="?do=newphoto&id='.$dir['id'].'">'.Lang::__('Добавить').'</a>';
    echo '</div>';
}
		
		
echo '<div class="mainname">'.Lang::__('Фотографии из папки').': '.$dir['name'].'';
echo '<span class="time">'.$iview.'</span></div>';
    echo '<div class="mainpost">';
		    
	$files = $db->query( "SELECT * FROM `gallery_files` WHERE `id_dir` = '{$dir[id]}'" );

			    
if($db->num_rows($files) > 0) {
    echo '<ul class="portfolio-items col-4">';
	while( $gallery = $db->get_array( $files ) ) {
            echo '<li class="portfolio-item">';
            echo '<div class="item-inner">';
            echo '<div class="portfolio-image">';
                echo '<img src="/upload/gallery/mini/'.$gallery['images'].'" title="'.$gallery['images'].'" />';
                                           
            echo '<div class="overlay">';
                echo '<a class="preview btn btn-info" title="'.$gallery['images'].'" href="/upload/gallery/max/'.$gallery['images'].'"><i class="icon-eye-open"></i></a>';        
            echo '</div>'; 
            echo '</div>'; 
                echo '<h5>'.engine::input_text($gallery['text']).'</h5>';         
            echo '</div>'; 
            echo '</li>';
        }
                                
    echo '</ul>';
}else {
    echo engine::warning('Фотографий не найдено');
    
}			
echo '</div>';
	