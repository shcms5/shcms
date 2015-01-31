<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
     //Выводим счетчик тем
    $row = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_topics`"));
    
    //Начинаем вывод всех тем с базы
    $topics = $db->query("SELECT * FROM `forum_topics` ORDER BY `id` DESC LIMIT 6");
        
        //Проверяем если ли темы в базе если да то выводит всех
	if($db->num_rows($topics) > 0) { 
	    echo '<div class="mainname">';
            echo '<img src="/engine/template/icons/allcaps.png">&nbsp;'.Lang::__('Новые темы раздела');
            echo '<span class="right"><a href="/modules/forum/">Форум</a></span>';
            echo '</div>';
            
            while($topic = $db->get_array($topics)) {
		//Получаем счетчик тем
                $rows = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$topic['id']."'"));
                //Выводим Ник пользователя
                $nick = $user->users($topic['id_user'],array('nick'));	
                //Вывод Всех определенных данных
                $sview   = '<table class="posts_gl"><tbody><tr class="">';
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
                $sview .= '<a href="/modules/forum/post.php?id='.$topic['id'].'"><b>'.engine::ucfirst($topic['name']).'</b></a>';
                //Параметры темы
		$sview .= '<p class="desc">';
		//$sview .= Lang::__('Автор:').'&nbsp;'.$nick.'&nbsp;,';
                //$sview .= date::make_date($topic['time']).';
                $sview .= engine::substr(engine::input_text($topic['text']),500);
                $sview .= '</p></td>';
                //Автор темы и Время создания
                $sview .= '<td style="width: 10%;" class="c_stats"><ul>';
		$sview .= '<li><b>'.$rows[0].'</b>&nbsp;'.Lang::__('Ответов').'</li>';
                $sview .= '<li><b>'.$topic['views'].'</b>&nbsp;'.Lang::__('Просмотров').'</li>';
		$sview .= '</ul></td>';
                $sview .= '</tr></tbody></table>';
                //Выводим все данные
                echo $sview;
            }
        
        }else {
            echo '<div class="mainname">'.Lang::__('Тем не найдено').'</div>';
            echo '<div class="mainpost">';
	    echo engine::error(Lang::__('В данном разделе нет ни одной темы!'));
            echo '</div>';
	}
        echo '<br/>';