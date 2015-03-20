<?php
/**
 * Класс Для работы с Комментариями в Файлах
 * 
 * @package            Files/Directory/
 * @author             Shamsik
 * @link               http://shcms.ru
 */

namespace files\Directory;

class Comment {

    /**
     * Получение Навигации страницы
     * 
     * @param $param Null
     * @param $id Индификатор раздела
     */       
    public static function getTitle($param = [],$id = false) {
        global $dbase;
        
        //Выводим данные по $id
        $views = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$id]);
        $view = $views->fetchArray();
        
        //По Выбору выводим навигацию
        if($param['nav'] == 'Nav') {
            //Навигация Вверхняя
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($view['name']).'-'.$id.'" class="btn btn-default">'.$view['name'].'</a>';
            echo '<a disabled href="" class="btn btn-default">Комментарии</a>';
            echo '</span>';
        }
    }
    
    /**
     * Обработка Добаляемого комментария
     * 
     * @param $id Индификатор
     */      
    public static function PostForm($id) {
        global $app,$dbase,$users,$id_user;
        
        //Выводим данные по $id
        $views = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$id]);
        $view = $views->fetchArray();        
        //Обрабокта кнопки
        $submit = filter_input(INPUT_POST,'submit', FILTER_DEFAULT);
        //Обработка текста
        $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
    
        //Если нажата кнопка
        if($submit == true) {
            //Если пустой текст
            if(empty($text)) {
                $app->redirect('/modules/files/comment/'.\Shcms\Component\String\Url::filter($view['name']).'-'.$id.'');
            }else {
                //Добавляем комментарий
                $dbase->insert('down_comment',[
                    'id_user' => ''.$id_user.'',
                    'id_file' => ''.intval($id).'',
                    'text'    => ''.$dbase->escape($text).'',
                    'time'    => ''.time().''
                ]);
                //Добавляем балл пользователю
                $dbase->update('users',[
                    'points'  => ''.($users['points']+1).''
                ],[
                    'id'      => ''.$id_user.''
                ]);
                //Переадресация
                $app->redirect('/modules/files/comment/'.\Shcms\Component\String\Url::filter($view['name']).'-'.$id.'');
            }
        }
    }
    
    /**
     * Получение всех данных и Форму
     * 
     * @param $id Индификатор
     */          
    public static function GetForm($id) {
        global $dbase,$profi,$tdate,$id_user;
   
        //Получаем Счетчик комментариев
        $ccount = $dbase->query("SELECT COUNT(*) as count FROM `down_comment` WHERE `id_file` = ?",[$id]);
        $count = $ccount->fetchArray();
    
        //Блок комментариев
        echo '<div class="mainname">Комментарии <b>'.$count['count'].'</b></div>';
        
        //Если не найдены комментарии
        if($count['count'] == 0) {
            echo \engine::warning(\Lang::__('Комментариев не найдено'));
        }else {
            //Вывод всех комментариев
            echo '<div class="mainpost">';
            //Объявляем навигацию
            $newlist = new \Navigation( $count['count'], 20, true );
            //Выводим комментарии по выбранному файлу
            $row = $dbase->query("SELECT * FROM `down_comment` WHERE `id_file` = ? ORDER BY `id` ". $newlist->limit()."",[$id]);
        
            echo '<div class="qa-message-list" id="wallmessages">';
                //Подготовка комментариев на вывод
                foreach ($row->fetchAll() as $result) {
                    //Профиль пользователя
                    $profile = $profi->Views(['*'],$result->id_user);
                    //Путь аватара
                    $avatar = '/upload/avatar/' . $profile['avatar'];
                    
                    echo '<div class="message-item">';
                    echo '<div class="message-inner">';
                    echo '<div class="message-head clearfix">';
                    
                    //Получаем действующий аватар
                    echo '<div class="avatar pull-left">';
                    if ($profile['avatar'] == false and file_exists($avatar) == false) {
                        echo '<img class="Chatimg" src="/engine/template/icons/default_large.png">';
                    }else {
                        echo '<img class="Chatimg" src="' . $avatar . '">';
                    }
                    echo '</div>';
                    
                    //Данные поста	
                    echo '<div class="user-detail">';
                    
                    //Автор добавления
                    echo '<h5 class="handle">';
                    echo '<a href="'.PROFILE.'?id='.$profile['id'].'">'.$profile['nick'].'</a>';
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
                    echo '<img src="/engine/template/icons/group.png">&nbsp;'.\user::group($result->id_user);
                    echo '</span>';
                    //Дата добавления
                    echo '<span>';
                    echo '<img src="/engine/template/icons/date.png">&nbsp;'.$tdate->make_date($result->time);
                    echo '</span></div>';
                    
                    echo '</span></span>';	
                    echo '</div></div>';
                    echo '</div></div>';
                    //Текст комментария
                    echo '<div class="qa-message-content">'.\engine::input_text($result->text).'</div>';
                    
                    echo '</div></div>';
                }
                echo '</div></div>';
                
            //Получаем постаничную навигацию
            echo $newlist->pagination( 'id='.$id.'' ); 
        } 
    
	//Если пользователь TRUE то показываем форму
	if($id_user == true) {
            
            echo '<div class="mainname">'.\Lang::__('Новый комментарий').'</div>';
            echo '<div class="mainpost">';
            
	    //Форма для печати сообщений
            $form = new \Shcms\Component\Form\Form;
            //Открываем форму
            echo $form->open(array('action' => ''));
            //Текст сообщения
            echo $form->textbox(array('name' => 'text','id' => 'editor'));
            //Кнопка
            echo '<br/>';
            echo $form->submit(array('name' => 'submit','class' => 'btn btn-success'));
            //Закрываем
            echo $form->close();	
            echo '</div>';
	}
    }

}