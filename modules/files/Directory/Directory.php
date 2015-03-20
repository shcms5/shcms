<?php
/**
 * Класс Для работы с Основными папками
 * 
 * @package            Files/Directory/
 * @author             Shamsik
 * @link               http://shcms.ru
 */

namespace files\Directory;

class Directory {
    
    /**
     * Получение Навигации страницы и Определенный данные
     * 
     * @param $param [nav , menu , razdel]
     */           
    public static function Navi($param = []) {
        global $user_group,$dbase,$do,$glob_core,$app;

        //Выводим навигацию страницы
        if($param['nav'] == 'Nav') {
            //Навигация Вверхняя
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a disabled href="index.php" class="btn btn-default">Загрузки</a>';
            echo '</span>';
        }elseif($param['nav'] == 'Search') {
            //Навигация Вверхняя
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="/modules/files/" class="btn btn-default">Загрузки</a>
                <a disabled href="index.php" class="btn btn-default">Поиск файлов</a>';
            echo '</span>';
        }elseif($param['nav'] == 'Top') {
            //Навигация Вверхняя
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="/modules/files/" class="btn btn-default">Загрузки</a>
                <a disabled href="index.php" class="btn btn-default">Топ скачиваемых</a>';
            echo '</span>';     
        }elseif($param['nav'] == 'Popular') {
            //Навигация Вверхняя
            echo '<span class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="/modules/files/" class="btn btn-default">Загрузки</a>
                <a disabled href="index.php" class="btn btn-default">Популярное</a>';
            echo '</span>';    
        }
        
        //Администрированное меню для управления
        if($param['menu'] == 'Menu') {
            if(\Shcms\Component\Users\Group\Group::Groups($user_group)) {

                switch ($do):
                    //Если отключен то включаем
                    case 'enter':
                        $dbase->update('system_settings',[
                            'filesadmin' => ''.intval(1).''
                        ]);
                        //Переадресация
                        $app->redirect('/modules/files/');
                    break;    
                    //Если включен то отключаем
                    case 'exit':
                        $dbase->update('system_settings',[
                            'filesadmin' => ''.intval(0).''
                        ]);
                        //Переадресация
                        $app->redirect('/modules/files/');
                    break;  
                
                endswitch;
                
                //Меню управления
                echo '<div style="margin-left:550px;margin-bottom:4px;" class="btn-group">';
                echo '<div class="dropdown">';
                echo '<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">';
                echo 'Управление&nbsp;<span class="caret"></span></button>';
                echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
                //Если управление отключено
                if($glob_core['filesadmin'] == 0) {
                    echo '<li><a role="menuitem" tabindex="-1" href="index.php?do=enter"><i class="glyphicon glyphicon-plus"></i>&nbsp;'.\Lang::__('Включить управление').'</a></li>'; 
                }else {
                    echo '<li><a role="menuitem" tabindex="-1" href="index.php?do=exit"><i class="glyphicon glyphicon-plus"></i>&nbsp;'.\Lang::__('Отключить управление').'</a></li>'; 
                }
                //Создаем новую папку
                echo '<li><a role="menuitem" tabindex="-1" href="foldernew"><i class="glyphicon glyphicon-plus"></i>&nbsp;'.\Lang::__('Добавить папку').'</a></li>';                
        
                //Счетчик о доступности папок
                $dirs = $dbase->query("SELECT COUNT(*) as count FROM `files_dir`");
                $dir = $dirs->fetchArray();
                
                echo '</ul></div></div>';
            }
        }
        
        
        //Пользовательские разделы 
        if($param['razdel'] == 'Razdel') {
            echo '<div class="mainname">'.\Lang::__('Разделы').'</div>';
            echo '<div class="mainpost"><center>';
            //Поиск определенных файлов
            echo '<div class="btn-group">';
	    echo '<a class="btn btn-default" href="search">';
            echo '<i class="glyphicon glyphicon-search"></i>&nbsp;'.\Lang::__('Поиск файлов').'</a>';
            //Топ скачиваемых
            echo '<a class="btn btn-default" href="topdownload">';
            echo '<i class="glyphicon glyphicon-globe"></i>&nbsp;'.\Lang::__('Топ скачиваемых').'</a>';
            //Популярные файлы
            echo '<a class="btn btn-default" href="popular">';
            echo '<i class="glyphicon glyphicon-star"></i>&nbsp;'.\Lang::__('Популярные').'</a>';            
            //Избранные файлы
            echo '<a class="btn btn-default" href="favorites">';
            echo '<i class="glyphicon glyphicon-heart"></i>&nbsp;'.\Lang::__('Избранные').'</a>';  
            
            echo '</div></center></div>';
        }        
    }

      
    /**
     * Получаем данные всех основных папок
     * 
     * @param Null
     */       
    public static function GetDirectory(){
        global $user_group,$dbase,$do,$glob_core,$app;
        
        //Проверяем Доступны ли Папки
        $row = $dbase->query("SELECT COUNT(*) as count FROM `files_dir` WHERE `dir` = ?",[\intval(0)]);
        $rows = $row->fetchArray();
        
        //Если Не найдено Папок
        if($rows['count'] == 0) {
            echo \engine::warning(\Lang::__('В разделе папок не обнаружено'));
            exit;
        }
        
        //Объявляем Навигацию
         $newlist = new \Navigation($rows['count'],10, true); 
         
        //Данные о выводе разделов
        $views = $dbase->query("SELECT * FROM `files_dir` WHERE `dir` = ? ". $newlist->limit()."",[\intval(0)]);
         
        //Выводим разделы
        echo '<div class="mainname">'.\Lang::__('Категории').'</div>';
        echo '<div class="mainpost">';
        echo '<table class="itable">';
        
        //Выводить Данные
        foreach ($views->fetchAll() as $view) {
            //Счетчик файлов
            $counts = $dbase->query("SELECT COUNT(*) as count FROM `files` WHERE `id_dir` = ? OR `idir` = ? ",[$view->id,$view->id]);
            $count = $counts->fetchArray();

                echo  '<tr class="">';
		echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/icons/dir3.png"></td>';
		echo '<td class="c_forum"><b><a style="font-size:15px;" href="'.\Shcms\Component\String\Url::filter($view->name).'-'.$view->id.'">'.$view->name.'</a></b>';
                    
                //Описание Файла
		echo '<p style="margin-top:4px;" class="desc">'.$view->text.'</p></td>';
                    
                echo '<td class="c_stats"><ul>';
                
                    //Если Вы админ и у вас включено управление то получаем разделы
                    if(\Shcms\Component\Users\Group\Group::Groups($user_group) == true AND $glob_core['filesadmin']  == 1) {
                        echo '<li style="font-size:14px;">';
                        echo '<a href="/modules/files/editor/'.\Shcms\Component\String\Url::filter($view->name).'-'.$view->id.'"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;';
                        echo '<a href="/modules/files/delete/'.\Shcms\Component\String\Url::filter($view->name).'-'.$view->id.'"><i class="glyphicon glyphicon-remove"></i></a>';
                        echo '</li>';
                    }else {
                        //Иначе стандартные управления
                        echo '<li><b>'.\engine::number($count['count']).'</b> Файлов</li>';
                    } 
                    
                echo '</ul></td>';
                echo '</tr>';
        }
        
        echo '</table></div>';
    }

