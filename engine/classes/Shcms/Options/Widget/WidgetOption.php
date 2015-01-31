<?php
/**
 * Класс для Работы с Главным меню
 * 
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
namespace Shcms\Options\Widget;

use Shcms\Component\Provider\Json\JsonScript;

class WidgetOption {
	
    /**
     * Выводим самые первый разделы на главную страниц
     * 
     * @return string
     */
    public static function statics() {
	    global $db;
		
        //Все пользователи
        $cuserall = $db->get_array( $db->query( "SELECT COUNT(*) FROM `users`" ) );
	//В сети
        $cuseronline = $db->get_array( $db->query( "SELECT COUNT(*) FROM `users` WHERE `lastdate` > '".(time()-600)."'"));
        
	//Последний зарегестрированный
	$newuser = $db->super_query( "SELECT * FROM `users` ORDER BY `id` DESC" );
    		
		$stat = '<center><div style="border-top: 0px;border-radius:0px 0px 4px 4px;" class="posts_gl">';
                $stat .= '<a class="btn btn-default" href="/modules/users.php"><b>'.$cuserall[0].'</b> Пользователей</a>';
		$stat .= '<span style="margin-left: 40px;"></span>';
                $stat .= '&nbsp;<a class="btn btn-default" href="/modules/users.php?do=online"><b>'.$cuseronline[0].'</b> Онлайн</a>';
		$stat .= '<span style="margin-left: 40px;">';
                $stat .= '<a class="btn btn-default disabled" href="#"><b>'.$newuser['nick'].'</b>&nbsp;Новый</a></span>';
		$stat .= '</div></center>';
		
	    return $stat;	
	
    }
    
	
    /**
     * Получаем и выводим все данные меню из таблицы
     * 
     * @return string
     */
    public static function listing() 
    {
	    global $db, $user,$engine;
		
        //Вывод данных из json
        $string = file_get_contents(H.'engine/widget/filejson/home.json');
        $json = new JsonScript;	
        $jsond = $json->decode($string);
	            
            //Вывод всех разделов
	    foreach($jsond as $key => $value) {	
		include_once(H.'engine/widget/count/forum.php');    
                include_once(H.'engine/widget/count/news.php');										
    	    }		
    }
}
