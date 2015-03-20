<?php
/**
 * Класс для работы с IP адресами
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */

namespace Shcms\Resources\IpUtils\Address;

use Shcms\Resources\IpUtils\Expression\Subnet;
use Shcms\Resources\IpUtils\Expression\ExpressionInterface;

class IPv4 implements AddressInterface {
	protected $address;

	public function __construct($address) {
		if (!self::isValid($address)) {
			throw new \UnexpectedValueException('"'.$address.'" нет действующий адрес IPv4..');
		}

		$this->address = $address;
	}

	/**
	 * @param  string $addr
	 * @return boolean
	 */
	public static function isValid($address) {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
	}

	/**
	 * @param  int $netmask
	 * @return boolean
	 */
	public static function isValidNetmask($netmask) {
		return $netmask >= 1 && $netmask <= 32;
	}

	/**
	 * @return IPv4
	 */
	public static function getLoopback() {
		return new self('127.0.0.1');
	}

	/**
	 * Получить полностью укомплектованную адрес
	 *
	 * @return string
	 */
	public function getExpanded() {
		return $this->address;
	}

	/**
	 * Получить представление компактный адрес
	 *
	 * @return string
	 */
	public function getCompact() {
		return $this->getExpanded();
	}

	/**
	 * Получить куски IP-специфические ([127,0,0,1])
	 *
	 * @return array
	 */
	public function getChunks() {
		return explode('.', $this->getExpanded());
	}

	/**
	 * Возвращает компактное представление
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getCompact();
	}

	/**
	 * Проверить точек IP на шлейф (локальный) устройство
	 *
	 * @return boolean
	 */
	public function isLoopback() {
		return $this->matches(new Subnet('127.0.0.0/8'));
	}

	/**
	 * Проверить IP внутри частной сети
	 *
	 * @return boolean
	 */
	public function isPrivate() {
		return
			$this->matches(new Subnet('10.0.0.0/8')) ||
			$this->matches(new Subnet('172.16.0.0/12')) ||
			$this->matches(new Subnet('192.168.0.0/16'))
		;
	}

	/**
	 * Проверить IP адрес многоадресного вещания
	 */
	public function isMulticast() {
		return $this->matches(new Subnet('224.0.0.0/4'));
	}

	/**
	 * Проверить IP является локальных адресов
	 *
	 * @return boolean
	 */
	public function isLinkLocal() {
		return $this->matches(new Subnet('169.254.1.0/24'));
	}

	/**
	 * Проверьте, соответствует ли адрес заданному шаблону / диапазон
	 *
	 * @param  ExpressionInterface $expression
	 * @return boolean
	 */
	public function matches(ExpressionInterface $expression) {
		return $expression->matches($this);
	}
}
