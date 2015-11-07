<?php
namespace DDTextCompare\Comparator;


use DDTextCompare\Math\Vector, DDTextCompare\TextUnit\Char, DDTextCompare\TextUnit\Text;

/**
 * Class Cosine
 * 
 * 
 * 
 * @package DDTextCompare\Comparator
 */
class Cosine implements Comparator {
	private $charTotalWeight = 0.5;
	private $charAveragePositionWeight = 0.5;
	
	/**
	 * setCharTotalWeight
	 * 
	 * @param $weight
	 * 
	 * @return bool
	 */
	public function setCharTotalWeight($weight){
		$output = false;
		
		if($weight <= 1){
			$this->charTotalWeight = (float) $weight;
			$this->charAveragePositionWeight = 1 - $this->charTotalWeight;
		}
		
		return $output;
	}
	
	/**
	 * getCharTotalWeight
	 * 
	 * @return float
	 */
	public function getCharTotalWeight(){
		return $this->charTotalWeight;
	}
	
	/**
	 * setCharAveragePositionWeight
	 * 
	 * @param $weight
	 * 
	 * @return bool
	 */
	public function setCharAveragePositionWeight($weight){
		$output = false;
		
		if($weight <= 1){
			$this->charAveragePositionWeight = (float) $weight;
			$this->charTotalWeight = 1 - $this->charAveragePositionWeight;
		}
		
		return $output;
	}
	
	/**
	 * getCharAveragePositionWeight
	 * 
	 * @return float
	 */
	public function getCharAveragePositionWeight(){
		return $this->charAveragePositionWeight;
	}
	
	/**
	 * compare
	 * 
	 * @param string $text1
	 * @param string $text2
	 * 
	 * @return false|float
	 */
	public function compare($text1, $text2){
		$text1 = new Text((string) $text1);
		$text2 = new Text((string) $text2);
		
		$output = 1;
		$uniqueCharsText1 = $text1->getRawUniqueChars();
		$uniqueCharsText2 = $text2->getRawUniqueChars();
		$uniqueCharsText1LowerCase = array();
		$uniqueCharsText2LowerCase = array();
		
		array_walk($uniqueCharsText1, function($char) use($text1, &$uniqueCharsText1LowerCase) {
			$uniqueCharsText1LowerCase[] = mb_strtolower($char, $text1->getCharset());
		});
		
		array_walk($uniqueCharsText2, function($char) use($text2, &$uniqueCharsText2LowerCase) {
			$uniqueCharsText2LowerCase[] = mb_strtolower($char, $text2->getCharset());
		});
		
		$uniqueChars = array_unique(array_merge($uniqueCharsText1LowerCase, $uniqueCharsText2LowerCase));
		
		//TODO: Паша: Что-то не нравится, как тут всё это написано дальше. Надо по-нормальному.
		$text1CoordinatesCharTotal = array();
		$text1CoordinatesCharPosition = array();
		$text2CoordinatesCharTotal = array();
		$text2CoordinatesCharPosition = array();
		
		foreach($uniqueChars as $uniqueChar){
			$uniqueCharText1 = new Char($uniqueChar, $text1->getCharset());
			$uniqueCharText2 = new Char($uniqueChar, $text2->getCharset());
			
			$charAveragePositionInText1 = $text1->getCharAveragePosition($uniqueCharText1, false);
			$charAveragePositionInText2 = $text2->getCharAveragePosition($uniqueCharText2, false);
			
			$charUsesTotalInText1 = $text1->getCharUsesTotal($uniqueCharText1, false);
			$charUsesTotalInText2 = $text2->getCharUsesTotal($uniqueCharText2, false);
			
			if($charAveragePositionInText1 === false){
				$charAveragePositionInText1 = -$charAveragePositionInText2;
				$charUsesTotalInText1 = -$charUsesTotalInText2;
			}elseif($charAveragePositionInText2 === false){
				$charAveragePositionInText2 = -$charAveragePositionInText1;
				$charUsesTotalInText2 = -$charUsesTotalInText1;
			}
			
			$text1CoordinatesCharTotal[] = $charUsesTotalInText1;
			$text1CoordinatesCharPosition[] = $charAveragePositionInText1;

			$text2CoordinatesCharTotal[] = $charUsesTotalInText2;
			$text2CoordinatesCharPosition[] = $charAveragePositionInText2;
		}
		
		if(count($uniqueChars) !== 0){
			$text1VectorCharTotal = new Vector($text1CoordinatesCharTotal);
			$text1VectorCharPosition = new Vector($text1CoordinatesCharPosition);
			
			$text2VectorCharTotal = new Vector($text2CoordinatesCharTotal);
			$text2VectorCharPosition = new Vector($text2CoordinatesCharPosition);
			
			$cosineCharTotal = Vector::cosine($text1VectorCharTotal, $text2VectorCharTotal);
			$cosineCharPosition = Vector::cosine($text1VectorCharPosition, $text2VectorCharPosition);
			
//			print_r("\r\n cosine char total: $cosineCharTotal cosine char position: $cosineCharPosition \r\n");
			
			//Приводим результат в интервал от 0 до 1
			$output = 0.5 * ($this->charTotalWeight * $cosineCharTotal + $this->charAveragePositionWeight * $cosineCharPosition + 1);
		}
		
		return $output;
	}
}