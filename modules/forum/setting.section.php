<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Настройка Разделов')); //Название страницы

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
        header('Location: index.php');
        exit;
    }
        //Если разделов нет то выведит эту ошибку
        $yes_sec = $db->query('SELECT * FROM `forum_subsection`');
		
        if(!$db->num_rows($yes_sec)){
	    header('Location: index.php');
            exit;
	}
        
    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Форум</a>
            <a href="#" class="btn btn-default disabled">Настройка разделов</a>
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
            $category = $db->query("SELECT * FROM `forum_subsection` WHERE `id` = '".$id."'");
                
                echo '<div class="mainname">'.Lang::__('Редактирование раздела').'</div>';
		//вывод данных с базы	
                $edit = $db->get_array($category);
                
                echo '<div class="mainpost">';
                    $update = filter_input(INPUT_POST,'update',FILTER_DEFAULT);
                    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
                    $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_STRING);
                    
		    //Проверяем нажата ли кнопка и если ли в поле input текст
		    if(isset($update)) {
			//Если не введена название раздела
			if(empty($name)) {
			    echo engine::error(Lang::__('Введите название раздела!'));
			}elseif(empty($text)){
			    echo engine::error(Lang::__('Введите текст'));
			}else {  
			    //Если все выполнена правильно то обновляет название в базе
			    $db->query("UPDATE `forum_subsection` SET `name` = '".$db->safesql($name)."',`text` = '".$db->safesql($text)."' WHERE `id` = '".$id."'");
			    header('Location: setting.section.php');
                            exit;
			}
		    }
				
                //Форма редактирования
                $form = new form('?id='.$id.'&edit','','','class="form-horizontal"');
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Название:').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'name','text',$edit['name'],'class="form-control"');
                $form->text('</div></div>');   

                //Описание категории
                $form->text('<div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Описание').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->textarea2(false,'text',$edit['text'],'','','form-control');
                $form->text('</div></div>');   
                
                //Обновляем
                $form->text('<div class="modal-footer">');
                $form->submit(Lang::__('Обновить'),'update',false,'btn btn-success');
                $form->text('<a class="btn btn-default" href="setting.section.php">Отмена</a>');
                $form->text('</div>');   
                
                $form->display();
                echo '</div>';
        }
        //Загружаем данные с базы
        $section = $db->query("SELECT * FROM `forum_subsection`");
            echo '<div class="mainname">'.Lang::__('Список разделов').'</div>';
			//Проверяем есть ли раздела
            if($db->num_rows($section)) {
                    echo '<ul class="list-group">';
				//Если есть вывводим их всех	
                while($sec = $db->get_array($section)) {
                    echo '<li class="list-group-item"><b>ID:'.$sec['id'].'</b>&nbsp;';
                    echo '<a href="section.php?id='.$sec['id'].'">'.$sec['name'].'</a>';
                    echo '<span class="time">';
                    echo '<a href="?id='.$sec['id'].'&edit"><img src="/engine/template/icons/notepad.png"/></a>&nbsp;&nbsp;';
                    echo '<a href="?act=delete_sec&id='.$sec['id'].'"><img src="/engine/template/icons/delete.png"/></a>';
                    echo '</span></li>';
                }
                    echo '</ul>';
            }
		//Переадресация на пред. старницу
		echo engine::home(array(Lang::__('Назад'),'index.php'));
    //Завершаем с редактированием раздела
    break;

    //Удаляем категорию
    case 'delete_sec':
        //Если вместо id num попытаются вставить текст то выводит ошибку
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                header('Refresh: 1; url=index.php');
                engine::error(Lang::__('Произошла ошибка при удаление раздела')); //При ошибке
                exit;
            }
            //из $_GET в обычную переменную
            $id = (int) $_GET['id'];
			
			//Если вы нажали на подтверждение то будет удалена категория со всеми разделами и темами
			if(isset($_POST['yes_delete'])) {
                //Удаляем разделы в данной раздела				
                $db->query('DELETE FROM `forum_subsection` WHERE `id` = "'.$id.'"');
                //Удаляем темы из раздела				
                $db->query('DELETE FROM `forum_topics` WHERE `id_sec` = "'.$id.'"');	
                //Удаляем посты из раздела							
                $db->query('DELETE FROM `forum_post` WHERE `id_sec` = "'.$id.'"');
               		
				//Успешное удаление всех выбранных данных
			    echo engine::success('Раздел успешно удален');
				echo engine::home(array('Назад','setting.section.php?act=editor_sec'));
				exit;
			//Если вы нажали на отмену то переадресовывается на пред. страницу
			}elseif(isset($_POST['no_delete'])){
			    header('Location: setting.section.php?act=editor_sec'); //Пред. стараница
			}
			
			
		//Подтверждение	
		echo '<div class="mainname">Подтверждение</div>';	
		echo '<div class="mainpost">';
		echo 'Вы действительно хотите удалить выбранный раздел? Данное действие невозможно будет отменить.<hr/>';
		//Форма удаление
		echo '<div style="text-align:right;">';
        $form = new form('?act=delete_sec&id='.$id.'');		
		$form->submit('Да','yes_delete');
        $form->submit('Нет','no_delete');		
        $form->display();
		echo '</div></div>';
	break;
endswitch;






?>