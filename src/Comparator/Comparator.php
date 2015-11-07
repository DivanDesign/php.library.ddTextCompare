<?php
namespace DDTextCompare\Comparator;


/**
 * Interface Comparator
 * 
 * This interface should be implemented by a concrete comparator class
 * 
 * @package DDTextCompare\Comparator
 */
interface Comparator {
	/**
	 * @param string $text1
	 * @param string $text2
	 * @return false|float
	 */
	public function compare($text1, $text2);
}