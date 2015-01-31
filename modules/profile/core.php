<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="?id='.$id_user.'" class="btn btn-default">Профиль</a>
            <a href="profile.php?act=edit_profile" class="btn btn-default">Изменить данные</a>
            <a href="#" class="btn btn-default disabled">Личные данные</a>
        </div>';

//Необходимые подключение не трогать
echo '<link href="/engine/template/module/country/jquery.kladr.min.css" rel="stylesheet">';
echo '<script src="/engine/template/module/country/jquery.kladr.min.js" type="text/javascript"></script>';
echo '<script src="/engine/template/module/country/js/city.js" type="text/javascript"></script>';  


//Обработка кнопки
$submit = filter_input(INPUT_POST,'submit',FILTER_DEFAULT);

//Редактируем профиль - Изменить данные профиля
if(isset($submit)) {
    //Обработка текста
    $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
    $desc = $db->safesql($desc);    
    //Обработка Имени
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $name = $db->safesql($name);
    //Обработка Фамилии
    $family = filter_input(INPUT_POST, 'family', FILTER_SANITIZE_STRING);
    $family = $db->safesql($family);
    //Обработка поле Скайпа
    $skype = filter_input(INPUT_POST, 'skype', FILTER_SANITIZE_STRING);
    //Обработка пола
    $pol = filter_input(INPUT_POST, 'pol', FILTER_SANITIZE_NUMBER_INT);
    $pol = intval($pol);
    //Обработка Разрешительных комментарий к профилям
    $cprof = filter_input(INPUT_POST, 'coom_prof', FILTER_SANITIZE_NUMBER_INT);
    $cprof = intval($cprof);
    //Обработка показа друзей из профиля
    $fprof = filter_input(INPUT_POST, 'frend_prof', FILTER_SANITIZE_NUMBER_INT);
    $fprof = intval($fprof);
    //Обработка Месяца Рождении
    $month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_STRING);
    //Обработка Дня рождения
    $day = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_NUMBER_INT);
    $day = intval($day);
    //Обработка Года рождения
    $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
    $year = intval($year);
    //Обработка сайта вашего
    $url = filter_input(INPUT_POST,'url',FILTER_SANITIZE_URL);
    //Обработка Города
    $city = filter_input(INPUT_POST,'city',FILTER_SANITIZE_STRING);
        //Проверяем введен ли скайп правильно
        if(isset($skype)) {
	    if(!empty($skype)) {
		if(!engine::skype($skype)) {
	            echo engine::error(Lang::__('Некорректно указан Skype'));
			echo engine::home(array(Lang::__('Назад'),'?act=core'));
			exit;
		}
	    }
	}
        
    if ($_FILES['avatar']['error'])
       	echo engine::error(Lang::__('Ошибка при загрузке'));
    elseif (!$_FILES['avatar']['size'])
        echo engine::error(Lang::__('Содержимое файла пусто')); 
    else {
        $info = pathinfo($_FILES['avatar']['name']);
		        
	switch (strtolower($info['extension'])) {
	    //JPG
            case 'jpg':
                $avatar = @imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
            break;
            //JPEG
	    case 'jpeg':
                $avatar = @imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
            break;
	    //GIF
            case 'gif':
                $avatar = @imagecreatefromgif($_FILES['avatar']['tmp_name']);
            break;
	    //PNG
            case 'png':
                $avatar = @imagecreatefrompng($_FILES['avatar']['tmp_name']);
            break;
	    //По умолчанию
            default:
                echo engine::error(Lang::__('Расширение файла не опознано'));
            break;
        }
	
        if (!empty($avatar)) {
	    //Категория куда попадет скриншот
            $uploaddir = H.'upload/avatar/';
	    //Название транслированное
	    $end_name = $users['id'];				
	    //Выполняем добавление
	    $handle = new upload($_FILES['avatar']);
		//если скрин доступен выполняем следующее ....
                if ($handle->uploaded) {
		    //даем название
                    $handle->file_new_name_body   = $end_name;
		    $handle->file_overwrite       = true;
		    //размеры
                    $handle->image_resize         = true;
                    $handle->image_x              = 500;
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
		    //Если загрузилась то выводит 
                    $handle->process($uploaddir);
                        //Загружаем Аватар в Папку
                        if ($handle->processed) {
                            $handle->clean();
                        } else {
                            echo 'error : ' . $handle->error;
                        }
                }
	    //Добавляем путь аватара в базу
            $db->query("UPDATE `users` SET `avatar` = '".$db->safesql($end_name).".jpg' WHERE `id` = '{$users['id']}'");

        }else {
            header('Location: ?act=core');
	}
    }	        
        
