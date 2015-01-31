<?php

define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

    //Обработка полученного $_GET и фильтруем ее    
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    //Проверка на нумерование    
    $id = intval($id);
    
    //Если произойдет неправильные действия то exit
    if (!isset($id) || !is_numeric($id)) {
        header('Location: index.php');
        exit;
    }
   
    
    //Вытаскивыем данные из базы
   $user_topic = $db->get_array($db->query("SELECT * FROM `forum_topics` WHERE `id` = '".$id."'"));
   
   //Класс Времени
   $tdate = new Shcms\Component\Data\Bundle\DateType();
   
switch($act):
    default:
    //Выводим заголовок темы из базы
    $posts = $db->get_array($db->query('SELECT * FROM `forum_topics` WHERE `id` = '.$id.''));
    //Название страницы
    $templates->template(''.$posts['name'].' - '.Lang::__('Форум'));

    //Отключения форума
    $off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
        //Если включена отключение форума то выйдет эта ошибка
	if($off_forum['off_forum'] == 1) {
	    echo engine::error(Lang::__('Форум приостановлен с ').$tdate->make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
	    echo engine::home(array('Назад','/index.php'));	 
	    exit;
	}
        
    //Обновляем счетчик просмотров    
    $db->query( "UPDATE `forum_topics` SET `views` = '".($posts['views']+1)."' WHERE `id` = '".$id."'" );
    //Обработка кнопки
    $submit = filter_input(INPUT_POST, 'submit');
        //Если пользователь авторизован
        if($id_user == true) {
            //Если нажата кнопка
            if(isset($submit)) {
	        //Обрабатывает описание
                $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
                //Обработка checkbox 0/1
                $pfile = filter_input(INPUT_POST,'file',FILTER_SANITIZE_NUMBER_INT);
	        $file_post = intval( $pfile);
                
                    //Если текст пуст то получаем ошибку
	            if(engine::trim($text) == false) {
	                echo engine::error(Lang::__('Введите текст сообщения'));
	            }else {
                        //Отключаем эту переменную временно
	                $update = false;
			//Вытаскиваем те посты которые находится в теме $posts[id]
		        $query = $db->query('SELECT * FROM `forum_post` WHERE `id_top` = '.$posts['id'].' ORDER BY `id` DESC LIMIT 1');
	                
                        //Проверям если ли посты в теме
                        if($db->num_rows($query)) {
			    //Через $user_post вытакиваем данные
		            $user_post = $db->get_array($query);
                            
                            //Смотрим кто написал последний пост и время публикования
                            if($user_post['id_user'] == $id_user && $user_post['time'] > time - 7200) {
                                //Выключаем переменную для обновления поста последнего пользователя
		                $update = false;
                                //даем простую переменную $id_post
		                $id_post = $user_post['id'];
		            }		
		        }	
                        //Чем больше постов, тем меньше это влияет на рейтинг пользователя
                        //В "-" Не расчитывается рейтинг
                        $addr = 1;
                        $addr = 1 - min($users['frating'], 9) / 10;
			//Проверяем если $update есть какая нибудь данная она ее выполняет
			if($update == true) {	
			    //Это функция обновляет последний пост пользователя и добавляет еще текст туда
			    $text = $user_post['text'] . "\n\n[small]".Lang::__('Пост обновлен:')." ".$tdate->make_date($posts['last_time'] + time).":[/small]\n".$text;
			    //Идет обновление поста ...
			    $db->query("UPDATE `forum_post` SET `text` = '".$db->safesql($text)."' WHERE `id_top` = '".intval($posts['id'])."' AND `id_user` = '".$id_user."' ORDER BY `id` DESC LIMIT 1");
			}else {
			    //Иначе добавляем в новый столбик новые данные
			    $db->query("INSERT INTO `forum_post` (`id_cat`,`id_sec`,`id_top`,`id_user`,`text`,`time`) VALUES ('".intval($posts['id_cat'])."','".intval($posts['id_sec'])."',".intval($posts['id']).",'".intval($id_user)."','".$db->safesql($text)."','".time()."')");
			    $db->query("UPDATE `users` SET `frating` = '".($users['frating']+$addr)."' WHERE `id` = '{$id_user}' LIMIT 1");
                            $id_post = $db->insert_id();
			}
                        
		        //Обновляем время добавления последнего поста
			$db->query('UPDATE `forum_topics` SET `last_time` = '.time().' WHERE `id` = '.$posts['id'].'');
			//Даем пользователю балл за пост
                        $db->query("UPDATE `users` SET `points` = '".($users['points']+1)."' WHERE `id` = '".intval($id_user)."'"); // Начисление баллов
			
                        //Если получена данные о добавление файлов то переадресуем
                        if($file_post == 1) {
			    header('Location: post_file.php?id='.$id);
			}else {
                             //Автоматическое переадресация на тему
			    header('Location: post.php?id='.$id.'');
			}
                            //Переадресация на пред. старницу
			    echo engine::home(array('Назад','post.php?id='.$id.''));
			    exit;
		    }
	    }    
        }
        
    //Ник добавленного
    $nick_author = $user->users($posts['id_user'],array('nick'),false);
    //Если пользователь авторизован
    if(isset($id_user)) {
	//Мне нравится
	$like = $db->get_array($db->query("SELECT * FROM `like` WHERE `id_user` = '".$id_user."' AND `id_list` = '".$id."'"));
	//Выводим счетчик мне нравится
       	$row2 = $db->get_array($db->query("SELECT COUNT(*) FROM `like` WHERE `id_list` = '".$id."'"));
        $viewlike = '';
	//Если вы хотите удалить тему из закладок
	if($like['id_list'] == $id) {
            //
            $viewlike .= '<a class="btn btn-small btn-success" data-toggle="modal" data-target="#like">'.Lang::__('Тема понравилась');
            $viewlike .= ': <b>'.$row2[0].'</b></a>';
	}elseif($id_user == true) {
	    //Если вы хотите добавить тему мне нравится
	    $viewlike .= '<img src="/engine/template/icons/fave_on_alt.png">&nbsp;<a href="post.php?id='.$id.'&do=like"><b>'.Lang::__('Мне нравится').'</b></a>';
	    $viewlike .= '&nbsp;&nbsp;<img src="/engine/template/icons/icon_users.png">&nbsp;'.$row2[0].'';
            $viewlike .= '<span style="font-size:11px;color: #5c5c5c;">';
            $viwelike .= '<a style="cursor: pointer;" data-toggle="modal" data-target="#like">'.$row2[0].'</a></span>';
        }else {
            $viewlike .= '<a class="btn btn-small btn-success" data-toggle="modal" data-target="#like"><i class="glyphicon glyphicon-thumbs-up"></i> '.Lang::__('Тема понравилась');
            $viewlike .= ': <b>'.$row2[0].'</b></a>';
        }
    }
    
    $zcount = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$id."'"));
    $ulike = $db->query("SELECT * FROM `like` WHERE `id_list` = '{$posts['id']}'");
    
    echo '<div class="modal fade" id="like" tabindex="-1" role="dialog" aria-labelledby="likeLabel" aria-hidden="true">
        <div class="modal-dialog"><br/><br/>
        <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="likeLabel">Тема '.$posts['name'].' понравилась</h4>
        </div>
        <div class="modal-body">';
            if($db->num_rows($ulike) > 0) {
            while($ulikes = $db->get_array($ulike)) {
                $tprofile = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '{$ulikes['id_user']}'"));
                echo '<a class="btn btn-small btn-info" href="'.MODULE.'/profile.php?id='.$tprofile['id'].'">'.$tprofile['nick'].'</a>, ';
            }
            }else {
                echo engine::error('Лайков пока нет');
            }
        echo '</div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Назад</button>
            </div>
        </div>
      </div>
    </div>';
    
        //Выводим необходимые данные о теме
        echo '<div class="titleBox">';
        //Название темы
        echo '<span style="font-size:17px;color: #323232;">'.engine::ucfirst($posts['name']).'</span>';
        //Кнопки Мне нравится  / Мне не нравится
        echo '<div class="time">'.$viewlike.'</div><br/>';
        //Автор темы
        echo '<span style="font-size:12px;">Автор:&nbsp;';
        echo '<a href="/modules/profile.php?id='.$posts['id_user'].'">'.$nick_author.'</a>';
        //Дата создания темы
        echo '&nbsp;,&nbsp;'.$tdate->make_date($posts['time']).'</span>';
        
        echo '<script src="/engine/template/module/shcms_share.js"></script>';
        echo '<br/><div class="right">';
            echo '<a class="social_share" data-type="vk" data-text="'.strip_tags($posts['text']).'" href="#"><img src="/engine/template/icons/share/vk.png"></a> ';
            echo '<a class="social_share" data-type="fb" data-text="'.strip_tags($posts['text']).'" href="#"><img src="/engine/template/icons/share/facebook.png"></a> ';
            echo '<a class="social_share" data-type="tw" data-text="'.strip_tags($posts['text']).'" href="#"><img src="/engine/template/icons/share/twitter.png"></a> ';
            echo '<a class="social_share" data-type="lj" data-text="'.strip_tags($posts['text']).'" href="#"><img src="/engine/template/icons/share/lj.png"></a> ';
            echo '<a class="social_share" data-type="ok" data-text="'.strip_tags($posts['text']).'" href="#"><img src="/engine/template/icons/share/ok.png"></a> ';
            echo '<a class="social_share" data-type="mr" data-text="'.strip_tags($posts['text']).'" href="#"><img src="/engine/template/icons/share/mail.png"></a> ';
        echo '</div>';               
        echo '</div>';
        
        //Если  пользователь является автором темы
	if($id_user == $posts['id_user'] or $users['group'] == 15) {
            echo '<div style="margin-left:84%;margin-bottom:4px;" class="btn-group">
                <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-cogs"></i> Опции темы
                    <span class="caret"></span>
                </a>
                  
                <ul class="dropdown-menu">
                    <li><a href="?act=editor&id='.$posts['id'].'">Изменить тему</a></li>';
                        if($posts['close'] == 1) {
                            echo '<li><a href="?act=close&id='.$posts['id'].'">Закрыть тему</a></li>';
                        }elseif($posts['close'] == 2) {
                            echo '<li><a href="?act=open&id='.$posts['id'].'">Открыть тему</a></li>';
                        }    
                    echo '<li><a href="?act=delete&id='.$posts['id'].'">Удалить</a></li>';
                    echo '<li><a href="?act=history&id='.$posts['id'].'">Вся историю</a></li>';    
                echo '</ul>';
            echo '</div>';
            
        }
	//Если авторизован пользователь
	if($id_user == true) {
	    switch($do):
		//Обработка функции мне нравится
		case 'like':
		    if(isset($id) and is_numeric($id)) {
			//Добавим в базу мне нравится
			$db->query('INSERT INTO `like` (`id_list`,`id_user`,`action`,`text`) VALUES ("'.$id.'","'.$id_user.'","2","'.Lang::__('Вам понравилась тема из форума').'")');
			header('Location: post.php?id='.$id.'');
		    } 
		break;
	    endswitch;
	}					
	//Определение категорий пользователей
 	//Выводим счетчик тем
        $row = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$id."'"));
        //Объявляем навигацию
        $newlist = new Navigation($row[0],15,true);
        
            //Если счетчик на 0 выведит
	    if($row[0] == false) {
		echo engine::error(Lang::__('В данной теме нет постов!'));
		exit;
	    }       
            //Вытаскиваем все посты
	    $messag = $db->query("SELECT * FROM `forum_post` WHERE `id_top` = '".$posts['id']."' ". $newlist->limit()."");
		//Проверяем есть ли посты
		if($db->num_rows($messag) > 0) {
		    //Выводим все посты
                    echo '<div class="mainname">Сообщений в теме '.$row[0].'</div>';
                    echo '<div class="mainpost">';
                    echo '<ul class="List_withminiphoto Pad_list">';
		    while($message = $db->get_array($messag)) { 
                        
			//Ник добавленного
    			$nick = $user->users($message['id_user'],array('nick'),false);
			//ID добавленного
			$id_users = $user->users($message['id_user'],array('id'));
                        //Данные о пользователе
                        $profile = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '{$message['id_user']}'"));
			//Сам пост
			if($posts['id_user'] == $message['id_user']) {
			    $notify = '<img src="/engine/template/icons/author.png">&nbsp;'.Lang::__('Автор темы');
			}
                            //Количество лайков
			    $like = $db->get_array($db->query(" SELECT COUNT(*) FROM `forum_like` WHERE `id_topic` = '".$id."' AND `id_post` = '".$message['id']."' AND `like` = 'plus'"));
			    //Количество дис-лайков
                            $dislike = $db->get_array($db->query(" SELECT COUNT(*) FROM `forum_like` WHERE `id_topic` = '".$id."' AND `id_post` = '".$message['id']."' AND `like` = 'minus'"));
			    //Количество постов
                            $countm = $db->get_array($db->query( "SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$posts['id']."'" ));
			    
                            //Если авторизован пользователь увидет лайки
                                if($id_user == true) { 
                                    $isset  = '<a style="color:green;" href="post.php?id='.$id.'&id_post='.$message['id'].'&act=like"><span class="glyphicon glyphicon-thumbs-up"></span> '.$like[0].'</a>'; 
                                    $disisset = '<a style="color:red;" href="post.php?id='.$id.'&id_post='.$message['id'].'&act=dislike"><span class="glyphicon glyphicon-thumbs-down"></span> '.$dislike[0].'</a>'; 
                                }else { 
                                    $isset = '<font color="green"><span class="glyphicon glyphicon-thumbs-up"></span> '.$like[0].'</font>'; 
                                    $disisset = '<font color="red"><span class="glyphicon glyphicon-thumbs-down"></span> '.$dislike[0].'</font>'; 
                                } 	
                            
                        //По порядку выводим посты     
                        echo '<li class="clearfix row3">';
                        
                            //Путь к аватарам
                            $avatar = '/upload/avatar/' . $profile['avatar'];
                                //Если нет аватара выводим аватар по умолчанию
                                if ($profile['avatar'] == false and file_exists($avatar) == false) {
                                    echo '<a href="/engine/template/icons/default_large.png" class="UserPhotoLink left">';
                                    echo '<img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini"></a>';
                                } else {
                                    echo '<a href="' . $avatar . '" title="Просмотр профиля" class="UserPhotoLink left">';
                                    echo '<img src="' . $avatar . '" class="UserPhoto UserPhoto_mini"></a>';
                                }    
                                
                        echo '<div class="list_content">';        
                        //Автор поста
			echo '<a href="'.MODULE.'profile.php?act=view&id='.$id_users.'"><b>'.$nick.'</b></a>';
                //Понравилось не понравился пост
                echo '<span class="time">';	
                        echo ''.$isset.''; 
                        echo '&nbsp;&nbsp;'.$disisset.'';  
                echo '</span>';
                        //Дополнительные параметры
			echo '<div class="details">';
                        //Группа пользователя
			echo '<span><img src="/engine/template/icons/group.png">&nbsp;<font color="green">'.user::group($message['id_user']).'</font></span>';
			//Дата добавления поста
                        echo '<span><img src="/engine/template/icons/date.png">&nbsp;'.$tdate->make_date($message['time']).'</span>';
			echo '</div>';
                
                            //Файлы пользователя в теме
		            $file_theme = $db->query("SELECT * FROM `forum_file` WHERE `id_them` = '".intval($id)."' and `id_post` = '".intval($message['id'])."'");
			    //Если больше 0 то выводим файлы
                            if($db->num_rows($file_theme) > 0) {
                                //Делаем спойлер
                                echo '<div class="panel-group" id="accordion2">';
                                //Файл и его данные
				while($file_them = $db->get_array($file_theme)) { 
                                    echo '<div style="margin-top:4px;" class="panel panel-default">
                                         <div class="panel-heading">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse'.$file_them['id'].'">
                                           '.$file_them['name'].'</a></div>
                                        
                                        <div id="collapse'.$file_them['id'].'" class="accordion-body collapse">
                                            <div class="panel-body">
                                                '.Lang::__('Размер файла').' '.engine::filesize($file_them['size']).'<br/>
                                                '.$tdate->make_date($file_them['time']).'
                                                <div class="right">'.$file_them['text'].'
                                            </div> </div>
                                        </div>';
                                    echo '</div>';
				}
                            echo '</div>';
			    }
                        //Сам пост    
			echo '<div class="row2">'.engine::input_text($message['text']).'</div>';
			echo '</div>';
                        echo '</li>';
                		
		    }
                    echo '</ul>';
                    echo '</div>';
                if($row[0] > 15){
		    //Вывод навигации
		    echo $newlist->pagination('id='.$id.'');
                }   
		}else {
   		    echo engine::error(Lang::__('В теме отсутствуют посты'));
		}
                
	//Если close 1 то темы закрыта
	if($posts['close'] == 1) {
            //Если авторизован пользователь
	    if($id_user == true) {

                echo '<div class="mainname">' . Lang::__('Новое комментарий') . '';
                echo '<span class="time">' . $view . '</span>';
                echo '</div>';
                echo '<div class="mainpost">';
                    //HTML форма
                    $form = new \Shcms\Component\Form\Form;
                    //Открываем форму
                    echo $form->open(array('action' => '?id='.$id.'','class' => 'class="form-horizontal"'));
                    //Текст 
                    echo $form->textbox(array('name' => 'text', 'id' => 'editor'));

                    echo '<div class="modal-footer">';
                    echo '<div class="left">';
                         //Добавляем файл
                        echo '<label>';
                            echo $form->checkbox(array(1 => array('name'=>'file', 'value'=>'1' )));
                        echo Lang::__('Прикрепить файл');
                        echo '</label></div>';
                    //Отправляем    
                    echo $form->submit(array('name' => 'submit','class' => 'btn btn-success'));
                    echo $form->close();
                    echo '<a class="btn" href="section.php?id='.$posts['id_sec'].'">Назад</a>';
                    echo '</div></div>';           
                
	    }
            
	}else {
	    echo engine::error('Тема закрыта для обсуждений');
	}                
    break;
    
    //Скачиваем файл
    case 'download':
        //Обрабатываем полученный файл
        $file = htmlspecialchars($_GET['file']);
        $file = str_replace('../','',$file);
        $file = str_replace('./','',$file);
        //Путь файла
        $filename = H.'upload/forum/files/'.$file.'';
            //Ограничения
            if($file == '.htaccess' || $file == 'index.php'){ 
                echo engine::error(Lang::__('Ошибка! Файл входит в состав запрещеных!'));
            }else{
                if (file_exists($filename)) { 
                    $list = $filename;
                    $name = explode("/",$list);
                    $name = $name[count($name)-1];
                    header('Content-type: text/plain');
                    header("Content-disposition: attachment; filename=$name");
                    header('Content-Description: File Transfer');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($list));
                    ob_clean();
                    flush();
                    readfile($list);
                } else { 
		    echo engine::error(Lang::__('Файл не существует!'));
		}
            }
    break;
    
    //Мне нравится тема
    case 'like':
	$templates->template('Форум'); //Название страницы
            //Сама тема
	    $id_topic = intval($_GET['id']);
            //Пост
	    $id_post= intval($_GET['id_post']);
            //Вытаскиваем данные
	    $likes =  $db->super_query("SELECT * FROM `forum_like` WHERE `id_post` = '".$id_post."' AND `id_topic` = '".$id_topic."' AND `id_user` = '".$id_user."'");
                //Добавлем еще один хороший отзыв
                if($likes['id_user'] != $id_user) {
		    $db->query("INSERT INTO `forum_like` (`id_post`,`id_topic`,`id_user`,`like`) VALUES ('".$id_post."','".$id_topic."','".$id_user."','plus')");
		    header("Location: post.php?id={$id_topic}");
		}else{
		     header("Location: post.php?id={$id_topic}");
		}			 
    break;
	
    //Мне не нравится тема            
    case 'dislike':
	$templates->template('Форум'); //Название страницы
            //Сама тема
	    $id_topic = intval($_GET['id']);
            //Пост
	    $id_post = intval($_GET['id_post']);
            //Вытаскиваем данные
	    $likes = $db->super_query("SELECT * FROM `forum_like` WHERE `id_post` = '".$id_post."' AND `id_topic` = '".$id_topic."' AND `id_user` = '".$id_user."'");
                //Добавляем не хороший отзыв
                if($likes['id_user'] != $id_user) {
		    $db->query("INSERT INTO `forum_like` (`id_post`,`id_topic`,`id_user`,`like`) VALUES ('".$id_post."','".$id_topic."','".$id_user."','minus')");
		    header("Location: post.php?id={$id_topic}");
		}else{
		    header("Location: post.php?id={$id_topic}");
		}			 
	
    break;	
    
    //Изменить заголовок   
    case'editor':
        
        //Если редактирует не автор темы и не админ то ошибка
        if($id_user != $user_topic['id_user'] and $users['group'] != 15) {
            header('Location: post.php?id='.$id.'');
        }else {
             //Название страницы
            $templates->template('Изменить тему');
            //Выводим все данные по теме $id
            $editor = $db->get_array($db->query("SELECT * FROM `forum_topics` WHERE `id` = '".$id."'"));
            //Обработка Кнопки
            $update = filter_input(INPUT_POST,'update',FILTER_DEFAULT);
            
                //Если нажата кнопка и введена название выведит
                if(isset($update)) {
		    //Обрабатываем название
		    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
                    
                    //Если название отсутствует
		    if(empty($name)) {
                        echo engine::error(Lang::__('Вы не ввели название темы'));
                    }else {
                        //Если все верно
		        $db->query("UPDATE `forum_topics` SET `name` = '".$db->safesql($name)."' WHERE `id` = '".$id."'");
		        //Закидываем обновленные данные в Историю				
		        $db->query('INSERT INTO `action` (`name`,`id_topic`,`time`,`id_user`,`action`) VALUES ("'.$user_topic['name'].'","'.$user_topic['id'].'","'.time().'","'.$user_topic['id_user'].'","'.Lang::__('Изменение в название темы').'") ');				
			header('Location: post.php?id='.$id.'');
			exit;
                    }
                }

            //HTML Форма
            echo '<div class="mainname">'.Lang::__('Редактировать Названия темы').'</div>';    
            echo '<div class="mainpost"><br/>';
                $form = new form('?act=editor&id='.$id.'','','','class="form-horizontal"');
                
                $form->text('<div class="form-group">');
                $form->text('<label class="col-sm-3 control-label">'.Lang::__('Название темы').'</label>');
                $form->text('<div class="col-sm-8">');
                $form->input2(false,'name','text',$editor['name'],'class="form-control" placeholder="Введите Название темы" required');
                $form->text('</div></div>');
                $form->text('<div class="modal-footer">'); 
                $form->submit(Lang::__('Изменить'),'update',false,'btn btn-success');
                $form->text('<a class="btn" href="post.php?id='.$id.'">Назад</a>');
                $form->display();
                
            echo '</div></div>';
        }
        
    break;

    //Удаление темы
    case 'delete':
        
        //Если редактирует не автор темы и не админ то ошибка
        if($id_user != $user_topic['id_user'] and $users['group'] != 15) {
            header('Location: post.php?id='.$id.'');
        }else {
             //Название страницы
            $templates->template('Удаление темы');
	    //Закидываем обновленные данные в Историю
	    $db->query('INSERT INTO `action` (`name`,`id_topic`,`time`,`id_user`,`action`) VALUES ("'.$user_topic['name'].'","'.$user_topic['id'].'","'.time().'","'.$user_topic['id_user'].'","'.Lang::__('Выполнялось удаление темы').'")');				
            //Обработка кнопки Да
            $yes = filter_input(INPUT_POST,'yes',FILTER_DEFAULT);
            //Обработки кнопки Нет
            $no = filter_input(INPUT_POST,'no',FILTER_DEFAULT);
            
                //Если вы нажали на подтверждение то будет удалена тема с постами
	        if(isset($yes)) {
                    //Удаляем тему				
                    $db->query('DELETE FROM `forum_topics` WHERE `id` = "'.$id.'"');
               	    //Удаляем все сообщение из темы
                    $db->query('DELETE FROM `forum_post` WHERE `id_top` = "'.$id.'"');				
		    header('Location: index.php'); //Пред. стараница
		    exit;
	        }
                
	    //Подтверждение	
	    echo '<div class="mainname">'.Lang::__('Подтверждение').'</div>';	
            echo '<div class="mainpost">';
	    echo engine::success('Вы действительно хотите удалить выбранную тему? Данное действие невозможно будет отменить.');
	
            //Форма удаление
	    echo '<div style="text-align:right;">';
                $form = new form('?act=delete&id='.$id.'');	
                 
                $form->text('<div class="modal-footer">'); 
		$form->submit(Lang::__('Удалить тему'),'yes',false,'btn btn-success');
                $form->text('<a class="btn" href="post.php?id='.$id.'">'.Lang::__('Отмена').'</a>');		
                $form->display();
                
	    echo '</div></div></div>';
        }    
	
    break;   
        
	
    //Функция закрытие темы
    case 'close':
        
        //Если редактирует не автор темы и не админ то ошибка
        if($id_user != $user_topic['id_user'] and $users['group'] != 15) {
            header('Location: post.php?id='.$id.'');
        }else {
             //Название страницы
            $templates->template('Отключение темы');
            
            $yes = filter_input(INPUT_POST,'yes',FILTER_DEFAULT);
            
		//Если вы нажали на подтверждение то будет закрыта тема для обсуждений
		if(isset($yes)) {
                    //обновляем таблицу для введение новых данные					
                    $db->query('UPDATE `forum_topics` SET `close` = "2" WHERE `id` = "'.$id.'"');	
                    //Закидываем обновленные данные в Историю				
		    $db->query('INSERT INTO `action` (`name`,`id_topic`,`time`,`id_user`,`action`) VALUES ("'.$user_topic['name'].'","'.$user_topic['id'].'","'.time().'","'.$user_topic['id_user'].'","'.Lang::__('Закрытие темы').'") ');				
		    //Успешное закрытие темы
		    header('Location: post.php?id='.$id.'');
		    exit;
		}
			
            //Подтверждение	
	    echo '<div class="mainname">'.Lang::__('Подтверждение').'</div>';	
	    echo '<div class="mainpost">';
	    echo engine::error('Вы действительно хотите закрыть выбранную тему?');
		
            //Форма удаление
	    echo '<div style="text-align:right;">';
                $form = new form('?act=close&id='.$id.'');	
                    
                $form->text('<div class="modal-footer">'); 
		$form->submit(Lang::__('Закрыть тему'),'yes',false,'btn btn-success');
                $form->text('<a class="btn" href="post.php?id='.$id.'">'.Lang::__('Отмена').'</a>');		
                $form->display();
                    
            echo '</div></div></div>';
        } 
        
    break;


    //Функция открытие темы
    case 'open':
        //Если редактирует не автор темы и не админ то ошибка
        if($id_user != $user_topic['id_user'] and $users['group'] != 15) {
            header('Location: post.php?id='.$id.'');
        }else {
             //Название страницы
            $templates->template('Включение темы');
                //обновляем таблицу для введение новых данные			
                $db->query('UPDATE `forum_topics` SET `close` = "1" WHERE `id` = "'.$id.'"');
		//Закидываем обновленные данные в Историю
                $db->query('INSERT INTO `action` (`name`,`id_topic`,`time`,`id_user`,`action`) VALUES ("'.$user_topic['name'].'","'.$user_topic['id'].'","'.time().'","'.$user_topic['id_user'].'","'.Lang::__('Открытие темы').'") ');				
		header('Location: post.php?id='.$id.'');
		exit;
        }        
    
    break;	
	
	
	
    case 'history':       
        //Если редактирует не автор темы и не админ то ошибка
        if($id_user != $user_topic['id_user'] and $users['group'] != 15) {
            header('Location: post.php?id='.$id.'');
        }else {
             //Название страницы
            $templates->template('Включение темы');
            
	    //Выводим счетчик действий
            $row1 = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$id."'"));
            //Определяем через $id_user ник пользователя 
	    $nick = $user->users($user_topic['id_user'],array('nick'));	
            
	    echo '<div class="mainname">'.Lang::__('История темы').'</div>';
	        
            echo '<ul class="mainpost">';
	        echo '<li class="row3">Название темы: <span style="float:right;">'.$user_topic['name'].'</span></li>';
	        echo '<li class="row3">Дата открытия: <span style="float:right;">'.date::make_date($user_topic['time']).'</span></li>';
	        echo '<li class="row3">Автор: <span style="float:right;">'.$nick.'</span></li>';
	        echo '<li class="row3">Всего сообщений: <span style="float:right;">'.$row1[0].'</span></li>';
	    echo '</ul>';
	
	    //Журнал всех действий со стороны администратора
	    echo '<div class="mainname">'.Lang::__('Журнал действий администратора темы').'</div>';
	        echo '<div class="mainpost">';
	        //Выводим таблицы данных с историей
	        echo '<table id="history" class="table table-striped table-bordered" cellspacing="0" width="100%">'; 
                echo '<thead>';
                    echo '<tr>'; 
                        echo '<th>'.Lang::__('Имя пользователя').' </th>'; 
                        echo '<th>'.Lang::__('Тема').'</th>'; 
                        echo '<th>'.Lang::__('Описание').'</th>'; 
                        echo '<th>'.Lang::__('Дата').'</th>'; 
                    echo '</tr>';
                echo '</thead>';    
  
   		//Выводим счетчик действий
                $row = $db->get_array($db->query("SELECT COUNT(*) FROM `action` WHERE `id_topic` = '".$id."'"));
                //Если блок пустой
	        if($row[0] == false) {
                    echo engine::error('Результатов нет');
	            exit;
	        }   
		//Выводим данные из таблицы где $id
                $table = $db->query("SELECT * FROM `action` WHERE `id_topic` = '".$id."' ORDER BY `id` DESC");
                echo '<tbody>';
                    while($tables = $db->get_array($table)) {
                        
                    //Определяем через $id_user ник пользователя 
		    $nick = $user->users($tables['id_user'],array('nick'));
		    //Определяем через $id_user id пользователя 
	            $id_users = $user->users($tables['id_user'],array('id'));
					
		    //Вытаскаваем название темы с таблицы
                    $topic = $db->get_array($db->query("SELECT * FROM `forum_topics` WHERE `id` = '".$tables['id_topic']."'"));
                        //Теперь через while все по сортировке выводим
			echo "<tr class='even'> 
                            <td><a href='".MODULE."profile.php?act=view&id=".$id_users."'>".$nick."</td> 
                            <td>".$topic['name']."</td> 
                            <td>".$tables['action']."</td> 
                            <td>".date::make_date($tables['time'])."</td>"; 
                        echo "</tr>";
                    } 
		//Далее закрываем таблицу	
                echo "</tbody></table>";
                echo '</div>';	
                
	    //Переадресация
            echo engine::home(array(Lang::__('Назад'),'post.php?id='.$id.''));
        }
	
    break;
        
    
endswitch;	
