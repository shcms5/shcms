<?php
namespace Shcms\Resources\Upload;

class Exception extends \RuntimeException
{
    /**
     * @var \Shcms\Resources\Upload\FileInfoInterface
     */
    protected $fileInfo;

    /**
     * Конструктор
     *
     * @param string                    $message Сообщение об исключении
     * @param \Shcms\Resources\Upload\FileInfoInterface $fileInfo Связанных с экземпляром файл
     */
    public function __construct($message, \Shcms\Resources\Upload\FileInfoInterface $fileInfo = null)
    {
        $this->fileInfo = $fileInfo;

        parent::__construct($message);
    }

    /**
     * Получить соответствующий файл
     *
     * @return \Shcms\Resources\Upload\FileInfoInterface
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }
}
