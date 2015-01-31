<?php
switch($act):
    default:

            echo '<ul class="nav nav-tabs nav-justified">
  <li class="active"><a href="#tab_a" data-toggle="tab">Все категории</a></li>
  <li><a href="#tab_b" data-toggle="tab">Новая категория</a></li>
</ul>';
        
            echo '<div class="tab-content">';
            
            echo '<div class="tab-pane active fade in" id="tab_a">';

        //Загружаем данные с базы
        $category = $db->query("SELECT * FROM `news_category`");
            echo '<br/><div class="panel panel-default">
                    <div class="panel-heading">Все категории</div>
                    <div class="panel-body">';
			//Проверяем есть ли категории
            if($db->num_rows($category)) {
                    echo '<ul class="list-group">';
                    
                while($cat = $db->get_array($category)) {
                    echo '<li class="list-group-item"><b>ID:'.$cat['id'].'</b>&nbsp;';
			echo '<a href="/modules/news/category.php?id='.$cat['id'].'">'.$cat['name'].'</a>';
                        echo '<span class="pull-right">';
                    echo '<a href="index.php?do=category&act=editor&id='.$cat['id'].'"><img src="/engine/template/icons/notepad.png"/></a>&nbsp;&nbsp;';
		    echo '<a href="index.php?do=category&act=delete&id='.$cat['id'].'"><img src="/engine/template/icons/delete.png"/></a>';			
		        echo '</span>';
                    echo '</li>';
                }
                
                    echo '</ul>';
            }
            echo '</div></div>';
		//Переадресация на пред. старницу
		echo engine::home(array(Lang::__('Назад'),'index.php'));
    //Завершаем с редактированием категории
            
            echo '</div>';
            
        echo '<div class="tab-pane fade in" id="tab_b">';
        echo '<div class="form-horizontal">';    
        echo '<center><h2>Новая категория</h2></center>';
        echo '<div class="row"></div>';
        
        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        $submit = filter_input(INPUT_POST,'submit');
        
//Выполняет функцию если уже введена название и нажата кнопка "Создать"
if(isset($submit)) {

   //Если введенная категория уже есть в базе данных то выводит ошибку		
    $check = $db->query("SELECT * FROM `news_category` WHERE `name`='" . $db->safesql($name) ."'");
    //Проверяет введена ли название
	
	if(empty($name)) {
	    engine::error(Lang::__('Введите название'));
	}elseif ($db->get_array($check) != 0) {
	//Выводит это если существует название категории
             echo engine::error(Lang::__('Введенная категория уже существует'));
	    }else {
		//Добавляем данные в базу
	    $db->query("INSERT INTO `news_category` (`name`,`time`) VALUES ('".$db->safesql($name)."','".time()."')");
		$id_cat = $db->insert_id();
	        header('Location: index.php?do=category');
	        exit;
	}
}

//Форма
$form = new form('index.php?do=category');

//Название сайта
$form->text('<div class="form-group">');
$form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название').'</label>');
$form->text('<div class="col-sm-10">');
$form->input2(false,'name','text',false,'class="form-control"','',false); //Название сайта
$form->text('<span class="desc descl">'.Lang::__('Введите название категории').'</span>');//Описание
$form->text('</div></div>');

//Кнопка сохранения
$form->text('<div class="row"></div>');
$form->text('<center><div class="form-actions">');
 //Передаем при нажатие на кнопку
$form->submit(Lang::__('Создать'),'submit',false,'btn btn-success');
//Если хотите отменить
$form->text('<a class="btn btn-warning" href="index.php">'.Lang::__('Отменить').'</a></div></center>');

$form->display();


echo '</div></div>';

break;


case 'editor':
    
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    $id = intval($id);
            
        //Если вместо id num попытаются вставить текст то выводит ошибку
        if (!isset($id) || !is_numeric($id)) {
            header('Location: index.php');
            exit;
        }
	
        //Выводим категорию по $ID
        $category = $db->query("SELECT * FROM `news_category` WHERE `id` = '".$id."'");
            
            echo '<div class="form-horizontal">';    
            echo '<center><h2>'.Lang::__('Редактирование категории').'</h2></center>';
            echo '<div class="row"></div>';
				
	        //Через $edit[]	начинаем вывод данных с базы	
                $edit = $db->get_array($category);
				
                $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
                $update = filter_input(INPUT_POST,'update');
		//Проверяем нажата ли кнопка и если ли в поле input текст
		if(isset($update)) {
                    
		    //Если не введена название категории
		    if(empty($name)) {
			echo engine::error(Lang::__('Введите название категории!'));
		    }else{
			//Если все выполнена правильно то обновляет название в базе
		        $db->query("UPDATE `news_category` SET `name` = '".$db->safesql($name)."' WHERE `id` = '".$id."'");
			    header('Location: index.php?do=category');
                            exit;
		    }
		}
				
                    
                //Форма редактирования
                $form = new form('index.php?do=category&act=editor&id='.$id.'');

                //Название
                $form->text('<div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'name','text',$edit['name'],'class="form-control"','',false); //Название сайта
                $form->text('<span class="desc descl">'.Lang::__('Введите название категории').'</span>');//Описание
                $form->text('</div></div>');
                    //Кнопка сохранения
                    $form->text('<div class="row"></div>');
                    $form->text('<center><div class="form-actions">');
                        
                    //Передаем при нажатие на кнопку
                    $form->submit(Lang::__('Изменить'),'update',false,'btn btn-success');
                    //Если хотите отменить
                    $form->text('<a class="btn btn-warning" href="index.php?do=newcategory">'.Lang::__('Отменить').'</a></div></center>');

                $form->display();


echo '</div></div>';                
    
break;

case 'delete':
    
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    $id = intval($id);
            
        //Если вместо id num попытаются вставить текст то выводит ошибку
        if (!isset($id) || !is_numeric($id)) {
            header('Location: index.php');
            exit;
        }
	
    //Выводим всю информацию из категории
    $cat = $db->get_array($db->query("SELECT * FROM `news_category` WHERE `id` = '".$id."'"));
									
    //Если вы нажали на подтверждение то будет удалена категория со всеми новостями
    if(isset($_POST['yes_delete'])) {
        //Удаляем категорию
        $db->query('DELETE FROM `news_category` WHERE `id` = "'.$id.'"');	
        //Удаляем новости в данной категории				
        $db->query('DELETE FROM `news` WHERE `id_cat` = "'.$id.'"');
               		
	//Успешное удаление всех выбранных данных
	header('Location: index.php?do=category'); //Пред. стараница
	exit;
    }elseif(isset($_POST['no_delete'])){
	header('Location: index.php?do=category'); //Пред. стараница
        exit;
    }
						
	//Подтверждение	
	         echo '<br/><div class="panel panel-default">
                    <div class="panel-heading">Подтверждаем удаление</div>
                    <div class="panel-body">';
	    echo 'Вы действительно хотите удалить выбранную категорию? Данное действие невозможно будет отменить.<hr/>';
	//Форма удаление
	echo '<div class="form-actions"><center>';
            $form = new form('index.php?do=category&act=delete&id='.$id.'');		
	    $form->submit('Удалить','yes_delete',false,'btn btn-success');
            $form->submit('Отмена','no_delete',false,'btn');		
            $form->display();
	echo '</center></div></div></div>';
        
break;

endswitch;