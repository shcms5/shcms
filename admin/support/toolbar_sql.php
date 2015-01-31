<?php
switch($act):

//По умолчанию получаем данные ниже
default:
     echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-laptop pull-left text-info"></i>
                        <a href="index.php?do=sql&act=overview"><h4>'.Lang::__('Инструмент Управления').'</h4></a>
                        <p class="info small">Инфорамация о SHCMS Engine и о ваших данных</p>
                    </li>   
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-heart-o pull-left text-info"></i>
                        <a href="index.php?do=sql&act=info_server"><h4>'.Lang::__('Состояние сервера').'</h4></a>
                        <p class="info small">Легкое управление базой данных</p>
                    </li> 
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-key pull-left text-info"></i>
                        <a href="index.php?do=sql&act=value_system"><h4>'.Lang::__('Системные значение').'</h4></a>
                        <p class="info small">Легкое управление базой данных</p>
                    </li> 
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-hdd-o pull-left text-info"></i>
                        <a href="index.php?do=sql&act=backup"><h4>'.Lang::__('Резервирование базы данных').'</h4></a>
                        <p class="info small">Легкое управление базой данных</p>
                    </li>                     
                </ul>
            </div>';
            
				
break;
		
		
//Выводим все таблицы которые находятся в базе и управлять ими!)	
case 'overview':

    $result = $db->query("SHOW TABLES");
                
	if (!$result) {
            echo Lang::__('Ошибка базы, не удалось получить список таблиц').'<br/>';
            echo Lang::__('Ошибка MySQL:'). $db->error_sql();
            exit;
        }

            //Вывод всех таблиц
            echo '<div style="text-align:right;">';
            echo '<h3>'.Lang::__('Всего таблиц в базе: ').$db->num_rows($result).'</h3>';
            echo '<div class="row"></div>';

		$sql_show = new sql_show();

            if (!empty($_POST)) {
                foreach ($_POST as $table => $val) {
                    if (!$val)
                        continue;
                            if (in_array($table, $sql_show->sql_show)) {
                                if (!empty($_POST['table_sql'])) {
                                    $sql_show->save_data(H .'engine/sql/'. $table . '.sql', $table);
                                }
                            }
                }

                if (!empty($_POST['table_sql'])) {
                   echo engine::success(Lang::__('База была успешно экспортирована в папку engine/sql/'));
	               echo '</div>';
	               echo engine::home(array(Lang::__('Назад'),'index.php?do=sql&act=overview'));
	               exit;
                }
	    }
		            
    echo "<table class='table table-bordered'>"; 
    echo "<thead><tr>
            <th>#</th>
	    <th>".Lang::__('Таблица')." </th> 
            <th>".Lang::__('Полей')."</th> 
            <th>".Lang::__('Строк')."</th> 		
            <th>".Lang::__('Размер')."</th> 							
	    <th>".Lang::__('Экспорт')."</th>  
	</tr></thead>";
	
    // Вывод ........
    
    echo '<tbody>';
    $num = 0;
	while ($row = $db->get_array($result)) {
	    echo '<tr>';
		$num++;
                $show = $db->get_array($db->query("SHOW TABLE STATUS FROM `".DBNAME."` WHERE `name` = '{$row[0]}'"));
		$total = engine::filesize($show[Data_length]+$show[Index_length]);
                    echo '<td style="text-align:left;"><b>'.$num.'</b></td>';        		
		    echo "<td style='text-align:center;'><a href='index.php?do=sql&act=viewsql&table={$row[0]}'>{$row[0]}</a></td>"; 	
                    echo "<td style='text-align:center;'>".$db->num_rows($db->query("SHOW COLUMNS FROM `{$row[0]}`"))."</td>";
		    echo "<td style='text-align:center;'>".$show['Rows']."</td>";
                    echo "<td style='text-align:center;'>";
			echo $total;
		    echo "</td>";
		    echo "<td style='text-align:center;'>";
                    
			//Форма
			$form = new form('index.php?do=sql&act=overview');
			$form->text('<input type="checkbox" id="'.$row[0].'" name="'.$row[0].'" checked hidden>');
			$form->submit(Lang::__('Экспорт'),'table_sql',false,'btn btn-default');
			$form->display();
				
                    echo '</td>';	
	    echo '</tr>';
	}		
    echo "</tbody></table>";		 

    echo engine::home(array(Lang::__('Назад'),'index.php?do=sql'));

