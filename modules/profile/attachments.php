<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
    //Дополнительные действия
		switch($do):
            //Действие с Игнорированными личными сообщениями
			case 'delete':
				
				//Если передаваеммый $_GET[id] придет с отсутствием либо с ненужными параметрами выводит ошибку
				if(!isset($_GET['id']) || !is_numeric($_GET['id'])) { 
                    echo engine::error(Lang::__('При работе возникли неполадки'));
                    header('Refresh: 1; url=?act=attachments');
                    exit;
                }
				    $delete = $db->get_array($db->query("SELECT * FROM `files` WHERE `id` = '".$_GET['id']."'"));
				//Если существует файл на сервере то выполнит следующие действие ....
				if (is_file(H.'upload/download/files/'.$delete['files'])) {
				    //Удаление файла и всех его дополнительных данных
						$db->query('DELETE FROM `files` WHERE `id` = '.$delete['id'].'');
					//Удаление всех комментариев к данному файлу
						$db->query('DELETE FROM `down_comment` WHERE `id_file` = '.$delete['id'].'');
					//После успешного удаление переадресует на предыдущую страницу
                    	header('Location: ?act=attachments');
				}else {
                    echo engine::error(Lang::__('Файл не найдет возможно он был удален ранее'));
		        echo engine::home(array(Lang::__('Назад'),'?act=edit_profile'));
                    exit;				    
				}
			break;
		endswitch;			
		
		
	        //Все добавленные вами файлы
			echo '<div class="mainname">'.Lang::__('Прикрепленные файлы').'</div>';
				echo '<div class="mainpost">';
				    //Выводим счетчик действий
                    if ($result = $db->query("SELECT COUNT(*) FROM `files` WHERE `id_user` = '".$id_user."'")) {
                        /* Переход к строке №400 */
                        $result->data_seek(399);
                        /* Получение строки */
                        $row = $result->fetch_row();
                    }
						
			//Если еще не добавлено айлов дальше закрыт доступ exit();
	        if($row[0] == false) {
                echo engine::error(Lang::__('Результатов нет'));
		        echo engine::home(array(Lang::__('Назад'),'?act=edit_profile'));
		        echo '</div>';
		        exit;
	        }
		    //Подключаем навигацию
                $newlist = new Navigation($row[0],10,true); 
				
	        		echo "<table class='little-table' cellspacing='0'>";
                    //Заглавные имена таблицы					
            		echo "<tr><th>".Lang::__('Файл')." </th> 
                    		<th>".Lang::__('Размер')."</th> 
                    		<th>".Lang::__('Тема')."</th></tr>"; 
						//Выводим все добавленные вами файлы из обменника	
                        $file = $db->query("SELECT * FROM `files` WHERE `id_user` = '".$id_user."' ORDER BY `id` DESC ". $newlist->limit()."");
							//Начинаем вывод ......
							while($files = $db->get_array($file)) {
								//Если не существует имя файла то выводит Без названия. расширение файла
								if($files['name'] == NULL) {
						    		$namefile = 'Без_названия.'.engine::format($files['files']);
								//А если существует то выводим название
								}else {
						    		$namefile = $files['name'];
								}
						    //Определяем в какую папку был добавлен файл
							$dir = $db->get_array($db->query("SELECT * FROM `files_dir` WHERE `id` = '".$files['id_dir']."'"));
						        //Появится все файлы, количество загрузок, размеры файлов, путь где был взят файл, действие дополнительное
						 		echo "<tr class='even'> 
                            		<td><a style='color: #225985;' href='/modules/download/view.php?id=".$files['id']."'>".$namefile."</a><br/>
									".Lang::__('Загрузок').": <b>".engine::number($files['count'])."</b></td> 
									<td><a style='color:green;' class='Button_secondary' href='#'>".engine::filesize($files['filesize'])."</a></td>
                            		<td><a href='/modules/download/dir.php?id=".$dir['id']."'>".$dir['name']."</a></td>";
								//Действие удаление файла
								echo "<td><a style='color:red;' class='Button_secondary' href='?act=attachments&do=delete&id=".$files['id']."'>".Lang::__('Удалить')."</a></td> ";
                            	echo "</tr></tr>";
	                    	}
					//Далее закрываем таблицу	
					echo '</table>';
				echo '</div>';
			
			//Вывод навигации
            echo $newlist->pagination('act=ignoredusers'); 				
			
			//Переадресация		
			echo engine::home(array(Lang::__('Назад'),'?act=edit_profile'));
			