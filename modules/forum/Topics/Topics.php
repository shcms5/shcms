<?php
/**
 * Класс Для работы с Темами Форума
 * 
 * @package            Forum/Topics/
 * @author             Shamsik
 * @link               http://shcms.ru
 */

namespace Forum\Topics;

use Shcms\Component\Form\Form;  
use Shcms\Component\Data\Bundle\DateType;
use engine;


class Topics {
    
    /**
     * Добавление Новой Темы
     * 
     * @param $param = array()
     */
    public static function AddTopics($id, $param = array()) {
        global $dbase,$error,$id_user;
        
        //По Выбору выводим навигацию
        if($param['nav'] == 'Nav') {
            //Навигация Вверхняя
            echo '<div class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="index.php" class="btn btn-default">Форум</a>
                <a href="#" class="btn btn-default disabled">Новая Тема</a>';
            echo '</div>';
        }
        
        $topics = $dbase->query("SELECT * FROM `forum_subsection` WHERE `id` = ? ",array($id));
        $topic = $topics->fetchArray();
        
        
        //Фильтруем Кнопку
        $submit = filter_input(INPUT_POST,'submit');
        //Фильтруем название
        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        //Обработка описмания
        $text = \filter_input(\INPUT_POST,'text',\FILTER_SANITIZE_STRING);
        
        //Если нажата кнопка
        if(isset($submit)) {
            //Проверяем Существует ли Имя введенное
           $object = $dbase->query("SELECT COUNT(*) as count FROM `forum_topics` WHERE `name`= '{$dbase->escape($name)}'");
           $row = $object->fetchArray();
            
            //Проверяет введена ли название
            if(empty($name)) {
	        $error['name'] = 'Введите название';
            }
            //Проверяет введена ли описание
            if(empty($text)) {
                $error['text'] = 'Введите Описание';
            }elseif ($row['count'] > 0) {
                $error['name'] = 'Введенная Тема уже существует';
            }elseif(empty($error)) {
                
	        //Установливаем Данные в Темы в Базу
                $dbase->insert('forum_topics',array(
                    'id_sec'  => ''.$topic['id'].'',
                    'id_cat'  => ''.$topic['id_cat'].'',
                    'id_user' => ''.$id_user.'',
                    'name'    => ''.$dbase->escape($name).'',
                    'text'    => ''.$dbase->escape($text).'',
                    'time'    => ''.time().''
                ));
                
                $topl = $dbase->query("SELECT * FROM `forum_topics` WHERE `id_sec` = ? AND `id_user` = ? AND `name` = ?",array(
                    $topic['id'],$id_user,$dbase->escape($name)
                ));
                $tops = $topl->fetchArray();
                
                //Создаем Новый Пост В Теме
                $dbase->insert('forum_post',array(
                    'id_sec'  => ''.$topic['id'].'',
                    'id_cat'  => ''.$topic['id_cat'].'',
                    'id_top'  => ''.$tops['id'].'',
                    'id_user' => ''.$id_user.'',
                    'text'    => ''.$dbase->escape($text).'',
                    'time'    => ''.time().''
                ));
                
                //Автомачитеская переадресация
	        header('Location: post.php?id='.$tops['id'].'');
                exit;
            }
        }
        //Данные о получение категорий
        $view = $dbase->query("SELECT `id`,`name` FROM `forum_category`");
        
        //HTML форма создания
        echo '<div class="mainname">'.\Lang::__('Создание Новой Темы').'</div>';
        echo '<div class="mainpost">';
        
            //Объявляем форму
            $form = new Form;
            
            //Получаем путь обработки и класс
            echo $form->open(array('name' => 'myForm','action' => '?do=new_topic&id='.$id.'','class' => 'form-horizontal','ng' => 'FormController'));
            
            echo '<div class="form-group"><br/>';
            //Заголовок Темы
            echo '<label class="col-sm-2 control-label">'.\Lang::__('Название Темы').'</label>';
            echo '<div class="col-sm-10">';
            //Поле ввода раздела
            echo $form->input(array('name' => 'name','class' => 'form-control','ng' => 'ng-model="form.name" ng-minlength="5" ng-maxlength="26"','required' => ''));
                //Если возникают ошибки
                if(isset($error['name'])) {
                    echo '<p class="text-danger">'.$error['name'].'</p>';
                }else {
                    echo '<p><div class="text-danger right" ng-messages="myForm.name.$error">
                        <div ng-message="required">Введите название темы</div>
                        <div ng-message="minlength">Некорректное название темы</div>
                        <div ng-message="maxlength">Название меньше 26 символов</div>';
                    echo '</div></p>';
                }       
                
            echo '</div></div>';
                
            //Описание Раздела
            echo '<div class="form-group"><br/>';
            echo '<label class="col-sm-2 control-label">Описание</label>';
            echo '<div class="col-sm-10">';
            //Поле ввода категории
            echo $form->textbox(array('name' => 'text','class' => 'form-control','id' =>'editor'));
                //Если возникают ошибки
                if(isset($error['text'])) {
                    echo '<p class="text-danger">'.$error['text'].'</p>';
                }else {
                    echo '<p class="text-danger right">Введите описание</p>';
                }       
                
            echo '</div></div>';             
            
            //Выводим Кнопку
            echo '<div class="modal-footer">';
            //Форма ввода кнопки
            echo $form->submit(array('name' => 'submit','value' => 'Создать тему','class' => 'btn btn-success','ng' => 'ng-disabled="myForm.$invalid"'));
            echo '<a class="btn" href="index.php">Отменить</a></div>';
            //Закрытие формы
            echo $form->close();
        echo '</div>';
        
    }
    
    /**
     * Получение заголовка стараницы
     * 
     * @param $id Индификатор раздела
     */   
    public static function Title($id) {
        global $dbase,$templates;
        
        //Получаем Заголовок Раздела
        $head = $dbase->query("SELECT * FROM `forum_subsection` WHERE `id` = ? ",array($id));
        $title = $head->fetch();
        
        //Выводим
        return $templates->template('Создание Новой Темы | '.$title->name);
    }    
    
    
    /**
     * Получение заголовка Темы
     * 
     * @param $id Индификатор раздела
     */   
    public static function TitleTopics($id,$name = false) {
        global $dbase,$templates;
        
        //Получаем Заголовок Раздела
        $head = $dbase->query("SELECT * FROM `forum_topics` WHERE `id` = ? ",array($id));
        $title = $head->fetch();
        
        //Выводим
        return $templates->template($title->name.' '.$name);
    }    
        

}
