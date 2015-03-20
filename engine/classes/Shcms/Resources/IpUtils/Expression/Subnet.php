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

class Subnet implements ExpressionInterface {
	protected $lower;
	protected $netmask;

	public function __construct($expression) {
		if (strpos($expression, '/') === false) {
			throw new InvalidExpressionException('Выражение Неверный подсети "'.$expression.'" данный.');
		}

		list($lower, $netmask) = \explode('/', $expression, 2);

		if (strpos($netmask, '.') !== false || strpos($netmask, ':') !== false) {
			throw new InvalidExpressionException('Сетевые маски не можете использовать IP-адрес формат("127.0.0.1/255.0.0.0").');
		}

		// проверить формат IP-первый

		if (IPv4::isValid($lower)) {
			$ip = new IPv4($lower);
		}
		elseif (IPv6::isValid($lower)) {
			$ip = new IPv6($lower);
		}
		else {
			throw new InvalidExpressionException('Выражение подсети"'.$expression.'" содержит недопустимый IP-адрес.');
		}

		//Теперь мы можем надлежащим образом урегулировать ряд маску сети

		$netmask = (int) $netmask;

		if (!$ip::isValidNetmask($netmask)) {
			throw new InvalidExpressionException('Неверный или вне диапазона маской сети дано.');
		}

		$this->lower   = $ip;
		$this->netmask = $netmask;
	}

	/**
	 * Проверьте, соответствует ли выражение адрес
	 *
	 * @param  AddressInterface $address
	 * @return boolean
	 */
	public function matches(AddressInterface $address) {
		$lower = $this->lower->getExpanded();
		$addr  = $address->getExpanded();

		// http://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php5
		if ($address instanceof IPv4 && $this->lower instanceof IPv4) {
			$addr    = ip2long($addr);
			$lower   = ip2long($lower);
			$netmask = -1 << (32 - $this->netmask) & ip2long('255.255.255.255');
			$lower  &= $netmask;

			return ($addr & $netmask) == $lower;
		}
		elseif ($address instanceof IPv6 && $this->lower instanceof IPv6) {
			$lower = unpack('n*', inet_pton($lower));
			$addr  = unpack('n*', inet_pton($addr));

			for ($i = 1; $i <= ceil($this->netmask / 16); $i++) {
				$left = $this->netmask - 16 * ($i-1);
				$left = ($left <= 16) ? $left : 16;
				$mask = ~(0xffff >> $left) & 0xffff;

				if (($addr[$i] & $mask) != ($lower[$i] & $mask)) {
					return false;
				}
			}

			return true;
		}

		throw new \LogicException('Сравнить только IP-адреса и той же версии.');
	}
}
