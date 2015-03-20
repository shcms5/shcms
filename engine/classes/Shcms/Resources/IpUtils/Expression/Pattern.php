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

class Pattern implements ExpressionInterface {
	protected $expression;

	public function __construct($expression) {
		$expression = strtolower(trim($expression));
		$expression = preg_replace('/\*+/', '*', $expression);

		$this->expression = $expression;
	}

	/**
	 * Проверьте, соответствует ли выражение адрес
	 *
	 * @param  AddressInterface $address
	 * @return boolean
	 */
	public function matches(AddressInterface $address) {
		$addrChunks = $address->getChunks();
		$exprChunks = preg_split('/[.:]/', $this->expression);

		if (count($exprChunks) !== count($addrChunks)) {
			throw new \UnexpectedValueException('Адрес и выражение не содержат такое же количество кусков. Разве вы смешиваете IPv4 и IPv6?');
		}

		foreach ($exprChunks as $idx => $exprChunk) {
			$addrChunk = $addrChunks[$idx];

			if (strpos($exprChunk, '*') === false) {
				// Это хорошо, если выражение содержит "0,0". и IP содержит '0,000.',
				// мы просто заботиться о численном значении (и это также хорошо, чтобы интерпретировать
				// IPv4 кусочкам, как шестнадцатеричные значения, до тех пор, как мы интерпретировать и как шестнадцатеричном виде).
				if (hexdec($addrChunk) !== hexdec($exprChunk)) {
					return false;
				}
			}
			else {
				$exprChunk = str_replace('*', '[0-9a-f]+?', $exprChunk);

				if (!preg_match('/^'.$exprChunk.'$/', $addrChunk)) {
					return false;
				}
			}
		}

		return true;
	}
}
