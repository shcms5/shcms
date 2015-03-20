<?php
/**
 * Класс для работы с IP адресами
 * 
 * @package               Classes
 * @author                Shamsik
 * @link                  http://shcms.ru
 */

namespace Shcms\Resources\IpUtils\Address;

use Shcms\Resources\IpUtils\Expression\ExpressionInterface;

interface AddressInterface {
	/**
	 * Получить полностью укомплектованную адрес
	 *
	 * @return string
	 */
	public function getExpanded();

	/**
	 * Получить представление компактный адрес
	 *
	 * @return string
	 */
	public function getCompact();

	/**
	 * Получить куски IP-специфические ([127,000,000,001] for IPv4 or [0000,0000,00ff,00ea,0001,...] for IPv6)
	 *
	 * @return array
	 */
	public function getChunks();

	/**
	 * Проверьте, соответствует ли адрес заданному шаблону / диапазон
	 *
	 * @param  ExpressionInterface $expression
	 * @return boolean
	 */
	public function matches(ExpressionInterface $expression);
}
