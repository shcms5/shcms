<?php
/**
 * Авторизация через Соц сеть Facebook
 * 
 * @package User
 * @author Shamsik
 * @link http://shcms.ru
 */
class FcAuth {
    
    //Индифицированный ключ
    public $id = 0;

    //Секретный ключ
    public $key = 0;
    
    //Включен отключен модуль
    public $close = 1;
    
    /**
     * Если все данные введены правильно то идет авторизация
     * 
     * @warning: Надо заполнить данные из Админки->Настройка Соц сети
     */
    public function __construct($param = array()) {
        
        //Индификатор приложения
        $this->id = $param['id'];
        //Секретный ключ
        $this->key = $param['key'];
        //Включен отключен модуль
        $this->close = $param['close'];
        
        //Если Ключ или Индификатор не заполнен то false
        if(empty($this->id) OR empty($this->key) OR $this->close == 1 ) {
            header("Location: /index.php");
            exit;
        }
    }
    
    /**
     * Проверяем данные и Регистрируем
     * 
     */
    public function fc() {
        global $db,$glob_core;
        
        // Адрес сайта
        $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/modules/auth.php?do=fc';
        //Путь при авторизации через Facebook
        $url = 'https://www.facebook.com/dialog/oauth';
            
        //Дополнительные параметры для точной авторизации
        $params = array('client_id' => $this->id,'redirect_uri' => $redirect_uri,
                        'response_type' => 'code','scope' => 'email,user_birthday');

        //Если $_GET сгенерирован
        if (isset($_GET['code'])) {
            $result = false;

            $params = array(
                'client_id'     => $this->id,
                'redirect_uri'  => $redirect_uri,
                'client_secret' => $this->key,
                'code'          => $_GET['code']
            );
            
            //Путь авторизации
            $url = 'https://graph.facebook.com/oauth/access_token';

            //Токен false по умолчанию
            $tokenInfo = null;
            parse_str(file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);
         
            if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
                $params = array('access_token' => $tokenInfo['access_token']);
                $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);
                //Получение Индификтора
                if (isset($userInfo['id'])) {
                    $userInfo = $userInfo;
                    $result = true;
                }
        
                //Дату выделяем
                $bdate = explode('/', $userInfo['birthday']);
                //Имя выделяем
                $nicks = $userInfo['name'];
        
                if($result) {
                    //Если существует определяемый Логин то ошибку выводим    
                    $cnick = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE `nick` = '{$nicks}'" );
                    //Если существует E-mail то отменяем все
                    $cmail = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE `email` = '{$userInfo['email']}'" );
                    //Выводим параметр
                    if( $cnick['count'] or $cmail['count']) {
                        echo engine::error('Регистрация через Facebook доступно только один раз');
                         //Переадресация
                        echo engine::home(array('Назад','/modules/auth.php?do=fc'));	
                        exit;
                    }else {
                        //Получение IP адреса гостя    
                        $ip = engine::get_ip();
                        //Закодирование пароля
                        $shgen = engine::shgen($userInfo['id']);
                        //Загружаем данные в базу
                        $mail = new mail($glob_core['from_email']);
                        //Отправка данных на почту
                        $mail->setFromName($glob_core['name_site']);
                        $mail->send_html($userInfo['email'], "Регистрация через Facebook", "Вы Успешно зарегистрированы на сайте {$_SERVER['HTTP_HOST']} <br/> Ваш логин: {$db->safesql($nicks)}<br/> Ваш пароль: {$userInfo['id']}");
                        //Если все отлично то регистрируем
                        $db->query( "INSERT INTO `users` (`nick`,`email`,`pol`,`day`,`month`,`year`,`password`,`reg_date`,`lastdate`,`group`,`logged_ip`,`asocial`) VALUES ('{$db->safesql($nicks)}','{$userInfo['email']}','{$pol}','{$bdate[0]}','{$bdate[1]}','{$bdate[2]}','{$shgen}','{$_date}','{$_date}','1','{$ip}','fc')" );
                        //Получаем последний id
                        $id = $db->insert_id();
                        //Запускаем Куки и добавляем данные
		        setcookie( "user_id_shcms", $id, 365 );
		        setcookie( "password_shcms", $shgen, 365 );
                        //Запуск Сессии и добавляем данные
		        $_SESSION['user_id_shcms'] = $id;
		        $_SESSION['password_shcms'] = $shgen;
                        //Переадресация
		        header("Location: /index.php");
                    }  
                }
            }      
        }else {
            header('Location: ' . $url . '?' . urldecode(http_build_query($params)) . '');
        }  
    }
}
