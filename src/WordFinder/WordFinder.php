<?php
namespace DDTextCompare\WordFinder;

/**
 * Interface WordFinder
 * 
 * This interface should be implemented by a concrete word finder class.
 * 
 * @package DDTextCompare\WordFinder
 */
interface WordFinder {
	/**
	 * getWords
	 * 
	 * @param $text
	 * 
	 * @return array
	 */
	public function getWords($text);
}