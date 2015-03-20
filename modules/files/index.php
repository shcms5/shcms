<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

$templates->template(\Lang::__('Загрузки'));

require_once 'Controller/Autoload.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//Класс для работы с пользователями
$profi = new \Shcms\Component\Users\User\User();
//Класс для работы со временем
$tdate = new Shcms\Component\Data\Bundle\DateType();
 
//Создаем массив ошибок
$error = [];


echo <<<HTML
<script type="text/javascript">
    $(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
HTML;
    
/**
 * Получаем страницу по умолчанию выводяться папки файлы и управление
 * 
 * @param function(NULL)
 * @param use $dbase - Объявляем базу, $app - Действие приложение
 */
$index = $app->get('/', function () use($dbase,$app) {
    //Выбираем данные для вывода
    echo \files\Directory\Directory::Navi(array('nav' => 'Nav','menu' => 'Menu','razdel' => 'Razdel'));
    //Выводим папки
    echo \files\Directory\Directory::GetDirectory();
});


/**
 * Получаем Поиск файлов
 * 
 * @param function(NULL)
 * @param use $dbase - Объявляем базу, $app - Действие приложение
 */
$search = $app->get('/search', function () use($dbase,$app) {
    //Выбираем данные для вывода
    echo \files\Directory\Directory::Navi(array('nav' => 'Search'));
    //Выводим папки
    echo \files\Directory\Directory::Search();
});


/**
 * Получаем Топ скачиваемых файлов
 * 
 * @param function(NULL)
 * @param use $dbase - Объявляем базу, $app - Действие приложение
 */
$search = $app->get('/topdownload', function () use($dbase,$app) {
    //Выбираем данные для вывода
    echo \files\Directory\Directory::Navi(array('nav' => 'Top'));
    //Выводим папки
    echo \files\Directory\Directory::TopDownload();
});


/**
 * Получаем Популярные Файлы и Авторы
 * 
 * @param function(NULL)
 * @param use $dbase - Объявляем базу, $app - Действие приложение
 */
$search = $app->get('/popular', function () use($dbase,$app,$profi) {
    //Выбираем данные для вывода
    echo \files\Directory\Directory::Navi(array('nav' => 'Popular'));
    //Выводим папки
    echo \files\Directory\Directory::Popular();
});


/**
 * Получаем страницу с подпапками и файлами
 * 
 * @param function($dirname - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $profi - Пользователь
 */     
$dirs = $app->get('/:dirname-:id', function ($dirname,$id) use ($dbase,$app,$profi,$tdate,$id_user) {
    //Выводим все подпапки по индификатору
    echo \files\Folder\Folder::GetFolder($id);
    
});


/**
 * Получаем данные на создание папки
 * 
 * @param function()
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $use_group - Группы
 */ 
$folderadd = $app->post('/foldernew', function() use ($dbase,$app,$user_group) {
    //Если вы Администратор то действие доступно
    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == 15) {
        //Обработка и добавление папки
        echo \files\Directory\Directory::PostAdd();
    }else {
        $app->redirect('/');
    }
});


/**
 * Получаем форму добавления новой папки
 * 
 * @param function()
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $use_group - Группы
 */ 
$foldernew = $app->get('/foldernew', function () use ($dbase,$app,$user_group) {
    //Если вы Администратор то действие доступно
    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == 15) { 
        //Форма Создания папки
        echo \files\Directory\Directory::GetPost();
    }else {
        $app->redirect('/');
    }    
});



/**
 * Получаем данные на создание подпапки
 * 
 * @param function()
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $use_group - Группы
 */ 
$addfolder = $app->post('/:id/newfolder', function($id) use ($dbase,$app,$user_group) {
    //Если вы Администратор то действие доступно
    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == 15) {
        //Обработка и добавление подпапки
        echo \files\Folder\Folder::PostAdd($id);
    }else {
        $app->redirect('/');
    }
});



/**
 * Получаем форму добавления новой подпапки
 * 
 * @param function($id) - Индификатор
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $use_group - Группы
 */ 
$newfolder = $app->get('/:id/newfolder', function ($id) use ($dbase,$app,$user_group) {
    //Если вы Администратор то действие доступно
    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == 15) {    
        //Форма добавления подпапки
        echo \files\Folder\Folder::GetPost($id);
    }else {
        $app->redirect('/');
    }    
});



