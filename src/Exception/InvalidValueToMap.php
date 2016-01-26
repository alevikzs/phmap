<?php

namespace PhMap\Exception;

use \PhMap\Exception;

/**
 * Class InvalidValueToMap
 * @package PhMap\Exception
 */
class InvalidValueToMap extends Exception {

    /**
     * @var mixed
     */
    private $value;

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    public function __construct($value) {
        $this->setValue($value);

        $message = 'Value "' . $this->getValue() . '" cannot be mapped. Must be an instance or valid class name';

        parent::__construct($message);
    }

}