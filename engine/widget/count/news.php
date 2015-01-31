<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

//Выводим счетчик постов
$row = $db->get_array($db->query("SELECT COUNT(*) FROM `news`")); 
//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();

echo '<div class="mainname">Свежие новости';
echo '<span class="right"><a href="/modules/news/">Новости</a></span>';
echo '</div>';

//Если писем больше 1 выводит из базы данные
$query = $db->query("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 6");	
            
    if($db->num_rows($query) > 0) {        
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
                echo '<div class="posts_gl">';
            if($news['noall'] == 0 or $news['addnews'] == 0) {
            //Проверяем
                echo '<div class="nsize"><a href="/modules/news/view.php?id='.$news['id'].'">'.$news['title'].'</a>'.$resulte.'</div>';
                
 	    //Выводи все записанные данные
            echo '<div class="details">';
            echo '<span><img src="/engine/template/icons/folder.png">&nbsp;';
            echo '<a style="color:#1E90FF;" href="/modules/news/category.php?id='.$views['id'].'">'.$views['name'].'</a>';
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
            echo '<a style="color:#1E90FF;" href="/modules/news/view.php?id='.$news['id'].'">'.engine::number($row1[0]).' Комментарий</a>';
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
            echo '<a class="btn right" href="/modules/news/view.php?id='.$news['id'].'">Читать дальше &raquo;</a>';
            
            echo '</div></div>';
         
      
            
            
            }
            echo '</div>';
        }
    }else {
        echo '<div class="mainpost">';
        echo engine::warning('Новостей не найдено.');
        echo '</div>';
    }    
