<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Новости')); //Название страницы
	
$tdate = new Shcms\Component\Data\Bundle\DateType();

switch($do):
    //По умолчанию выводим основные параметры новостей
    default:
        //Если есть хоть одна категория начинаем добавлять новость
        $ncategory = $db->query("SELECT * FROM `news_category`");
        
        echo '<div style="text-align:right;margin-bottom:6px;">';
         echo '<div class="btn-group">';
            if($db->num_rows($ncategory) > 0 and $users['group'] == 15) {
                $aview .= '<a class="btn btn-success" href="/admin/news/index.php?do=nnews">';
                $aview .= Lang::__('Опубликовать новость').'</a>';
                
                echo $aview;
            }
            echo '<a class="btn btn-info" href="?do=input_search">';
            echo Lang::__('Поиск Новостей');
            echo '</a>';
            echo '<a class="btn btn-info" href="?do=category">';
            echo Lang::__('Категории');
            echo '</a>'; 
            echo '</div></div>';
			
	//Выводим счетчик постов
        $row = $db->get_array($db->query("SELECT COUNT(*) FROM `news`")); 
        $newlist = new Navigation($row[0],25, true); 
	    //Если писем больше 1 выводит из базы данные
            if($row[0] > 0) {
	        $query = $db->query("SELECT * FROM `news` ORDER BY `id` DESC ". $newlist->limit()."");
	    }else {
                echo engine::warning('Новостей не найдено');
		exit;
	    }		

        while($news = $db->get_array($query)) {
            //Определяем ник
	    $nick = $user->users($news['id_user'],array('nick'),false);
	    //Определяем id
            $id_users = $user->users($news['id_user'],array('id'));
	    //Определяем раздел новости
	    $views = $db->get_array($db->query("SELECT * FROM `news_category` WHERE `id` = '".$news['id_cat']."'"));
	    //Выводим счетчик постов
            $row1 = $db->get_array($db->query("SELECT COUNT(*) FROM `news_comment` WHERE `id_news` = '".$news['id']."'"));

                if($users['group'] == 15) {
		    $resulte  = '&nbsp;<a href="/admin/news/index.php?do=enews&id='.$news['id'].'">';
                    $resulte .= '<img src="/engine/template/icons/edit.png"></a>';
		    $resulte .= '&nbsp;<a href="setting.news.php?act=delete_sec&id='.$news['id'].'">';
                    $resulte .= '<img src="/engine/template/icons/delete.png"></a>';
		}
                
            if($news['noall'] == 0 or $news['addnews'] == 0) {
            //Проверяем
                echo '<div class="mainname">'.$news['title'].'</b><span class="time">'.$resulte.'</span></div>';
                echo '<div class="mainpost">';

 	    //Выводи все записанные данные
            echo '<div class="details">';
            echo '<span><img src="/engine/template/icons/folder.png">&nbsp;';
            echo '<a style="color:#1E90FF;" href="category.php?id='.$views['id'].'">'.$views['name'].'</a>';
            echo '</span>';
	    echo '<span><img src="/engine/template/icons/author.png">&nbsp;';
            if($news['anonim'] == 1) {
                echo '<a style="color:#1E90FF;" href="#">Анонимный</a>';
            }else {
                echo '<a style="color:#1E90FF;" href="'.MODULE.'profile.php?act=view&id='.$id_users.'">'.$nick.'</a>';
            }
            echo '</span>';
	    echo '<span><img src="/engine/template/icons/eye.png">&nbsp;';
            echo '<font color="#778899">'.engine::number($news['view']).'</font></span>';
	    echo '<span><img src="/engine/template/icons/comment.png">&nbsp;';
            echo '<a style="color:#1E90FF;" href="view.php?id='.$news['id'].'">'.engine::number($row1[0]).' Комментарий</a>';
            echo '</span>';
            
	    echo '<span><img src="/engine/template/icons/date.png">&nbsp;';
            echo $tdate->make_date($news['time']);
            echo '</span>';   
            
            echo '</div><br/>';
            echo '<div class="row">
			    <span class="col-md-3 col-xs-3">
                    <img class="img-thumbnail" height="200" src="/upload/news/image/'.$news['image'].'">
                </span>';
            
	    echo '<div class="col-md-9 col-xs-9"><p>'.engine::input_text($news['cr_news']).'</p></div>';  
            echo '<div class="col-md-12 col-xs-12">';
            echo '<a class="btn right" href="view.php?id='.$news['id'].'">Читать дальше &raquo;</a>';
            
            echo '</div></div>';
            
            echo '</div>';
            
            
            }
        }
        //Вывод навигации
        echo $newlist->pagination(); 
        
                echo engine::home(array('Назад','/index.php')); //Переадресация
    
    break;

    //Выводим все категории
    case 'category':

        
        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Новости</a>
            <a href="index.php?do=category" class="btn btn-default disabled">Категории</a>
        </div>';
        
	echo '<div class="mainname">Все категории</div>';
	echo '<ul class="mainpost">';
	    $lcat = $db->query( "SELECT * FROM `news_category`" );
                //Вывод всех категорий          
		while($category = $db->get_array($lcat)) { 
                    //Счетчик новостей в категории
		    $cont = $db->get_array($db->query("SELECT COUNT(*) FROM `news` WHERE `id_cat` = '".intval($category['id'])."'"));
			
                        echo '<li><a href="category.php?id='.intval($category['id']).'"></li>';
                        echo '<li class="clearfix row3">';
                        echo '<img src="/engine/template/icons/list.png"> ';
                        echo $category['name'];
                        echo '<div class="time">'.$cont[0].'</div>';
                        echo '</li></a>';
		}
 	echo '</ul>';
        
                echo engine::home(array('Назад','index.php')); //Переадресация
    break;
    
    //Форма для поиска новостей
    case 'input_search':
        
        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Новости</a>
            <a href="#" class="btn btn-default disabled">Поиск новостей</a>
        </div>';
        
        //Заголовок
	echo '<div class="mainname">'.Lang::__('Поиск Новостей').'</div>';
            echo '<div class="mainpost">';           
               //Открываем форму
                echo '<form action="index.php?do=search" class="navbar-form" role="search" method="post">';
                    echo '<div class="input-group col-sm-6 col-md-4 col-xs-4">';
                        //Поле для поиска
                        echo '<input type="search" id="container-search" class="form-control" placeholder="Найти новость" name="search">';
                            echo '<div class="input-group-btn">';
                                //Кнопка
                                echo '<button class="btn btn-default" type="submit">';
                                echo '<i class="glyphicon glyphicon-search"></i>';
                                echo '</button>';
                            echo '</div>';
                    echo '</div>';
                //Закрываем форму    
                echo '</form>';
	    echo '</div>';
            
            echo '<div id="searchlist" class="list-group">';
            
             $seanews = $db->query("SELECT * FROM `news`");
              while($search = $db->get_array($seanews)) {
                    echo '<div class="list-group-item">';
                    echo '<div class="row_3">';
                    echo '<a href="/modules/news/view.php?id='.$search['id'].'"><b>'.$search['title'].'</b></a>';
                    echo '</div>';
                    echo '<div class="row_3">';
                    echo engine::input_text($search['cr_news']);
                    echo '</div>';
                    echo '</div>';
              }
            echo '</div>';

    break;

    //Обработка поиска и получение данных
    case 'search':

        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Новости</a>
            <a href="index.php?do=input_search" class="btn btn-default">Поиск новостей</a>
            <a href="#" class="btn btn-default disabled">Результат поиска</a>
        </div>';
        
        //Функция поиска
	function search($query) { 
	    global $db,$user,$tdate;
	
	$query = engine::trim($query); 
    	$query = $db->safesql($query);
    	$query = htmlspecialchars($query);
    	
        if (!empty($query)) { 
            if (strlen($query) < 3) {
            	$text = '<p>Слишком короткий поисковый запрос.</p>';   
            } else if (strlen($query) > 128) {
            	$text = '<p>Слишком длинный поисковый запрос.</p>';
            } else { 
            	$q = "SELECT * FROM `news` WHERE `title` LIKE '%$query%'";
            	$result = $db->query($q);

            if ($db->num_rows($result) > 0) { 
                $row = $db->get_array($result); 
                $num = $db->num_rows($result);
	        
                echo engine::warning('По запросу <b>'.$query.'</b> найдено совпадений: '.$num);
                	
                    do {
                    	// Делаем запрос, получающий ссылки на статьи
                    	$q1 = "SELECT * FROM `news` WHERE `id` = '$row[id]'";
                    	$result1 = $db->query($q1);
                            if ($db->num_rows() > 0) {
                        	$row1 = $db->get_array($result1);
                   	    }
                            //Определяем ник
		            $nick = $user->users($row['id_user'],array('nick'),false);
		            //Определяем id
                            $id_users = $user->users($row['id_user'],array('id'));
		            //Определяем раздел новости
		            $views = $db->get_array($db->query("SELECT * FROM `news_category` WHERE `id` = '".$row['id_cat']."'"));
		            //Выводим счетчик постов
                            $row1 = $db->get_array($db->query("SELECT COUNT(*) FROM `news_comment` WHERE `id_news` = '".$row['id']."'"));
                        
            //Проверяем
                echo '<div class="mainname">'.engine::search_text($query,$row['title']).'</b></div>';
                echo '<div class="mainpost">';

 	    //Выводи все записанные данные
            echo '<div class="details">';
            echo '<span><img src="/engine/template/icons/folder.png">&nbsp;';
            echo '<a style="color:#1E90FF;" href="category.php?id='.$views['id'].'">'.$views['name'].'</a>';
            echo '</span>';
	    echo '<span><img src="/engine/template/icons/author.png">&nbsp;';
            if($row['anonim'] == 1) {
                echo '<a style="color:#1E90FF;" href="#">Анонимный</a>';
            }else {
                echo '<a style="color:#1E90FF;" href="'.MODULE.'profile.php?act=view&id='.$id_users.'">'.$nick.'</a>';
            }
            echo '</span>';
	    echo '<span><img src="/engine/template/icons/eye.png">&nbsp;';
            echo '<font color="#778899">'.engine::number($row['view']).'</font></span>';
	    echo '<span><img src="/engine/template/icons/comment.png">&nbsp;';
            echo '<a style="color:#1E90FF;" href="view.php?id='.$row['id'].'">'.engine::number($row1[0]).' Комментарий</a>';
            echo '</span>';
            
	    echo '<span><img src="/engine/template/icons/date.png">&nbsp;';
            echo $tdate->make_date($row['time']);
            echo '</span>';   
            
            echo '</div><br/>';
            echo '<div class="row">
			    <span class="col-md-3 col-xs-3">
                    <img class="img-thumbnail" height="200" src="/upload/news/image/'.$row['image'].'">
                </span>';
            
	    echo '<div class="col-md-9 col-xs-9"><p>'.engine::input_text($row['cr_news']).'</p></div>';  
            echo '<div class="col-md-12 col-xs-12">';
            echo '<a class="btn right" href="view.php?id='.$row['id'].'">Читать дальше &raquo;</a>';
            
            echo '</div></div>';
            
            echo '</div>';
            
                        

                    }while ($row = $db->get_array($result));
															
            } else {
		$text = '<p>По вашему запросу ничего не найдено.</p>';
            }
        } 
    } else {
        $text = '<p>Задан пустой поисковый запрос.</p>';
    }    
    	return $text; 
    } 
    $search = filter_input(INPUT_POST,'search',FILTER_SANITIZE_SPECIAL_CHARS);
	if (!empty($search)) { 
    		$search_result = search ($search); 
    		echo $search_result; 
	    }else {
		echo engine::error('Введите название новостя');
	    }	
            
    break;
	
endswitch;
	
