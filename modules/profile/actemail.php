<?php 
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
    //Если у него отсутствиет доступ к смене пароля то он не сможет сюда переадресоваться
    if(!$users['new_email'] or !$users['key']) {
    	echo engine::error('К сожелению доступ по прямой ссылки закрыт');
    	echo engine::home(array(Lang::__('Назад'),'?act=edit_profile'));  
		exit;
	}
	
	if(isset($_POST['submit'])) {
	    ///Если ID пользователя: не будет совпадать с вашим то выводит ошибку
	    if($_POST['id_list'] != $users['id']) {
    		echo engine::error('ID не совпадает с вашим');
   			echo engine::home(array(Lang::__('Назад'),'?act=act_email'));  
			exit;			     
		}
		///Если Ключ активации: не будет совпадать тем что в базе то выводит ошибку
		if($_POST['key_act'] != $users['key']) {
    		echo engine::error('Ключ не совпадет тем что в базе');
   			echo engine::home(array(Lang::__('Назад'),'?act=act_email'));  
			exit;		
		}
		
		$actemail = $db->query("UPDATE `users` SET `email` = '".$users['new_email']."' WHERE `id` = '".$id_user."'");
		if($actemail == true) {
		    //При успешной смене
    		echo engine::success('Активация успешно прошла');
			echo engine::success('Email успешно изменен');
			//Если все изменено то неактивированные email удаляется
			$db->query("UPDATE `users` SET `new_email` = '' WHERE `id` = '".$id_user."'");
   			echo engine::home(array(Lang::__('Назад'),'?act=email'));  
			exit;			
		}else {
		    //При возникновение неполадок
    		echo engine::error('При активации произошли ошибки');
			echo engine::error('Email не изменен');
   			echo engine::home(array(Lang::__('Назад'),'?act=act_email'));  
			exit;					
		}
	
	}




echo '<div class="subpost">
Вам было отправлено письмо с инструкциями по активации вашего аккуанта. Пожалуйста, следуйте инструкциям в письме.
</div>';

echo '<div class="mainname">'.Lang::__('Активация нового Email адреса').'</div>';
echo '<div class="mainpost">';
$form = new form('?act=act_email');
$form->input(Lang::__('ID пользователя:'),'id_list','text');
$form->input(Lang::__('Ключ активации:'),'key_act','text');
$form->text('</div>');
$form->text('<div class="submit">');
$form->submit(Lang::__('Активировать'),'submit');
$form->text('</div>');
$form->display();
