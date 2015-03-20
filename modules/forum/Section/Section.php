<?php
/**
 * Класс Для работы с Разделами
 * 
 * @package            Forum/Category/
 * @author             Shamsik
 * @link               http://shcms.ru
 */

namespace Forum\Section;

use Shcms\Component\Form\Form;  
use Shcms\Component\Data\Bundle\DateType;
use engine;

class Section {
    
    protected $title = '';
    protected $off;


    /**
     * Добавление Новых Разделов
     * 
     * @param $param = array()
     */
    public static function AddSection($param = array()) {
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
                <a href="#" class="btn btn-default disabled">Новый раздел</a>';
            echo '</div>';
        }
        
        //Фильтруем Кнопку
        $submit = filter_input(INPUT_POST,'submit');
        //Фильтруем название
        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        //Обработка описмания
        $desc = \filter_input(\INPUT_POST,'desc',\FILTER_SANITIZE_STRING);
        //Вводим Категорий
        $cats = filter_input(INPUT_POST,'category',FILTER_SANITIZE_NUMBER_INT);
        $category = \intval($cats);
        
        //Если нажата кнопка
        if(isset($submit)) {
            //Проверяем Существует ли Имя введенное
           $object = $dbase->query("SELECT COUNT(*) as count FROM `forum_subsection` WHERE `name`= '{$dbase->escape($name)}' ");
           $row = $object->fetchArray();
            
            //Проверяет введена ли название
            if(empty($name)) {
	        $error['name'] = 'Введите название';
            }
            //Проверяет введена ли описание
            if(empty($desc)) {
                $error['desc'] = 'Введите Описание';
            }elseif ($row['count'] > 0) {
                $error['name'] = 'Введенный раздел уже существует';
            }elseif(empty($error)) {
	        //Установливаем Данные в Базу
                $dbase->insert('forum_subsection',array(
                    'id_cat' => ''.intval($category).'',
                    'name' => ''.$dbase->escape($name).'',
                    'text' => ''.$dbase->escape($desc).'',
                    'time' => ''.time().''
                ));
                
                //Автомачитеская переадресация
	        header('Location: index.php');
                exit;
            }
        }
        //Данные о получение категорий
        $view = $dbase->query("SELECT `id`,`name` FROM `forum_category`");
        
        //HTML форма создания
        echo '<div class="mainname">Новый Раздел</div>';
        echo '<div class="mainpost">';
            //Объявляем форму
            $form = new Form;
            //Получаем путь обработки и класс
            echo $form->open(array('action' => '?do=new_section','class' => 'form-horizontal'));
            echo '<div class="form-group"><br/>';
            //Имя Раздела
            echo '<label class="col-sm-2 control-label">Название</label>';
            echo '<div class="col-sm-10">';
            //Поле ввода раздела
            echo $form->input(array('name' => 'name','class' => 'form-control'));
                //Если возникают ошибки
                if(isset($error['name'])) {
                    echo '<p class="text-danger">'.$error['name'].'</p>';
                }else {
                    echo '<p class="text-muted small">Введите название раздела</p>';
                }       
                
            echo '</div>';
            
            //Описание Раздела
            echo '<label class="col-sm-2 control-label">Описание</label>';
            echo '<div class="col-sm-10">';
            //Поле ввода категории
            echo $form->textbox(array('name' => 'desc','class' => 'form-control'));
                //Если возникают ошибки
                if(isset($error['desc'])) {
                    echo '<p class="text-danger">'.$error['desc'].'</p>';
                }else {
                    echo '<p class="text-muted small">Введите описание</p>';
                }       
                
            echo '</div>';    
            //Выборка категорий
            echo '<label class="col-sm-2 control-label">Выбор Категории</label>';
            echo '<div class="col-sm-10">';
            //Поле ввода категории
            echo '<select name="category" class="form-control">';
                while($cat = $view->fetchArray()) {
                    echo "<option value='{$cat['id']}' selected='selected'>{$cat['name']}</option>\n";
                }
            echo '</select>';
                
            echo '</div></div>';                
            
            //Выводим Кнопку
            echo '<div class="modal-footer">';
            //Форма ввода кнопки
            echo $form->submit(array('name' => 'submit','value' => 'Создать  раздел','class' => 'btn btn-success'));
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
                <a href="#" class="btn btn-default disabled">Настройка Раздела</a>';
            echo '</div>';
        }
        
        $null = $dbase->query("SELECT COUNT(*) as count FROM `forum_subsection`");
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
        $sub = $dbase->query("SELECT * FROM `forum_subsection`");
	     //Проверяем есть ли категории
            echo '<table class="table table-bordered">';
            echo '<thead><tr>';
            //Индификатор
            echo '<th>ID</th>';
            //Блок Названия Категорий
            echo '<th>Категория</th>';
            //Блок Название Радела
            echo '<th>Раздел</th>';
            //Блок Действий
            echo '<th>Действие</th>';
            echo '</tr></thead>';
            
            echo '<tbody>';
                //Если есть вывводим их всех	
                foreach ($sub->fetchAll() as $subcat) {   
                    //Данные по Категории
                   $fcat = $dbase->query("SELECT * FROM `forum_category` WHERE `id` = ? ",array($subcat->id_cat));
                   $cats = $fcat->fetchArray();
                  echo '<tr>';
                  //Индификатор
                  echo '<td>'.$subcat->id.'</td>';
                  //Названия Категориии
                  echo '<td ><b>'.$cats['name'].'</b></td>';
                  //Названия Раздела
                  echo '<td ><a href="index.php"><b>'.$subcat->name.'</b></a></td>';
                  //Параметры
                  echo '<td class="text-center">';
                  echo '<a class="btn btn-small btn-info" href="?do=rsetting&act=editor&id='.$subcat->id.'">Изменить</a>';
                  echo '&nbsp;&nbsp;&nbsp;';
                  echo '<a class="btn btn-small btn-danger" href="?do=rsetting&act=delete&id='.$subcat->id.'">Удалить</a>';
                  echo '</td>';
                  echo '</tr>';
            }
            
        echo '</tbody></table>';
    }    

    /**
     * Получение заголовка стараницы
     * 
     * @param $id Индификатор раздела
     */   
    public static function Title($id) {
        global $dbase,$templates;
        
        //Получаем Заголовок Раздела
        $head = $dbase->query("SELECT * FROM `forum_subsection` WHERE `id` = ? ",array($id));
        $title = $head->fetch();
        
        //Выводим
        return $templates->template($title->name);
    }

    /**
     * Вверхняя навигация
     * 
     * @param Null
     */ 
    public static function Secnav() {
        //Параметры навигации
        $nav  = '<div class="btn-group btn-breadcrumb margin-bottom">';
        $nav .= '<a href="/index.php" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>';
        $nav .= '<a href="index.php" class="btn btn-default">'.\Lang::__('Форум').'</a>';
        $nav .= '<a href="#" class="btn btn-default disabled">'.\Lang::__('Все темы').'</a>';
        $nav .= '</div>';
        
        //Выводим
        return $nav;
    }   
}