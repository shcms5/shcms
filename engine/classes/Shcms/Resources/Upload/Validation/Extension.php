<?php
/**
 * Подтвердить расширение файла
 * 
 * Этот класс проверяет в дата загрузки расширение файла. Она занимает расширение файла с из точки
 * или массив расширений. Например: "PNG" или массива ('JPG' ',' PNG ',' GIF ').
 * 
 * ВНИМАНИЕ! Проверка только расширение не очень безопасно файлов.
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */
namespace Shcms\Resources\Upload\Validation;

class Extension implements \Shcms\Resources\Upload\ValidationInterface
{
    /**
     * Массив допустимых расширений файлов без ведущих точек
     * @var array
     */
    protected $allowedExtensions;

    /**
     * Конструктор
     *
     * @param string|array $allowedExtensions Допустимые расширения файлов
     * @example new \Shcms\Resources\Upload\Validation\Extension(array('png','jpg','gif'))
     * @example new \Shcms\Resources\Upload\Validation\Extension('png')
     */
    public function __construct($allowedExtensions)
    {
        if (is_string($allowedExtensions) === true) {
            $allowedExtensions = array($allowedExtensions);
        }

        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
    }

    /**
     * Валидация
     *
     * @param  \Shcms\Resources\Upload\FileInfoInterface $fileInfo
     * @throws \RuntimeException         Если проверка не пройдена
     */
    public function validate(\Shcms\Resources\Upload\FileInfoInterface $fileInfo)
    {
        $fileExtension = strtolower($fileInfo->getExtension());

        if (in_array($fileExtension, $this->allowedExtensions) === false) {
            throw new \Shcms\Resources\Upload\Exception(sprintf('Неверный файл с расширением. Должен быть одним из: %s', implode(', ', $this->allowedExtensions)), $fileInfo);
        }
    }
}
