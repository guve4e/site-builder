<?php

/**
 * Utility class.
 * Provides Interfaces to abstract Reflection.
 */
trait UtilityTest
{
    /**
     * Call protected/private properties of a class.
     *
     * @param object &$object Instantiated object
     * @param $propertyName
     * @return mixed property return.
     * @throws ReflectionException
     * @internal param string $propertiedName
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

    /**
     * Determine if two associative arrays are similar
     *
     * Both arrays must have the same indexes with identical values
     * without respect to key ordering
     *
     * @param array $a
     * @param array $b
     * @return bool
     */
    function arraysAreSimilar($a, $b) {
        // if the indexes don't match, return immediately
        if (count(array_diff_assoc($a, $b))) {
            return false;
        }
        // we know that the indexes, but maybe not values, match.
        // compare the values between the two arrays
        foreach($a as $k => $v) {
            if ($v !== $b[$k]) {
                return false;
            }
        }
        // we have identical indexes, and no unequal values
        return true;
    }
}