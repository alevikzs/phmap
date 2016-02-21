<?php

namespace PhMap\Mapper\Structure;

use \PhMap\Mapper\Structure;

/**
 * Class Associative
 * @package PhMap\Mapper\Structure
 */
class Associative extends Structure {

    /**
     * @param array $array
     * @param string|object $outputClassOrObject
     * @param integer $adapter
     */
    public function __construct(
        array $array,
        $outputClassOrObject,
        $adapter = self::MEMORY_ANNOTATION_ADAPTER
    ) {
        parent::__construct($array, $outputClassOrObject, $adapter);
    }

    /**
     * @return array
     */
    public function getInputArray() {
        return $this->getInputStructure();
    }

    /**
     * @param array $array
     * @return $this
     */
    public function setInputArray(array $array) {
        return $this->setInputStructure($array);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    protected function isObject($value) {
        return is_array($value) && !$this->isSequential($value);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    protected function isSequential($value) {
        return array_keys($value) === range(0, count($value) - 1);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    protected function isStructure($value) {
        return is_array($value);
    }

    /**
     * @return array
     */
    protected function getInputAttributes() {
        return $this->getInputArray();
    }

}