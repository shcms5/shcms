<?php
namespace Shcms\Component\Data\Filesize;

class Filesize extends SizeAll
{
    public function __construct($file) 
    {
        if (! file_exists($file)) {
            throw new FileNotFoundException("Файл ($file) не найден");
        }
        parent::__construct(filesize($file));
    }

    public function from($unit) 
    {
        throw new \RuntimeException("SizeAll::из метода не поддерживается для файлов");
    }
}
