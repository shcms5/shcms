<?php
/**
 * Класс Интерфейса Информации о файле
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */
namespace Shcms\Resources\Upload;

interface FileInfoInterface
{
    public function getPathname();

    public function getName();

    public function setName($name);

    public function getExtension();

    public function setExtension($extension);

    public function getNameWithExtension();

    public function getMimetype();

    public function getSize();

    public function getMd5();

    public function getDimensions();

    public function isUploadedFile();
}
