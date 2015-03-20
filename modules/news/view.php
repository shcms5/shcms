<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');


//Обрабатываем $_GET
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
/* @var $id type intval */
$id = intval($id);
//Создаем массив
$error = array();


    //Если нужные параметры не доступны
    if (!isset($id) || !is_numeric($id)) {
        header('Location: index.php');
        exit;
    }
	
        //Создаем массив
        $error = array();
        //Библиотека Angular подключаем
        echo engine::AngularJS();
        
        //Класс Времени
        $tdate = new Shcms\Component\Data\Bundle\DateType();

	//Выводим название с базы для title
        $newsc = $db->query("SELECT * FROM `news` WHERE `id` = '".$id."'");
        
        if($db->num_rows($newsc) == 0) {
            header('Location: /modules/news/index.php');   
            exit;
        }else {
            $news = $db->get_array($newsc);
        }    
        $templates->template($news['title'],$news['text'],$news['title']); //Название страницы
        
                

       
        //Определяем ник
	$nick = $user->users($news['id_user'],array('nick'),false);
	//Определяем id
        $id_users = $user->users($news['id_user'],array('id'));
                          
        //Обновляем таблицу view (Чтобы счетчик просмотров поднимался)
        $db->query("UPDATE `news` SET `view` = '".($news['view']+1)."' WHERE `id` = '".$id."'");
			
	//Определяем раздел новости
	$views = $db->get_array($db->query("SELECT * FROM `news_category` WHERE `id` = '".$news['id_cat']."'"));
	    //След. и предыдущая новость
            include_once('core/nextview.php');
		
            //Действие для администраторов
	    if($users['group'] == 15) {
                //Редактируем новость
		$resulte = '&nbsp;<a href="/admin/news/index.php?do=enews&id='.$news['id'].'">';
                $resulte .= '<img src="/engine/template/icons/edit.png"></a>';
                //Удаляем новость
		$resulte .= '&nbsp;<a href="setting.news.php?act=delete_sec&id='.$news['id'].'">';
                $resulte .= '<img src="/engine/template/icons/delete.png"></a>';
	    }
									
        //Выводи все записанные данные
        $wview  = '<div class="mainname">'.$news['title'].'<span class="time">'.$resulte.'</span></div>'; 
        $wview .= '<div class="mainpost">';
        $wview .= '<div class="details">';
        $wview .= '<span><img src="/engine/template/icons/folder.png">&nbsp;';
        $wview .= '<a style="color:#1E90FF;" href="/modules/news/category.php?id='.$views['id'].'">'.$views['name'].'</a>';
        $wview .= '</span>';
	$wview .= '<span><img src="/engine/template/icons/author.png">&nbsp;';
        $wview .= '<a style="color:#1E90FF;" href="'.MODULE.'profile.php?act=view&id='.$id_users.'">'.$nick.'</a>';
        $wview .= '</span>';
	$wview .= '<span><img src="/engine/template/icons/eye.png">&nbsp;';
        $wview .=  '<font color="#778899">'.engine::number($news['view']).'</font>';
        $wview .=  '</span>';
        $wview .=  '<span><img src="/engine/template/icons/date.png">&nbsp;';
        $wview .=  '<font color="#778899">'.$tdate->make_date($news['time']).'</font>';
        $wview .=  '</span>';
        $wview .=  '<div class="right"><div class="btn-group">';
        $wview .=  '<input class="btn btn-small" type="button" value="Сменить шрифт" ng-click="myVar=\'my-class\'">';
        $wview .=  '<input class="btn btn-small" type="button" value="Вернуть" ng-click="myVar=\'\'">';        
        $wview .=  '</div></div>';        
        $wview .=  '</div>';
    
    echo $wview;
        echo '<span class="base-class" ng-class="myVar">';
	echo '<div style="text-align: justify;" class="row2">'.engine::input_text($news['text']);
        echo '</span>';
            if($news['editm'] == 1) {
                echo '<div style="text-align:right;"><i>'.$news['textm'].','.$tdate->make_date($news['timem']).'</i></div>';
            }
        echo '</div></div> '; 
	//Выводим счетчик постов
            $row1 =  $db->get_array($db->query("SELECT COUNT(*) FROM `news_comment` WHERE `id_news` = '".$id."'"));
                
            if($news['addcomm'] == 0) {
                echo '<div class="mainname">Комментарии <b>'.$row1[0].'</b></div>';

		//Из $_POST превращаем в обычные переменные и убираем слэши
                $submit = filter_input(INPUT_POST,'submit');
	        //Из $_POST превращаем в обычные переменные
                $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
            if(isset($submit)) {

		//Если текст отсутствует
                if(empty($text)) {
                    $error['text'][] = 'Введите комментарий';
                }
		
                //Если пользователь авторизован под своим ником то , добавляем новый пост в базу
		if(empty($error) and $id_user == true) {	
                    $db->query("INSERT INTO `news_comment` (`id_user`,`id_news`,`text`,`time`) VALUES ('{$id_user}','{$id}','".$db->safesql($text)."','".time()."')");
		    user::points();
                    header('Location: ?id='.$id.'');
                }
            }

        //Обработка полученного Индификатора поста
        $id_quote = filter_input(INPUT_GET,'id_quote',FILTER_SANITIZE_NUMBER_INT);
        $id_quote = intval($id_quote);
        
        //Если нажата цитирование
        if($id_quote == true) {
            //Вытаскиваем из базы выбранный пост для цитирования
            $tquote = $db->get_array($db->query("SELECT * FROM `news_comment` WHERE `id` = '{$id_quote}'"));
            $newsnick = $user->users($tquote['id_user'],array('nick'),false);
            //Форма цитирования
            $quotet = '[quote='.$newsnick.']'.$tquote['text'].'[/quote]';            
        }
	//Если авторизован пользователь то выведит ему форма
        if($id_user == true) {

            echo '<div class="mainpost">';
	        
	    //Форма для печати сообщений
            $form = new \Shcms\Component\Form\Form;
            //Открываем форму
            echo $form->open(array('action' => $id.'?'));
            //Текст сообщения
            echo $form->textbox(array('name' => 'text','id' => 'editor','value' => $quotet));
            //Если ошибки
            if(isset($error['text'])) {
                echo '<div class="text-danger">' . implode('<br />', $error['text']) . '</div><br />';
            }
            //Кнопка
            echo '<br/>';
            echo $form->submit(array('name' => 'submit','class' => 'btn btn-success'));
            //Закрываем
            echo $form->close();                
	
            echo '</div>';
        }
	//Выводим счетчик постов
        $row = $db->get_array($db->query("SELECT COUNT(*) FROM `news_comment` WHERE `id_news` = '".$id."'"));
        $newlist = new Navigation($row[0],10,true);  
        
	    //Если писем больше 1 выводит из базы данные
            if($row[0] > 0) {
                //Выводим все данные и таблицы `news_comment`
	        $query = $db->query("SELECT * FROM `news_comment` WHERE `id_news` = '".$id."' ". $newlist->limit()."");
	    }else {
                echo '<div class="mainpost">';
                echo engine::warning('Комментарий не найдено.');
                 echo '</div>';
		echo engine::home(array('Назад','/modules/news/index.php'));
		exit;
	    }		
    echo '<div class="mainpost">';
    echo '<ul class="List_withminiphoto Pad_list">'; 
    
        while($comment = $db->get_array($query)) {
            //Получаем данные о пользователе
            $profile = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '{$comment[id_user]}'"));
            //Получение данных о привилегий
            $group = user::users($id_user, array('group'));
            $viewd = '';
            
            if($id_user != $comment['id_user']  and $id_user == true) {
                $viewd .= '<a title="Цитирование" href="?id='.$id.'&id_quote=' . $comment['id'] . '">';
                $viewd .= '<i style="font-size:14px;" class="glyphicon glyphicon-tasks"></i></a>';
            }
            
            echo '<li class="clearfix row3">';
            //Путь к аватарам
            $avatar = '/upload/avatar/' . $profile['avatar'];

                //Если нет аватара выводим аватар по умолчанию
                 if ($profile['avatar'] == false and file_exists($avatar) == false) {
                    echo '<a href="/engine/template/icons/default_large.png" class="UserPhotoLink left">';
                    echo '<img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini"></a>';
                } else {
                    echo '<a href="' . $avatar . '" title="Просмотр профиля" class="UserPhotoLink left">';
                    echo '<img src="' . $avatar . '" class="UserPhoto UserPhoto_mini"></a>';
                }

            echo '<div class="list_content">';
            
            //Ник Автора сообщения
            echo '<a href="../profile.php?id=' . $profile['id'] . '"><b>' . $profile['nick'] . '</b></a>';
            echo '<span class="time">' . $viewd . '</span>';
            echo '<div class="details">';
            
            //Привиление пользователя
            echo '<span><img src="/engine/template/icons/group.png">&nbsp;' . user::group($comment['id_user']) . '</span>';
            
            //Время добавление сообщения
            echo '<span><img src="/engine/template/icons/date.png">&nbsp;' . $tdate->make_date($comment['time']) . '</span>';
            echo '</div>';
            
            //Текст сообщения
            echo '<div class="lighter row2">' . engine::input_text($comment['text']) . '</div>';
            echo '</div>';
            echo '</li>';
        }
	
    echo '</ul></div>';
   //Вывод навигации
   echo $newlist->pagination(); 	
    }else {
        echo engine::error(Lang::__('Комментарии отключены автором'));
    }
            
    echo engine::home(array('Назад','/modules/news/index.php'));
