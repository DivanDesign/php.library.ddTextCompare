<?php
namespace DDTextCompare\TextUnit;


class Word implements \Iterator, \Countable {
	private
		$word,
		$chars = array(),
		$charset,
		$position = 0;
	
	/**
	 * Word constructor.
	 * 
	 * @param $word
	 * @param string $charset
	 */
	public function __construct($word, $charset = 'UTF-8'){
		$this->charset = $charset;
		$this->word = (string) $word;
		$wordLength = mb_strlen($this->word, $this->charset);
		
		for($charIndex = 0; $charIndex < $wordLength; $charIndex++){
			$this->chars[] = new Char(mb_substr($this->word, $charIndex, 1, $this->charset), $this->charset);
		}
	}
	
	/* Iterator interface */
	public function current(){
		return clone $this->chars[$this->position];
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
		return isset($this->chars[$this->position]);
	}
	/* Iterator interface */
	
	/* Countable interface */
	public function count(){
		return count($this->chars);
	}
	/* Countable interface */
	
	/**
	 * @return string
	 */
	public function getCharset(){
		return $this->charset;
	}
	
	/**
	 * getCharUsesTotal
	 * 
	 * @param Char $char
	 * @param bool $caseSensitive
	 * 
	 * @return int
	 */
	public function getCharUsesTotal(Char $char, $caseSensitive = true){
		$rawWord = $this->word;
		
		if(!$caseSensitive){
			$rawWord = mb_strtolower($rawWord, $this->getCharset());
		}
		
		return mb_substr_count($rawWord, $char->getChar(), $this->charset);
	}
	
	/**
	 * getCharIndices
	 * 
	 * @param Char $char
	 * @param bool $caseSensitive
	 * 
	 * @return array
	 */
	public function getCharIndices(Char $char, $caseSensitive = true){
		$output = array();
		
		foreach($this as $wordCharIndex => $wordChar){
			if($wordChar->equals($char, $caseSensitive)){
				$output[] = $wordCharIndex;
			}
		}
		
		return $output;
	}
	
	/**
	 * getWord
	 * 
	 * @return string
	 */
	public function getWord(){
		return $this->word;
	}
	
	/**
	 * getCharAveragePosition
	 * 
	 * @param Char $char
	 * @param bool $caseSensitive
	 * 
	 * @return bool|float|int
	 */
	public function getCharAveragePosition(Char $char, $caseSensitive = true){
		$output = false;
		$charIndices = $this->getCharIndices($char, $caseSensitive);
		$charCount = count($charIndices);
		
		if($charCount > 0){
			$averagePosition = 0;
			
			foreach($charIndices as $wordCharIndex){
				$averagePosition += ($wordCharIndex + 1)/$charCount;
			}
			
			$output = $averagePosition;
		}
		
		return $output;
	}
}