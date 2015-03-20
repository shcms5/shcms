<?php
/**
 * Класс Для работы с Выводом Файла
 * 
 * @package            Files/Folder/
 * @author             Shamsik
 * @link               http://shcms.ru
 */
namespace files\Folder;

use \Shcms\Component\Data\Filesize;

class ViewsFile {
    
    /**
     * Получаем данные на скачивание
     * 
     * @param Null
     */      
    public static function Download($id) {
        global $dbase;   
        //Выводим данные из Индификатора
        $prosm = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[intval($id)]);
        $pro = $prosm->fetchArray();
    
        //Получаем файл на скачивание
        $fileDownload = \Shcms\Component\Files\FileDownload\FileDownload::createFromFilePath(H.'upload/download/files/'.$pro['files'].'');
        //Получаем Имя файла
        $fileDownload->sendDownload($pro['files']); 
    
        //Обновляем данные в базе
        $dbase->update('files',[
            'oldtime' => ''.time().'',
            'count'   => ''.($pro['count']+1).'',
            'countd'  => ''.($pro['countd']+1).''
        ],[
            'id' => $id
        ]);
    }
    
    
    
    /**
     * Получаем данные о файле
     * 
     * @param $id Индификатор
     */      
    public static function ViewFile($id) {
        global $dbase,$app,$id_user,$tdate,$profi,$id_user;
    
        //Получаем из базы данные о файле
        $prosm = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[intval($id)]);
        $pro = $prosm->fetchArray();
    
        //Проверяем существует ли Данные о файле
        if($id != $pro['id']) {
            $app->redirect('/modules/files');
            exit;
        }
        
        //Обновляем счетчик просмотров
        $update = $dbase->update('files',[
            'count' => ''.($pro['count']+1).''
        ],[
            'id'    => ''.\intval($id).''
        ]);
    
        //Получаем данные об Авторе файла
        $profile = $profi->Views(['*'],$pro['id_user']);
    
        //Счетчик Коментариев
        $comments = $dbase->query("SELECT COUNT(*) as count FROM `down_comment` WHERE `id_file` = ? ",[$pro['id']]);
        $comment = $comments->fetchArray();
    
        //Получаем Расширение Файлов
        $format = \engine::format($pro['files']);
    
        //Получение размера Файла
        $filesize = new \Shcms\Component\Data\Filesize\Filesize(\H.'upload/download/files/'.$pro['files']);
        $size = $filesize->to('B');
        
        $favd = $dbase->query("SELECT COUNT(*) as count FROM `files_favorites` WHERE `id_files` = ?",[$id]);
        $dfav = $favd->fetchArray();

        
        $favo = $dbase->query("SELECT COUNT(*) as count FROM `files_favorites` WHERE `id_user` = ? AND `id_files` = ?",[$id_user,$pro['id']]);
        $fav = $favo->fetchArray();
    
        //Информация
        $minitext = \Lang::__('Чтобы файл попал в раздел Избранное необходимо выполнить его 5 раз ('.$dfav['count'].' - 5)');
        //Если Пользователь автор файла
        if($pro['id_user'] == $id_user) {
            echo '<div style="margin-left:510px;margin-bottom:4px;" class="btn-group">';
            echo '<a class="btn btn-info" href="/modules/files/views/screen/'.\Shcms\Component\String\Url::filter($pro['name']).'-'.$pro['id'].'">'.\Lang::__('Скриншот').'</a>'; 
            echo '<a class="btn btn-info" href="/modules/files/views/editor/'.\Shcms\Component\String\Url::filter($pro['name']).'-'.$pro['id'].'">'.\Lang::__('Опции файла').'</a>'; 
            echo '<a class="btn btn-danger" href="/modules/files/views/delete/'.\Shcms\Component\String\Url::filter($pro['name']).'-'.$pro['id'].'">'.\Lang::__('Удалить').'</a>';
            echo '</div>';	
        }

        //Полная Информация
        echo '<div class="mainname">'.\Lang::__('Информация').'</div>';
        echo '<div class="mainpost">';
           echo '<div class="row">';
           echo '<ul class="portfolio-items col-3">';
           
            //Скриншоты файла если сущействует
            //Первый скрин
            if($pro['screen'] == TRUE) {
                echo '<li class="portfolio-item">';
                echo '<div class="item-inner">';
                echo '<div class="portfolio-image">';
                //Фотография
                echo '<img src="/upload/download/screen/'.$pro['screen'].'" title="'.$pro['files'].'" />';
                echo '<div class="overlay">';
                //Переход на фото Modal Jquery
                echo '<a class="preview btn btn-info" title="'.$pro['name'].'" href="/upload/download/screen/'.$pro['screen'].'"><i class="icon-eye-open"></i></a>';
                echo '</div></div></div></li>';
            }
            
            //Второй скрин
            if($pro['screen2'] == TRUE) {
                echo '<li class="portfolio-item">';
                echo '<div class="item-inner">';
                echo '<div class="portfolio-image">';
                //Фотография
                echo '<img src="/upload/download/screen/'.$pro['screen2'].'" title="'.$pro['files'].'" />';
                echo '<div class="overlay">';
                //Переход на фото Modal Jquery
                echo '<a class="preview btn btn-info" title="'.$pro['name'].'" href="/upload/download/screen/'.$pro['screen2'].'"><i class="icon-eye-open"></i></a>';
                echo '</div></div></div></li>';
            }
            
            //Третий скрин
            if($pro['screen3'] == TRUE) {
                echo '<li class="portfolio-item">';
                echo '<div class="item-inner">';
                echo '<div class="portfolio-image">';
                //Фотография
                echo '<img src="/upload/download/screen/'.$pro['screen3'].'" title="'.$pro['files'].'" />';
                echo '<div class="overlay">';
                //Переход на фото Modal Jquery
                echo '<a class="preview btn btn-info" title="'.$pro['name'].'" href="/upload/download/screen/'.$pro['screen3'].'"><i class="icon-eye-open"></i></a>';
                echo '</div></div></div></li>';
            }
            
            
            echo '</ul></div>';
            
            //Название файла
            echo '<hr/><div class="form-group">';
            echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Имя файла').'</b></label>';
            echo '<div class="col-sm-10">'.$pro['name'].'';
            echo '<span class="time">'.$tdate->make_date($pro['time']).'</span><br/><br/>';
            echo '</div></div><div class="row_g"></div>';
            
            //Если Полное описание Существует
            if($pro['text2'] == true) {
                echo '<div class="form-group">';
                echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Описание').'</b></label>';
                echo '<div class="col-sm-10">'.\engine::input_text($pro['text2']).'<br/><br/></div>';
                echo '</div><div class="row_g"></div>'; 
            }
        
        //Автор файла и Кол. просмотров
        echo '<div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Опубликовал').'</b></label>';
        echo '<div class="col-sm-10"><a href="'.\PROFILE.'?id='.$profile['id'].'"><b>'.$profile['nick'].'</b></a>';
        echo '<span class="time">'.\Lang::__('Просмотров').' <b>'.\engine::number($pro['count']).'</b></span><br/><br/>';
        echo '</div><div class="row_g"></div>';         
   
        
        //Количество комментариев
        echo '<div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Комментарий').'</b></label>';
        echo '<div class="col-sm-10"><b><a href="/modules/files/comment/'.\Shcms\Component\String\Url::filter($pro['name']).'-'.$pro['id'].'">'.$comment['count'].'</b></a>';
        //Если авторизован
        if($id_user == true) {
            //Если нет в избранном
            if($fav['count'] == TRUE){
                echo '<div class="time"><a data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.$minitext.'">?</a></div>';
            }else {
                echo '<div class="time"><a href="/modules/files/favorite/'.\Shcms\Component\String\Url::filter($pro['name']).'-'.$pro['id'].'">'.\Lang::__('Добавить в избранное').'</b></a></div>';
            }
        }
        
        echo '<br/><br/></div>';
        echo '</div><div class="row_g"></div>'; 
        
        //Размер файла
        echo '<div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Размер').'</b></label>';
        echo '<div class="col-sm-10">'.\engine::number($size).' байт<br/><br/></div>';
        echo '</div><div class="row_g"></div>'; 
        
        //Количество скачиваний
        echo '<div class="form-group">';
        echo '<label class="col-sm-2 control-label"><b>'.\Lang::__('Кол. Загрузок').'</b></label>';
        echo '<div class="col-sm-10">'.\engine::number($pro['countd']);
        
            //Если есть хотя-бы одно скачивание
            if($pro['countd'] > 0) {
                echo '<span class="time">Посл.загрузка: '.$tdate->make_date($pro['oldtime']).'</span>';
            }
            
        echo '<br/><br/></div>';
        echo '</div>';      
        
        //Если расщирение равна (mp4 или mkv)
        if($format == 'mp4' OR $format == 'mkv') {
            //Плеер для воспроизведения
            echo '<div class="row_g"></div>';
            echo \engine::VideoJS();    
            echo '<center><video class="video-js vjs-default-skin my-custom-skin" controls preload="auto" width="640" height="264" data-setup="{}">';
            echo '<source src="/upload/download/files/'.$pro['files'].'" type=\'video/mp4\'></video></center>';
            echo '<br/>';
            
        //Если расщирение равна (mp3)
        }elseif($format == 'mp3') {
            echo '<div class="row_g"></div>';
            //Плеер для воспроизведения
            echo \engine::AudioJS();
            echo '<script>audiojs.events.ready(function() {var as = audiojs.createAll();});</script>';
            echo '<center><audio src="/upload/download/files/'.$pro['files'].'" preload="auto"></audio></center>';
            echo '<br/>';
        }else {
            echo '<br/>';
        }    
        
        //Загружаем файл
        echo '<div class="modal-footer">';
        echo '<a class="btn btn-large btn-success" href="/modules/files/'.$pro['id'].'/download">';
        echo \Lang::__('Загрузить').'</a>';
        echo '</div>';
            
        echo '</div></div>';
        
        //Счетчик файлов автора
        $afile = $dbase->query("SELECT COUNT(*) as count FROM `files` WHERE `id_user` = ? ",[$profile['id']]);
        $afiles = $afile->fetchArray();
    
        //Если файлов найдется больше 2 то выводим их
        if($afiles['count'] > 2) {
        //Другие файлы Автора
        echo '<div class="mainname">'.\Lang::__('Другие файлы Автора').'</div>';
            echo '<div class="mainpost">';
            echo '<table class="itable">';
        
            //Вывод данных файлов
            $sfiles = $dbase->query("SELECT * FROM `files` WHERE `id_user` = ? ORDER BY rand() LIMIT 5",[$profile['id']]);
            
            //Получаем данные о выводе
            foreach ($sfiles->fetchAll() as $sfile) {
                echo  '<tr class="">';
                echo '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/down/'.\engine::format($sfile->files).'.png"></td>';
                echo '<td class="c_forum"><b><a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($sfile->name).'-'.$sfile->id.'">'.\engine::ucfirst($sfile->name).'</a></b>';
                echo '<small> <b>Автор:</b>'.$profile['nick'].'</small>';
                
                    //Если существует текст
                    if($sfile->text2 == true) {
                        echo '<p class="desc">'.\engine::input_text($sfile->text2).'</p>';
                    }else {
                        echo '<p class="desc">'.\Lang::__('Описание не найдено').'</p>';
                    }
                    
                //Просмотры файлов    
                echo '</td><td class="c_stats"><ul>';
                echo '<li><b>'.\engine::number($sfile->count).'</b> Просмотров</li>';
                echo '</ul></td>';
                echo '</tr>';
            }
    
            echo '</table></div>';
        }
    }

    /**
     * Удаляем Файл и его данные с комментариями
     * 
     * @param $id Индификатор
     * @var GetDeleteFile()
     */ 
    public static function GetDeleteFile($id) {
        global $dbase,$app;
        
        $file = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$id]);
        $files = $file->fetchArray();

        //Удаляем прикрепленный файл если есть
        @unlink(\H.'upload/download/files/'.$files['files'].'');
        
        //Удаляем данные
        $delete = $dbase->delete('files',[
            'id'  =>   ''.$id.''
        ]);
        
        //Удаление комментариев
        $deletecomm = $dbase->delete('down_comment',[
            'id_file'  => ''.$id.''
        ]);
        
        $app->redirect('/modules/files/');
    
    }

    /**
     * Получаем вверхнюю переходную навигацию
     * 
     * @param $id Индификатор
     * @var NavScreen()
     */ 
    public static function NavScreen($id){
        global $dbase;
        
        //Данные о файле
        $prosm = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[intval($id)]);
        $pro = $prosm->fetchArray();      
        
            //Навигация Вверхняя
            echo '<span class="btn-group btn-breadcrumb margin-bottom">';
            //На главную
            echo '<a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>';
            //На файл
            echo '<a href="/modules/files/views/'.\Shcms\Component\String\Url::filter($pro['name']).'-'.$pro['id'].'" class="btn btn-default">'.$pro['name'].'</a>';
            //Скрин
            echo '<a disabled href="index.php" class="btn btn-default">'.\Lang::__('Скриншот').'</a>';
            echo '</span>';
    }

    
    /**
     * Получаем данные о Загруженных скриншотах и Форма
     * 
     * @param $id Индификатор
     * @var GetScreen() 
     */ 
    public static function GetScreen($id) {
        global $dbase,$app;
        
        $file = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$id]);
        $files = $file->fetchArray();
    
        //Удаляем Скриншот по выбору
        //Обработка суперглобального переменного
        $delete = filter_input(INPUT_GET,'delete',FILTER_SANITIZE_NUMBER_INT);

        //Удаляем скриншоты 
        //Первый скрин
        if($delete == 1) {
            //Удаляем из дериктории
            if(@unlink(\H.'upload/download/screen/'.$files['screen'].'')) {
                //Удаляем из базы
                $dbase->update('files',[
                    'screen' => ''
                ],[
                    'id'      => ''.$id.''
                ]);
                //Переадресация
                $app->redirect('/modules/files/views/screen/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id'].'');       
            }
        //Второй скрин
        }elseif($delete == 2) {
            //Удаляем из дериктории
            if(@unlink(\H.'upload/download/screen/'.$files['screen2'].'')) {
                //Удаляем из базы
                $dbase->update('files',[
                    'screen2' => ''
                ],[
                    'id'      => ''.$id.''
                ]);
                $app->redirect('/modules/files/views/screen/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id'].'');       
            }
        //Третий скрин    
        }elseif($delete == 3) {
            //Удаляем из дериктории
            if(@unlink(\H.'upload/download/screen/'.$files['screen3'].'')) {
                //Удаляем из базы
                $dbase->update('files',[
                    'screen3' => ''
                ],[
                    'id'      => ''.$id.''
                ]);
                $app->redirect('/modules/files/views/screen/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id'].'');       
            }
        }
        
        //Запуск AngularJS
        echo \engine::AngularJS();
        
        //Главная навигация
        self::NavScreen($id);
        
        echo '<div class="mainname">'.\Lang::__('Прикрепление скриншота').'</div>';                
        
        //Если скриншоты отсутствуют	
        if($files['screen'] == false) {
            echo '<div class="mainpost">';
            echo \engine::warning(\Lang::__('Скриншотов не найдено.'));
            echo '</div>';
        }else {
            //Получаем скриншоты и номера их
            for ($i = 0; $i < 1; $i++) {
                //Основной скриншот
	        if($files['screen'] == true) { 
	            echo '<div class="posts_gl">Скриншот №('.($i + 1).')';
                    echo '<img style="margin-right:5px;" src="/upload/download/screen/'.$files['screen'].'" align="left" width="50">';
		    echo '<div class="right">';
                    echo '<a href="/modules/files/views/screen/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id'].'&delete=1"><i class="glyphicon glyphicon-remove"></i></a>';
                    echo '</div>';
                    echo '<p class="text-success">'.\Lang::__('Добавлен').'</p>';
                    echo '</div>';
                //Второй скриншот    
	        }if($files['screen2'] == true) {
	            echo '<div class="posts_gl">Скриншот №('.($i + 2).')';
                    echo '<img style="margin-right:5px;" src="/upload/download/screen/'.$files['screen2'].'" align="left" width="50">';                    
		    echo '<div class="right">';
                    echo '<a href="/modules/files/views/screen/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id'].'&delete=2"><i class="glyphicon glyphicon-remove"></i></a>';
                    echo '</div>';
                    echo '<p class="text-success">'.\Lang::__('Добавлен').'</p>';
                    echo '</div>';
                //Третий скриншот    
	        }if($files['screen3'] == true) {
	            echo '<div class="posts_gl">Скриншот №('.($i + 3).')';
                    echo '<img style="margin-right:5px;" src="/upload/download/screen/'.$files['screen3'].'" align="left" width="50">'; 
		    echo '<div class="right">';
                    echo '<a href="/modules/files/views/screen/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id'].'&delete=3"><i class="glyphicon glyphicon-remove"></i></a>';
                    echo '</div>';
                    echo '<p class="text-success">'.\Lang::__('Добавлен').'</p>';
                    echo '</div>';
                }

            }
        }
        
        echo '<div class="mainpost">';
        //Объявляем форму
        $form = new \Shcms\Component\Form\Form();
        //Получаем метод и открытие
        echo $form->open(['action' => '','class' => 'form-horizontal','files' => 'files']);
        
        //Добавление скриншотов
        echo '<br/><div class="form-group">';
        echo '<label class="col-sm-2 control-label">'.\Lang::__('Скриншот').'</label>';
        echo '<div class="col-sm-10">';
        
        if($files['screen'] == false) {
            echo $form->input(['type' => 'file','name' => 'file','class' => 'file form-control']);
        }elseif($files['screen2'] == false) {
            echo $form->input(['type' => 'file','name' => 'file','class' => 'file form-control']);
        }elseif($files['screen3'] == false) {
            echo $form->input(['type' => 'file','name' => 'file','class' => 'file form-control']);
        }
        echo '</div></div>';
        
        //Закрытие Формы
        echo $form->close();
        echo '</div>';   
    
    }
    
    
    public static function PostScreen($id) {
        global $dbase,$app;
   
        $file = $dbase->query("SELECT * FROM `files` WHERE `id` = ?",[$id]);
        $files = $file->fetchArray();
    
        $DirFile = \H .'upload/download/screen/';
        $type = \engine::format($_FILES['file']['name']);
        $ntype = \engine::format_r($_FILES['file']['name']);
        $filename = \Shcms\Component\String\Url::filter($ntype).'.'.$type;
        $filenames = \Shcms\Component\String\Url::filter($ntype);
        
        //Объявляем Класс Добавление файла
        $handle = new \Upload($_FILES['file']);
        
        if ($handle->uploaded) {
            $handle->file_new_name_body   = $filenames;
            //размеры
            $handle->image_resize         = true;
            $handle->image_x              = 640;
            $handle->image_ratio_y        = true;
            
            //Конвертируем все изображение в jpg для качественности
	    $handle->image_convert        = 'jpg';									
		
            //Водяной знак
            $handle->image_text            = 'SHCMS Engine'; //Временно не менять 
            $handle->image_text_opacity    = 80;
		   
            //Установка цвета к водяному знаку
            $handle->image_text_color      = '#0000FF';
            $handle->image_text_background = '#FFFFFF';
		   
            //Установим значем в какой угол пойдет знак
            $handle->image_text_x          = -5;
            $handle->image_text_y          = -5;
            $handle->image_text_padding    = 5;
            //Путь
            $handle->process($DirFile);
            
            if ($handle->processed) {
                //Если Скриншота 1 нет
                if($files['screen'] == false) {
                    $dbase->update('files',[
                        'screen'  => ''.$dbase->escape($filename).''
                    ],[
                        'id'   => ''.$id.''
                    ]); 
                    
                //Если Скриншота 2 нет
                }elseif($files['screen2'] == false) {
                    $dbase->update('files',[
                        'screen2'  => ''.$dbase->escape($filename).''
                    ],[
                        'id'   => ''.$id.''
                    ]); 
                    
                //Если Скриншота 3 нет    
                }elseif($files['screen3'] == false) {
                    $dbase->update('files',[
                        'screen3'  => ''.$dbase->escape($filename).''
                    ],[
                        'id'   => ''.$id.''
                    ]); 
                }
                //Переадресация
                $app->redirect('/modules/files/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id']);
            }
            
        }else {
            //Переадресует
             $app->redirect('/modules/files/'.\Shcms\Component\String\Url::filter($files['name']).'-'.$files['id']);
        }
    }
}
