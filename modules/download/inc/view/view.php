<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
//Резка по форматам		
$format_audio = engine::format($view_file['files']);
//Определяем количество комментарий в файлу
$comment = $db->get_array( $db->query( "SELECT COUNT(*) FROM `down_comment` WHERE `id_file` = '".intval($id)."'" ) );
//Объявляем класс времени
$tdate = new \Shcms\Component\Data\Bundle\DateType();
$lview = '';
//Если у пользователя есть права Администратора
if($id_user == $view_file['id_user']) {
    $lview .= '<span class="btn-group right">';
    $lview .= '<a class="btn btn-small btn-info" href="?act=screen&id='.$id.'">Скрины</a> ';
    $lview .= '<a class="btn btn-small btn-info" href="?act=edit_file&id='.$id.'">Опции файла</a>'; 
    $lview .= '<a class="btn btn-small btn-danger" href="?act=delete&id='.$id.'">Удалить</a></span>';	
}

//Информация о Файле	
$lview .= '<div class="mainname">';
$lview .= '<img src="/engine/template/icons/info.png"> '.Lang::__('Информация');
$lview .= '</div>';
$lview .= '<div class="mainpost">';	
						
//Доступен вывод только 3 скриншотов
$lview .= '<div class="row">';
$lview .= '<ul class="portfolio-items col-4">';
    //Первый скрин
    if($view_file['screen'] == true) {
        $lview .= '<li class="portfolio-item">';
        $lview .= '<div class="item-inner">';
        $lview .= '<div class="portfolio-image">';
        $lview .= '<img src="/upload/download/screen/'.$view_file['screen'].'" title="'.$view_file['files'].'" />';
            $lview .= '<div class="overlay">';
            $lview .= '<a class="preview btn btn-info" title="'.$view_file['name'].'" href="/upload/download/screen/'.$view_file['screen'].'"><i class="icon-eye-open"></i></a>';        
        $lview .= '</div>'; 
        $lview .= '</div>'; 
            $lview .= '<h5>'.engine::input_text($view_file['text2']).'</h5>';         
        $lview .= '</div>'; 
        $lview .= '</li>';
    }
    //Второй скрин
    if($view_file['screen_2'] == true) {
        $lview .= '<li class="portfolio-item">';
        $lview .= '<div class="item-inner">';
        $lview .= '<div class="portfolio-image">';
        $lview .= '<img src="/upload/download/screen/'.$view_file['screen_2'].'" title="'.$view_file['files'].'" />';
            $lview .= '<div class="overlay">';
            $lview .= '<a class="preview btn btn-info" title="'.$view_file['name'].'" href="/upload/download/screen/'.$view_file['screen_2'].'"><i class="icon-eye-open"></i></a>';        
        $lview .= '</div>'; 
        $lview .= '</div>'; 
            $lview .= '<h5>'.engine::input_text($view_file['text2']).'</h5>';         
        $lview .= '</div>'; 
        $lview .= '</li>';
    }
    //Третий скрин
    if($view_file['screen_3'] == true) {
        $lview .= '<li class="portfolio-item">';
        $lview .= '<div class="item-inner">';
        $lview .= '<div class="portfolio-image">';
        $lview .= '<img src="/upload/download/screen/'.$view_file['screen_3'].'" title="'.$view_file['files'].'" />';
            $lview .= '<div class="overlay">';
            $lview .= '<a class="preview btn btn-info" title="'.$view_file['name'].'" href="/upload/download/screen/'.$view_file['screen_3'].'"><i class="icon-eye-open"></i></a>';        
        $lview .= '</div>'; 
        $lview .= '</div>'; 
            $lview .= '<h5>'.engine::input_text($view_file['text2']).'</h5>';         
        $lview .= '</div>'; 
        $lview .= '</li>';
   }

