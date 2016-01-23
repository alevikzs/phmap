<?php

namespace PhMap\Exception\FieldValidator;

use \PhMap\Exception\FieldValidator;

/**
 * Class MustBeSimple
 * @package PhMap\Exception\FieldValidator
 */
class MustBeSimple extends FieldValidator {

    /**
     * @param string $field
     * @param string $class
     */
    public function __construct($field, $class) {
        $this
            ->setField($field)
            ->setClass($class);

        $message = "'$field' field of '$class' class must be a simple type";

        parent::__construct($message);
    }

}