<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

        
        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Загрузки</a>
            <a href="#" class="btn btn-default disabled">Поиск файлов</a>
        </div>';
        
        //Заголовок
	echo '<div class="mainname">'.Lang::__('Поиск Файлов').'</div>';
            echo '<div class="mainpost">';           
               //Открываем форму
                echo '<form action="?act=search" class="navbar-form" role="search" method="post">';
                    echo '<div class="input-group col-sm-6">';
                        //Поле для поиска
                        echo '<input type="search" id="container-search" class="form-control" placeholder="Поиск файлов" name="search">';
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
            
            $seafile = $db->query("SELECT * FROM `files`");
            if ($db->num_rows($seafile) > 0) { 
                while($search = $db->get_array($seafile)) {
                    echo '<div class="list-group-item">';
                    echo '<div class="row_3">';
                    echo '<a href="/modules/forum/post.php?id='.$search['id'].'"><b>'.$search['name'].'</b></a>';
                    echo '</div>';
                    echo '<div class="row_3">';
                    echo engine::input_text($search['text']);
                    echo '</div>';
                    echo '</div>';
                }
            }else {
                echo engine::warning('Файлов не найдено');
            }   
            echo '</div>';
            