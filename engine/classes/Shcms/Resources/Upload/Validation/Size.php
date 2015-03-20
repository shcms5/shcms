<?php
/**
 * Проверка Загрузить Размер файла
 * 
 * Этот класс проверяет в дата загрузки размер файла, используя максимум и (по желанию)
 * Минимальный размер файла границы (включительно). Укажите приемлемые размеры файлов
 * Как целое (в байтах), либо как человеческого восприятия строку (например, "5 МБ").
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */
namespace Shcms\Resources\Upload\Validation;

class Size implements \Shcms\Resources\Upload\ValidationInterface
{
    /**
     * Минимальный приемлемый размер файла (в байтах)
     * @var int
     */
    protected $minSize;

    /**
     * Максимально допустимая размер файла (в байтах)
     * @var int
     */
    protected $maxSize;

    /**
     * Конструктор
     *
     * @param int $maxSize Максимально допустимая размер файла в байтах (включительно)
     * @param int $minSize Минимальный приемлемый размер файла в байтах (включительно)
     */
    public function __construct($maxSize, $minSize = 0)
    {
        if (is_string($maxSize)) {
            $maxSize = \Shcms\Resources\Upload\File::humanReadableToBytes($maxSize);
        }
        $this->maxSize = $maxSize;

        if (is_string($minSize)) {
            $minSize = \Shcms\Resources\Upload\File::humanReadableToBytes($minSize);
        }
        $this->minSize = $minSize;
    }

    /**
     * Валидация
     *
     * @param  \Shcms\Resources\Upload\FileInfoInterface  $fileInfo
     * @throws \RuntimeException          Если проверка не пройдена
     */
    public function validate(\Shcms\Resources\Upload\FileInfoInterface $fileInfo)
    {
        $fileSize = $fileInfo->getSize();

        if ($fileSize < $this->minSize) {
            throw new \Shcms\Resources\Upload\Exception(sprintf('Размер файла слишком мал. Должно быть больше или равна: %s', $this->minSize), $fileInfo);
        }

        if ($fileSize > $this->maxSize) {
            throw new \Shcms\Resources\Upload\Exception(sprintf('Размер файла слишком велик. Должно быть не менее: %s', $this->maxSize), $fileInfo);
        }
    }
}
