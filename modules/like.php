<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');

$templates->template(Lang::__('Мне нравится')); //Название страницы

//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();

            //Если не авторизован пользователь то переадресация на главную
        if($id_user == false) { //$id_user - id пользователя 
		    header("Location: ../index.php"); //Переадресация
			exit; //Закрыть дальнейщее действие
		}       
 	//Выводим счетчик мне нравится
       	if ($result = $db->query("SELECT COUNT(*) FROM `like` WHERE `id_user` = '".$id_user."'")) {
        		/* Переход к строке №400 */
         		$result->data_seek(399);
         		/* Получение строки */
          		$row = $result->fetch_row();
       	}
		   //Подключение навигационной системы
            $newlist = new Navigation($row[0],10,true);
			
            //Если счетчик на 0 выведит
	        if($row[0] == false) {
				echo engine::error(Lang::__('Не найдено понравившиеся темы!'));
				exit;
			}  		
	
    //Выводить все данные из таблицы Мне нравится	
    $likes = $db->query("SELECT * FROM `like` WHERE `id_user` = '".$id_user."'  ORDER BY `id` DESC ". $newlist->limit()."");
        while($like = $db->get_array($likes)) { //Вывод всех определенных полей
	        $topics = $db->get_array($db->query("SELECT * FROM `forum_topics` WHERE `id` = '".$like['id_list']."' ")); //Определение названия темы
			    //Определения автора темы
				$nick = $user->users($topics['id_user'],array('nick'));
	        	echo '<div class="subpost">'; //Открытие блока div
	                echo '<b><a href="/modules/forum/post.php?id='.$topics['id'].'">'.$topics['name'].'</a> <span title="Автор темы" style="color: #9E9C9C;font-size:10px;">'.$nick.'</span></b>'; //Выводим названия и путь к теме
			        echo '<span class="time">'.$tdate->make_date($topics['time']).'</span><br/>'; //Дата создания темы
			        echo '<div style="font-size:11px;color:green;">'.$like['text'].'</div>'; //Дополнительный текст к теме 
	            echo '</div>'; //Закрытия блока div
	    }
			//Вывод навигации
			echo $newlist->pagination();
				
		echo engine::home(array('Назад','/modules/menu.php')); //Переадресация			
?>