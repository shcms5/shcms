<?php
/**
 * Класс Для работы с Редактированием Категорий
 * 
 * @package            Forum/Category/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace Forum\Category\Editor;

use Shcms\Component\Form\Form;

class Editor {
    /**
     * Редактирование
     * 
     * @param Null
     */
    public static function Edit() {
        global $id,$dbase,$error;
        //Получение Категорий по Индификатору
        $category = $dbase->query("SELECT * FROM `forum_category` WHERE `id` = ?",array($id));
        
            echo '<div class="mainname">Изменение Категории</div>';
            echo '<div class="mainpost">';
            $edit = $category->fetch();
            
            //Обработка кнопки
            $update = filter_input(INPUT_POST,'update',FILTER_DEFAULT);
            //Обработка названия
            $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
            
            //Проверяем нажата ли кнопка и если ли в поле input текст
            if(isset($update)) {
                 //Если не введена название категории
                if(empty($name)) {
                    $error['updates'][] = 'Введите название категории!';
                }elseif(empty($error)) {
                    //Если все выполнена правильно то обновляет название в базе
                    $dbase->update('forum_category',array(
                        'name' => ''.$dbase->escape($name).''
                    ),'id = '.$id.'');
                    header('Location: index.php?do=setting');
                }
            }
            
            //Объявляем форму
            $form = new Form;
            //Открытие формы
            echo $form->open(array('action' => 'index.php?do=setting&act=editor&id='.$id.'?','class' => 'form-horizontal'));
            
            //Название
            echo '<br/><div class="form-group">';
            echo '<label for="inputEmail3" class="col-sm-2 control-label">Название:</label>';
            echo '<div class="col-sm-10">';
            echo $form->input(array('name' => 'name','value' => $edit->name,'class' => 'form-control'));
                //Уведомления
                if(isset($error['updates'])) {
                    echo '<span style="color:red"><small>' . implode('<br />', $error['updates']) . '</small></span><br />';
                }
            echo '</div></div>';
            
            //Кнопка
            echo '<div class="modal-footer">';
            echo $form->submit(array('name' => 'update','value' => 'Обновить','class' => 'btn btn-success'));
            echo '<a class="btn btn-default" href="index.php?do=setting">Отмена</a>';
            echo '</div></div>';
            
            //Закрываем форму
            echo $form->close();
    }
}
