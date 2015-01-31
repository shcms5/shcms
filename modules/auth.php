<?php

define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
engine::auth();
$templates->template(Lang::__('Авторизация'));


//Действующая время
$time = time();
//Хешированный логин
$hash_login = '';
//Данные о браузере 
$AboutGuest = new AboutGuest;

$submit = filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);

require_once 'auth/Controller/Auth.php';
require_once 'auth/Social/VkAuth.php';
require_once 'auth/Social/FcAuth.php';

$auth = new Auth();

switch($do):

default:    
    
    
if(isset($submit)) {
	
    $login = $db->safesql(filter_input( INPUT_POST, 'nick', FILTER_SANITIZE_STRING ) );
    $password = $db->safesql(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) );
    $code = filter_input(INPUT_POST,'code',FILTER_SANITIZE_NUMBER_INT);
    $auth->Login(array('login' => $login,'password' => $password,'code' => $code));
	
}
//Форма авторизации
echo '<div class="mainname">'.Lang::__('Авторизация').'</div>';
echo '<div class="mainpost">';

echo $auth->Form(array('login' => htmlspecialchars($login)));

echo '</div></div>';

break;

//Авторизация Через Вконтакте
case 'vk':
    
    $vkauth = new VkAuth(array('id' => $social['vk_id'],'key' => $social['vk_key'],'close' => $social['vk_close']));

    echo $vkauth->vk();
    
break;

//Авторизация через Фейзбук
case 'fc':
    
    $fcauth = new FcAuth(array('id' => $social['fc_id'],'key' => $social['fc_key'],'close' => $social['fc_close']));
    echo $fcauth->fc();
    
break;    

endswitch;