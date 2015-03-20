<?php
 /**
  * Класс для Фильтрации Полученных Ссылок
  * 
  * @package            Classes
  * @author             Shamsik
  * @link               http://shcms.ru
  */
namespace Shcms\Component\String;

class Url {
  public static $maps = array (
    /**
     * @var Немецкий
     */  
    'de' => array (
      'Ä' => 'Ae', 'Ö' => 'Oe', 'Ü' => 'Ue', 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
      'ẞ' => 'SS'
    ),
    /**
     * @var Латинский
     */  
    'latin' => array (
      'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A','Ă' => 'A', 'Æ' => 'AE', 'Ç' =>
        'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
      'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' =>
        'O', 'Ő' => 'O', 'Ø' => 'O','Ș' => 'S','Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U',
      'Ý' => 'Y', 'Þ' => 'TH', 'ß' => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' =>
        'a', 'å' => 'a', 'ă' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
      'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' =>
        'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 'ø' => 'o', 'ș' => 's', 'ț' => 't', 'ù' => 'u', 'ú' => 'u',
      'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 'ÿ' => 'y'
    ),
    'latin_symbols' => array (
      '©' => '(c)'
    ),
    /**
     * @var Греческий
     */  
    'el' => array (
      'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
      'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
      'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
      'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
      'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
      'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
      'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
      'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
      'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
      'Ϋ' => 'Y'
    ),
    /**
     * @var Турецкий
     */        
    'tr' => array (
      'ş' => 's', 'Ş' => 'S', 'ı' => 'i', 'İ' => 'I', 'ç' => 'c', 'Ç' => 'C', 'ü' => 'u', 'Ü' => 'U',
      'ö' => 'o', 'Ö' => 'O', 'ğ' => 'g', 'Ğ' => 'G'
    ),
    /**
     * @var Российский
     */  
    'ru' => array (
      'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
      'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
      'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
      'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
      'я' => 'ya',
      'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
      'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
      'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
      'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
      'Я' => 'Ya',
      '№' => ''
    ),
    /**
     * @var Украинский
     */  
    'uk' => array (
      'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G', 'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g'
    ),
    /**
     * @var Чешский
     */  
    'cs' => array (
      'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
      'ž' => 'z', 'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T',
      'Ů' => 'U', 'Ž' => 'Z'
    ),
    /**
     * @var Польшский
     */  
    'pl' => array (
      'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
      'ż' => 'z', 'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S',
      'Ź' => 'Z', 'Ż' => 'Z'
    ),
    /**
     * @var Румынский
     */  
    'ro' => array (
      'ă' => 'a', 'â' => 'a', 'î' => 'i', 'ș' => 's', 'ț' => 't'
    ),
    /**
     * @var Латышский
     */  
    'lv' => array (
      'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
      'š' => 's', 'ū' => 'u', 'ž' => 'z', 'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i',
      'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z'
    ),
    /**
     * @var Литовский
     */  
    'lt' => array (
      'ą' => 'a', 'č' => 'c', 'ę' => 'e', 'ė' => 'e', 'į' => 'i', 'š' => 's', 'ų' => 'u', 'ū' => 'u', 'ž' => 'z',
      'Ą' => 'A', 'Č' => 'C', 'Ę' => 'E', 'Ė' => 'E', 'Į' => 'I', 'Š' => 'S', 'Ų' => 'U', 'Ū' => 'U', 'Ž' => 'Z'
    ),
    /**
     * @var Некоторые специальные символы
     */  
    'entities' => array (
      '&' => '&amp;',
      '<' => '&lt;',
      '>' => '&gt;',
      '"' => '&quot;',
      '\'' => '&apos;',
    )
  );
  /**
   * Список слов, чтобы удалить из URL.
   */
  public static $remove_list = array (
    'a', 'an', 'as', 'at', 'before', 'but', 'by', 'for', 'from',
    'is', 'in', 'into', 'like', 'of', 'off', 'on', 'onto', 'per',
    'since', 'than', 'the', 'this', 'that', 'to', 'up', 'via',
    'with'
  );
  /**
   * Таблица символов.
   */
  private static $map = array ();
  /**
   * Список символов в строке.
   */
  private static $chars = '';
  /**
   * Список символов в виде регулярного выражения.
   */
  private static $regex = '';
  /**
   * Текущий язык
   */
  private static $language = '';
  /**
   * Инициализирует карту символов.
   */
  private static function init ($language = "") {
    if (count (self::$map) > 0 && (($language == "") || ($language == self::$language))) {
      return;
    }
    /**
     * Определенную карту Связана с $language
     */
    if (isset(self::$maps[$language]) && is_array(self::$maps[$language])) {
      /* Перемещение эту карту до конца. Это означает, что будет иметь приоритет над другими */
      $m = self::$maps[$language];
      unset(self::$maps[$language]);
      self::$maps[$language] = $m;
    }
    /* Сброс статические Вар */
    self::$language = $language;
    self::$map = array();
    self::$chars = '';
    foreach (self::$maps as $map) {
      foreach ($map as $orig => $conv) {
        self::$map[$orig] = $conv;
        self::$chars .= $orig;
      }
    }
    self::$regex = '/[' . self::$chars . ']/u';
  }
  /**
   * Добавить новые символы в списке. `$map` должно быть хэш.
   */
  public static function add_chars ($map) {
    if (! is_array ($map)) {
      throw new LogicException ('$map должны быть ассоциативным массивом.');
    }
    self::$maps[] = $map;
    self::$map = array ();
    self::$chars = '';
  }
  /**
   * Добавление слова в список удалить. Принимает либо отдельные слова
   * или массив слов.
   */
  public static function remove_words ($words) {
    $words = is_array ($words) ? $words : array ($words);
    self::$remove_list = array_merge (self::$remove_list, $words);
  }
  /**
   * Транслитерирует символы на ASCII эквиваленты.
   * $language определяет приоритет для конкретного языка.
   * Последнее полезно, если языки имеют разные правила для того же характера.
   */
  public static function downcode ($text, $language = "") {
    self::init ($language);
    if (preg_match_all (self::$regex, $text, $matches)) {
      for ($i = 0; $i < count ($matches[0]); $i++) {
        $char = $matches[0][$i];
        if (isset (self::$map[$char])) {
          $text = str_replace ($char, self::$map[$char], $text);
        }
      }
    }
    return $text;
  }
  /**
   * Фильтры строку, например, "мелкая кража" на "мелкую кражу"
   */
  public static function filter ($text, $length = 60, $language = "", $file_name = false) {
    $text = self::downcode ($text,$language);
    // удалить все эти слова из строки перед urlifying
    $text = preg_replace ('/\b(' . join ('|', self::$remove_list) . ')\b/i', '', $text);
    // if downcode doesn't hit, the char will be stripped here
    $remove_pattern = ($file_name) ? '/[^-.\w\s]/' : '/[^-\w\s]/';
    //Удалить ненужные символы
    $text = preg_replace ($remove_pattern, '', $text);
    //удовольствие подчеркивает как пространства
    $text = str_replace ('_', ' ', $text);
    //отделка начальные / конечные пробелы
    $text = preg_replace ('/^\s+|\s+$/', '', $text);
    //конвертировать пространства в тире
    $text = preg_replace ('/[-\s]+/', '-', $text);
    //конвертировать в нижний регистр
    $text = strtolower ($text);
    	
        // обрезать до первых $length символов
        return trim (substr ($text, 0, $length), '-');
  }
  /**
   * Псевдоним  `Url::downcode()`.
   */
  public static function transliterate ($text) {
    return self::downcode ($text);
  }
}