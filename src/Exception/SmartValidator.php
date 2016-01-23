<?php

namespace PhMap\Exception;

use \PhMap\Exception;

/**
 * Class SmartValidator
 * @package PhMap\Exception
 */
class SmartValidator extends Exception {

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

    /**
     * @param mixed $value
     */
    public function __construct($value) {
        $this->setValue($value);

        $message = 'Value is unsupported. Must be json string, object or array; '
            . $this->getValueType() . ' given';

        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getValueType() {
        return gettype($this->getValue());
    }

}