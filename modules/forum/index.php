<?php

define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Форум')); //Название страницы

    
//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();
    
//Отключения форума
$off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
    if($off_forum['off_forum'] == 1) {
	echo engine::error(Lang::__('Форум приостановлен с ').$tdate->make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
	echo engine::home(array('Назад','/index.php'));	 
	exit;
    }
    
switch($do): 
    //По умолчанию выводим основные данные
    default;
	//Выводим счетчик категорй
        $row = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_category`"));
        //Если нет категорий то никуда не получится попасть больше
        if($row[0] == false) {
	    echo '<div class="mainname">Отсутствие категорий</div>';
	    echo '<div class="mainpost">';
            
	    if($users['group'] == 15) {
                echo engine::error(Lang::__('Категорий не найдено.'));
                echo '<center><a class="btn btn-success" href="new.category.php">Создать категорию</a></center>';
	    }else {
		echo engine::error(Lang::__('В форуме не найдено категорий'));
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
                    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="new.category.php"><i class="glyphicon glyphicon-plus"></i>&nbsp;Новая категория</a></li>';
                    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="new.section.php"><i class="glyphicon glyphicon-plus"></i>&nbsp;Новый раздел</a></li>';
                    //Разделяем
                    $sview .= '<li class="divider"></li>';
                    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="setting.category.php"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Настройки категории</a></li>';
                    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="setting.section.php"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Настройка раздела</a></li>';
                $sview .= '</ul></div>';
                $sview .= '</div>';
            }
            
                //Заносим в $ и получаем все данные по разделу
	        $sview .= '<div class="mainname">'.Lang::__('Разделы').'</div>';
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
                        $sview .= '<a href="section.php?id='.$subrazd['id'].'"><b>'.engine::ucfirst($subrazd['name']).'</b></a>';
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
		    echo engine::error(Lang::__('В категории отсутствуют разделы'));
		}
            	
            }
            echo '</div>';
        //Переадресация на пред. страницу	
	echo engine::home(array('Назад','/index.php'));	
        
    break;

    //Мои темы
    case 'mthem':
        //Если пользователь авторизован то даем доступ
	if($id_user == true) {
	    include_once('core/mthem.php');
	}else {
	    header('Location: index.php');
	}
			
    break;

    //Мои посты
    case 'mpost':
        //Если пользователь авторизован то даем доступ        
	if($id_user == true) {
	    include_once('core/mpost.php');
	}else {
	    header('Location: index.php');
	}
			
    break;	
    //Поиск тем
    case 'search':
        //Если пользователь авторизован то даем доступ        
	if($id_user == true) {
	    include_once('core/search.php');
	}else {
	    header('Location: index.php');
	}
			
    break;
    
    case 'rating':
        if($id_user == true) {
            include_once('core/rating.php');
        }else {
            header('Location: index.php');
        }
    break;    
    
endswitch;
