<?php
/**
 * Класс Для работы с Категориями
 * 
 * @package            Forum/Category/
 * @author             Shamsik
 * @link               http://shcms.ru
 */

namespace Forum\Category;

use Shcms\Component\Form\Form;  

class Category {
    
    
    /**
     * Получение заголовка стараницы
     * 
     * @param $id Индификатор раздела
     */   
    public static function Title() {
        global $dbase,$templates;
        
        //Выводим
        return $templates->template('Новая Категория | Форум');
    }        
    /**
     * Добавление Новой Категории
     * 
     * @param Addcategory($param = array())
     */
    public static function Addcategory($param = array()) {
        global $dbase,$groups,$user_group,$error;
        
        //Проверяем Является ли Пользователь Администратором
        if($param['group'] == 'Groups') {
            if($groups->setAdmin($user_group) !=  15) {
                header('Location: index.php');
                exit;
            }
        }
        //По Выбору выводим навигацию
        if($param['nav'] == 'Nav') {
            //Навигация Вверхняя
            echo '<div class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="index.php" class="btn btn-default">Форум</a>
                <a href="#" class="btn btn-default disabled">Новая категория</a>';
            echo '</div>';
        }
        
        
        //Фильтруем Кнопку
        $submit = filter_input(INPUT_POST,'submit');
        //Фильтруем название
        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        
        //Если нажата кнопка
        if(isset($submit)) {
            //Проверяем Существует ли Имя введенное
           $object = $dbase->query("SELECT COUNT(*) as count FROM `forum_category` WHERE `name`= '{$dbase->escape($name)}' ");
           $row = $object->fetchArray();
            
            //Проверяет введена ли название
            if(empty($name)) {
	        $error['name'] = 'Введите название';
            }elseif ($row['count'] > 0) {
                $error['name'] = 'Введенная категория уже существует';
            }elseif(empty($error)) {
	        //Устанавливаем Название в базу
                $dbase->insert('forum_category',array(
                    'name' => ''.$dbase->escape($name).'',
                    'time' => ''.time().''
                ));
                
                //Автомачитеская переадресация
	        header('Location: index.php');
                exit;
            }
        }
        
        //HTML форма создания
        echo '<div class="mainname">Новая категория</div>';
        echo '<div class="mainpost">';
            //Объявляем форму
            $form = new Form;
            //Получаем путь обработки и класс
            echo $form->open(array('action' => '?do=new_category','class' => 'form-horizontal'));
            echo '<div class="form-group"><br/>';
            //Имя Категории
            echo '<label class="col-sm-2 control-label">Название</label>';
            echo '<div class="col-sm-10">';
            //Поле ввода категории
            echo $form->input(array('name' => 'name','class' => 'form-control'));
                //Если возникают ошибки
                if(isset($error['name'])) {
                    echo '<p class="text-danger">'.$error['name'].'</p>';
                }else {
                    echo '<p class="text-muted small">Введите название категории</p>';
                }       
                
            echo '</div></div>';
            //Выводим Кнопку
            echo '<div class="modal-footer">';
            //Форма ввода кнопки
            echo $form->submit(array('name' => 'submit','value' => 'Создать категорию','class' => 'btn btn-success'));
            echo '<a class="btn" href="index.php">Отменить</a></div>';
            //Закрытие формы
            echo $form->close();
        echo '</div>';
        
    }
    
    /**
     * Настройка Административных параметров
     * 
     * @param $param = array()
     */    
    public static function Setting($param = array()) {
        global $dbase,$groups,$user_group;
        
        //Проверяем Является ли Пользователь Администратором
        if($param['group'] == 'Groups') {
            if($groups->setAdmin($user_group) !=  15) {
                header('Location: index.php');
                exit;
            }
        }
        //По Выбору выводим навигацию
        if($param['nav'] == 'Nav') {
            //Навигация Вверхняя
            echo '<div class="btn-group btn-breadcrumb margin-bottom">
                <a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
                <a href="index.php" class="btn btn-default">Форум</a>
                <a href="#" class="btn btn-default disabled">Настройка Категории</a>';
            echo '</div>';
        }
        
        $null = $dbase->query("SELECT COUNT(*) as count FROM `forum_category`");
        $nulls = $null->fetchArray();
        
        if($nulls['count'] == 0) {
            header('Location: index.php');
            exit;
        }
        
    }
    
    /**
     * Все Разделы Выводим Из функций
     * 
     * @param $param = array()
     */   
    public static function AllSetting() {
        global $dbase;
        //Загружаем данные с базы
        $cat = $dbase->query("SELECT * FROM `forum_category`");
	     //Проверяем есть ли категории
            echo '<table class="table table-bordered">';
            echo '<thead><tr>';
            //Индификатор
            echo '<th>ID</th>';
            //Имя категории
            echo '<th>Категория</th>';
            //Блок Действий
            echo '<th>Действие</th>';
            echo '</tr></thead>';
            
            echo '<tbody>';
                //Если есть выводим их всех	
                foreach ($cat->fetchAll() as $cats) {   
                  echo '<tr>';
                  //Индификатор
                  echo '<td>'.$cats->id.'</td>';
                  //Название категории
                  echo '<td style="width: 50%;"><a href="index.php"><b>'.$cats->name.'</b></a></td>';
                  //Параметры Управления
                  echo '<td class="text-center">';
                  echo '<a class="btn btn-small btn-info" href="?do=setting&act=editor&id='.$cats->id.'">Изменить</a>';
                  echo '&nbsp;&nbsp;&nbsp;';
                  echo '<a class="btn btn-small btn-danger" href="?do=setting&act=delete&id='.$cats->id.'">Удалить</a>';
                  echo '</td>';
                  echo '</tr>';
            }
            
            echo '</tbody></table>';
    }
}
