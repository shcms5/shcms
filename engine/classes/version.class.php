<?php 
/**
 * Класс для Вывода Версии SHCMS Engine
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
final class version {
    
	/** @ var string Название продукта.  */
    public $product = 'SHCMS Engine!';

	/** @ var string Версия продукта.  */
	public $release = '5.7';
	
	/** @ var string Версия техническое обслуживание.  */
	public $dev_level = '5';
	
	/** @ var string Статус развития.  */
	public $dev_status = 'Стабильная версия';
	
	/** @ var string Номер сборки.  */
	public $build = '139';
	
	/** @var  string  Кодовое слово. */
	public $codename = 'Shamsik_SHCMS';

	/** @var  string  Дата выхода. */
    public $reldate = '20.03.2015';
    
	/** @var  string  Время выхода. */
	public $reltime = '01.00';

    /** @var string Псевдоним разработчика CMS системы */
    public $developer = 'Shamsik';
	
    /** @var string Имя и Фамилия  */
    public $name_surname = 'Шамсик Сердеров';
	
	/** @var  string  Уведомление об авторских правах. */
	public $copyright = 'Copyright (C) 2014-2015 - SHCMS Engine, Inc. Все права защищены.';

	/** @var  string  Текст ссылки. */
    public $url = '<a href="http://www.shcms.ru">SHCMS Engine!</a>';

    /** 
     * Сравнивает два "PHP стандартизированы" номер версии против текущей версии SHCMS Engine. 
     * 
     * @param string $minimum Минимальная версия SHCMS Engine, который совместим. 
     * 
     * @return BOOL Правда, если версия совместима. 
     * 
     */
    public function isCompatible($minimum)
    {
	return version_compare(PHP_VERSION, $minimum, 'ge');
    }

    /** 
     * Метод, чтобы получить версию файл справки. 
     * 
     * @return string Версия суффикс файлы справки. 
     *   
     */	
    public function HelpVersion()
    {
	return '.' . str_replace('.', '', $this->release);
    }
	
    /** 
     * Получает "PHP стандартизированы" строку версии для текущего SHCMS Engine. 
     * 
     * @return string Версия строка. 
     */
    public function ShortVersion()
    {
	return $this->release . '.' . $this->dev_level;
    }	
    
    /** 
     * Получает строку авторских прав текущего SHCMS Engine. 
     * 
     * @return string Версия строка. 
     */
    public function CopyrightVersion() 
    {
	return $this->copyright;
    }
	
    /** 
     * Получает строку Разрабочика текущей системы SHCMS Engine. 
     * 
     * @return string Полное строка версии. 
     */	
    public function AuthorVersion() {
	return $this->name_surname.' [<font color="green">'.$this->developer.'</font>]';
    }
	
    /** 
     * Получает "PHP стандартизированы" строку номера сборки для текущего SHCMS Engine.
     * 
     * @return string Версия строка. 
     */
    public function BuildVersion() 
    {
	return $this->build;    
    }
	
    /** 
     * Получает строку статуса развития для текущего SHCMS Engine.
     * 
     * @return string Версия строка. 
     */
    public function StatusVersion() 
    {
	return $this->dev_status;    
    }	
	
    /** 
     * Получает строку версии для текущего SHCMS Engine со всей информацией релиза. 
     * 
     * @return string Полное строка версии. 
     */
    public function LongVersion()
    {
	    return $this->product . ' ' . $this->release . '.' . $this->dev_level . ' '
		   . $this->dev_status . ' [ ' . $this->codename . ' ] ' . $this->reldate . ' '
		   . $this->reltime.'';
    }	

     /** 
      * Возвращает агента пользователя. 
      * 
      * @param string "$component" Имя компонента. 
      * @param BOOL "$mask" Маска как Mozilla / 5,0 или нет. 
      * @param BOOL "$add_version" Добавить версию впоследствии компонента. 
      * 
      * @return string Агент пользователя. 
      */
    public function getUserAgent($component = null, $mask = false, $add_version = true)
    {
	if ($component === null)
	{
		$component = 'Framework';
	}

	if ($add_version)
	{
		$component .= '/' . $this->release;
	}

	//Если маскируется вид, чтобы выглядеть как Mozilla 5.0, но все еще идентифицируем себя.
	if ($mask)
	{
		return 'Mozilla/5.0 ' . $this->product . '/' . $this->release . '.' . $this->dev_level . ($component ? ' ' . $component : '');
	}
	else
	{
		return $this->product . '/' . $this->release . '.' . $this->dev_level . ($component ? ' ' . $component : '');
	}
    }	
	
}


$sversion = new version;