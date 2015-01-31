<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
//Проверяем если $_GET существует то выведит данные ниже
if(isset($_GET['id']) and is_numeric($_GET['id'])) {
    
	//Преобразуем из $_GET в обычную
	$id = (int) $_GET['id'];

	//Проверяем существует ли выбранный пользователь   
if(!$luser['id']) {
	echo engine::error(Lang::__('Извините, мы не можем найти это!'),Lang::__('Вы запросили профиль несуществующего пользователя.'));
	echo engine::home(array(Lang::__('Назад'),'/index.php'));
	exit;
}
	//Выводим счетчик друзей
    if ($result = $db->query("SELECT COUNT(*) FROM `friends` WHERE `id_user` = '".$id."' AND `approved` = '1'")) {
        /* Переход к строке №400 */
        $result->data_seek(399);
        /* Получение строки */
        $row = $result->fetch_row();
    }
	    //Навигация постраничная
        $newlist = new Navigation($row[0],10, true); 


		//Если друзей больше 1 выводит из базы данные
        if($row[0] > 0) {
            //Выводим все данные и таблицы `chat`
	        $friend = $db->query("SELECT * FROM `friends` WHERE `id_user` = '".$id."' AND `approved` = '1' ORDER BY `id` DESC ". $newlist->limit()."");
        // А если меньше 0 то выводит это сообщение
		}else {
			echo '<div class="mainpost">Друзей не найдено!</div>';
			echo engine::home(array(Lang::__('Назад'),'?id='.$id.'')); //Переадресация 
			exit;
		}
    //Данные 
	echo '<div class="mainname">'.Lang::__('Друзья').'</div>';
	echo '<div class="mainpost">';
	//Открытие блока ul
	echo '<ul class="List_withminiphoto Pad_list">';
        //Вывод всех друзей id пользователя
		while($friends = $db->get_array($friend)) {
		    //Выводит данные id друзей из таблиц users
			$frend = $db->get_array($db->query('SELECT * FROM `users` WHERE `id` = "'.$friends['id_friends'].'"'));
                //Обработка ника
				$nick_list = $user->users($frend['id'],array('nick'));
                    
					//Открытие li
					echo '<li class="clearfix">';
					    //Путь к картинке
						echo '<a href="" title="Просмотр профиля" class="UserPhotoLink left"><img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini"></a>';	
						//Профиль выводит
						echo "<div class='list_content'><a href='?id=".$frend['id']."'>".$nick_list."</a>";
						//Дата дружбы
						echo '<br><span class="desc lighter">'.$tdate->make_date($friends['time']).'</span></div>';
					//Закрытие li	 
					echo '</li>';
		}
    //Закрытие ul
	echo '</ul>';
	echo '</div>';
}
			    //Вывод навигации
                echo $newlist->pagination(); 
				
echo engine::home(array(Lang::__('Назад'),'?id='.$id.'')); //Переадресация 