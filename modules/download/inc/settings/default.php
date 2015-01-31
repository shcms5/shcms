<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
$edit = filter_input(INPUT_GET,'edit');
//Обработка полученного $_GET[id]
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$id = intval($id);

    //Навигация Вверхняя
    echo '<div class="btn-group btn-breadcrumb margin-bottom">
            <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
            <a href="index.php" class="btn btn-default">Загрузка</a>
            <a href="#" class="btn btn-default disabled">Настройка папок</a>
        </div>';

if(isset($edit) == 'edit') {
    //Если вместо id num попытаются вставить текст то выводит ошибку
    if (!isset($id) || !is_numeric($id)) {
        header('Location: index.php');
    }

    //Выводим категорию по $ID
    $dir_in = $db->query("SELECT * FROM `files_dir` WHERE `id` = '".$id."'");
        echo '<div class="mainname">'.Lang::__('Редактирование папки').'</div>';
	    //Вывод данных 
            $edit = $db->get_array($dir_in);
            //Создаем массив ошибок
            $error = array();
            //Обработка кнопки
            $update = filter_input(INPUT_POST,'update');
           //Обработка названия
            $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
                echo '<div class="mainpost">';
		    //Проверяем нажата ли кнопка и если ли в поле input текст
		    if(isset($update)) {
                        //Если не введена название папки
			if(empty($name)) {
			    $error['name'][] = Lang::__('Введите название папки!');
			}else {
			    //Если все выполнена правильно то обновляет название в базе
			    $db->query("UPDATE `files_dir` SET `name` = '".$db->safesql($name)."' WHERE `id` = '".$id."'");
			    header('Location: setting.in.php?act=editor_cat');
			    exit;
			}
		    }
				
         //Форма редактирования

            $form = new form('?act=editor_cat&id='.$id.'&edit','','','class="form-horizontal"');
            //Наименование Категории
            $form->text('<br/><div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label">'.Lang::__('Название папки:').'</label>');
            $form->text('<div class="col-sm-10">');
            $form->input2(false,'name','text',$edit['name'],'class="form-control"');
                //При ошибке
                if(isset($error['name'])) {
                    $form->text('<p class="text-danger small">'.implode('<br/>', $error['name']).'</p>');
                }  
            $form->text('</div></div>');   
            $form->text('<div class="modal-footer">');
            $form->submit(Lang::__('Изменить'),'update',false,'btn btn-success');
            $form->text('<a class="btn btn-default" href="setting.in.php">Отмена</a>');
            $form->text('</div>');
                    $form->display();
                    
	echo '</div>';
			
    }
	
        //Загружаем данные с базы
        $dir_in = $db->query("SELECT * FROM `files_dir` WHERE `dir` = '0' ORDER BY `id` DESC");
            
			echo '<div class="mainname">'.Lang::__('Список категорий').'</div>';
			
			//Проверяем есть ли категории
            if($db->num_rows($dir_in)) {
			
                    echo '<ul class="list-group">';
				
				//Если есть выводим их всех	
                while($cat = $db->get_array($dir_in)) {
				
                    echo '<li class="list-group-item"><b>ID:'.$cat['id'].'</b>&nbsp;<a href="index.php">'.$cat['name'].'</a>
                        <span class="time">
                        <a href="?act=editor_cat&id='.$cat['id'].'&edit">';
                    echo '<img src="/engine/template/icons/notepad.png"/></a>&nbsp;&nbsp;
                        <a href="?act=delete_cat&id='.$cat['id'].'">';
                    echo '<img src="/engine/template/icons/delete.png"/></a>
                        </span></li>';
						
                }
				
                    echo '</ul>';
					
            }
			
    //Переадресация на пред. старницу
	echo engine::home(array(Lang::__('Назад'),'index.php'));