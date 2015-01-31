<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');

$libview = $db->super_query( "SELECT * FROM `libs_files` WHERE `id` = '".intval($_GET['id'])."'" );
//Название страницы
$templates->template( Lang::__('Библиотека'),$libview['text'] );

//Класс Времени
$tdate = new Shcms\Component\Data\Bundle\DateType();

switch($do):
    //Выводит все разделы
    default:
        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default disabled">Библиотека</a>
        </div>';
        
	//Проверяем есть ли права у пользователя
        if( $users['group'] == 15 ) {
            echo '<div class="right">';
	    echo '<a class="btn btn-info" href="index.php?do=newdir">Добавить раздел</a>';
            echo '</div>';
        }	
        
	echo '<div class="mainname">'.Lang::__('Разделы').'</div>';
	    $libs = $db->query( "SELECT * FROM `libs`" );
		if($db->num_rows( $libs ) > 0) { 
                    //Всех существующих папок выводим
		    while($lib = $db->get_array($libs)) {
	                echo  '<div class="posts_gl">';
		        echo  '<table cellspacing="0" callpadding="0" width="100%"><tr>';
			    //Проверяем есть ли у пользователя права
		            if( $users['group'] == 15 ) {
			        $group  = '<a href="index.php?do=editdir&id='.$lib['id'].'">';
				$group .= '<img src="/engine/template/icons/edit.png"></a>&nbsp;';
				$group .= '<a href="index.php?do=deldir&id='.$lib['id'].'">';
				$group .= '<img src="/engine/template/icons/delete.png"></a>';
			    }
			    //Счетчик всех папок	
                            $lcount = $db->get_array($db->query( "SELECT COUNT(*) FROM `libs_files` WHERE `id_lib` = '".intval($lib['id'])."'" ) );
		        echo '<td class="icons"><img src="/engine/template/icons/dir_libs.png"></td>';
		        echo '<td class="name" colspan="10"><b><a href="index.php?do=libs&id='.$lib['id'].'">'.engine::ucfirst($lib['name']).'</a></b>';
		        echo '<span class="time">'.engine::number($lcount[0]).'&nbsp;'.$group.'</span></td>';
	                echo '</tr><tr>';
			echo '<td class="content" colspan="10">'.engine::input_text($lib['text']).'</td>';
			echo '</tr></table></div>';
	            }
		}
		else {
	            //Если папки отсутствуют
		    echo  engine::warning(Lang::__('Папок не найдено'));
                    echo '<div class="modal-footer">';
                    echo '<a class="btn btn-success" href="index.php?do=newdir">Новый раздел</a>';
                    echo '</div>';
	        }
	    echo  engine::home(array('Назад','/index.php'));
    break;
	
    //Создать раздел
    case 'newdir':	
        
        //Навигация Вверхняя
        echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Библиотека</a>
            <a href="index.php" class="btn btn-default disabled">Новый раздел</a>            
        </div>';
        
        include_once('core/newdir.php');
    break;
			
			
    //Редактировать раздел
    case 'editdir':	
        include_once('core/editdir.php');
    break;	
		
    //Удаляем раздел	
    case 'deldir':
        include_once('core/deldir.php');
    break;	

    //Выводим имеющиеся статьи
    case 'libs':	
        include_once('core/libs.php');
    break;
	
    //Создаем новую статью
    case 'newfiles':	
        include_once('core/newfiles.php');	
    break;			
	
    //Редактировать статью	
    case 'editfiles':	
        include_once('core/editfiles.php');	
    break;		

    //Удаляем статью
    case 'delfiles':	
	include_once('core/delfiles.php');	
    break;
						
    //Выводим статью
    case 'view':	
        include_once('core/view.php');
    break;
					
    //Коментарии к статьям
    case 'comment':	
        include_once('core/comment.php');
    break;
					
endswitch;