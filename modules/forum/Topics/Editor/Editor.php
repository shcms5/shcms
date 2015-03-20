<?php
/**
 * Класс Для работы с Редактированием Темы
 * 
 * @package            Forum/Topics/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace Forum\Topics\Editor;

use Shcms\Component\Form\Form;

class Editor {
    
    /**
     * Получение заголовка стараницы
     * 
     * @param $id Индификатор раздела
     */   
    public static function Etitle($id) {
        global $dbase,$templates;
        
        //Получаем Заголовок Раздела
        $head = $dbase->query("SELECT * FROM `forum_topics` WHERE `id` = ? ",array($id));
        $title = $head->fetch();
        
        //Выводим
        return $templates->template('Редактирование темы | '.$title->name);
    }    

    /**
     * Редактирование
     * 
     * @param Null
     */
    public static function Edit() {
        global $id,$dbase,$error,$topics,$users,$id_user;
        
        if($id_user != $topics['id_user'] and $users['group'] != 15) {
            header('Location: index.php?do=topics&id='.$id.'');
        }
        //Получение Категорий по Индификатору
        $topicq = $dbase->query("SELECT * FROM `forum_topics` WHERE `id` = ?",array($id));
        
            echo '<div class="mainname">Изменение Раздела</div>';
            echo '<div class="mainpost">';
            $edit = $topicq->fetch();
            //Обработка Кнопки
            $update = filter_input(INPUT_POST,'update',FILTER_DEFAULT);
            //Обработка Названия
            $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
            
            //Проверяем Нажата ли кнопка
            if(isset($update)) {
                //Если не введена название раздела
                if(empty($name)) {
                    $error['updates'][] = 'Введите название раздела!';
                }elseif(empty($error)) {
                    //Если все выполнена правильно то обновляет название в базе
                    $dbase->update('forum_topics',array(
                        'name' => ''.$dbase->escape($name).'',
                    ),'id = '.$id.'');
                    
                    //Закидываем обновленные данные в Историю
                    $dbase->insert('action',array(
                        'name'     => ''.$dbase->escape($name).'',
                        'id_topic' => ''.$id.'',
                        'id_user'  => ''.$id_user.'',
                        'action'   => ''.\Lang::__('Редактирование Заголовка').'',
                        'time'     => ''.time().''
                    ));
                    
                    //Автопереадресация
                    \header('Location: index.php?do=topics&id='.$id.'&act=editor');
                }
            }
            
            //Объявление формы
            $form = new Form;
            //Открытие Формы
            echo $form->open(array('action' => 'index.php?do=topics&id='.$id.'&act=editor','class' => 'form-horizontal'));
            echo '<br/><div class="form-group">';
            echo '<label for="inputEmail3" class="col-sm-2 control-label">Название:</label>';
            echo '<div class="col-sm-10">';
            //Уведомление для формы
            echo $form->input(array('name' => 'name','value' => $edit->name,'class' => 'form-control'));
                if(isset($error['updates'])) {
                    echo '<span style="color:red"><small>' .implode('<br />',$error['updates']). '</small></span><br />';
                }
            echo '</div></div>';
        
            
            //Кнопка
            echo '<div class="modal-footer">';
            echo $form->submit(array('name' => 'update','value' => 'Применить','class' => 'btn btn-success'));
            echo '<a class="btn btn-default" href="index.php?do=topics&id='.$id.'">Отмена</a>';
            echo '</div></div>';
            
            //Закрываем Форму
            echo $form->close();
    }
   
    
    public static function Close() {
        global $id,$dbase,$error,$topics,$users,$id_user;
        
        if($id_user != $topics['id_user'] and $users['group'] != 15) {
            header('Location: index.php?do=topics&id='.$id.'');
            exit;
        }
        //обновляем таблицу для введение новых данные
        $dbase->update('forum_topics',array(
            'close' => '2'
        ),array(
            'id' => $id
        ));
        //Закидываем обновленные данные в Историю
        $dbase->insert('action',array(
           'name'     => ''.$topics['name'].'',
           'id_topic' => ''.$id.'',
           'id_user'  => ''.$id_user.'',
           'action'   => ''.\Lang::__('Закрытие темы').'',
           'time'     => ''.time().'',
        ));
        
       header('Location: index.php?do=topics&id='.$id.'');
    }
    
    public static function Open() {
        global $id,$dbase,$error,$topics,$users,$id_user;
        
        if($id_user != $topics['id_user'] and $users['group'] != 15) {
            header('Location: index.php?do=topics&id='.$id.'');
            exit;
        }
        //обновляем таблицу для введение новых данные
        $dbase->update('forum_topics',array(
            'close' => '1'
        ),array(
            'id' => $id
        ));
        //Закидываем обновленные данные в Историю
        $dbase->insert('action',array(
           'name'     => ''.$topics['name'].'',
           'id_topic' => ''.$id.'',
           'id_user'  => ''.$id_user.'',
           'action'   => ''.\Lang::__('Закрытие темы').'',
           'time'     => ''.time().'',
        ));
        
       header('Location: index.php?do=topics&id='.$id.'');
    }    
}
