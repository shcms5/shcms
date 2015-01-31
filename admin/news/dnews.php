<?php
switch($act):
    
    default:
     
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($id);
        
                
        $deletes = $db->query("SELECT * FROM `news` WHERE `id` = '{$id}'");
        //Чтобы не было Непредвиденных ситуаций
        if($db->num_rows($deletes) < 1) {
            header('Location: /');
        }else {
            $delete = $db->get_array($deletes);
        }
        
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
             header('Location: index.php'); //Пред. стараница
	    exit;
	}elseif(isset($no_delete)){
	    header('Location: index.php'); //Пред. стараница
            exit;
	}        
        
    //Подтверждение удаление	
    echo '<div class="panel panel-default">
            <a href="#" class="panel-heading" data-toggle="collapse">Удалить новость</a>
            <div class="panel-body collapse in">';
    echo engine::error('Вы действительно хотите удалить выбранную новость? Данное действие невозможно будет отменить.');
		
            //Форма удаление
	    echo '<div class="form-actions" style="text-align:center;">';
                $form = new form('index.php?do=dnews&id='.$id.'');		
		$form->submit('Удалять','yes_delete',false,'btn btn-primary');
                $form->submit('Оставить','no_delete',false,'btn');		
                $form->display();
	    echo '</div></div></div>';
            
        
        
    break;

endswitch;    