<?php
/*
 * FTP класса - взаимодействовать с удаленным FTP-сервер
 * 
 * @package      Classes
 * @author       Shamsik
 * @link         http://shcms.ru
 */ 
namespace Shcms\Component\Ftp;

class Ftp {

	/**
	 * удерживайте connction FTP
	 * @var integer
	 */
	private $conn;

	/**
	 * держит путь относительно корня сервера
	 * @var string
	 */
	private $basePath;
	
	/**
	 * открыть соединение FTP
	 * @param string $host адрес сервера
	 * @param string $user Имя пользователя
	 * @param string $pass пароль
	 * @param string $base общая папка, как правило, public_html или httpdocs
	 */
	public function __construct($host,$user,$pass,$base){

		//установить BasePath
		$this->basePath = $base.'/';	
		
		//открыть соединение
		$this->conn = ftp_connect($host);
		
		//войдите на сервер
		ftp_login($this->conn,$user,$pass);
	}
	
	/**
	 * закрыть соединение
	 */
	function close(){
		ftp_close($this->conn);
	}
		
	/**
	 * создать каталог на го удалённый FTP-сервер
	 * @param  string $dirToCreate Имя каталога, чтобы создать
	 */
	function makeDirectory($dirToCreate){
		 if(!file_exists($this->basePath.$dirToCreate)){
			ftp_mkdir($this->conn,$this->basePath.$dirToCreate);
		 }
	}

	/**
	 * удалить каталог с FTP-сервера
	 * @param  string $dir Папка для удаления
	 */
	function deleteDirectory($dir){
		ftp_rmdir($this->conn, $this->basePath.$dir);
	}
	
	/**
	 * Разрешение заданной папки
	 * @param  string $folderChmod имя папки
	 * @param  integer $permission значение разрешения
	 * @return string  успех сообщение
	 */
	function folderPermission($folderChmod,$permission){
		if (ftp_chmod($this->conn, $permission, $folderChmod) !== false){
			return "<p>$folderChmod CHMOD успешно ".$permission."</p>\n";
		}
	}
	
	/**
	 * загрузить файл для удаления FTP-сервер
	 * @param  string $remoteFile путь и имя файла для удаленного файла
	 * @param  string $localFile  локальный путь к файлу
	 * @return string сообщение
	 */
	function uploadFile($remoteFile,$localFile){
		
		if (ftp_put($this->conn,$this->basePath.$remoteFile,$localFile,FTP_ASCII)){
			return "<p>Успешно загружен $localFile для $remoteFile</p>\n";
		} else {
			return "<p>Были проблемы при загрузке $remoteFile</p>\n";
		}
	}

	/**
	 * удалить файл
	 * @param  string $file путь и имя файла
	 */
	function deleteFile($file){
		ftp_delete($this->conn, $this->basePath.$file);
	}
	
}