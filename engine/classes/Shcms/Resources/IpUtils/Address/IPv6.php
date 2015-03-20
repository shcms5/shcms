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

class IPv6 implements AddressInterface {
	protected $address;

	public function __construct($address) {
		if (!self::isValid($address)) {
			throw new \UnexpectedValueException('"'.$address.'" нет действующий адрес IPv6.');
		}

		$this->address = implode(':', array_map(function($b) {
			return sprintf('%04x', $b);
		}, unpack('n*', inet_pton($address))));
	}

	/**
	 * @param  string $addr
	 * @return boolean
	 */
	public static function isValid($address) {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
	}

	/**
	 * @param  int $netmask
	 * @return boolean
	 */
	public static function isValidNetmask($netmask) {
		return $netmask >= 1 && $netmask <= 128;
	}

	/**
	 * @return IPv6
	 */
	public static function getLoopback() {
		return new self('::1');
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
		return inet_ntop(inet_pton($this->address));
	}

	/**
	 * Получить куски IP-специфические([ff,0,0,0,12,2001,ff,....])
	 *
	 * @return array
	 */
	public function getChunks() {
		return array_map(function($c) {
			return ltrim($c, '0') ?: '0';
		}, explode(':', $this->getExpanded()));
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
		return $this->matches(new Subnet('::1/128'));
	}

	/**
	 * Проверить IP внутри частной сети
	 *
	 * @return boolean
	 */
	public function isPrivate() {
		return $this->matches(new Subnet('fc00::/7'));
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
