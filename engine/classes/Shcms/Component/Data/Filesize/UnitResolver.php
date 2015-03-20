<?php
namespace Shcms\Component\Data\Filesize;

class UnitResolver
{
    /**
     * Шаблон для проверки является стандартной единицей IEC
     *
     * @var string
     */
    const IEC_PATTERN = '/[A-Z]iB/';

    /**
     * Шаблон для проверки является стандартной единицей СИ
     */
    const SI_PATTERN = '/[A-Zk]B/';

    /**
     * Binary справочная таблица
     *
     * @var array
     */
    private static $binary = array(
        'KiB' => 1,
        'MiB' => 2,
        'GiB' => 3,
        'TiB' => 4,
        'PiB' => 5,
        'EiB' => 6,
        'ZiB' => 7,
        'YiB' => 8
    );

    /**
     * Метрическая справочная таблица
     *
     * @var array
     */
    private static $metric = array(
        'kB' => 1,
        'MB' => 2,
        'GB' => 3,
        'TB' => 4,
        'PB' => 5,
        'EB' => 6,
        'ZB' => 7,
        'YB' => 8
    );

    /**
     * Взгляд вверх показатель на основе префикса
     *
     * @param $key
     * @throws UnitNotFoundException
     * @return int
     */
    public static function resolve($key)
    {
        if ($key == 'B') {
            return 0;
        }
        $dict = static::$metric;
        if (preg_match(static::IEC_PATTERN, $key)) {
            $dict = static::$binary;
        }
        if (array_key_exists($key, $dict)) {
            return $dict[$key];
        }
        throw new UnitNotFoundException(sprintf('Блок "%s" не найден', $key));
    }


    /**
     * Проверьте, если два устройства находятся в одной и той же семьи
     *
     * @param string $first
     * @param string $second
     * @return bool
     */
    public static function unitsAreDifferent($first, $second)
    {
        return
            (preg_match(UnitResolver::SI_PATTERN, $first) && preg_match(UnitResolver::IEC_PATTERN, $second)) ||
            (preg_match(UnitResolver::IEC_PATTERN, $first) && preg_match(UnitResolver::SI_PATTERN, $second));
    }
}