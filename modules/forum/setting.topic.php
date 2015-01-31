<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Опции Темы')); //Название страницы

    //Отключения форума
    //Вывод определенных данных
    $off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
	if($off_forum['off_forum'] == 1) {
	    echo engine::error(Lang::__('Форум приостановлен с ').date::make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
	    echo engine::home(array('Назад','/index.php'));	 
	    exit;
	}
        
    //Если вместо id num попытаются вставить текст то выводит ошибку
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header('Refresh: 1; url=index.php');
        engine::error(Lang::__('Произошла ошибка при выборки темы')); //При ошибке
        exit;
    }
        //из $_GET в обычную переменную
        $id = (int) $_GET['id'];
		
    //Проверяем существует ли тема
    $query = $db->query("SELECT * FROM `forum_topics` WHERE `id` = '".$id."'");
    
	if (!$db->num_rows($query)) {
    	    header('Location: index.php');
   	    exit;
        }
        
	//Выводим данные `id_user`
	$user_topic = $db->get_array($query);
	//Проверяем является ли пользователем создатем темы
	if($id_user != $user_topic['id_user'] and $users['group'] != 15) {
	    header('Location: index.php');
            exit;
	}
	
    switch($act):
        //По умолчанию выводим список функций
        default:
            echo '<div class="mainname">'.Lang::__('Настройка темы').'</div>';
                echo '<div class="mainpost">';
                echo '<div class="row3"><img src="/engine/template/icons/pencil.png">&nbsp;<a href="?act=editor&id='.$id.'">Редактировать заголовок</a></div>';
		    //Если автонистратор темы не закрыл тему выведит это
		    if($user_topic['close'] == 1) {
                echo '<div class="row3"><img src="/engine/template/icons/closed.png">&nbsp;<a href="?act=close&id='.$id.'">Закрыть тему</a></div>';
		    //А если закрыл тему и хочет открыть выведит это	
		    }elseif($user_topic['close'] == 2) {
                echo '<div class="row3"><img src="/engine/template/icons/kyellow.png">&nbsp;<a href="?act=open&id='.$id.'">Открыть тему</a></div>';		
		    }	
                echo '<div class="row3"><img src="/engine/template/icons/delete.png">&nbsp;<a href="?act=delete&id='.$id.'">Удалить</a></div>';
                echo '<div class="row3"><img src="/engine/template/icons/history.png">&nbsp;<a href="?act=history&id='.$id.'">Посмотреть историю</a></div>';
                echo '</div>';
						//Переадресация
        echo engine::home(array(Lang::__('Назад'),'post.php?id='.$id.'')); //Переадресация
        break;
    
	//Редактируем название темы
    case'editor':
        //Выводим все данные по теме $id
        $editor = $db->get_array($db->query("SELECT * FROM `forum_topics` WHERE `id` = '".$id."'"));
            //Если нажата кнопка и введена название выведит
            if(isset($_POST['name']) and isset($_POST['update'])) {
			    //Обрабатываем название
			    $name = engine::proc_name($_POST['name']);
            //Если название отсутствует
			if(!$name) {
                engine::error(Lang::__('Вы не ввели название темы'));
            }else {
                //Если все выполнена правильно то обновляет название в базе
			    $db->query("UPDATE `forum_topics` SET `name` = '".$db->safesql($name)."' WHERE `id` = '".$id."'");
				 //Закидываем обновленные данные в Историю				
				$db->query('INSERT INTO `action` (`name`,`id_topic`,`time`,`id_user`,`action`) VALUES ("'.$user_topic['name'].'","'.$user_topic['id'].'","'.time().'","'.$user_topic['id_user'].'","'.Lang::__('Изменение в название темы').'") ');				

			    echo engine::success(Lang::__('Название темы успешно изменена')); //Успешно
			    echo engine::home(array(Lang::__('Назад'),'setting.topic.php?id='.$id.'')); //Переадресация
			    exit;
            }
            }

        //Форма
        echo '<div class="mainpost">';
            $form = new form('?act=editor&id='.$id.''); //Пусть к обработке  формы / $_POST
            $form->input(Lang::__('Название темы:'),'name','text',$editor['name']); //Поле редактрования
            $form->submit(Lang::__('Изменить'),'update'); //Кнопка
            $form->display(); //Выводит данные формы
        echo '</div>';
		//Переадресация
        echo engine::home(array(Lang::__('Назад'),'setting.topic.php?id='.$id.'')); //Переадресация
    break;
	
	//Удаление темы
    case 'delete':
	//Закидываем обновленные данные в Историю
	$db->query('INSERT INTO `action` (`name`,`id_topic`,`time`,`id_user`,`action`) VALUES ("'.$user_topic['name'].'","'.$user_topic['id'].'","'.time().'","'.$user_topic['id_user'].'","'.Lang::__('Выполнялось удаление темы').'")');				
			//Если вы нажали на подтверждение то будет удалена тема с постами
			if(isset($_POST['yes_delete'])) {
                //Удаляем тему				
                $db->query('DELETE FROM `forum_topics` WHERE `id` = "'.$id.'"');
               	//Удаляем все сообщение из темы
                $db->query('DELETE FROM `forum_post` WHERE `id_top` = "'.$id.'"');				
				//Успешное удаление всех выбранных данных
				echo engine::success(Lang::__('Тема успешно удалена'));
                echo engine::home(array(Lang::__('Назад'),'index.php')); //Переадресация
				exit;
			//Если вы нажали на отмену то переадресовывается на пред. страницу
			}elseif(isset($_POST['no_delete'])){
			    header('Location: setting.topic.php?id='.$id.''); //Пред. стараница
			}
			
			
		//Подтверждение	
		echo '<div class="mainname">'.Lang::__('Подтверждение').'</div>';	
		echo '<div class="mainpost">';
		echo Lang::__('Вы действительно хотите удалить выбранную тему? Данное действие невозможно будет отменить.').'<hr/>';
		//Форма удаление
		echo '<div style="text-align:right;">';
        $form = new form('?act=delete&id='.$id.'');		
		$form->submit(Lang::__('Да'),'yes_delete');
        $form->submit(Lang::__('Нет'),'no_delete');		
        $form->display();
		echo '</div></div>';
	break;
	
	//Функция закрытие темы
	case 'close':
	
			//Если вы нажали на подтверждение то будет закрыта тема для обсуждений
			if(isset($_POST['yes_close'])) {
                //обновляем таблицу	для введение новых данные					
                $db->query('UPDATE `forum_topics` SET `close` = "2" WHERE `id` = "'.$id.'"');	
                //Закидываем обновленные данные в Историю				
				$db->query('INSERT INTO `action` (`name`,`id_topic`,`time`,`id_user`,`action`) VALUES ("'.$user_topic['name'].'","'.$user_topic['id'].'","'.time().'","'.$user_topic['id_user'].'","'.Lang::__('Закрытие темы').'") ');				

				//Успешное закрытие темы
			    echo engine::success(Lang::__('Тема успешно закрыта'));
                echo engine::home(array(Lang::__('Назад'),'setting.topic.php?id='.$id.'')); //Переадресация
				exit;
			//Если вы нажали на отмену то переадресовывается на пред. страницу
			}elseif(isset($_POST['no_close'])){
			    header('Location: setting.topic.php?id='.$id.''); //Пред. стараница
			}
			//Подтверждение	
		echo '<div class="mainname">'.Lang::__('Подтверждение').'</div>';	
		echo '<div class="mainpost">';
		echo Lang::__('Вы действительно хотите закрыть выбранную тему? Данное действие невозможно будет отменить.').'<hr/>';
		//Форма удаление
		echo '<div style="text-align:right;">';
        $form = new form('?act=close&id='.$id.'');		
		$form->submit(Lang::__('Закрыть'),'yes_close');
        $form->submit(Lang::__('Отмена'),'no_close');		
        $form->display();
		echo '</div></div>';
	break;

	//Функция открытие темы
	case 'open':
	
                //обновляем таблицу	для введение новых данные			
                $db->query('UPDATE `forum_topics` SET `close` = "1" WHERE `id` = "'.$id.'"');
				//Закидываем обновленные данные в Историю
                $db->query('INSERT INTO `action` (`name`,`id_topic`,`time`,`id_user`,`action`) VALUES ("'.$user_topic['name'].'","'.$user_topic['id'].'","'.time().'","'.$user_topic['id_user'].'","'.Lang::__('Открытие темы').'") ');				
				//Успешное открытие темы
			    echo engine::success('Тема успешно открыта');
                echo engine::home(array(Lang::__('Назад'),'setting.topic.php?id='.$id.'')); //Переадресация
				exit;
	break;	
	
	
	case 'history':
	
	   		//Выводим счетчик действий
    if ($result1 = $db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$id."'")) {
        /* Переход к строке №400 */
        $result1->data_seek(399);
        /* Получение строки */
        $row1 = $result1->fetch_row();
    }
            //Определяем через $id_user ник пользователя 
			$nick = $user->users($user_topic['id_user'],array('nick'));	
	echo '<div class="mainname">'.Lang::__('История темы').'</div>';
	echo '<div class="mainpost">';
	echo '<div class="subpost">Название темы: <span style="float:right;"">'.$user_topic['name'].'</span></div>';
	echo '<div class="subpost">Дата открытия: <span style="float:right;"">'.date::make_date($user_topic['time']).'</span></div>';
	echo '<div class="subpost">Автор: <span style="float:right;"">'.$nick.'</span></div>';
	echo '<div class="subpost">Всего сообщений: <span style="float:right;"">'.$row1[0].'</span></div>';
	echo '</div>';
	
	//Журнал всех действий со стороны администратора
	echo '<div class="mainname">'.Lang::__('Журнал действий администратора темы').'</div>';
	echo '<div class="mainpost">';
	//Выводим таблицы данных с историей
	echo "<table class='little-table' cellspacing='0'>"; 
       echo "<tr> 
            <th>".Lang::__('Имя пользователя')." </th> 
            <th>".Lang::__('Тема')."</th> 
            <th>".Lang::__('Описание')."</th> 
            <th>".Lang::__('Дата')."</th> 
            </tr>";
  
   		//Выводим счетчик действий
    if ($result = $db->query("SELECT COUNT(*) FROM `action` WHERE `id_topic` = '".$id."'")) {
        /* Переход к строке №400 */
        $result->data_seek(399);
        /* Получение строки */
        $row = $result->fetch_row();
    }
	
	if($row[0] == false) {
        echo engine::error('Результатов нет');
		exit;
	}
	    //Подключаем навигацию
        $newlist = new Navigation($row[0],10,true);   
		    
			//Выводим данные из таблицы где $id
            $table = $db->query("SELECT * FROM `action` WHERE `id_topic` = '".$id."' ORDER BY `id` DESC ". $newlist->limit()."");
                while($tables = $db->get_array($table)) {
                    //Определяем через $id_user ник пользователя 
					$nick = $user->users($tables['id_user'],array('nick'));
					//Определяем через $id_user id пользователя 
	                $id_users = $user->users($tables['id_user'],array('id'));
					
					//Вытаскаваем название темы с таблицы
                    $topic = $db->get_array($db->query("SELECT * FROM `forum_topics` WHERE `id` = '".$tables['id_topic']."'"));
                        //Теперь через while все по сортировке выводим
						echo "<tr class='even'> 
                            <td><a href='".MODULE."profile.php?act=view&id=".$id_users."'>".$nick."</td> 
                            <td>".$topic['name']."</td> 
                            <td>".$tables['action']."</td> 
                            <td>".date::make_date($tables['time'])."</td> 
                            </tr></tr> ";
                } 
			//Далее закрываем таблицу	
            echo "</table> <br/>";
				//Вывод навигации
                echo $newlist->pagination('act=history&id='.$id.''); 			
	        echo '</div>';	
			//Переадресация
        echo engine::home(array(Lang::__('Назад'),'setting.topic.php?id='.$id.'')); //Переадресация
	break;
	endswitch;
?>