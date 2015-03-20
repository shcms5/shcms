<?php
/**
 * Класс Для работы с Удаление Тем
 * 
 * @package            Forum/Category/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace Forum\Topics\Editor;

use Shcms\Component\Form\Form;

class Delete {
    
    /**
     * Получение заголовка стараницы
     * 
     * @param $id Индификатор раздела
     */   
    public static function Dtitle($id) {
        global $dbase,$templates;
        
        //Получаем Заголовок Раздела
        $head = $dbase->query("SELECT * FROM `forum_topics` WHERE `id` = ? ",array($id));
        $title = $head->fetch();
        
        //Выводим
        return $templates->template('Отказ от темы | '.$title->name);
    }        
    /**
     * Удаление Разделов и его Содержимое
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
            $dbase->delete('forum_topics',array('id' => $id));
            $dbase->delete('forum_post',array('id_top' => $id));
            
            header('Location: index.php');
        }elseif($no == true) {
            header('Location: index.php?do=topics&id='.$id.'');
            exit;
        }
        
            //Текст при удаление разделов
            echo '<div class="mainname">Подтверждение</div>';
            echo '<div class="mainpost">';
            echo \engine::warning('Вы действительно хотите удалить выбранную тему? Данное действие невозможно будет отменить.');
            
            //Форма удаление
            $form = new Form;
            echo $form->open(array('action' => 'index.php?do=topics&id='.$id.'&act=delete'));
            
            //Кнопка Удаление и Отмены
            echo '<div class="modal-footer">';
            echo $form->submit(array('name' => 'yes','value' => 'Удалить','class' => 'btn btn-info'));
            echo $form->submit(array('name' => 'no','value' => 'Отменить','class' => 'btn btn-danger'));
            echo '</div>';
            echo $form->close();
            echo '</div>';
    }
}
