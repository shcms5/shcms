<?php							   

 //Если передаваеммый $_GET[id] придет с отсутствием либо с ненужными параметрами выводит ошибку
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) { 
                echo engine::error(Lang::__('При работе возникли неполадки'));
                header('Refresh: 1; url=view.php?id='.$_GET['id'].'');
                exit;
        }
	//Выводим данные из базы по файлу	
    $download = $db->get_array($db->query("SELECT * FROM `files` WHERE `id` = '".$_GET['id']."'"));
        
		//Проверяем есть ли в базе указанный по id файл
		if($download['files'] == false) {
                echo engine::error(Lang::__('Скачивание невозможно'));
                header('Refresh: 1; url=view.php?id='.$_GET['id'].'');
                exit;		
		}else {
				$db->query("UPDATE `files` SET `count` = '".($download['count']+1)."',`countd` = '".($download['countd']+1)."' WHERE `id` = '".$_GET['id']."'");
		}
        //Начинаем закачку ....
		
		$files_load = $cfile->downloadFile(H.'/upload/download/files/'.$download['files'].'',$download['files']);
