<?php
/**
 * Класс для работы с IP адресами
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */

namespace Shcms\Resources\IpUtils\Expression;

use Shcms\Resources\IpUtils\Address\AddressInterface;
use Shcms\Resources\IpUtils\Address\IPv4;
use Shcms\Resources\IpUtils\Address\IPv6;
use Shcms\Resources\IpUtils\Exception\InvalidExpressionException;

class Literal implements ExpressionInterface {
	protected $expression;

	public function __construct($expression) {
		$expression = strtolower(trim($expression));

		if (IPv4::isValid($expression)) {
			$ip = new IPv4($expression);
		}
		elseif (IPv6::isValid($expression)) {
			$ip = new IPv6($expression);
		}
		else {
			throw new InvalidExpressionException('Выражение должно быть либо действительным IPv4 или IPv6.');
		}

		$this->expression = $ip->getCompact();
	}

	/**
	 * Проверьте, соответствует ли выражение адрес
	 *
	 * @param  AddressInterface $address
	 * @return boolean
	 */
	public function matches(AddressInterface $address) {
		return $address->getCompact() === $this->expression;
	}
}
