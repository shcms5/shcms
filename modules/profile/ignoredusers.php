<?php
if(!defined('SHCMS_ENGINE')) {
	die( "Неправильное действие" );
}
//Выводим данные с таблицы ignor
$ignor = $db->get_array($db->query("SELECT * FROM `ignor`"));
    
	//Если нажата кнопка то выполняется действие ниже указанные
    if(isset($_POST['submit'])) {
	    
		//Обрабатываем название
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
		
	        //Проверяется введена ли Имя(Логин)
            if(empty($name)) {
			    echo engine::error(Lang::__('Не указано Имя'));
			    echo engine::home(array(Lang::__('Назад')),'?act=ignoredusers');
			    exit;		
		    }
		
		    //Если введенный вами Имя уже существует в базе выводит вам ошибку
		    $iguser = $db->get_array($db->query("SELECT * FROM `users` WHERE `nick` = '" . $db->safesql($name) ."'"));
                if (!$iguser['nick']) {
			        echo engine::error(Lang::__('Не удалось найти пользователя с таким именем'));
			        echo engine::home(array(Lang::__('Назад')),'?act=ignoredusers');
			        exit;
	            }
		
		        //Если вы себя попытаетесь игнорирвоать то попытка неудастся
		        if($iguser['id'] == $id_user) {
			        echo engine::error(Lang::__('Вы не можете игнорировать этого пользователя'));
			        echo engine::home(array(Lang::__('Назад')),'?act=ignoredusers');
			        exit;		
		        }
		
	            //Если введенный вами Имя уже вы добавили в игнорируемые то в следующий раз нельзя будет
		        $there = $db->get_array($db->query("SELECT * FROM `ignor` WHERE `id_ignor` = '" . $iguser['id'] ."' AND `id_user` = '".$id_user."'"));
                    if ($there['id_ignor'] == true) {
			            echo engine::error(Lang::__('Пользователь уже находится в списке игнорируемых'));
			            echo engine::home(array(Lang::__('Назад')),'?act=ignoredusers');
			            exit;
	                }
		        //Добавляем нового пользователя в список игнорируеммых
		        $mysql = $db->query("INSERT `ignor` (`id_user`,`id_ignor`,`ignor_mess`,`ignor_prof`,`ignor_poch`) VALUES ('".$id_user."','".$iguser['id']."','".$_POST['ignor_mess']."','".$_POST['ignor_prof']."','".$_POST['ignor_poch']."')");
		            //Если все указано верно то добавится успешно
					if($mysql == true) {
				        echo engine::success(Lang::__('Настройки сохранены'));
				        echo engine::home(array(Lang::__('Назад')),'?act=ignoredusers');
				        exit;
                    //Если же имеются ошибки то не добавится						
			        }else {
				        echo engine::error(Lang::__('Настройки не сохранены'));
				        echo engine::home(array(Lang::__('Назад')),'?act=ignoredusers');
				        exit;					
			        }
		
		
    }
        //Форма добавления новых пользователей
	    echo '<div class="mainname">'.Lang::__('Добавить новых пользователей в список').'</div>';

        echo '<div class="mainpost">';
            $form = new form('?act=ignoredusers');
            $form->input(Lang::__('Имя пользователя:'),'name','text');
            $form->input2(false,'ignor_prof','checkbox',1,($ignor['ignor_prof']?'checked="checked"':''),Lang::__('Игнорировать профиль '));
            $form->input2(false,'ignor_mess','checkbox',1,($ignor['ignor_mess']?'checked="checked"':''),Lang::__('Игнорировать личные  сообщения '));
                $form->text('</div>');
                $form->text('<div class="submit">');
            $form->submit(Lang::__('Сохранить','submit'));
                $form->text('</div><br/>');
            $form->display();
        //Заканчиваем с формой
		//Управление игнорируеммыми пользователями
		echo '<div class="mainname">'.Lang::__('Управление списком игнорируемых пользователей').'</div>';
            echo '<div class="mainpost">';
	
	        //Выводим счетчик действий
            if ($result = $db->query("SELECT COUNT(*) FROM `ignor` WHERE `id_user` = '".$id_user."'")) {
                /* Переход к строке №400 */
                $result->data_seek(399);
                /* Получение строки */
                $row = $result->fetch_row();
            }
	        
			//Если еще не добавлено никого в список дальше доступ закрывается exit();
	        if($row[0] == false) {
                echo engine::error(Lang::__('Результатов нет'));
		        echo engine::home(array(Lang::__('Назад'),'?act=edit_profile'));
		        echo '</div>';
		        exit;
	        }
		    //Подключаем навигацию
                $newlist = new Navigation($row[0],10,true);   
		
	        //Выводим таблицы данных с игнорируемыми пользователями
	        echo "<table class='little-table' cellspacing='0'>"; 
                echo "<tr> 
                    <th>".Lang::__('Имя')." </th> 
                    <th>".Lang::__('Игнорировать сообщения')."</th> 
                    <th>".Lang::__('Игнорировать профиль')."</th> 
                    </tr>";

		    //Выводим данные из таблицы где $id
            $table = $db->query("SELECT * FROM `ignor` WHERE `id_user` = '".$users['id']."' ORDER BY `id` DESC ". $newlist->limit()."");
                //Начинается вывод всех добавленных в игнор
				while($tables = $db->get_array($table)) {
                    //Определяем через $id_user ник пользователя 
					$ignor_user = $user->users($tables['id_ignor'],array('nick'));
					//Определяем через $id_user id пользователя 
	                $user_users = $user->users($tables['id_user'],array('nick'));
					
					    //Дополнительные действия
						switch($do):
                            //Действие с Игнорированными личными сообщениями
							case 'messages':
							    //Если передаваеммый $_GET[id] придет с отсутствием либо с ненужными параметрами выводит ошибку
							    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) { 
                                    echo engine::error(Lang::__('При работе возникли неполадки'));
                                    header('Refresh: 1; url=?act=ignoredusers');
                                    exit;
                                }
							    //Если вы открыли доступ к личным сообщениям указанному пользователю то он может вам отправлять и просматривать вашу с ним переписку 
								if($tables['ignor_mess'] == NULL) {
									$db->query("UPDATE `ignor` SET `ignor_mess` = '1' WHERE id = '".$_GET['id']."'");
                                   header('Location: ?act=ignoredusers');
								//Если вы закрыли доступ к личным сообщениям указанному пользователю  то ему все действие будут закрыты
							   }elseif($tables['ignor_mess'] == 1) {
									$db->query("UPDATE `ignor` SET `ignor_mess` = '' WHERE id = '".$_GET['id']."'");
                                    header('Location: ?act=ignoredusers');							   
							   }
							break;
							
							//Действие с Игнорированным профилем
							case 'profile':
							    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) { 
                                    echo engine::error(Lang::__('При работе возникли неполадки'));
                                    header('Refresh: 1; url=?act=ignoredusers');
                                    exit;
                                }
							    //Если вы открыли доступ к личному указанному пользователю то он может просматривать ваши данные и добавлять в друзья 
								if($tables['ignor_prof'] == NULL) {
									$db->query("UPDATE `ignor` SET `ignor_prof` = '1' WHERE id = '".$_GET['id']."'");
                                   header('Location: ?act=ignoredusers');
								//Если вы закрыли доступ к профилю указанному пользователю то ему все действие будут закрыты
							   }elseif($tables['ignor_prof'] == 1) {
									$db->query("UPDATE `ignor` SET `ignor_prof` = '' WHERE id = '".$_GET['id']."'");
                                    header('Location: ?act=ignoredusers');							   }
							break;
                            
							//Удаляем из списка игнорируеммых
							case 'delete':
							        //Выбранный пользователь будет удален из вашего списка игнорируеммых
									$db->query("DELETE FROM `ignor` WHERE id = '".$_GET['id']."'");
                                    header('Location: ?act=ignoredusers');										
							break;
							
							

						endswitch;
					
					//Обработка номер на определенное значение
					$arraylist = array(1 => '<font color="green">'.Lang::__('Показать').'</font>', NULL => '<font color="red">'.Lang::__('Блокировать').'</font>');
					
                        //Все доступные действие и их управление
						echo "<tr class='even'> 
                            <td><a style='color: #225985;' href='?act=view&id=".$tables['id_ignor']."'>".$ignor_user."</a><br/>
							<span style='font-size:10px;'>".Lang::__('Ограничили доступ')."</span>
							</td> 
							<td><a class='Button_secondary' href='?act=ignoredusers&do=messages&id=".$tables['id']."'>".$arraylist[$tables['ignor_mess']]."</a></td> 
                            <td><a class='Button_secondary' href='?act=ignoredusers&do=profile&id=".$tables['id']."'>".$arraylist[$tables['ignor_prof']]."</a></td> 
							  <td><a class='Button_secondary' href='?act=ignoredusers&do=delete&id=".$tables['id']."'>".Lang::__('Удалить')."</a></td> 
                            </tr></tr> ";
				}			
			//Далее закрываем таблицу	
            echo "</table> </div>";
				
				//Вывод навигации
                echo $newlist->pagination('act=ignoredusers'); 
				
		//Переадресация		
		echo engine::home(array(Lang::__('Назад'),'?act=edit_profile'));