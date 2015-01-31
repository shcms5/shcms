<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}

    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Форум</a>
            <a href="#" class="btn btn-default disabled">Рейтинг пользователей</a>
        </div>';


    echo '<div class="mainname">'.Lang::__('Рейтинг пользователей форума').'</div>';

        $ratings = $db->query("SELECT * FROM `users` WHERE `frating` > '0' ORDER BY `frating` DESC");
            while($rating = $db->get_array($ratings)) {
                
                echo '<div class="posts_gl">';
                echo '<div class="row">';
                echo '<div class="col-md-3 col-xs-3">';
                echo '<a class="btn btn-default" href="">'.$rating['nick'].'</a></div>';
                 echo '<div class="col-md-8 col-xs-8">';
                echo '<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.$rating['frating'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$rating['frating'].'%">
    Рейтинг&nbsp; '.engine::number($rating['frating'],',').'%
  </div>
</div></div>';
                echo '</div></div>';
                
            }