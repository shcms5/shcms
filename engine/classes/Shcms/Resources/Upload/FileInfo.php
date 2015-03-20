<?php
/**
 * Класс для работы с Информацией о файлах
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */
namespace Shcms\Resources\Upload;

class FileInfo extends \SplFileInfo implements \Shcms\Resources\Upload\FileInfoInterface
{
    /**
     * Фабрика метод, который возвращает новый экземпляр \ FileInfoInterface
     * @var callable
     */
    protected static $factory;

    /**
     * Имя файла (без расширения)
     * @var string
     */
    protected $name;

    /**
     * Расширение файла (без точки префикса)
     * @var string
     */
    protected $extension;

    /**
     * MimeType файлов
     * @var string
     */
    protected $mimetype;

    /**
     * Конструктор
     *
     * @param string $filePathname   Абсолютный путь к загруженного файла на диске
     * @param string $newName        Желаемый имя файла (с расширением) из загруженного файла
     */
    public function __construct($filePathname, $newName = null)
    {
        $desiredName = is_null($newName) ? $filePathname : $newName;
        $this->name = pathinfo($desiredName, PATHINFO_FILENAME);
        $this->extension = strtolower(pathinfo($desiredName, PATHINFO_EXTENSION));

        parent::__construct($filePathname);
    }

    /**
     * Получить имя файла (без расширения)
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Набор имя файла (без расширения)
     *
     * @param  string           $name
     * @return \Shcms\Resources\Upload\FileInfo Self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Получить расширение файла (без точки префикса)
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Расширение набора файлов (без точки префикса)
     *
     * @param  string           $extension
     * @return \Shcms\Resources\Upload\FileInfo Self
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Получить имя файла с расширением
     *
     * @return string
     */
    public function getNameWithExtension()
    {
        return $this->extension === '' ? $this->name : sprintf('%s.%s', $this->name, $this->extension);
    }

    /**
     * Получить MimeType
     *
     * @return string
     */
    public function getMimetype()
    {
        if (isset($this->mimetype) === false) {
            $finfo = new \finfo(FILEINFO_MIME);
            $mimetype = $finfo->file($this->getPathname());
            $mimetypeParts = preg_split('/\s*[;,]\s*/', $mimetype);
            $this->mimetype = strtolower($mimetypeParts[0]);
            unset($finfo);
        }

        return $this->mimetype;
    }

    /**
     * Получить MD5
     *
     * @return string
     */
    public function getMd5()
    {
        return md5_file($this->getPathname());
    }

    /**
     * Получить размеры изображения
     *
     * @return array() отформатирован массив размеров
     */
    public function getDimensions()
    {
        list($width, $height) = getimagesize($this->getPathname());

        return array(
            'width' => $width,
            'height' => $height
        );
    }

    /**
     * Загрузили этот файл с запросом POST?
     *
     * Это отдельный метод, так что он может быть подменены в стандартных тестах, чтобы избежать
     * трудно зависимость от `is_uploaded_file` функции.
     *
     * @return bool
     */
    public function isUploadedFile()
    {
        return is_uploaded_file($this->getPathname());
    }

    public static function setFactory($callable)
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new \InvalidArgumentException('Обратный звонок не Закрытие и доступен объект.');
        }

        static::$factory = $callable;
    }

    public static function createFromFactory($tmpName, $name = null) {
        if (isset(static::$factory) === true) {
            $result = call_user_func_array(static::$factory, array($tmpName, $name));
            if ($result instanceof \Shcms\Resources\Upload\FileInfoInterface === false) {
                throw new \RuntimeException('FileInfo завод должен вернуться экземпляр \Upload\FileInfoInterface.');
            }

            return $result;
        }

        return new static($tmpName, $name);
    }
}
