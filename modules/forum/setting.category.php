<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Настройка Категорий')); //Название страницы
    
    //Отключения форума
	//Вывод определенных данных
    $off_forum = $db->get_array($db->query("SELECT * FROM `off_modules`"));
	if($off_forum['off_forum'] == 1) {
	    echo engine::error(Lang::__('Форум приостановлен с ').date::make_date($off_forum['time_forum']),$off_forum['text_forum']); //Ошибка об отключении и дополнительный текст
	    echo engine::home(array('Назад','/index.php'));	 
	    exit;
	}
	//Если у тебя права 15 то ты можешь приступить к работе
    if($groups->setAdmin($user_group) !=  15) {
        echo engine::error('У вас нет прав для доступа');
        header('Location: index.php');
        exit;
    }
        //Если категорий нет то выведит эту ошибку
        $yes_cat = $db->query('SELECT * FROM `forum_category`');
		
            if(!$db->num_rows($yes_cat)){
		header('Location: index.php');
                exit;
	    }


    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Форум</a>
            <a href="#" class="btn btn-default disabled">Настройка категории</a>
        </div>';
    
    switch($act):
	
        //По умолчанию
        default:

        if(isset($_GET['edit']) == 'edit') {
            //Обработка полученного ID
            $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
            //Проверка нуммерации данных
            $id = intval($id);
            
            //Если вместо id num попытаются вставить текст то выводит ошибку
            if (!isset($id) || !is_numeric($id)) {
                header('Location: index.php');
                exit;
            }
			//Выводим категорию по $ID
            $category = $db->query("SELECT * FROM `forum_category` WHERE `id` = '".$id."'");
                echo '<div class="mainname">'.Lang::__('Редактирование категории').'</div>';
				
			//Через $edit[]	начинаем вывод данных с базы	
            $edit = $db->get_array($category);
                echo '<div class="mainpost">';
                
                    $update = filter_input(INPUT_POST,'update',FILTER_DEFAULT);
                    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
                    
			//Проверяем нажата ли кнопка и если ли в поле input текст
			if(isset($update)) {
			    //Если не введена название категории
			    if(empty($name)) {
				echo engine::error(Lang::__('Введите название категории!'));
			    }else {
			        //Если все выполнена правильно то обновляет название в базе
				$db->query("UPDATE `forum_category` SET `name` = '".$db->safesql($name)."' WHERE `id` = '".$id."'");
				header('Location: setting.category.php');
                                exit;
			    }
			}
				
               //Форма редактирования
                $form = new form('?id='.$id.'&edit','','','class="form-horizontal"');
                //Название 
                $form->text('<br/><div class="form-group">'); 
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Название:').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'name','text',$edit['name'],'class="form-control"');
                $form->text('</div></div>');   
                
                $form->text('<div class="modal-footer">');
                $form->submit(Lang::__('Обновить'),'update',false,'btn btn-success');
                $form->text('<a class="btn btn-default" href="setting.section.php">Отмена</a>');
                $form->text('</div>');  
                $form->display();
            echo '</div>';
        }
        //Загружаем данные с базы
        $category = $db->query("SELECT * FROM `forum_category`");
            echo '<div class="mainname">'.Lang::__('Список категорий').'</div>';
			//Проверяем есть ли категории
            if($db->num_rows($category)) {
                    echo '<ul class="list-group">';
				//Если есть вывводим их всех	
                while($cat = $db->get_array($category)) {
                    echo '<li class="list-group-item"><b>ID:'.$cat['id'].'</b>&nbsp;<a href="category.php?id='.$cat['id'].'">'.$cat['name'].'</a>
                        <span class="time">
                        <a href="?act=editor_cat&id='.$cat['id'].'&edit"><img src="/engine/template/icons/notepad.png"/></a>&nbsp;&nbsp;
                        <a href="?act=delete_cat&id='.$cat['id'].'"><img src="/engine/template/icons/delete.png"/></a>
                        </span></li>';
                }
                    echo '</ul>';
            }
    break;

    //Удаляем категорию
    case 'delete_cat':
        //Если вместо id num попытаются вставить текст то выводит ошибку
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                header('Refresh: 1; url=index.php');
                engine::error(Lang::__('Произошла ошибка при удаление категории')); //При ошибке
                exit;
            }
            //из $_GET в обычную переменную
            $id = (int) $_GET['id'];
			
			//Если вы нажали на подтверждение то будет удалена категория со всеми разделами и темами
			if(isset($_POST['yes_delete'])) {
                //Удаляем категорию
                $db->query('DELETE FROM `forum_category` WHERE `id` = "'.$id.'"');	
                //Удаляем разделы в данной категории				
                $db->query('DELETE FROM `forum_subsection` WHERE `id_cat` = "'.$id.'"');
                //Удаляем темы из категории				
                $db->query('DELETE FROM `forum_topics` WHERE `id_cat` = "'.$id.'"');	
                //Удаляем посты из категории							
                $db->query('DELETE FROM `forum_post` WHERE `id_cat` = "'.$id.'"');
               		
				//Успешное удаление всех выбранных данных
			    echo engine::success('Категория успешно удалена');
				echo engine::home(array('Назад','setting.category.php'));
				exit;
			//Если вы нажали на отмену то переадресовывается на пред. страницу
			}elseif(isset($_POST['no_delete'])){
			    header('Location: setting.category.php'); //Пред. стараница
			}
			
			
		//Подтверждение	
		echo '<div class="mainname">Подтверждение</div>';	
		echo '<div class="mainpost">';
		echo 'Вы действительно хотите удалить выбранную категорию? Данное действие невозможно будет отменить.<hr/>';
		//Форма удаление
		echo '<div style="text-align:right;">';
        $form = new form('?act=delete_cat&id='.$id.'');		
		$form->submit('Да','yes_delete');
        $form->submit('Нет','no_delete');		
        $form->display();
		echo '</div></div>';
	break;
endswitch;






?>