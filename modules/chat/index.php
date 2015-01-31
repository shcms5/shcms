<?php
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template('Общение');
$error = array();

include_once 'view/view.php';

switch($do):
    default:
    if($id_user == true) {
        $submit = filter_input(INPUT_POST,'submit');
        //Обрабатываем цитирование
        $quote = filter_input(INPUT_GET,'quote',FILTER_SANITIZE_STRING);
        //Обработка полученного Индификатора поста
        $quoteid = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = intval($quoteid);
            //Если нажата кнопка то обработаем
            if(isset($submit)) {
                $controller->comment($_POST['text']);
            }
        //Если нажата цитирование
        if(isset($quote)) {
            //Вытаскиваем из базы выбранный пост для цитирования
            $tquote = $db->get_array($db->query("SELECT * FROM `chat` WHERE `id` = '{$id}'"));
            $chatnick = $user->users($tquote['id_user'],array('nick'),false);
            //Форма цитирования
            $quotet = '[quote='.$chatnick.']'.$tquote['text'].'[/quote]';
            $controller->form($quotet);
        }else {
            $controller->form();
        }
    }
    $controller->output();
    
    break;
        
    case 'all':
        //Передает управление админу для действий
        $removal->removall();
    break;
    case 'delete';
        //Обработка полученное $_GET[id]
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        //Проверка на нумерование данных
        $id = intval($id);
        //Действие при удаление
        $removal->deleteid($id);
    break;
endswitch;