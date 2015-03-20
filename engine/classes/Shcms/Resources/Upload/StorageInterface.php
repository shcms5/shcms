<?php
/**
 * Класс Интерфейса системы хранения
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */
namespace Shcms\Resources\Upload;

interface StorageInterface
{
    /**
     * Загрузить файл
     *
     * Этот метод отвечает за загрузку `\Shcms\Resources\Upload\FileInfoInterface` пример
     * его назначению. Если сбой отправки, исключение должно быть брошено.
     *
     * @param  \Shcms\Resources\Upload\FileInfoInterface $fileInfo
     * @throws \Exception               Если сбой отправки
     */
    public function upload(\Shcms\Resources\Upload\FileInfoInterface $fileInfo);
}
