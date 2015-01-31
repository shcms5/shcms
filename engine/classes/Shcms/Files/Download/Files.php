<?php
/**
 * Класс для работы с файлами
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */
namespace Shcms\Files\Download;

class Files {
    
    /**
     * Аудио и Видио Плеера
     * 
     * @param $name  Название
     * @param $param Параметры вывода
     * @param $style Стили Плееров
     * @param $file  Файл проигрывания
     */
    public function player($name,$param,$style,$file) {
	    $player = '<object '.$param.'>';
		$player .= '<param name="allowFullScreen" value="true" />';
		$player .= '<param name="allowScriptAccess" value="always" />';
		$player .= '<param name="wmode" value="transparent" />';
		$player .= '<param name="movie" value="'.$name.'" />';
		$player .= '<param name="flashvars" value="st='.$style.'&amp;file='.$file.'" />';
		$player .= '<embed src="uppod.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" flashvars="st='.$style.'&amp;file='.$file.'" '.$param.'>';
		$player .= '</embed>';
		$player .= ' </object>';
		
	return $player;
    }
    
    /**
     * Функция скачивания файлов
     * 
     * @param $filename Имя файла
     * @param $name Название
     * @param @mimetype
     */
    public function downloadFile($filename, $name, $mimetype = 'application/octet-stream') 
    { 
        if (!file_exists($filename)) {
            die('Файл не найден');
        }        
            $from = $to = 0; 
            $cr = null; 
            if (isset($_SERVER['HTTP_RANGE'])) { 
                $range = substr($_SERVER['HTTP_RANGE'], strpos($_SERVER['HTTP_RANGE'], '=') + 1); 
                $from = strtok($range, '-'); 
                $to = strtok('/'); 
                if ($to > 0) 
                    ++$to; 
                if ($to) 
                    $to -= $from; 
                header('HTTP/1.1 206 Partial Content'); 
                $cr = 'Content-Range: bytes ' . $from . '-' . (($to) ? ($to . '/' . $to + 1) : 
                    filesize($filename)); 
            } else 
                header('HTTP/1.1 200 Ok'); 
            $etag = md5($filename); 
            $etag = substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 
                8); 
            header('ETag: "' . $etag . '"'); 
            header('Accept-Ranges: bytes'); 
            header('Content-Length: ' . (filesize($filename) - $to + $from)); 
            if ($cr) {
                header($cr);
            }
            header('Connection: close'); 
            header('Content-Type: ' . $mimetype); 
            header('Last-Modified: ' . gmdate('r', filemtime($filename))); 
            $f = fopen($filename, 'r'); 
            header('Content-Disposition: attachment; filename="' . $name . '";'); 
            if ($from) {
                fseek($f, $from, SEEK_SET); 
            }    
            if (!isset($to) or empty($to)) { 
                $size = filesize($filename) - $from; 
            } else { 
                $size = $to; 
            } 
            $downloaded = 0; 
            while (!feof($f) and !connection_status() and ($downloaded < $size)) { 
                echo fread($f, 512000); 
                $downloaded += 512000; 
                ob_flush(); 
                flush(); 
            } 
            fclose($f); 
    } 


	 public function AudioInfo() {

		// Initialize getID3 engine
		$this->getID3 = new getID3;
		$this->getID3->option_md5_data        = true;
		$this->getID3->option_md5_data_source = true;
		$this->getID3->encoding               = 'UTF-8';
	}




	/**
	* Extract information - only public function
	*
	* @access   public
	* @param    string  file    Audio file to extract info from.
	*/

	 public function Info($file) {

		// Analyze file
		$this->info = $this->getID3->analyze($file);

		// Exit here on error
		if (isset($this->info['error'])) {
			return array ('error' => $this->info['error']);
		}

		// Init wrapper object
		$this->result = array();
		$this->result['format_name']     = (isset($this->info['fileformat']) ? $this->info['fileformat'] : '').'/'.(isset($this->info['audio']['dataformat']) ? $this->info['audio']['dataformat'] : '').(isset($this->info['video']['dataformat']) ? '/'.$this->info['video']['dataformat'] : '');
		$this->result['encoder_version'] = (isset($this->info['audio']['encoder'])         ? $this->info['audio']['encoder']         : '');
		$this->result['encoder_options'] = (isset($this->info['audio']['encoder_options']) ? $this->info['audio']['encoder_options'] : '');
		$this->result['bitrate_mode']    = (isset($this->info['audio']['bitrate_mode'])    ? $this->info['audio']['bitrate_mode']    : '');
		$this->result['channels']        = (isset($this->info['audio']['channels'])        ? $this->info['audio']['channels']        : '');
		$this->result['sample_rate']     = (isset($this->info['audio']['sample_rate'])     ? $this->info['audio']['sample_rate']     : '');
		$this->result['bits_per_sample'] = (isset($this->info['audio']['bits_per_sample']) ? $this->info['audio']['bits_per_sample'] : '');
		$this->result['playing_time']    = (isset($this->info['playtime_seconds'])         ? $this->info['playtime_seconds']         : '');
		$this->result['avg_bit_rate']    = (isset($this->info['audio']['bitrate'])         ? $this->info['audio']['bitrate']         : '');
		$this->result['tags']            = (isset($this->info['tags'])                     ? $this->info['tags']                     : '');
		$this->result['comments']        = (isset($this->info['comments'])                 ? $this->info['comments']                 : '');
		$this->result['warning']         = (isset($this->info['warning'])                  ? $this->info['warning']                  : '');
		$this->result['md5']             = (isset($this->info['md5_data'])                 ? $this->info['md5_data']                 : '');

		// Post getID3() data handling based on file format
		$method = (isset($this->info['fileformat']) ? $this->info['fileformat'] : '').'Info';
		if ($method && method_exists($this, $method)) {
			$this->$method();
		}

		return $this->result;
	}
    
    
}
