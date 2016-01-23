<?php

namespace PhMap\Mapper\Structure;

use \stdClass,

    \PhMap\Mapper\Structure;

/**
 * Class Object
 * @package PhMap\Mapper\Structure
 */
class Object extends Structure {

    /**
     * @param stdClass $object
     * @param string $class
     */
    public function __construct(stdClass $object, $class) {
        parent::__construct($object, $class);
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