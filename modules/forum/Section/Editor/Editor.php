<?php
/**
 * Класс Для работы с Редактированием Разделов
 * 
 * @package            Forum/Section/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace forum\Section\Editor;

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
        $category = $dbase->query("SELECT * FROM `forum_subsection` WHERE `id` = ?",array($id));
        
            echo '<div class="mainname">Изменение Раздела</div>';
            echo '<div class="mainpost">';
            $edit = $category->fetch();
            //Обработка Кнопки
            $update = filter_input(INPUT_POST,'update',FILTER_DEFAULT);
            //Обработка Названия
            $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
            //Обработка Описания
            $desc = filter_input(INPUT_POST,'desc',FILTER_SANITIZE_STRING);
            
            //Проверяем Нажата ли кнопка
            if(isset($update)) {
                //Если не введена название раздела
                if(empty($name)) {
                    $error['updates'][] = 'Введите название раздела!';
                }
                //Если введена описание
                if(empty($desc)) {
                    $error['desc'][] = 'Введите описание раздела!';
                }elseif(empty($error)) {
                    //Если все выполнена правильно то обновляет название в базе
                    $dbase->update('forum_subsection',array(
                        'name' => ''.$dbase->escape($name).'',
                        'text' => ''.$dbase->escape($desc).''
                    ),'id = '.$id.'');
                    //Автопереадресация
                    \header('Location: index.php?do=rsetting');
                }
            }
            
            //Объявление формы
            $form = new Form;
            //Открытие Формы
            echo $form->open(array('action' => 'index.php?do=rsetting&act=editor&id='.$id.'?','class' => 'form-horizontal'));
            echo '<br/><div class="form-group">';
            echo '<label for="inputEmail3" class="col-sm-2 control-label">Название:</label>';
            echo '<div class="col-sm-10">';
            //Уведомление для формы
            echo $form->input(array('name' => 'name','value' => $edit->name,'class' => 'form-control'));
                if(isset($error['updates'])) {
                    echo '<span style="color:red"><small>' . implode('<br />', $error['updates']) . '</small></span><br />';
                }
            echo '</div></div>';
    
            //Название
            echo '<br/><div class="form-group">';
            echo '<label for="inputEmail3" class="col-sm-2 control-label">Название:</label>';
            echo '<div class="col-sm-10">';
            echo $form->textbox(array('name' => 'desc','value' => $edit->text,'class' => 'form-control'));
               //Уведомление для формы
                if(isset($error['desc'])) {
                    echo '<span style="color:red"><small>' . implode('<br />', $error['desc']) . '</small></span><br />';
                }
            echo '</div></div>';            
            
            //Кнопка
            echo '<div class="modal-footer">';
            echo $form->submit(array('name' => 'update','value' => 'Обновить','class' => 'btn btn-success'));
            echo '<a class="btn btn-default" href="index.php?do=setting">Отмена</a>';
            echo '</div></div>';
            
            //Закрываем Форму
            echo $form->close();
    }
}
