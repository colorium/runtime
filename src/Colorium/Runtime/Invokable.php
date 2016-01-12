<?php

namespace Colorium\Runtime;

class Invokable
{

    const CLOSURE = 0;
    const STATIC_METHOD = 1;
    const CLASS_METHOD = 2;

    /** @var array */
    public $params = [];

    /** @var callable */
    protected $callable;

    /** @var int */
    protected $type;

    /** @var array */
    protected $annotations = [];

    /** @var \ReflectionFunctionAbstract */
    protected $reflector;


    /**
     * Resolved resource
     *
     * @param callable $callable
     * @param int $type
     * @param array $annotations
     * @param \ReflectionFunctionAbstract $reflector
     */
    public function __construct($callable, $type, array $annotations, \ReflectionFunctionAbstract $reflector)
    {
        $this->callable = $callable;
        $this->type = $type;
        $this->annotations = $annotations;
        $this->reflector = $reflector;
    }


    /**
     * Is static method
     *
     * @return bool
     */
    public function isStaticMethod()
    {
        return $this->type === self::STATIC_METHOD;
    }


    /**
     * Is class method
     *
     * @return bool
     */
    public function isClassMethod()
    {
        return $this->type === self::CLASS_METHOD;
    }


    /**
     * Is closure
     *
     * @return bool
     */
    public function isClosure()
    {
        return $this->type === self::CLOSURE;
    }


    /**
     * Get annotation
     *
     * @param string $key
     * @return string
     */
    public function annotation($key)
    {
        return isset($this->annotations[$key])
            ? $this->annotations[$key]
            : null;
    }


    /**
     * Get all annotations
     *
     * @return array
     */
    public function annotations()
    {
        return $this->annotations;
    }


    /**
     * Get reflector instance
     *
     * @return \ReflectionFunctionAbstract
     */
    public function reflector()
    {
        return $this->reflector;
    }


    /**
     * Instanciate class
     *
     * @param array $params
     * @return mixed
     */
    public function instanciate(array $params = [])
    {
        $reflector = $this->reflector;
        if($this->isClassMethod() and !is_object($this->callable[0]) and $reflector instanceof \ReflectionMethod) {
            $this->callable[0] = $reflector->getDeclaringClass()->newInstanceArgs($params);
        }

        return $this;
    }


    /**
     * Execute callable
     *
     * @param array $params
     * @return mixed
     */
    public function call(...$params)
    {
        $params = $params ?: $this->params;

        return call_user_func_array($this->callable, $params);
    }


    /**
     * Invokable resource
     *
     * @param ...$params
     * @return mixed
     */
    public function __invoke(...$params)
    {
        return $this->call(...$params);
    }

}