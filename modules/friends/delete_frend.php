<?
    //Если существует $ID  то выполнит следующие действие
    if(isset($_GET['id']) and is_numeric($_GET['id'])) {
        //Из $_GET преобразуем обычную $ 
		$id = (int) $_GET['id'];
	    
		    //Проверка введенного $id
            $delete = $db->get_array($db->query("SELECT * FROM `friends` WHERE `id_friends` = '".$id."' AND `id_user` = '".$id_user."'"));
		        
				//Если под введенным $id отсутствует пользователь то выведит ошибку ниже указанный
		        if($id != $delete['id_friends']) {
		            engine::error(Lang::__('Произошли ошибки при удаление'),Lang::__('Пользователь не найден у вас в друзьях')); //Ошибка
		            echo engine::home(array(Lang::__('Назад'),'friends.php')); //Переадресация 
			        exit; //Закрываем доступ 
		        }
	                //Если же все правильно введено и нет ошибок выполняется удаление пользователя с обеих сторон 
	                $db->query("DELETE FROM `friends` WHERE `id_user` = '".$id_user."' AND `id_friends` = '".$id."' OR `id_user` = '".$id."' AND `id_friends` = '".$id_user."'");
                    header("Location: /modules/friends.php"); //Переадресует на переходящую страницу


    }

?>