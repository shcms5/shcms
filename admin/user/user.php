<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

//Пользователи
switch($act):

    default:
            echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-gavel pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'user/index.php?do=user&act=office"><h4>Управление пользователями</h4></a>
                        <p class="info small">Управление пользователями</p>
                    </li>
                   <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-question pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'user/index.php?do=user&act=status"><h4>Cтатус пользователя</h4></a>
                        <p class="info small">Статусы для пользователей</p>
                    </li>
                   <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa  fa-key pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'user/index.php?do=user&act=new_user"><h4>Создание пользователя</h4></a>
                        <p class="info small">Добавляем нового пользователя</p>
                    </li>                    
                </ul>
            </div>';


    break;
	
	
	/*
	 * Управление Пользователями + Создание пользователя
	*/
	case 'office':
    echo '<div style="text-align:right;margin-bottom:4px;"><a class="btn btn-default" href="index.php?do=user&act=new_user">Создать нового</a></div>';
        echo '<div class="row"></div>';
	//Поиск пользователей
        $form = new form('index.php?do=user&act=office','','','class="form-horizontal"');
        
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Ник пользователя:').'</label>');
        $form->text('<div class="col-sm-10">');
        $form->input2(false,'login','text',false,'class="form-control"','',false);
        //Кнопка
        $form->text('<div class="form-actions">');
        $form->submit(Lang::__('Искать'),'submit',false,'btn btn-default');
        $form->text('</div>');
        $form->text('</div></div>');
        $form->text('<div class="row"></div>');
        $form->display();
        
			    
        echo '<table style="text-shadow: 0px 0px 0px #fff;" class="little-table" cellspacing="0"><thead>';
        echo "<tr> 
            <th>".Lang::__('Логин')." </th> 
	    <th>".Lang::__('Группа')."</th>
            <th>".Lang::__('Действие')."</th>";			
        echo "</tr></thead>";
	echo '<tbody>';
        
		if($_POST['submit']) {
		    $submit = $_POST['submit'];
		}
			
		if( isset( $submit ) ){
		    //Проверка существуйте ли название поиска
    		    if( isset( $_POST['login'] ) ) {
	    	        $login = $_POST['login'];
		    }
		    //Глобальная обработка названии
		    $login = trim($login);
    		    $login = $db->safesql($login); 
    		    $login = htmlspecialchars($login); 
					
                //Если поиск превышает больше 20 символов выводит ошибку
    		if ( !empty( $login ) ) {
		    if ( strlen( $login ) > 20 ){
		    	echo '<br/>';
            		echo engine::error(Lang::__('Длинный запрос!')); // error 
                    } else { 
			//Запрос в базу по названию
        		$result = $db->query("SELECT `id`,`name`, `nick`,`reg_date`,`group` FROM `users` WHERE `name` LIKE '%$login%' OR `nick` LIKE '%$login%'");
                        //Обработка .......
           		if ( $db->num_rows() > 0 ) { 
                	    $row = $db->get_array($result);  //Получение всех данных 
                	    $num = $db->num_rows($result);   // Получение счетчика                     
                						
			    do  {
                    		// Делаем запрос, получающий пользователей
                    		$search = "SELECT * FROM `users` WHERE `id` = '$row[id]'";
                    		$searchl = $db->query($search);
                                                
				//Проверка на доступность данных
                    		if ( $db->num_rows() > 0 ) {
                        	    $wsearch = $db->get_array($searchl);
                    		}   
			            $igroup = user::users($row['id'],array('group'));
				    if( $igroup != 15 ) {
                                        echo '<tr class="even">';
					    //Получение Ника
					    $nick = user::users($row['id'],array('nick'),true);
					    //Вывод ника и подбираем цвет					    
					    echo '<td><font color="6E2A06";><b>'.$nick.'</b></font></td>';
					    //Получаем права к каждому пользователю	
					    $group = array(15 => '<font color="green";>'.Lang::__('Админы').'</font>', 1 => '<font color="720479";>'.Lang::__('Юзеры').'</font>', 0 => '<font color="red";>'.Lang::__('Гости').'</font>');
					    //Выводим права и подбираем к правам цвета необходимые
					    echo '<td>'.$group[$row['group']].'</td>';
					    //Действие над пользователем (Удаление / Редактирование)
					    echo '<td>';
					    echo '<a class="btn btn-default btn-sm" href="index.php?do=user&act=edituser&id='.$row['id'].'">Управление</a>
						<a class="btn btn-default btn-sm" href="index.php?do=ban&id='.$row['id'].'">Блокировать</a>';
					    echo '</td>'; 
					    echo '</tr>';
				    }
								
                		} while ( $row = $db->get_array( $result ) );
           
			    } 
        	    }
    		}   
	echo '</table><br/>';
    }else {
	//Выводим счетчик постов
    	$row = $db->get_array($db->query("SELECT COUNT(*) FROM `users`"));
        $newlist = new Navigation($row[0],20, true); 
							
	$user_all = $db->query("SELECT * FROM `users` ". $newlist->limit()."");
	    while($all_user = $db->get_array($user_all)) {
		$nick = user::users($all_user['id'],array('nick'),true);
		$igroup = user::users($all_user['id'],array('group'));
		
                    if($igroup != 15) {
			echo '<tr  class="even">';
			echo '<td><font color="6E2A06";>'.$nick.'</font></td>'; 							
						    
                            $group = array(15 => '<font color="green";>'.Lang::__('Админы').'</font>', 1 => '<font color="720479";>'.Lang::__('Юзеры').'</font>', 0 => '<font color="red";>'.Lang::__('Гости').'</font>');
				
                            echo '<td>'.$group[$all_user['group']].'</td>';		
			    echo '<td><center>
                                <a class="btn btn-default btn-sm" href="index.php?do=user&act=edituser&id='.$all_user['id'].'">Управление</a>
				<a class="btn btn-default btn-sm" href="index.php?do=ban&id='.$all_user['id'].'">Блокировать</a>';
			    echo '</center></td>'; 
			echo '</tr>';
		    }
	    }
    //Далее закрываем таблицу	
    echo "</table> </div>";		 
		
        //Вывод навигации
        echo $newlist->pagination('do=user&act=office');			
    }						
    
	break;
	
	//Создаем нового пользователя
	case 'new_user':
		
		$error = array();
		
		    if(isset($_POST['submit_new'])) {
			$nick = filter_input( INPUT_POST, 'login', FILTER_SANITIZE_STRING );
                        $email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
                        $email = filter_var( $email, FILTER_VALIDATE_EMAIL );
			$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

		    if( strlen($password) < 5 ) $error['password'][] = 'Пароль должен быть выше 5 символов';
	            if( engine::strlen_shcms( $nick,'utf-8' ) > 30 or engine::strlen_shcms( trim( $nick ), 'utf-8' ) < 3) $error['login'][] = 'Недопустимая длина ника. Логин должен быть больше 3 символов и меньше 30 символов!';
	            if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $nick ) ) $error['login'][] = 'Ваш ник недопустим к регистрации';
		    if( preg_match('/[0-9]+/',engine::format_r($nick)))  $error['login'][] = 'В начале запрещено использовать числовые значение'; 
		    if( empty( $email ) or strlen( $email ) > 50 OR @count( explode( '@',$email ) ) != 2 ) $error['email'][] =  'Введенный Email неверный';
	            if( strpos( strtolower( $nick ), '.php' ) !== false )  $error['login'][] =  'Введенный ник недопустим';
			if( stripos( urlencode($nick), '%AD') !== FALSE) {
			    $error['login'][] = 'Введенный ник недопустим';
		        }
			if( function_exists('mb_strtolower') ) {
			    $nick = engine::trim(mb_strtolower($nick, 'utf-8'));
		        } else {
			    $nick = engine::trim(strtolower( $nick ));
			}
			    $cnick = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE nick = '$nick'" );
		            if( $cnick['count'] ) $error['login'][] =  'Введенный Логин уже существует';
				$row = $db->super_query( "SELECT COUNT(*) as count FROM `users` WHERE email = '$email'" );
		            if( $row['count'] ) $error['email'][] =  'Введенный Email уже существует';
			    $regt = time();
						
				if(empty($error)) {
	                            $shgen = engine::shgen($password);
					$ok_reg = $db->query("INSERT INTO `users` (`nick`,`password`,`email`,`reg_date`,`lastdate`) VALUES ('".$db->safesql($nick)."', '".$shgen."', '".$db->safesql($email)."','".time()."','".time()."')");			
			                        
					if($ok_reg == true) {
					    echo engine::success(Lang::__('Пользователь зарегестрирован!'));
					    header('Location: index.php?do=user&act=office');
					    exit;
					}else {
					    header('Location: index.php?do=user&act=office');
					    exit;
					}
				}			
			}         
		    $form = new form('index.php?do=user&act=new_user','','','class="form-horizontal"');
		//Ник при регистрации
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Имя пользователя').'</label>');
        $form->text('<div class="col-sm-10">');    
        $form->input2(false,'login','text',htmlspecialchars($nick),'class="form-control"','',false);
        //Если ошибки есть
        if(isset($error['login'])) {
            $form->text('<div class="desc text-danger">' . implode('<br />', $error['login']) . '</div>');
        }else {
            $form->text('<div class="desc" style="color:#969a9d;">Между 3 и 30 символами</div>');
        }    
        
        $form->text('</div></div>'); 
        
        //Email нужен при регистрации для уведомлений
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('E-mail адрес').'</label>');
        $form->text('<div class="col-sm-10">'); 
	$form->input2(false,'email','text',htmlspecialchars($email),'class="form-control"','',false);
        //Описание
        if(isset($error['email'])) {
            $form->text('<div class="desc text-danger">' . implode('<br />', $error['email']) . '</div>');
        }else {
            $form->text('<div class="desc" style="color:#969a9d;">Email необходим для восстановления пароля, подтвержать не нужно</div>');
        }   
        $form->text('</div></div>'); 
        
                
        //Пароль при регистрации (Надежные)
        $form->text('<div class="form-group">');
        $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Пароль').'</label>');
        $form->text('<div class="col-sm-10">'); 
	$form->input2(false,'password','password',false,'class="form-control"','',false);
        //Описание
        if(isset($error['password'])) {
            $form->text('<div class="desc text-danger">' . implode('<br />', $error['password']) . '</div>');
        }else {
            $form->text('<div class="desc" style="color:#969a9d;">Вы должны использовать сложный пароль, содержащий не менее 3 и не более 32 символов</div>');
        }      
        $form->text('</div></div>'); 
        
        $form->text('<div class="row"></div>');
        $form->text('<center><div class="form-actions">');
        $form->submit(Lang::__('Создать пользователя'),'submit_new',false,'btn btn-success');
        $form->text('<a class="btn btn-warning" href="index.php?do=user">Отмена</a>');
        $form->text('</div></center>');
        $form->display();

	break; 
	//Редактирование пользователя
	case 'edituser':
	
	$id = intval($_GET['id']);
	$edituser = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".intval($id)."'"));
	
	    if($edituser['id'] == false) {
		    header('Location: index.php?do=user&act=office');
			exit;
		}
		    if($edituser['group'] == 15) {
			    header('Location: index.php?do=user&act=office');
				exit;
			}
	    if(isset($_POST['edit_submit'])) {
	    //Обрабатывет на правильность ICQ
		$icq_uin = engine::icq($_POST['icq']);
		
		    //Обрабатывает поле Skype
			//Если с ошибками она то выведит ошибку
		    if(isset($_POST['skype'])) {
			    if(!empty($_POST['skype'])) {
				    if(!engine::skype($_POST['skype'])) {
					    echo engine::error(Lang::__('Некорректно указан Skype'));
						echo engine::home(array(Lang::__('Назад'),'?act=core'));
						exit;
					}
				}
			}
		    //Обрабатывает поле Обо мне
			//Если в текст будет превышен допустимых символов то выведит ошибку		
			if(isset($_POST['desc'])) {
			    if(substr($_POST['desc'], 0, 1000) > 1000) {
					    echo engine::error(Lang::__('Обо мне: Не должно превышать 1 000 символов'));
						echo engine::home(array(Lang::__('Назад'),'index.php?do=user&act=edituser&id='.$id.''));
						exit;
				}else {
				$desc = $_POST['desc'];
				}
			}	
		            //Если все правильно то обновляем данные
			$mysql = $db->query('UPDATE `users` SET `icq` = "'.intval($icq_uin).'",`site` = "'.$_POST['site'].'",`skype` = "'.$_POST['skype'].'",`city` = "'.$db->safesql($_POST['city']).'", `pol` = "'.$_POST['pol'].'", `desc` = "'.$db->safesql($desc).'" WHERE `id` = '.intval($id).'');
	            
				//При правильности
	            if($mysql == true) {
				    echo engine::success(Lang::__('Настройки сохранены'));
					header('Location: index.php?do=user&act=edituser&id='.$id.'');
					exit;
				//При ошибки
				}else {
				    echo engine::error(Lang::__('Настройки не сохранены'));
				    header('Location: index.php?do=user&act=edituser&id='.$id.'');
				    exit;
				}	
		}	  
    echo '<ul class="nav nav-tabs">
  <li class="active"><a href="#tab_a" data-toggle="tab">Данные</a></li>
  <li><a href="#tab_b" data-toggle="tab">Изменить логин</a></li>
  <li><a href="#tab_c" data-toggle="tab">Сменить пароль</a></li>
  <li><a href="#tab_d" data-toggle="tab">Изменить Email</a></li>
  <li><a style="color:red;" href="#tab_i" data-toggle="tab">Удаление аккаунта</a></li>
