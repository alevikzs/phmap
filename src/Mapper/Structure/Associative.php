<?php

namespace PhMap\Mapper\Structure;

use \PhMap\Mapper\Structure;

/**
 * Class Associative
 * @package PhMap\Mapper\Structure
 */
class Associative extends Structure {

    /**
     * @param array $associativeArray
     * @param string $class
     * @param integer $adapter
     */
    public function __construct(array $associativeArray, $class, $adapter = self::MEMORY_ANNOTATION_ADAPTER) {
        parent::__construct($associativeArray, $class, $adapter);
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

}