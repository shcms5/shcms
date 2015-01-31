<?php
define('SHCMS_ENGINE',true);
define('HOME',$_SERVER['DOCUMENT_ROOT'],true);
define(CP_DERICTORY,'../');
define(ICON_ADMIN,'../icons/');

include_once(HOME.'/engine/system/core.php');
include_once(HOME.'/admin/skins/header.php');
echo engine::admin($users['group']);


switch($do):

default:
    echo '<div class="header"><h1 class="page-title">Настройка Социальных сетей</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a> </li>
            <li class="active">Поддержка</li>
        </ul></div>';
    
echo '
<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Авторизация через Соц Сетей</h4>
        </div><div class="container"></div>
        <div class="modal-body">
          <a data-toggle="modal" href="#vk" class="btn btn-primary">Вконтакте</a>
          <a data-toggle="modal" href="#facebook" class="btn btn-primary">Facebook</a>
        </div>
        <div class="modal-footer">
          <a href="#" data-dismiss="modal" class="btn btn-default">Назад</a>
        </div>
      </div>
    </div>
</div>
<div class="modal  fade" id="vk" data-backdrop="static">
	<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Настройка Авторизации через Вконтакте</h4>
        </div><div class="container"></div>
        <div class="modal-body">
          Для начала вам необходимо зайти Вконтакте и <a target="_blank" href="http://vk.com/editapp?act=create">создать приложение</a><br/>
          <img width="90%" src="/engine/template/info/auth/1.png"><br/>
          После нажатия на кнопку "Подключить сайт", вам придётся ввести проверочный код, который придёт по смс. 
          Если вы пройдёте проверку, то вам должна открыться следующая форма с настройками приложения:<br/>
          <img width="90%" src="/engine/template/info/auth/2.png"><br/>
          Далее нажимаем на кнопку "Сохранить изменение" и переходим в пункт "Настройки"<br/>
          <img width="90%" src="/engine/template/info/auth/3.png"><br/>
          Из данной формы вам понадобятся такие данные, как "ID приложения" и "Защищённый ключ", 
          которые вы должны ввести в Администраторской в разделе Настройка социальных сетей.
        </div>
        <div class="modal-footer">
          <a href="#" data-dismiss="modal" class="btn btn-default">Назад</a>
        </div>
      </div>
    </div>
</div>
<div class="modal  fade" id="facebook" data-backdrop="static">
	<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Настройка Авторизации через Facebook</h4>
        </div><div class="container"></div>
        <div class="modal-body">
           Для начала вам необходимо зайти на Facebook и <a target="_blank" href="https://developers.facebook.com/apps">Добавить новое приложение</a><br/>
           В вверхнем правом углу Нажимаем Add a New App и создаем приложение, далее<br/>
           выбираем платформу "Веб Сайт" и вводим название и нажимаем на Create New Facebook App ID
           <img width="90%" src="/engine/template/info/auth/f1.png"><br/>
           Далее нужно будет выбрать категорию вашего сайта и для каких целей вам нужно его использовать
           Test/Action  и нажимаем на Create App ID<br/>
           <img width="90%" src="/engine/template/info/auth/f2.png"><br/>
           Теперь вам нужно вводить Полный путь к скрипту http://ваш.сайт/modules/auth_fc.php
           Вводите путь в обе поля<br/>
           <img width="90%" src="/engine/template/info/auth/f3.png"><br/>
           Далее сохраняем данные и переходим на вверх и Нажимаем на
           "Apps" -> "Ваше приложение"<br/>
           <img width="90%" src="/engine/template/info/auth/f4.png"><br/>
           Для получение секретного кода нажмите на "show". 
         </div>
        <div class="modal-footer">
          <a href="#" data-dismiss="modal" class="btn btn-default">Назад</a>
        </div>
      </div>
    </div>
</div>';    
    

    echo '<div style="text-align:right;margin-bottom:4px;">';
    echo '<a class="btn btn-info" data-toggle="modal" data-target="#info">Инструкция по Авторизациям</a>';
    echo '</div>';
 echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-vk pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'social/index.php?do=vk"><h4>'.Lang::__('Авторизация через Вконтакте').'</h4></a>
                        <p class="info small">Настройка Авторизации через Вконтакте</p>
                    </li> 
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-facebook pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'social/index.php?do=fc"><h4>'.Lang::__('Авторизация через Facebook').'</h4></a>
                        <p class="info small">Настройка Авторизации через Facebook</p>
                    </li>                     
                </ul>
            </div>';

echo engine::home(array('Назад','/admin.php'));	 
break;

//Настройка Вконтакте
case 'vk':
    echo '<div class="header"><h1 class="page-title">Настроить авторизацию через Вконтакте</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Настройка</li>
        </ul></div>';
    
include_once('vk.php');

break;

//Настройка Facebook
case 'fc':
    echo '<div class="header"><h1 class="page-title">Настроить авторизацию через Facebook</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Настройка</li>
        </ul></div>';
    
include_once('fc.php');

break;

endswitch;


include_once(HOME.'/admin/skins/footer.php');