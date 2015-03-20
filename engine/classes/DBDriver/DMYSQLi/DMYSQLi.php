<?php
/**
 * Библиотека для работы с Расширенной базой MYSQLi
 * 
 * 
 * @package      Classes
 * @author       Shamsik
 * @link         http://shcms.ru
 */
namespace DBDriver\DMYSQLi;

Class DMYSQLi
{

  public $query_count = 0;
  
  protected $exit_on_error = true;
  
  protected $echo_on_error = true;
  
  protected $css_mysql_box_border = '3px solid red';
  
  protected $css_mysql_box_bg = '#FFCCCC';
  /**
   * @var \mysqli
   */
  protected $link = false;
  
  protected $connected = false;
  /**
   * @var array
   */
  protected $mysqlDefaultTimeFunctions;
  
  private $logger_class_name;
  /**
   * @var string
   *
   * 'TRACE', 'DEBUG', 'INFO', 'WARN', 'ERROR', 'FATAL'
   */
  private $logger_level;
  /**
   * @var string
   */
  private $hostname = '';
  /**
   * @var string
   */
  private $username = '';
  /**
   * @var string
   */
  private $password = '';
  /**
   * @var string
   */
  private $database = '';
  /**
   * @var string
   */
  private $port = '3306';
  /**
   * @var string
   */
  private $charset = 'utf8';
  /**
   * @var string
   */
  private $socket = '';
  /**
   * @var array
   */
  private $_errors = array();
  /**
   * @var bool
   */
  private $session_to_db = false;
  /**
   * @var bool
   */
  private $_in_transaction = false;
  /**
   * __construct()
   *
   * @param string  $hostname
   * @param string  $username
   * @param string  $password
   * @param string  $database
   * @param int     $port
   * @param string  $charset
   * @param boolean $exit_on_error
   * @param boolean $echo_on_error
   * @param string  $logger_class_name
   * @param string  $logger_level 'TRACE', 'DEBUG', 'INFO', 'WARN', 'ERROR', 'FATAL'
   * @param boolean $session_to_db
   */
  private function __construct($hostname, $username, $password, $database, $port, $charset, $exit_on_error, $echo_on_error, $logger_class_name, $logger_level, $session_to_db)
  {
    $this->connected = false;
    $this->_loadConfig($hostname, $username, $password, $database, $port, $charset, $exit_on_error, $echo_on_error, $logger_class_name, $logger_level, $session_to_db);
    if ($this->connect()) {
      $this->set_charset($this->charset);
    }
    $this->mysqlDefaultTimeFunctions = array(
      //Возвращает текущую дату
      'CURDATE()',
      // CURRENT_DATE	| Synonyms for CURDATE()
      'CURRENT_DATE()',
      // CURRENT_TIME	| Synonyms for CURTIME()
      'CURRENT_TIME()',
      // CURRENT_TIMESTAMP | Synonyms for NOW()
      'CURRENT_TIMESTAMP()',
      //Возвращает текущее время
      'CURTIME()',
      // Synonym for NOW()
      'LOCALTIME()',
      // Synonym for NOW()
      'LOCALTIMESTAMP()',
      //Возвращает текущую дату и время
      'NOW()',
      //Возвращает время, в которое функция выполняется
      'SYSDATE()',
      //Возвращает UNIX timestamp
      'UNIX_TIMESTAMP()',
      //Возвращает текущую дату UTC
      'UTC_DATE()',
      //Возвращает текущее время UTC
      'UTC_TIME()',
      //Возвращает текущую дату и время UTC
      'UTC_TIMESTAMP()'
    );
  }
  /**
   * load the config
   *
   * @param $hostname
   * @param $username
   * @param $password
   * @param $database
   * @param $port
   * @param $charset
   * @param $exit_on_error
   * @param $echo_on_error
   * @param $logger_class_name
   * @param $logger_level
   * @param $session_to_db
   *
   * @return bool
   */
  private function _loadConfig($hostname, $username, $password, $database, $port, $charset, $exit_on_error, $echo_on_error, $logger_class_name, $logger_level, $session_to_db)
  {
    $this->hostname = $hostname;
    $this->username = $username;
    $this->password = $password;
    $this->database = $database;
    if ($charset) {
      $this->charset = $charset;
    }
    if ($port) {
      $this->port = (int)$port;
    }
    if ($exit_on_error === true || $exit_on_error === false) {
      $this->exit_on_error = $exit_on_error;
    }
    if ($echo_on_error === true || $echo_on_error === false) {
      $this->echo_on_error = $echo_on_error;
    }
    $this->logger_class_name = $logger_class_name;
    $this->logger_level = $logger_level;
    $this->session_to_db = $session_to_db;
    return $this->showConfigError();
  }
  /**
   * show config error and throw a exception
   */
  public function showConfigError()
  {
    if (
        !$this->hostname
        || !$this->username
        || !$this->database
        || (!$this->password && $this->password != '')
    ) {
      if (!$this->hostname) {
        throw new \Exception('no sql-hostname');
      }
      if (!$this->username) {
        throw new \Exception('no sql-username');
      }
      if (!$this->database) {
        throw new \Exception('no sql-database');
      }
      if (!$this->password) {
        throw new \Exception('no sql-password');
      }
      return false;
    } else {
      return true;
    }
  }
  /**
   * connect
   *
   * @return boolean
   */
  public function connect()
  {
    if ($this->isReady()) {
      return true;
    }
    if (!$this->socket) {
      $this->socket = @ini_get('mysqli.default_socket');
    }
    if (!$this->port) {
      $this->port = @ini_get('mysqli.default_port');
    }
    $this->link = @mysqli_connect(
        $this->hostname,
        $this->username,
        $this->password,
        $this->database,
        $this->port
    );
    if (!$this->link) {
      $this->_displayError("Ошибка подключения к серверу MySQL: " . mysqli_connect_error(), true);
    } else {
      $this->connected = true;
    }
    return $this->isReady();
  }
  /**
   * check if db-connection is ready
   *
   * @return boolean
   */
  public function isReady()
  {
    return ($this->connected) ? true : false;
  }
  /**
   * _displayError
   *
   * @param      $e
   * @param null $force_exception_after_error
   *
   * @throws \Exception
   */
  private function _displayError($e, $force_exception_after_error = null)
  {
    $this->logger(
        array(
            'error',
            '<strong>' . date("d. m. Y G:i:s") . ' (SQL-ошибка):</strong> ' . $e . '<br>'
        )
    );
    if ($this->checkForDev() === true) {
      $this->_errors[] = $e;
      if ($this->echo_on_error) {
        $box_border = $this->css_mysql_box_border;
        $box_bg = $this->css_mysql_box_bg;
        echo '
        <div class="OBJ-mysql-box" style="border:' . $box_border . '; background:' . $box_bg . '; padding:10px; margin:10px;">
          <b style="font-size:14px;">MYSQL Ошибка:</b>
          <code style="display:block;">
            ' . $e . '
          </code>
        </div>
        ';
      }
      if ($force_exception_after_error === true) {
        throw new \Exception($e);
      } else if ($force_exception_after_error === false) {
        // nothing
      } else if ($force_exception_after_error === null) {
        // default
        if ($this->exit_on_error === true) {
          throw new \Exception($e);
        }
      }
    }
  }
  /**
   * Обертка для "Logger" -класса
   *
   * @param $log array [method, text, type] e.g.: array('error', 'this is a error', 'sql')
   */
  private function logger($log)
  {
    $logMethod = '';
    $logText = '';
    $logType = '';
    $logClass = $this->logger_class_name;
    $tmpCount = count($log);
    if ($tmpCount == 2) {
      $logMethod = $log[0];
      $logText = $log[1];
    } else if ($tmpCount == 3) {
      $logMethod = $log[0];
      $logText = $log[1];
      $logType = $log[2];
    }
    if ($logClass && class_exists($logClass)) {
      if ($logMethod && method_exists($logClass, $logMethod)) {
        $logClass::$logMethod($logText, $logType);
      }
    }
  }

  /**
   * Проверить застройщика
   *
   * @return bool
   */
  private function checkForDev()
  {
    $return = false;
    if (function_exists('checkForDev')) {
      $return = checkForDev();
    } else {
      //Для тестирования с DEV-адрес
      $noDev = isset($_GET['noDev']) ? (int)$_GET['noDev'] : 0;
      $remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
      if
      (
          $noDev != 1
          &&
          (
              $remoteAddr == '127.0.0.1'
              || $remoteAddr == '::1'
              || PHP_SAPI == 'cli'
          )
      ) {
        $return = true;
      }
    }
    return $return;
  }
  /**
   * Кодировка
   *
   * @param string $charset
   */
  public function set_charset($charset)
  {
    $this->charset = $charset;
    mysqli_set_charset($this->link, $charset);
    mysqli_query($this->link, "SET NAMES " . $charset);
  }
  /**
   * getInstance() - Подключение К базе данных
   *
   * @param string $hostname
   * @param string $username
   * @param string $password
   * @param string $database
   * @param int|string $port
   * @param string $charset
   * @param bool|string $exit_on_error
   * @param bool|string $echo_on_error
   * @param string $logger_class_name
   * @param string $logger_level
   * @param bool|string $session_to_db
   *
   */
  public static function getInstance($hostname = '', $username = '', $password = '', $database = '', $port = '', $charset = '', $exit_on_error = '', $echo_on_error = '', $logger_class_name = '', $logger_level = '', $session_to_db = '')
  {
    /**
     * @var $instance
     */
    static $instance;
    /**
     * @var $firstInstance
     */
    static $firstInstance;
    
    if ($hostname . $username . $password . $database . $port . $charset == '') {
      if (null !== $firstInstance) {
        return $firstInstance;
      }
    }
    $connection = md5($hostname . $username . $password . $database . $port . $charset);
    if (null === $instance[$connection]) {
      $instance[$connection] = new self($hostname, $username, $password, $database, $port, $charset, $exit_on_error, $echo_on_error, $logger_class_name, $logger_level, $session_to_db);
      if (null === $firstInstance) {
        $firstInstance = $instance[$connection];
      }
    }
    return $instance[$connection];
  }
  
  /**
   * Выполнить SQL-запрос и
   * Возвращает результат-массив для Select-отчетности
   *
   * @param $query
   *
   * @return array|bool|int|\DBDriver\DMysqli\DResult
   * @deprecated
   * @throws \Exception
   */
  public static function qry($query)
  {
    $db = \DBDriver\DMYSQLi\DMYSQLi::getInstance();
    $args = func_get_args();
    $query = array_shift($args);
    $query = str_replace("?", "%s", $query);
    $args = array_map(
        array(
            $db,
            'escape'
        ), $args
    );
    array_unshift($args, $query);
    $query = call_user_func_array('sprintf', $args);
    $result = $db->query($query);
    if ($result instanceof Result) {
      $return = $result->fetchAllArray();
    } else {
      $return = $result;
    }
    if ($return || is_array($return)) {
      return $return;
    } else {
      return false;
    }
  }
  
  /**
   * __wakeup
   *
   * @return void
   */
  public function __wakeup()
  {
    $this->reconnect();
  }
  
  /**
   * Reconnect
   *
   * @param bool $checkViaPing
   *
   * @return bool
   */
  public function reconnect($checkViaPing = false)
  {
    $ping = false;
    if ($checkViaPing === true) {
      $ping = $this->ping();
    }
    if ($ping === false) {
      $this->connected = false;
      $this->connect();
    }
    return $this->isReady();
  }
  
  /**
   * Ping MYSQLi
   *
   * @return boolean
   */
  public function ping()
  {
    return mysqli_ping($this->link);
  }
  
  /**
   * Получить имена всех таблиц
   *
   * @return array
   */
  public function getAllTables()
  {
    $query = "SHOW TABLES";
    $result = $this->query($query);
    return $result->fetchAllArray();
  }
  
  /**
   * Запустить SQL-запрос
   *
   * @param string $sql строка SQL-запрос
   *
   * @param array|boolean $params  "Array" из SQL-запроса параметров
   *                               "false" если вам не нужно какой-либо параметр
   *
   *
   * @throws \Exception
   */
  public function query($sql = '', $params = false)
  {
    static $reconnectCounter;
    
    if (!$this->isReady()) {
      return false;
    }
    
    if (!$sql || strlen($sql) == 0) {
      $this->_displayError('Can\'t execute an empty Query', false);
      return false;
    }
    
    if ($params !== false && is_array($params)) {
      $sql = $this->_parseQueryParams($sql, $params);
    }

      
    $cache = false;
      
    $this->query_count++;
    $query_start_time = microtime(true);
    $result = mysqli_query($this->link, $sql);
    $query_duration = microtime(true) - $query_start_time;
    $this->_logQuery($sql, $query_duration, (int)$this->affected_rows());
    
    if ($result instanceof \mysqli_result && $result !== null) {
      //Возвращение результат запроса объект
      $return = new Result($sql, $result);

      return $return;
      
    } else {
        //Является запрос успешной
        if ($result === true) {
            if (preg_match('/^\s*"?(INSERT|UPDATE|DELETE|REPLACE)\s+/i', $sql)) {
            // Это "INSERT" || "REPLACE"
            if ($this->insert_id() > 0) {
                return (int)$this->insert_id();
            }
            // Это "UPDATE" || "DELETE"
            if ($this->affected_rows() > 0) {
                return (int)$this->affected_rows();
            }
            }
            
        return true;
        
        } else {  
        $errorMsg = mysqli_error($this->link);
        if ($errorMsg == 'DB server has gone away' || $errorMsg == 'MySQL server has gone away') {
          //выход, если у нас есть более чем 3 "сервер БД ушел" -errors
          if ($reconnectCounter > 3) {
            $this->mailToAdmin('SQL-Fatal-Error', $errorMsg . ":\n<br />" . $sql, 5);
            throw new \Exception($errorMsg);
          } else {
            $this->mailToAdmin('Ошибка SQL', $errorMsg . ":\n<br />" . $sql);
            // подключите
            $reconnectCounter++;
            $this->reconnect(true);
            // Re перспективе текущий запрос
            $this->query($sql, $params);
          }
        } else {
          $this->mailToAdmin('SQL-Warning', $errorMsg . ":\n<br />" . $sql);
          //Этот запрос вернул ошибку, мы должны показать, что (только для разработчика)
          $this->_displayError($errorMsg . ' | ' . $sql);
        }
      }
    }
    return false;
  }
  
  /**
   * _parseQueryParams
   *
   * @param string $sql
   * @param array  $params
   *
   * @return string
   */
  private function _parseQueryParams($sql, $params)
  {
    //Есть что-нибудь разобрать?
    if (strpos($sql, '?') === false) {
      return $sql;
    }
    //преобразовать в массив
    if (!is_array($params)) {
      $params = array($params);
    }
    $parse_key = md5(uniqid(time(), true));
    $parsed_sql = str_replace('?', $parse_key, $sql);
    $k = 0;
    while (strpos($parsed_sql, $parse_key) > 0) {
      $value = $this->secure($params[$k]);
      $parsed_sql = preg_replace("/$parse_key/", $value, $parsed_sql, 1);
      $k++;
    }
    return $parsed_sql;
  }
  /**
   * Secure
   *
   * @param mixed $var
   *
   * @return string | null
   */
  public function secure($var)
  {
    if (is_string($var)) {
      if (!in_array($var, $this->mysqlDefaultTimeFunctions)) {
        $var = "'" . $this->escape(trim($var)) . "'";
      }
    } else if (is_int($var)) {
      $var = intval((int)$var);
    } else if (is_float($var)) {
      $var = "'" . floatval(str_replace(',', '.', $var)) . "'";
    } else if (is_bool($var)) {
      $var = (int)$var;
    } else if (is_array($var)) {
      $var = null;
    } else if (($var instanceof \DateTime)) {
      try {
        $var = "'" . $this->escape($var->format('Y-m-d h:m:i'), false, false) . "'";
      } catch (\Exception $e) {
        $var = null;
      }
    }
    else {
      $var = "'" . $this->escape(trim($var)) . "'";
    }
    return $var;
  }
  
  /**
   * MYSQLi REAL ESCAPE STRING
   *
   * @param string $str
   * @param bool   $stripe_non_utf8
   * @param bool   $html_entity_decode
   *
   * @return array|bool|float|int|string
   */
  public function escape($str = '', $stripe_non_utf8 = true, $html_entity_decode = true)
  {
    if (is_int($str) || is_bool($str)) {
      return intval((int)$str);
    } else if (is_float($str)) {
      return floatval(str_replace(',', '.', $str));
    } else if (is_array($str)) {
      foreach ($str as $key => $value) {
        $str[$this->escape($key, $stripe_non_utf8, $html_entity_decode)] = $this->escape($value, $stripe_non_utf8, $html_entity_decode);
      }
      return (array)$str;
    }
    if (is_string($str)) {
      if ($stripe_non_utf8 === true) {
        $str = utf8_clean($str);
      }
      if ($html_entity_decode === true) {
        $str = html_entity_decode($str);
      }
      $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
      $str = mysqli_real_escape_string($this->getLink(), $str);
      return (string)$str;
    } else {
      return false;
    }
  }
  
  /**
   * getLink
   *
   * @return \mysqli
   */
  public function getLink()
  {
    return $this->link;
  }
  
  /**
   * _logQuery
   *
   * @param $sql
   * @param $duration
   * @param $results
   *
   * @return bool
   */
  private function _logQuery($sql, $duration, $results)
  {
    $logLevelUse = strtolower($this->logger_level);
    if ($logLevelUse != 'trace' && $logLevelUse != 'debug') {
      return false;
    }
    // init
    $file = '';
    $line = '';
    $referrer = debug_backtrace();
    foreach ($referrer as $key => $ref) {
      if ($ref['function'] == '_logQuery') {
        $file = $referrer[$key + 1]['file'];
        $line = $referrer[$key + 1]['line'];
      }
      if ($ref['function'] == 'execSQL') {
        $file = $referrer[$key]['file'];
        $line = $referrer[$key]['line'];
        break;
      }
    }
    $info = 'time => ' . round($duration, 5) . ' - ' . 'results => ' . $results . ' - ' . 'SQL => ' . htmlentities($sql);
    $this->logger(
        array(
            'debug',
            '<strong>' . date("d. m. Y G:i:s") . ' (' . $file . ' line: ' . $line . '):</strong> ' . $info . '<br>',
            'sql'
        )
    );
    return true;
  }
  
	
  /**
   * Возвращает число затронутых прошлой операцией рядов
   * mysqli_affected_rows()
   * 
   * @return string
   */   
  public function affected_rows()
  {
    return mysqli_affected_rows($this->link);
  }
  
	
  /**    
   * Возвращает идентификатор, сгенерированный при последнем INSERT-запросе 
   * mysqli_insert_id()
   *
   * @return string
   */ 	
  public function insert_id()
  {
    return mysqli_insert_id($this->link);
  }
  
  /**
   * Написать об ошибке почты администратора / разработчика
   *
   * @param     $subject
   * @param     $htmlBody
   * @param int $priority
   */
  private function mailToAdmin($subject, $htmlBody, $priority = 3)
  {
    if (function_exists('mailToAdmin')) {
      mailToAdmin($subject, $htmlBody, $priority);
    } else {
      if ($priority == 3) {
        $this->logger(
            array(
                'debug',
                $subject . ' | ' . $htmlBody
            )
        );
      } else if ($priority > 3) {
        $this->logger(
            array(
                'error',
                $subject . ' | ' . $htmlBody
            )
        );
      } else if ($priority < 3) {
        $this->logger(
            array(
                'info',
                $subject . ' | ' . $htmlBody
            )
        );
      }
    }
  }
  /**
   * запустить SQL-Multi-запрос
   *
   * @param string $sql
   *
   * @return bool
   * @throws \Exception
   */
  public function multi_query($sql)
  {
    static $reconnectCounterMulti;
    if (!$this->isReady()) {
      return false;
    }
    if (strlen($sql) == 0) {
      $this->_displayError('Невозможно выполнить пустой запрос', false);
      return false;
    }
    $query_start_time = microtime(true);
    $resultTmp = mysqli_multi_query($this->link, $sql);
    $query_duration = microtime(true) - $query_start_time;
    $this->_logQuery($sql, $query_duration, 0);
    $result = array();
    if ($resultTmp) {
      do {
        $resultTmpInner = mysqli_store_result($this->link);
        if (is_object($result) && $result !== null) {
          $result[] = new Result($sql, $resultTmpInner);
        } else {
          $errorMsg = mysqli_error($this->link);
          //является запрос успешной
          if ($resultTmpInner === true || !$errorMsg) {
            $result[] = true;
          } else {
            if ($errorMsg == 'DB server has gone away' || $errorMsg == 'MySQL server has gone away') {
              // выход, если у нас есть более чем 3 "сервер БД ушел" -errors
              if ($reconnectCounterMulti > 3) {
                $this->mailToAdmin('SQL-Fatal-Error', $errorMsg . ":\n<br />" . $sql, 5);
                throw new \Exception($errorMsg);
              } else {
                $this->mailToAdmin('Ошибка SQL', $errorMsg . ":\n<br />" . $sql);
                // подключите
                $reconnectCounterMulti++;
                $this->reconnect(true);
              }
            } else {
              $this->mailToAdmin('SQL Предупреждение', $errorMsg . ":\n<br />" . $sql);
              // Этот запрос вернул ошибку, мы должны показать, что (только для разработчика) !!!
              $this->_displayError($errorMsg . ' | ' . $sql);
            }
          }
        }
        //бесплатно MEM
        if ($resultTmpInner instanceof \mysqli_result) {
          mysqli_free_result($resultTmpInner);
        }
      }
      while (mysqli_more_results($this->link) === true ? mysqli_next_result($this->link) : false);
    } else {
      $errorMsg = mysqli_error($this->link);
      if ($this->checkForDev() === true) {
        echo "Информация: может быть, у вас есть, чтобы увеличить ваш 'max_allowed_packet = 30M' в конфигурации: 'my.conf' \n<br />";
        echo "Ошибка:" . $errorMsg;
      }
      $this->mailToAdmin('Ошибка SQL в mysqli_multi_query', $errorMsg . ":\n<br />" . $sql);
    }
    return false;
  }
  /**
   * alias for "beginTransaction()"
   */
  public function startTransaction()
  {
    $this->beginTransaction();
  }
  /**
   * Начинает транзакцию, путем включения и выключения Автоматическая фиксация
   *
   * @return true or false с указанием успех сделки
   */
  public function beginTransaction()
  {
    if ($this->inTransaction() === true) {
      $this->_displayError("Ошибка MySQL сервер уже в сделки!", true);
      return false;
    } else if (mysqli_connect_errno($this->link)) {
      $this->_displayError("Ошибка подключения к серверу MySQL:" . mysqli_connect_error(), true);
      return false;
    } else {
      $this->_in_transaction = true;
      mysqli_autocommit($this->link, false);
      return true;
    }
  }
  /**
   * Проверьте, если в сделке
   *
   * @return boolean
   */
  public function inTransaction()
  {
    return $this->_in_transaction;
  }
  /**
   * откат в сделке
   */
  public function rollback() {
    // init
    $return = false;
    if ($this->_in_transaction === true) {
      $return = mysqli_rollback($this->link);
      mysqli_autocommit($this->link, true);
      $this->_in_transaction = false;
    }
    return $return;
  }
  /**
   * Завершает транзакцию и совершает при отсутствии ошибок, то не заканчивается AutoCommit
   *
   * @return true or false указывающий на успешное сделок
   */
  public function endTransaction()
  {
    if (!$this->errors()) {
      mysqli_commit($this->link);
      $return = true;
    } else {
      $this->rollback();
      $return = false;
    }
    mysqli_autocommit($this->link, true);
    $this->_in_transaction = false;
    return $return;
  }
  /**
   * получить все ошибки
   *
   * @return array false on error
   */
  public function errors()
  {
    return count($this->_errors) > 0 ? $this->_errors : false;
  }
  /**
   * Вставить В таблицу
   *
   * @param string $table
   * @param array  $data
   *
   * @return bool|int|Result
   */
  public function insert($table, $data = array())
  {
    $table = trim($table);
    if (strlen($table) == 0) {
      $this->_displayError("Неправильное имя таблицыыыыы");
      return false;
    }
    if (count($data) == 0) {
      $this->_displayError("пустые данные для вставки");
      return false;
    }
    $SET = $this->_parseArrayPair($data);
    $sql = "INSERT INTO " . $this->quote_string($table) . " SET $SET;";
    return $this->query($sql);
  }
  /**
   * Анализирует массивы с парами значений и генерирует SQL, чтобы использовать в запросах
   *
   * @param array  $arrayPair
   * @param string $glue это сепаратор
   *
   * @return string
   */
  private function _parseArrayPair($arrayPair, $glue = ',')
  {
    // init
    $sql = '';
    $pairs = array();
    if (!empty($arrayPair)) {
      foreach ($arrayPair as $_key => $_value) {
        $_connector = '=';
        $_key_upper = strtoupper($_key);
        if (strpos($_key_upper, ' NOT') !== false) {
          $_connector = 'NOT';
        }
        if (strpos($_key_upper, ' IS') !== false) {
          $_connector = 'IS';
        }
        if (strpos($_key_upper, ' IS NOT') !== false) {
          $_connector = 'IS NOT';
        }
        if (strpos($_key_upper, " IN") !== false) {
          $_connector = 'IN';
        }
        if (strpos($_key_upper, " NOT IN") !== false) {
          $_connector = 'NOT IN';
        }
        if (strpos($_key_upper, ' BETWEEN') !== false) {
          $_connector = 'BETWEEN';
        }
        if (strpos($_key_upper, ' NOT BETWEEN') !== false) {
          $_connector = 'NOT BETWEEN';
        }
        if (strpos($_key_upper, ' EXISTS') !== false) {
          $_connector = 'EXISTS';
        }
        if (strpos($_key_upper, ' NOT EXISTS') !== false) {
          $_connector = 'NOT EXISTS';
        }
        if (strpos($_key_upper, ' LIKE') !== false) {
          $_connector = 'LIKE';
        }
        if (strpos($_key_upper, ' NOT LIKE') !== false) {
          $_connector = 'NOT LIKE';
        }
        if (strpos($_key_upper, ' >') !== false && strpos($_key_upper, ' =') === false) {
          $_connector = ">";
        }
        if (strpos($_key_upper, ' <') !== false && strpos($_key_upper, ' =') === false) {
          $_connector = "<";
        }
        if (strpos($_key_upper, ' >=') !== false) {
          $_connector = '>=';
        }
        if (strpos($_key_upper, ' <=') !== false) {
          $_connector = '<=';
        }
        if (strpos($_key_upper, ' <>') !== false) {
          $_connector = "<>";
        }
        $pairs[] = " " . $this->quote_string(trim(str_ireplace($_connector, '', $_key))) . " " . $_connector . " " . $this->secure($_value) . " \n";
      }
      $sql = implode($glue, $pairs);
    }
    return $sql;
  }
  /**
   * Цитата && Побег напримерИмя таблицы строка
   *
   * @param string $str
   *
   * @return string
   */
  public function quote_string($str)
  {
    return "`" . $this->escape($str, false, false) . "`";
  }
  /**
   * заменять
   *
   * @param string $table
   * @param array  $data
   *
   * @return bool|int|Result
   */
  public function replace($table, $data = array())
  {
    $table = trim($table);
    if (strlen($table) == 0) {
      $this->_displayError("Неправильное имя таблицы");
      return false;
    }
    if (count($data) == 0) {
      $this->_displayError("пустые данные для ЗАМЕНЫ");
      return false;
    }
    // extracting column names
    $columns = array_keys($data);
    foreach ($columns as $k => $_key) {
      $columns[$k] = $this->quote_string($_key);
    }
    $columns = implode(",", $columns);
    // extracting values
    foreach ($data as $k => $_value) {
      $data[$k] = $this->secure($_value);
    }
    $values = implode(",", $data);
    $sql = "REPLACE INTO " . $this->quote_string($table) . " ($columns) VALUES ($values);";
    return $this->query($sql);
  }
  /**
   * Обновление Данных
   *
   * @param string       $table
   * @param array        $data
   * @param array|string $where
   *
   * @return bool|int|Result
   */
  public function update($table, $data = array(), $where = '1=1')
  {
    $table = trim($table);
    if (strlen($table) == 0) {
      $this->_displayError("Неправильное имя таблицы");
      return false;
    }
    if (count($data) == 0) {
      $this->_displayError("Пустые данные для Обновление");
      return false;
    }
    $SET = $this->_parseArrayPair($data);
    if (is_string($where)) {
      $WHERE = $this->escape($where, false, false);
    } else if (is_array($where)) {
      $WHERE = $this->_parseArrayPair($where, "AND");
    } else {
      $WHERE = '';
    }
    $sql = "UPDATE " . $this->quote_string($table) . " SET $SET WHERE ($WHERE);";
    return $this->query($sql);
  }
  /**
   * Удаление Данных
   *
   * @param string       $table
   * @param string|array $where
   *
   * @return bool|int|Result
   */
  
  public function delete($table, $where)
  {
    $table = trim($table);
    if (strlen($table) == 0) {
     $this->_displayError("Неправильное имя таблицы");
      return false;
    }
    if (is_string($where)) {
      $WHERE = $this->escape($where, false, false);
    } else if (is_array($where)) {
      $WHERE = $this->_parseArrayPair($where, "AND");
    } else {
      $WHERE = '';
    }
    $sql = "DELETE FROM " . $this->quote_string($table) . " WHERE ($WHERE);";
    return $this->query($sql);
  }
  
  /**
   * Вывод из таблицы
   *
   * @param string       $table
   * @param string|array $where
   *
   * @return bool|int|Result
   */
  
  function select($table, $where = '1=1')
  {
    if (strlen($table) == 0) {
      $this->_displayError("Неправильное имя таблицы");
      return false;
    }
    if (is_string($where)) {
      $WHERE = $this->escape($where, false, false);
    } else if (is_array($where)) {
      $WHERE = $this->_parseArrayPair($where, 'AND');
    } else {
      $WHERE = '';
    }
    $sql = "SELECT * FROM " . $this->quote_string($table) . " WHERE ($WHERE);";
    return $this->query($sql);
  }
  
  /**
   * Получаем последнюю Ошибку
   *
   * @return string false on error
   */
  public function lastError()
  {
    return count($this->_errors) > 0 ? end($this->_errors) : false;
  }
  
  /**
   * __destruct
   *
   */
  function __destruct()
  {
    // close the connection only if we don't save PHP-SESSION's in DB
    if ($this->session_to_db === false) {
      $this->close();
    }
    return;
  }
  
  /**
   * Закрываем Соединение
   */
  public function close()
  {
    $this->connected = false;
    if ($this->link) {
      mysqli_close($this->link);
    }
  }
  /**
   * Предотвратить экземпляр из клонирования
   *
   * @return void
   */
  private function __clone()
  {
      //return false
  }
  
}