break;
		
		
////// Получение всех полей таблицы и дополнительные разделы
		
case 'viewsql':
		    
    $name_sql = engine::proc_name($_GET['table']);
   
    echo '<ul class="nav nav-tabs">
          <li class="active"><a href="#tab_a" data-toggle="tab">Статистика</a></li>
          <li><a href="#tab_b" data-toggle="tab">Информация</a></li>
        </ul>';
    
    echo '<div class="tab-content">
        <div class="tab-pane active fade in" id="tab_a">';
    
echo '<div id="three-summaries" class="row">
    <div class="col-md-5 block">
    <div class="panel panel-default">
        <div class="panel-heading">Разделы</div>
               <ul class="cards list-group not-bottom no-sides">
               
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-fire pull-left text-info"></i>
                        <a href="index.php?do=sql&act=optimize&table='.$name_sql.'"><h4>Оптимизация</h4></a>
                        <p class="info small">Оптимизируем таблицу</p>
                    </li>
                    
                   <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-cogs pull-left text-info"></i>
                        <a href="index.php?do=sql&act=repair&table='.$name_sql.'"><h4>Ремонт</h4></a>
                        <p class="info small">Чиним таблицу если повреждена</p>
                    </li>
                    
                   <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-circle-o-notch pull-left text-info"></i>
                        <a href="index.php?do=sql&act=analiz&table='.$name_sql.'"><h4>Анализ</h4></a>
                       <p class="info small">Анализируем таблицу</p>
                    </li>   
                    
                   <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-exclamation-triangle pull-left text-info"></i>
                        <a href="index.php?do=sql&act=view_table&table='.$name_sql.'"><h4>Проверка</h4></a>
                        <p class="info small">Обычная проверка таблицы</p>
                    </li>  
                    
                   <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-bar-chart-o pull-left text-info"></i>
                        <a href="index.php?do=sql&act=table_creact&table='.$name_sql.'"><h4>Структура</h4></a>
                       <p class="info small">Смотрим структуру таблицы</p>
                    </li>   
                    
                </ul>
    </div></div>
    
    <div class="col-md-7">';
        echo '<div class="panel panel-default">
        <div class="panel-heading"> '.Lang::__('Поля').' <b>'.$name_sql.'</b></div>
                    <table class="table table-bordered table-first-column-check table-hover">
                        <thead>
                            <tr>
                              <th class="col-md-1">'.Lang::__('Имя').'</th>
                              <th class="col-md-2">'.Lang::__('Тип').'</th>
                              <th class="col-md-8">'.Lang::__('Null').'</th>
                              <th class="col-md-3">'.Lang::__('Default').'</th>
                            </tr>
                        </thead>
                        <tbody>';
                    $result = $db->query("SHOW COLUMNS FROM `".$name_sql."`");
			while ($row = $db->get_array($result)) {
				    $null = array('NO' => '<i>Нет</i>','YES' => 'Да');
                                    
                            echo'<tr>
                              <td style="text-align:center;">'.$row['Field'].'</td>
                              <td style="text-align:center;">'.$row['Type'].'</td>
                              <td style="text-align:center;">'.$null[$row['Null']].'</td>
                              <td style="text-align:center;">'.$row['Default'].'</td>
                            </tr>';
                        }   
                        
                    echo '</tbody></table></div>';
        echo '<div class="panel panel-default">
        <div class="panel-heading">'.Lang::__('Индексы').' <b>'.$name_sql.'</b></div>
                    <table class="table table-bordered table-first-column-check table-hover">
                        <thead>
                            <tr>
                              <th class="col-md-1">'.Lang::__('Имя').'</th>
                              <th class="col-md-2">'.Lang::__('Тип').'</th>
                              <th class="col-md-8">'.Lang::__('Уникальный').'</th>
                              <th class="col-md-3">'.Lang::__('Столбец').'</th>
                              <th class="col-md-4">'.Lang::__('Элементы').'</th>
                            </tr>
                        </thead>
                        <tbody>';
                    $result_in = $db->query("SHOW INDEX FROM `".$name_sql."`");
			while ($row_in = $db->get_array($result_in)) {
                            $non = array(0 => 'Да', 1 => 'Нет');
                                    
                            echo'<tr>
                              <td style="text-align:center;"><b>'.$row_in['Key_name'].'</b></td>
                              <td style="text-align:center;">'.$row_in['Index_type'].'</td>
                              <td style="text-align:center;">'.$non[$row_in['Non_unique']].'</td>
                              <td style="text-align:center;">'.$row_in['Column_name'].'</td>
                              <td style="text-align:center;">'.$row_in['Cardinality'].'</td>
                            </tr>';
                        }   
                        
                    echo '</tbody></table></div>';                    
                    
                echo '</div> </div>';
                
        echo '</div><div class="tab-pane fade in" id="tab_b">';
        echo '<div class="form-horizontal">';
		                
                $info_show = $db->get_array($db->query("SHOW TABLE STATUS FROM `".DBNAME."` WHERE `name` = '{$name_sql}'"));
		//Переписка английские значение на русские
                $format = array('Dynamic' => 'Динамический','Static' => 'Статический'); 
     
    //Используемое пространство            
    echo '<br/><center style="padding-bottom: 5px;"><b>Используемое пространство</b></center>';
    
    //Размер данных
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Данные').'</b></label>';
    echo '<div class="col-sm-10">'.engine::filesize($info_show['Data_length']).'</div></div>';
    
    //Индексация
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Индекс').'</b></label>';
    echo '<div class="col-sm-10">'.engine::filesize($info_show['Index_length']).'</div></div>'; 

    //Общий размер
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Всего').'</b></label>';
    echo '<div class="col-sm-10">'.engine::filesize($info_show['Data_length']+$info_show['Index_length']).'</div></div>';     

        //Статистика определенных строк
	echo '<div class="row"></div><center style="padding-bottom: 5px;"><b>Статистика строк</b></center>';

    //Формат таблицы    
    echo '<div class="row"></div><div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Формат').'</b></label>';
    echo '<div class="col-sm-10">'.$format[$info_show['Row_format']].'</div></div>';  
    
    //Кодировка таблицы
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Сравнение').'</b></label>';
    echo '<div class="col-sm-10">'.$info_show['Collation'].'</div></div>';  
    
    //Кол. строк
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Строки').'</b></label>';
    echo '<div class="col-sm-10">'.$info_show['Rows'].'</div></div>';  
    
    //Длина строки
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Длина строки').'</b></label>';
    echo '<div class="col-sm-10">'.engine::filesize($info_show['Avg_row_length']).'</div></div>';  
    
    //Следующий индекс
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('След. индекс').'</b></label>';
    echo '<div class="col-sm-10">'.$info_show['Auto_increment'].'</div></div>';  
    
    //Время Создание
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Создание').'</b></label>';
    echo '<div class="col-sm-10">'.$info_show['Create_time'].'</div></div>';  
    
    //Последнее обновление
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Посл. обновление').'</b></label>';
    echo '<div class="col-sm-10">'.$info_show['Update_time'].'</div></div>'; 
    
    //Описание таблицы
    echo '<div class="form-group">';
    echo '<label for="inputEmail3" class="col-sm-2 control-label col-font-2"><b>'.Lang::__('Комментария').'</b></label>';
    echo '<div class="col-sm-10">'.$info_show['Comment'].'</div></div>';     
    			
