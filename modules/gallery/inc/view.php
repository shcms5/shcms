<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

    $id = intval($_GET['id']);
	$view = $db->super_query( "SELECT * FROM `gallery_files` WHERE `id` = '{$id}'") ;
    $nick = $user->users($view['id_user'],array('nick'),false);    
    $album = $db->super_query( "SELECT * FROM `gallery_dir` WHERE `id` = '{$id}'" );
	
	    $url = H.'/upload/gallery/max/'.$view['images'];
	
	    if( $view['id_user'] == $id_user )
	    {
		
			$idelete  = '<span class="time">';
			$idelete .= '<a href="?do=delete&id='.$id.'"><img src="/engine/template/icons/delete.png"></a>';
			$idelete .= '</span>';
		
		}
    	//Размеры изорбражении и вес 
		list($width, $height) = getimagesize($url);
		
		    $size   = engine::filesize(filesize($url));
	    
		        $viewf .= '<div class="mainname">';
				$viewf .= '<img src="/engine/template/icons/info.png"> Информация о фотографии';
				$viewf .= $idelete;
				$viewf .= '</div>';
	            $viewf .= '<div class="mainpost">';
		        $viewf .= '<div class="subpost">Добавлен: '.$tdate->make_date($view['time']).'</div>';
		        $viewf .= '<div class="subpost"><img src="/upload/gallery/max/'.$view['images'].'"></div>';
		        $viewf .= '<div class="subpost"><a href="?do=download&id='.$view['id'].'"><b>Скачать Оригинал ('.$size.')</b></a><br/>';
		        $viewf .= 'Размеры: '.$width.'x'.$height.' px<br/>';
		        $viewf .= 'Автор: '.$nick.' | Альбом: '.$album['name'].' </div>';
		        $viewf .= '</div>';

	    echo $viewf;	
	
	echo engine::home( array( Lang::__('Назад') , 'index.php?do=photo&id='.$view['id_dir'].'' ) );
?>