<?php
/**
 * Класс для работы с Файловой системой хранения
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */

namespace Shcms\Resources\Upload\Storage;


class FileSystem implements \Shcms\Resources\Upload\StorageInterface
{
    /**
     * Путь загрузить каталог назначения (с косой чертой)
     * @var string
     */
    protected $directory;

    /**
     * Заменить существующие файлы?
     * @var bool
     */
    protected $overwrite;

    /**
     * Конструктор
     *
     * @param  string                    $directory Относительный или абсолютный путь для загрузки каталога
     * @param  bool                      $overwrite Если это перезаписывать существующие файлы?
     * @throws \InvalidArgumentException            Если каталог не существует
     * @throws \InvalidArgumentException            Если каталог не доступен для записи
     */
    public function __construct($directory, $overwrite = false)
    {
        if (!is_dir($directory)) {
            throw new \InvalidArgumentException('Каталог не существует');
        }
        if (!is_writable($directory)) {
            throw new \InvalidArgumentException('Каталог невозможна');
        }
        $this->directory = rtrim($directory, '/') . DIRECTORY_SEPARATOR;
        $this->overwrite = (bool)$overwrite;
    }

    /**
     * Загружаем файл 
     *
     * @param  \Shcms\Resources\Upload\FileInfoInterface $file  Объект файл для загрузки
     * @throws \Shcms\Resources\Upload\Exception                Если перезаписи является ложной и файл уже существует
     * @throws \Shcms\Resources\Upload\Exception                Если ошибка при перемещении файла назначения
     */
    public function upload(\Shcms\Resources\Upload\FileInfoInterface $fileInfo)
    {
        $destinationFile = $this->directory . $fileInfo->getNameWithExtension();
        if ($this->overwrite === false && file_exists($destinationFile) === true) {
            throw new \Shcms\Resources\Upload\Exception('Файл уже существует', $fileInfo);
        }

        if ($this->moveUploadedFile($fileInfo->getPathname(), $destinationFile) === false) {
            throw new \Shcms\Resources\Upload\Exception('Файл не может быть перемещен в конечный пункт назначения.', $fileInfo);
        }
    }

    /**
     * Переместить загруженный файл
     *
     * Этот метод позволяет заглушки этот метод в модульные тесты, чтобы избежать
     * трудно зависимость от `move_uploaded_file` функции.
     *
     * @param  string $source      Исходный файл
     * @param  string $destination Файл назначения
     * @return bool
     */
    protected function moveUploadedFile($source, $destination)
    {
        return move_uploaded_file($source, $destination);
    }
}
