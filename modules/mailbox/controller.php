<?php
/**
 * Контроллер для работы с Почтовым Ящиком
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
   
    private $max_limit;
    
    /** @var: Запускаем Навигацию */
    protected $start;
    
    /** @var: Выводим навигацию */
    protected $end;
    
    /** @var: Массив ошибок */
    protected $error = array();

    /**
     * Проверка существует ли в user/ файл module.json
     * 
     * @warning: Без данного файла скрипт не будет работать
     */
    public function __construct() {
        //Путь к файлу
        $path = file_exists(H.'modules/mailbox/module.json');
        try {
            //Если файл не найден то ошибка
            if($path == false) {
                throw new Exception('Файл module.json отсутствует');
            }else {
               $string = file_get_contents(H.'modules/mailbox/module.json');
               $json = new JsonScript;	
               $jsond = $json->decode($string);
               $this->module = $jsond;
            }
        }catch (Exception $e) {
            echo engine::error($e->getMessage());
            exit;
        }
    }

    /**
     * Проверяем не отключена ли почта
     * 
     * @warning: Отключить может только администратор
     */
    public function offmail() {
        global $glob_core;
        
        try {
            //Если Почта отключена
            if($glob_core['on_mail'] == 2) {
	        throw new Exception($this->module[text][offmail]);
	    }
        }catch (Exception $e) {
            //Выводит текст ошибки
            echo engine::error($e->getMessage());
            //Через 4 секунды переадресует на главную
            header('Refresh: 4; url=/index.php');
            exit;
        }
    }
    
    /**
     * Ставим лимит на создание тем
     * 
     * @warning: Необходима чтобы не загружать базу
     */
    public function limit() {
        return $this->max_limit = $this->module[limit][them];
    }

    /**
     * Каждый пользователь сможет по выбору Отключить свою почту
     * 
     * @warning: После отключения никто не сможет отправить письма
     */    
    public function off_mail() {
        global $users;
        
        $view = '';
        try {
            //Если отключена почта
            if($users['off_mail'] == 1) {
                throw new Exception($this->module[text][offuser]);
            }else {
                //Если включен то нажмите для отключения
                $view .= '<div style="text-align:right;margin-bottom:5px;">';
                $view .= '<a class="btn btn-info" href="?do=off">'.$this->module[off_mail][off].'</a>';
                $view .= '</div>';
            }
        }catch (Exception $ex) {
                //Если полностью отключена почта
                $eview = $ex->getMessage();
                $eview .= '<br/><center><div class="btn-group">';
                $eview .= '<a class="btn btn-info right" href="?do=onmail">'.$this->module[off_mail][on].'</a>';
                $eview .= '<a class="btn" href="menu.php">'.$this->module[off_mail][cancel].'</a></div></center>';
                $view .= engine::info($eview);
                exit;
        }    
        
        return $view;    
    }
    
    /**
     * Выводим папки Почты
     * 
     * @warning: Null
     */    
    public function dir_mail() {
        global $db,$row,$row1;
        
            //Вывод папок
            $message = $db->query("SELECT * FROM `messaging_dir`");
            
	    $mview = ''; 	
            //Если в базе существует больше 0 папок выводим их
            if($db->num_rows($messaging) > 0) {
                //Все папки выводим
                while($mdir = $db->get_array($message)) {
                    $mview .= '<table class="posts_gl"><tbody><tr class="">';
                    //Иконка
                    $mview .= '<td class="c_icon"><img src="/engine/template/messaging/'.$mdir['images'].'"></td>';
                    //Название папки
                    $mview .= '<td class="c_forum"><b><a href="?act=message&id='.$mdir['id'].'">'.$mdir['name'].'</b></a>';
                    //Небольшое описание
                    $mview .= '<p class="desc">'.$mdir['text'].'</p>';
                    $mview .= '</td>';
                    //Выводим счетчики
                    $mview .= '<td class="c_stats width-1"><ul>';
                    //Счетчики Моих переписок
		    if($mdir['id'] == 2) {
	                $mview .= '<li><span class="badge">'.$row[0].'</span></li>';
		    }
                    //Счетчик Черновиков
		    if($mdir['id'] == 3) {
		        $mview .= '<li><span class="badge">'.$row1[0].'</span></li>';
		    }
		    $mview .= '</ul></td></tr></tbody></table>';
	        }
            }else {
                $mview .= engine::error($this->module[text][not_dir]);
            }
           
        return $mview;    
    }

    /**
     * Объявляем навигацию и применяем параметры
     * 
     * @param $this->start, $this->end
     */
    public function dnavigation () {
        global $rows;
        //Навигационная система
        $newlist = new Navigation($rows[0],10, true); 
        $this->start = $newlist->limit();
        $this->end = $newlist->pagination();
    }
    
    /**
     * Выводим навигацию
     * 
     * @param $this->end
     */
    
    public function dnavigation_end() {
        return $this->end;
    }

    /**
     * Вывод всех тем которые вы отправили и получили
     * 
     * @warning: Null
     */        
    public function my_them($str = false) {
        global $id_user,$db,$user,$rows,$user_topics;
        
            //Выводим все темы и сообщения в личном ящике
            if($str == false) {
                $user_topics = $db->query("SELECT * FROM `messaging_topics_user` WHERE `id_user` = '".$id_user."' ORDER BY `id` DESC ". $this->start."");
            }
            
            $myview = '';
            
        //Получаем данные по выводу всех тем
	while($user_top = $db->get_array($user_topics)) {
	    $myview .= '<table class="posts_gl"><tbody><tr class="">';	
            
            //Получаем из базы названия темы через $id
	    $mtopics = $db->get_array($db->query("SELECT * FROM `messaging_topics` WHERE `id` = '".$user_top['id_topics']."'"));		
	    //Получаем из базы все сообщения и дополнительные данные
	    $messaging = $db->get_array($db->query("SELECT * FROM `messaging` WHERE `id_topics` = '".$mtopics['id']."'"));
	    
            //Получаем данные по автору темы
	    $nick = $user->users($messaging['id_user'],array('nick'),false);
	    //Получаем данные по получателю темы
	    $nick_post = $user->users($messaging['id_post'],array('nick'),false);
            
            //Счетчик тем
            $cmail = $db->get_array($db->query("SELECT COUNT(*) as count FROM `messaging` WHERE `id_topics` = '{$mtopics['id']}'"));
            //Все данные автора темы
            $profiles = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".$messaging['id_user']."'"));
           
            $myview .= '<td class="c_icon">';
            //Путь к аватарам
            $avatars = '/upload/avatar/' . $profiles['avatar'];
            
            //Если нет аватара выводим аватар по умолчанию
            if ($profiles['avatar'] == false and file_exists($avatars) == false) {
                $myview .= '<img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini">';
            } else {
                $myview .= '<img src="'.$avatars.'" class="UserPhoto UserPhoto_mini">';
            }
            
            $myview .= '</td>';
            //Ск. писем непрочитано
	    if($messaging['id_post'] == $id_user) {
	        $countmes = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging` WHERE `id_topics` = '".$user_top['id_topics']."' and `action` = '0'"));
	    }else {
	        $countmes = '';
	    }
                
	    //Выводим Названия темы если есть
	    $myview .= '<td class="f_subject"><b>';
            $myview .= '<a href="?act=topics&id='.$mtopics['id'].'">'.engine::ucfirst($mtopics['name']).'</a></b>&nbsp;';
            
            //Если это письмо посылающая
	    if($messaging['id_dir'] == 2) {
                //Проверка на прочитанность
		if($messaging['id_post'] == $id_user) {
                    //Если новую тему не читали
		    if($messaging['action'] == 0) {
			$myview .= '<span class="text-danger">'.$this->module[mess_status][not].' '.$countmes[0].'</span>';
		    }else {
                        $myview .= '<span class="text-success">'.$this->module[mess_status][yes].'</span>';
		    }
		}else {
                    //Если вы явлеетесь автором темы  
		    $myview .= '<span class="text-info">'.$this->module[mess_status][my].'</span>';
		}
	    }elseif($messaging['id_dir'] == 3) {
                //Если тема в черновике  
		$myview .= '<span class="text-danger">'.$this->module[mess_status][draft].'</span>';
	    }
            
            //Данные об Авторе и об Получаетеле    
	    $myview .= '<p class="descl">';
	    //Кто отправил
            $myview .= 'Автор <a href="/modules/profile.php?act=view&id='.$messaging['id_user'].'">'.$nick.'</a>'; 
            //Тот кто получил письмо
            $myview .= ', получатель <a href="/modules/profile.php?act=view&id='.$messaging['id_post'].'">'.$nick_post.'</a>';
            $myview .= '</p></td>';
            
            //Выводим счетчик всех писем в теме
            $myview .= '<td class="m_replies">';
            $myview .= '<span class="time">';
            $myview .= $cmail['count'].''.$this->module[mess_status][otvet].'';
	    $myview .= '</span></td>';
            
            //Удаление темы
            $myview .= '<td class="c_stats width-2"><ul>';
            $myview .= '<li>';
            $myview .= '<a href="?act=delete_topics&topicid='.$mtopics['id'].'">';
            $myview .= '<img src="/engine/template/icons/delete.png"></a></li>';
            $myview .= '</ul></td>';
            $myview .= '</tr></tbody></table>';
	}
        
        return $myview;    
    
    }
    
    /**
     * Обработка при создании темы пользователю
     * 
     * @warning: Если все правильно введено то создаем
     */    
    public function newsend() {
        global $id_user,$db,$id;
        
            //Обрабатываем тему
            $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
	    //Обрабатывает сообщение
            $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
   	    $topic = engine::input_text($text);	
	        
	    //Если не введена название темы ...
	    if(empty($name)) {
		$this->error['name'] =  engine::error('Введите названия темы');
	    }elseif(!$topic) {
	        $this->error['text'] =  engine::error('Введите сообщение');
	    }else { 
                //Если правильно все добавляем данные в базу
		$db->query("INSERT INTO `messaging_topics` (`id_user`,`id_post`,`id_dir`,`time`,`name`) VALUES ('".$id_user."','".$id."','2','".TIME()."','".$db->safesql($name)."')");
		//Выводим данные для следующей передачи
		$message_topics = $db->get_array($db->query("SELECT * FROM `messaging_topics` WHERE `id_user` = '".$id_user."' AND `id_post` = '".$id."' AND `name` = '".$name."'"));
                //Добавим данные новые в новую таблицу и создаем таблицы для $id_user || $ID
		$db->query("INSERT INTO `messaging_topics_user` (`id_user`,`id_dir`,`id_topics`,`time`) VALUES ('".$message_topics['id_user']."','2','".$message_topics['id']."','".time()."') , ('".$message_topics['id_post']."','2','".$message_topics['id']."','".time()."')");
		//Передаем текст сообщенив базу
		$db->query("INSERT INTO `messaging` (`id_dir`,`id_topics`,`id_user`,`id_post`,`text`,`time`,`action`) VALUES ('2','".$message_topics['id']."','".$message_topics['id_user']."','".$message_topics['id_post']."','".$db->safesql($topic)."','".TIME()."','0')");
                //Переадресация
		header("Location: /modules/messaging.php");
	    }
    }
    
    /**
     * Форма добавления новой темы
     * 
     * @warning: Заполните все поля
     */    
    public function formsend() {
        global $form,$id,$profile;
        
        //Класс Времени
        $tdate = new DateType();
        //Объявляем форму
        $form = new Form;
        //Открываем форму
        echo $form->open(array('action' => '?act=newsend&id='.$id.'','class' => 'form-horizontal'));
        //Кто получает письмо
        echo '<div class="form-group">';
        echo '<label class="col-sm-3 control-label">'.$this->module[form][recipient].'</label>';
        echo '<div class="col-sm-8">';
        echo $form->input(array('name' => 'name','value' => $profile['nick'], 'class' => 'form-control','disabled' => 'disabled'));
        //Посл. визит получателя
        echo '<span class="text-info small">'.$this->module[form][lastdate].'&nbsp;'.$tdate->make_date($profile['lastdate']).'</span>';
        echo '</div></div>';
        
        //Имя темы
        echo '<div class="form-group">';
        echo '<label class="col-sm-3 control-label">'.$this->module[form][them].'</label>';
        echo '<div class="col-sm-8">';
        echo $form->input(array('name' => 'name','class' => 'form-control'));
        echo '</div></div>';
        
        //Текст темы
        echo $form->textbox(array('name' => 'text','id' => 'editor'));
        
        //Отправляем или отменяем
        echo '<div class="modal-footer">';
        echo $form->submit(array('name' => 'submit','class' => 'btn btn-success'));
        echo '<a class="btn" href="/modules/messaging.php">'.$this->module[form][cancel].'</a>';
        echo '</div>';
        //Закрываем форму
        echo $form->close();
    }

    /**
     * Обработка и добавление нового поста в тему
     * 
     * @warning: Null
     */    
    
    public function stopics() {
        global $mtopics,$id,$db,$id_user;
        
        //Обработка текста
	$text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
        //Если текст пустой
	if(empty($text)) {
	    $this->error['post'] = engine::error(Lang::__('Введите текст ответа'));
	}else {
	    if($id_user != $mtopics['id_user']) {
		$userid =  $mtopics['id_post'];
	    }else {
		$userid = $mtopics['id_user'];
	    }
	    if($id_user == $mail['id_post']) {
		$postid =  $mtopics['id_user'];
	    }else {
		$postid = $mtopics['id_post'];
	    }
		$insert = $db->query("INSERT INTO `messaging` (`id_dir`,`id_topics`,`id_user`,`id_post`,`text`,`time`) VALUES ('2','".$id."','".$userid."','".$postid."','".$db->safesql($text)."','".time()."')");
		header('Location: ?act=topics&id='.$id.'');
	}
    }

    /**
     * Форма добавления поста
     * 
     * @warning: Введите текст выше 10 символов
     */        
    public function formtopic() {
        global $id;
            //Форма
            $form = new Form;
            echo $form->open(array('action' => '?act=topics&id='.$id.''));
            //Текст отправки
            echo $form->textbox(array('name' => 'text', 'id' => 'editor'));
            //Кнопка получения
            echo '<br/>';
            echo $form->submit(array('name' => 'submit', 'class' => 'btn btn-success','value' => $this->module[topic][submit]));
            //Кнопка отмены
            echo '<a class="btn" href="?">'.$this->module[topic][cancel].'</a>';
            //Закрываем форму
            echo $form->close();
    }

    /**
     * Вывод всех постов в теме
     * 
     * @warning: Все отправленные посты будут получены из базы
     */        
    public function viewtopic() {
        global $id,$db,$newlist,$row,$id_user,$user;

            //Вывод сообщений
            $mtext = $db->query("SELECT * FROM `messaging` WHERE `id_topics` = '".$id."' ORDER BY `id` DESC ". $newlist->limit()."");
            $topicv = '';
            
	    while($tmail = $db->get_array($mtext)) {
                //Обновляет как прочитанное
	        $db->query("UPDATE `messaging` SET `action` = '1' WHERE `id_topics` = '".$id."' AND `id_post` = '".$id_user."'");
	        //Получаем данные по автору темы
	        $nicks = $user->users($tmail['id_user'],array('nick'),false);
                //Полная информация пользователя
                $profile = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '{$tmail['id_user']}'"));  
                //Класс Времени
                $tdate = new DateType();
                
                $topicv .= '<li class="clearfix row3">';
                //Путь к аватарам
                $avatar = '/upload/avatar/' . $profile['avatar'];
                
                //Если нет аватара выводим аватар по умолчанию
                if ($profile['avatar'] == false and file_exists($avatar) == false) {
                    $topicv .= '<a href="#" class="UserPhotoLink left">';
                    $topicv .= '<img src="/engine/template/icons/default_large.png" class="UserPhoto UserPhoto_mini"></a>';
                } else {
                    $topicv .= '<a href="#" class="UserPhotoLink left">';
                    $topicv .= '<img src="' . $avatar . '" class="UserPhoto UserPhoto_mini"></a>';
                }
            
                $topicv .= '<div class="list_content">';
                $topicv .= '<a href="/modules/profile.php?act=view&id='.$tmail['id_user'].'"><b>'.$nicks.'</b></a>';
                $topicv .= '<div class="details">';
                //Привиление пользователя
                $topicv .= '<span><img src="/engine/template/icons/group.png">&nbsp;' . user::group($tmail['id_user']) . '</span>';
                //Время добавление сообщения
                $topicv .= '<span><img src="/engine/template/icons/date.png">&nbsp;' . $tdate->make_date($tmail['time']) . '</span>';
                $topicv .= '</div>';
                //Текст сообщения
                $topicv .= '<div class="lighter row2">' . engine::input_text($tmail['text']) . '</div>';
                $topicv .= '</div>';
                $topicv .= '</li>';	
	    }
            
        return $topicv;
    }
    
    /**
     * Получение заголовок при переходах
     * 
     * @warning: $id = num array(1,2,3)
     */  
    public function mpath() {
        global $id;
 
            $mpath = '';
        
            if($id == 1) {
                $mpath .= '<div class="mainname">'.$this->module[path][news].'</div>';		
            }elseif($id == 2) {
                $mpath .= '<div class="mainname">'.$this->module[path][my_them].'</div>';
            }elseif($id == 3) {  
                $mpath .= '<div class="mainname">'.$this->module[path][draft].'</div>';		
            }      
    
        return $mpath;
    }
        
}
