<?php 
/**
 * Функциональный класс для работы с HTML Формой
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
namespace Shcms\Component\Form;

use Shcms\Component\Templating\Path\Assets;

class Form implements \Shcms\Component\Form\ConfigInterface {

    /**
     * Подключение Интерфейсных файлов
     * 
     * @param __construct(NULL)
     */
    public function __construct() {
        //Объявляем класс
        $assets = new Assets;
        
        //Вытаскиваем данные 
        $assets->css(array(
            '/engine/template/module/editor/theme/default/wbbtheme.css'
        ));
        $assets->js(array(
            '/engine/template/module/editor/jquery.wysibb.js',
            '/engine/template/module/editor/lang/ru.js'
        ));        
    }

    /**
     * Открытая форма
     * Этот метод возвращает элемент формы <form...
     * 
     * @param   array(id, name, class, onsubmit, method, action, files, style)
     * @return  string
     */
    public function open($params = array()){
        $o = '<form';
        $o .= (isset($params['id']))        ? " id='{$params['id']}'"                       : '';
        $o .= (isset($params['name']))      ? " name='{$params['name']}'"                   : '';
        $o .= (isset($params['class']))     ? " class='{$params['class']}'"                 : '';
        $o .= (isset($params['onsubmit']))  ? " onsubmit='{$params['onsubmit']}'"           : '';
        $o .= (isset($params['method']))    ? " method='{$params['method']}'"               : ' method="post"';
        $o .= (isset($params['action']))    ? " action='{$params['action']}'"               : '';
        $o .= (isset($params['files']))     ? " enctype='multipart/form-data'"              : '';
        $o .= (isset($params['style']))     ? " style='{$params['style']}'"                 : '';
        $o .= (isset($params['autocomplete'])) ? " autocomplete='{$params['autocomplete']}'" : '';
        $o .= (isset($params['role']))      ? " role='{$params['role']}'"                    : '';
        $o .= (isset($params['ng']))        ? " ng-controller='{$params['ng']}'"             : '';
        $o .= '>';
        return $o."\n";
    }

    /**
     * Закрыл форму
     * @return string
     */
    public function close(){
        return "</form>\n";
    }

    /**
     * textbox
     * Этот метод создает текстовой элемент
     * 
     * @param   array(id, name, class, onclick, columns, rows, disabled, placeholder, style, value)
     * @return  string
     */
    public function textbox($params = array()) {
        $o = '<textarea';
        $o .= (isset($params['id']))        ? " id='{$params['id']}'"                           : '';
        $o .= (isset($params['name']))      ? " name='{$params['name']}'"                       : '';
        $o .= (isset($params['class']))     ? " class='form-input textbox {$params['class']}'"  : '';
        $o .= (isset($params['onclick']))   ? " onclick='{$params['onclick']}'"                 : '';
        $o .= (isset($params['cols']))      ? " cols='{$params['cols']}'"                       : '';
        $o .= (isset($params['rows']))      ? " rows='{$params['rows']}'"                       : '';
        $o .= (isset($params['disabled']))  ? " disabled='{$params['disabled']}'"               : '';
        $o .= (isset($params['placeholder']))  ? " placeholder='{$params['placeholder']}'"      : '';
        $o .= (isset($params['style']))     ? " style='{$params['style']}'"                     : '';
        $o .= '>';
        $o .= (isset($params['value']))     ? $params['value']                                  : '';
        $o .= "</textarea>\n";
        return $o;
    }

    /**
     * input
     * Этот метод возвращает поле ввода текста элемента.
     * 
     * @param   array(id, name, class, onclick, value, length, width, disable,placeholder)
     * @return  string
     */
    public function input($params = array()) {
        $o = '<input ';
        $o .= (isset($params['type']))      ? " type='{$params['type']}'"                   : 'type="text"';
        $o .= (isset($params['id']))        ? " id='{$params['id']}'"                       : '';
        $o .= (isset($params['name']))      ? " name='{$params['name']}'"                   : '';
        $o .= (isset($params['class']))     ? " class='form-input text {$params['class']}'" : '';
        $o .= (isset($params['onclick']))   ? " onclick='{$params['onclick']}'"             : '';
        $o .= (isset($params['onkeypress']))? " onkeypress='{$params['onkeypress']}'"       : '';
        $o .= (isset($params['value']))     ? ' value="' . $params['value'] . '"'           : '';
        $o .= (isset($params['length']))    ? " maxlength='{$params['length']}'"            : '';
        $o .= (isset($params['width']))     ? " style='width:{$params['width']}px;'"        : '';
        $o .= (isset($params['disabled']))  ? " disabled='{$params['disabled']}'"           : '';
        $o .= (isset($params['placeholder']))  ? " placeholder='{$params['placeholder']}'"  : '';
        $o .= (isset($params['style']))     ? " style='{$params['style']}'"                 : '';
        $o .= (isset($params['autocomplete'])) ? " autocomplete='{$params['autocomplete']}'": '';
        $o .= (isset($params['ng']))        ? "{$params['ng']}"                 : '';    
        $o .= (isset($params['required']))        ? " required='{$params['required']}'"                 : '';            
        $o .= " />\n";
        return $o;
    }

    /**
     * select
     * Этот метод возвращает Выбор формата HTML элемент.
     * Это может быть дан парам под названием значение, которое затем будет предварительно
     * 
     * Данные должны быть array(k=>v)
     * @param   array(id, name, class, onclick, disabled)
     * @return  string
     */
    public function select($params = array()) {
        $o = "<select";
        $o .= (isset($params['id']))        ? " id='{$params['id']}'"                           : '';
        $o .= (isset($params['name']))      ? " name='{$params['name']}'"                       : '';
        $o .= (isset($params['class']))     ? " class='{$params['class']}'"                     : '';
        $o .= (isset($params['onclick']))   ? " onclick='{$params['onclick']}'"                 : '';
        $o .= (isset($params['width']))     ? " style='width:{$params['width']}px;'"            : '';
        $o .= (isset($params['disabled']))  ? " disabled='{$params['disabled']}'"               : '';
        $o .= (isset($params['style']))     ? " style='{$params['style']}'"                 : '';
        $o .= ">\n";
        $o .= "<option value=''>Select</option>\n";
        if (isset($params['data']) && is_array($params['data'])) {
            foreach ($params['data'] as $k => $v) {
                if (isset($params['value']) && $params['value'] == $k) {
                    $o .= "<option value='{$k}' selected='selected'>{$v}</option>\n";
                } else {
                    $o .= "<option value='{$k}'>{$v}</option>\n";
                }
            }
        }
        $o .= "</select>\n";
        return $o;
    }

    /**
     * checkboxMulti
     * Этот метод возвращает несколько элементов флажок в указанном порядке в массиве
     * Для проверки флажок проходят проверить
     * Каждый чекбокс должен выглядеть array(0=>array('id'=>'1', 'name'=>'cb[]', 'value'=>'x', 'label'=>'SHCMS CheckBox' ))
     * 
     * @param   array(array(id, name, value, class, checked, disabled))
     * @return  string
     */
    public function checkbox($params = array()) {
        $o = '';
        if (!empty($params)) {
            $x = 0;
            foreach ($params as $k => $v) {
                //$v['id'] = (isset($v['id']))        ? $v['id']                                          : "cb_id_{$x}_".rand(1000,9999);               
                $o .= "<input type='checkbox'";
                $o .= (isset($v['id']))             ? " id='{$v['id']}'"                                : '';
                $o .= (isset($v['name']))           ? " name='{$v['name']}'"                            : '';
                $o .= (isset($v['value']))          ? " value='{$v['value']}'"                          : '';
                $o .= (isset($v['class']))          ? " class='{$v['class']}'"                          : '';
                $o .= (isset($v['checked']))        ? " checked='checked'"                              : '';
                //$o .= (isset($v['disabled']))       ? " disabled='{$v['disabled']}'"                    : '';
                $o .= (isset($params['style']))     ? " style='{$params['style']}'"                 : '';
                $o .= " />\n";
                $o .= (isset($v['label']))          ? "<label for='{$v['id']}'>{$v['label']}</label> "  : '';
               // $x++;
            }
        }
        return $o;
    }
 
    /**
     * radioMulti
     * Этот метод возвращает элементы радио в указанном порядке в массиве
     * Для выбора проходят проверить
     * Каждый радио должна выглядеть array(0=>array('id'=>'1', 'name'=>'rd[]', 'value'=>'x', 'label'=>'SHCMS Radio' ))
     * 
     * @param   array(array(id, name, value, class, checked, disabled, label))
     * @return  string
     */
    public function radio($params = array()) {
        $o = '';
        if (!empty($params)) {
            $x = 0;
            foreach ($params as $k => $v) {
                $v['id'] = (isset($v['id']))        ? $v['id']                                          : "rd_id_{$x}_".rand(1000,9999);               
                $o .= "<input type='radio'";
                $o .= (isset($v['id']))             ? " id='{$v['id']}'"                                : '';
                $o .= (isset($v['name']))           ? " name='{$v['name']}'"                            : '';
                $o .= (isset($v['value']))          ? " value='{$v['value']}'"                          : '';
                $o .= (isset($v['class']))          ? " class='{$v['class']}'"                          : '';
                $o .= (isset($v['checked']))        ? " checked='checked'"                              : '';
                $o .= (isset($v['disabled']))       ? " disabled='{$v['disabled']}'"                    : '';
                $o .= (isset($params['style']))     ? " style='{$params['style']}'"                 : '';
                $o .= " />\n";
                $o .= (isset($v['label']))          ? "<label for='{$v['id']}'>{$v['label']}</label> "  : '';
                $x++;
            }
        }
        return $o;
    }
 
    /**
     * Этот метод возвращает элемент кнопки учитывая ПАРАМЕТРЫ для настройки
     * 
     * @param   array(id, name, class, onclick, value, disabled)
     * @return  string
     */
    public function button($params = array()) {
        $o = "<button type='submit'";
        $o .= (isset($params['id']))        ? " id='{$params['id']}'"                           : '';
        $o .= (isset($params['name']))      ? " name='{$params['name']}'"                       : '';
        $o .= (isset($params['class']))     ? " class='{$params['class']}'"                     : '';
        $o .= (isset($params['onclick']))   ? " onclick='{$params['onclick']}'"                 : '';
        $o .= (isset($params['disabled']))  ? " disabled='{$params['disabled']}'"               : '';
        $o .= (isset($params['style']))     ? " style='{$params['style']}'"                 : '';
        $o .= ">";
        $o .= (isset($params['iclass']))    ? "<i class='fa {$params['iclass']}'></i> "         : '';
        $o .= (isset($params['value']))     ? "{$params['value']}"                              : '';
        $o .= "</button>\n";
        return $o;
    }
 
    /**
     * Этот метод возвращает элемент кнопки отправки учитывая ПАРАМЕТРЫ для настройки
     * 
     * @param   array(id, name, class, onclick, value, disabled)
     * @return  string
     */
    public function submit($params = array()) {
        $o = '<input type="submit"';
        $o .= (isset($params['id']))        ? " id='{$params['id']}'"                           : '';
        $o .= (isset($params['name']))      ? " name='{$params['name']}'"                       : '';
        $o .= (isset($params['class']))     ? " class='{$params['class']}'"                     : '';
        $o .= (isset($params['onclick']))   ? " onclick='{$params['onclick']}'"                 : '';
        $o .= (isset($params['value']))     ? " value='{$params['value']}'"                     : '';
        $o .= (isset($params['disabled']))  ? " disabled='{$params['disabled']}'"               : '';
        $o .= (isset($params['style']))     ? " style='{$params['style']}'"                 : '';
        $o .= (isset($params['ng']))        ? " {$params['ng']}"                            : '';
        $o .= " />\n";
        return $o;
    }
 
    /**
     * Этот метод возвращает скрытые элементы ввода с учетом его колен
     * 
     * @param   array(id, name, class, value)
     * @return  string
     */
    public function hidden($params = array()) {
        $o = '<input type="hidden"';
        $o .= (isset($params['id']))        ? " id='{$params['id']}'"                           : '';
        $o .= (isset($params['name']))      ? " name='{$params['name']}'"                       : '';
        $o .= (isset($params['class']))     ? " class='{$params['class']}'"   : '';
        $o .= (isset($params['value']))     ? " value='{$params['value']}'"                     : '';
        $o .= " />\n";       
        return $o;
    }



}
