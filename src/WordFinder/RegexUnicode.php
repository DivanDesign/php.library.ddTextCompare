<?php
namespace DDTextCompare\WordFinder;

/**
 * Class RegexUnicode
 * 
 * This word finder class use a simple regular expression to find words
 * 
 * @package DDTextCompare\WordFinder
 */
class RegexUnicode implements WordFinder {
	/**
	 * getWords
	 * 
	 * @param string $text
	 * 
	 * @return array
	 */
	public function getWords($text) {
		$text = (string) $text;
		$output = array();
		
		//Find all words in the given text
		preg_match_all('/\w+/u', $text, $matches);
		
		if(is_array($matches[0])){
			$output = $matches[0];
		}
		
		return $output;
	}
}