<?php
/**
 * Класс Для работы с Загрузками Архивов
 * 
 * @package            Forum/Topics/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace Forum\Topics\Editor;

use Shcms\Component\Form\Form;

class Files {
    
    /**
     * Получение заголовка стараницы
     * 
     * @param $id Индификатор раздела
     */   
    public static function Title($id) {
        global $dbase,$templates;
        
        //Получаем Заголовок Раздела
        $head = $dbase->query("SELECT * FROM `forum_topics` WHERE `id` = ? ",array($id));
        $title = $head->fetch();
        
        //Выводим
        return $templates->template('Добавление Файла | '.$title->name);
    }   
    
    
    public static function AddFiles() {
        global $id,$dbase;
        
        if (!isset($id) || !is_numeric($id)) {
            \header('Location: index.php');
            exit;
        }
        $posts = $dbase->query("SELECT * FROM `forum_post` WHERE `id_top` = ? ORDER BY `id` DESC",array($id));
        $post = $posts->fetchArray();
        
        $files = $dbase->query("SELECT * FROM `forum_file` WHERE `id_them` = ? AND `id_post` = ?",array($id,$post['id']));
        

            echo '<div class="mainname">'.\Lang::__('Прикрепленные файлы:').'</div>';
            echo '<ul class="mainpost">';
            
                while($file = $files->fetchArray()) {
                    echo '<li class="row3">';
                    echo '<img src="../../engine/template/down/'.$file['type'].'.png">';
                    echo $file['text'].'<span class="time">'.\engine::filesize($file['size']).'</span>';
                    echo '</li>';
                }
            
            echo '</ul>';
 
        
        echo '<div class="mainname">'.\Lang::__('Прикрепить файл').'</div>';
        echo '<div class="mainpost">';
        
            
            $submit = filter_input(INPUT_POST,'submit',FILTER_DEFAULT);
            
               if($submit == TRUE) {
        //Объявляем Класс Добавление файла
        $storage = new \Shcms\Resources\Upload\Storage\FileSystem(\H.'upload/forum/files');
        $file = new \Shcms\Resources\Upload\File('filename', $storage);
        
        $new_filename = uniqid();
        $file->setName($new_filename);

        $file->addValidations(array(

            //Размер файлов  
            new \Shcms\Resources\Upload\Validation\Size('100G')
        ));

        // Доступ к данным о файле, который был загружен
        $data = [
            'name'       => $file->getNameWithExtension(),
            'extension'  => $file->getExtension(),
            'size'       => $file->getSize()
        ];

        $file_text = "<a class='btn btn-success' href='index.php?do=topics&id=".$id."&act=download&file=".$data['name']."'>".$data['name']."</a>";
        

        $dfiles = $dbase->query("SELECT * FROM `forum_post` ORDER BY `id` DESC");
        $dfile = $dfiles->fetchArray();
        
                                //Заливаем данные файла в базу
                                $dbase->insert('forum_file',array(
                                    'id_post' => ''.$dfile['id'].'',
                                    'id_them' => ''.$id.'',
                                    'text'    => ''.$file_text.'',
                                    'type'    => ''.\engine::format($data['name']).'',
                                    'size'    => ''.$data['size'].'',
                                    'time'    => ''.time().'',
                                    'name'    => ''.$dbase->escape($data['name']).''
                                ));
                                
        //Попытка загрузки файла
        try {
            //Успешно
           $file->upload();
            header('Location: index.php?do=topics&id='.$id.'&act=files');
        } catch (\Exception $e) {
           // Ошибки!
           $errors = $file->getErrors();
        }        
                   }else {
            		// если мы здесь, загрузка файлов на сервер не удалось по ряду причин
            		echo  \engine::error(\Lang::__('<b>Файл не загружен на сервер</b>'));
                    }
            //Форма Загрузка Файлов
            $form = new Form;
            //Открытие Формы
            echo $form->open(array('action' => 'index.php?do=topics&id='.$id.'&act=files','class' => 'form-horizontal','files' => ''));
            echo '<br/><div class="form-group">';
            echo '<label for="inputEmail3" class="col-sm-2 control-label">Выбираем Файл:</label>';
            echo '<div class="col-sm-10">';
            //Уведомление для формы
            echo $form->input(array('name' => 'filename','type' => 'file','class' => 'form-control'));
            echo '</div></div>';
        
            //Кнопка
            echo '<div class="modal-footer">';
            echo $form->submit(array('name' => 'submit','value' => 'Загрузить','class' => 'btn btn-success'));
            echo '<a class="btn btn-default" href="index.php?do=topics&id='.$id.'">Отмена</a>';
            echo '</div></div>';
            
            //Закрываем Форму
            echo $form->close();
            
        
        echo '</div>';
        
    }
}
