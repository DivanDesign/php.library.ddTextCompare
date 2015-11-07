<?php
namespace DDTextCompare\TextUnit;


use DDTextCompare\WordFinder\RegexUnicode;
use DDTextCompare\WordFinder\WordFinder;

class Text implements \Iterator, \Countable {
	private
		$text,
		$words = array(),
		//Only UTF-8 is supported for now
		$charset = 'UTF-8',
		$uniqueChars = array(),
		$position = 0;
	
	/**
	 * Text constructor.
	 * @param $text
	 * @param WordFinder $wordFinder
	 */
	public function __construct($text, WordFinder $wordFinder = null){
		
		if($wordFinder === null){
			$wordFinder = new RegexUnicode();
		}
		
		$this->text = (string) $text;
		$wordsArray = $wordFinder->getWords($this->text);
		$rawUniqueChars = array();
		
		//Iterate over all found words to construct Word objects and push them into $this->words[]
		foreach($wordsArray as $textWord){
			$word = new Word($textWord, $this->charset);
			$this->words[] = $word;
			
			//Iterate over the chars in the word to populate the text unique chars array
			foreach($word as $char){
				$rawChar = $char->getChar();
				
				if(!in_array($rawChar, $rawUniqueChars)){
					$rawUniqueChars[] = $rawChar;
					$this->uniqueChars[] = clone $char;
				}
			}
		}
	}
	
	/* Iterator interface */
	public function current(){
		return clone $this->words[$this->position];
	}
	
	public function key(){
		return $this->position;
	}
	
	public function next(){
		$this->position += 1;
	}
	
	public function rewind(){
		$this->position = 0;
	}
	
	public function valid(){
		return isset($this->words[$this->position]);
	}
	/* Iterator interface */
	
	/* Countable interface */
	public function count(){
		return count($this->words);
	}
	/* Countable interface */
	
	/**
	 * getCharset
	 * 
	 * @return string
	 */
	public function getCharset(){
		return $this->charset;
	}
	
	/**
	 * getUniqueChars
	 * 
	 * @return array
	 */
	public function getUniqueChars(){
		return $this->uniqueChars;
	}
	
	/**
	 * getRawUniqueChars
	 * 
	 * Returns the unique chars used in the text.
	 * 
	 * @return array
	 */
	public function getRawUniqueChars(){
		$output = array();
		$uniqueChars = $this->getUniqueChars();
		
		foreach($uniqueChars as $uniqueChar){
			$output[] = $uniqueChar->getChar();
		}
		
		return $output;
	}
	
	/**
	 * getCharUsesTotal
	 * 
	 * @param Char $char
	 * @param bool $caseSensitive
	 * @return int
	 */
	public function getCharUsesTotal(Char $char, $caseSensitive = true){
		$output = 0;
		
		foreach($this as $word){
			$output += $word->getCharUsesTotal($char, $caseSensitive);
		}
		
		return $output;
	}
	
	/**
	 * getCharAveragePosition
	 * 
	 * @param Char $char
	 * @param bool $caseSensitive
	 * @return bool|float
	 */
	public function getCharAveragePosition(Char $char, $caseSensitive = true){
		$output = false;
		$charAveragePositionSum = 0;
		$wordWithCharCount = 0;
		
		foreach($this as $textWord){
			$charAverageWordPosition = $textWord->getCharAveragePosition($char, $caseSensitive);
			
			if($charAverageWordPosition !== false){
				$charAveragePositionSum += $charAverageWordPosition;
				$wordWithCharCount++;
			}
		}
		
		if($wordWithCharCount > 0){
			$output = $charAveragePositionSum/$wordWithCharCount;
		}
		
		return $output;
	}
}