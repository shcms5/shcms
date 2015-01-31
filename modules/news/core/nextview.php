<?php

if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

$newsl = $db->query("SELECT * FROM `news` WHERE (`id` = (SELECT MAX(`id`) FROM `news` WHERE `id` < ".$id.")OR `id` = (SELECT MIN(`id`) FROM `news` WHERE `id` > ".$id.")) ");
    //Если новостей больше меньше одного
    if($db->num_rows() < 1) {
        return false;
    }else {
        while ($row = $db->get_array($newsl)){
            //Слэши убираем			
            $row['title'] = stripslashes( $row['title'] );		
            //Название новостей			
            $substrtitle = ( strlen( $row['title'] ) > 25 ) ? substr($row['title'],0,25)."...":$row['title'];
            //категория
            $row['id_cat'] = intval( $row['id_cat'] );	
            // время обрабатываем
            $row['time'] = $tdate->make_date( $row['time'] );			
            //Путь к новости
            $full_link = '?id='.$row['id'].'';
								
            //Пред. новость				
            $nextview .= ($row['id'] < $id) ? '<li class="previous"> <a title="'.Lang::__('Предыдушая новость').'" href="'.$full_link.'">&larr; '.$substrtitle.'</a></li>' : '';
            //След. новость
            $nextview .= ($row['id'] > $id) ? '<li class="next"><a title="'.Lang::__('Следуюшая новость').'" href="'.$full_link.'">'.$substrtitle.' &rarr;</a> </li>' : '';   
	}
        //Вывод новостей
        echo '<ul style="margin: 5px 0;" class="pager">';
        echo $nextview;
        echo '</ul>';
    }