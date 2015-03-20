<?php
/**
 * Класс Для работы с Параметрами Форума
 * 
 * @package            Forum/Category/
 * @author             Shamsik
 * @link               http://shcms.ru
 */

namespace Forum\Category\Other;

class MyAccount {
    
    /**
     * Получение навигации
     * 
     * @param Nav($param = array())
     */    
    public static function Nav($param = false) {
        global $templates;
        
        //Получаем Заголовок по данным
        if($param == 'them') {
            $templates->template(\Lang::__('Мои темы'));
        }elseif($param == 'post') {
            $templates->template(\Lang::__('Мои Посты'));
        }elseif($param == 'rating') {
            $templates->template(\Lang::__('Рейтинг пользователей'));
        }elseif($param == 'search') {
            $templates->template(\Lang::__('Поиск пользователей'));
        }
        
        //Навигация Вверхняя
        $navi = '<div class="btn-group btn-breadcrumb margin-bottom">';
        $navi .= '<a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>';
        $navi .= '<a href="index.php" class="btn btn-default">'.\Lang::__('Форум').'</a>';
        
            //Данные по выбору (Темы,Посты)
            if($param == 'them') {
                $navi .= '<a href="#" class="btn btn-default disabled">'.\Lang::__('Мои Темы').'</a>';
            }elseif($param == 'post') {
                $navi .= '<a href="#" class="btn btn-default disabled">'.\Lang::__('Мои Посты').'</a>';
            }elseif($param == 'rating') {
                $navi .= '<a href="#" class="btn btn-default disabled">'.\Lang::__('Рейтинг пользователей').'</a>';
            }elseif($param == 'search') {
                $navi .= '<a href="#" class="btn btn-default disabled">'.\Lang::__('Поиск пользователей').'</a>';
            }
            
        $navi .= '</div>';
        
        return $navi;

    }
    
    /**
     * Выводим Данные Из Форума
     * 
     * @param ThemPost($param = array())
     */
    public static function ThemPost($param = false) {
        global $dbase,$id_user,$users;
        
        //Если вызывает Посл. Темы
        if($param == 'them') {
            //Навигация
            echo self::Nav('them');
            //Таблица в базе
            $row = 'forum_topics';
            //Текст
            $text = 'Темы, созданные: '.$users['nick'];
        }elseif($param == 'post') {
            //Если вызывает Посл. Посты
            //
            //Навигация
            echo self::Nav('post');
            //Таблица в базе
            $row = 'forum_post';
            //Текст
            $text = 'Посты, созданные: '.$users['nick'];            
        }
            //Получаем Счетчик по выбору
            $counts = $dbase->query("SELECT COUNT(*) as count FROM `{$row}` WHERE `id_user` = ? ",array($id_user));
            $count = $counts->fetchArray();
            
            //Выводим Текст определенный
            echo '<div class="mainname">'.$text.'</div>';
            
        //Объявляем Навигацию
        $newlist = new \Navigation($count['count'],10,true);
        
        //Если найдены посты или темы то выводим
        if($count['count'] > 0) {
            $results = $dbase->query("SELECT * FROM `{$row}` WHERE `id_user` = ? ORDER BY `id` DESC ".$newlist->limit()."",array($id_user));
        }else {
	    echo \engine::error('Данные не доступны или не найдено');
	    exit;
	}
        
        //Вывод всех данных из базы
        foreach ($results->fetchAll() as $result) {
            //Если из темы то добавляем ответы
            if($param == 'them') {
                $otvets = $dbase->query("SELECT COUNT(*) as count FROM `forum_post` WHERE `id_top` = ? ",array($result->id));
                $otvet = $otvets->fetchArray();
                $view = '<div class="right">Ответов: '.$otvet['count'].'</div>';
            }
            //И выводим остальные данные
            echo  '<div class="posts_gl">';
            echo  '<table cellspacing="0" callpadding="0" width="100%"><tr>'; 
                //Иконка
                echo  '<td class="icons"><img src="/engine/template/icons/fol_txt.png"></td>';
                //Заголовок
                echo '<td class="name" colspan="10">';
                echo '<b> <a href="index.php?do=topics&id='.$result->id.'">'.\engine::ucfirst($result->name).'</a></b>';
                echo $view.'</td></tr>';
                //Текст
                echo '<tr><td class="content" colspan="10">'.\engine::input_text($result->text).'</td>';
            echo '</tr></table>';
            echo '</div>';
        }
        
    }
    
    /**
     * Рейтинг Пользователей Из Форума
     * 
     * @param Rating($param = array())
     */    
    public static function Rating($param = 'rating') {
        global $dbase;
        
        //Навигация
        echo self::Nav($param);
        //Заголовок
        echo '<div class="mainname">'.\Lang::__('Рейтинг пользователей форума').'</div>';
        
        //Получаем данные о выводе Рейтинга
        $ratingp = $dbase->query("SELECT * FROM `users` WHERE `frating` > ? ORDER BY `frating` DESC",array(intval(0)));
        if($ratingp->num_rows > 0) {
            //Выводим Рейтинг
            foreach ($ratingp->fetchAll() as $rating) {
                echo '<div class="posts_gl"><div class="row">';
                echo '<div class="col-md-3 col-xs-3">';
                //Имя Участника
                echo '<button class="btn btn-info disabled">'.$rating->nick.'</button></div>';
                //Его статус Постов
                echo '<div class="col-md-8 col-xs-8"><div class="progress">';
                echo '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.$rating->frating.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$rating->frating.'%">';
                echo 'Рейтинг&nbsp; '.\engine::number($rating->frating,',').'%</div></div></div></div></div>';            
            }
        }else {
            //Если пусто
            echo '<div class="mainpost">';
            echo \engine::warning(\Lang::__('В рейтинг никто не попал'));
            echo '</div>';
        }
    }
    
    /**
     * Поиск Тем и Постов Из Форума
     * 
     * @param Rating($param = array())
     */    
    public static function Search($param = 'search') {
        global $dbase;
        
        //Навигация
        echo self::Nav($param);
        //Заголовок
        echo '<div class="mainname">'.\Lang::__('Поиск Тем и Постов').'</div>';
        
        //Данные для поиска
        echo '<div class="mainpost">';
        //Открываем форму
        echo '<form class="navbar-form" role="search" method="post">';
        echo '<div class="input-group col-sm-6">';
        //Поле для поиска
        echo '<input type="search" id="container-search" class="form-control" placeholder="Поиск тем" name="search">';
        echo '<div class="input-group-btn">';
            echo '<button class="btn btn-default" type="submit">';
            echo '<i class="glyphicon glyphicon-search"></i>';
            echo '</button>';
        echo '</div></div></form></div>';    
        
        //Вывод все тем И делаем по ним поиск
        echo '<div id="searchlist" class="list-group">';
        
        $topics = $dbase->query("SELECT * FROM `forum_topics`");
            //Произведем вывод
            foreach ($topics->fetchAll() as $topic) {
                echo '<div class="list-group-item">';
                echo '<div class="row_3">';
                //Путь к теме и Название
                echo '<a href="index.php?do=topics&id='.$topic->id.'"><b>'.\engine::ucfirst($topic->name).'</b></a>';
                echo '</div><div class="row_3">';
                //Описание темы
                echo \engine::input_text($topic->text);
                echo '</div></div>';
            }
            
        echo '</div>';    
    }    
}
