<?
		//Если пользователь не является автором файла ему остальные функции ограничены
        if($id_user != $view_file['id_user']) {
			echo engine::error(Lang::__('Доступ ограничен'));
			echo engine::home(array('Назад','view.php?id='.$id.''));
			exit;
		}		
		
		//Если пользователь подтвердил удаление файла 
        if(isset($_POST['yes_delete']))	 {
                    //Удаляем сначала скриншоты		
					@unlink(H.'upload/download/screen/'.$view_file['screen'].'');
					@unlink(H.'upload/download/screen/'.$view_file['screen_2'].'');
					@unlink(H.'upload/download/screen/'.$view_file['screen_3'].'');
				$db->query("DELETE FROM `files` WHERE `id` = '".$id."'");
				echo engine::success(Lang::__('Файл и скриншоты успешно удалены'));		
				//Переадресации
				echo engine::home(array('Назад','index.php'));
                exit;					
		}elseif(isset($_POST['no_delete'])){
			    header('Location: view.php?id='.$id.''); //Пред. стараница		
		}			
		//Подтверждение	
		echo '<div class="mainname">Подтверждение</div>';	
		echo '<div class="mainpost">';
		echo 'Вы действительно хотите удалить выбранный? Данное действие невозможно будет отменить.<hr/>';
		//Форма удаление
		echo '<div style="text-align:right;">';
        $form = new form('?act=delete&id='.$id.'');		
		$form->submit('Да','yes_delete');
        $form->submit('Нет','no_delete');		
        $form->display();
		echo '</div></div>';					
					
?>