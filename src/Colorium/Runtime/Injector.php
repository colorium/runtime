<?php

namespace Colorium\Runtime;

class Injector
{

    /** @var array */
    protected $injectable = [];

    /** @var array */
    protected $factories = [];


    /**
     * Store value
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function value($key, $value)
    {
        $this->injectable[$key] = $value;
    }


    /**
     * Store object
     *
     * @param $object
     * @return $this
     */
    public function object($object)
    {
        $this->injectable[get_class($object)] = $object;
    }


    /**
     * Store factory
     *
     * @param string $key
     * @param \Closure $factory
     * @return $this
     */
    public function factory($key, \Closure $factory)
    {
        $this->injectable[$key] = $factory;
        $this->factories[$key] = true;
    }


    /**
     * Inject in parameters
     *
     * @param \ReflectionFunctionAbstract $reflector
     * @param array $annotations
     * @param array $params
     * @return array
     */
    public function inject(\ReflectionFunctionAbstract $reflector, array $annotations = [], array $params = [])
    {
        $output = [];
        foreach($reflector->getParameters() as $index => $parameter) {
            $value = $default = isset($params[$index]) ? $params[$index] : null;
            $name = $parameter->getName();
            foreach($annotations as $annotation) {
                if(preg_match('/\$' . $name . ' injected as (.+)/', $annotation, $out)) {
                    $key = trim($out[1]);
                    if (isset($this->injectable[$key])) {
                        $value = $this->injectable[$key];
                        if($value instanceof \Closure and isset($this->factories[$key])) {
                            $value = $value($default);
                        }
                        continue;
                    }
                    else {
                        throw new \RuntimeException('Unknown injectable named "' . $key . '"');
                    }
                }
            }
            $output[] = $value;
        }

        return $output;
    }

}