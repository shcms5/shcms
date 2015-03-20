<?php
namespace Shcms\Component\Data\Filesize;

class SizeAll
{
    /**
     * Метрические и двоичные константы
     */
    const BYTES = 'B';
    const kB    = 'kB';
    const MB    = 'MB';
    const GB    = 'GB';
    const TB    = 'TB';
    const PB    = 'PB';
    const EB    = 'EB';
    const ZB    = 'ZB';
    const YB    = 'YB';
    const KiB   = 'KiB';
    const MiB   = 'MiB';
    const GiB   = 'GiB';
    const TiB   = 'TiB';
    const PiB   = 'PiB';
    const EiB   = 'EiB';
    const ZiB   = 'ZiB';
    const YiB   = 'YiB';

    /**
     * Число для преобразования
     *
     * @var float|int
     */
    private $start = 0;

    /**
     * Какие база для преобразования из / в
     *
     * @var int
     */
    private $base = 2;

    /**
     * @var int
     */
    private $from = 0;

    /**
     * Construct
     *
     * @param float $start
     */
    public function __construct($start)
    {
        $this->start = $start;
    }

    /**
     * Блок для преобразования из
     *
     * @param string $unit
     * @return $this
     */
    public function from($unit)
    {
        $this->from = $unit;
        return $this;
    }

    /**
     * Преобразование начальное значение в данном устройстве.
     * Принимает необязательный точность на сколько значащих цифр
     * сохранять
     *
     * @param $unit
     * @param int|null $precision
     * @return float
     */
    public function to($unit, $precision = null)
    {
        $fromUnit = UnitResolver::resolve($this->from);
        $toUnit = UnitResolver::resolve($unit);
        $this->setBase($unit);
        $base = $this->getBase() == 2 ? 1024 : 1000;
        //некоторые напуганные вещи с отрицательными показателями и военнопленных
        if ($toUnit > $fromUnit) {
            return $this->div($this->start, pow($base, $toUnit - $fromUnit), $precision);
        }
        return $this->mul($this->start, pow($base, $fromUnit - $toUnit), $precision);
    }

    /**
     * Преобразование начальное значение для его высокая Весь блок.
     * Accespts необязательная точность на сколько значащих цифр
     * сохранить
     *
     * @param int|null $precision
     * @return float
     */
    public function toBest($precision = null)
    {
        $fromUnit = UnitResolver::resolve($this->from);
        $base = $this->getBase() == 2 ? 1024 : 1000;
        $converted = $this->start;
        while ($converted >= 1) {
            $fromUnit++;
            $result = $this->div($this->start, pow($base, $fromUnit), $precision);
            if ($result <= 1) {
                return $converted;
            }
            $converted = $result;
        }
        return $converted;
    }

    /**
     * Возвращает счисления используется SizeAll
     *
     * @return int
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Возвращает новый SizeAll
     *
     * @param $start
     * @return Shcms\Component\Data\Filesize\SizeAll
     */
    public static function nom($start)
    {
        return new SizeAll($start);
    }

    /**
     * Возвращает новый Размер файла
     *
     * @param $file
     * @return Shcms\Component\Data\Filesize\Filesize
     */
    public static function file($file)
    {
        return new Filesize($file);
    }

    /**
     * Используйте bcdiv если точность определена
     * в противном случае использовать родной оператор деления
     *
     * @param $left
     * @param $right
     * @param $precision
     * @return float
     */
    protected function div($left, $right, $precision)
    {
      
        return floatval(\bcdiv($left, $right, $precision));
    }

    /**
     * Используйте bcmul если точность определена
     * в противном случае использовать родной оператор умножения
     *
     * @param $left
     * @param $right
     * @param $precision
     * @return float
     */
    protected function mul($left, $right, $precision)
    {
        if (is_null($precision)) {
            return $left * $right;
        }
        return floatval(\bcmul($left, $right, $precision));
    }

    /**
     * @param $unit
     * @throws ConversionException
     */
    protected function setBase($unit)
    {
        if ($this->shouldSetBaseTen($unit)) {
            $this->base = 10;
        }
        if (UnitResolver::unitsAreDifferent($this->from, $unit)) {
            throw new ConversionException("Не можете конвертировать между метрической и бинарных форматов");
        }
    }

    /**
     * Матч из против блока, чтобы увидеть, если
     * Основание должно быть установлено на 10
     *
     * @param $unit
     * @return bool
     */
    protected function shouldSetBaseTen($unit)
    {
        $unitMatchesIec = preg_match(UnitResolver::IEC_PATTERN, $unit);
        return
            ($this->from == 'B' && !$unitMatchesIec) ||
            (preg_match(UnitResolver::SI_PATTERN, $this->from) && !$unitMatchesIec);
    }
}
