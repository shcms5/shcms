<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
//Название страницы
$templates->template('Ваш почтовый ящик');

//Загружаем почтовый контроллер
include_once 'mailbox/controller.php';
 
//Объявляем класс почтового контроллера
$mailc = new controller;    

$tdate = new Shcms\Component\Data\Bundle\DateType();

//Проверяем не отключена ли почта    
echo $mailc->offmail();


//Если пользователь не авторизован
if(empty($id_user)) {
    header('Location: /index.php');
    exit;
}
	
switch($do):
    //Если не отключена почта то включаем 
    case 'onmail':
        if($users['off_mail'] == 1) {
            //Обновляем таблицу
            $db->get_array($db->query("UPDATE `users` SET `off_mail` = '0' WHERE `id` = '{$id_user}'"));
            //Переадресация
            header('Location: messaging.php');
            exit;
        }
    break;    
    //Если включена почта то отключаем
    case 'off':
        if($users['off_mail'] == 0) {
            //Обновляем таблицу
            $db->get_array($db->query("UPDATE `users` SET `off_mail` = '".intval(1)."' WHERE `id` = '{$id_user}'"));
            //Переадресация
            header('Location: messaging.php');
            exit;
        }
    break;          
endswitch;        
                
        
//Отключение пользовательской почты
echo $mailc->off_mail();
        
        
//Выводим все папки если есть
echo '<div class="mainname">'.Lang::__('Папки').'</div>';
    //Выводим счетчик постов
    $row = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging_topics` WHERE `id_user` = '".$id_user."'"));
    //Выводим счетчик постов
    $row1 = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging_topics` WHERE `id_user` = '".$id_user."' AND `id_dir` = '3'"));   		

//Выводим папки Почты    
echo $mailc->dir_mail();    
 
    
//Проверяем состояние почтового ящика
echo '<br/><div class="mainname">'.Lang::__('Состояние ящика').'</div>';
    echo '<div class="mainpost">';
    //Получаем прогресс бар
    echo '<div class="progress progress-striped active">';
    echo '<div class="progress">';
    //Прогресс бар
    echo '<div class="progress-bar progress-bar-striped active"
        role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" 
        style="width: '.$row[0].'%;">';
    //Создано тем
    echo  $row[0].'%</div>';
    echo '</div></div>';
    //Сколько набралось и сколько осталось
    echo '<p>'.$row[0].'% от лимита ('.$mailc->limit().' тем)</p>';
    echo '</div>'; 
	
    
