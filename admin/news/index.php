<?php
define('SHCMS_ENGINE',true);
define('HOME',$_SERVER['DOCUMENT_ROOT'],true);
define(CP_DERICTORY,'../');
define(ICON_ADMIN,'../icons/');

include_once(HOME.'/engine/system/core.php');
include_once(HOME.'/admin/skins/header.php');
echo engine::admin($users['group']);

switch($do):

default:
    echo '<div class="header"><h1 class="page-title">Управление новостями</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a> </li>
            <li class="active">Поддержка</li>
        </ul></div>';
    
 echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-folder-open pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'news/index.php?do=category"><h4>'.Lang::__('Категории').'</h4></a>
                        <p class="info small">Управление категориям</p>
                    </li>   
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-bars pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'news/index.php?do=nnews"><h4>'.Lang::__('Добавить новость').'</h4></a>
                        <p class="info small">Добавление новых новостей</p>
                    </li>   
                </ul>
            </div>';

 
 
        //Загружаем данные с базы
        $new = $db->query("SELECT * FROM `news`");
            echo '<br/><div class="panel panel-default">
                    <div class="panel-heading">Все новости</div>
                    <div class="panel-body">';
			//Проверяем есть ли категории
            if($db->num_rows($new)) {
                    echo '<ul class="list-group">';
                    
                while($news = $db->get_array($new)) {
                    echo '<li class="list-group-item"><b>ID:'.$news['id'].'</b>&nbsp;';
			echo '<a href="/modules/news/view.php?id='.$news['id'].'">'.$news['title'].'</a>';
                        echo '<span class="pull-right">';
                    echo '<a href="index.php?do=enews&id='.$news['id'].'"><img src="/engine/template/icons/notepad.png"/></a>&nbsp;&nbsp;';
		    echo '<a href="index.php?do=dnews&id='.$news['id'].'"><img src="/engine/template/icons/delete.png"/></a>';			
		        echo '</span>';
                        if($news['editm'] == 1) {
                            echo '<div class="desc"><i>'.$news['textm'].','.date::make_date($news['timem']).'</i></div>';
                        }
                    echo '</li>';
                }
                
                    echo '</ul>';
            }
            echo '</div></div>';
            
            
echo engine::home(array('Назад','/admin.php'));	 
break;

//Категория
case 'category':
  echo '<div class="header"><h1 class="page-title">Категории</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Категории</li>
        </ul></div>';
    
include_once('category.php');

break;


//Добавить новость
case 'nnews':
        echo '<div class="header"><h1 class="page-title">Добавить новость</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Добавить новость</li>
        </ul></div>';
include_once('nnews.php');
break;

//Изменить новость
case 'enews':
        echo '<div class="header"><h1 class="page-title">Редактировать новость</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Изменить новость</li>
        </ul></div>';
include_once('enews.php');
break;


case 'dnews':
        echo '<div class="header"><h1 class="page-title">Удалить новость</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Удаление</li>
        </ul></div>';
    include_once('dnews.php');
    
break;    

endswitch;


include_once(HOME.'/admin/skins/footer.php');