</ul>';
    
    echo '<div class="tab-content">
        <div class="tab-pane active fade in" id="tab_a">';
    echo '<div class="form-horizontal">';
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('IP адрес').'</label>';
    echo '<div class="col-sm-10">'.$edituser['logged_ip'].'</div></div>';
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Последний визин:').'</label>';
    echo '<div class="col-sm-10">'.date::make_date($edituser['lastdate']).'</div></div>';  
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Дата регистрации:').'</label>';
    echo '<div class="col-sm-10">'.date::make_date($edituser['reg_date']).'</div></div>';       
    
    echo '<div class="row"></div>';
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Логин').'</label>';
    echo '<div class="col-sm-10">'.$edituser['nick'].'</div></div>';
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Пароль').'</label>';
    echo '<div class="col-sm-10">********</div></div>';  
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Эл. почта').'</label>';
    echo '<div class="col-sm-10">'.$edituser['email'].'</div></div>';       
    
    echo '<div class="row"></div>';    
    echo '</div>';
    
	    //Форма редактировании
	    $form = new form('index.php?do=user&act=edituser&id='.$id.'','','','class="form-horizontal"');
            //О себе
            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('О себе:').'</label>');
            $form->text('<div class="col-sm-10">');
	    $form->textarea(false,'desc',$edituser['desc'],'','','form_control');
            $form->text('</div></div>');

            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Сайт:').'</label>');
            $form->text('<div class="col-sm-10">');
            $form->input2(false,'site','text',$edituser['site'],'class="form-control"','',false);
            $form->text('</div></div>');
            
            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('ICQ:').'</label>');
            $form->text('<div class="col-sm-10">');
	    $form->input2(false,'icq','text',$edituser['icq'],'class="form-control"','',false);
            $form->text('</div></div>');
            
            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Skype:').'</label>');
            $form->text('<div class="col-sm-10">');
	    $form->input2(false,'skype','text',$edituser['skype'],'class="form-control"','',false);
            $form->text('</div></div>');
            
            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Место жительства:').'</label>');
            $form->text('<div class="col-sm-10">');
	    $form->input2(false,'city','text',$edituser['city'],'class="form-control"','',false);
	    $form->text('</div></div>');
            
            $form->text('<div class="form-group">');
            $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Пол:').'</label>');
            $form->text('<div class="col-sm-10">');
            $form->select(false,'pol',array(Lang::__('Не определился') => 1, Lang::__('Мужской') => 2, Lang::__('Женский') => 3),$edituser['pol'],'','','','','class="form-control"');
            $form->text('</div></div>');
            $form->text('<div class="row"></div>');
            
            $form->text('<center><div class="form-actions">');
	    $form->submit(Lang::__('Изменить'),'edit_submit',false,'btn btn-success');	
            $form->text('<a class="btn btn-warning" href="index.php?do=user&act=office">Отмена</a>');
            $form->text('</div></center>');
	    $form->display(); //Обработка форма и вывод полей;
     
            
    //Редактирование Логина        
    echo '</div><div class="tab-pane fade in" id="tab_b">';

	//Вывод данных по выбранному id
	$editlogin = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".intval($id)."'"));
	    //Если по вашему id ничего нет то назад
	    if($editlogin['id'] == false) {
		    header('Location: index.php?do=user&act=office');
		    exit;
		}	
	    //Обработка формы 
		//Проверка на нажатия кнопки
		if(isset($_POST['login_submit'])) {
		        //Обрабатывает ник
		        $nick = isset($_POST['new_login']) ? trim($_POST['new_login']) : '';
	
				//Проверяем Логин
    			if(empty($nick)) {
				echo  engine::error(Lang::__('Не введен логин'));
				header('Location: index.php?do=user&act=edituser&id='.$id.'');
				exit;
			}elseif(mb_strlen($nick) < 2 || mb_strlen($nick) > 30) { //Разрешение максимального введения символа
				echo engine::error(Lang::__('Недопустимая длина логина'));
				header('Location: index.php?do=user&act=edituser&id='.$id.'');	
				exit;
			}
                        
	                //Если есть запрещенные символы то выводит ошибку
		        if($nick  != $db->safesql($nick)) {
    		            echo engine::error(Lang::__('В Имени содержатся запрещенные символы'));
			    header('Location: index.php?do=user&act=edituser&id='.$id.'');	
			    exit;	
		        }
			//Выводим данные по нику
                        $regnick = $db->query("SELECT * FROM `users` WHERE `nick`='" . $db->safesql($nick) ."'");
			
                        //Проверка сводобен ли введенный ник
                            if ($db->get_array($regnick) != 0) {
                                echo engine::error(Lang::__('Введенный вами Логин занят'));
				header('Location: index.php?do=user&act=edituser&id='.$id.'');
				exit;							
	                    }
                            
			//Заливаем, и обрабатываем данные по базе по определенному id			
                        $db_user = $db->query("UPDATE `users` SET `nick` = '".$db->safesql($nick)."' WHERE `id` = '".intval($id)."'");		// UDDATE MYSQLI	 
				if($db_user == true) {
	    			    echo engine::success('Логин изменен успешно');
				    header('Location: index.php?do=user&act=edituser&id='.$id.'');
				    exit;								
				}else {
	    			    echo engine::error('Ошибка при редактировании логина');
				    header('Location: index.php?do=user&act=edituser&id='.$id.'');
				    exit;									
				}			
		}
                
		//Форма редактирования логина
                
	    $form = new form('index.php?do=user&act=edituser&id='.$editlogin['id'],'','','class="form-horizontal"');
               
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Логин').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'new_login','text',$editlogin['nick'],'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Изменить'),'login_submit',false,'btn btn-success');	
                $form->text('<a class="btn btn-warning" href="index.php?do=user&act=office">Отмена</a>');
                $form->text('</div></center>');
                
	    $form->display();
	
    //Редактирование Почта   
    echo '</div><div class="tab-pane fade in" id="tab_c">';        
	//Выводим данные по определенному id	
	$editpassword = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".intval($id)."'"));
	    if($editpassword['group'] == 15) {
		header('Location: index.php?do=user&act=office');
		exit;
	    }
            
           //Проверка на доступность данные по id		  
	    if($editpassword['id'] == false) {
		 header('Location: index.php?do=user&act=office');
		    exit;
	    }	
                $error = array();

            //Обрабатывает форму
        if(isset($_POST['pass_submit'])) {
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                
                if( strlen($password) < 5 ) $error['password'][] = 'Пароль должен быть выше 5 символов';
			$shgen = engine::shgen($password);
				
                if(empty($error)) {				
				//Заливаем данные и обновляем данные		
			    $db_user = $db->query("UPDATE `users` SET `password` = '".$shgen."' WHERE `id` = '".intval($id)."'");			
				if($db_user == true) {
	    				echo engine::success('Пароль изменен успешно'); // Ok
					header('Location: index.php?do=user&act=edituser&id='.$id.'');
					exit;
				}else {
	    				echo engine::error('Ошибка при редактировании пароля'); // Error
					header('Location: index.php?do=user&act=edituser&id='.$id.'');
					exit; 
				}	
                }					
	}
		
        //Форма редактирование пароля		
	    $form = new form('index.php?do=user&act=edituser&id='.$editpassword['id'],'','','class="form-horizontal"');
               
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Пароль').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'password','text',false,'class="form-control"','',false);
                
                if(isset($error['password'])) {
                    $form->text('<span style="color:red"><small>' . implode('<br />', $error['password']) . '</small></span>');
                }
                
                $form->text('</div></div>');
                
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Изменить'),'pass_submit',false,'btn btn-success');
                $form->text('<a class="btn btn-warning" href="index.php?do=user&act=office">Отмена</a>');
                $form->text('</div></center>');	
                
	    $form->display();
        
    //Изменение почты     
    echo '</div><div class="tab-pane fade in" id="tab_d">'; 
    
	//Получение данных по  id	
	$editemail = $db->get_array($db->query("SELECT * FROM `users` WHERE `id` = '".intval($id)."'"));
	    
	    //Если в id есть пользователь выводит все что неободимо 
	    if($editemail['id'] == false) {
		header('Location: index.php?do=user&act=office');
		exit;
	    }	 
		
	    //Проверям идет ли работа с формой
	    if(isset($_POST['email_submit'])) {
		// $mail true 
		$mail = isset($_POST['email']) ? trim($_POST['email']) : '';
			
		//Проверяем правильно ли введен Email
		$valid_email = filter_var($mail, FILTER_VALIDATE_EMAIL);
	   			
                    if($valid_email === false) { //__False / _True
			echo engine::error(Lang::__('Некорректный E-mail адрес')); //__Err
			header('Location: index.php?do=user&act=edituser&id='.$id.'');	
			exit;	
		    }
                    
		//Если введенный емаил уже есть в базе данных то выводит ошибку		
            	$reqmail = $db->query("SELECT * FROM `users` WHERE `email`='" . $db->safesql($mail) ."'");
        			
                    if ($db->get_array($reqmail) != 0) { // Num != 0
            		echo engine::error(Lang::__('Введенный вами Email занят')); // __Err
			header('Location: index.php?do=user&act=edituser&id='.$id.'');
			exit;	
	    	    }	
				
                    //Заливаем данные и обновляем таблицу email
		    $db_user = $db->query("UPDATE `users` SET `email` = '".$db->safesql($mail)."' WHERE `id` = '".intval($id)."'");			
					
                        if($db_user == true) {
	    		    echo engine::success('Email изменен успешно'); 
			    header('Location: index.php?do=user&act=edituser&id='.$id.'');
			    exit;								
			}else {
	    		    echo engine::error('Ошибка при редактировании email`а');
			    header('Location: index.php?do=user&act=edituser&id='.$id.'');	
			    exit;									
			}	
		}	
                

	    $form = new form('index.php?do=user&act=edituser&id='.$editemail['id'],'','','class="form-horizontal"');
               
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Изменить Email').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'email','text',$editemail['email'],'class="form-control"','',false);
                
                $form->text('</div></div>');
                
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Изменить'),'email_submit',false,'btn btn-success');	
                $form->text('<a class="btn btn-warning" href="index.php?do=user&act=office">Отмена</a>');
                $form->text('</div></center>');	
                
	    $form->display();
            
    //Удаление аккаунта           
    echo '</div><div class="tab-pane fade in" id="tab_i">'; 
		        
        $delete = $db->get_array($db->query( "SELECT * FROM `users` WHERE `id` = '{$id}'" ));
        
	    if($delete['group'] == 15) {
		header('Location: index.php?do=user&act=office');
		exit;
	    }
                
            if(isset($_POST['yes_delete'])) {
		$db->query("DELETE FROM `users` WHERE `id` = '".intval($id)."'"); // Удаление пользователя
		$db->query("DELETE FROM `chat` WHERE `id_user` = '".intval($id)."'"); //Удаление всех сообщений пользователя
		$db->query("DELETE FROM `forum_post` WHERE `id_user` = '".intval($id)."'"); // Удаление всех постов в форуме пользователя		 
		$db->query("DELETE FROM `forum_topics` WHERE `id_user` = '".intval($id)."'");	//Удаление всех тем в форуме пользователя
		$db->query("DELETE FROM `like` WHERE `id_user` = '".intval($id)."'");	//Удаление все понравившиеся темы пользователя
		$db->query("DELETE FROM `log_auth` WHERE `id_user` = '".intval($id)."'");//Удаление логов входа пользователя
		$db->query("DELETE FROM `log_user` WHERE `id_user` = '".intval($id)."'"); //Удаление новых уведомлений пользователя
                $db->query("DELETE FROM `messaging` WHERE `id_user` = '".intval($id)."'");		 //Удаление всех почт пользователя
		$db->query("DELETE FROM `messaging_topics` WHERE `id_user` = '".intval($id)."'"); //Удаление созданных тем пользователя
		$db->query("DELETE FROM `messaging_topics_user` WHERE `id_user` = '".intval($id)."'"); //Удаление тем пользователя
                $db->query("DELETE FROM `news_comment` WHERE `id_user` = '".intval($id)."'");		 //Удаление всех комментарий пользователя
		
		//Переадресация автоматическая
		header('Location: index.php?do=user&act=edituser&id='.$id.'');
                exit;
    }
	
		//Форма удаление	
                $form = new form('index.php?do=user&act=edituser&id='.$delete['id'],'','','class="form-horizontal"');
                
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2"></label>');
                $form->text('<div class="col-sm-10">');
                $form->text(Lang::__('Вы действительно хотите удалить выбранного пользователя? Данное действие невозможно будет отменить.'));
                
                $form->text('</div></div>');
                $form->text('<div class="row"></div>');
                
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Удалить'),'yes_delete',false,'btn btn-success');
                $form->text('<a class="btn btn-warning" href="index.php?do=user&act=office">Отмена</a>');
                $form->text('</div></center>');			
        	$form->display();

    echo '</div></div>';
	
