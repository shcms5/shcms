<?php

/**
 * Класс для работы с Электоронной почтой
 * 
 * @package Classes
 * @author Shamsik
 * @link http://shcms.ru
 */

class Mail {

  private $from;
  private $from_name = "";
  private $type = "text/html";
  private $encoding = "utf-8";
  private $notify = false;

  /**
   * Конструктор принимающий обратный e-mail адрес
   * 
   * @param $from
   */
  public function __construct($from) {
    $this->from = $from;
  }

  /**
   * Изменение обратного e-mail адреса
   * 
   * @param $from
   */
  public function setFrom($from) {
    $this->from = $from;
  }

  /**
   * Изменение имени в обратном адресе
   * 
   * @param $from_name
   */  
  public function setFromName($from_name) {
    $this->from_name = $from_name;
  }

  /**
   * Изменение типа содержимого письма
   * 
   * @param $type
   */    
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * Нужно ли запрашивать подтверждение письма
   * 
   * @param $notify
   */      
  public function setNotify($notify) {
    $this->notify = $notify;
  }

  /**
   * Изменение кодировки письма
   * 
   * @param $encoding
   */  
  public function setEncoding($encoding) {
    $this->encoding = $encoding;
  }

  /**
   * Метод отправки сообщений на почту
   * 
   * @param $to Кому
   * @param $subject От кого
   * @param $message Текст письма
   */  
  public function send($to, $subject, $message) {
    $from = "=?utf-8?B?".base64_encode($this->from_name)."?="." <".$this->from.">"; // Кодируем обратный адрес (во избежание проблем с кодировкой)
    $headers = "From: ".$from."\r\nReply-To: ".$from."\r\nContent-type: ".$this->type."; charset=".$this->encoding."\r\n"; // Устанавливаем необходимые заголовки письма
    if ($this->notify) $headers .= "Disposition-Notification-To: ".$this->from."\r\n"; // Добавляем запрос подтверждения получения письма, если требуется
    $subject = "=?utf-8?B?".base64_encode($subject)."?="; // Кодируем тему (во избежание проблем с кодировкой)
    return mail($to, $subject,$message, $headers); // Отправляем письмо и возвращаем результат
  }
  
  /**
   * Метод отправки сообщений на почту с HTML тэгами
   * 
   * @param $to Кому
   * @param $subject От кого
   * @param $message Текст письма
   */  
   public function send_html($to, $subject, $message) {
    $from = "=?utf-8?B?".base64_encode($this->from_name)."?="." <".$this->from.">"; // Кодируем обратный адрес (во избежание проблем с кодировкой)
    $headers = "From: ".$from."\r\nReply-To: ".$from."\r\nContent-type: ".$this->type."; charset=".$this->encoding."\r\n"; // Устанавливаем необходимые заголовки письма
    if ($this->notify) $headers .= "Disposition-Notification-To: ".$this->from."\r\n"; // Добавляем запрос подтверждения получения письма, если требуется
    $subject = "=?utf-8?B?".base64_encode($subject)."?="; // Кодируем тему (во избежание проблем с кодировкой)
    return mail($to, $subject,"<div style='background-color: #fcfcfc;margin: 4px;padding: 8px;word-wrap: break-word;border: 1px #D9D9D9 solid;border-radius: 4px;'>".$message.'</div>', $headers); // Отправляем письмо и возвращаем результат
  } 

}