    /**
     * Обрабатываем кол. скачиваний
     * 
     * @param $n 
     */     
    public static function TypeOk($n) {
        return ($n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2));
    }

    /**
     * Получаем данные по поиску файлов
     * 
     * @param Null
     */   
    public static function Search() {
        global $dbase;
        
        //Заголовок
	echo '<div class="mainname">'.\Lang::__('Поиск Файлов').'</div>';
        echo '<div class="mainpost">';           
        
            //Объявляем форму
            $form = new \Shcms\Component\Form\Form();
            //Открытие формы
            echo $form->open(['class' => 'navbar-form']);
            //Поле для поиска
            echo '<div class="input-group col-sm-6">';
            echo $form->input(['type' => 'search','id' => 'container-search','class' => 'form-control','name' => 'search']);
            //Кнопка поиска
            echo '<div class="input-group-btn">';
            echo $form->button(['class' => 'btn btn-default','type' => 'submit','value' => '<i class="glyphicon glyphicon-search"></i>']);
            echo '</div></div>';
            //Закрытие формы
            $form->close();
            echo '</div>';
            
            
            echo '<div id="searchlist" class="list-group">';
            
                $searchs = $dbase->query("SELECT * FROM `files`");
                    foreach ($searchs->fetchAll() as $search) {
                        echo '<div class="list-group-item">';
                        echo '<div class="row_3">';
                        echo '<a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($search->name).'-'.$search->id.'"><b>'.$search->name.'</b></a>';
                        echo '</div>';
                        echo '<div class="row_3">'.\engine::input_text($search->text2).'</div></div>';
                    }
            echo '</div>';        
            
            
    }

    /**
     * Получаем данные по Топу Скачиваний
     * 
     * @param Null
     */ 
    public static function TopDownload() {
        global $dbase;
        
        //Кол. страниц
        $sum = $sum ? $sum : 10;
        //Массив вывода по скачиваний
        $alls_num = ['раз','раза'];
        //Данные из базы
        $count = $dbase->query("SELECT * FROM `files` WHERE `countd` > '0' ORDER BY `countd` DESC LIMIT 0, ?",[$sum]);
        //Кол. данных вывелось
        $i = 1;
        
        echo '<div class="mainname">'.\Lang::__('Топ скачиваемых').'</div>';
        //Если есть скачивание
        if($count->num_rows > 0) {
            //Вывод все данные
            foreach ($count->fetchAll() as $counts) {
                //Обработка счетчика
                $global = $alls_num[self::TypeOk($counts->countd)];
                //Обработка названия
                $name = htmlspecialchars( strip_tags( stripslashes( $counts->name ) ), ENT_QUOTES) ;
                $name = str_replace("{", "&#123;", $counts->name);
                $name = strip_tags($counts->name);
                
                //Выводим все
                echo  '<div class="posts_gl">';
                //Счетчик , Название файла и путь
                echo $i.').<a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($counts->name).'-'.$counts->id.'"><b>'.$name.'</b></a>';
                //Кол. скачиваний
                echo '<span class="right text-primary">'.\Lang::__('Загружено').': '.$counts->countd.'&nbsp;'.$global.'</span><br/>';
                //Описание файла
                echo \engine::input_text($counts->text2);
                echo  '</div>';
                $i++;
            }
        }else {
            //Если нет файлов
            echo \engine::warning(\Lang::__('Не было скачано файлов'));
        }
    }

    
    public static function Popular() {
        global $dbase,$profi;
        
        echo '<div class="mainname">'.\Lang::__('Популярные Файлы').'</div>';
        echo '<div class="mainpost">';
            //Данные получаем
            $pfiles = $dbase->query("SELECT * FROM `files` WHERE `count` > '10' OR `countd` > '10' ORDER BY rand() LIMIT 10");
                //Проверяем есть ли популярные файлы
                if($pfiles->num_rows > 0) {
                    echo '<table class="itable">';
                    
                    //Выводим все данные
                    foreach ($pfiles->fetchAll() as $pfile) {
                        echo  '<tr class="">';
                        //Иконка
                        echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/down/'.\engine::format($pfile->files).'.png"></td>';
                        //Название и Путь
                        echo '<td class="c_forum"><b><a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($pfile->name).'-'.$pfile->id.'">'.$pfile->name.'</a></b>';
                        //Краткое описание
                        echo '<p class="desc">'.$pfile->text1.'</p></td>';

                        //Дополнительная информация
                        echo '<td class="c_stats"><ul>';
                            //Кол. просмотров
                            echo '<li><b>'.\engine::number($pfile->count).'</b> Просмотров</li>';
                            //Кол. загрузок
                            echo '<li><b>'.\engine::number($pfile->countd).'</b> Загрузок</li>';
                        echo '</ul></td>';
                        echo '</tr>';
                    }
                    
                    echo '</table>';
                    
                }else {
                    //Если нет Файлов
                    echo \engine::warning(\Lang::__('Популярных файлов не найдено'));
                }
        echo '</div>';
        
        
        echo '<div class="mainname">'.\Lang::__('Популярные Авторы').'</div>';
        echo '<div class="mainpost">';
            //Данные получаем
            $afiles = $dbase->query("SELECT DISTINCT(id_user) FROM `files` ORDER BY rand() LIMIT 10");
                //Проверяем есть ли популярные файлы
                if($afiles->num_rows > 0) {
                    echo '<table class="itable">';
                    
                    //Выводим все данные
                    foreach ($afiles->fetchAll() as $afile) {
                        $profile = $profi->Views(['*'],$afile->id_user);
                        
                        $cfiles = $dbase->query("SELECT COUNT(*) as count FROM `files` WHERE `id_user` = ?",[$afile->id_user]);
                        $cfile = $cfiles->fetchArray();
                        
                        echo  '<tr class="">';
                        //Иконка
                        if($profile['avatar'] == true) {
                            echo '<td class="c_icon"><img src="/upload/avatar/'.$profile['avatar'].'" class="UserPhoto UserPhoto_mini"></td>';
                        }else {
                            echo '<td class="c_icon"><img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini"></td>';
                        }
                        //Название и Путь
                        echo '<td class="c_forum"><b><a href="'.\PROFILE.'?id='.$profile['id'].'">'.$profile['nick'].'</a></b>';

                        //Дополнительная информация
                        echo '<td class="c_stats"><ul>';
                            //Кол. загрузок
                            echo '<li><b>'.\engine::number($cfile['count']).'</b> Файлов всего</li>';
                        echo '</ul></td>';
                        echo '</tr>';
                    }
                    
                    echo '</table>';
                    
                }else {
                    //Если нет Файлов
                    echo \engine::warning(\Lang::__('Популярных файлов не найдено'));
                }        
        echo '</div>';        
    }

    



    /**
     * Обработка Данных и создаем Папку
     * 
     * @param Null
     */          
    public static function PostAdd() {
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
                    'dir'    => ''.intval(0).'',
                    'load'   => ''.intval($new_file).''
                ]);
                //Получаем Данные по Разделу
                $viewi = $dbase->query("SELECT * FROM `files_dir` WHERE `id` = ?",[$id]);
                $view = $viewi->fetchArray();
            
                //Переадресация
                $app->redirect('/modules/files/');
            }	
        }
    }
    
    /**
     * Получаем форму создания папки
     * 
     * @param Null
     */      
    public static function GetPost() {
        //Запуск AngularJS
        echo \engine::AngularJS();
    
        //Блок Названия
        echo '<div class="mainname">'.\Lang::__('Добавить Новый Раздел').'</div>';                
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
