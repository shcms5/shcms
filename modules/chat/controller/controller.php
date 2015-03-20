<?php
/**
 * Класс для работы с чатом
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
use Shcms\Component\Form\Form;  
use Shcms\Component\Data\Bundle\DateType;

class controller {
    /*
     * Обрабатываем отправленный текст
     * 
     * @param $param 
     */

    public static function comment($param) {
        global $dbase,$_date, $glob_core, $id_user, $error;

        //Проверка переданного $_POST
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
        //Экранируем специальные символы
        $text = $dbase->escape($text,true);

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
            $chat = $dbase->insert('chat',array(
                'text' => ''.$text.'',
                'time' => ''.$_date.'',
                'id_user' => ''.$id_user.''        
            ));
            if ($chat == true) {
                //Начисление баллов пользователю
                user::points();
                //Переадресция после успешной отправки
                header('Location: index.php');
            } else {
                $error['text'][] = 'Сообщение не добавлено';
            }
        }
    }

    /**
     * HTML форма добавления сообщений
     * 
     * @param form()
     */

    public static function form($text = false) {
        global $error, $id_user;
        //Привелегие пользователя
        $group = user::users($id_user, array('group'));
        //Если у пользователя привилегия 15
        if ($group == 15) {
            echo '<div class="btn-margin">';
            echo '<a class="btn btn-danger" href="?do=all">' . Lang::__('Удаление постов') . '</a>';
            echo '</div>';
        }
        
        //Полученная форма

        echo '<div class="mainname">' . Lang::__('Новое сообшение') . '</div>';
        echo '<div class="mainpost">';

        
        //HTML форма
        
        $form = new Form;
        echo $form->open(array('action' => '?','method' => 'post'));
        echo $form->textbox(array('name' => 'text','value' => $text,'id' => 'editor'));
        if(isset($error['text'])) {
           echo '<span style="color:red"><small>' . implode('<br />', $error['text']) . '</small></span><br />';
        }
        echo '<br/>';
        echo $form->submit(array('name' => 'submit','class' => 'btn btn-success'));
        echo $form->close();        

        echo '</div>';
    }

    /**
     * Вывод всех сообщений чата
     * 
     * @param output()
     */

    public static function output() {
        global $dbase, $id_user;
        
        //Счетчик вывода сообщений По умолчанию 10
        $rowc = $dbase->query("SELECT COUNT(*) as count FROM `chat`");
        $count = $rowc->fetchArray();        
        $list = new Navigation($count['count'], 20, true);
        //Если в базе есть хоть одно сообщение то выводим их все.
        if ($count['count'] > 0) {
            $query = $dbase->query("SELECT * FROM `chat` ORDER BY `id` DESC " . $list->limit() . "");
        } else {
            echo '<div class="mainname">'.Lang::__('Сообщений нет').'</div>';
            echo '<div class="mainpost">';
            echo engine::warning(Lang::__('Сообщений не найдено'));
            echo '</div>';
            exit;
        }

        echo '<div class="mainname">Всего постов: '.$count['count'].'</div>';
        echo '<div class="mainpost">';
        echo '<div class="qa-message-list" id="wallmessages">';
        
        //Вывод всех сообщений
        foreach( $query->fetchAll() as $chat ) {
            //Получаем данные о пользователе
            $viewz = $dbase->query("SELECT * FROM `users` WHERE `id` = ? ",array($chat->id_user));
            $profile = $viewz->fetch(); 
            //Получение данных о привилегий
            $group = user::users($id_user, array('group'));
            //Объявляем класс времени
            $tdate = new DateType;
            //Если у привилегии 15 то выполняем действие
            $viewds = '';
            
            
            if ($group == 15) {
                $viewds .= '<a href="index.php?do=delete&id=' . $chat->id . '">';
                $viewds .= '<i style="font-size:14px;" class="glyphicon glyphicon-trash"></i></a>&nbsp;';
            }
            if($id_user != $chat->id_user and $id_user == true) {
                $viewds .= '<a title="Цитирование" href="index.php?quote&id=' . $chat->id . '">';
                $viewds .= '<i style="font-size:14px;" class="glyphicon glyphicon-tasks"></i></a>';
            }else {
                $viewds .= '';
            }
            //Путь к аватарам
            $avatar = '/upload/avatar/' . $profile->avatar;

    	    echo '<div class="message-item">';
                echo '<div class="message-inner">';
	            echo '<div class="message-head clearfix">';
                        //Получаем действующий аватар
	                echo '<div class="avatar pull-left">';
                            if ($profile->avatar == false and file_exists($avatar) == false) {
                                echo '<img class="Chatimg" src="/engine/template/icons/default_large.png">';
                            } else {
                                echo '<img class="Chatimg" src="' . $avatar . '">';
                            }
                        echo '</div>';
			//Данные поста					
                        echo '<div class="user-detail">';
                            //Автор добавления
		            echo '<h5 class="handle">';
                            echo '<a href="'.MODULE.'profile.php?id='.$profile->id.'">'.$profile->nick.'</a>';
                            echo '<span class="time">' . $viewds . '</span>';
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
                                        echo '<img src="/engine/template/icons/group.png">&nbsp;'.user::group($chat->id_user);
                                        echo '</span>';
                                        //Дата добавления
                                        echo '<span>';
                                        echo '<img src="/engine/template/icons/date.png">&nbsp;'.$tdate->make_date($chat->time);
                                        echo '</span>';
                                    echo '</div>';
                                        echo '</span>';
			                echo '</span>';		
		                    echo '</div>';
		                echo '</div>';
                            echo '</div>';
	                echo '</div>';
                        //Текст сообщения                        
		        echo '<div class="qa-message-content">'.engine::input_text($chat->text).'</div>';
	 
                echo '</div></div>'; 
        }

        echo '</div>';
        echo '</div>';

        //Получаем постаничную навигацию
        echo $list->pagination();
        echo engine::home( array( Lang::__('Назад') , '/' ) );
    }

}