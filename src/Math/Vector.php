<?php
namespace DDTextCompare\Math;


/**
 * Class Vector
 * 
 * A representation of an n-th dimensional vector.
 * 
 * @package DDTextCompare\Math
 */
class Vector implements \Iterator, \Countable {
	private $coordinates, $position = 0;
	
	/**
	 * Vector constructor.
	 * 
	 * @param array $initArray
	 */
	public function __construct(array $initArray){
		$this->coordinates = array_map('floatval', $initArray);
	}
	
	/* Iterator interface */
	public function current(){
		$coordinates = $this->getCoordinates();
		
		return $coordinates[$this->position];
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
		$coordinates = $this->getCoordinates();
		
		return $coordinates[$this->position];
	}
	/* Iterator interface */
	
	/* Countable interface */
	public function count(){
		return count($this->getCoordinates());
	}
	/* Countable interface */
	
	/**
	 * getCoordinates
	 * 
	 * @return array
	 */
	public function getCoordinates(){
		return $this->coordinates;
	}
	
	/**
	 * magnitude
	 * 
	 * Calculates magnitude.
	 * 
	 * @return float
	 */
	public function magnitude(){
		$quadricSum = 0;
		
		foreach($this->coordinates as $coord){
//			print_r(" COORD $coord ");
			
			$quadricSum += pow($coord, 2);
		}
		
		return sqrt($quadricSum);
	}
	
	/**
	 * getOppositeVector
	 * 
	 * Returns of the opposite vector to the given.
	 * 
	 * @return Vector
	 */
	public function getOppositeVector(){
		$oppositeCoord = array();
		
		array_walk($this->coordinates, function($coord) use(&$oppositeCoord){
			$oppositeCoord[] = -$coord;
		});
		
		return new self($oppositeCoord);
	}
	
	/**
	 * dotProduct
	 * 
	 * Calculates dot product of two vectors.
	 * 
	 * @param Vector $a
	 * @param Vector $b
	 * 
	 * @return float|int|false
	 */
	public static function dotProduct(Vector $a, Vector $b){
		$output = false;
		$dotProduct = 0;
		
		if(count($a) === count($b)){
			$aCoordinates = $a->getCoordinates();
			$bCoordinates = $b->getCoordinates();
			
			foreach($aCoordinates as $index => $aCoord){
				$dotProduct += $aCoord * $bCoordinates[$index];
			}
			
			$output = $dotProduct;
		}
		
		return $output;
	}
	
	/**
	 * cosine
	 * 
	 * Calculates the cosine between two given vectors.
	 * The vectors must have the same dimension.
	 * 
	 * @param Vector $a
	 * @param Vector $b
	 * 
	 * @return false|float
	 */
	public static function cosine(Vector $a, Vector $b){
		$output = false;
		$abMagProduct = $a->magnitude() * $b->magnitude();
		
//		print_r("\r\nMAG 1: ".$a->magnitude()." MAG 2: ".$b->magnitude()."\r\n");
		
		if($abMagProduct != 0){
//			print_r(" DOT PRODUCT: ".static::dotProduct($a, $b)." MAG PRODUCT: ".$abMagProduct." ");
			$output = static::dotProduct($a, $b) / $abMagProduct;
		}
		
		return $output;
	}
}