//Полученные данные отправляем в базу        
$db->query("UPDATE `users` SET `pol` = '{$pol}', `desc` = '{$desc}', `coom_prof` = '{$cprof}', `frend_prof` = '{$fprof}',
           `month` = '{$month}', `day` = '{$day}', `year` = '{$year}', `site` = '{$url}', `skype` = '{$skype}', `city` = '{$city}',
           `name` = '{$name}', `family` = '{$family}' WHERE `id` = '{$id_user}'");  
//Перезагржаем без подтвеждения           
header('Location: ?act=core');

}

echo '<div class="mainname">'.Lang::__('Изменить Данные').'</div>';
echo '<div class="mainpost">';
    
    //Настройка комментарий
        $form = new form('?act=core','','','class="form-horizontal" enctype="multipart/form-data"');
        
        $form->text('<div class="form-group">');
        if($users['avatar'] == false or file_exists(H.'/upload/avatar/'.$users['avatar'].'') == false) {
            $form->text('<label class="col-sm-2 control-label col-font-2" for="inputEmail"><b style="font-size:13px;">Аватар</b></label>');
        }else {
            $form->text('<label class="col-sm-2 control-label col-font-2" for="inputEmail"><b style="font-size:13px;">Аватар</b></label>');
        }    

        $form->text('<div class="col-sm-offset-2 col-sm-10">'); 
        
        $form->text('<input id="input-2" name="avatar" type="file" class="file form-control" multiple="true" data-show-upload="false" data-show-caption="true">');
        $form->text('</div></div><div class="row_g"></div>');
        
        //Настройка вывода всех друзей
        $form->text('<div class="form-group">');
        $form->text('<label class="col-sm-2 control-label col-font-2" for="inputEmail"><b style="font-size:13px;">Комментарии</b></label>');
        $form->text('<div class="col-sm-offset-2 col-sm-10">'); 
        
        //Разрешить комментирование профиля
        $form->text('<div class="checkbox checkbox-info">');
        $form->input3(false,'coom_prof','checkbox','1',($users['coom_prof']?'checked="checked"':'').'id="checkbox3"');
        $form->text('<label for="checkbox3">'.Lang::__('Разрешить комментирование профиля').'</label></div>');
        
        //Показывать друзей в профиле
        $form->text('<div class="checkbox checkbox-info">');
        $form->input3(false,'frend_prof','checkbox','1',($users['frend_prof']?'checked="checked"':'').'id="checkbox4"');
        $form->text('<label for="checkbox4">'.Lang::__('Показывать друзей в профиле').'</label></div>');
            
        $form->text('</div></div><div class="row_g"></div>');
        
        //Настройка дополнительных данных
        $form->text('<div class="form-group">');
        $form->text('<label class="col-sm-2 control-label col-font-2" for="inputEmail"><b style="font-size:13px;">Личные данные</b></label>');
        $form->text('<div class="col-sm-offset-2 col-sm-10">');  
        
            //Дата рождение День Год Месяц
            $form->text('Введите дату рождения');
            //Вывод дня рождения
            $form->text('<div class="row"><div class="col-sm-2">');
            $form->text('<select name="day" class="form-control">');
                //Выводим все дни месяца
	        for($i = 1; $i <= 31; $i++){
                    $form->text('<option value="'.$i.'" ' . ($i == $users['day'] ? 'selected="selected"' : '') . '>'.$i.'</option>');
                }  
	    $form->text('</select>');
             $form->text('</div>');
            //Месяц рождения
            $form->text('<div class="col-sm-4">');
            $form->text('<select name="month" class="form-control">');
	        //Все имеющиеся месеца
	        $dates = array('--', Lang::__('Январь'), Lang::__('Февраль'),Lang::__('Март'),
                       Lang::__('Апрель'),Lang::__('Май'),Lang::__('Июнь'),Lang::__('Июль'),
                       Lang::__('Август'),Lang::__('Сентябрь'),Lang::__('Октябрь'),Lang::__('Ноябрь'),
                       Lang::__('Декабрь'));            
               $i = 0;
	        
                //Вывод месяца
	        foreach($dates as $date) {
	    	    $form->text('<option value="'.$i.'" ' . ($i == $users['month'] ? 'selected="selected"' : '') . '>'.$date.'</option>');
		    $i++;
	        }
	    $form->text('</select>');
            $form->text('</div>');
            //Вывод года рождения
            $form->text('<div class="col-sm-3">');
            $form->text('<select name="year" class="form-control">');
                //Выводим все действуещиеся года рождений
	        for($i = 1930; $i <= 2013; $i++){
                    $form->text('<option value="'.$i.'" ' . ($i == $users['year'] ? 'selected="selected"' : '') . '>'.$i.'</option>');
                }  
	    $form->text('</select><br/>');
             $form->text('</div></div>');
            //Имя пользователья
            $form->text('<div class="row"><div class="col-sm-6">');
	    $form->input2(Lang::__('Имя'),'name','text',$users['name'],'class="form-control"');
            $form->text('</div></div>');
            //Фамилия пользователя
            $form->text('<div class="row"><div class="col-sm-6">');
	    $form->input2(Lang::__('Фамилия'),'family','text',$users['family'],'class="form-control"');
            $form->text('</div></div>');
            //Обо мне
            $form->text('<div class="row"><div class="col-sm-10">');
            $form->textarea2('Обо мне', 'desc',$users['desc'],false,false,'form-control');
            $form->text('</div></div>');
            $form->text('</div></div><div class="row_g"></div>');        
    


        //Настройка контактов
        $form->text('<div class="form-group">');
        $form->text('<label class="col-sm-2 control-label col-font-2" for="inputEmail"><b style="font-size:13px;">Контакты</b></label>');
        $form->text('<div class="col-sm-offset-2 col-sm-10">');  
        
        //Ваш Сайт
        $form->text('<div class="row"><div class="col-sm-6">');
        $form->input2(Lang::__('Сайт'),'url','text',$users['site'],'class="form-control"');
        $form->text('</div></div>');
        //Ваш Skype
        $form->text('<div class="row"><div class="col-sm-6">');
        $form->input2(Lang::__('Skype'),'skype','text',$users['skype'],'class="form-control"');
        $form->text('</div></div>');
        
        $form->text('</div></div><div class="row_g"></div>'); 
        
        //Продолжение дополнительной информации
        $form->text('<div class="form-group">');
        $form->text('<label class="col-sm-2 control-label col-font-2" for="inputEmail"><b style="font-size:13px;">Прочие</b></label>');
        $form->text('<div class="col-sm-offset-2 col-sm-10">');  
        
        //Ваш Пол array(Муж,Жен)
        $form->text('<div class="row"><div class="col-sm-6">');
        $form->select(Lang::__('Пол:'),'pol',array(Lang::__('Не определился') => 1, Lang::__('Мужской') => 2, Lang::__('Женский') => 3),$users['pol'],'','','','','class="form-control"');
        $form->text('</div></div>');
        
        //Ваш Город
        $form->text('<div class="row"><div class="col-sm-6">');
        $form->input2(Lang::__('Город:'),'city','text',$users['city'],'class="form-control"');
        $form->text('</div></div>');
        
        $form->text('</div></div>'); 
        
        
        $form->text('<div class="modal-footer">');
        $form->submit(Lang::__('Применить'),'submit',false,'btn btn-success');
	$form->text('<a class="btn" href="profile.php?act=edit_profile">'.Lang::__('Отменить').'</a>');
	$form->text('</div>');            
        $form->display();
echo '</div>';
//Переадресация        
echo engine::home(array(Lang::__('Назад'),'/modules/profile.php?act=edit_profile'));
		