$lview .= '</ul></div><hr/>';
					
    //Если название существует выведит
    if($view_file['name'] == true) {
        $lview .= '<div class="form-group">';
        $lview .= '<label class="col-sm-2 control-label">Имя файла:</label>';
	$lview .= '<div class="col-sm-10">'.$view_file['name'].'';
	$lview .= '<span class="time">'.$tdate->make_date($view_file['time']).'</span><br/><br/>';
	$lview .= '</div></div><div class="row_g"></div>';
    }elseif($view_file['files'] == true){
        $lview .= '<div class="form-group">';
        $lview .= '<label class="col-sm-2 control-label">Имя файла:</label>';
	$lview .= '<div class="col-sm-10">'.$view_file['files'].'';
	$lview .= '<span class="time">'.$tdate->make_date($view_file['time']).'</span><br/><br/>';
	$lview .= '</div></div><div class="row_g"></div>';			
					
    }   
    //Вывод описании
    if($view_file['text2'] == true) {
        $lview .= '<div class="form-group">';
        $lview .= '<label class="col-sm-2 control-label">Описание:</label>';
	$lview .= '<div class="col-sm-10">'.$view_file['text2'].'<br/><br/>';
	$lview .= '</div></div><div class="row_g"></div>';        
    }else {
        $lview .= '<div class="form-group">';
        $lview .= '<label class="col-sm-2 control-label">Описание:</label>';
	$lview .= '<div class="col-sm-10">'.Lang::__('Описание отсутствует').'<br/><br/>';
	$lview .= '</div></div><div class="row_g"></div>'; 					
    }

    //Получаем ник добавленного файла
    $nick = $user->users($view_file['id_user'],array('nick'));
    //Получаем id добавленного файла
    $id_users = $user->users($view_file['id_user'],array('id'));
    //Выводим автора Файла
        $lview .= '<div class="form-group">';
        $lview .= '<label class="col-sm-2 control-label">Добавил:</label>';
	$lview .= '<div class="col-sm-10"><a href="/modules/profile.php?id='.$id_users.'">'.$nick.'</a>';
        $lview .= '<span class="time">'.Lang::__('Просмотров:').' '.engine::number($view_file['count']).'</span><br/><br/>';
	$lview .= '</div></div><div class="row_g"></div>';	    
    //Комментарии
        $lview .= '<div class="form-group">';
        $lview .= '<label class="col-sm-2 control-label">Комментарий:</label>';
	$lview .= '<div class="col-sm-10"><a href="?act=comment&id='.$id.'">Просмотр</a>';
        $lview .= '<span class="time"><b>'.$comment[0].'</b></span><br/><br/>';
	$lview .= '</div></div><div class="row_g"></div>';
        //Размер
        $lview .= '<div class="form-group">';
        $lview .= '<label class="col-sm-2 control-label">Размер:</label>';
	$lview .= '<div class="col-sm-10">'.engine::filesize($view_file['filesize']).'<br/><br/>';
	$lview .= '</div></div><div class="row_g"></div>';
        
        $lview .= '<div class="form-group">';
        $lview .= '<label class="col-sm-2 control-label">Кол. загрузок:</label>';
	$lview .= '<div class="col-sm-10">'.engine::number($view_file['countd']).'<br/><br/>';
	$lview .= '</div></div><div class="row_g"></div>';	        
 							
    //Выводим все данные
    echo $lview;
	echo '<div class="row">';					
	//Для фотографий, видиозаписей , аудиозаписей
	if($format_audio == 'mp3' or $format_audio == 'midi') {
	    $fview  = '<div class="form-group">';
            $fview .= '<div class="col-sm-10">';
            $fview .= $cfile->player('player/uppod.swf','width="500" height="41"','player/audio/audio127-611.txt','/upload/download/files/'.$view_file['files'].'');
	    $fview .= '</div></div>';
	}elseif($format_audio == 'mp4' or $format_audio == 'flv' or $format_audio == 'avi'){   
	    $fview  = '<div class="form-group">';
            $fview .= '<div class="col-sm-10">';
	    $fview .= $cfile->player('player/uppod.swf','width="500" height="321"','player/video/video127-1278.txt','/upload/download/files/'.$view_file['files'].'');
	    $fview .= '</div></div>';
	}elseif($format_audio == 'jpg' or $format_audio == 'png' or $format_audio == 'gif' or $format_audio == 'jpeg') {
	    $fview  = '<div class="form-group">';
            $fview .= '<div class="col-sm-10">';
	    $fview .= $cfile->player('player/uppod.swf','width="500" height="321"','player/photo/photo127-65.txt','/upload/download/files/'.$view_file['files'].'');									    
	    $fview .= '</div></div>';
	}
            //Вывод плеера
	    echo $fview.'</div>';					   
						
	$pview  = '<div class="modal-footer">';
	$pview .= '<a class="btn btn-success" href="?act=download&id='.$view_file['id'].'">';
	$pview .= Lang::__('Загрузить').'&nbsp;'.$view_file['name'].'</a></div>';
						
        //Вывод параметров скачиваний						
		echo $pview;