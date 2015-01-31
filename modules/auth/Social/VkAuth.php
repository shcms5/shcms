<?php
/**
 * Авторизация через Соц сеть Вконтакте
 * 
 * @package User
 * @author Shamsik
 * @link http://shcms.ru
 */
class VkAuth {
    
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
    public function vk() {
        global $db;
        
        // Адрес сайта
        $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/modules/auth.php?do=vk';
        //Путь при авторизации через VK
        $url = 'http://oauth.vk.com/authorize';
        
            //Дополнительные параметры для точной авторизации
            $params = array('client_id' => $this->id,'scope' => 'status,email',
                'redirect_uri' => $redirect_uri,'response_type' => 'code');
            
        //Если $_GET сгенерированм
        if (isset($_GET['code'])) {
            //Отключаем результат работы
            $result = false;
            
            //Получаем точные сведение об авторизации
            $params = array(
                'client_id' => $this->id,
                'client_secret' => $this->key,
                'code' => $_GET['code'],
                'redirect_uri' => $redirect_uri,
            );
            
            //Получаем данные после отправки запроса
            $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

            //Ограничиваем вывод данных
            if (isset($token['access_token'])) {
                $params = array(
                    'uids'         => $token['user_id'],
                    'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                    'access_token' => $token['access_token']
               );
                
                //Доступ о выводе информации о профиле
                $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
                //Получаем ID 
                if (isset($userInfo['response'][0]['uid'])) {
                    $userInfo = $userInfo['response'][0];
                    //Включаем результат
                    $result = true;
                }
            }
    
            //Если $result = true
            if ($result) {
                //Подправляем получение пола
                if($userInfo['sex'] == 1) {
                    $pol = 2;
                }elseif($userInfo['sex'] == 2) {
                    $pol = 1;
                }
            
                //Разделяем дату 
                $bdate = explode('.',$userInfo['bdate']);
                //Получаем Имя и Фамилию
                $nicks = $userInfo['first_name'].' '.$userInfo['last_name'];
                
                //Если не найден Email то Помогаем его заполнять
                if(empty($token['email'])) {
                    echo '<div id="email" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="emailLabel" aria-hidden="true">
                        <div class="modal-header">
    	                <div id="emailLabel">Как Заполнить Email</div>
                        </div>
                        <div class="modal-body">
                            Для заполнения поле Email Сделайте сдедующие действия:<br/>
                            Зайдите на ваш профиль в Вконтакте -> <b>Настройки</b> -> <b>Адрес Вашей электронной почты</b>
                        </div>  		
                        <div class="modal-footer">
    	                    <a href="auth_vk.php" class="btn">Закрыть</a>
  	                </div>
                        </div>';
                    echo '<div class="mainname">Email не заполнен</div>';
                    echo '<div class="mainpost">';
                    echo '<a href="#email" data-toggle="modal"> Как заполнить Email?</a>';
                    echo '</div>';
                    exit;
                }
                
                //Если существует определяемый Логин то ошибку выводим    
                $cnick = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE `nick` = '{$nicks}'" );
                //Если существует E-mail то отменяем все
                $cmail = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE `email` = '{$token['email']}'" );
            
                //Выводим параметр
                if( $cnick['count'] or $cmail['count']) {
                    echo engine::error('Регистрация через VK доступно только один раз');
                    echo engine::home(array('Назад','/modules/auth.php')); //Переадресация	
                    exit;
                }else {
                    //Получение IP адреса гостя    
                    $ip = engine::get_ip();
                    //Закодирование пароля
                    $shgen = engine::shgen($userInfo['uid']);
                    
                    //Загружаем данные в базу
                    $mail = new mail($glob_core['from_email']);
                    //Отпарвка На почту данные при Регистрации
                    $mail->setFromName($glob_core['name_site']);
                    $mail->send_html($profile['email'], "Регистрация через Вконтакте", "Вы Успешно зарегистрированы на сайте {$_SERVER['HTTP_HOST']} <br/> Ваш логин: {$db->safesql($nicks)}<br/> Ваш пароль: {$userInfo['uid']}");
                    
                    //Регистрируем данные
                    $db->query( "INSERT INTO `users` (`nick`,`email`,`pol`,`day`,`month`,`year`,`password`,`reg_date`,`lastdate`,`group`,`logged_ip`,`asocial`) 
                                      VALUES ('{$db->safesql($nicks)}','{$db->safesql($token['email'])}','{$pol}','{$bdate[0]}','{$bdate[1]}','{$bdate[2]}','{$shgen}','{$_date}','{$_date}','1','{$ip}','vk')" );
                    
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
    
        }else {
            header('Location: ' . $url . '?' . urldecode(http_build_query($params)) . '');
        }
    }
}
