<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="/modules/menu.php" class="btn btn-default">Основное меню</a>
            <a href="?id='.$id_user.'" class="btn btn-default">Профиль</a>
                <a href="#" class="btn btn-default disabled">Изменить данные</a>
        </div>';

    echo '<div class="mainname">';
    echo '<img src="/engine/template/icons/edit.png">&nbsp;'.Lang::__('Изменение данных');
    echo '</div>';

    //Вывод данных из json
        $string = file_get_contents(H.'engine/widget/filejson/editprofile.json');
        $jsond = $json->decode($string);

	foreach($jsond as $key => $value) {
            
	    $list  = '<div class="posts_gl">';			
	    $list .= '<table cellspacing="0" callpadding="0" width="100%">';
	
                $list .= '<tr>';
                $list .= '<td class="icons"><img src="/engine/template/icons/profile/'. $value['icon'] .'"></td>';
		$list .= '<td class="name" colspan="10"><b><a href="'.$value['path'].'">'.$key.'</a>';
                $list .= '<span class="time">'.$row1[0].'</span></td>';
                $list .= '</tr>';
		$list .= '<tr>';
                $list .= '<td class="content" colspan="10">'. $value['desc'] .'</td>';
                $list .= '</tr>';
                
	    $list .= '</table>';
            $list .= '</div>';
				
	   echo $list;	
	}

    //Переадресация		
    echo engine::home(array(Lang::__('Назад'),'?id='.$id_user.''));
