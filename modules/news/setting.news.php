<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Настройка Новостей')); //Название страницы

//Если у тебя права 15 то ты можешь приступить к работе
if($groups->setAdmin($user_group) !=  15) {
    header('Location: index.php');
    exit;
}
        
    //Если разделов нет то выведит эту ошибку
    $yes_sec = $db->query('SELECT * FROM `news`');
	if(!$db->num_rows($yes_sec)){
	    header('Location: index.php');
            exit;
   }

//Делаем облегчение создаем 2разных фукнции в одной станице
switch($act):

    //Редактируем категорию
    default:
        //Обработка передаваемого $_GET параметра
        $edit = filter_input(INPUT_GET,'edit',FILTER_SANITIZE_STRING);
        //Обработка полученного $_GET параметра
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        //Проверка находится ли там номерные данные
        $id = intval($id);
        
        //Если $edit true
        if(isset($edit) == 'edit') {
            //Если вместо id num попытаются вставить текст то выводит ошибку
            if (!isset($id) || !is_numeric($id)) {
                header('Location: index.php');
                exit;
            }
	//Выводим категорию по $ID
        $category = $db->query("SELECT * FROM `news` WHERE `id` = '".$id."'");
        
        //Редактирование новостей
        echo '<div class="mainname">'.Lang::__('Редактирование новостей').'</div>';
	    //Создаем массив ошибок
            $error = array();
            //Вытастиваем все данные по ID
            $edit = $db->get_array($category);
            //Обработка полученного названия
            $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
            //Обработка кнопки
            $update = filter_input(INPUT_POST,'update');
            //Обработка текста
            $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
            //Обработка краткого описания
            $cr_news = filter_input(INPUT_POST,'cr_news',FILTER_SANITIZE_STRING);
            
            echo '<div class="mainpost">';
		//Проверяем нажата ли кнопка и если ли в поле input текст
		if(isset($update)) {
		    //Проверяем существует ли название
		    if(empty($name)) {
			$error['name'][] = Lang::__('Введите название новости!');
		    }
                    //Проверяем существует ли текст
                    if(empty($text)){
			$error['text'][] = Lang::__('Введите описание');
		    }
                    //Проверяем существует ли краткого описание
                    if(empty($cr_news)){
                       $error['cr_news'][] = Lang::__('Введите краткое описание');
                    }elseif(empty($error)) {  
                        //Если не найдено ошибок начинаем обновление данных
			$db->query("UPDATE `news` SET `title` = '".$db->safesql($name)."',`cr_news` = '".$db->safesql($cr_news)."',`text` = '".$db->safesql($text)."' WHERE `id` = '".$id."'");
			header('Location: setting.news.php?act=editor_sec');
			exit;
		    }
		}
                
            //Форма редактирования новостей
            $form = new form('?act=editor_sec&id='.$id.'&edit');
            $form->input(Lang::__('Название:'),'name','text',$edit['title'],(isset($error['name']) ? '<span style="color:red"><small>' . implode('<br />', $error['name']) . '</small></span><br />' : ''));
            $form->textarea(Lang::__('Полное описание:'),'text',$edit['text'],(isset($error['text']) ? '<span style="color:red"><small>' . implode('<br />', $error['text']) . '</small></span><br />' : ''));
	    $form->textarea(Lang::__('Краткое описание:'),'cr_news',$edit['cr_news'],(isset($error['cr_news']) ? '<span style="color:red"><small>' . implode('<br />', $error['cr_news']) . '</small></span><br />' : ''));
            $form->submit(Lang::__('Обновить'),'update');
            $form->display();
            
            echo '</div>';
        }
        
    //Загружаем данные с базы
    $section = $db->query("SELECT * FROM `news`");
            
    echo '<div class="mainname">'.Lang::__('Список новостей').'</div>';
	//Проверка на доступность разделов
        if($db->num_rows($section)) {
            echo '<div class="mainpost">';
            
	    //Если все TRUE то выводим 	
            while($sec = $db->get_array($section)) {
                //Заносим данные в $eview
                $eview  = '<div class="sortable"><b>ID:'.$sec['id'].'</b>&nbsp;';
                $eview .= '<a href="view.php?id='.$sec['id'].'">'.$sec['title'].'</a>';
                $eview .= '<span class="time">';
                $eview .= '<a href="?id='.$sec['id'].'&edit">';
                $eview .= '<img src="/engine/template/icons/edit.png"/></a>&nbsp;&nbsp;';
                $eview .= '<a href="?act=delete&id='.$sec['id'].'">';
                $eview .= '<img src="/engine/template/icons/delete.png"/></a>';
                $eview .= '</span></div>';
                //Выводит все данные
                echo $eview;
            }
            echo '</div>';
        }
    
    //Переадресация на пред. старницу
    echo engine::home(array(Lang::__('Назад'),'index.php'));

    
    break;

    //Удаляем новость
    case 'delete':
        //Обработка полученного $_GET
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        //Проверка на номерные символы
        $id = intval($id);
        //Обработка кнопки Удаление
        $yes_delete = filter_input(INPUT_POST,'yes_delete');
        //Обработка кнопки Отмена удаление
        $no_delete = filter_input(INPUT_POST,'no_delete');
        
        //Если вместо id num попытаются вставить текст то выводит ошибку
        if (!isset($id) || !is_numeric($id)) {
            header('Location: index.php');
            exit;
        }
			
	//Если вы нажали на подтверждение то будет удалена новость с комментариями
	if(isset($yes_delete)) {
            //Удаляем новость			
            $db->query('DELETE FROM `news` WHERE `id` = "'.$id.'"');
            //Удаляем комментарии к новости			
            $db->query('DELETE FROM `news_comment` WHERE `id_news` = "'.$id.'"');	
            header('Location: setting.news.php?act=editor_sec');
	    exit;
	}elseif(isset($no_delete)){
	    header('Location: setting.news.php?act=editor_sec'); //Пред. стараница
            exit;
	}
			
			
    //Подтверждение удаление	
   echo '<div class="mainname">Подтверждение</div>';	
	echo '<div class="mainpost">';
	echo 'Вы действительно хотите удалить выбранную новость? Данное действие невозможно будет отменить.<hr/>';
		
            //Форма удаление
	    echo '<div style="text-align:right;">';
                $form = new form('?act=delete&id='.$id.'');		
		$form->submit('Да','yes_delete');
                $form->submit('Нет','no_delete');		
                $form->display();
	    echo '</div></div>';
            
    break;
    
endswitch;