echo '</div>';		
									
    echo '</div> </div>';
		
break;		
        

//Оптимизация таблицы
case 'optimize':
		        
    $table = filter_input(INPUT_GET,'table',FILTER_SANITIZE_STRING);		
	
    echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
    echo "<tr>
	<th>".Lang::__('Таблица')." </th> 
        <th>".Lang::__('Op')."</th> 
        <th>".Lang::__('Тип')."</th> 		
        <th>".Lang::__('Текст')."</th> 							
    </tr>";				
		                            
	$optimize = $db->query("OPTIMIZE TABLE  `{$table}`");
	    while($optim = $db->get_array($optimize)) {
                echo '<tr class="even">';			     
		    
                    echo '<td><b>'.$optim['Table'].'</b></td>';
		    echo '<td>'.$optim['Op'].'</td>';
		    echo '<td>'.$optim['Msg_type'].'</td>';
	            echo '<td>'.$optim['Msg_text'].'</td>';
									
		echo '</tr>';
	    }
    	                
    echo "</table>";	

    echo engine::home(array(Lang::__('Назад'),'index.php?do=sql&act=viewsql&table='.$table.''));

break;
		
		
//Ремонт таблицы
case 'repair':

    $table = filter_input(INPUT_GET,'table',FILTER_SANITIZE_STRING);       
    
        echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
            echo "<tr>
		<th>".Lang::__('Таблица')." </th> 
                <th>".Lang::__('Op')."</th> 
                <th>".Lang::__('Тип')."</th> 		
                <th>".Lang::__('Текст')."</th> 							
	    </tr>";				
            
            $repaire = $db->query("REPAIR TABLE  `{$table}`");
		while($repair = $db->get_array($repaire)) {
                    echo '<tr class="even">';	
                    
			echo '<td><b>'.$repair['Table'].'</b></td>';
			echo '<td>'.$repair['Op'].'</td>';
			echo '<td>'.$repair['Msg_type'].'</td>';
			echo '<td>'.$repair['Msg_text'].'</td>';
                        
		    echo '</tr>';
	        }
    	                
        echo "</table>";	
				
        echo engine::home(array(Lang::__('Назад'),'index.php?do=sql&act=viewsql&table='.$table.''));
		
