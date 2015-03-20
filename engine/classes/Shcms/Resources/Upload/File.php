<?php
/**
 * Класс для работы с Загрузкой файлов
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */


namespace Shcms\Resources\Upload;

class File implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * Загрузить сообщения код ошибки
     * @var array
     */
    protected static $errorCodeMessages = [
        1 => 'Загруженный файл превышает upload_max_filesize директиву в php.ini',
        2 => 'Загруженный файл превышает MAX_FILE_SIZE директиву, которая была указана в HTML форме',
        3 => 'Загруженный файл был загружен только частично',
        4 => 'Файл не был загружен',
        6 => 'Пропавших без вести во временную папку',
        7 => 'Не удалось записать файл на диск',
        8 => 'Расширение PHP остановил загрузку файлов'
       ];

    /**
     * Делегат хранения
     * @var \Shcms\Resources\Upload\StorageInterface
     */
    protected $storage;

    /**
     * Информация о файле
     * @var array[\Shcms\Resources\Upload\FileInfoInterface]
     */
    protected $objects = array();

    /**
     * Проверки
     * @var array[\Shcms\Resources\Upload\ValidationInterface]
     */
    protected $validations = array();

    /**
     * Ошибки проверки
     * @var array[String]
     */
    protected $errors = array();

    /**
     * Перед проверкой обратного вызова
     * @var callable
     */
    protected $beforeValidationCallback;

    /**
     * После проверки обратного вызова
     * @var callable
     */
    protected $afterValidationCallback;

    /**
     * Перед загрузки обратного вызова
     * @var callable
     */
    protected $beforeUploadCallback;

    /**
     * После загрузки обратного вызова
     * @var callable
     */
    protected $afterUploadCallback;

    /**
     * Конструктор
     *
     * @param  string                    $key     The $_FILES[] ключ
     * @param  \Shcms\Resources\Upload\StorageInterface  $storage Экземпляр загрузки делегат
     * @throws \RuntimeException                  Если загрузка файлов отключены в файле php.ini
     * @throws \InvalidArgumentException          если $_FILES[] не содержит ключ
     */
    public function __construct($key, \Shcms\Resources\Upload\StorageInterface $storage)
    {
        // Проверьте, если загрузка файлов разрешено
        if (ini_get('file_uploads') == false) {
            throw new \RuntimeException('Загрузка файлов отключена в вашем файле php.ini');
        }

        // Проверьте, находится ли ключ
        if (isset($_FILES[$key]) === false) {
            throw new \InvalidArgumentException("Не можете найти загруженный файл (ы), идентифицированный ключом: $key");
        }

        // Соберите информацию о файле
        if (is_array($_FILES[$key]['tmp_name']) === true) {
            foreach ($_FILES[$key]['tmp_name'] as $index => $tmpName) {
                if ($_FILES[$key]['error'][$index] !== UPLOAD_ERR_OK) {
                    $this->errors[] = sprintf(
                        '%s: %s',
                        $_FILES[$key]['name'][$index],
                        static::$errorCodeMessages[$_FILES[$key]['error'][$index]]
                    );
                    continue;
                }

                $this->objects[] = \Shcms\Resources\Upload\FileInfo::createFromFactory(
                    $_FILES[$key]['tmp_name'][$index],
                    $_FILES[$key]['name'][$index]
                );
            }
        } else {
            if ($_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
                $this->errors[] = sprintf(
                    '%s: %s',
                    $_FILES[$key]['name'],
                    static::$errorCodeMessages[$_FILES[$key]['error']]
                );
            }

            $this->objects[] = \Shcms\Resources\Upload\FileInfo::createFromFactory(
                $_FILES[$key]['tmp_name'],
                $_FILES[$key]['name']
            );
        }

        $this->storage = $storage;
    }

    /********************************************************************************
     * Обратные вызовы
     *******************************************************************************/

    /**
     * Установите `beforeValidation` выкупу
     *
     * @param  callable                  $callable Should accept one `\Shcms\Resources\Upload\FileInfoInterface` аргумент
     * @return \Shcms\Resources\Upload\File                        себя
     * @throws \InvalidArgumentException           Если аргумент не Закрытие и доступен объект
     */
    public function beforeValidate($callable)
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new \InvalidArgumentException('Обратный звонок не Закрытие и доступен объект.');
        }
        $this->beforeValidation = $callable;

        return $this;
    }

    /**
     * Установите `afterValidation` выкупу
     *
     * @param  callable                  $callable Should accept one `\Shcms\Resources\Upload\FileInfoInterface` аргумент
     * @return \Shcms\Resources\Upload\File                        себя
     * @throws \InvalidArgumentException           Если аргумент не Закрытие и доступен объект
     */
    public function afterValidate($callable)
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new \InvalidArgumentException('Обратный звонок не Закрытие и доступен объект.');
        }
        $this->afterValidation = $callable;

        return $this;
    }

    /**
     * Установите `beforeUpload` выкупу
     *
     * @param  callable                  $callable Should accept one `\Shcms\Resources\Upload\FileInfoInterface` argument
     * @return \Shcms\Resources\Upload\File                        себя
     * @throws \InvalidArgumentException           Если аргумент не Закрытие и доступен объект
     */
    public function beforeUpload($callable)
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new \InvalidArgumentException('Обратный звонок не Закрытие и доступен объект.');
        }
        $this->beforeUpload = $callable;

        return $this;
    }

    /**
     * Установите `afterUpload` выкупу
     *
     * @param  callable                  $callable Should accept one `\Shcms\Resources\Upload\FileInfoInterface` argument
     * @return \Shcms\Resources\Upload\File                        себя
     * @throws \InvalidArgumentException           Если аргумент не Закрытие и доступен объект
     */
    public function afterUpload($callable)
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new \InvalidArgumentException('Обратный звонок не Закрытие и доступен объект.');
        }
        $this->afterUpload = $callable;

        return $this;
    }

    /**
     * Применить выкупу
     *
     * @param  string                    $callbackName
     * @param  \Shcms\Resources\Upload\FileInfoInterface $file
     * @return \Shcms\Resources\Upload\File              Self
     */
    protected function applyCallback($callbackName, \Shcms\Resources\Upload\FileInfoInterface $file)
    {
        if (in_array($callbackName, array('beforeValidation', 'afterValidation', 'beforeUpload', 'afterUpload')) === true) {
            if (isset($this->$callbackName) === true) {
                call_user_func_array($this->$callbackName, array($file));
            }
        }
    }

    /********************************************************************************
     * Проверка и обработка ошибок
     *******************************************************************************/

    /**
     * Добавить проверок файлов
     *
     * @param  array[\Shcms\Resources\Upload\ValidationInterface] $validations
     * @return \Shcms\Resources\Upload\File                       Self
     */
    public function addValidations(array $validations)
    {
        foreach ($validations as $validation) {
            $this->addValidation($validation);
        }

        return $this;
    }

    /**
     * Добавить валидацию
     *
     * @param  \Shcms\Resources\Upload\ValidationInterface $validation
     * @return \Shcms\Resources\Upload\File                Self
     */
    public function addValidation(\Shcms\Resources\Upload\ValidationInterface $validation)
    {
        $this->validations[] = $validation;

        return $this;
    }

    /**
     * Получить проверок файлов
     *
     * @return array[\Shcms\Resources\Upload\ValidationInterface]
     */
    public function getValidations()
    {
        return $this->validations;
    }

    /**
     * Это коллекция действительным и без ошибок?
     *
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->objects as $fileInfo) {
            // Перед проверкой обратного вызова
            $this->applyCallback('beforeValidation', $fileInfo);

            // Проверьте загружается файл
            if ($fileInfo->isUploadedFile() === false) {
                $this->errors[] = sprintf(
                    '%s: %s',
                    $fileInfo->getNameWithExtension(),
                    'Разве это не загруженный файл'
                );
                continue;
            }

            // Применить пользовательские проверок
            foreach ($this->validations as $validation) {
                try {
                    $validation->validate($fileInfo);
                } catch (\Shcms\Resources\Upload\Exception $e) {
                    $this->errors[] = sprintf(
                        '%s: %s',
                        $fileInfo->getNameWithExtension(),
                        $e->getMessage()
                    );
                }
            }

            // После проверки обратного вызова
            $this->applyCallback('afterValidation', $fileInfo);
        }

        return empty($this->errors);
    }

    /**
     * Получить ошибки проверки файла
     *
     * @return array[String]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /********************************************************************************
     * Вспомогательные методы
     *******************************************************************************/

    public function __call($name, $arguments)
    {
        $count = count($this->objects);
        $result = null;

        if ($count) {
            if ($count > 1) {
                $result = array();
                foreach ($this->objects as $object) {
                    $result[] = \call_user_func_array([$object, $name], $arguments);
                }
            } else {
                $result = \call_user_func_array([$this->objects[0], $name], $arguments);
            }
        }

        return $result;
    }

    /********************************************************************************
    * Загрузить
    *******************************************************************************/

    /**
     * Загрузить файл (делегированы объекта хранения)
     *
     * @return bool
     * @throws \Shcms\Resources\Upload\Exception Если проверка не пройдена
     * @throws \Shcms\Resources\Upload\Exception Если сбой отправки
     */
    public function upload()
    {
        if ($this->isValid() === false) {
            throw new \Shcms\Resources\Upload\Exception('Проверка файла не удалось');
        }

        foreach ($this->objects as $fileInfo) {
            $this->applyCallback('beforeUpload', $fileInfo);
            $this->storage->upload($fileInfo);
            $this->applyCallback('afterUpload', $fileInfo);
        }

        return true;
    }

    /********************************************************************************
     * Массив интерфейсом доступа
     *******************************************************************************/

    public function offsetExists($offset)
    {
        return isset($this->objects[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->objects[$offset]) ? $this->objects[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->objects[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->objects[$offset]);
    }

    /********************************************************************************
     * Итератор Совокупный интерфейс
     *******************************************************************************/

    public function getIterator()
    {
        return new \ArrayIterator($this->objects);
    }

    /********************************************************************************
     * Счетный Интерфейс
     *******************************************************************************/

    public function count()
    {
        return count($this->objects);
    }

    /********************************************************************************
    * Помощники
    *******************************************************************************/

    /**
     * Преобразование в читабельный размер файла (например, "10K" или "3М") в байтах
     *
     * @param  string $input
     * @return int
     */
    public static function humanReadableToBytes($input)
    {
        $number = (int)$input;
        $units = [
            'b' => 1,
            'k' => 1024,
            'm' => 1048576,
            'g' => 1073741824
            ];
        $unit = strtolower(substr($input, -1));
        if (isset($units[$unit])) {
            $number = $number * $units[$unit];
        }

        return $number;
    }
}
