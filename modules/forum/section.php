<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    $id = intval($id);
//Если вместо id num попытаются вставить текст то выводит ошибку
if (!isset($id) || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}
    //Выводит названия по нужному $id
    $title = $db->get_array($db->query("SELECT * FROM `forum_subsection` WHERE `id` = '".$id."'"));
    //Получаем название по $id
    $templates->template(engine::ucfirst($title['name']));
    //Класс Времени
    $tdate = new Shcms\Component\Data\Bundle\DateType();

    //Отключаем форум
    $off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
	if($off_forum['off_forum'] == 1) {
	    echo engine::error(Lang::__('Форум приостановлен с ').$tdate->make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
	    echo engine::home(array('Назад','/index.php'));	 
	    exit;
	}

 //Если пользователь авторизован
 if($id_user == true) {
    echo '<a class="btn btn-primary right" href="new.topic.php?id='.$id.'">';
    echo ''.Lang::__('Новая тема').'</a>';
    echo '<br/><br/>';
    
}
    //Выводим счетчик тем
    $row = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_topics`"));
    //Подключаем навигацию
    $newlist = new Navigation($row[0],10,true);    
    
        //Если счетчик тем на 0 выводит ошибку
	if($row[0] == false) {
	    echo engine::error(Lang::__('В данном разделе нет ни одной темы!'));
	    exit;
	}
    //Начинаем вывод всех тем с базы
    $topics = $db->query("SELECT * FROM `forum_topics` WHERE `id_sec` = '".$id."' ORDER BY `id` DESC ". $newlist->limit()."");
        
        //Проверяем если ли темы в базе если да то выводит всех
	if($db->num_rows($topics) > 0) { 
	    echo '<div class="mainname">';
            echo '<img src="/engine/template/icons/allcaps.png">&nbsp;'.Lang::__('Все темы раздела').'</div>';
            echo '<div class="mainpost">';
            
            echo '<table class="itable"></tbody>';
            while($topic = $db->get_array($topics)) {
		//Получаем счетчик тем
                $rows = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$topic['id']."'"));
                //Выводим Ник пользователя
                $nick = $user->users($topic['id_user'],array('nick'));	
                //Вывод Всех определенных данных
                $sview   = '<tr class="">';
		    //Если тема закрыта выводит иконку
	            if($topic['close'] == 2) {
		        $sview .= '<td class="c_icon">';
                        $sview  .= '<img src="/engine/template/icons/locked.png">';
	                $sview  .= '</td>';
                    }else {
                        $sview .= '<td class="c_icon">';
                        $sview  .= '<img src="/engine/template/icons/nthem.png">';
	                $sview  .= '</td>';
                    }
		//Вывод данных из базы
                $sview .= '<td class="c_forum">';
                //Название тем
                $sview .= '<a href="post.php?id='.$topic['id'].'"><b>'.engine::ucfirst($topic['name']).'</b></a>';
                //Параметры темы
		$sview .= '<p class="desc">';
		$sview .= Lang::__('Автор:').'&nbsp;'.$nick.'&nbsp;,';
                $sview .= $tdate->make_date($topic['time']).'</p></td>';
                //Автор темы и Время создания
                $sview .= '<td class="c_stats"><ul>';
		$sview .= '<li><b>'.$rows[0].'</b>&nbsp;'.Lang::__('Ответов').'</li>';
                $sview .= '<li><b>'.$topic['views'].'</b>&nbsp;'.Lang::__('Просмотров').'</li>';
		$sview .= '</ul></td>';
                $sview .= '</tr>';
                //Выводим все данные
                echo $sview;
            }
	echo '</tbody></table></div>';
        
        //Вывод навигации
        echo $newlist->pagination('id='.$id.'');
        }else {
            echo '<div class="mainname">'.Lang::__('Тем не найдено').'</div>';
            echo '<div class="mainpost">';
	    echo engine::error(Lang::__('В данном разделе нет ни одной темы!'));
            echo '</div>';
	}
        
    //Переадресация на пред. страницу	
    echo engine::home(array('Назад','index.php'));	