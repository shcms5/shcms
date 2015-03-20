<?php
/**
 * Класс Для работы с Подпапками
 * 
 * @package            Files/Folder/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace files\Folder;

class Folder {
    
    /**
     * Получаем данные и выводим все подпапки
     * 
     * @param Null
     */        
    public static function GetFolder($id){
    global $dbase,$profi,$tdate,$id_user,$glob_core,$app,$user_group;

        //Получаем Индификатор
        $filel = $dbase->query("SELECT * FROM `files_dir` WHERE `id` = ? ",[intval($id)]);
        $lfile = $filel->fetchArray();
    
        //Если неподходят данные назад
        if($lfile['id'] != $id) {
            header('Location: index.php');
        }
             
        //Профиль пользователя
        $nick = $profi->Views(['`group`'],$id_user);
    
        //Если Пользователь в группе админа и дана разрещение на добавление
        if($lfile['load'] == 2 OR $nick['group'] == 15) {
            //Авторизован ли пользователь
            if($id_user == true) {
                echo '<div class="btn-group right">';
            
                //Если Пользователь в группе 15
                if($nick['group'] == 15) {
                    echo '<a class="btn btn-success" href="'.$lfile['id'].'/newfolder">';
                    echo '<i class="glyphicon glyphicon-plus"></i>&nbsp;'.\Lang::__('Новая папка').'</a>';
                }
            
                echo '<a class="btn btn-info" href="'.$lfile['id'].'/upload">';
                echo '<i class="glyphicon glyphicon-download-alt"></i>&nbsp;'.\Lang::__('Загрузить').'</a>';
                echo '</div>';
            }    
        }
    
        //Навигация основная
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Загрузки</a>
            <a href="#" class="btn btn-default disabled">'.$lfile['name'].'</a>
        </div>';
    
        //Выводим Все разделы и Файлы
        echo '<div class="mainname">'.\Lang::__('Разделы').'</div>';
        echo '<div class="mainpost">';
        
        //Проверяем есть ли папки в разделе
        $dfiles = $dbase->query("SELECT * FROM `files_dir` WHERE `dir` = ?",[$lfile['id']]);
        
        if($dfiles->num_rows > 0) {
            //Если имеются	
            echo '<table class="itable">';
            foreach ($dfiles->fetchAll() as $dfile) {
                
            //Счетчик файлов
            $counts = $dbase->query("SELECT COUNT(*) as count FROM `files` WHERE `id_dir` = ? OR `idir` = ? ",[$dfile->id,$dfile->id]);
            $count = $counts->fetchArray();

                echo  '<tr class="">';
		echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/icons/dir3.png"></td>';
		echo '<td class="c_forum"><b><a style="font-size:15px;" href="'.\Shcms\Component\String\Url::filter($dfile->name).'-'.$dfile->id.'">'.$dfile->name.'</a></b>';
                    
                //Описание
		echo '<p style="margin-top:4px;" class="desc">';	
		echo $dfile->text;						
		echo '</p></td>';
                    
                    
                echo '<td class="c_stats"><ul>';
                    //Администрированное меню для управления
                    if(\Shcms\Component\Users\Group\Group::Groups($user_group) ==true AND $glob_core['filesadmin']  == 1) {
                        echo '<li style="font-size:14px;">';
                        //Редактирование папки
                        echo '<a href="/modules/files/editor/'.\Shcms\Component\String\Url::filter($dfile->name).'-'.$dfile->id.'"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;';
                        //Удаление папки
                        echo '<a href="/modules/files/delete/'.\Shcms\Component\String\Url::filter($dfile->name).'-'.$dfile->id.'"><i class="glyphicon glyphicon-remove"></i></a>';
                        echo '</li>';
                    }else {
                        echo '<li><b>'.\engine::number($count['count']).'</b> Файлов</li>';
                    }    
                        
                    
                echo '</ul></td>';
                echo '</tr>';
            }
            echo '</table>';
            
        }
        
        //Счетчик файлов
        $cfiles = $dbase->query("SELECT COUNT(*) as count FROM `files` WHERE `id_dir` = ?",[$lfile['id']]);
        $cfile = $cfiles->fetchArray();
        
        //Определяем навигацию и лимит постов
        $newlist = new \Navigation($cfile['count'],10, true); 
        
        //Если ничего нет то выводит
        if($dfiles->num_rows < 1 AND $cfile['count'] < 1) {
            echo \engine::warning('В разделе отсутствует данные');
            exit;
        }

        
        if($cfile['count'] > 0) {
            $files = $dbase->query("SELECT * FROM `files` WHERE `id_dir` = ? ORDER BY `id` DESC ". $newlist->limit()."",[$lfile['id']]);
        }
        echo '<table class="itable">';
        //Выводим все папки
        foreach ($files->fetchAll() as $file) {
            
            //Выводим счетчик комментариев
            $comments = $dbase->query("SELECT COUNT(*) FROM `down_comment` WHERE `id_file` = ?",[$file->id]);
            $comment = $comments->fetchArray();
                
                //Файл выводит
                echo  '<tr class="">';
		echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/down/'.\engine::format($file->files).'.png"></td>';
		echo '<td class="c_forum"><b><a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($file->name).'-'.$file->id.'">'.$file->name.'</a></b>';
                    
                //Описание
                echo '<p class="desc">'.$file->text1.'</p></td>';
                    
                echo '<td class="c_stats"><ul>';
                echo '<li><b>'.\engine::number($file->count).'</b> Просмотров</li>';
                echo '<li><b>'.\engine::number($comment['count']).'</b> Комментариев</li>';
                echo '</ul></td>';
                echo '</tr>';	
        }
        echo '</table>';
    
    
    echo '</div>';
    
    }
    
    /**
     * Получаем данные на добавление папки
     * 
     * @param Null
     */      
    public static function PostAdd($id) {
        global $dbase,$app;
        //Обработка кнопки
        $submit = \filter_input(\INPUT_POST,'submit');
        //Обработка названия
        $name = \filter_input(\INPUT_POST,'name',\FILTER_SANITIZE_STRING);
        //Обработка checkbox`a
        $new_file = \filter_input(\INPUT_POST,'new_file',\FILTER_SANITIZE_NUMBER_INT);
        //Обработка текста
        $text = \filter_input(\INPUT_POST,'text',\FILTER_SANITIZE_STRING);
   
        if(isset($submit)) {
	    //Проверка на нуммерование
	    $new_file = \intval($new_file);		
            //Проверка существует ли название
            if(empty($name)) {
	        $app->redirect('/');
	    }
            //Проверка существует ли описание
            if(empty($text)){
                $app->redirect('/');
            }else {		
            
                if($new_file != 2) {
		    $new_file = 1;
	        }					
                    
                //Добавлям данные в базу
                $dbase->insert('files_dir',[
                    'name'   => ''.$dbase->escape($name).'',
                    'text'   => ''.$dbase->escape($text).'',
                    'time'   => ''.time().'',
                    'dir'    => ''.intval($id).'',
                    'load'   => ''.intval($new_file).''
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
     * Данные на получение формы
     * 
     * @param $id = []
     */          
    public static function GetPost($id) {
        //Запуск AngularJS
        echo \engine::AngularJS();
    
        //Блок Названия
        echo '<div class="mainname">'.\Lang::__('Добавить Новую Категорию').'</div>';                
        echo '<div class="mainpost">';
        //Объявляем форму
        $form = new \Shcms\Component\Form\Form();
        //Открытие формы
        echo $form->open(['action' => '','class' => 'form-horizontal','name' => 'myForm','ng' => 'FormController']);
        
            //Название
            echo '<div class="form-group">';
            echo '<label class="col-sm-2 control-label">'.\Lang::__('Название').'</label>';
            echo '<div class="col-sm-10">';
            //Форма Названия
            echo $form->input(['name' => 'name','class' => 'form-control','ng' => 'ng-model="form.name" ng-minlength="5" ng-maxlength="26"','required' => '']);
                //Текст Ошибок            
                echo '<p><div class="text-danger right" ng-messages="myForm.name.$error">
                    <div ng-message="required">Введите название</div>
                    <div ng-message="minlength">Некорректное название</div>
                    <div ng-message="maxlength">Название меньше 26 символов</div>';
                echo '</div></p>';
        
            echo '</div></div>';
        
            //Описание Раздела
            echo '<div class="form-group">';
            echo '<label class="col-sm-2 control-label">'.\Lang::__('Описание').'</label>';
            echo '<div class="col-sm-10">';
            //Форма Описания
            echo $form->textbox(['name' => 'text','class' => 'form-control','ng' => 'ng-model="form.text" ng-minlength="5" ng-maxlength="100"','required' => '']);
                //Текст ошибок                    
                echo '<p><div class="text-danger right" ng-messages="myForm.text.$error">
                    <div ng-message="required">Введите описание</div>
                    <div ng-message="minlength">Некорректное описани</div>
                    <div ng-message="maxlength">Описание меньше 100 символов</div>';
                echo '</div></p>';
        
            echo '</div></div>';        
        
            //Дополнительные Данные
            echo '<div class="form-group">';
            echo '<label class="col-sm-2 control-label">'.\Lang::__('Дополнение').'</label>';
            echo '<div class="col-sm-10">';
        
            //Загрузка Файлов
            echo '<div class="col-sm-5"><label>';
            echo $form->checkbox([1 => ['name'=>'dir_open', 'value'=>'1','checked' => 'checked']]);
            echo \Lang::__('Папка открыта?');
            echo '</label>';
        
            //Добавление Файлов
            echo '</div><div class="col-sm-5"><label>';
            echo $form->checkbox([1 => ['name'=>'new_file', 'value'=>'2','checked' => 'checked']]);
            echo \Lang::__('Разрешение на добавление файлов');
            echo '</label>';        
            echo '</div></div></div>';
        
            echo '<div class="modal-footer">';
            echo $form->submit(['name' => 'submit','value' => 'Создать Папку','class' => 'btn btn-success','ng' => 'ng-disabled="myForm.$invalid"']);
            echo '<a class="btn btn-default" href="/modules/files">Отмена</a>';
            echo '</div>';
        
            //Закрытие Формы
            echo $form->close();
            echo '</div>';            
    }    
    
}
