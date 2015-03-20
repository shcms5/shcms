<?php
/**
 * Класс для работы с IP адресами
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */

namespace Shcms\Resources\IpUtils;

use Shcms\Resources\IpUtils\Address;
use Shcms\Resources\IpUtils\Expression;

class Factory {
	public static function getAddress($address) {
		if (strpos($address, ':') === false) {
			return new Address\IPv4($address);
		}

		return new Address\IPv6($address);
	}

	public static function getExpression($expr) {
		if (strpos($expr, '/') === false) {
			if (strpos($expr, '*') === false) {
				return new Expression\Literal($expr);
			}

			return new Expression\Pattern($expr);
		}

		return new Expression\Subnet($expr);
	}
}