break;

	
	/*
	 * Статус пользователя
	*/
	
	case 'status':
            echo '<div style="text-align:right;margin-bottom:4px;">';
	    echo '<a class="btn btn-default" href="index.php?do=user&act=new_status">Добавить статус</a></div>';
            echo '<div class="row"></div>';
            
		echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
                echo "<tr>
		    <th>".Lang::__('Название:')." </th> 
                    <th>".Lang::__('Баллы:')."</th>  
		</tr>";
					
		    $reput = $db->query("SELECT * FROM `status_user`");
			while($status = $db->get_array($reput)) {
			    echo '<tr class="even">';
                            echo '<td>'.$status['name'].'</td>'; 
			    echo '<td>'.engine::number($status['points']).'</td>'; 	
                                echo '<td  style="padding: 1px;">
				    <a title="'.Lang::__('Редактировать').'" class="Button_secondary"href="index.php?do=user&act=edit_status&id='.$status['id'].'"><img src="../icons/user/edit_status.png"></a>
				    <a title="'.Lang::__('Удалить').'" class="Button_secondary"href="index.php?do=user&act=delete_status&id='.$status['id'].'"><img src="../icons/user/editdelete.png"></a>
				</td>';									
			    echo '</tr>';
			}
                        
    	        echo "</table>";		 
		echo '</div>';
                
	break;
        
	//Добавить новый статус для пользователя
	case 'new_status':
			
			if(isset($_POST['new_submit'])) {
			    $name = engine::proc_name($_POST['name']);
				$points = intval($_POST['points']);
				
			    if(empty($name)) {
				    echo engine::error(Lang::__('Введите названия'));
					header('Location: index.php?do=user&act=new_status');
					exit;
				}
			    if(empty($points)) {
				    echo engine::error(Lang::__('Введите баллы'));
					header('Location: index.php?do=user&act=new_status');
					exit;
				}				
				
				
				$edit_status = $db->query("INSERT INTO `status_user` (`name`,`points`) VALUES  ('".$db->safesql($name)."','".intval($points)."')");
			        if($edit_status == true) {
	    				echo engine::success('Параметры успешно изменены'); 
                                        header('Location: index.php?do=user&act=new_status');
					exit;								
				}else {
	    				echo engine::error('Ошибка при изменение параметр');
					header('Location: index.php?do=user&act=new_status');
					exit;									
				}	
			}
                        
                        
	    $form = new form('index.php?do=user&act=new_status','','class="form-horizontal"');
               
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название статуса').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'name','text',false,'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Количество баллов:').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'points','text',false,'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Добавить'),'new_submit',false,'btn btn-success');	
                $form->text('<a class="btn btn-warning" href="index.php?do=user&act=status">Отмена</a>');
                $form->text('</div></center>');	
                
	    $form->display();

		
	break;
        
	//Редактировать статус	
	case 'edit_status':
	    $id = intval($_GET['id']);
            $status = $db->get_array($db->query("SELECT * FROM `status_user` WHERE `id` = '".intval($id)."'"));
			
			if(isset($_POST['edit_status'])) {
			    $name = engine::proc_name($_POST['name']);
				$points = intval($_POST['points']);
				
			    if(empty($name)) {
				    echo engine::error(Lang::__('Введите названия'));
                                    header('Location: index.php?do=user&act=edit_status&id='.$status['id'].'');
				    exit;
				}
			    if(empty($points)) {
				    echo engine::error(Lang::__('Введите количество баллов'));
				    header('Location: index.php?do=user&act=edit_status&id='.$status['id'].'');
                                    exit;
				}				
				
				$edit_status = $db->query("UPDATE `status_user` SET `name` = '".$db->safesql($name)."',`points` = '".intval($points)."' WHERE `id` = '".intval($id)."'");
			        
                                if($edit_status == true) {
	    				echo engine::success('Параметры успешно изменены'); 
					header('Location: index.php?do=user&act=edit_status&id='.$status['id'].'');
                                        exit;								
				}else {
	    				echo engine::error('Ошибка при изменение параметр');
					header('Location: index.php?do=user&act=edit_status&id='.$status['id'].'');
                                        exit;									
				}	
			}

	    $form = new form('index.php?do=user&act=edit_status&id='.$status['id'].'','','class="form-horizontal"');
               
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Название статуса').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'name','text',$status['name'],'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<br/><div class="form-group">');
                $form->text('<label for="inputEmail3" class="col-sm-2 control-label col-font-2">'.Lang::__('Количество баллов:').'</label>');
                $form->text('<div class="col-sm-10">');
                $form->input2(false,'points','text',$status['points'],'class="form-control"','',false);
                $form->text('</div></div>');
                
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Изменить'),'edit_status',false,'btn btn-success');	
                $form->text('<a class="btn btn-warning" href="index.php?do=user&act=status">Отмена</a>');
                $form->text('</div></center>');	
                
	    $form->display();        
        
	break;
	
	//Удаление статуса
	case 'delete_status':
	    $id = intval($_GET['id']); //Проверка на нумерное значение в $id
		
		//Удаление статуса
		$db->query("DELETE FROM `status_user` WHERE `id` = '".intval($id)."'");
		//Переадресация
		header("Location: index.php?do=user&act=status");
	
	break;
endswitch;

?>