<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}    
    //Если кнопка нажата 
    $submit = filter_input(INPUT_POST,'submit');
    
    //Идет обработка данных
    if(isset($submit)) {
        //Проверка переданного $_POST
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
        //Экранируем специальные символы
        $text = $db->safesql($text);
        //Если пользователь является основателем аккаунта
        if($luser['id'] == $id_user) {
            $error['text'][] = 'Ошибка при добавление отзыва';
        } 
        if (engine::trim($text) == false and empty($text)) {
            $error['text'][] = 'Сообщение не введено';
        }
        //Короткие сообщение не должны отправляться
        if (strlen($text) < 5) {
            $error['text'][] = 'Сообщение должно быть больше 5 символов';
        }
        //В сообщение не должно присутствовать мат
        if ($glob_core['antimat'] == 1) {
            $text = SwearFilter::filter($text);
        }
        //В сообщение не должен присутствовать реклама (ссылки)
        if ($glob_core['antiadv'] == 1) {
            $text = engine::antilink($text);
        }
        //Удаляем ссылки из текстов
        if ($glob_core['antilink'] == 1) {
            $text = engine::removing($text);
        }
        //Если хотябы одна ошибка найдется в $error то не допустится
        if (empty($error)) {
            //Записываем сообщение в базу
            $chat = $db->query("INSERT INTO `vcomment` (`id_user`,`id_prof`,`text`,`time`)
                    VALUES ('{$id_user}','{$luser['id']}','{$text}','{$_date}')");
            if ($chat == true) {
                //Начисление баллов пользователю
                user::points();
                //Переадресция после успешной отправки
                header('Location: profile.php?id='.$luser['id'].'');
            } else {
                $error['text'][] = 'Сообщение не добавлено';
            }
        }
    }
    //Если $id_user true то даем запрос в базу
    if($id_user == true) {
        $commentq = $db->query("SELECT * FROM `vcomment` WHERE `id_user` = {$id_user} AND `id_prof` = {$luser[id]}");
    }
    //Если пользователь не создатель аккаунта + Если у него нет отзывов
    if($db->num_rows($commentq) == 0 and $id_user != $luser['id']  and $id_user == true) {

            //Название поля
            echo '<div class="mainname">' . Lang::__('Сообщение') . '';
            echo '</div>';
            echo '<div class="mainpost">';
            //HTML форма добавления отзыва
            $form = new form('profile.php?id='.$luser['id'].'#tab2');
            $form->textarea(false, 'text', false, (isset($error['text']) ? '<span style="color:red"><small>' . implode('<br />', $error['text']) . '</small></span><br />' : ''));
            $form->submit('Отправить', 'submit',true,'btn btn-success');
            $form->display();
            echo '</div>';
    }else {
        //Счетчик вывода сообщений По умолчанию 10
        $count = $db->get_array($db->query("SELECT COUNT(*) FROM `vcomment` WHERE `id_prof` = {$luser['id']}"));
        //Объявляем вывод навигации
        $list = new Navigation($count[0], 10, true);

        //Если в базе есть хоть одно сообщение то выводим их все.
        if ($count[0] > 0) {
            $query = $db->query("SELECT * FROM `vcomment` WHERE `id_prof` = {$luser['id']} ORDER BY `id` DESC " . $list->limit() . "");
        
        echo '<div class="mainname">Отзывы о пользователе</div>';    
        echo '<div class="mainpost">';
        echo '<ul class="List_withminiphoto Pad_list">';
        //Вывод всех отзывов
        while ($recall = $db->get_array($query)) {
            
            //Получаем данные о пользователе
            $profile = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$recall['id_user']."'"));
            echo '<li class="clearfix row">';
            //Путь к аватарам
            $avatar = '/upload/avatar/' . $profile['avatar'];

            //Если нет аватара выводим аватар по умолчанию
            if ($profile['avatar'] == false or file_exists($avatar) == false) {
                echo '<a href="/engine/template/icons/default_large.png" class="UserPhotoLink left">';
                echo '<img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini"></a>';
            } else {
                echo '<a href="' . $avatar . '" title="Просмотр профиля" class="UserPhotoLink left">';
                echo '<img src="' . $avatar . '" class="UserPhoto UserPhoto_mini"></a>';
            }

            echo '<div class="list_content">';
            //Ник Автора сообщения
            echo '<a href="../profile.php?id=' . $profile['id'] . '"><b>' . $profile['nick'] . '</b></a>';
            echo '<div class="details">';
            //Привиление пользователя
            echo '<span><img src="/engine/template/icons/group.png">&nbsp;' . user::group($recall['id_user']) . '</span>';
            //Время добавление сообщения
            echo '<span><img src="/engine/template/icons/date.png">&nbsp;' . $tdate->make_date($recall['time']) . '</span>';
            echo '</div>';
            //Текст сообщения
            echo '<div class="lighter row2">' . engine::input_text($recall['text']) . '</div>';
            echo '</div>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';

        //Получаем постаничную навигацию
        echo $list->pagination('id='.$luser['id'].'');
        
        } else {
            echo '<div class="mainname">'.Lang::__('Не найдено ничего').'</div>';
            echo '<div class="mainpost"><center>' . Lang::__('Отзывов пока нет!') . '</center></div>';
        }
    }