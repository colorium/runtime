<?php

namespace Colorium\Runtime\Contract;

interface CacheInterface
{

    /**
     * Store class annotations
     *
     * @param string $classname
     * @param array $annotations
     */
    public function storeClass($classname, array $annotations = []);

    /**
     * Retrieve class annotations
     *
     * @param string $classname
     * @return array
     */
    public function getClass($classname);

    /**
     * Check if class annotations are stored
     *
     * @param string $classname
     * @return bool
     */
    public function hasClass($classname);

    /**
     * Store class method annotations
     *
     * @param string $classname
     * @param string $method
     * @param array $annotations
     */
    public function storeMethod($classname, $method, array $annotations = []);

    /**
     * Retrieve class method annotations
     *
     * @param string $classname
     * @param string $method
     * @return array
     */
    public function getMethod($classname, $method);

    /**
     * Check if class method annotations are stored
     *
     * @param string $classname
     * @param string $method
     * @return bool
     */
    public function hasMethod($classname, $method);

    /**
     * Store class property annotations
     *
     * @param string $classname
     * @param string $property
     * @param array $annotations
     */
    public function storeProperty($classname, $property, array $annotations = []);

    /**
     * Retrieve class property annotations
     *
     * @param string $classname
     * @param string $property
     * @return array
     */
    public function getProperty($classname, $property);

    /**
     * Check if class property annotations are stored
     *
     * @param string $classname
     * @param string $property
     * @return bool
     */
    public function hasProperty($classname, $property);

    /**
     * Store function annotations
     *
     * @param string $function
     * @param array $annotations
     */
    public function storeFunction($function, array $annotations = []);

    /**
     * Retrieve function annotations
     *
     * @param string $function
     * @return array
     */
    public function getFunction($function);

    /**
     * Check if function annotations are stored
     *
     * @param string $function
     * @return bool
     */
    public function hasFunction($function);

}