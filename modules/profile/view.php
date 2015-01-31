<?php          
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
} 

//Каждый раз при переходе на профиль в базе  таблица views будет обновляться
$db->query('UPDATE `users` SET `views` = "'.($luser['views']+1).'" WHERE `id` = "'.$id.'"');
    //Редактируем профиль
    if(isset($id_user) and $id_user == $luser['id']) {
        echo '<div style="text-align:right;margin-bottom:5px;"><a class="btn btn-info" href="?act=edit_profile">Изменить данные</a></div>';
    }
	echo '<div class="mainname"><img src="/engine/template/icons/info.png">&nbsp;'.Lang::__('Информация').' о '.$luser['nick'].'</div>';
        echo '<div class="mainpost">';	
        echo '<div class="row">';
//Если аватар отсутствует
     echo '<div class="col-sm-5 col-md-3 col-xs-3">';
    if($luser['avatar'] == false or file_exists(H.'/upload/avatar/'.$luser['avatar'].'') == false) {
	echo '<img class="img-rounded img-responsive" src="/engine/template/avatar/no_avatar/default.png">';
    }else {
	echo '<img class="img-rounded img-responsive" src="/upload/avatar/'.$luser['avatar'].'">';
    }  
  echo '</div>';
  echo '<div class="col-sm-6 col-md-9 col-xs-6">';
    //Ник пользвателя
    echo '<h3 style="color: #323232;">'.$luser['nick'].'</h3><br/>';
    echo '<a href="?act=warnings&id='.$luser['id'].'">'.$luser['warnings'].'&nbsp;'.Lang::__('предупреждений').'</a><br/>';
    //Время первой регистрации
    echo 'Регистрация: '.user::realtime($luser['reg_date']).' назад<br/>';
		    
    // Время, в течении которого пользователь считается online (сек.)
    $delay = 120;
    $online = $luser['lastdate'] + $delay <= time();
				
    //Если пользователь вышел выведит Offline
    if($online) {
        echo '<span class="label label-warning">Не в сети </span>&nbsp;';
    }else {
        echo '<span class="label label-success">В сети </span>&nbsp;';
    }
    //Дата последнего посещения	
    echo $tdate->make_date($luser['lastdate']);
	if($id_user == true){
	    //Подключаем обработку модуля друзей
            include_once(H.'modules/profile/friends.php');
        }else {
            echo '<div class="privice"></div>';
        }
    //Массив Пола
    $pol_array = array(1 => Lang::__('Не определился'), 2 => Lang::__('Мужской'), 3 => Lang::__('Женский'));
    //Выводим счетчик отзывов
    $ccomment = $db->get_array($db->query("SELECT COUNT(*) FROM `vcomment` WHERE `id_prof` = {$luser['id']}"));
    //Массив Месяцев
    $month_array = array(0 => '--', 1 => Lang::__('Январь'), 2 => Lang::__('Февраль'),
                        3 => Lang::__('Март'), 4 => Lang::__('Апрель'), 5 => Lang::__('Май'),
                        6 => Lang::__('Июнь'), 7 => Lang::__('Июль'), 8 => Lang::__('Август'),
                        9 => Lang::__('Сентябрь'),10 => Lang::__('Октябрь'), 11 => Lang::__('Ноябрь'),
                        2 => Lang::__('Декабрь'));
    
    //Баллы и статус
    echo '</div></div></div><div class="tabbable">';
    echo '<ul class="nav nav-tabs nav-justified" style="margin-bottom:4px;">';
    echo '<li role="presentation"  class="active"><a href="#tab1" data-toggle="tab">Информация</a></li>';  
    echo '<li><a role="presentation"  href="#tab2" data-toggle="tab">Отзывы <strong>'.$ccomment[0].'</strong></a></li>';  
    echo '</ul><div class="tab-content">';  
    
        echo '<div class="tab-pane active fade in" id="tab1">';                    	
	//Дополнительная статистика
        echo '<div class="mainname"><img src="/engine/template/icons/user-info.png">&nbsp;'.Lang::__('Информация').'</div>';		
	echo '<div class="mainpost"><ul class="list_data clearfix">';
        //Группа в которой находится пользователь
	echo '<li class="clear clearfix">';
	echo '<span class="row_title">'.Lang::__('Группа').':</span>';
	echo '<span class="row_data">'.$groups->group_profile($luser['group']).'</span><br/>';
	echo '</li>';
	//Друзья пользователя
	echo '<li class="clear clearfix">';
	echo '<span class="row_title">'.Lang::__('Друзья').':</span>';
	echo '<span class="row_data"><a href="?act=friend_list&id='.$id.'">'.Lang::__('Перейти').'</a></span><br/>';
	echo '</li>';					
	//Количество просмотров профиля
	echo '<li class="clear clearfix">';
	echo '<span class="row_title">'.Lang::__('Просмотров').':</span>';
	echo '<span class="row_data">'.engine::number($luser['views']).'</span><br/>';
	echo '</li>';
	//День рождения
	if($luser['year'] == 0 and $luser['month'] == 0 and $luser['day'] == 0) {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('Родился').':</span>';
	    echo '<span class="row_data">'.Lang::__('Неизвестен').'</span><br/>';
	    echo '</li>';						
	}else {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('Родился').':</span>';
	    echo '<span class="row_data">'.$luser['day'],' '.$month_array[$luser['month']],' ',$luser['year'].' г</span><br/>';
	    echo '</li>';
	}
	//Значение пола
	echo '<li class="clear clearfix">';
	echo '<span class="row_title">'.Lang::__('Пол').':</span>';
	echo '<span class="row_data">'.$pol_array[$luser['pol']].'</span><br/>';
	echo '</li>';	
	//По дате определяем возрост
	if($luser['year'] == 0 and $luser['month'] == 0 and $luser['day'] == 0) {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('Возраст').':</span>';
	    echo '<span class="row_data">'.Lang::__('Неизвестен').'</span><br/>';
	    echo '</li>';
	}else {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('Возраст').':</span>';
	    echo '<span class="row_data">'.$user->age($luser['year'],$luser['month'],$luser['day']).'</span><br/>';
	    echo '</li>';
	}
        //Баллы
        echo '<li class="clear clearfix">';
        echo '<span class="row_title">'.Lang::__('Баллов').':</span>';
        echo '<span class="row_data"><img src="/engine/template/icons/points.png"> '.engine::number($luser['points']).'</span><br/>';
	echo '</li>';        
	//Город - выводит только когда поле город будет заполнено
	if($luser['city'] == true) {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('Город').':</span>';
	    echo '<span class="row_data">'.$luser['city'].'</span><br/>';
	    echo '</li>';
	}	
	//О себе - выводит только когда поле обо мне будет заполнено
	if($luser['desc'] == true) {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('О себе').':</span>';
	    echo '<span class="row_data">'.engine::input_text($luser['desc']).'</span><br/>';
	    echo '</li>';
	}													
    echo '</ul></div>';
    //Контактные данные
    echo '<div class="mainname"><img src="/engine/template/icons/contact.png">&nbsp;'.Lang::__('Контактная информация').'</div>';	
    echo '<div class="mainpost"><ul class="list_data clearfix">';
	//E-mail пользователя
	if($luser['email'] == true) {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('E-mail').':</span>'; 
	    echo '<span class="row_data"><img src="/engine/template/icons/email.png"> <a href="mailto:'.$luser['email'].'">'.$luser['email'].'</a></span><br/>';
	    echo '</li>';
        }
	//Сайт - выводит только когда поле SITE будет заполнено
	if($luser['site'] == true) {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('Сайт').':</span>';
	    echo '<span class="row_data"><img src="/engine/template/icons/url.png"> <a href="http://'.$luser['site'].'">'.$luser['site'].'</a></span><br/>';
	    echo '</li>';
        }	
	//Skype - выводит только когда поле skype будет заполнено
	if($luser['skype'] == true) {
	    echo '<li class="clear clearfix">';
	    echo '<span class="row_title">'.Lang::__('Skype').':</span>';
            echo '<span class="row_data"><img src="/engine/template/icons/skype.png"> <a href="skype:'.$luser['skype'].'?call">'.$luser['skype'].'</a></span><br/>';
	    echo '</li>';
        }
    echo '</ul></div>';
    echo ' </div>';
    echo '<div class="tab-pane fade in" id="tab2">'; 
        if($luser['coom_prof'] == 1) {
            echo '<br/>';
            echo engine::error('Комментарии отключены Пользователем');
        }else {
            include_once 'comment.php';
        }

    echo '</div>'; 
    echo '</div></div>';

echo engine::home(array('Назад','/index.php'));
							
							