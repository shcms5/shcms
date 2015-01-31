<?php
// Обзор диагностики системы SHCMS Engine

switch($act):
    //По умолчанию получаем данные ниже
    default:
        //Получаем счетчик всех пользователей
	$r_user = $db->get_array($db->query("SELECT COUNT(*) FROM `users`"));
	//Получаем счетчик авторизованных
	$online = $db->get_array($db->query('SELECT COUNT(*) FROM `users` WHERE `lastdate` > '.(time()-600).''));
        
		$topic = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_topics`"));
		$topic_close = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_topics` WHERE `close` = '2'"));
		$topic_open = $db->get_array($db->query("SELECT COUNT(*) FROM `forum_topics` WHERE `close` = '1'"));
                $message = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging`"));
		$message_topic = $db->get_array($db->query("SELECT COUNT(*) FROM `messaging_topics`"));
    echo '<ul class="nav nav-tabs">
          <li class="active"><a href="#tab_a" data-toggle="tab">Статистика</a></li>
          <li><a href="#tab_b" data-toggle="tab">Системная информация</a></li>
          <li><a href="#tab_c" data-toggle="tab">Системные процессы</a></li>
        </ul>';
    
    echo '<div class="tab-content">
        <div class="tab-pane active fade in" id="tab_a">';
    echo '<div class="form-horizontal">';
    
	//Вывод статистики необходимой
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Пользователей').':&nbsp;'.engine::number($r_user[0]).'</b></label>';
    echo '<div class="col-sm-10">'.Lang::__('В сети').':</b>&nbsp;<a style="color: #3287c9;" href="/modules/all_users.php?do=online_user">'.Lang::__('Просмотр').'</a> ('.engine::number($online[0]).')</div></div>';
    
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Тем').':&nbsp'.engine::number($topic[0]).'</b></label>';
    echo '<div class="col-sm-10">'.Lang::__('Закрытые темы').':&nbsp;'.engine::number($topic_close[0]).'<br/>';
    echo Lang::__('Открытые темы').':</b>&nbsp;'.engine::number($topic_open[0]);
    echo '</div></div><div class="row"></div>';
    
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Сообщений').':&nbsp;</b>'.engine::number($message[0]).'</label>';
    echo '<div class="col-sm-10">'.Lang::__('Тем создано').':</b>&nbsp;'.engine::number($message_topic[0]).'</div></div>';
    echo '<div class="row"></div></div>';
    
    
    echo '</div><div class="tab-pane fade in" id="tab_b">';
    
	//Выводим полную системную информацию	
	$extensions = get_loaded_extensions();
	$extensions = array_combine( $extensions, $extensions );
	    sort( $extensions, SORT_STRING );
	$extension = str_replace( "suhosin", "<strong>suhosin</strong>", implode( ", ", $extensions ) );
	$server_software = @php_uname();
            
            //материал сервера
	    $total_memory = '<font color="red">'.Lang::__('Не выведена').'</font>';
            $avail_memory = '<font color="red">'.Lang::__('Не выведена').'</font>';
		                        
	    $_disabled = @ini_get('disable_functions') ? explode( ',', @ini_get('disable_functions') ) : array();
            $_shellExecAvail = in_array( 'shell_exec', $_disabled ) ? false : true;
		
                if( $_shellExecAvail ) {
		    if( strpos( strtolower( PHP_OS ), 'win' ) === 0 ) {
			$tasks = @shell_exec( "tasklist" );
			$tasks = str_replace( " ", "&nbsp;", $tasks );
			$tasks = mb_convert_encoding($tasks, 'utf-8', 'CP866');
			
                        
                    }else if( strtolower( PHP_OS ) == 'darwin' ) {
			$tasks = @shell_exec( "top -l 1" );
			$tasks = str_replace( " ", "&nbsp;", $tasks );
		    }else {
			$tasks = @shell_exec( "top -b -n 1" );
			$tasks = str_replace( " ", "&nbsp;", $tasks );
		    }
		}else {
		    $tasks	= '';
		}
		    if( !$tasks ) {
			$tasks = Lang::__('Ошибки при получение информации');
		    }else {
			$tasks = "<pre>".$tasks."</pre>";
		    }			
		                    
		    if( strpos( strtolower( PHP_OS ), 'win' ) === 0 ){
			$mem = $_shellExecAvail ? @shell_exec('systeminfo') : null;
			    if( $mem ) {
				$server_reply = explode( "\n", str_replace( "\r", "", $mem ) );
				    if( count($server_reply) ) {
					foreach( $server_reply as $info ) {
				            if( strstr( $info, Lang::__('Всего физической памяти')) ) {
						$total_memory =  trim( str_replace( ":", "", strrchr( $info, ":" ) ) );
					    }
					
                                            if( strstr( $info, Lang::__('Доступно физической памяти')) ) {
						$avail_memory =  trim( str_replace( ":", "", strrchr( $info, ":" ) ) );
					    }
					}
				    }
			    }
		        }else {
			    $mem = $_shellExecAvail ? @shell_exec("free -m") : null;
			        if( $mem ) {
				    $server_reply = explode( "\n", str_replace( "\r", "", $mem ) );
				    $mem = array_slice( $server_reply, 1, 1 );
				    $mem = preg_split( "#\s+#", $mem[0] );
	
				        $total_memory = ( $mem[1] ) ? $mem[1] . ' MB' : '--';
				        $avail_memory = ( $mem[3] ) ? $mem[3] . ' MB' : '--';
			        }else {
				        $total_memory	= '--';
				        $avail_memory	= '--';
			        }
		            }		
		        
    //Выводим таблицу со всеми данными
    echo '<div class="form-horizontal">';
    echo '<br/><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Версия SHCMS Engine').'</b></label>';
    echo '<div class="col-sm-10">v&nbsp;'.$sversion->ShortVersion().'&nbsp;(Build: #'.$sversion->BuildVersion().')</div></div>';

    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Статус развития').'</b></label>';
    echo '<div class="col-sm-10">'.$sversion->StatusVersion().'</div></div>'; 
    
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Копирайт').'</b></label>';
    echo '<div class="col-sm-10">'.$sversion->CopyrightVersion().'</div></div>';  
    
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Основатель системы').'</b></label>';
    echo '<div class="col-sm-10">'.$sversion->AuthorVersion().'</div></div>';  
    
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('MYSQLI Версия').'</b></label>';
    echo '<div class="col-sm-10">'.mysqli_get_client_info().'</div></div>';  
    
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('PHP Версия').'</b></label>';
    echo '<div class="col-sm-10">'.PHP_VERSION.'</div></div>'; 
    
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Отключенные PHP функции').'</b></label>';
    echo '<div class="col-sm-10"><a href="index.php?do=diagnostic&act=php_function">'.Lang::__('Просмотр').'</a></div></div>';  
    
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Загруженные расширение').'</b></label>';
    echo '<div class="col-sm-10">'.$extension.'</div></div>';      
 	
    echo '</div>';
    echo '</div><div class="tab-pane fade in" id="tab_c">';
    
	   //Получаем информацию о системных процессах необходимых для работыыы	
            echo '<div style="margin-top:4px;">';
		    echo $tasks;
		echo '</div></div>';
				
	    
break;
	    
		
//Отключенные + Неотлюченные функции php
case 'php_function':
	    echo '<div class="panel panel-default">
                    <div class="panel-heading">Отключение PHP Функций </div>';
    echo '<ul class="list-group">';
   echo ' <li class="list-group-item">'; 
   
       echo '<img src="../icons/support/frame.png">&nbsp;Полную информацию о безопастности почитайте тут - <a href="/admin/system/index.php?do=system&act=info_phpfunction">Просмотр</a>';
                    
	//Небезопастная функция exec
	echo '</li><li class="list-group-item"><img src="../icons/support/text.png">&nbsp;<b>exec</b> - ';
			            
        if (!engine::function_enabled('exec')) {
            echo '<font color="green"><b>Отключена</b></font>';
        }else {
            echo '<font color="red"><b>Включена</b></font>';
        }
	echo '<br/>Описание: Исполняет внешнюю программу'; // Описание
					
	//Небезопастная функция system
        echo '</li><li class="list-group-item">';
        echo '<img src="../icons/support/text.png">&nbsp;<b>system</b> - ';
	    if (!engine::function_enabled('system')) {
                echo '<font color="green"><b>Отключена</b></font>';
            }else {
                echo '<font color="red"><b>Включена</b></font>';
            }			
		echo '<br/>Описание: Выполняет внешнюю программу и отображает её вывод</li>';// Описание
            
		//Небезопастная функция popen
                echo '</li><li class="list-group-item">';
		echo '<img src="../icons/support/text.png">&nbsp;<b>popen</b> - ';
			            
                if (!engine::function_enabled('popen')) {
                    echo '<font color="green"><b>Отключена</b></font>';
                }else {
                    echo '<font color="red"><b>Включена</b></font>';
                }			
		echo '<br/>Описание: Открывает файловый указатель процесса</li>';// Описание
					
		//Небезопастная функция proc_open
                echo '</li><li class="list-group-item">';
                echo '<img src="../icons/support/text.png">&nbsp;<b>proc_open</b> - ';
		    if (!engine::function_enabled('proc_open')) {
                        echo '<font color="green"><b>Отключена</b></font>';
                    }else {
                        echo '<font color="red"><b>Включена</b></font>';
                    }	
                    
                echo '<br/>Описание: Выполняет команду и открывает указатель на файл для ввода/вывода</li>';	// Описание		
					
		//Небезопастная функция shell_exec
                echo '</li><li class="list-group-item">';
                echo '<img src="../icons/support/text.png">&nbsp;<b>shell_exec</b> - ';
		
                    if (!engine::function_enabled('shell_exec')) {
                        echo '<font color="green"><b>Отключена</b></font>';
                    }else {
                        echo '<font color="red"><b>Включена</b></font>';
                    }
                    
                echo '<br/>Описание: Выполняет команду через шелл и возвращает полный вывод в виде строки</li>';// Описание			
		
                echo '</ul></div>';
                echo engine::home(array('Назад','index.php?do=diagnostic'));	 
                echo '</div></div>';
	    
break;
	
    
endswitch;