/**
 * Получаем данные на редактирование папки
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $use_group - Группы
 */ 
$editfolder = $app->post('/editor/:name-:id', function ($name,$id) use ($dbase,$app,$user_group) {
    //Если вы Администратор то действие доступно
    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == 15) {  
        //Обработка и Редактируем папку
        echo \files\Folder\Editor::PostEdit($id);
    }else {
        $app->redirect('/');
    }    
});



/**
 * Получаем форму редактирования папок
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $use_group - Группы
 */ 
$editfolder = $app->get('/editor/:name-:id', function ($name,$id) use ($dbase,$app,$user_group) {
    //Если вы Администратор то действие доступно
    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == 15) {   
        //Форма редактирования папки
        echo \files\Folder\Editor::GetPost($id);
    }else {
        $app->redirect('/');
    }    
});


/**
 * Получаем данные на удаление папок с файлами
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $use_group - Группы
 */ 
$deletefolder = $app->post('/delete/:name-:id', function ($name,$id) use ($dbase,$app,$user_group) {
    //Если вы Администратор то действие доступно
    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == 15) {   
        //Данные на удаление папки с файлами
        echo \files\Folder\Editor::DeletePost($id);
    }else {
        $app->redirect('/');
    }    
});



/**
 * Получаем предупреждение на удаление
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $use_group - Группы
 */ 
$deletefolder = $app->get('/delete/:name-:id', function ($name,$id) use ($dbase,$app,$user_group) {
    //Если вы Администратор то действие доступно
    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == 15) {    
        //Даем возможность удаление и отмены выбора
        echo \files\Folder\Editor::DeleteGet($id);
    }else {
        $app->redirect('/');
    }    
});


/**
 * Получаем данные на редактирование файла
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение
 */ 
$editpost = $app->post('/views/editor/:name-:id', function ($name,$id) use ($dbase,$app,$id_user) {
    //Вытаскиваем из базы данные по Индификатору
    $views = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$id]);
    $view = $views->fetchArray();
    
    //Проверяем Является ли автризованный автором файла
    if($id_user == $view['id_user']) {
        echo \files\Folder\Editor::ViewEditorPost($id);
    }
});


/**
 * Получаем форму Редактирование файла
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $user_group - Группа
 */ 
$editfile = $app->get('/views/editor/:name-:id', function ($name,$id) use ($dbase,$app,$user_group,$id_user) {
    //Вытаскиваем из базы данные по Индификатору
    $views = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$id]);
    $view = $views->fetchArray();
    
    //Проверяем Является ли автризованный автором файла
    if($id_user == $view['id_user']) {
        echo \files\Folder\Editor::ViewEditorGet($id);
    }
});


/**
 * Получаем данные на Скачивание
 * 
 * @param function($id - Индификатор)
 * @param use $dbase - Объявляем базу, $id_user Пользователь
 */ 
$download = $app->get('/:id/download', function($id) use ($dbase,$id_user) {
    
    //Данные о файле
    $prosm = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[intval($id)]);
    $pro = $prosm->fetchArray();
    
    //Проверяем Является ли автризованный автором файла
    if($id_user == $pro['id_user']) {
        echo \files\Folder\ViewsFile::Download($id);
    }
});


