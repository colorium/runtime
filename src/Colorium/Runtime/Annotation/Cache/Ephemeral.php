<?php

namespace Colorium\Runtime\Annotation\Cache;

use Colorium\Runtime\Annotation\Cache;

class Ephemeral implements Cache
{

    /** @var array */
    protected $classes = [];

    /** @var array */
    protected $methods = [];

    /** @var array */
    protected $properties = [];

    /** @var array */
    protected $functions = [];


    /**
     * Store class annotations
     *
     * @param string $classname
     * @param array $annotations
     */
    public function storeClass($classname, array $annotations = [])
    {
        $this->classes[$classname] = $annotations;
    }

    /**
     * Retrieve class annotations
     *
     * @param string $classname
     * @return array
     */
    public function getClass($classname)
    {
        return $this->hasClass($classname)
            ? $this->classes[$classname]
            : [];
    }

    /**
     * Check if class annotations are stored
     *
     * @param string $classname
     * @return bool
     */
    public function hasClass($classname)
    {
        return isset($this->classes[$classname]);
    }

    /**
     * Store class method annotations
     *
     * @param string $classname
     * @param string $method
     * @param array $annotations
     */
    public function storeMethod($classname, $method, array $annotations = [])
    {
        $this->methods[$classname . '::' . $method] = $annotations;
    }

    /**
     * Retrieve class method annotations
     *
     * @param string $classname
     * @param string $method
     * @return array
     */
    public function getMethod($classname, $method)
    {
        return $this->hasMethod($classname, $method)
            ? $this->methods[$classname . '::' . $method]
            : [];
    }

    /**
     * Check if class method annotations are stored
     *
     * @param string $classname
     * @param string $method
     * @return bool
     */
    public function hasMethod($classname, $method)
    {
        return isset($this->methods[$classname . '::' . $method]);
    }

    /**
     * Store class property annotations
     *
     * @param string $classname
     * @param string $property
     * @param array $annotations
     */
    public function storeProperty($classname, $property, array $annotations = [])
    {
        $this->properties[$classname . '$' . $property] = $annotations;
    }

    /**
     * Retrieve class property annotations
     *
     * @param string $classname
     * @param string $property
     * @return array
     */
    public function getProperty($classname, $property)
    {
        return $this->hasProperty($classname, $property)
            ? $this->properties[$classname . '$' . $property]
            : [];
    }

    /**
     * Check if class property annotations are stored
     *
     * @param string $classname
     * @param string $property
     * @return bool
     */
    public function hasProperty($classname, $property)
    {
        return isset($this->properties[$classname . '$' . $property]);
    }

    /**
     * Store function annotations
     *
     * @param string $function
     * @param array $annotations
     */
    public function storeFunction($function, array $annotations = [])
    {
        $this->functions[$function] = $annotations;
    }

    /**
     * Retrieve function annotations
     *
     * @param string $function
     * @return array
     */
    public function getFunction($function)
    {
        return $this->hasFunction($function)
            ? $this->functions[$function]
            : [];
    }

    /**
     * Check if function annotations are stored
     *
     * @param string $function
     * @return bool
     */
    public function hasFunction($function)
    {
        return isset($this->functions[$function]);
    }

}