<?php

namespace PhMap\Exception;

use \PhMap\Exception;

/**
 * Class FieldValidator
 * @package PhMap\Exception
 */
abstract class FieldValidator extends Exception {

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $class;

    /**
     * @return string
     */
    public function getField() {
        return $this->field;
    }

    /**
     * @param string $field
     * @return $this
     */
    public function setField($field) {
        $this->field = $field;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass() {
        return $this->field;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass($class) {
        $this->class = $class;

        return $this;
    }

}