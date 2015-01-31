<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
$id = intval($_GET['id']);
   
$nick = $user->users($id,array('nick'),false);

    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Фотоальбомы</a>
            <a href="index.php" class="btn btn-default">Пользователи</a>
            <a href="#" class="btn btn-default disabled">Альбомы '.$nick.'</a>
        </div>';

    if($id == $id_user){
        echo '<div class="right">';
	echo '<a class="btn btn-info" href="?do=newdir&id='.$id.'">'.Lang::__('Новый альбом').'</a>';
        echo '</div>';
    }
		
		
        echo '<div class="mainname">'.Lang::__('Альбомы').'&nbsp;'.$nick.'</div>';		
		echo '<div class="mainpost">';
			
            $userd = $db->query( "SELECT * FROM `gallery_dir` WHERE `id_user` = '{$id}'" );
			    
		if($db->num_rows($userd) > 0) {
                    echo '<div class="row">';
		    while( $dir = $db->get_array($userd) ) {
			if($id == $id_user) {   
		    	    $eview = '<div class="right small">';
		    	    $eview .= '<a href="?do=editdir&id='.$dir['id'].'">';
                            $eview .= '<i class="glyphicon glyphicon-pencil"></i></a>&nbsp;';
			    $eview .= '<a href="?do=deletedir&id='.$dir['id'].'">';
                            $eview .= '<i class="glyphicon glyphicon-remove"></i></a>';
		    	    $eview .= '</div>';
			}	
				        
			$nick = $user->users($dir['id_user'],array('nick'),false);
				                                
		echo '<div class="col-sm-6 col-md-4 col-xs-4">';
		echo '<div class="thumbnail">';
                echo '<div class="caption">';
		echo '<h4><a href="index.php?do=photo&id='.$dir['id'].'">'.engine::ucfirst($dir['name']).'</a>'.$eview.'</h4>';
		echo '<p class="text-muted">'.$dir['text'].'</p>';
		echo '</div></div></div>';
				
		    }
                    echo '</div>';
		}else {
		    echo engine::error(Lang::__('Папок не найдено'));
		    echo '</div>';
		    echo engine::home( array( Lang::__('Назад') , 'index.php' ) );
		    exit;
					
		}		
	    echo '</div>';	

