<?php 
/**
 * Класс постраничной навигации для файлов 
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */

class Navigation { 
/**
 * Конструктор навигации
 
 * @param $input     - массив данных ( array() - для файлов, int - для записей из бд ) 
 * @param $on_page   - сколько результатов выводить на странице ( int ) 
 * @param $print_str - писать ли возле ссылок на страницы Стр. ( bool (true / false ) ) 
 * @param $order     - сортировка для файлов, asc - по возрастанию, desc - по убыванию (  string  ( asc , desc ))
 * @param $link      - строка запроса, которая прицепится к ссылкам на страницы ( string ) 
*/
public function __construct($input,$on_page,$print_str = false, $order = 'asc',$link = '?') 
{ 
     
    // если входящий массив 
    if(is_array($input)) 
    { 
        // перестройка массива в нужный формат для навигации 
        $this->rfile=array_values($input); 
        // счетчик файлов 
        $this->all_files = count($this->rfile); 
        // оборачиваем(сортируем в нужном направлении) или оставляем без изменений массив файлов 
        $this->rfile = $order == 'asc' ? $this->rfile : array_reverse($this->rfile); 
        $this->type_result = 'Файлов'; 
    } 
    else 
    { 
        $this->all_files = $input; 
        $this->type_result = 'Записей'; 
    }     
    // линк который цеплять к ссылкам постранички 
    $this->link = $link; 
    // сколько результатов на страницу 
    $this->on_page = $on_page < 1 ? 1 : $on_page; 
    // пишем или не пишем Стр. 
    $this->str = $print_str === true ? '' : ''; 
    // сортировка, asc - по убыванию, desc - по возрастанию 
    $this->order = $order; 
     
    // всего страниц 
    $this->all_pages = ceil($this->all_files / $this->on_page); 
     
    // если не установлен page = он равен 1, если установленый page больше 
    // за все страниц - окончательный page равен последней странице 
    $this->page = !isset($_GET['p']) || $_GET['p'] <= 0 ? 1 : abs(intval($_GET['p'])); 
    $this->page = $this->page > $this->all_pages ? $this->all_pages : $this->page; 
    // бросать ли на последнюю страницу 
    $this->page = isset($_GET['last']) ? $this->all_pages : $this->page;     
     
    // старт и конец отсчета файлов в массиве 
    $this->start = ($this->page * $this->on_page) - 1; 
    // если число больше чем файлов, знач максим число = это число файлов 
    $this->start = $this->start > $this->all_files  ? $this->all_files : $this->start; 
    // начальное число массива 
    $this->end   = ($this->page * $this->on_page)- $this->on_page; 
    // последние 3 страницы 
    $this->last_three_page = $this->all_pages - 2; 
    // "стрелочки" возле ссылок Вперед/Назад 
    $this->lt = '&#8592; '; // Назад 
    $this->gt = ' &#8594;'; // Вперед 
} 
/** 
  *  вывод списка страниц  
  */ 
public function pagination($pag = false) { 
    // пишем или не пишем Стр. 
    $string_pages = $this->str; 
     
    // если страниц больше чем 10, выводим тройной линк, иначе все ссылки подряд 
    if($this->all_pages > 10) 
    { 
        // если текущая страница меньше равна 3 
        // выводим первые три ссылки и последние 
        if($this->page <= 3) 
        { 
            // выводим первые три ссылки 
            for($i=1;$i<=3;$i++) 
            { 
                $string_pages .= $this->page == $i ? ' <li class="active"><a href="#">'.$i.'</a></li>' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a></li> '; 
            } 
             
            // если пейдж 3 - рендерим 4 сстраницу 
            if($this->page == 3) 
            { 
                $string_pages .= '<li><a href="'.$this->link.''.$pag.'&amp;p=4">4</a></li>'; 
            } 
             
            // и последние 3 ссылки, через троеточие 
            $string_pages .= '<li class="disabled"><a href="#">...</a></li>'; 
             
            for($i = $this->last_three_page;$i<=$this->all_pages;$i++) 
            { 
                $string_pages .= $this->page == $i ? ' <li class="active"><a href="#">'.$i.'</a></li>' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a></li> '; 
            } 
             
        } 
        // если ссылки вышли за радиус обзора, т.е в середину списка 
        elseif($this->page < 6 && $this->page <= $this->last_three_page) 
        { 
             
            // выводим первые три ссылки 
            for($i=1;$i<=6;$i++) 
            { 
                 
                $string_pages .= $this->page == $i ? ' <li class="active"><a href="#">'.$i.'</a></li>' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a></li> '; 
            } 
             
            // и последние 3 ссылки, через троеточие 
            $string_pages .= '<li class="disabled"><a href="#">...</a></li>'; 
             
            for($i = $this->last_three_page;$i<=$this->all_pages;$i++) 
            { 
                $string_pages .= $this->page == $i ? '<li class="active"><a href="#">'.$i.'</a></li>' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a> </li>'; 
            } 
        } 
        // если выбранная страница >= 6 
        elseif($this->page >= 6 && $this->page <= ($this->last_three_page - 2)) 
        { 
             
            // выводим первые три ссылки 
            for($i=1;$i<=3;$i++) 
            { 
                 
                $string_pages .= $this->page == $i ? '<li class="active"><a href="#">'.$i.'</a></li>' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a> </li>'; 
            } 
            $string_pages .= '<li class="disabled"><a href="#">...</a></li>'; 
             
            // одна ссылка слева/ неактивная посредине / ссылка справа 
            $string_pages .= ' <li><a href="'.$this->link.''.$pag.'&amp;p='.($this->page - 1).'">'.($this->page - 1).'</a></li>'; 
            $string_pages .= ' '.$this->page.' '; 
            $string_pages .= '<li><a href="'.$this->link.''.$pag.'&amp;p='.($this->page + 1).'">'.($this->page + 1).'</a></li>'; 
             
            // и последние 3 ссылки, через троеточие 
            $string_pages .= '<li class="disabled"><a href="#">...</a></li>'; 
             
            for($i = ($this->last_three_page);$i<=$this->all_pages;$i++) 
            { 
                $string_pages .= $this->page == $i ? '<li class="active"><a href="#">'.$i.'</a></li> ' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a> </li>'; 
            } 
        } 
        // если выбранные ссылки в конце 
        elseif(($this->page + 1) >= ($this->last_three_page)) 
        { 
            // выводим первые три ссылки 
            for($i=1;$i<=3;$i++) 
            { 
                 
                $string_pages .= $this->page == $i ? '<li class="active"><a href="#">'.$i.'</a></li> ' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a> </li>'; 
            } 
                         
            // и последние 3 ссылки, через троеточие 
            $string_pages .= '<li class="disabled"><a href="#">...</a></li>'; 
             
            for($i = ($this->last_three_page - 3);$i<=$this->all_pages;$i++) 
            { 
                $string_pages .= $this->page == $i ? ' <li class="active"><a href="#">'.$i.'</a> </li>' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a> </li>'; 
            } 
        } 
    } 
    else 
    { 
        // вывод всех ссылок, если ссылок <= 10 
        for($i=1;$i<=$this->all_pages;$i++) 
        { 
            $string_pages .= $this->page == $i ? ' <li class="active"><a href="#">'.$i.'</a></li>' : '<li><a href="'.$this->link.''.$pag.'&amp;p='.$i.'">'.$i.'</a></li> '; 
        } 
    } 
    return '<center><nav><ul class="pagination">'.$string_pages.'</ul></nav></center>';  
} 

/** 
 * function result_files 
 * результирующий массив файлов 
 */ 

public function result_files() { 
     
    $this->arr = array(); 
    for($i=$this->end;$i<=$this->start;$i++) 
    { 
        if(isset($this->rfile[$i])) 
        { 
            $this->arr[$i] = $this->rfile[$i]; 
        } 
    } 
     
    return $this->arr; 
} 
/** 
 * function show_info 
 * выводим сколько всего файлов 
 */ 

public function show_info($vsego = false) { 
    $res = $vsego != false ? $vsego : $this->type_result; 
    return $res.': '.$this->all_files; 
} 

/** 
 * function back_forward_links 
 * функция вывода линков вида - Назад | Вперед 
 */ 

public function back_forward_links($razdelitel = ' | ' , $poradok = true) { 
     
    if($poradok === true) 
    { 
    if($this->page <= 1) 
    { 
        $this->this_links = '<a href="'.$this->link.'&amp;p='.($this->page+1).'">Вперед'.$this->gt.'</a>'; 
    } 
    elseif($this->page >= $this->all_pages) 
    { 
        $this->this_links = '<a href="'.$this->link.'&amp;p='.($this->page-1).'">'.$this->lt.'Назад</a>'; 
    } 
    else 
    { 
        $this->this_links = '<a href="'.$this->link.'&amp;p='.($this->page-1).'">'.$this->lt.'Назад</a>'.$razdelitel.'<a href="'.$this->link.'&amp;p='.($this->page+1).'">Вперед'.$this->gt.'</a>'; 
    } 
    } 
    else 
    { 
        if($this->page <= 1) 
    { 
        $this->this_links = '<a href="'.$this->link.'&amp;p='.($this->page+1).'">Назад'.$this->gt.'</a>'; 
    } 
    elseif($this->page >= $this->all_pages) 
    { 
        $this->this_links = '<a href="'.$this->link.'&amp;p='.($this->page-1).'">'.$this->lt.'Вперед</a>'; 
    } 
    else 
    { 
        $this->this_links = '<a href="'.$this->link.'&amp;p='.($this->page-1).'">'.$this->lt.'Вперед</a>'.$razdelitel.'<a href="'.$this->link.'&amp;p='.($this->page+1).'">Назад'.$this->gt.'</a>'; 
    } 
    } 
     
    return $this->this_links; 
} 

/** 
 * function show_form 
 * функция показа формы для перехода 
 */ 

public function show_form($array = array()) { 
     
    $hidden = ''; 
    // добавляем скрытые поля к форме 
    if(count($array) > 0) 
    { 
    foreach($array as $key => $value) 
    { 
        $hidden .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />'; 
    } 
    } 
     
    return '<form action="'.$_SERVER['PHP_SELF'].'" method="get">'. 
    '<div>'.$hidden. 
    'Стр. <input type="text" size="4" name="p" value="'.$this->page.'" />'. 
    '<input type="submit" value="Go!" />'.     
    '</div>'. 
    '</form>'; 
} 

/** 
 * function limit 
 * Лимит выводящих записей 
 */ 

function limit() { 
    // высчитываем лимиты 
    return 'limit '.(($this->page*$this->on_page)-$this->on_page).' ,'.$this->on_page." ;"; 
} 

} 