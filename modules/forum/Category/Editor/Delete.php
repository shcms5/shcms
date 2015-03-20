<?php
/**
 * Класс Для работы с Удалением Категорий
 * 
 * @package            Forum/Category/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace Forum\Category\Editor;

use Shcms\Component\Form\Form;

class Delete {

    /**
     * Удаление Категории и его Содержимое
     * 
     * @param Null
     */
    public static function Delete() {
        global $dbase,$id;
        
        //Кнопка Подтверждение
        $yes = filter_input(INPUT_POST,'yes',FILTER_SANITIZE_STRING);
        //Кнопка Отмены
        $no = filter_input(INPUT_POST,'no',FILTER_SANITIZE_STRING);
        
        //Если нажата Подтвержение
        if($yes == true) {
            $dbase->delete('forum_category',array('id' => $id));
            $dbase->delete('forum_subsection',array('id_cat' => $id));
            $dbase->delete('forum_topics',array('id_cat' => $id));
            $dbase->delete('forum_post',array('id_cat' => $id));
            
            header('Location: index.php?do=setting');
        }elseif($no == true) {
            header('Location: index.php?do=setting');
            exit;
        }
        
            //Текст при удаление категорий
            echo '<div class="mainname">Подтверждение</div>';
            echo '<div class="mainpost">';
            echo \engine::warning('Вы действительно хотите удалить выбранную категорию? Данное действие невозможно будет отменить.');
            
            //Форма удаление
            $form = new Form;
            echo $form->open(array('action' => 'index.php?do=setting&act=delete&id='.$id.''));
            
            //Кнопка Удаление и Отмены
            echo '<div class="modal-footer">';
            echo $form->submit(array('name' => 'yes','value' => 'Удалить','class' => 'btn btn-info'));
            echo $form->submit(array('name' => 'no','value' => 'Отменить','class' => 'btn btn-danger'));
            echo '</div>';
            
            echo $form->close();
            echo '</div>';
    }
}
