<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

$tdate = new Shcms\Component\Data\Bundle\DateType();

//Обработка полученного ID
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$id = intval($id);

//Если вместо id num попытаются вставить текст то выводит ошибку
if (!isset($id) || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

    //Выводим название с базы для title
    $title = $db->get_array($db->query("SELECT * FROM `news_category` WHERE `id` = '".$id."'"));
     //Название страницы
    $templates->template(Lang::__('Новости').' - '.$title['name']);


        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Новости</a>
            <a href="index.php?do=category" class="btn btn-default">Категории</a>
            <a href="#" class="btn btn-default disabled">'.$title['name'].'</a>
        </div>';
    
		
    //Выводим счетчик тем
    $row = $db->get_array($db->query("SELECT COUNT(*) FROM `news` WHERE `id_cat` = '".$id."'"));

        
    $newlist = new Navigation($row[0],10,true);
            
        //Если счетчик на 0 выведит
	if($row[0] == false) {
	    echo engine::error('В данном категории нет новостей!');
	    exit;
	}
        
	//Начинаем вывод всех тем с базы
	$newsq = $db->query("SELECT * FROM `news` WHERE `id_cat` = '".$id."' ORDER BY `id` DESC ". $newlist->limit()."");
	
            //Проверяем если ли разделы в базе если да то выводит всех
			if($db->num_rows($newsq) > 0) { 
                while($news = $db->get_array($newsq)) {
				//Вывод ника
                $nick = $user->users($news['id_user'],array('nick'),false);
			    //Вывод id
                $id_users = $user->users($news['id_user'],array('id'));
		//Выводим счетчик категорй
                $row1 = $db->get_array($db->query("SELECT COUNT(*) FROM `news_comment` WHERE `id_news` = '".$news['id']."'"));

            //Проверяем
                echo '<div class="mainname">'.$news['title'].'</b></div>';
                echo '<div class="mainpost">';

 	    //Выводи все записанные данные
            echo '<div class="details">';
            echo '<span><img src="/engine/template/icons/folder.png">&nbsp;';
            echo '<a style="color:#1E90FF;" href="category.php?id='.$title['id'].'">'.Lang::__($title['name']).'</a>';
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
            echo '<a style="color:#1E90FF;" href="view.php/'.$news['id'].'">'.engine::number($row1[0]).' Комментарий</a>';
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
            echo '<a class="btn right" href="view.php/'.$news['id'].'">Читать дальше &raquo;</a>';
            
            echo '</div></div>';
            
            echo '</div>';
            
                }
				//Вывод навигации
                echo $newlist->pagination('id='.$id.'');
			//Если нет разделов то выводит это
            }else {
                echo engine::error('В данном категории нет разделов!');
            }
			
		//Переадресация на пред. страницу	
		echo engine::home(array('Назад','index.php'));	 			