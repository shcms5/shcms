<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Загрузки</a>
            <a href="index.php?act=search_file" class="btn btn-default">Поиск файлов</a>
            <a href="#" class="btn btn-default disabled">Результат поиска</a>
        </div>';

    function search($query) { 
	global $db;
    			
	$query = trim($query); 
    	$query = $db->safesql($query);
    	$query = htmlspecialchars($query);
    	    if (!empty($query)) { 
		if (strlen($query) < 3) {
		    $text = '<p>Слишком короткий поисковый запрос.</p>';
		} elseif (strlen($query) > 128) {
		    $text = '<p>Слишком длинный поисковый запрос.</p>';
		} else { 
		    $q = "SELECT `id`,`name` FROM `files` WHERE `name` LIKE '%$query%'";
            	    $result = $db->query($q);

            	if ($db->num_rows($result) > 0) { 		
                    $row = $db->get_array($result); 
                    $num = $db->num_rows($result);
                    echo engine::warning('По запросу <b>'.$query.'</b> найдено совпадений: '.$num.'');
                    $text .= '<div class="mainpost">';
		    $text .= '<ul class="List_withminiphoto Pad_list">';
			do {
                    	    // Делаем запрос, получающий ссылки на статьи
                    	    $q1 = "SELECT * FROM `files` WHERE `id` = '$row[id]'";
                    	    $result1 = $db->query($q1);
                    	        if ($db->num_rows() > 0) {	
                        	    $row1 = $db->get_array($result1);			
                   		}

			    $text .= '<li class="clearfix">';
			    //Путь к картинке
			    $text .= '<a href="" title="Просмотр профиля" class="UserPhotoLink left"><img src="/engine/template/down/'.engine::format($row1['files']).'.png" class="UserPhoto UserPhoto_mini"></a>';	
			    //Файл выводит
			    $text .= '<div class="list_content"><a href="view.php?id='.$row1['id'].'"><b>'.engine::search_text($query,$row1['name']).'</b><span class="time">Просмотров:'.engine::number($row1['count']).'</span></a>';
			    //Описание
			    if(!$row1['text2']) {
				$text .= '<br><span class="desc lighter">'.Lang::__('Не добавлено описание к файлу').'</span></div>';
			    }else {
				$text .= '<br><span class="desc lighter">'.engine::input_text(engine::string($row1['text2'],500)).'</span></div>';
			    }
				
				$text .= '</li><hr/>';	
					
                	} while ($row = $db->get_array($result));
							
                    $text .= '</ul></div>';	
						
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
        echo '<div class="mainname">'.Lang::__('Поиск пустой').'</div>';
        echo '<div class="mainpost">';
	echo engine::error('Введите название файла в поиске');
        echo '</div>';
}
