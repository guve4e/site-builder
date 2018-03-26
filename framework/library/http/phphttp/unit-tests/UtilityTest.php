<?php

/**
 * Utility class that provide some reflection utilities.
 */
trait UtilityTest
{
    /**
     * Call protected/private properties of a class.
     *
     * @param object &$object Instantiated object
     * @param $propertyName
     * @return mixed property return.
     * @internal param string $propertiedName
     * @throws ReflectionException
     */
    public function invokeProperty(&$object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property;
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     * @throws ReflectionException
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     *
     * @param $object
     * @param $nameOfVar
     * @return mixed
     * @throws ReflectionException
     */
    public function getProperty($object, $nameOfVar)
    {
        $var = $this->invokeProperty($object,$nameOfVar);
        $value = $var->getValue($object);
        return $value;
    }
}