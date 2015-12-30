<?php

namespace Colorium\Runtime;

abstract class Annotation
{

	/** @var Annotation\Parser */
	protected static $parser;

	/** @var Annotation\Cache */
	protected static $cache;


	/**
	 * Load annotation parser
	 *
	 * @param Annotation\Parser $parser
	 * @return Annotation\Parser
	 */
	public static function parser(Annotation\Parser $parser = null)
	{
		if($parser) {
			static::$parser = $parser;
		}
		elseif(!static::$parser) {
			static::$parser = new Annotation\Parser\KeyValuePair;
		}

		return static::$parser;
	}


	/**
	 * Load annotation cache strategy
	 * 
	 * @param Annotation\Cache $cache
	 * @return Annotation\Cache
	 */
	public static function cache(Annotation\Cache $cache = null)
	{
		if($cache) {
			static::$cache = $cache;
		}
		elseif(!static::$cache) {
			static::$cache = new Annotation\Cache\Ephemeral;
		}

		return static::$cache;
	}


	/**
	 * Parse class annotations
	 *
	 * @param $class
	 * @param string $key 
	 * @return array
	 */
	public static function ofClass($class, $key = null)
	{
		$reflector = is_object($class) ? new \ReflectionObject($class) : new \ReflectionClass($class);

		if(!static::cache()->hasClass($reflector->getName())) {
			$annotations = static::parser()->parse($reflector->getDocComment());
			static::cache()->storeClass($reflector->getName(), $annotations);
		}
		else {
			$annotations = static::cache()->getClass($reflector->getName());
		}

		if($key) {
			return isset($annotations[$key]) ? $annotations[$key] : null;
		}

		return $annotations;
	}


	/**
	 * Parse class property annotations
	 *
	 * @param $class
	 * @param string $property
	 * @param string $key
	 * @return array
	 */
	public static function ofProperty($class, $property, $key = null)
	{
		$reflector = is_object($class) ? new \ReflectionObject($class) : new \ReflectionClass($class);

		if(!static::cache()->hasProperty($reflector->getName(), $property)) {
			$annotations = static::parser()->parse($reflector->getProperty($property)->getDocComment());
			static::cache()->storeProperty($reflector->getName(), $property, $annotations);
		}
		else {
			$annotations = static::cache()->getProperty($reflector->getName(), $property);
		}

		if($key) {
			return isset($annotations[$key]) ? $annotations[$key] : null;
		}

		return $annotations;
	}


	/**
	 * Parse class property annotations
	 *
	 * @param $class
	 * @param string $method
	 * @param string $key
	 * @return array
	 */
	public static function ofMethod($class, $method, $key = null)
	{
		$reflector = is_object($class) ? new \ReflectionObject($class) : new \ReflectionClass($class);

		if(!static::cache()->hasMethod($reflector->getName(), $method)) {
			$annotations = static::parser()->parse($reflector->getMethod($method)->getDocComment());
			static::cache()->storeMethod($reflector->getName(), $method, $annotations);
		}
		else {
			$annotations = static::cache()->getMethod($reflector->getName(), $method);
		}

		if($key) {
			return isset($annotations[$key]) ? $annotations[$key] : null;
		}

		return $annotations;
	}


	/**
	 * Parse class property annotations
	 *
	 * @param $function
	 * @param string $key
	 * @return array
	 */
	public static function ofFunction($function, $key = null)
	{
		$reflector = new \ReflectionFunction($function);

		if(!static::cache()->hasFunction($reflector->getName())) {
			$annotations = static::parser()->parse($reflector->getDocComment());
			static::cache()->storeFunction($reflector->getName(), $annotations);
		}
		else {
			$annotations = static::cache()->getFunction($reflector->getName());
		}

		if($key) {
			return isset($annotations[$key]) ? $annotations[$key] : null;
		}

		return $annotations;
	}

}