<?php
/**
 * Класс Для работы с Управлениями Папок
 * 
 * @package            Files/Folder/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace files\Folder;

class Editor {
    
    
    /**
     * Получаем данные на редактирование папки
     * 
     * @param Null
     */    
    public static function PostEdit($id) {
        global $dbase,$app;
        //Обработка кнопки
        $submit = \filter_input(\INPUT_POST,'submit');
        //Обработка названия
        $name = \filter_input(\INPUT_POST,'name',\FILTER_SANITIZE_STRING);
        //Обработка текста
        $text = \filter_input(\INPUT_POST,'text',\FILTER_SANITIZE_STRING);
   
        if(isset($submit)) {	
            //Проверка существует ли название
            if(empty($name)) {
	        $app->redirect('/');
	    }
            //Проверка существует ли описание
            if(empty($text)){
                $app->redirect('/');
            }else {						
                    
                //Добавлям данные в базу
                $dbase->update('files_dir',[
                    'name'   => ''.$dbase->escape($name).'',
                    'text'   => ''.$dbase->escape($text).'',
                    'time'   => ''.  \time().'',
                ],[
                    'id'    => ''.intval($id).''
                ]);
                //Получаем Данные по Разделу
                $viewi = $dbase->query("SELECT * FROM `files_dir` WHERE `id` = ?",[$id]);
                $view = $viewi->fetchArray();
            
                //Переадресация
                $app->redirect('/modules/files/'.\Shcms\Component\String\Url::filter($view['name']).'-'.$view['id']);
            }	
        }
    }
    
    /**
     * Получаем форму редактирования
     * 
     * @param Null
     */
    public static function GetPost($id) {
         global $dbase;
        
        $views = $dbase->query("SELECT * FROM `files_dir` WHERE `id` = ? ",[$id]);
        $view = $views->fetchArray();

        //Блок Названия
        echo '<div class="mainname">'.\Lang::__('Редактировать категорию').'</div>';                
        echo '<div class="mainpost">';
        //Объявляем форму
        $form = new \Shcms\Component\Form\Form();
        //Открытие формы
        echo $form->open(['action' => '','class' => 'form-horizontal','name' => 'myForm']);
        
            //Название
            echo '<div class="form-group">';
            echo '<label class="col-sm-2 control-label">'.\Lang::__('Название').'</label>';
            echo '<div class="col-sm-10">';
            //Форма Названия
            echo $form->input(['name' => 'name','value' => $view['name'],'class' => 'form-control']);
        
            echo '</div></div>';
        
            //Описание Раздела
            echo '<div class="form-group">';
            echo '<label class="col-sm-2 control-label">'.\Lang::__('Описание').'</label>';
            echo '<div class="col-sm-10">';
            //Форма Описания
            echo $form->textbox(['name' => 'text','class' => 'form-control','value' => $view['text']]);
        
            echo '</div></div>';        
        
            //Кнопка
            echo '<div class="modal-footer">';
            echo $form->submit(['name' => 'submit','value' => 'Изменить Папку','class' => 'btn btn-success']);
            echo '<a class="btn btn-default" href="/modules/files">Отмена</a>';
            echo '</div>';
        
            //Закрытие Формы
            echo $form->close();
            echo '</div>';            
    }    
    
    /**
     * Получаем удаление папки с данными
     * 
     * @param Null
     */    
    public static function DeletePost($id) {
        global $dbase,$app;
        
        //Обработка кнопки удалить
        $submit = filter_input(INPUT_POST,'submit',FILTER_DEFAULT);
        //Обработка кнопки отменить
        $cancel = filter_input(INPUT_POST,'cancel',FILTER_DEFAULT);
        
        //Если нажата кнопка
        if($submit == true) {
            //Удаляем папку
            $delete = $dbase->delete('files_dir',[
                'id'   => $id
            ]);
            //Удаление оставщиеся папок
            $deleteall = $dbase->delete('files_dir',[
                'dir'   => $id
            ]);        
            //Удаление файлов из папки
            $deletefiles = $dbase->delete('files',[
                'idir'   => $id
            ]);            
            //Переадресация
            $app->redirect('/modules/files/');
        }elseif($cancel == true) {
            $app->redirect('/modules/files/');
        }
    }
 
    /**
     * Получаем форму на удаление папок с данными
     * 
     * @param Null
     */   
    public static function DeleteGet($id) {
        
        //Предупреждение
        echo \engine::warning(\Lang::__('Вы действительно хотите удалить папку'));
        //Объявляем форму
        $form = new \Shcms\Component\Form\Form();
        echo '<center>';
        //Открываем форму
        echo $form->open(['action' => '']);
        //Кнопка удаление
        echo $form->submit(['name' => 'submit','class' => 'btn btn-danger','value' => 'Удалить']);
        //Кнопка отмены
        echo $form->submit(['name' => 'cancel','class' => 'btn btn-default','value' => 'Отменить']);
        echo '</center>';
        //Закрытие формы
        echo $form->close();
        
    }   
    
    /**
     * Получаем данные на Успешное редактирование
     * 
     * @param $id
     */  
    public static function ViewEditorPost($id) {
        global $dbase,$app;
      
        //Обработка кнопки подтверждение
        $submit = \filter_input(INPUT_POST, 'submit', FILTER_DEFAULT);
        //Обработка кнопки отмены
        $cancel = \filter_input(INPUT_POST, 'cancel', FILTER_DEFAULT);
        //Обработка названия
        $name = \filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        //Обработка Краткого описания
        $text1 = \filter_input(INPUT_POST, 'text1', FILTER_SANITIZE_STRING);
        //Обработка полного описания
        $text2 = \filter_input(INPUT_POST, 'text2', FILTER_SANITIZE_STRING);
        //Обработка Описания(Description)
        $desc = \filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING); 
        //Обработка ключевых слов
        $key = \filter_input(INPUT_POST, 'key', FILTER_SANITIZE_STRING);    
    
    
        //Если нажата кнопка Подтверждение
        if($submit == TRUE) {
            //Если название и кр.описание не пусты 
            if(!empty($name) and !empty($text1)) {
                //Обновляем данные в базе
                $dbase->update('files',[
                    'name'   =>  ''.$dbase->escape($name).'',
                    'text1'   =>  ''.$dbase->escape($text1).'',
                    'text2'  =>  ''.$dbase->escape($text2).'',
                    'desc'   =>  ''.$dbase->escape($desc).'',
                    'key'    =>  ''.$dbase->escape($key).''
                ],[
                    'id'     =>   ''.intval($id).''
                ]);
                //Переадресация
                $app->redirect('/modules/files/views/'.\Shcms\Component\String\Url::filter($name).'-'.$id.'');
            }
        }elseif($cancel == TRUE) {
            $app->redirect('/modules/files/views/'.\Shcms\Component\String\Url::filter($name).'-'.$id.'');
        } 
    }
    
    
    /**
     * Получаем Форму Редактирование Файла
     * 
     * @param $id
     */      
    public static function ViewEditorGet($id) {
        global $view,$dbase;
            
        //Выводим данные из базы по Индификатору
        $views = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$id]);
        $view = $views->fetchArray();
        
        //Объявляем Форму
        $form = new \Shcms\Component\Form\Form();
        
        //Открываем Форму
        echo $form->open(['class' => 'form-horizontal','action' => '']);
        
        //Название Файла
        echo '<br/><div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Название').'</b></label>';
        echo '<div class="col-sm-10">';
        echo $form->input(['name' => 'name','class' => 'form-control','value' => $view['name']]);
        echo '</div></div>';
        
        //Краткое описание
        echo '<br/><div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Кр. описание').'</b></label>';
        echo '<div class="col-sm-10">';
        echo $form->textbox(['name' => 'text1','class' => 'form-control','id' => 'editor2','value' => $view['text1']]);
        echo '</div></div>';
        
        //Полное описание
        echo '<br/><div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Описание').'</b></label>';
        echo '<div class="col-sm-10">';
        echo $form->textbox(['name' => 'text2','class' => 'form-control','id' => 'editor','value' => $view['text2']]);
        echo '</div></div>';
        
        //Описание для Ботов
        echo '<br/><div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Description').'</b></label>';
        echo '<div class="col-sm-10">';
        echo $form->textbox(['name' => 'desc','class' => 'form-control','value' => $view['desc']]);
        echo '</div></div>';
        
        //Ключевые слова для Ботов
        echo '<br/><div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Ключевые слова').'</b></label>';
        echo '<div class="col-sm-10">';
        echo $form->textbox(['name' => 'key','class' => 'form-control','value' => $view['key']]);
        echo '</div></div>';  
        
        //Кнопки Сохранение и Отмены
        echo '<div class="modal-footer">';
        echo $form->submit(['name' => 'submit','value' => 'Сохранить','class' => 'btn btn-success']);
        echo $form->submit(['name' => 'cancel','value' => 'Отмена','class' => 'btn btn-danger']);
        echo '</div>';
    
    }
    
    
}
