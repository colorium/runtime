<?php

namespace Colorium\Runtime;

abstract class Resolver
{

    /**
     * Resolve if callable is static method
     *
     * @param $callable
     * @return Invokable
     */
    public static function ofStaticMethod($callable)
    {
        // parse class::method to [class, method]
        if(is_string($callable) and strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }

        // resolve [class, method]
        if(is_array($callable) and count($callable) === 2) {
            $reflector = new \ReflectionMethod($callable[0], $callable[1]);
            if($reflector->isPublic() and $reflector->isStatic()) {
                $annotations = array_merge(
                    Annotation::ofClass($callable[0]),
                    Annotation::ofMethod($callable[0], $callable[1])
                );
                return new Invokable($callable, Invokable::STATIC_METHOD, $annotations, $reflector);
            }
        }
    }


    /**
     * Resolve if callable is class method
     *
     * @param $callable
     * @return Invokable
     */
    public static function ofClassMethod($callable)
    {
        // parse class::method to [class, method]
        if(is_string($callable) and strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }

        // resolve [class, method]
        if(is_array($callable) and count($callable) === 2) {
            $reflector = new \ReflectionMethod($callable[0], $callable[1]);
            if($reflector->isPublic() and !$reflector->isStatic() and !$reflector->isAbstract()) {
                $annotations = array_merge(
                    Annotation::ofClass($callable[0]),
                    Annotation::ofMethod($callable[0], $callable[1])
                );
                return new Invokable($callable, Invokable::CLASS_METHOD, $annotations, $reflector);
            }
        }
    }


    /**
     * Resolve if callable is invoke class method
     *
     * @param $callable
     * @return Invokable
     */
    public static function ofInvokeMethod($callable)
    {
        if((is_object($callable) or class_exists($callable)) and method_exists($callable, '__invoke')) {
            return static::ofClassMethod([$callable, '__invoke']);
        }
    }


    /**
     * Resolve if callable is closure or function
     *
     * @param $callable
     * @return Invokable
     */
    public static function ofFunction($callable)
    {
        if($callable instanceof \Closure or (is_string($callable) and function_exists($callable))) {
            $annotations = Annotation::ofFunction($callable);
            $reflector = new \ReflectionFunction($callable);
            return new Invokable($callable, Invokable::CLOSURE, $annotations, $reflector);
        }
    }


    /**
     * Resolve callable to valid resource
     *
     * @param $callable
     * @return Invokable
     */
    public static function of($callable)
    {
        return static::ofStaticMethod($callable)
            ?: static::ofInvokeMethod($callable)
            ?: static::ofClassMethod($callable)
            ?: static::ofFunction($callable)
            ?: false;
    }

}