/**
 * Получаем данные о файле
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$views = $app->get('/views/:name-:id',  function($name,$id) use ($dbase,$app,$id_user,$tdate,$profi) {
    
    //Данные о файле
    echo \files\Folder\ViewsFile::ViewFile($id);    
});


/**
 * Полное применение настроек Скриншотов
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$viewsp = $app->post('/views/screen/:name-:id',  function($name,$id) use ($dbase,$app,$id_user,$tdate,$profi) {
    
    //Данные о файле
    $prosm = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[intval($id)]);
    $pro = $prosm->fetchArray();
    
    //Проверяем Является ли автризованный автором файла
    if($id_user == $pro['id_user']) {
        echo \files\Folder\ViewsFile::PostScreen($id);
    }
});



/**
 * Полное управление над скриншотами файла
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$viewsc = $app->get('/views/screen/:name-:id',  function($name,$id) use ($dbase,$app,$id_user) {
    
    //Данные о файле
    $prosm = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[intval($id)]);
    $pro = $prosm->fetchArray();
    
    //Проверяем Является ли автризованный автором файла
    if($id_user == $pro['id_user']) {
        echo \files\Folder\ViewsFile::GetScreen($id);
    }
  
});


/**
 * Полное управление над скриншотами файла
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$deletefile = $app->get('/views/delete/:name-:id', function($name,$id) use($dbase,$app,$id_user) {
    
    //Данные о файле
    $prosm = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[intval($id)]);
    $pro = $prosm->fetchArray();
    
    //Проверяем Является ли автризованный автором файла
    if($id_user == $pro['id_user']) {
        echo \files\Folder\ViewsFile::GetDeleteFile($id);
    }
  
});


/**
 * Добавление комментариев к файлам
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$addcomment = $app->post('/comment/:name-:id', function($name,$id) use ($dbase,$app,$id_user,$users) {
   echo \files\Directory\Comment::PostForm($id);
});


/**
 * Полный список комментариев + Добавление
 * 
 * @param function($name - Имя папки, $id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$comment = $app->get('/comment/:name-:id', function($name,$id) use ($dbase,$app,$id_user,$tdate,$profi) {
    echo \files\Directory\Comment::getTitle(['nav' => 'Nav'],$id);
    echo \files\Directory\Comment::GetForm($id);
});



/**
 * Загрузка файла на сервер
 * 
 * @param function($id - Индификатор)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$addupload = $app->post('/:id/upload', function($id) use ($dbase,$app,$id_user) {
    if($id_user == false) {
        $app->redirect('/');
        exit;
    }
    //Обработка кнопки
    $submit = \filter_input(\INPUT_POST,'submit');
    //Обработка названия
    $name = \filter_input(\INPUT_POST,'name',\FILTER_SANITIZE_STRING);
  
    $DirFile = \H .'upload/download/files/';
    
    $viewd = $dbase->query("SELECT * FROM `files_dir` WHERE `id` = ? ",[$id]);
    $dview = $viewd->fetchArray();
    
    if(isset($submit)) {
        if(empty($name)) {
            $app->redirect('/modules/files/');
        }
        if(empty($_FILES['file']['name'])) {
            $app->redirect('/modules/files/');
        }
        $type = \engine::format($_FILES['file']['name']);
        $ntype = \engine::format_r($_FILES['file']['name']);
        $filename = \Shcms\Component\String\Url::filter($ntype).'.'.$type;
        $filenames = \Shcms\Component\String\Url::filter($ntype);
        
        //Объявляем Класс Добавление файла
        $storage = new \Shcms\Resources\Upload\Storage\FileSystem(\H.'/upload/download/files');
        $file = new \Shcms\Resources\Upload\File('file', $storage);
        
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

        //Добавление файлов в базу           
        $dbase->insert('files',[         
            'id_dir'   =>   ''.$id.'',            
            'idir'     =>   ''.$dview['dir'].'',
            'name'     =>   ''.$dbase->escape($name).'',
            'files'    =>   ''.$dbase->escape($data['name']).'',
            'time'     =>   ''.time().'',
            'id_user'  =>   ''.$id_user.'',
            'filesize' =>   ''.$data['size'].'',
        ]);

        //Попытка загрузки файла
        try {
            //Успешно
           $file->upload();
           $app->redirect('/modules/files/'.\Shcms\Component\String\Url::filter($dview['name']).'-'.$dview['id']);
        } catch (\Exception $e) {
           // Ошибки!
           $errors = $file->getErrors();
        }
			
    }
});

/**
 * Список избранных файлов
 * 
 * @param function()
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$faverites = $app->get('/favorites',function() use ($dbase,$app,$id_user){

    //Получение списка файлов
    $fav = $dbase->query("SELECT DISTINCT(`id_files`) FROM `files_favorites`");
    //Заголовок
    echo '<div class="mainname">'.\Lang::__('Избранные файлы').'</div>';
    echo '<div class="mainpost">';
    //Проверка доступны ли файлы
    if($fav->num_rows > 0) {
        
    echo '<table class="itable">';
        //Выводим весь список файлов
        foreach ($fav->fetchAll() as $favs) {
            //Получаем информацию о файле
            $file = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$favs->id_files]);
            $files = $file->fetchArray();
                
            //Количество лайков
            $favd = $dbase->query("SELECT COUNT(*) as count FROM `files_favorites` WHERE `id_files` = ?",[$files['id']]);
            $dfav = $favd->fetchArray();
            //Если файл больше 5
            if($dfav['count'] > 5) {
                echo  '<tr class="">';
                //Иконка
                echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/down/'.\engine::format($files['files']).'.png"></td>';
                //Название и Путь
                echo '<td class="c_forum"><b><a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id'].'">'.$files['name'].'</a></b></td>';
                echo '</tr>';            
            }
        }    
    
            echo '</table>';
    }else {
        echo \engine::warning(\Lang::__('В избранном нет файлов'));
    }        
            
    echo '</div>';
    
});


/**
 * Добавление файла в избранное
 * 
 * @param function($id - Индификатор,$name = ИМЯ)
 * @param use $dbase - Объявляем базу, $app - Действие приложение, $id_user Пользователь
 */ 