break;
		
		
//Анализируем таблицы
case 'analiz':

    $table = filter_input(INPUT_GET,'table',FILTER_SANITIZE_STRING);
				
	echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
        echo "<tr>
	    <th>".Lang::__('Имя')." </th> 
            <th>".Lang::__('Мин. Значение')."</th> 
            <th>".Lang::__('Мак. Значение')."</th> 
            <th>".Lang::__('Ср. Значение')."<br/>".Lang::__('Ср. Длина')."</th> 
	</tr>";				
		    
	    $analize = $db->query("SELECT * FROM  `{$table}` PROCEDURE ANALYSE ( )");
		while($analiz = $db->get_array($analize)) {
                    echo '<tr class="even">';
                    
			echo '<td>'.$analiz['Field_name'].'</td>';
			echo '<td>'.$analiz['Min_value'].'</td>';
			echo '<td>'.$analiz['Max_value'].'</td>';
			echo '<td>'.$analiz['Avg_value_or_avg_length'].'</td>';		
		
                    echo '</tr>';
                    
		}
    	                
        echo "</table>";	
    
    //Переадресация
    echo engine::home(array(Lang::__('Назад'),'index.php?do=sql&act=viewsql&table='.$table.''));
		
break;
		

//Проверяем таблицы
case 'view_table':

    $table = filter_input(INPUT_GET,'table',FILTER_SANITIZE_STRING);
			
            echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
            echo "<tr>
		<th>".Lang::__('Таблица')." </th> 
                <th>".Lang::__('Op')."</th> 
                <th>".Lang::__('Тип')."</th> 
                <th>".Lang::__('Текст')."</th> 
	    </tr>";				
		                            
	$views = $db->query("CHECK TABLE  `{$table}`");
	    while($view = $db->get_array($views)) {
                echo '<tr class="even">';		     
			
                    echo '<td>'.$view['Table'].'</td>';
		    echo '<td>'.$view['Op'].'</td>';
		    echo '<td>'.$view['Msg_type'].'</td>';
		    echo '<td>'.$view['Msg_text'].'</td>';	
		
                echo '</tr>';
	    }
    	                
        echo "</table>";	

    //Переадресация
    echo engine::home(array(Lang::__('Назад'),'index.php?do=sql&act=viewsql&table='.$table.''));
		