switch($act):
    
    /**
     * По умолчанию выводим все темы
     */    
    default:
        //Если темы превышают по указанным ограничением
        // то удаляете ненужные темы
	if($row[0] >= $mailc->limit() ) {
	    echo engine::error(Lang::__('Превышен лимит создании тем, Удалите старые темы'));
	}
        
        //Проверка если есть в базе данные то выводит из если нет то ничего не выводим
        $rows1 = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging` WHERE `id_user` = '".$id_user."'"));

        //Мои переписки	
        echo '<div class="mainname">'.Lang::__('Мои переписки').'</div>';
   
        //Счетчик тем
        $rows = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging_topics_user` WHERE `id_user` = '".$id_user."'"));  
    
        //Проверка доступны ли темы
        if($rows[0] == 0) {
            echo engine::warning(Lang::__('Не найдено тем для просмотра'));
	    exit;
        }
    
        //Объявляем навигацию
        echo $mailc->dnavigation();
        //Выводим список тем
        echo $mailc->my_them();
        //Используем навигацию
        echo $mailc->dnavigation_end();
       
    break;

    /**
     * Получение тем из разных таблиц
     */  
    case 'message':
        
	switch($do):
            default:
                //Обработка полученного Индификатора
                $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
                $id = intval($id);

                //Передаем данные по $id и распределяем по папкам данные
                if(!isset($id) and !is_numeric($id)) {
                    header('Location: menu.php');
                    exit;
                }
                
                //Получение заголовок при переходах
                echo $mailc->mpath();
        
                //Для каждого перехода получаем отдельный счетчик        
                if($id == 1) {
                    $rows = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging_topics` WHERE `id_user` = '".$id_user."' AND `time` > '".(time()-86400)."'"));		
                }elseif($id == 2) {
	            $rows = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging_topics` WHERE `id_user` = '".$id_user."'"));
                }elseif($id == 3) {
	            $rows = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging_topics` WHERE `id_user` = '".$id_user."' AND `id_dir` = '3'"));
                }   

                //Если тем не найдено
                if($rows[0] == 0) {
	            echo engine::error(Lang::__('Тем не найдено!'));
	            exit;
                }
                
                //Постраничная навигация
                $newlist = new Navigation($rows[0],10, true);    
    
                //Для каждого перехода получаем отдельную таблицу
                if($id == 1) {
	            $user_topics = $db->query("SELECT * FROM `messaging_topics_user` WHERE `id_user` = '".$id_user."' AND `time` > '".(time()-86400)."' ORDER BY `id` DESC ". $newlist->limit()."");			      
                }elseif($id == 2) {
	            $user_topics = $db->query("SELECT * FROM `messaging_topics_user` WHERE `id_user` = '".$id_user."' ORDER BY `id` DESC ". $newlist->limit()."");
                }elseif($id == 3) {
	            $user_topics = $db->query("SELECT * FROM `messaging_topics_user` WHERE `id_user` = '".$id_user."' AND `id_dir` = '3' ORDER BY `id` DESC ". $newlist->limit()."");			
                }
        
                //Счетчик тем
                $rows = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging_topics_user` WHERE `id_user` = '".$id_user."'"));  
    
                //Проверка доступны ли темы
                if($rows[0] == 0) {
                    echo engine::warning(Lang::__('Не найдено тем для просмотра'));
	            exit;
                }
                //Вытаскиваем темы из разных таблиц
                echo $mailc->my_them(true);
                //Получение навигации
                echo $newlist->pagination('act=message&id='.$id.'');
                //Переадресация
                echo engine::home(array(Lang::__('Назад'),'messaging.php'));
            break;
        endswitch;
   
    break; 

    /**
     * Получение сообщений из темы
     */  
    case 'topics':
    
        //Обработка полученного Индификатора
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($id);

        //Передаем данные по $id и распределяем по папкам данные
        if(!isset($id) and !is_numeric($id)) {
            header('Location: menu.php');
            exit;
        }

        //Получаем из базы названия темы через $id
        $mtopics = $db->get_array($db->query("SELECT * FROM `messaging_topics` WHERE `id` = '".$id."'"));
        //Получаем из базы все сообщения и дополнительные данные
        $mail = $db->get_array($db->query("SELECT * FROM `messaging` WHERE `id_topics` = '".$id."'"));
        //Получаем данные по автору темы
        $nick = $user->users($mail['id_user'],array('nick'),true);
        //Получаем данные по получателю темы
        $nick_post = $user->users($messaging['id_post'],array('nick'),true);
        //Обработка кнопки
        $submit = filter_input(INPUT_POST,'submit',FILTER_DEFAULT);

        //Если нажата кнопка то идет обработка сообщения
        if(isset($submit)) {
            //Функция обработки
            $mailc->stopics();
        }
        		
        echo '<div class="mainname">'.Lang::__('Текст ответа').'</div>';
        echo '<div class="mainpost">';
            //Получаем форму Для отправки с сообщения
            $mailc->formtopic();
        echo '</div>';

        echo '<div class="mainname">'.Lang::__('Тема').'&nbsp;'.$mtopics['name'].'</div>';
            echo '<div class="mainpost">';
                echo '<ul class="List_withminiphoto Pad_list">';

                    //Счетчик постов
                    $row = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging` WHERE `id_topics` = '".$id."'"));
                    //Навигация постраничная
                    $newlist = new Navigation($row[0],10, true);
                    //Получаем вывод всех сообщений
                    echo $mailc->viewtopic();
                    
                echo '</ul>';
            echo '</div>';
            
        //Вывод навигации
        echo $newlist->pagination('act=topics&id='.$id.''); 
        
    break;
	
    /**
     * Создание и обработка новой темы
     */  
    case 'newsend':
        
        //Если темы превышают по указанным ограничением
        // то удаляете ненужные темы
        if($row[0] >= $mailc->limit() ) {
	    echo engine::error(Lang::__('Превышен лимит создании тем, Удалите старые темы'));
	    exit;
	}
        
        //Обработка полученного Индификатора
        $userid = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($userid);

        //Передаем данные по $id и распределяем по папкам данные
        if(!isset($id) and !is_numeric($id)) {
            header('Location: menu.php');
            exit;
        }
	
        //Проверяем заблокировал ли пользователь отправителя
        $ignor = $db->get_array($db->query("SELECT * FROM `ignor` WHERE `id_ignor` = '".$id_user."' AND `id_user` = '".$id."'"));
    
            //По возможности запрет на получение писем
            if($ignor['ignor_mess'] and $ignor['id_ignor'] == $id_user) {
	        echo engine::error(Lang::__('Данный пользователь запретил вам отправку сообщений!'));
	        echo engine::home(array(Lang::__('Назад'),'/modules/profile.php?id='.$id.''));
	        exit;
            }
            
            //Если попытаесь отправить самому себе то произойдет ошибочное действие
            if($id == $id_user) {
	        echo engine::error(Lang::__('Вы не можете отправить сообщение самому себе')); //Текст ошибки
	        echo engine::home(array(Lang::__('Назад'),'/modules/profile.php?id='.$id.'')); //Переадресация
	        exit;
            }
            //Получение данных о пользователе
            $userpro = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '{$id}'"));
    
            //Проверяем не отключил ли получатель почту
            if($userpro['off_mail'] == 1 ) {
                echo '<div class="mainname">'.Lang::__('Ошибка при создании темы').'</div>';
                echo '<div class="mainpost">';
                echo engine::error('Выбранный пользователь отключить систему ЛС, вы не сможете отправить письмо');
                echo '<div class="modal-footer">';
                echo '<a class="btn" href="profile.php?id='.$id.'">Отмена</a>';
                echo '</div>';
                echo '</div>';
                exit;
            }
    
            //Обработка кнопки
            $submit = filter_input(INPUT_POST,'submit',FILTER_DEFAULT);
    
            //Если нажата кнопка
            if(isset($submit)) {
                $mailc->newsend();
            }
            
     	    //Получаем данные об получателе
            $profile = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '{$id}'"));
	        echo '<div class="mainname">'.Lang::__('Новое личное сообщение').'</div>';
	        echo '<div class="mainpost">';
                    //Форма создание новой темы
                    $mailc->formsend();
                echo '</div>';
        
    break;
    
        
    /**
     * Удаление темы и все что с ней связано
     */  
    case 'delete_topics':
        
        //Обработка полученного Индификатора
        $topic = filter_input(INPUT_GET,'topicid',FILTER_SANITIZE_NUMBER_INT);
        $topic = intval($topic);

        if(isset($topic)) {
	
	    //Если все правильно то идет удаление....
	    if(isset($_POST['yes_delete'])) {
	        //Удаляем сообщение в теме
		$db->query("DELETE FROM `messaging` WHERE `id_topics` = '".$topic."'");
		//Удаляем темы с обеих сторон
		$db->query("DELETE FROM `messaging_topics_user` WHERE `id_topics` = '".$topic."'");
		//Полная очистка темы
		$db->query("DELETE FROM `messaging_topics` WHERE `id` = '".$topic."'");
		//И переадресуем на пред страницу
		header("Location: /modules/messaging.php");	
	    }elseif(isset($_POST['no_delete'])) {
		//И переадресуем на пред страницу
		header("Location: /modules/messaging.php");	
	    }
	
	    //Подтверждение	
	    echo '<div class="mainname">Подтверждение действий</div>';	
	    echo '<div class="mainpost">';
	    echo engine::warning('Вы действительно хотите удалять эту переписку?');
	    //Форма удаление
	    echo '<div style="modal-footer">';
            
                    //HTML Форма
                    $form = new Shcms\Component\Form\Form();
                    echo $form->open(array('action' => '?act=delete_topics&topicid='.$topic.''));
                    echo '<div class="modal-footer">';
                    echo $form->submit(array('name' => 'yes_delete','value' => 'Удалить','class' => 'btn btn-success'));
                    echo $form->submit(array('name' => 'no_delete','value' => 'Отменить','class' => 'btn btn-info'));     
                    echo $form->close();
                    echo '</div>';
                
	    echo '</div>';
            echo '</div>';
	}

    
    break;


endswitch;	