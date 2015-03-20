<?php
define('SHCMS_ENGINE',true);
include_once('engine/system/core.php');
include_once('admin/skins/header.php');
    
define(CP_DERICTORY,'/admin/');
define(ICON_ADMIN,'/admin/icons/');    

use Shcms\Component\Provider\Json\JsonScript;
$json = new JsonScript;	

//Если Пользователь не Администратор
if($users['group'] != 15) {
   header('Location: index.php');
   exit;
}
echo '<div class="header"><h1 class="page-title">Главная страница панели управления</h1></div>';	    

//Счетчик пользователей
$usersc = $db->get_array($db->query("SELECT COUNT(*) as count FROM `users`"));
//Счетчик всех писем
$mailall = $db->get_array($db->query("SELECT COUNT(*) as count FROM `messaging`"));
//Счетчик всех тем в форуме
$countt = $db->get_array($db->query("SELECT COUNT(*) as count FROM `forum_topics`"));
//Счетчик всех файлов
$countf = $db->get_array($db->query("SELECT COUNT(*) as count FROM `files`"));
echo '
    <div class="panel panel-default">
        <a class="panel-heading" data-toggle="collapse">Общая статистика</a>
        <div class="panel-collapse panel-body collapse in">

                        <div class="col-md-3 col-sm-6">
                            <div class="knob-container">
                                <input class="knob" data-width="180" data-min="0" data-max="6000" data-displayPrevious="true" value="'.$usersc['count'].'" data-fgColor="#92A3C2" data-readOnly=true;>
                                <h4 class="text-muted text-center">Пользователей</h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="knob-container">
                                <input class="knob" data-width="180" data-min="0" data-max="10000" data-displayPrevious="true" value="'.$mailall['count'].'" data-fgColor="#92A3C2" data-readOnly=true;>
                                <h4 class="text-muted text-center">Писем в почте</h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="knob-container">
                                <input class="knob" data-width="180" data-min="0" data-max="100000" data-displayPrevious="true" value="'.$countt['count'].'" data-fgColor="#92A3C2" data-readOnly=true;>
                                <h4 class="text-muted text-center">Тем в форуме</h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="knob-container">
                                <input class="knob" data-width="180" data-min="0" data-max="10000" data-displayPrevious="true" value="'.$countf['count'].'" data-fgColor="#92A3C2" data-readOnly=true;>
                                <h4 class="text-muted text-center">Всего файлов</h4>
                            </div>
                        </div>
                    </div>
        </div>';


        
	//Вывод данных из json
        $string = file_get_contents(H.'engine/widget/filejson/admin.json');
        $jsond = $json->decode($string);
        
            echo '<div style="border-bottom:0px;" class="mainname">Все разделы Администратора</div>';				    

                echo '<div style="border: 0px;" class="box"><div style="padding: 0px;" class="row box-section">';
                    echo '<ul class="list-group">';
        
			foreach($jsond as $key => $value){
			    echo '<li class="list-group-item" style="float: left; width: 50%; ">';				
			    echo '<i class="fa-2x padding-top-small padding-bottom padding-right-small fa '.$value['icon'].' pull-left text-info"></i>';
			    echo '<h4><a href="'.CP_DERICTORY.''.$value['path'].'/"><b>'.$key.'</b></a></h4>';
			    echo ' <span class="info">'.$value['text'].'</span>';
			    echo '</li>';
			
			}	

		    echo '</ul>';
                echo '</div>';
            echo '</div>';
                        

include_once('admin/skins/footer.php');
