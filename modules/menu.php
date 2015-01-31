<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
$templates->template('Пользовательское меню');
    
    use Shcms\Component\Provider\Json\JsonScript;
        
    //Если авторизован пользователь то переадресация на главную
    if($id_user == false) {
	header("Location: ../index.php");
	exit;
    }       
		
    //Если у пользователя есть права то может попасть в админку
    if ($users['group'] == 15) {
	$admin = '<img src="/engine/template/icons/adept.png">&nbsp;<a href="admin.php"><b style="color:green;">'.Lang::__('Панель управления').'</b></a>';
    }
		
    echo '<div class="mainname"><img src="/engine/template/icons/view.png">&nbsp;'.Lang::__('Пользовательское меню');
    echo '<span class="time">'.$admin.'</span></div>';
    //Вывод данных из json
        $string = file_get_contents(H.'engine/widget/filejson/menu.json');
        $json = new JsonScript;
        $jsond = $json->decode($string);
	
        foreach($jsond as $key => $value) {	
		
			if($key == Lang::__('Мой профиль')) {
			
				$list = '<a href="'.$value['path'].'?id='.$users['id'].'">'.$key.'</a>';
				
			}elseif($key == Lang::__('Настройки')) {
			
			    $list = '<a href="'.$value['path'].'?act=edit_profile">'.$key.'</a>';
				
			}elseif($key == Lang::__('Игнорирумые')) {
			
			    $list = '<a href="'.$value['path'].'?act=ignoredusers">'.$key.'</a>';
				
			}else {
			
			    $list = '<a href="'.$value['path'].'">'.$key.'</a>';
				
			}		
			
				echo  '<div class="posts_gl">';			
				echo  '<table cellspacing="0" callpadding="0" width="100%"><tr>';		
				echo  '<td class="icons"><img src="/engine/template/icons/'. $value['icon'] .'"></td>';
				echo  '<td class="name" colspan="10"><b>'.$list.'</b></td></tr>';
			    echo  '<tr><td class="content" colspan="10">'. $value['desc'] .'</td></tr>';	
			    echo  '</table></div>';
		
		}