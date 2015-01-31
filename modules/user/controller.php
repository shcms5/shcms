<?php
/**
 * Контроллер для работы с Пользователями
 * 
 * @package User
 * @author Shamsik
 * @link http://shcms.ru
 */

use Shcms\Component\Provider\Json\JsonScript;
use Shcms\Component\String\StringProcess;
use Shcms\Component\Form\Form;
use Shcms\Component\Data\Bundle\DateType;


class controller {
       
    /** @var: Основные функции */
    protected $module;

    /** @var: Запускаем Навигацию */
    protected $start;
    
    /** @var: Выводим навигацию */
    protected $end;
    
    /**
     * Проверка существует ли в user/ файл module.json
     * 
     * @warning: Без данного файла скрипт не будет работать
     */
    public function __construct() {
        //Путь к файлу
        $path = file_exists(H.'modules/user/module.json');
        try {
            //Если файл не найден то ошибка
            if($path == false) {
                throw new Exception(engine::error('Файл module.json отсутствует'));
            }else {
               $string = file_get_contents(H.'modules/user/module.json');
               $json = new JsonScript;	
               $jsond = $json->decode($string);
               $this->module = $jsond;
            }
        }catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /**
     * Счетчик всех пользователей и тех кто в Онлайне
     * 
     * @param all_user
     */    
    public function name() {
        global $rowq,$row;
            $name = '<div class="mainname">'.$this->module[info][all].'&nbsp;'.$rowq[0];
            $name .= '<span class="right">';
            $name .= '<a href="?do=online">'.$this->module[info][online].'&nbsp;'.$row[0].'</a>';
            $name .= '</span></div>';
        
        return $name;
    }
    
    /**
     * Счетчик всех в Онлайне и всех Пользователей
     * 
     * @param do=online
     */
    public function onlinename() {
        global $rowq,$row;
        
            $name = '<div class="mainname">'.$this->module[info][online].'&nbsp;'.$row[0];
            $name .= '<span class="right">';
            $name .= '<a href="users.php">'.$this->module[info][all].'&nbsp;'.$rowq[0].'</a>';
            $name .= '</span></div>';
        
        return $name;
    }    

    /**
     * Поиск пользователей
     * 
     * @param search()
     */    
    public function search() {
            //Объявляем форму
            $form = new Form;
            //Открываем форму
            $search  = $form->open(array('class' => 'navbar-form','role' => 'search'));
            $search .= '<div class="input-group col-sm-7 col-md-5 col-xs-5">';
            //Поиск пользователя
            $search .= $form->input(array('type' => 'search', 'id' => 'container-search','class' => 'form-control','placeholder' => 'Найти пользователя'));
            $search .= '</div><br/><br/>';
            //Закрываем форму
            $search .= $form->close();
        
        return $search;
        
    }
    /**
     * Объявляем навигацию и применяем параметры
     * 
     * @param $this->start, $this->end
     */
    public function navigation() {
        global $rowq;
        //Навигационная система
        $newlist = new Navigation($rowq[0],$this->module[listing][page], true); 
        $this->start = $newlist->limit();
        $this->end = $newlist->pagination();
    }
    
    /**
     * Выводим навигацию
     * 
     * @param $this->end
     */
    public function navigation_end() {
        return $this->end;
    }
    /**
     * Выводим всех пользователей и пользователей из онлайн
     * 
     * @param view_all($param = all OR $param = online)
     */
    public function view($param = false) {
        global $rowq,$row,$db,$newlist,$groups;
            //Если пользователей больше 0
            if($rowq[0] > 0) {
                if($param == 'all') { 
	            $vuser = $db->query("SELECT * FROM `users` GROUP BY `nick` DESC ".$this->start."");
                }elseif($param == 'online') {
	            $vuser = $db->query("SELECT * FROM `users` WHERE `lastdate` > ".(time()-$this->module[online][delay])." GROUP BY `nick` DESC ". $this->start."");  
                }
	    }else {
	        echo engine::error($this->module[info][error]);
	        exit;
            }
            
            $view = '';
            //Обработка строк
            $String = new StringProcess;
            //Обработка времени
            $tdate = new DateType();
            //Выводим список всех пользователей
            while($userq = $db->get_array($vuser)) {
                //Кидаем ник в Массив
                $names[] = $String->lower($userq['nick'],'utf-8');
            }       
            //Сортируем Имя
            sort($names);
            $first = array();
                
            foreach($names as $name) {
                $first[] = mb_substr($name,0,1,'utf8');
            }    
            
            $first = array_unique($first); 
            $dic = array();
                
            foreach($names as $name) {
                foreach($first as $f => $v) {
                    if(mb_substr($name,0,1,'utf8') == $v) {
                        $dic[$v][] = $name;
                    }
                }
            }    
            foreach($dic as $title => $nick) {
                $view .= '<div class="user_alf">'.$String->ucfirst($title,'utf-8').'</div>';
            
                foreach($nick as $val) {
                    $userv = $db->get_array($db->query("SELECT * FROM `users` WHERE `nick`= '{$val}'"));
                    $view .= '<li class="clearfix row3">';
                    $view .= '<div class="listing">';
                
                    //Выводим загруженный аватар
                    //Если не загружен аватар то получаем стандартный 
                    if($userv['avatar'] == false or file_exists(H.'/upload/avatar/'.$userv['avatar'].'') == false) {
                        $view .= '<a href="profile.php?id='.$userv['id'].'" class="UserPhotoLink left">';
                        $view .= '<img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini"></a>';
                    }else {
                        $view .= '<a href="profile.php?id='.$userv['id'].'" class="UserPhotoLink left">';
                        $view .= '<img src="/upload/avatar/'.$userv['avatar'].'" class="UserPhoto UserPhoto_mini"></a>';
                    }
                
                    //Путь к профилю пользователя
                    $view .= '<a href="profile.php?id='.$userv['id'].'">';
                    $view .= '<div class="list_content">';
                    //Получаем Ник пользователя
                    $view .= '<b>'.$userv['nick'].'</b>';
                    //Получаем последнее посещение
                    $view .= '<span class="time">'.user::realtime($userv['lastdate']).'</span>';
                    $view .= '<br/>';
                
                    // Время, в течении которого пользователь считается online (сек.)
                    $online = $userv['lastdate'] + $this->module[online][delay] <= time();
                
                    //Если нет в сети пользователей то true
                    if($online == true) {
                        $view .= '<div class="label label-default">'.$this->module[online][offline].'</div>&nbsp;';
                    }else {
                        $view .= '<div class="label label-success">'.$this->module[online][online].'</div>&nbsp;';
                    }
                    //Выводим группу в котором находится пользователь
		    $view .= '<br/><span class="desc lighter">';
		    $view .= $groups->user_group($userv['group']).'<br/>';
		    //Дата Регистрации
                    $view .= $this->module[info][register];
		    $view .= $tdate->make_date($userv['reg_date']);
		    $view .= '</span></div></a></div></li>';
                }
            }    
        //Выводим все данные    
        return $view;
    }
    
    
    
}
