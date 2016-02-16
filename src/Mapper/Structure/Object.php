<?php

namespace PhMap\Mapper\Structure;

use \ReflectionClass,

    \PhMap\Mapper\Structure;

/**
 * Class Object
 * @package PhMap\Mapper\Structure
 */
class Object extends Structure {

    /**
     * @param object $inputObject
     * @param string|object $outputClassOrObject
     * @param integer $adapter
     */
    public function __construct(
        $inputObject,
        $outputClassOrObject,
        $adapter = self::MEMORY_ANNOTATION_ADAPTER
    ) {
        parent::__construct($inputObject, $outputClassOrObject, $adapter);
    }

    /**
     * @return object
     */
    public function getInputObject() {
        return $this->getInputStructure();
    }

    /**
     * @param object $object
     * @return $this
     */
    public function setInputObject($object) {
        return $this->setInputStructure($object);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    protected function isObject($value) {
        return is_object($value);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    protected function isSequential($value) {
        return is_array($value);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    protected function isStructure($value) {
        return $this->isObject($value) || $this->isSequential($value);
    }

    /**
     * @return array
     */
    protected function getInputAttributes() {
        if ($this->isInputStandardClass()) {
            return $this->getInputObject();
        }

        $reflectionClass = new ReflectionClass($this->getInputClass());

        $attributes = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $value = null;

            if ($property->isPublic()) {
                $value = $property->getValue($this->getInputObject());
            } else {
                $getter = $this->createGetter($property->getName());

                if ($reflectionClass->hasMethod($getter)) {
                    $value = $this->getInputObject()->$getter();
                }
            }

            if ($value) {
                $attributes[$property->getName()] = $value;
            }
        }

        return $attributes;
    }

    /**
     * @return string
     */
    protected function getInputClass() {
        return get_class($this->getInputObject());
    }

    /**
     * @return boolean
     */
    protected function isInputStandardClass() {
        return $this->getInputClass() === 'stdClass';
    }

}