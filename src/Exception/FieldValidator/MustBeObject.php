<?php

namespace PhMap\Exception\FieldValidator;

use \PhMap\Exception\FieldValidator;

/**
 * Class MustBeObject
 * @package PhMap\Exception\FieldValidator
 */
class MustBeObject extends FieldValidator {

    /**
     * @param string $field
     * @param string $class
     */
    public function __construct($field, $class) {
        $this
            ->setField($field)
            ->setClass($class);

        $message = "'$field' field of '$class' class must be an object";

        parent::__construct($message);
    }

}