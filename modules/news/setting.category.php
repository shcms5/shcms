<?
define('SHCMS_ENGINE',true);
include_once('../../engine/system/core.php');
$templates->template(Lang::__('Настройка Категорий')); //Название страницы
    
	//Если у тебя права 15 то ты можешь приступить к работе
    if($groups->setAdmin($user_group) !=  15) {
        echo engine::error('У вас нет прав для доступа');
        header('Refresh: 1; url=index.php');
        exit;
    }
        //Если категорий нет то выведит эту ошибку
        $yes_cat = $db->query('SELECT * FROM `news_category`');
		if(!$db->num_rows($yes_cat)){
		    header('Refresh: 1; url=index.php');
            engine::error(Lang::__('Произошла ошибка не найдено категорий')); //При ошибке
            exit;
		}

    //Делаем облегчение создаем 2разных фукнции в одной станице
    switch($act):
	
        //По умолчанию
        default:
            echo '<div class="mainpost">';
            echo '<a href="?act=editor_cat">'.Lang::__('Редактирование и удаление категорий').'</a>';
            echo '</div>';
            echo engine::home(array(Lang::__('Назад'),'index.php'));
        break;

    //Редактируем категорию
    case 'editor_cat':

        if(isset($_GET['edit']) == 'edit') {
            //Если вместо id num попытаются вставить текст то выводит ошибку
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                header('Refresh: 1; url=index.php');
                engine::error(Lang::__('Произошла ошибка при выборки категории')); //При ошибке
                exit;
            }
            //из $_GET в обычную переменную
            $id = (int) $_GET['id'];
			//Выводим категорию по $ID
            $category = $db->query("SELECT * FROM `news_category` WHERE `id` = '".$id."'");
                echo '<div class="mainname">'.Lang::__('Редактирование категории').'</div>';
				
			//Через $edit[]	начинаем вывод данных с базы	
            $edit = $db->get_array($category);
                echo '<div class="mainpost">';
				
				//Проверяем нажата ли кнопка и если ли в поле input текст
				if(isset($_POST['name']) and isset($_POST['update'])) {
				       //Обрабатываем название
                        $name = engine::proc_name($_POST['name']);
				
				    //Если не введена название категории
					if(!$_POST['name']) {
				    	echo engine::error(Lang::__('Введите название категории!'));
					}else{
					    //Если все выполнена правильно то обновляет название в базе
				   		$db->query("UPDATE `news_category` SET `name` = '".$db->safesql($name)."' WHERE `id` = '".$id."'");
				    	echo engine::success(Lang::__('Категории успешно изменена')); //Успешно
				    	echo engine::home(array(Lang::__('Назад'),'setting.category.php?act=editor_cat')); //Переадресация
				    	exit;
					}
				}
				//Форма редактирования
                $form = new form('?act=editor_cat&id='.$id.'&edit');
                $form->input(Lang::__('Название:'),'name','text',$edit['name']); //Название
                $form->submit(Lang::__('Обновить'),'update'); //Обновляем
                $form->display();
                echo '</div>';
        }
        //Загружаем данные с базы
        $category = $db->query("SELECT * FROM `news_category`");
            echo '<div class="mainname">'.Lang::__('Список категорий').'</div>';
			//Проверяем есть ли категории
            if($db->num_rows($category)) {
                    echo '<div class="mainpost">';
				//Если есть вывводим их всех	
                while($cat = $db->get_array($category)) {
                    echo '<div class="sortable"><b>ID:'.$cat['id'].'</b>&nbsp;';
					echo '<a href="category.php?id='.$cat['id'].'">'.$cat['name'].'</a>';
                    echo '<span class="time">';
                    echo '<a href="?act=editor_cat&id='.$cat['id'].'&edit"><img src="/engine/template/icons/notepad.png"/></a>&nbsp;&nbsp;';
                    //Иконк удаление категорий
						echo '<a href="?act=delete_cat&id='.$cat['id'].'"><img src="/engine/template/icons/delete.png"/></a>';
					echo '</span>';			
					echo '</div>';
                }
                    echo '</div>';
            }
		//Переадресация на пред. старницу
		echo engine::home(array(Lang::__('Назад'),'setting.category.php'));
    //Завершаем с редактированием категории
    break;

    //Удаляем категорию
    case 'delete_cat':
        //Если вместо id num попытаются вставить текст то выводит ошибку
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                header('Refresh: 1; url=index.php');
                engine::error(Lang::__('Произошла ошибка при удаление категории')); //При ошибке
                exit;
            }
            //из $_GET в обычную переменную
            $id = (int) $_GET['id'];
			
			//Выводим всю информацию из категории
			$cat = $db->get_array($db->query("SELECT * FROM `news_category` WHERE `id` = '".$id."'"));
									
			//Если вы нажали на подтверждение то будет удалена категория со всеми новостями
			if(isset($_POST['yes_delete'])) {
                //Удаляем категорию
                $db->query('DELETE FROM `news_category` WHERE `id` = "'.$id.'"');	
                //Удаляем новости в данной категории				
                $db->query('DELETE FROM `news` WHERE `id_cat` = "'.$id.'"');
               		
				//Успешное удаление всех выбранных данных
			    echo engine::success('Категория успешно удалена');
				echo engine::home(array('Назад','setting.category.php?act=editor_cat'));
				exit;
			//Если вы нажали на отмену то переадресовывается на пред. страницу
			}elseif(isset($_POST['no_delete'])){
			    header('Location: setting.category.php?act=editor_cat'); //Пред. стараница
			}
						
		//Подтверждение	
		echo '<div class="mainname">Подтверждение</div>';	
		echo '<div class="mainpost">';	
			echo 'Вы действительно хотите удалить выбранную категорию? Данное действие невозможно будет отменить.<hr/>';
		//Форма удаление
		echo '<div style="text-align:right;">';
        $form = new form('?act=delete_cat&id='.$id.'');		
		$form->submit('Да','yes_delete');
        $form->submit('Нет','no_delete');		
        $form->display();
		echo '</div></div>';
	break;
endswitch;






?>