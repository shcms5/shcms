<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
//Обработка полученного $_GET
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$id = intval($id);

    //Если вместо id num попытаются вставить текст то выводит ошибку
    if (!isset($id) || !is_numeric($id)) {
        header('Location: index.php');
        exit;			
    }
	$yes_delete = filter_input(INPUT_POST,'yes_delete');
        $no_delete = filter_input(INPUT_POST,'yno_delete');
			
    //Если вы нажали на подтверждение то будет удалена категория со всеми разделами и темами
    if(isset($yes_delete)) {
        //Удаляем папку
        $db->query('DELETE FROM `files_dir` WHERE `id` = "'.$id.'"');
        $db->query('DELETE FROM `files_dir` WHERE `dir` = "'.$id.'"');				
        //Удаляем файлы в данной папке				
        $db->query('DELETE FROM `files` WHERE `idir` = "'.$id.'"');
	    //Успешное удаление всех выбранных данных
	    echo engine::success(Lang::__('Папка успешно удалена'));
	    echo engine::home(array('Назад','setting.in.php'));
	    exit;
				
    }elseif(isset($no_delete)){
	header('Location: setting.in.php'); //Пред. стараница
    }
			
//Подтверждение	
echo '<div class="mainname">'.Lang::__('Подтверждение').'</div>';	
echo '<div class="mainpost">';
echo 'Вы действительно хотите удалить выбранную папку? Данное действие невозможно будет отменить.<hr/>';
echo '<div style="text-align:right;">';
    //Форма удаление
    $form = new form('?act=delete_cat&id='.$id.'');		
    $form->submit(Lang::__('Да'),'yes_delete');
    $form->submit(Lang::__('Нет'),'no_delete');		
    $form->display();
    
echo '</div></div>';