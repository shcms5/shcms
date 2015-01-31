<?php
/**
 * Класс для работой с временем
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
namespace Shcms\Component\Data\Bundle;

class DateType {
    
    static protected $_LANGDATE = array (
        'January'    =>    "января",
        'February'   =>    "февраля",
        'March'      =>    "марта",
        'April'      =>    "апреля",
        'May'        =>    "мая",
        'June'       =>    "июня",
        'July'       =>    "июля",
        'August'     =>    "августа",
        'September'  =>    "сентября",
        'October'    =>    "октября",
        'November'   =>    "ноября",
        'December'   =>    "декабря",
        'Jan'        =>    "январь",
        'Feb'        =>    "февраль",
        'Mar'        =>    "март",
        'Apr'        =>    "апрель",
        'Jun'        =>    "июнь",
        'Jul'        =>    "июль",
        'Aug'        =>    "август",
        'Sep'        =>    "сентябрь",
        'Oct'        =>    "октябрь",
        'Nov'        =>    "ноябрь",
        'Dec'        =>    "декабрь",

        'Sunday'    =>    "Воскресенье",
        'Monday'    =>    "Понедельник",
        'Tuesday'   =>    "Вторник",
        'Wednesday' =>    "Среда",
        'Thursday'  =>    "Четверг",
        'Friday'    =>    "Пятница",
        'Saturday'  =>    "Суббота",

        'Sun'       =>    "Вс",
        'Mon'       =>    "Пн",
        'Tue'       =>    "Вт",
        'Wed'       =>    "Ср",
        'Thu'       =>    "Чт",
        'Fri'       =>    "Пт",
        'Sat'       =>    "Сб",
    );
    
    /**
     * @desc перевести rus=>rus
     */
    static public function _date($format, $timestamp)
    {
        return strtr(@date($format, $timestamp), self::$_LANGDATE);
    }

    /**
     * @desc преобразование unix timestamp к норме Дата (Сейчас ...., Вчера ...., и т.д.)
     * @param int $timestamp unix-timestamp
     * @return string time
     */
    static public function make_date($timestamp)
    {
        if (date('Ymd', $timestamp) == date('Ymd', time()))
        {
            return 'Сегодня'. self::_date(', H:i', $timestamp);
        }
        elseif(date('Ymd', $timestamp) == date('Ymd', (time() - 86400)))
        {
            return 'Вчера' . self::_date(', H:i', $timestamp);
        }
        else
        {
            return self::_date('d M. Y, H:i', $timestamp);
        }
    }

    CONST PERIOD_YEAR = 31536000;
    CONST PERIOD_MONTH = 2592000;
    CONST PERIOD_DAY = 86400;
    CONST PERIOD_HOUR = 3600;
    CONST PERIOD_MINUTE = 60;
    
    /**
     * @desc конвертировать секунд, чтобы норма формат (y.d.m.h)
     * @param int $seconds seconds
     * @return string time
     */
    static public function sec_to_time($seconds)
    {
        //self::_number_ending($n, "продуктов", "продукт", "продукта")
        $seconds_period = array(
        self::PERIOD_YEAR   => 'г.',
        self::PERIOD_MONTH  => 'м',
        self::PERIOD_DAY    => 'д.',
        self::PERIOD_HOUR   => 'ч.',
        self::PERIOD_MINUTE => 'мин.',
        );
        $out = '';
        foreach ($seconds_period as $period => $date_words) {
          $number = floor($seconds / $period);
          $out .= $number ? $number.$date_words.' ' : '';
          $seconds -= $number * $period;
        }
        return $out;
    }
}

$tdate = new \Shcms\Component\Data\Bundle\DateType();