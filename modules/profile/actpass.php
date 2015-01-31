<?php 
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
    //Если у него отсутствиет доступ к смене пароля то он не сможет сюда переадресоваться
    if(!$users['new_pass'] or !$users['key']) {
    	echo engine::error('К сожелению доступ по прямой ссылки закрыт');
    	echo engine::home(array(Lang::__('Назад'),'?act=edit_profile'));  
		exit;
	}
	
	if(isset($_POST['submit'])) {
		///Если Ключ активации: не будет совпадать тем что в базе то выводит ошибку
		if($_POST['key_act'] != $users['key']) {
    		echo engine::error('Ключ не совпадет тем что в базе');
   			echo engine::home(array(Lang::__('Назад'),'?act=act_pass'));  
			exit;		
		}
		
		$actpass = $db->query("UPDATE `users` SET `password` = '".$users['new_pass']."' WHERE `id` = '".$id_user."'");
		if($actpass == true) {
		    //При успешной смене
    		echo engine::success('Активация успешно прошла');
			echo engine::success('Пароль успешно изменен');
			//Если все изменено то неактивированные пароль удаляется
			$db->query("UPDATE `users` SET `new_pass` = '' WHERE `id` = '".$id_user."'");
			    //После успешно смены сбрасывает авторизацию
				setcookie('COOKIE_ID', '');
                setcookie('COOKIE_PASS', '');
                session_destroy();
   			echo engine::home(array(Lang::__('Назад'),'/index.php'));  
			exit;			
		}else {
		    //При возникновение неполадок
    		echo engine::error('При активации произошли ошибки');
			echo engine::error('Пароль не изменен');
   			echo engine::home(array(Lang::__('Назад'),'?act=act_pass'));  
			exit;					
		}
	
	}




echo '<div class="subpost">
Вам было отправлено письмо с инструкциями по активации вашего аккуанта. Пожалуйста, следуйте инструкциям в письме.
</div>';

echo '<div class="mainname">'.Lang::__('Активация нового Пароля').'</div>';
echo '<div class="mainpost">';
$form = new form('?act=act_pass');
$form->input(Lang::__('Ключ активации:'),'key_act','text');
$form->text('</div>');
$form->text('<div class="submit">');
$form->submit(Lang::__('Активировать'),'submit');
$form->text('</div>');
$form->display();


?>