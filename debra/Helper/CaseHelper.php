<?php namespace Debra\Helper;

/**
 * Class CaseHelper
 * @package Debra\Helper
 */
class CaseHelper
{
	/**
	 * transforms a string to camel case
	 *
	 * @param string $string
	 * @return string
	 */
	public function toCamelCase($string)
	{
		$changed = false;
		$newString = '';
		for($i = 0; $i < strlen($string); $i++) {

			// if underscore, make the next latter to uppercase
			if ($string[$i] === '_') {
				$newString .= strtoupper($string[++$i]);
				$changed = true;
			} else {
				$newString .= $string[$i];
			}
		}

		return $changed === true ? $this->toCamelCase($newString) : $newString;
	}

	/**
	 * transforms a string to snake case
	 *
	 * @param string $string
	 * @return string
	 */
	public function toSnakeCase($string)
	{
		$newString = '';
		for($i = 0; $i < strlen($string); $i++) {

			// if uppercase
			if (ctype_upper($string[$i]) === true) {
				$newString .= '_';
			}

			$newString .= strtolower($string[$i]);
		}

		return $newString;
	}
}