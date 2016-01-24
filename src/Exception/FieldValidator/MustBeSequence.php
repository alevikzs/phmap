<?php

namespace PhMap\Exception\FieldValidator;

use \PhMap\Exception\FieldValidator;

/**
 * Class MustBeSequence
 * @package PhMap\Exception\FieldValidator
 */
class MustBeSequence extends FieldValidator {

    /**
     * return string
     */
    protected function createMessage() {
        return 'Passed "' . $this->getField() . '" field of "' . $this->getClass() . '" class must be sequence';
    }

}