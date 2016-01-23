<?php

namespace PhMap\Exception\FieldValidator;

use \PhMap\Exception\FieldValidator;

/**
 * Class MustBeSequence
 * @package PhMap\Exception\FieldValidator
 */
class MustBeSequence extends FieldValidator {

    /**
     * @param string $field
     * @param string $class
     */
    public function __construct($field, $class) {
        $this
            ->setField($field)
            ->setClass($class);

        $message = "'$field' field of '$class' class must be sequence";

        parent::__construct($message);
    }

}