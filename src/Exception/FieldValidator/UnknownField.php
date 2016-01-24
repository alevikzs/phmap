<?php

namespace PhMap\Exception\FieldValidator;

use \PhMap\Exception\FieldValidator;

/**
 * Class UnknownField
 * @package PhMap\Exception\FieldValidator
 */
class UnknownField extends FieldValidator {

    /**
     * return string
     */
    protected function createMessage() {
        return '"' . $this->getClass() . '" class has not a "' . $this->getField() . '" field';
    }

}