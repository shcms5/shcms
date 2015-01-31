<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

switch($act):
	
    default:
        
        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Форум</a>
            <a href="#" class="btn btn-default disabled">Поиск тем</a>
        </div>';
        
        //Заголовок
	echo '<div class="mainname">'.Lang::__('Поиск Тем').'</div>';
            echo '<div class="mainpost">';           
               //Открываем форму
                echo '<form action="?do=search&act=search" class="navbar-form" role="search" method="post">';
                    echo '<div class="input-group col-sm-6">';
                        //Поле для поиска
                        echo '<input type="search" id="container-search" class="form-control" placeholder="Поиск тем" name="search">';
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
            
             $seatop = $db->query("SELECT * FROM `forum_topics`");
              while($search = $db->get_array($seatop)) {
                    echo '<div class="list-group-item">';
                    echo '<div class="row_3">';
                    echo '<a href="/modules/forum/post.php?id='.$search['id'].'"><b>'.$search['name'].'</b></a>';
                    echo '</div>';
                    echo '<div class="row_3">';
                    echo engine::input_text($search['text']);
                    echo '</div>';
                    echo '</div>';
              }
            echo '</div>';
            
			
        break;
			
	case'search':
            //Навигация Вверхняя
            echo '<div class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="index.php" class="btn btn-default">Форум</a>
                <a href="index.php?do=search" class="btn btn-default">Поиск тем</a>
                <a href="#" class="btn btn-default disabled">Результат поиска</a>
            </div>';
            
                function search($query) { 
	            global $db,$user;
					
    			$query = trim($query); 
    			$query = $db->safesql($query);
    			$query = htmlspecialchars($query);
    				
			if (!empty($query)) { 
        		    if (strlen($query) < 3) {
            			$text = '<p>Слишком короткий поисковый запрос.</p>';
        		    } else if (strlen($query) > 128) {
            			$text = '<p>Слишком длинный поисковый запрос.</p>';
        		    } else { 
            			$q = "SELECT `id`,`name` FROM `forum_topics` WHERE `name` LIKE '%$query%'";
            			$result = $db->query($q);

            		if ($db->num_rows($result) > 0) { 
                	    $row = $db->get_array($result); 
                	    $num = $db->num_rows($result);

                		echo engine::warning('По запросу <b>'.$query.'</b> найдено совпадений: '.$num.'');
                                
                                $text .= '<div class="mainpost">';
                                $text .= '<table class="itable"></tbody>';
                		
                            do {
                    		    // Делаем запрос, получающий ссылки на статьи
                    		    $q1 = "SELECT * FROM `forum_topics` WHERE `id` = '$row[id]'";
                    		    $result1 = $db->query($q1);

                    	            if ($db->num_rows() > 0) {
                                        $row1 = $db->get_array($result1);
                   	            }
                        
		                    //Получаем счетчик тем
                                    $rows = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_post` WHERE `id_top` = '".$row1['id']."'"));
                                    //Выводим Ник пользователя
                                    $nick = $user->users($row1['id_user'],array('nick'));	
                                    //Вывод Всех определенных данных
                                    $text .= '<tr class="">';
		                    //Если тема закрыта выводит иконку
	                            if($row1['close'] == 2) {
		                        $text .= '<td class="c_icon">';
                                        $text .= '<img src="/engine/template/icons/locked.png">';
	                                $text .= '</td>';
                                    }else {
                                        $text .= '<td class="c_icon">';
                                        $text .= '<img src="/engine/template/icons/nthem.png">';
	                                $text .= '</td>';
                                    }
		
                                //Вывод данных из базы
                                $text .= '<td class="c_forum">';
                                //Название тем
                                $text .= '<a href="post.php?id='.$row1['id'].'"><b>'.engine::ucfirst(engine::search_text($query,$row1['name'])).'</b></a>';
                                //Параметры темы
		                $text .= '<p class="desc">';
		                $text .= Lang::__('Автор:').'&nbsp;'.$nick.'&nbsp;,';
                                $text .= date::make_date($row1['time']).'</p></td>';
                                //Автор темы и Время создания
                                $text .= '<td class="c_stats"><ul>';
		                $text .= '<li><b>'.$rows[0].'</b>&nbsp;'.Lang::__('Ответов').'</li>';
                                $text .= '<li><b>'.$topic['views'].'</b>&nbsp;'.Lang::__('Просмотров').'</li>';
		                $text .= '</ul></td>';
                                $text .= '</tr>';
                			
                               
                            } while ($row = $db->get_array($result));
            		} else {
                	    $text = engine::error('По вашему запросу ничего не найдено.');
            		}
        	    } 
                        $text .= '</tbody></table></div>';
    		} else {
        	    $text = engine::error('Задан пустой поисковый запрос.');
    		}
    	    return $text; 
	} 
			
			
        if (!empty($_POST['search'])) { 	
            $search_result = search ($_POST['search']); 
    	        echo $search_result; 
	}else {
	    echo engine::error('Введите название файла в поиске');
	}
			
			
        break;
		
    endswitch;