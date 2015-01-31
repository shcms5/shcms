<?php
/**
 * Контроллер для работы с Авторизацией
 * 
 * @package User
 * @author Shamsik
 * @link http://shcms.ru
 */
use Shcms\Component\Form\Form;

class Auth {
    
    /** @var: Обработка введенных параметров */
    public $login;
    
    /** @var: Сохраяем введенный логин в форме */
    public $value;
    
    /** @var: Массив Ошибок */
    public $error = array();
    
    /**
     * Обработка Логина и Пароля при авторизации
     * 
     * @warning: Если ошибки есть то не допускают
     */
    public function Login($param = array()) {
         global $db,$AboutGuest,$glob_core;
         
        //Данный пользователя по массиву
        foreach ($param as $key => $value) {
            $this->login = $param;
        }
        
        $authl = new Shcms\Component\Users\User\User;
        
        //Если введенный пароль меньше 5 символов
        if( strlen($this->login['password']) < 5 ) {
            //Массив ошибки
           $this->error['password'] = 'Пароль должен быть выше 5 символов';
        }
       
        //Если Ник превышает 30 символов
        if( engine::strlen_shcms($this->login['login'],'utf-8') > 30 or engine::strlen_shcms(trim($this->login['login']), 'utf-8' ) < 3) {
            //Массив ошибки
            $this->error['login'] = 'Недопустимая длина ника';
        }
    
        //Проверка на недоступных символов
        if(preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/",$this->login['login'])) {
            //Массив ошибки
            $this->error['login'] = 'Ваш ник недопустим к регистрации';
        }
    
        //Проверяем Ник
        if(strpos( strtolower($this->login['login']), '.php' ) !== false) {
            //Массив ошибки
            $this->error['login'] =  'Введенный ник недопустим';
        }
        
        //Проверяем Ник на ограниченные ошибки
        if(stripos( urlencode($this->login['login']), '%AD') !== FALSE) {
            //Массив ошибки
	    $this->error['login'] = 'Введенный ник недопустим';
        }
    
        //Регистрация ников через Маленькие буквы
        if(function_exists('mb_strtolower')) {
            //Установка кадировки
	    $this->login['login'] = mb_strtolower($this->login['login'], 'utf-8');
        } else {
            //Стандартная кадировка
	    $this->login['login'] = strtolower($this->login['login']);
        }    
        
        //Проверяем находятся еще ошибки в массиве $error
        if(empty($this->error['login'])) {
            //Получаем данные по Логину
	    $userl = $db->query( "SELECT * FROM `users` WHERE `nick` = '".$this->login['login']."' LIMIT 1" );
	
            //Проверяем существует ли введенный Логин
            if($db->num_rows($userl)) {
                //Получение всех данных по Логину
	        $auser[] = $authl->UserLogin($this->login['login']);
                //Если введенный пароль совпадает с тем что в базе то авторизуем
                if(\engine::shgen($this->login['password']) === $auser[0]['password']) {
		    // Если Нажата кнопка Запомнить
                    if ($this->login['code'] == true) {
                        //Индификатор
                        $cuser_id = base64_encode($auser[0]['id']);
                        //Закодированный пароль
                        $cpassword = \engine::shgen($this->login['password']);
                        //Установим Кукки
                        setcookie("cuser_id_shcms", $cuser_id, time() + 3600 * 24 * 365);
                        setcookie("cpassword_shcms", $cpassword, time() + 3600 * 24 * 365);
                    }
                        // Установка данных сессии
                        $_SESSION['user_id_shcms'] = $auser[0]['id'];
                        $_SESSION['password_shcms'] = engine::shgen($this->login['password']);
                    //Переадресуем на главную    
                    header("Location: ../index.php");						
	        }else {
                    //Если превышает Неправильный ввод данных то Блокируем на 5мин
                    if($auser[0]['limit_auth'] >= $glob_core['un_auth']) {
                        //Текст ошибки
			echo engine::error(Lang::__('Ваша учетная запись заблокирована на 5 минут!'));
                        //Ручная переадресация
                        echo engine::home(array(Lang::__('Назад'),'auth.php'));
                        exit;
		    }else {
                        //Обновляем Неудачные авторизации лимит 5
			$db->query("UPDATE `users` SET `limit_auth` = '".($auser[0]['limit_auth']+1)."',`limit_time` = '".(time()+60*5)."' WHERE `id` = '".$auser[0]['id']."'");
			//Заносим данные в Лог Таблицы
                        $db->query("INSERT INTO `log_auth` SET `id_user` = '".intval($auser[0]['id'])."',`ip` = INET_ATON('".$AboutGuest->ip."'),`browser` = '".$AboutGuest->browser."',
                                                               `version_browser` = '".$AboutGuest->version."',`oc` = '".$AboutGuest->operating_system."',
                                                               `version_oc` = '".$AboutGuest->os_version."',`time` = '".time()."'");}													   
		    	
                        //Текст ошибки                                       
                        echo engine::error('Неверный Логин или Пароль');
                        //Ручная переадресация
			echo engine::home(array(Lang::__('Назад'),'auth.php'));
            		exit();
		}
            }
        }
    }    
    
    /**
     * Форма Авторизации
     * 
     * @warning: Если ошибки есть то не допускают
     */
    public function Form($param = array()) {
        
        //Сохраняем введенный Логин в Форме
        foreach ($param as $key => $value) {
            $this->value = $param;
        }
        
        //Объявляем Форму
        $form = new Form;
        
        $html = '';
        //Открываем Форму
        $html .= $form->open(array('action' => '?','class' => 'form-horizontal'));
        
        //Поле Логина
        $html .= '<div class="form-group">';
        $html .= '<label class="col-sm-3 control-label">Логин</label>';
        $html .= '<div class="col-sm-8">';
        $html .= $form->input(array('name' => 'nick','value' => $this->value['login'],'class' => 'form-control','placeholder' => 'Введите Логин'));
        //Если ошибки
        if(isset($this->error['login'])) {
            $html .= '<p class="text-danger">'. $this->error['login'].'</p>';
        }       
        $html .= '</div></div><div class="row_g"></div>';

        //Поле Пароля
        $html .= '<div class="form-group">';
        $html .= '<label class="col-sm-3 control-label">Пароль</label>';
        $html .= '<div class="col-sm-8">';        
        $html .= $form->input(array('name' => 'password','type' => 'password','class' => 'form-control','placeholder' => 'Введите Пароль'));
        //Если Ошибки
        if(isset($this->error['password'])) {
            $html .= '<p class="text-danger">'.$this->error['password'].'</p>';
        }
        $html .= '</div></div><div class="row_g"></div>';
        
        //Запоминаем Данные по выбору
        $html .= '<div class="form-group">';
        $html .= '<label class="col-sm-3 control-label">Запомнить</label>';
        $html .= '<div class="col-sm-8">';        
        $html .= $form->checkbox(array(1 => array('name'=>'code', 'value'=>'1' )));
        $html .= '</div></div>';   
        
        //Кнопка Авторизации
        $html .= '<div class="modal-footer">';
        $html .= $form->submit(array('name' => 'submit','value' => 'Авторизация','class' => 'btn btn-success'));
        $html .= '<a class="btn btn-warning" href="/modules/lostpass.php">Восстановление</a>';
        
        //Закрываем Форму
        $html .= $form->close();
        
        return $html;
    }

}
