<?php
    echo '<div class="header"><h1 class="page-title">Центр Безопастности SHCMS Engine</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Настройки</a></li>
            <li class="active">Система</li>
        </ul></div>';

switch($act):

default:
	echo '<div class="widget"><ul class="cards list-group not-bottom no-sides">';
        echo '<li class="list-group-item""><i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-child pull-left text-info"></i>';
        echo '<span class="time"><a class="btn btn-default" href="index.php?do=system&act=info_phpfunction">'.Lang::__('Подробнее').'</a></span>';
        echo '<h4>Отключение Опасных PHP Функций</h4>';
	echo '<div class="desc">
	 Рекомендуется отключить следующие PHP-функции. Если вы не имеете доступа к настройкам сервера, обратитесь к вашему хостинг-провайдеру.
exec, system, popen, proc_open, shell_exec</div>';
        echo '</li>';
	echo '</ul></div>';
break;

case 'info_phpfunction':
    echo '<div class="panel panel-default">
                    <div class="panel-heading">Отключение Опасных PHP Функций </div>';
    echo '<ul class="list-group">';
   echo ' <li class="list-group-item">'; 
	$php_info = $db->get_array($db->query("SELECT * FROM `info_secure`"));
	echo engine::input_text($php_info['text']);
	echo '</li>';
        
    echo ' <li class="list-group-item">';
	echo '<img src="../icons/system/text.png">&nbsp;<b>exec</b> — Исполняет внешнюю программу</li>';
    echo '</li><li class="list-group-item">';     
	echo '<img src="../icons/system/text.png">&nbsp;<b>system</b> — Выполняет внешнюю программу и отображает её вывод';
    echo '</li><li class="list-group-item">';     
	echo '<img src="../icons/system/text.png">&nbsp;<b>popen</b> — Открывает файловый указатель процесса';
    echo '</li><li class="list-group-item">';         
	echo '<img src="../icons/system/text.png">&nbsp;<b>proc_open</b> — Выполняет команду и открывает указатель на файл для ввода/вывода';
    echo '</li><li class="list-group-item">';     
	echo '<img src="../icons/system/text.png">&nbsp;<b>shell_exec</b> — Выполняет команду через шелл и возвращает полный вывод в виде строки';

	echo '</li></ul>';
	break;
endswitch;

?>