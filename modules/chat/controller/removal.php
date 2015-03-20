<?php

/**
 * Класс для удаление сообщений из чата
 *
 * @author Шамсик
 */
class removal {

    public static function removall(){
        global $dbase,$id_user,$users;
        
        //Если пользователь админ то передаем управление
        if($users['group'] == 15) {
        //Обработка кнопки  
        $submit = filter_input(INPUT_POST,'submit');
            //Если кнопка TRUE то выполняем удаление
            if(isset($submit)){
                //Удаление данных
                $dbase->query("DELETE FROM `chat`");
                header('Location: index.php');
            }
            //Счетчик накоплений
        $rowc = $dbase->query("SELECT COUNT(*) as count FROM `chat`");
        $count = $rowc->fetchArray();        
                //Уведомление при удаление
                echo '<div class="mainname">'.Lang::__('Удаление постов').'</div>';
                echo '<div class="mainpost">';
                echo engine::error('У вас накопилось постов '.engine::number($count['count']).',&nbsp;
                вы действительно хотите удалить их?');
                echo '</div>';
                echo '<div class="submit">';
                    //Форма при удаление
                    $form = new form('?do=all');
                    $form->submit('Удалить','submit');
                    $form->text('или <a class="cancel" href="index.php">Отменить</a>');
                    $form->display();
                
                echo '</div>';
        }else {
            header('Location: index.php');
        }        
    }
    
    public static function deleteid($id) {
        global $dbase,$users;
            //Если пользователь является админом то дает управление
            if($users['group'] == 15) {
                //Удаляем пост
                 $dbase->delete('chat',array('id' => $id));
                header('Location: index.php');
            }else {
                header('Location: index.php');
            }
    }
}
