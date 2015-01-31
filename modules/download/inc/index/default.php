<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
   
if($groups->setAdmin($user_group) == 15) {
    //Действие для администратора
    $sview .= '<div style="margin-left:85%;margin-bottom:4px;" class="btn-group">';
    $sview .= '<div class="dropdown">';
    //Пункты доступные
    $sview .= '<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">';
    $sview .= 'Управление&nbsp;<span class="caret"></span></button>';
    //Подкатегории пункта
    $sview .= '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
    $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?act=new_dir"><i class="glyphicon glyphicon-plus"></i>&nbsp;'.Lang::__('Добавить папку').'</a></li>';
                    
    $cat = $db->query('SELECT * FROM `files_dir`');
	if($db->num_rows($cat) > 0){
            //Разделяем
            $sview .= '<li class="divider"></li>';
            $sview .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="setting.in.php"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;'.Lang::__('Настройка папки').'</a></li>';
        }
    $sview .= '</ul></div>';
    $sview .= '</div>';
                    
    echo $sview;
}    

$cat = $db->query('SELECT * FROM `files_dir`');
    if($db->num_rows($cat) > 0){			
        echo '<div class="mainname">'.Lang::__('Разделы').'</div>';
        echo '<div class="mainpost"><center>';
	echo '<a class="btn btn-default" href="?act=search_file">';
        echo '<img src="/engine/template/icons/search.png">&nbsp;'.Lang::__('Поиск файлов').'</a>';
        echo '&nbsp;&nbsp;<a class="btn btn-default" href="?act=topfiles">';
        echo '<img src="/engine/template/icons/topfiles.png">&nbsp;'.Lang::__('Топ скачиваемых файлов').'</a>';
        echo '</center></div>';
    }			
        echo '<div class="mainname"><img src="/engine/template/icons/folder.png">&nbsp;'.Lang::__('Категории').'</div>';
        echo '<div class="mainpost">';
	    //Выводим счетчик папок
    	    $row = $db->get_array($db->query("SELECT COUNT(*) FROM `files_dir` WHERE `dir` = '0'"));
	        if($row[0] == false) {
		    echo engine::error(Lang::__('Папок не найдено'));
		    echo '</div></div>';
		    exit;
		}
	    //Определяем навигацию и лимит постов
	    $newlist = new Navigation($row[0],10, true); 
            //Загружаем в $upload данные из базы 
            $upload = $db->query("SELECT * FROM `files_dir` WHERE `dir` = '0' ". $newlist->limit()."");
	        //Выводим все данные где `upload` = 1	
            	while($file = $db->get_array($upload)) {
		    //Выводим счетчик всех файлов
                    $row1 = $db->get_array($db->query("SELECT COUNT(*) FROM `files` WHERE `id_dir` = '{$file['id']}' OR `idir` = '{$file['id']}'"));
		    //Путь к папкам
                    $dview  = '<table class="itable"><tbody><tr class="">';
		    $dview .= '<td class="c_icon"><img style="margin-bottom:10px;" src="/engine/template/icons/dir3.png"></td>';
		    $dview .= '<td class="c_forum">';
                    $dview .= '<b><a href="dir.php?id='.$file['id'].'">'.$file['name'].'</a></b>';
                    $dview .= '<p class="desc">'.$file['text'].'</p></td>';
		    $dview .= '<td class="c_stats"><ul>';
                    $dview .= '<li><b>'.$row1[0].'</b> Файлов</li>';
                    $dview .= '</ul></td>';
                    $dview .= '</tr></tbody></table>';
		    
                    echo $dview;
					
            	}		
			
		echo '</div>';	
		
	//Вывод навигации
    echo $newlist->pagination(); 		
