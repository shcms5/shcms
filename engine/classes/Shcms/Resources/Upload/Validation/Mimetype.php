<?php
/**
 * Тип Подтвердить Загрузить Медиа
 * 
 * Этот класс проверяет тип носителя на закачку (например, "image/png").
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */
namespace Shcms\Resources\Upload\Validation;

class Mimetype implements \Shcms\Resources\Upload\ValidationInterface
{
    /**
     * Типы Допустимые СМИ
     * @var array
     */
    protected $mimetypes;

    /**
     * Конструктор
     *
     * @param string|array $mimetypes
     */
    public function __construct($mimetypes)
    {
        if (is_string($mimetypes) === true) {
            $mimetypes = array($mimetypes);
        }
        $this->mimetypes = $mimetypes;
    }

    /**
     * Валидация
     *
     * @param  \Shcms\Resources\Upload\\FileInfoInterface  $fileInfo
     * @throws \RuntimeException          Если проверка не пройдена
     */
    public function validate(\Shcms\Resources\Upload\FileInfoInterface $fileInfo)
    {
        if (in_array($fileInfo->getMimetype(), $this->mimetypes) === false) {
            throw new \Shcms\Resources\Upload\Exception(sprintf('Неверный MimeType. Должен быть одним из: %s', implode(', ', $this->mimetypes)), $fileInfo);
        }
    }
}
