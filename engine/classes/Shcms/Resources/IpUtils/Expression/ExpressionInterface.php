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

interface ExpressionInterface {
	/**
	 * Проверьте, соответствует ли выражение адрес
	 *
	 * @param  AddressInterface $address
	 * @return boolean
	 */
	public function matches(AddressInterface $address);
}
