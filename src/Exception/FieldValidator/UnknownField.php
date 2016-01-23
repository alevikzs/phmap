<?php

namespace PhMap\Exception\FieldValidator;

use \PhMap\Exception\FieldValidator;

/**
 * Class UnknownField
 * @package PhMap\Exception\FieldValidator
 */
class UnknownField extends FieldValidator {

    /**
     * @param string $field
     * @param string $class
     */
    public function __construct($field, $class) {
        $this
            ->setField($field)
            ->setClass($class);

        $message = "'$class' hasn't '$field' field";

        parent::__construct($message);
    }

}