break;		
		
		
//Структура таблиц
case 'table_creact':

    $table = filter_input(INPUT_GET,'table',FILTER_SANITIZE_STRING);
		                
        echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
            
            echo "<tr>
		<th>".Lang::__('Имя переменной')." </th> 
                <th>".Lang::__('Значение')."</th> 
	    </tr>";				
		                            
	    $values = $db->query("SHOW CREATE TABLE `{$table}`");
		while($value = $db->get_array($values)) {
                    echo '<tr class="even">';		
                    
			echo '<td>'.$value['Table'].'</td>';
			echo '<td>'.engine::input_text('[php]'.$value['Create Table'].'[/php]').'</td>';
                        
			echo '</tr>';
		}
    	                
        echo "</table>";	
				
        //Переадресация
        echo engine::home(array(Lang::__('Назад'),'index.php?do=sql&act=viewsql&table='.$table.''));
        
		
break;    
	    
case 'info_server':
            
	echo '<div class="subpost">';
    
	    printf("Версия сервера: %s\n<br/>", $db->get_server_info());
	    printf("Тип соединения: %s\n<br/>", $db->get_host_info());
					
        echo '</div>';
	
        echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
            echo "<tr>
		<th>".Lang::__('Имя переменной')." </th> 
                <th>".Lang::__('Значение')."</th> 
	    </tr>";				
		                            
	        $views = $db->query("SHOW STATUS;");
		    while($view = $db->get_array($views)) {
                        echo '<tr class="even">';		     
			
                            echo '<td>'.$view['Variable_name'].'</td>';
			    echo '<td>'.$view['Value'].'</td>';
                            
			echo '</tr>';
		    }
    	                
        echo "</table>";	
	
    //Переадресация
    echo engine::home(array(Lang::__('Назад'),'index.php?do=sql'));
    
break;
		
		
//Системные значение настроек
case 'value_system':
	
    echo "<table style='text-shadow: 0px 0px 0px #fff;' class='little-table' cellspacing='0'>"; 
        echo "<tr>
	    <th>".Lang::__('Имя переменной')." </th> 
            <th>".Lang::__('Значение')."</th> 
	</tr>";				
		                            
	    $values = $db->query("SHOW VARIABLES;");
		while($value = $db->get_array($values)) {
                    echo '<tr class="even">';	
                    
			echo '<td>'.$value['Variable_name'].'</td>';
			echo '<td>'.$value['Value'].'</td>';
		    
                    echo '</tr>';
		}
    	                
        echo "</table>";	
				
    //Переадресация
    echo engine::home(array(Lang::__('Назад'),'index.php?do=sql'));		
		
    break;
		
		
//Создание резервинованого копирование ваших таблиц
case 'backup':
        
        echo engine::error('<b>Предупреждение</b><br/>Если у вас есть доступ к phpMyAdmin или другим подобным инструментам, то мы рекомендуем для создания резервной копии воспользоваться ими.');
	echo engine::success('Все необходимы таблицы готовы к резервинованию, после сохранения бэкап можно будет найти перейти по пути engine/sql/');
			    
	    $form = new form('index.php?do=sql&act=backup');
    
                $form->text('<center><div class="form-actions">');
	        $form->submit(Lang::__('Начать резервинование'),'submit',false,'btn btn-success');	
                $form->text('<a class="btn btn-warning" href="index.php?do=sql">Отмена</a>');
                $form->text('</div></center>');	
                
	    $form->display();
						
            //Для резервинования нужно пройти по форме	
	    if(isset($_POST['submit'])) {
            	$backup = new MySQL_Backup();
            	$backup->fname_format = 'Ymd-His';
               			
		//Соединение с сервером
                $backup->server = DBHOST;
                $backup->username = DBUSER;
                $backup->password = DBPASS;
                $backup->database = DBNAME;
				
                // Кодировка базы
                $backup->characters = 'UTF8';
                        
		// Место куда будем заливать дамп. Не забываем про слеши.
                $backup->backup_dir = H.'engine/sql/';
                $run = $backup->Execute();
                    
            echo $run;
	    }		
				
	echo engine::home(array(Lang::__('Назад'),'index.php?do=sql'));		
		
break;
		
	
endswitch;
