<?php

/**
 * Класс для удаление сообщений из чата
 *
 * @author Шамсик
 */
class removal {

    public static function removall(){
        global $db,$id_user,$users;
        
        //Если пользователь админ то передаем управление
        if($users['group'] == 15) {
        //Обработка кнопки  
        $submit = filter_input(INPUT_POST,'submit');
            //Если кнопка TRUE то выполняем удаление
            if(isset($submit)){
                //Удаление данных
                $db->query('DELETE FROM `chat`');
                $db->query('TRUNCATE TABLE `chat`');
                header('Location: index.php');
            }
            //Счетчик накоплений
            $count = $db->get_array($db->query('SELECT COUNT(*) FROM `chat`'));
                //Уведомление при удаление
                echo '<div class="mainname">'.Lang::__('Удаление постов').'</div>';
                echo '<div class="mainpost">';
                echo engine::error('У вас накопилось постов '.engine::number($count[0]).',&nbsp;
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
        global $db,$users;
            //Если пользователь является админом то дает управление
            if($users['group'] == 15) {
                //Удаляем пост
                $db->query('DELETE FROM `chat` WHERE `id` = '.$id.'');
                header('Location: index.php');
            }else {
                header('Location: index.php');
            }
    }
}
