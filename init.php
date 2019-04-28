<?php 
/**
 * Simple Autoloading Class
 * @author Choi Junho H
 * @date 7/10/2015
 */
class AutoloadException extends Exception {
	
	const FILE_NOT_FOUND = 'FILE_NOT_FOUND';
	
	public function __construct(){
		$args = func_get_args();
		switch($args[0]){
			case self::FILE_NOT_FOUND:
			default:
				parent::__construct($args[1]);
		}
	}
	public function getError(){
		$msg = __CLASS__ . ' on loading class, message: File not found! File: ' . parent::getMessage();
		return $msg;
	}
}
class Loader {
	
	/**
	 *
	 * constant string EXT
	 *
	 * @desc hold the value of the external file extension (.php) by default
	 *
	 */
	const EXT = '.php';
	
	/**
	 * static method
	 * 
	 * @param string $param
	 *
	 * @desc method to include external files by autoloading it
	 *
	 * @return void
	 *
	 */
	public static function init($param)
	{
		$param = self::setSeparator($param);
		
		/* Get Filename and Directory */
		$filename = self::getFileName($param);
		$dir = self::getDirectory($param);
		
		/* Check if Directory exists */
		if($dir == ''){
			$filedir = 'package/' . $filename . '.class' . self::EXT;
		} else {
			$filedir = 'package/' . $dir . '/' . $filename . '.class' . self::EXT;
		}
		
		try {
			if(!file_exists($filedir)){
				
				/* Throw AutoloadException when file doesn't exist */
				throw new AutoloadException(AutoloadException::FILE_NOT_FOUND, $filedir);
			} else {
				
				/* Require file if the file exists */
				require_once($filedir);
			}
		}
		catch(AutoloadException $e){
			echo $e->getErrorMsg();
			exit();
		}
	}
	
	/**
	 * static method
	 * 
	 * @param string $param
	 * 
	 * @desc method to replace backslash with frontslash on parameter
	 *
	 * @return string dir
	 *
	 */
	private static function setSeparator($param)
	{
		return str_replace('\\', '/', $param);
	}
	
	/**
	 * static method
	 * 
	 * @param string $param
	 * 
	 * @desc method to get file directory without the filename
	 *
	 * @return string directory
	 *
	 */
	private static function getDirectory($param)
	{
		$arr = explode('/', $param);
		array_pop($arr);
		return implode('/', $arr);
	}
	
	/**
	 * static method
	 * 
	 * @param string $param
	 * 
	 * @desc method to get filename from directory
	 *
	 * @return string filename
	 *
	 */
	private static function getFileName($param)
	{
		$arr = explode('/', $param);
		$arrayLength = count($arr);
		return $arr[$arrayLength - 1];
	}
}
spl_autoload_register('Loader::init');
?>
