<?php
namespace DDTextCompare\TextUnit;


class Char {
	private $char, $charset;
	
	/**
	 * Char constructor.
	 * @param $char
	 * @param string $charset
	 * 
	 * @throws \Exception
	 */
	public function __construct($char, $charset = 'UTF-8'){
		$this->charset = $charset;
		$char = (string) $char;
		$charLength = mb_strlen($char, $this->charset);
		
		if($charLength !== false){
			if($charLength > 1){
				$char = mb_substr($char, 0, 1, $this->charset);
			}
			
			$this->char = $char;
		}else{
			throw new \Exception("Error occurred while creating the character $char with $charset encoding");
		}
	}
	
	public function getCharset(){
		return $this->charset;
	}
	
	public function getChar(){
		return $this->char;
	}
	
	/**
	 * equals
	 * 
	 * Compares two chars.
	 * 
	 * @param Char $char
	 * @param bool $caseSensitive
	 * 
	 * @return bool
	 */
	public function equals(Char $char, $caseSensitive = true){
		$thisChar = $this->getChar();
		$anotherChar = $char->getChar();
		
		if(!$caseSensitive){
			$thisChar = mb_strtolower($thisChar, $this->getCharset());
			$anotherChar = mb_strtolower($anotherChar, $char->getCharset());
		}
		
		return $thisChar === $anotherChar;
	}
}