<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

//Комментарии
echo '<div class="mainname">Комментарии <b>'.$row1[0].'</b></div>';
echo '<div class="mainpost">';
    //Из $_POST превращаем в обычные переменные и убираем слэши
    $error = array();
    $submit = filter_input(INPUT_POST,'submit');
    $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
        if(isset($submit)) {
            //Если текст отсутствует
            if(empty($text)) {
            	$error['text'][] = 'Введите текст комментария';
	    }elseif(empty($error)) {
	        //Если пользователь авторизован под своим ником то , добавляем новый пост в базу
	        if($id_user == true) {
		    $mysql = $db->query("INSERT INTO `down_comment` (`id_user`,`id_file`,`text`,`time`) VALUES ('".$id_user."','".$id."','".$db->safesql($text)."','".time()."')");
        	    //Если все правильно
        	    if( $mysql == true ) {
		        $db->query("UPDATE `users` SET `points` = '".($users['points']+1)."' WHERE `id` = '".intval($id_user)."'"); // Начисление баллов
          	        header('Location: view.php?act=comment&id='.$id.'');
            	        exit;
                    }
		    else {
		        header('Location: view.php?act=comment&id='.$id.'');
		    }
                }
            }
    	}
	//Если авторизован пользователь то выведит ему форма
	if($id_user == true) {
	    //Форма для печати сообщений
            $form = new \Shcms\Component\Form\Form;
            //Открываем форму
            echo $form->open(array('action' => '?act=comment&id='.$id.''));
            //Текст сообщения
            echo $form->textbox(array('name' => 'text','id' => 'editor'));
            //Если ошибки
            if(isset($error['text'])) {
                echo '<div class="text-danger">' . implode('<br />', $error['text']) . '</div><br />';
            }
            //Кнопка
            echo '<br/>';
            echo $form->submit(array('name' => 'submit','class' => 'btn btn-success'));
            //Закрываем
            echo $form->close();	
	}
	        
        echo '</div>';
			
    //Выводим счетчик постов
    $row = $db->get_array( $db->query( "SELECT COUNT(*) FROM `down_comment` WHERE `id_file` = '".intval($id)."'" ) );
    $newlist = new Navigation( $row[0], 10, true );
        //Если писем больше 1 выводит из базы данные
        if( $row[0] > 0 ) {
	    //Выводим все данные и таблицы `news_comment`
	    $query = $db->query("SELECT * FROM `down_comment` WHERE `id_file` = '".$id."' ORDER BY `id` DESC ". $newlist->limit()."");
	}else {
	    echo engine::warning(Lang::__('Комментарий не найдено'));
	    echo engine::home(array(Lang::__('Назад'),'dir.php?id='.$view_file['id_dir'].''));
	    exit;
	}		
        
    echo '<div class="mainpost">';
    echo '<div class="qa-message-list" id="wallmessages">';
    
        while($comment = $db->get_array($query)) {
            //Получаем данные о пользователе
            $profile = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '{$comment[id_user]}'"));
            //Получение данных о привилегий
            $group = user::users($id_user, array('group'));
            //Получаем время
            $tdate = new Shcms\Component\Data\Bundle\DateType();
            
            //Путь к аватарам
            $avatar = '/upload/avatar/' . $profile['avatar'];

    	    echo '<div class="message-item">';
                echo '<div class="message-inner">';
	            echo '<div class="message-head clearfix">';
                        //Получаем действующий аватар
	                echo '<div class="avatar pull-left">';
                            if ($profile['avatar'] == false and file_exists($avatar) == false) {
                                echo '<img class="Chatimg" src="/engine/template/icons/default_large.png">';
                            } else {
                                echo '<img class="Chatimg" src="' . $avatar . '">';
                            }
                        echo '</div>';
			//Данные поста					
                        echo '<div class="user-detail">';
                            //Автор добавления
		            echo '<h5 class="handle">';
                            echo '<a href="'.MODULE.'profile.php?id='.$profile['id'].'">'.$profile['nick'].'</a>';
                            echo '</h5>';
                                //Разделитель
		                echo '<div class="post-meta">';
		                    echo '<div class="asker-meta">';
			                echo '<span class="qa-message-what"></span>';
		                        echo '<span class="qa-message-when">';
			                echo '<span class="qa-message-when-data">';
                                    //Дополнительные данные    
                                    echo '<div style="border-bottom: 0px;" class="details">';
                                        //Привиление пользователя
                                        echo '<span>';
                                        echo '<img src="/engine/template/icons/group.png">&nbsp;'.user::group($comment['id_user']);
                                        echo '</span>';
                                        //Дата добавления
                                        echo '<span>';
                                        echo '<img src="/engine/template/icons/date.png">&nbsp;'.$tdate->make_date($comment['time']);
                                        echo '</span>';
                                    echo '</div>';
                                        echo '</span>';
			                echo '</span>';		
		                    echo '</div>';
		                echo '</div>';
                            echo '</div>';
	                echo '</div>';
                        //Текст сообщения                        
		        echo '<div class="qa-message-content">'.engine::input_text($comment['text']).'</div>';
	 
                echo '</div></div>'; 
        }

        echo '</div>';
        echo '</div>';

        //Получаем постаничную навигацию
        echo $newlist->pagination( 'id='.$id.'' ); 
 	
    //Переадресация	
echo engine::home( array( Lang::__('Назад') , 'view.php?id='.$id.'' ) );