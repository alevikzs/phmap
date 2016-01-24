<?php

namespace PhMap\Exception\FieldValidator;

use \PhMap\Exception\FieldValidator;

/**
 * Class MustBeObject
 * @package PhMap\Exception\FieldValidator
 */
class MustBeObject extends FieldValidator {

    /**
     * return string
     */
    protected function createMessage() {
        return 'Passed "' . $this->getField() . '" field of "' . $this->getClass() . '" class must be an object';
    }

}