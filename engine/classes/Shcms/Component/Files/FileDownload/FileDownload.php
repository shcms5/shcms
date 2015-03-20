<?php
/**
 * Обеспечивает возможность легко создавать загрузку файлов
 *
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
namespace Shcms\Component\Files\FileDownload;

use Shcms\Component\Files\MimeType\Mimetype;
/**
 * Обеспечивает простой способ создания загрузки файлов
 */
class FileDownload
{
    /**
     * Указатель на файл, чтобы загрузить
     *
     * @var resource
     */
    private $filePointer;
    /**
     * Создает новый файл скачать
     *
     * @param resource $filePointer
     *
     * @throws \InvalidArgumentException
     */
    public function __construct ($filePointer)
    {
        if (!is_resource($filePointer))
        {
            throw new \InvalidArgumentException("Вы должны передать указатель файла в CTOR");
        }
        $this->filePointer = $filePointer;
    }
    /**
     * Отправляет скачать в браузере
     *
     * @param string $filename
     * @param bool $forceDownload
     *
     * @throws \RuntimeException выбрасывается, если заголовки уже отправлены
     */
    public function sendDownload ($filename, $forceDownload = true)
    {
        if (headers_sent())
        {
            throw new \RuntimeException("Нельзя отправлять файл в браузере, так как заголовки были уже отправлены.");
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: {$this->getMimeType($filename)}");
        if ($forceDownload)
        {
            header("Content-Disposition: attachment; filename=\"{$filename}\";" );
        }
        else
        {
            header("Content-Disposition: filename=\"{$filename}\";" );
        }
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: {$this->getFileSize()}");
        @ob_clean();
        rewind($this->filePointer);
        fpassthru($this->filePointer);
    }
    /**
     * Возвращает тип пантомимы имени файла
     *
     * @param string $fileName
     *
     * @return string
     */
    private function getMimeType ($fileName)
    {
        $fileExtension  = pathinfo($fileName, PATHINFO_EXTENSION);
        $mimeTypeHelper = Mimetype::getInstance();
        $mimeType       = $mimeTypeHelper->fromExtension($fileExtension);
        return !is_null($mimeType) ? $mimeType : "application/force-download";
    }
    /**
     * Возвращает размер файла
     *
     * @return int
     */
    private function getFileSize ()
    {
        $stat = fstat($this->filePointer);
        return $stat['size'];
    }
    /**
     * Создает новый файл скачать с пути к файлу
     *
     * @static
     *
     * @param string $filePath
     *
     * @throws \InvalidArgumentException выбрасывается, если данный файл не существует или не читается
     *
     * @return FileDownload
     */
    public static function createFromFilePath ($filePath)
    {
        if (!is_file($filePath))
        {
            throw new \InvalidArgumentException("Файл не существует");
        }
        else if (!is_readable($filePath))
        {
            throw new \InvalidArgumentException("Файл для скачивания не читается.");
        }
        return new FileDownload(fopen($filePath, "rb"));
    }
    /**
     * Создает новый файл Download Helper с заданным содержанием
     *
     * @static
     *
     * @param string $content Содержимое файла
     *
     * @return FileDownload
     */
    public static function createFromString ($content)
    {
        $file = tmpfile();
        fwrite($file, $content);
        return new FileDownload($file);
    }
}