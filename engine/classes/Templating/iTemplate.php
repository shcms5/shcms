<?php
/**
 * Класс для вывода Шаблонов по браузерам
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */

use Shcms\Component\Provider\MobileDetect\MobileDetect;

class iTemplate {

    /**
     * Подключаем Шаблонизатор от Twig
     * 
     * @return string
     */
    public function __construct() {
        require_once H.'engine/classes/Shcms/Component/Templating/Twig/Autoloader.php';
        Twig_Autoloader::register(true); 
    }


    /**
     * Выводим полнный html шаблон с параметрами
     * 
     * @param $title Заголовок страницы
     * @param $desc Описание старницы
     * @param $key Ключевые слова
    */
    public function template($title = 'Название страницы', $desc = false, $key = false) {
	global $id_user,$user,$glob_core,$db,$users,$social;
		    
	    //Определение типа браузера
	    $detect = new MobileDetect;
	    $deviceType = ($detect->isMobile() ? 
                          ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
			
	    //Путь к новостям
            if ($_SERVER['PHP_SELF'] == '/modules/news/index.php' OR 
                $_SERVER['PHP_SELF'] == '/modules/news/view.php' ){
                $news = true;
	    }        
				
	if($db == true) {
            //Популярные новости
            $viewn = $db->query("SELECT * FROM `news` WHERE `view` > '10' ORDER BY `view` DESC LIMIT 5");
                while($nview = $db->get_array($viewn)) {                    
                    $view_news[] = $nview;
                }
            //Меню
            $gmenu = $db->query("SELECT * FROM `application`");  
                 while($menul = $db->get_array($gmenu)) {
                    $data[] = $menul; 
                }
                
            //Счетчики для меню
            $scount = $db->get_array($db->query("SELECT COUNT(*) as count FROM  `chat`"));   
            $fcount = $db->get_array($db->query("SELECT COUNT(*) as count FROM  `forum_topics`"));   
            $ncount = $db->get_array($db->query("SELECT COUNT(*) as count FROM  `news`"));   
            $dcount = $db->get_array($db->query("SELECT COUNT(*) as count FROM  `files`"));   
            $lcount = $db->get_array($db->query("SELECT COUNT(*) as count FROM  `libs_files`"));   
            $gcount = $db->get_array($db->query("SELECT COUNT(*) as count FROM  `gallery_files`"));               
                
	}
	
        //Если авторизован
	if($id_user == true) {
	    if($deviceType == 'computer'){
                //Путь к шаблону
		$loader = new Twig_Loader_Filesystem(H.'templates/'.$users['web_template'].'/');
                //инициализируем Twig
                $twig = new Twig_Environment($loader, array('cache' => H.'templates/'.$users['web_template'].'/cache/','debug' => true));
		
	    }elseif($deviceType == 'phone') {
                //Путь к шаблону
		$loader = new Twig_Loader_Filesystem(H.'templates/'.$users['wap_template'].'/');
                //инициализируем Twig
                $twig = new Twig_Environment($loader, array('cache' => H.'templates/'.$users['wap_template'].'/cache/','debug' => true));
	    }		
        }else {
	    if($deviceType == 'computer'){
                //Путь к шаблону
                $loader = new Twig_Loader_Filesystem(H.'templates/web_default/');
                //инициализируем Twig
                $twig = new Twig_Environment($loader, array('cache' => H.'templates/web_default/cache/','debug' => true));                
	    }elseif($deviceType == 'phone') {
                //Путь к шаблону
		$loader = new Twig_Loader_Filesystem(H.'templates/wap_default/');
                //инициализируем Twig
                $twig = new Twig_Environment($loader, array('cache' => H.'templates/web_default/cache/','debug' => true));
	    }	
	}

        $filter = new Twig_SimpleFilter("input_text", function($str) {
            // обработка входящего текста
            $str = (string) $str;
            $inputbbcode = new bbcode($str);
            $str = $inputbbcode->get_html();
            
            echo  $str;
        });

        $twig->addFilter($filter);
        
        //Подгружаем основной файл
        $template = $twig->loadTemplate('header.twig');
		
        // передаём в шаблон переменные и значения
        // выводим сформированное содержание
	if( $db == true ) {
	    if($user->users($id_user,array('group')) == 15) {
		$lgroup = 'Администратор';
	    }elseif($user->users($id_user,array('group')) == 1) {
		$lgroup = 'Пользователь';
	    }	
		
            //Описание если существует выводить
	    if($desc == NULL) {
		$des =  $glob_core['description'];
	    }else{
         	$des = $desc; 
	    }		
            //Ключевые слова
	    if( $key == NULL ) {
		$keys = $glob_core['keywords'];
	    }else {
		$keys = $key;
	    }
               
            //Счетчик Друзей
	    $count_friend = $db->get_array(
                    $db->query("SELECT COUNT(*) FROM `friends` WHERE `id_user` = '".$id_user."' AND `approved` = '1'"));
            //Счетчик Почты
	    $count_m = $db->get_array(
                    $db->query("SELECT COUNT(*) FROM `messaging` WHERE `id_post` = '".$id_user."' AND `action` = '0'"));
            //Счетчик Всех друзей
	    $count_f = $db->get_array(
                    $db->query("SELECT COUNT(*) FROM `friends` WHERE `id_friends` = '".$id_user."' AND `approved` = '0'"));
            
            //Версия SHCMS 
            $cversion = new version; 
            
            //Основные параметры
            echo $template->render(
                    array('fc_id' => $social['fc_id'],'fc_key' => $social['fc_key'],
                        'fc_close' => $social['fc_close'],'vk_id' => $social['vk_id'],
                        'vk_key' => $social['vk_key'],'vk_close' => $social['vk_close'],
		        'mail' => $count_m[0],'countf' => $count_f[0],'view_news' => $view_news,
		        'friend' => $count_friend[0],'countq' => $countq, 'data' => $data,
		        'main_name' => $main_name,'title' => $title,'description' => $des,
		        'keywords' => $keys,'id_user' => $id_user,'news_twig' => $news,
		        'login' => $user->users($id_user,array('nick')),'group' => $lgroup,
		        'site_name' => $glob_core['name_site'],'copyright' => $cversion->CopyrightVersion(),
                        'chat' => $scount,'forum' => $fcount,'news' => $ncount, 'download' => $dcount,
                        'libs' => $lcount,'gallery' => $gcount
                    ));
	}else {
	    echo $template->render(
                    array('data' => $data,'main_name' => $main_name,
		        'title' => $title, 'description' => $des,
		        'keywords' => $keys,'news_twig' => $news
                    ));	
	}
    }
	

    /**
     * Выводим нижнюю часть шаблона
     * 
     * @return string
     */
    public function __destruct(){ 
	    global $users,$id_user;
			
        //Определение типа браузера			
	$detect = new MobileDetect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	    	if( $id_user == true ) {
                    // указывае где хранятся шаблоны
		    if($deviceType == 'computer') {
                        include_once(H.'templates/'.$users['web_template'].'/footer.twig');
                    }elseif($deviceType == 'phone') {
			include_once(H.'templates/'.$users['wap_template'].'/footer.twig');			
		    }elseif($deviceType == 'tablet') {
			include_once(H.'templates/'.$users['wap_template'].'/footer.twig');	
		    }else{
		        include_once(H.'templates/'.$users['wap_template'].'/footer.twig');			
		    }
		    
		}else {
                    // указывае где хранятся шаблоны
		    if($deviceType == 'computer') {
			include_once(H.'templates/web_default/footer.twig');
		    
		    }elseif($deviceType == 'phone') {
			include_once(H.'templates/wap_default/footer.twig');			
	            }	
			
		}	
    }
	
}

$templates = new iTemplate;
