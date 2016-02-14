<?php

namespace PhMap\Mapper\Structure;

use \PhMap\Mapper\Structure;

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
    protected function getInputObject() {
        return $this->getInputStructure();
    }

    /**
     * @param object $object
     * @return $this
     */
    protected function setInputObject($object) {
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

}