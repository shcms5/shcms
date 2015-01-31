<?php
/**
 * Класс помощником типа для Filer клянется в входных строк.
 * <example>
 *     $newString = SwearFilter::filter($string);
 * </example>
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
class SwearFilter
{
    /**
     * Удержание таблицы транслитерации (английский комплект)
     * 
     * @var array
     */
    static private $translitTableFrom = array(
        'a', 'b', 'v', 'g', 'd', 'e', 'g', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
        'r', 's', 't', 'u', 'f', 'i', 'e', 'A', 'B', 'V', 'G', 'D', 'E', 'G', 'Z', 'I',
        'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'I', 'E', 'yo', 'h',
        'ts', 'ch', 'sh', 'shch', 'yu', 'ya', 'YO', 'H', 'TS', 'CH', 'SH', 'SHCH', 'YU', 'YA'
    );

    /**
     * Удержание таблицы транслитерации (русский набор)
     * 
     * @var array
     */
    static private $translitTableTo = array(
        'а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
        'р', 'с', 'т', 'у', 'ф', 'ы', 'э', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И',
        'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ы', 'Э', 'ё', 'х',
        'ц', 'ч', 'ш', 'щ', 'ю', 'я', 'Ё', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ю', 'Я'
    );
    
    /**
     * Плохие слова массив строк в формате совместим preg_match RegExp в. Вы должны вручную назначить 
     * Разделители RegExp в, модификатор для сравнения строк начало / конец. Также не забудьте использовать PCRE8
     *    
     * @var array 
     */
    static private $badWords = array(
        "/^.*ху(й|и|я|е|л(и|е)).*/u", "/^.*пи(з|с)д.*/u", "/^бля.*/u", "/^.*бля(д|т|ц).*/u", 
        "/^(с|сц)ук(а|о|и).*/u", "/^еб.*/u", "/^.*уеб.*/u", "/^заеб.*/u", "/^.*еб(а|и)(н|с|щ|ц).*/u", 
        "/^.*ебу(ч|щ).*/u", "/^.*п(и|ы)д(о|е)р.*/u", "/^.*хер.*/u", "/^г(а|о)ндон/u", "/^.*залуп.*/u"
    );
    
    /**
     * С его помощью можно фильтровать входной строки для бранные слова. В процессе фильтрации все бранные слова заменяются
     * by '*' symbol. 
     *
     * @param string $string Source string
     * @return string Filtered строка с клянусь заменены символом '*'.
     */
    public function filter($string)
    {
        $swearingFound = false; 
        //здесь мы взорваться строку на слова
        $elems = explode (" ", $string); 
        $count_elems = count($elems);
        
        for ($i = 0; $i < $count_elems; $i++) {
            //Transliterate цифрой, буквой filterter строка. Мы немогу использование strtr из-за UTF8.
            $str_rep = str_replace(self::$translitTableFrom,self::$translitTableTo,
                preg_replace('/[^a-zA-Zа-яА-Яё]/u', '', mb_strtolower($elems[$i], 'UTF8'))
            );
                        
            //Here we are trying to find bad word matching in the special array
            $countBadWords = count(self::$badWords);
            for ($k = 0; $k < $countBadWords; $k++) {
                if (preg_match(self::$badWords[$k], $str_rep)) {
                    $elems[$i] = str_repeat('*', mb_strlen($elems[$i], 'UTF8') - 1);
                    $swearingFound = true;
                    break;
                }
            }
        }
        
        //here we implode words in the whole string
        if ($swearingFound) {
            $string = implode (" ", $elems);
        }
        
        return $string;
    }
}