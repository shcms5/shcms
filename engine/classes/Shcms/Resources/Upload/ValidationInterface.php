<?php
/**
 * Класс Интерфейса Валидации
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */
namespace Shcms\Resources\Upload;
interface ValidationInterface
{
    public function validate(\Shcms\Resources\Upload\FileInfoInterface $fileInfo);
}
