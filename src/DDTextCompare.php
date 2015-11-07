<?php
namespace DDTextCompare;


use DDTextCompare\Comparator\Comparator, DDTextCompare\Comparator\Cosine;

class DDTextCompare {
	/**
	 * compare
	 * 
	 * @param string $text1
	 * @param string $text2
	 * @param Comparator|null $comparator
	 * 
	 * @return false|float
	 */
	public function compare($text1, $text2, Comparator $comparator = null){
		if($comparator === null){
			$comparator = new Cosine();
		}
		
		return $comparator->compare($text1, $text2);
	}
}