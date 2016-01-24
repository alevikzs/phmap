<?php

namespace PhMap\Exception\FieldValidator;

use \PhMap\Exception\FieldValidator;

/**
 * Class MustBeSimple
 * @package PhMap\Exception\FieldValidator
 */
class MustBeSimple extends FieldValidator {

    /**
     * return string
     */
    protected function createMessage() {
        return 'Passed "' . $this->getField() . '" field of "' . $this->getClass() . '" class must be a simple type';
    }

}