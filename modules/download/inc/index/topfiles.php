<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Загрузки</a>
            <a href="#" class="btn btn-default disabled">Топ скачиваемых</a>
        </div>';

    $sum = $sum ? $sum : 10;
            
	function ok_type($n){
	    return ($n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2));
        }	
    $dcount = $db->query( "SELECT * FROM `files` WHERE countd > 0 ORDER BY `countd` DESC LIMIT 0,$sum" );
    $i = 1;
        echo '<div class="mainname">'.Lang::__('Топ скачиваний').'</div>';
	
        if($db->num_rows() < 1) {
	    echo engine::error(Lang::__('Скачанных файлов не найдено!'));
	}else {
            echo '<ul class="list-group">';
	    while ( $row = $db->get_array($dcount) ) {
		//// Проверка количества скачиваний, и вывод правильного слова ////
                $alls_num = array(
                                'раз',
                                'раза',
                                'раз');
                
                $counts_u = $alls_num[ok_type($row['countd'])];
				
		//// Правильный вывод титлов ////			
                $row['name'] = htmlspecialchars( strip_tags( stripslashes( $row['name'] ) ), ENT_QUOTES) ;
                $row['name'] = str_replace("{", "&#123;", $row['name']);
                $row['name'] = strip_tags($row['name']);
				
		echo  '<li class="list-group-item"><font color="red">'.$i.'.</font>';
                echo '<a href="view.php?id='.$row['id'].'" rel="nofollow"><b>'.$row['name'].'</b></a> - скачано: '.$row['countd'].'&nbsp;';
		echo  $counts_u.'</ul>';
		$i++;

    	    }	
            echo '</ul>';
        }
        