$favorite = $app->get('/favorite/:name-:id',function($name,$id) use ($dbase,$app,$id_user) {
        
    //Получаем из базы данные о файле
    $prosm = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[intval($id)]);
    $pro = $prosm->fetchArray();
        
    //Получаем счетчик лайков
    $favo = $dbase->query("SELECT COUNT(*) as count FROM `files_favorites` WHERE `id_user` = ? AND `id_files` = ?",[$id_user,intval($id)]);
    $fav = $favo->fetchArray();
    
    //Если нет пользователя и есть лайк от пользователя то FALSE
    if($id_user == FALSE OR $fav['count'] == TRUE) {
        $app->redirect('/modules/files/views/'.\Shcms\Component\String\Url::filter($pro['name']).'-'.$pro['id'].'');
        exit;
    }
    
    //Загружаем в базу
    $dbase->insert('files_favorites',[
        'id_user'   => ''.$id_user.'',
        'id_files'  => ''.$id.'',
        'time'      => ''.time().''
        
    ]);
    
    //Переадресация
    $app->redirect('/modules/files/views/'.\Shcms\Component\String\Url::filter($pro['name']).'-'.$pro['id'].'');
});



$newupload = $app->get('/:id/upload', function ($id) use ($dbase,$app,$id_user,$groups) {
    
    $views = $dbase->query("SELECT * FROM `files_dir` WHERE `id` = ?",[intval($id)]);
    $view = $views->fetchArray();
    
  
    //Запуск AngularJS
    echo \engine::AngularJS();
    
    if($id_user == false) {
        $app->redirect('/');
        exit;
    }
    echo '<div class="mainname">'.Lang::__('Добавление Нового Файла').'</div>';                
    echo '<div class="mainpost">';
        $form = new \Shcms\Component\Form\Form();
        echo $form->open(['action' => '','class' => 'form-horizontal','name' => 'myForm','ng' => 'FormController','files' => 'files']);
        
        //Название
        echo '<div class="form-group">';
        echo '<label class="col-sm-2 control-label">'.\Lang::__('Название').'</label>';
        echo '<div class="col-sm-10">';
        echo $form->input(['name' => 'name','class' => 'form-control','ng' => 'ng-model="form.name" ng-minlength="5" ng-maxlength="26"','required' => '']);
                            
        echo '<p><div class="text-danger right" ng-messages="myForm.name.$error">
            <div ng-message="required">Введите название</div>
            <div ng-message="minlength">Некорректное название</div>
            <div ng-message="maxlength">Название меньше 26 символов</div>';
        echo '</div></p>';
        
        echo '</div></div>';
        
        echo '<div class="form-group">';
        echo '<label class="col-sm-2 control-label">'.\Lang::__('Файл').'</label>';
        echo '<div class="col-sm-10">';
        echo $form->input(['type' => 'file','name' => 'file','class' => 'form-control']);
        echo '</div></div>';
        
        
        echo '<div class="modal-footer">';
        echo $form->submit(['name' => 'submit','value' => 'Загрузить','class' => 'btn btn-success','ng' => 'ng-disabled="myForm.$invalid"']);
        echo '<a class="btn btn-default" href="/modules/files">Отмена</a>';
        echo '</div>';
        
        //Закрытие Формы
        echo $form->close();

                
    echo '</div>';                

});

$app->run();
        