<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();
use Shcms\Component\Form\Form;

require_once 'Controller/Autoload.php';


//Отключения форума
$off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
    if($off_forum['off_forum'] == 1) {
	echo engine::error(Lang::__('Форум приостановлен с ').$tdate->make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
	echo engine::home(array('Назад','/index.php'));	 
	exit;
    }
    
    
switch($do): 
    /**
     * По умолчанию Выводим Основную страницу
     */
    default;
        $templates->template(Lang::__('Форум'));
        
	//Выводим счетчик категорй
        $row = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_category`"));
        //Если нет категорий то никуда не получится попасть больше
        if($row[0] == false) {
	    echo '<div class="mainname">Отсутствие категорий</div>';
	    echo '<div class="mainpost">';
            
	    if($users['group'] == 15) {
                echo engine::error(\Lang::__('Категорий не найдено.'));
                echo '<center><a class="btn btn-success" href="index.php?do=new_category">Создать категорию</a></center>';
	    }else {
		echo engine::error(\Lang::__('В форуме не найдено категорий'));
	    }
	    echo '</div>';
	    //Переадресация на пред. страницу	
	    echo engine::home(array('Назад','/index.php'));	 
	    exit;
	}
        //Если пользователь авторизован
	if($id_user == true) {
            //Вывод всех моих тем
	    $mthem = $db->get_array($db->query( "SELECT COUNT(*) FROM `forum_topics` WHERE `id_user` = '{$id_user}'" ));
            //Вывод всех моих постов
	    $mpost = $db->get_array($db->query( "SELECT COUNT(*) FROM `forum_post` WHERE `id_user` = '{$id_user}'" ));
    
            if($groups->setAdmin($user_group) == 15) {
                //Действие для администратор
                $sview .= '<div style="margin-left:85%;margin-bottom:4px;" class="btn-group">';
                $sview .= '<div class="dropdown">';
                //Пункты доступные
                $sview .= '<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">';
                $sview .= 'Управление&nbsp;<span class="caret"></span></button>';
                //Подкатегории пункта
                $sview .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
                    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?do=new_category"><i class="glyphicon glyphicon-plus"></i>&nbsp;Новая категория</a></li>';
                    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?do=new_section"><i class="glyphicon glyphicon-plus"></i>&nbsp;Новый раздел</a></li>';
                    //Разделяем
                    $sview .= '<li class="divider"></li>';
                    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?do=setting"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Настройки категории</a></li>';
                    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?do=rsetting"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Настройка раздела</a></li>';
                $sview .= '</ul></div>';
                $sview .= '</div>';
            }
            

                //Заносим в $ и получаем все данные по разделу
	        $sview .= '<div class="mainname">'.\Lang::__('Разделы').'</div>';
                $sview .= '<center><div class="mainpost">';
		$sview .= '<a class="btn btn-default" href="?do=search">';
                $sview .= '<i class="glyphicon glyphicon-search"></i>&nbsp;Найти тему</a>';
                $sview .= '&nbsp;<a class="btn btn-default" href="?do=mthem">';
                $sview .= '<i class="glyphicon glyphicon-th-large"></i>&nbsp;Мои темы&nbsp;'.$mthem[0].'</a>';
                $sview .= '&nbsp;<a role="button" class="btn btn-default" href="?do=mpost">';
                $sview .= '<i class="glyphicon glyphicon-pencil"></i>&nbspМои посты&nbsp;'.$mpost[0].'</a>';
                $sview .= '&nbsp;<a role="button" class="btn btn-info" href="?do=rating">';
                $sview .= '<i class="glyphicon glyphicon-signal"></i>&nbspРейтинг форума</a>';                
		$sview .= '</div></center>';
                
            //Выводим все данные
            echo $sview;
        }	
        //Получаем запрос о выводе всех данных из таблицы
	$category = $db->query('SELECT * FROM `forum_category`');
            
            //Начинаем выводить все данные
            echo '<div class="panel-group" id="accordion">';
	    while($cat = $db->get_array($category)) {
		//Счетчик сообщений
		$countm = $db->get_array($db->query( "SELECT COUNT(*) FROM `forum_post` WHERE `id_cat` = '".$cat['id']."'" ) );
	        
                //Название и id категории
		echo '<div style="margin-top:3px;"  class="mainname">';
                echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#panel'.$cat['id'].'">';
                echo '<b>'.engine::ucfirst($cat['name']).'</b></a>';
                echo '</div>';

		
		    //Выводит все Разделы	
		    $subrazdel = $db->query('SELECT * FROM `forum_subsection` WHERE `id_cat` = "'.$cat['id'].'"');
		    //Проверяем есть ли разделы в категории
		if($db->num_rows($subrazdel) > 0) {
                    echo '<div id="panel'.$cat['id'].'" class="panel-collapse collaps  in">';
                    echo '<div class="mainpost">';
		    while($subrazd = $db->get_array($subrazdel)) {
			//Выводим счетчик категорй
                        $row1 = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_topics` WHERE `id_sec` = '".$subrazd['id']."'"));
                        $cpost = $db->get_array($db->query("SELECT * FROM `forum_topics` WHERE `id_sec` = '{$subrazd['id']}'"));
                       $row2 = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_sec` = '{$cpost['id']}'"));
                        //Вывод Всех определенных данных
                        $sview  = '<table class="itable">';
		        $sview .= '<tbody><tr class="">';
                        $sview .= '<td class="c_icon">';
                        $sview .= '<img src="/engine/template/icons/fol_txt.png">';
	                $sview .= '</td>';
		        //Вывод данных из базы
                        $sview .= '<td class="c_forum"><h5>';
                        //Название тем
                        $sview .= '<a href="index.php?do=section&id='.$subrazd['id'].'"><b>'.engine::ucfirst($subrazd['name']).'</b></a>';
                        //Параметры темы
		        $sview .= '</h5><p class="desc">';
                        $sview .= engine::input_text($subrazd['text']).'</p></td>';
                        //Автор темы и Время создания
                        $sview .= '<td class="c_stats"><ul>';
		        $sview .= '<li><b>'.$row1[0].'</b> Тем</li>';
                        $sview .= '<li><b>'.$row2[0].'</b> Постов</li>';
		        $sview .= '</ul></td>';
                        $sview .= '</tr></tbody></table>';
                        //Выводим все данные
                        echo $sview;
		    }
                echo '</div>';    
                echo '</div>';
		}else {
                    echo '<div class="mainpost">';
		    echo engine::warning(\Lang::__('В категории не найдено Разделов'));
                    echo '</div>';
		}
            	
            }
            echo '</div>';
        //Переадресация на пред. страницу	
	echo engine::home(array('Назад','/index.php'));	
        
    break;
    
    /**
     * Функция Получения Разделов Форума
     */
    case 'section':
        
        //Обработка Индификатора
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($id);
            
        //Проверка существует ли Индификатор
        if(empty($id)) {
            header('Location: index.php');
        }    
        
        //Получаем Заголовок
        $title = \forum\Section\Section::Title($id);
        
        //Навигация
        echo \forum\Section\Section::Secnav();
        
        //Если авторизован то создает тему
        if($id_user == true) {
            echo '<a class="btn btn-info right" href="index.php?do=new_topic&id='.$id.'">';
            echo '<i class="glyphicon glyphicon-plus"></i> '.\Lang::__('Новая тема').'</a><br/>';
        }
        
        //Получаем Счетчик Тем
        $rows = $dbase->query("SELECT COUNT(*) as count FROM `forum_topics` WHERE `id_sec` = ?",array($id));
        $nav = $rows->fetchArray();
        
        //Добавляем Навигацию
        $newlist = new \Navigation($nav['count'],10,true);   
        
        //Проверяем Если ли Темы в разделе
        if($nav['count'] == false) {
            echo engine::error('В данном разделе тем не найдено');
            exit;
        }
        
        //Производим вывод данных
        $topics = $dbase->query("SELECT * FROM `forum_topics` WHERE `id_sec` = ? ORDER BY `id` DESC {$newlist->limit()} ",array($id));
        
        //Выводим разделы
        echo '<div class="mainname">'.\Lang::__('Все темы раздела').'</div>';
        echo '<div class="mainpost">';
        
        echo '<table class="itable"></tbody>';
            //Все темы раздела
            foreach ($topics->fetchAll() as $topic) {
                //Счетчик тем Выводим
                $tcounts = $dbase->query("SELECT COUNT(*) as count FROM `forum_post` WHERE `id_top` = ? ",array($topic->id));
                $tcount = $tcounts->fetchArray();
                //Ник автора темы
                $nick = \Shcms\Component\Users\User\User::Views(array('nick'),$topic->id_user);
                
                echo '<tr class="">';
                    //Если тема закрыта 
                    if($topic->close == 2) {
                        echo '<td class="c_icon">';
                        echo '<img src="/engine/template/icons/locked.png">';
                        echo '</td>';
                    }else {
                        echo '<td class="c_icon">';
                        echo '<img src="/engine/template/icons/nthem.png">';
                        echo '</td>';
                    }
                    
                    //Выводим Название Темы, Автор, И время созданиея
                    echo '<td class="c_forum">';
                        //Название
                        echo '<a href="index.php?do=topics&id='.$topic->id.'"><b>'.\engine::ucfirst($topic->name).'</b></a>';
                        //Автор
                        echo '<p class="desc">'.\Lang::__('Автор:').'&nbsp;'.$nick['nick'].'&nbsp;,';
                        //Время создания
                        echo $tdate->make_date($topic->time).'</p>';
                    echo '</td>';
                    
                    echo '<td class="c_stats"><ul>';
                        //Количество ответов
                        echo '<li><b>'.$tcount['count'].'</b>&nbsp;'.\Lang::__('Ответов').'</li>';
                        //Количество просмотров
                        echo '<li><b>'.$topic->views.'</b>&nbsp;'.\Lang::__('Просмотров').'</li>';
                    echo '</ul></td>';
                echo '</tr>';
            
            }
        echo '</tbody></table></div>';    
        
    break;   
    
    /**
     * Добавление Новой темы
     * 
     */    
    case 'new_topic':
        //Обработка Индификатора
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($id);
            
        //Проверка существует ли Индификатор
        if(empty($id)) {
            header('Location: index.php');
        }    
        
        if($id_user == false) {
            header('Location: index.php');
            exit;
        }
        
        //Получаем Заголовок
        $title = \forum\Topics\Topics::Title($id);
        
        //Подключаем Angular
        echo \engine::AngularJS();
        
        $error = array();
        echo \forum\Topics\Topics::AddTopics($id,array('group' => 'Groups','nav' => 'Nav'));
    break;       
   
    
    /**
     * Получаем Из базы все темы
     */    
    case 'topics':
        
        //Обработка полученного $_GET и фильтруем ее    
        $id = \filter_input(\INPUT_GET,'id',  \FILTER_SANITIZE_NUMBER_INT);
        //Проверка на нумерование    
        $id = \intval($id);

        //Проверка существует ли Индификатор
        if(empty($id)) {
            \header('Location: index.php');
        }    
        
        
        //Получаем Данные Темы
        $topic = $dbase->query("SELECT * FROM `forum_topics` WHERE `id` = ? ",  [$id]);
        $topics = $topic->fetchArray();
        
        switch ($act):
            
            default:
                //Получаем Заголовок Темы
                $title = \forum\Topics\Topics::TitleTopics($id);
                
                //Обновляем Просмотры
                $dbase->update('forum_topics',array(
                    'views' => ''.($topics['views'] + 1).''
                ),array(
                    'id' => ''.$id.''
                ));
                
                //Обработка Кнопки
                $submit = \filter_input(INPUT_POST,'submit',FILTER_DEFAULT);
                
                if($id_user == TRUE) {
                    //Если нажата кнопка
                    if($submit == TRUE) {
                        //Обработка описания
                        $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
                        //Обработка параметра checkbox
                        $pfile = filter_input(INPUT_POST,'file',FILTER_SANITIZE_NUMBER_INT);
                        $file_post = intval($pfile);
                        
                        //Если Не введен текст
                        if(\engine::trim($text) == FALSE) {
                            echo \engine::error(\Lang::__('Введите текст сообщения'));
                        }else {
                            //Чем больше постов, тем меньше это влияет на рейтинг пользователя
                            $addr = 1;
                            $addr = 1 - \min($users['frating'], 9) / 10;
                            
                            $dbase->insert('forum_post',array(
                                'id_cat'   =>  ''.$topics['id_cat'].'',
                                'id_sec'   =>  ''.$topics['id_sec'].'',
                                'id_top'   =>  ''.$topics['id'].'',
                                'id_user'  =>  ''.$id_user.'',
                                'text'     =>  ''.$dbase->escape($text).'',
                                'time'     =>  ''.time().''
                            ));
                            
                            $dbase->update('users',array(
                                'frating' => ''.($users['frating']+$addr).''
                            ),array(
                                'id' => ''.$id_user.''
                            ));
                        
                            //Обновляем время добавления последнего поста
                            $dbase->update('forum_topics',array(
                                'last_time' => ''.time().''
                            
                            ),array(
                                'id' => ''.$topics['id'].''
                            ));
                            //Даем пользователю балл за пост
                            $dbase->update('users',array(
                                'points' => ''.($users['points']+1).''
                            ),array(
                                'id' => $id_user
                            ));
                        
                            //Если получена данные о добавление файлов то переадресуем
                            if($file_post == 1) {
                                header('Location: index.php?do=topics&id='.$id.'&act=files');
                            }else {
                                header('Location: index.php?do=topics&id='.$id.'');
                            }
                        }
                    }
                }
                
                //Данные по пользователю
                $profile = \Shcms\Component\Users\User\User::Views(array('nick'),$topics['id_user']);
                    //Мне нравится
                    $likes = $dbase->query("SELECT * FROM `like` WHERE `id_user` = ? AND `id_list` = ?",array($id_user,$id));
                    $like = $likes->fetchArray();
                    //Счетчик Лайков
                    $rows = $dbase->query("SELECT COUNT(*) as count FROM `like` WHERE `id_list` = ? ",array($id));
                    $clike = $rows->fetchArray();
                    $vlike = '';
                    
                    //Если вы хотите удалить тему из закладок
                    if($like['id_list'] == $id) {
                        $vlike .= '<a class="btn btn-small btn-success" data-toggle="modal" data-target="#like">Тема понравилась';
                        $vlike .= ': <b>'.$clike['count'].'</b></a>';

                    }elseif($id_user == true) {
                        //Если вы хотите добавить тему мне нравится
                        $vlike .= '<img src="/engine/template/icons/fave_on_alt.png">&nbsp;';
                        $vlike .= '<a href="index.php?do=topics&id='.$id.'&act=like"><b>Мне нравится </b></a>';
                        $vlike .= '<img src="/engine/template/icons/icon_users.png">&nbsp;';
                        $vlike .= '<span style="font-size:11px;color: #5c5c5c;">';
                        $vlike .= '<a style="cursor: pointer;" data-toggle="modal" data-target="#like">'.$clike['count'].'</a></span>';
                    }else {
                        $vlike .= '<a class="btn btn-small btn-success" data-toggle="modal" data-target="#like">';
                        $vlike .= '<i class="glyphicon glyphicon-thumbs-up"></i> Тема понравилась';
                        $vlike .= ': <b>'.$clike['count'].'</b></a>';
                    }
               
                
                //Счетчик Постов
                $zcounts = $dbase->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = ? ",array($id));
                $zcount = $zcounts->fetchArray();
                //Лайки
                $ulike = $dbase->query("SELECT * FROM `like` WHERE `id_list` = ? ",array($topics['id']));
                
                echo '<div class="modal fade" id="like" tabindex="-1" role="dialog" aria-labelledby="likeLabel" aria-hidden="true">';
                echo '<div class="modal-dialog"><br/><br/>';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span></button>';
                echo '<h4 class="modal-title" id="likeLabel">Тема '.$topics['name'].' понравилась</h4>';
                echo '</div>';
                
                //Контент
                echo '<div class="modal-body">';
                        while($likes = $ulike->fetch()) {
                            //Пользователи которые Лайкнули
                            $profile = \Shcms\Component\Users\User\User::Views(['id','nick'],$likes->id_user);
                            echo '<a class="btn btn-small btn-info" href="'.\MODULE.'/profile.php?id='.$profile['id'].'">'.$profile['nick'].'</a>, ';
                        }
                echo '</div>';
                //Footer Modal
                echo '<div class="modal-footer">';
                echo '<button type="button" class="btn btn-default" data-dismiss="modal">Назад</button>';
                echo '</div></div></div></div>';
                
                //Выводим необходимые данные о теме
                echo '<div class="titleBox">';
                //Название темы
                echo '<span style="font-size:17px;color: #323232;">'.\engine::ucfirst($topics['name']).'</span>';
                //Кнопки Мне нравится  / Мне не нравится
                echo '<div class="time">'.$vlike.'</div><br/>';
                
                //Автор темы
                echo '<span style="font-size:12px;">Автор:&nbsp;';
                echo '<a href="/modules/profile.php?id='.$topics['id_user'].'">'.$profile['nick'].'</a>';
                //Дата создания темы
                echo '&nbsp;,&nbsp;'.$tdate->make_date($topics['time']).'</span>';
                //Подключение Соц сети
                echo '<script src="/engine/template/module/shcms_share.js"></script>';
                        
                echo '<br/><div class="right">';
                echo '<a class="social_share" data-type="vk" data-text="'.strip_tags($topics['text']).'" href="#"><img src="/engine/template/icons/share/vk.png"></a> ';
                echo '<a class="social_share" data-type="fb" data-text="'.strip_tags($topics['text']).'" href="#"><img src="/engine/template/icons/share/facebook.png"></a> ';
                echo '<a class="social_share" data-type="tw" data-text="'.strip_tags($topics['text']).'" href="#"><img src="/engine/template/icons/share/twitter.png"></a> ';
                echo '<a class="social_share" data-type="lj" data-text="'.strip_tags($topics['text']).'" href="#"><img src="/engine/template/icons/share/lj.png"></a> ';
                echo '<a class="social_share" data-type="ok" data-text="'.strip_tags($topics['text']).'" href="#"><img src="/engine/template/icons/share/ok.png"></a> ';
                echo '<a class="social_share" data-type="mr" data-text="'.strip_tags($topics['text']).'" href="#"><img src="/engine/template/icons/share/mail.png"></a> ';
                echo '</div></div>';
                
                //Если  пользователь является автором темы
                if($id_user == $topics['id_user'] OR $users['group'] == 15) {
                    echo '<div style="margin-left:84%;margin-bottom:6px;" class="btn-group">';
                    echo '<a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">';
                    echo '<i class="fa fa-cogs"></i> Опции темы <span class="caret"></span></a>';
                    
                    echo '<ul class="dropdown-menu">';
                        //Редактирование Темы
                        echo '<li><a href="index.php?do=topics&id='.$id.'&act=editor">';
                        echo '<i class="glyphicon glyphicon-pencil"></i> Изменить тему</a></li>';
                            //Доступ на закрытие темы
                            if($topics['close'] == 1) {
                                echo '<li><a href="index.php?do=topics&id='.$id.'&act=close">';
                                echo '<i class="glyphicon glyphicon-ban-circle"></i> Закрыть тему</a></li>';
                           }elseif($topics['close'] == 2) {
                                echo '<li><a href="index.php?do=topics&id='.$id.'&act=open">';
                                echo '<i class="glyphicon glyphicon-ok-circle"></i> Открыть тему</a></li>';
                            }    
                        
                        //Удаление темы    
                        echo '<li><a href="index.php?do=topics&id='.$id.'&act=delete">';
                        echo '<i class="glyphicon glyphicon-remove"></i> Удалить</a></li>';
                        //Просмотр всей истории в теме
                        echo '<li><a href="index.php?do=topics&id='.$id.'&act=history">';
                        echo '<i class="glyphicon glyphicon-th-large"></i> Вся историю</a></li>'; 
                    echo '</ul></div>';    
                    
                }
                
                //Выводим счетчик тем
                $rtop = $dbase->query("SELECT COUNT(*) as count FROM `forum_post` WHERE `id_top` = ? ",array($id));
                $rowtop = $rtop->fetchArray();
                //Объявляем навигацию
                $newlist = new Navigation($rowtop['count'],15,true);
                
                    //Если счетчик на FALSE выведит 
                    if($rowtop['count'] == FALSE) {
                        echo \engine::error(Lang::__('В данной теме нет постов!'));
                    }else {
                    //Вытаскиваем все посты   
                    $posts = $dbase->query("SELECT * FROM `forum_post` WHERE `id_top` = ? {$newlist->limit()}",array($topics['id']));    
                
                echo '<div class="mainpost">';
                    
                    foreach ($posts->fetchAll() as $post) {
                        //Счетчик постов Пользователей
                        $countu = $dbase->query("SELECT COUNT(*) as count FROM `forum_post` WHERE `id_top` = ? ",array($topics['id']));
                        $ucount = $countu->fetchArray();
                        
                        //Профиль Авторов Постов
                        $profile = \Shcms\Component\Users\User\User::Views(array('avatar','id','nick','lastdate'),$post->id_user);
                        
                        echo '<div class="post_block hentry clear clearfix column_view">';
                        echo '<div class="post_wrap">';
                        
                            //Количество лайков
                            $likem = $dbase->query("SELECT COUNT(*) as count FROM `forum_like` WHERE `id_topic` = ? AND `id_post` = ? AND `like` = 'plus'",array(
                               $id,$post->id 
                            ));
                            $like = $likem->fetchArray();
                            
                            //Количество дислайков
                            $likep = $dbase->query("SELECT COUNT(*) as count FROM `forum_like` WHERE `id_topic` = ? AND `id_post` = ? AND `like` = 'minus'",array(
                               $id,$post->id 
                            ));
                            $dislike = $likep->fetchArray();    
                            
                            //Количество Постов
                            $counts = $dbase->query("SELECT * FROM `forum_post` WHERE `id_top` = ? ",array($topics['id']));
                            
                            //Если авторизован пользователь увидет лайки
                            if($id_user == true) { 
                                $isset  = '<a style="color:green;" href="index.php?do=topics&id='.$id.'&id_post='.$post->id.'&act=plike"><span class="glyphicon glyphicon-thumbs-up"></span> '.$like['count'].'</a>'; 
                                $disisset = '<a style="color:red;" href="index.php?do=topics&id='.$id.'&id_post='.$post->id.'&act=dlike"><span class="glyphicon glyphicon-thumbs-down"></span> '.$dislike['count'].'</a>'; 
                            }else { 
                                $isset = '<font color="green"><span class="glyphicon glyphicon-thumbs-up"></span> '.$like['count'].'</font>'; 
                                $disisset = '<font color="red"><span class="glyphicon glyphicon-thumbs-down"></span> '.$dislike['count'].'</font>'; 
                            } 
                            
                        echo '<div class="user_alf">';
                        echo \Shcms\Component\Users\User\User::Icons($profile['id']);
                        echo '&nbsp;'.$profile['icons'].''.$profile['nick'].'';
                        echo '<span class="right font-weight-1">'.$isset.'&nbsp;&nbsp;'.$disisset.'</span>';
                        echo '</div>';  
                        echo '<div class="author_info">';
                        echo '<ul class="basic_info">';
                            echo '<li class="avatar">';
                            
                            //Путь к аватарам
                            $avatar = '/upload/avatar/' . $profile['avatar'];
                            //Если нет аватара выводим аватар по умолчанию
                            if ($profile['avatar'] == false and file_exists($avatar) == false) {
                                echo '<img src="/engine/template/icons/default_large.png" class="ipsUserPhoto UserPhoto_large">';
                            }else {
                                echo '<img src="' . $avatar . '" class="ipsUserPhoto UserPhoto_large">';
                            }    
                                
                            echo '</li>';
                            
                            echo '<li class="group_title">';
                                echo \user::group($post->id_user);
                            echo '</li>';
                            
                            echo '<li class="post_count desc lighter">';
                                echo "{$ucount['count']} сообщений";
                            echo '</li>';
                       
                        echo '</ul></div>';
                        $data = $tdate->make_date($post->time);
                        echo '<div class="post_body">';
                        echo '<p class="posted_info desc lighter ipsType_small">';
                        echo 'Отправлено <abbr class="published" itemprop="commentTime" title="'.$data.'">'.$data.'</abbr>';
                        echo '</p>';
                        
                        echo '<div itemprop="commentText" class="post entry-content ">';
                        
                        //Файлы пользователя в теме
                        $files = $dbase->query("SELECT * FROM `forum_file` WHERE `id_them` = ? AND `id_post` = ? ", [$id,$post->id]);
                        if($files->num_rows > 0) {
                            echo '<div class="panel-group" id="accordion2">';
                            
                                foreach ($files->fetchAll() as $file) {
                                    echo '<div style="margin-top:4px;" class="panel panel-default">';
                                    echo '<div class="panel-heading">';
                                    echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse'.$file->id.'">';
                                    echo ''.$file->name.'</a></div>';
                                    
                                    echo '<div id="collapse'.$file->id.'" class="accordion-body collapse">';
                                    echo '<div class="panel-body">';
                                    echo \Lang::__('Размер файла').' '.\engine::filesize($file->size).'<br/>';
                                    echo $tdate->make_date($file->time);
                                    echo '<div class="right">'.$file->text.'</div></div>';
                                    echo '</div></div>';
                                }
                            
                            echo '</div>';
                        }
                        
                        echo \engine::input_text($post->text);
                        echo '</div>';
                        echo '<div class="signature" data-memberid="1"><b>'.\Lang::__('Посл. Визит').':</b> '.$tdate->make_date($profile['lastdate']).'</div>';
                        
                        echo '</div></div></div>';
                        
                    }
                
                echo '</div>';
                
                    if($rowtop['count'] > 15) {
                        echo $newlist->pagination('id='.$id.'');
                    }
                }
                
                //Если close 1 то темы закрыта
                if($topics['close'] == 1) {
                    //Если авторизован пользователь
                    if($id_user == true) {
                        echo '<div class="mainname">'.\Lang::__('Новое комментарий').'</div>';
                        echo '<div class="mainpost">';
                            //HTML Форма
                            $form = new Form;
                            //Открываем форму
                            echo $form->open(array('action' => 'index.php?do=topics&id='.$id.'','class' => 'form-horizontal'));
                            //Текст 
                            echo $form->textbox(array('name' => 'text','class' => 'form-control','id' => 'editor'));
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
                            echo '<a class="btn" href="index.php?do=section&id='.$topics['id_sec'].'">Назад</a>';
                             echo '</div></div>';   
                        
                        echo '</div>';
                        
                    }
                }
                
            break;
            
            /**
             * Понравивщиеся посты
             */
            case 'plike':
                
                $idpost = filter_input(INPUT_GET,'id_post',FILTER_SANITIZE_NUMBER_INT);
                $postid = intval($idpost);
                
                $like = $dbase->query("SELECT * FROM `forum_like` WHERE `id_post` = ? AND `id_topic` = ? AND `id_user` = ?",array(
                    $postid,$id,$id_user
                ));
                $likes = $like->fetchArray();
                
                    if($likes['id_user'] != $id_user) {
                        $dbase->insert('forum_like',array(
                            'id_post'   =>  ''.$postid.'',
                            'id_topic'  =>  ''.$id.'',
                            'id_user'   =>  ''.$id_user.'',
                            'like'      =>  'plus'
                        ));
                        
                        header('Location: index.php?do=topics&id='.$id.'');
                    }else {
                        header('Location: index.php?do=topics&id='.$id.'');
                    }
                
            break; 
            
            /**
             * Удаляем Лайки
             */
            case 'dlike':
                
                $idpost = filter_input(INPUT_GET,'id_post',FILTER_SANITIZE_NUMBER_INT);
                $postid = intval($idpost);
                
                $like = $dbase->query("SELECT * FROM `forum_like` WHERE `id_post` = ? AND `id_topic` = ? AND `id_user` = ?",array(
                    $postid,$id,$id_user
                ));
                $likes = $like->fetchArray();
                
                    if($likes['id_user'] != $id_user) {
                        $dbase->insert('forum_like',array(
                            'id_post'   =>  ''.$postid.'',
                            'id_topic'  =>  ''.$id.'',
                            'id_user'   =>  ''.$id_user.'',
                            'like'      =>  'minus'
                        ));
                        
                        header('Location: index.php?do=topics&id='.$id.'');
                    }else {
                        header('Location: index.php?do=topics&id='.$id.'');
                    }
                
            break;      
            
            /**
             * Редактируем Тему
             * 
             */
            case 'editor':
                $error = array();
                //Заголовок
                echo \forum\Topics\Editor\Editor::Etitle($id);
                //Редактирование
                echo \forum\Topics\Editor\Editor::Edit();
                
            break;  
        
            /**
             * Закрываем Тему
             */
            case 'close':
                $error = array();
                //Закрытие темы
                echo \forum\Topics\Editor\Editor::Close(); 
            break; 
        
            /**
             * Открываем Тему
             */
            case 'open':
                $error = array();
                //Открытие темы
                echo \forum\Topics\Editor\Editor::Open(); 
            break; 
        
            /**
             * Удаляем Тему
             */
            case 'delete':
                $error = array();
                //Заголовок
                echo \forum\Topics\Editor\Delete::Dtitle($id);                
                //Открытие темы
                echo \Forum\Topics\Editor\Delete::Delete(); 
            break; 
        
            /**
             * Файлы в теме
             */
            case 'files':
                $error = array();
                //Заголовок
                echo \forum\Topics\Editor\Files::Title($id);             
                //Открытие темы
                echo \forum\Topics\Editor\Files::AddFiles();    
            break;    
        
            /**
             * Если понравилась Тема
             */
            case 'like':
                //Если Авторизован
                if($id_user == true) {
                    //Обработка функции мне нравится    
                    if($id == TRUE and is_numeric($id)) {
                        //Добавим в базу Лайк
                        $dbase->insert('like',array(
                            'id_list'   =>  ''.$id.'',
                            'id_user'   =>  ''.$id_user.'',
                            'action'    =>  ''.intval(2).'',
                            'text'      =>  'Понравилось тема'
                        ));
                       
                        header('Location: index.php?do=topics&id='.$id.'');
                    }
                }else {
                    header('Location: index.php');
                    exit;
                }
                
            break;    
        endswitch;
        
    break;

    /**
     * Добавляем Новый раздел
     * 
     */
    case 'new_section':
        $error = array();
        $templates->template(Lang::__('Создать Новый Раздел'));
        echo \forum\Section\Section::AddSection(array('group' => 'Groups','nav' => 'Nav'));
    break;    

    /**
     * Добавляем Новую Категорию
     * 
     */
    case 'new_category':
        $error = array();
        //Заголовок
        echo \forum\Category\Category::Title();
        //Добавление
        echo \forum\Category\Category::Addcategory(array('group' => 'Groups','nav' => 'Nav'));
    break;

    /**
     * Полная Настройка Категорий
     * 
     */
   case 'setting':
        $error = array();
        $templates->template(Lang::__('Настройка Категорий'));
       
        echo \forum\Category\Category::Setting(array('group' => 'Groups','nav' => 'Nav'));
        //По умолчанию
        switch ($act):
            default:
                echo \forum\Category\Category::AllSetting();
            break;
   
            //Редактирование Категории
            case 'editor':
                $editid = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
                $id = intval($editid);

                    $nulle = $dbase->query("SELECT COUNT(*) as count FROM `forum_category` WHERE `id` = ? ",array($id));
                    $null = $nulle->fetchArray();
                    
                    if($null['count'] == 0) {
                        echo engine::error('Категорий не найдено');
                        exit;
                    }
                    
                echo \forum\Category\Editor\Editor::Edit();
  
            break; 
            
            //Удаляем Категорию
            case 'delete':
                $editid = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
                $id = intval($editid);

                    $nulle = $dbase->query("SELECT COUNT(*) as count FROM `forum_category` WHERE `id` = ? ",array($id));
                    $null = $nulle->fetchArray();
                    
                    if($null['count'] == 0) {
                        echo engine::error('Категорий не найдено');
                        exit;
                    }
                    
                echo \forum\Category\Editor\Delete::Delete();
  
            break;         
            
        endswitch;
    break;         

    
    /**
     * Настройка Всех Разделов
     * 
     */
    case 'rsetting':
        //Массив Ошибок
        $error = array();
        //Заголовок
        $templates->template(Lang::__('Настройка Разделов'));
        //Настройка Управления
        echo \forum\Section\Section::Setting(array('group' => 'Groups','nav' => 'Nav'));
        //Путь к Дальним Настройкам
        switch ($act):
            default:
                //Все данные по Категориям
                echo \forum\Section\Section::AllSetting();
            break;
   
            /**
             * Редактирование Имени Категорий
             */
            case 'editor':
                $editid = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
                $id = intval($editid);
                    //Проверяем Существует ли категория
                    $nulle = $dbase->query("SELECT COUNT(*) as count FROM `forum_subsection` WHERE `id` = ? ",array($id));
                    $null = $nulle->fetchArray();
                    
                    if($null['count'] == 0) {
                        echo engine::error('Категорий не найдено');
                        exit;
                    }
                    
                echo \forum\Section\Editor\Editor::Edit();
  
            break; 
            
            case 'delete':
                $editid = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
                $id = intval($editid);

                    $nulle = $dbase->query("SELECT COUNT(*) as count FROM `forum_subsection` WHERE `id` = ? ",array($id));
                    $null = $nulle->fetchArray();
                    
                    if($null['count'] == 0) {
                        echo engine::error('Категорий не найдено');
                        exit;
                    }
                    
                echo \forum\Section\Editor\Delete::Delete();
  
            break;             
        endswitch;
    break;
    
    /**
     * Раздел Моих Тем
     */
    case 'mthem':
	if($id_user == true) {
            echo \forum\Category\Other\MyAccount::ThemPost('them');
	}else {
	    header('Location: index.php');
	}	
    break;

    /**
     * Раздел Моих Постов
     */
    case 'mpost':
	if($id_user == true) {
            echo \forum\Category\Other\MyAccount::ThemPost('post');
	}else {
	    header('Location: index.php');
	}	
    break;	
    
    /**
     * Раздел Поиска Тем
     */
    case 'search':
	if($id_user == true) {
            echo \forum\Category\Other\MyAccount::Search('search');
	}else {
	    header('Location: index.php');
	}	
    break;
    
    /**
     * Раздел Рейтинга Форума
     */
    case 'rating':
	if($id_user == true) {
            echo \forum\Category\Other\MyAccount::Rating('rating');
	}else {
	    header('Location: index.php');
	}
    break;    
    
    
endswitch;
