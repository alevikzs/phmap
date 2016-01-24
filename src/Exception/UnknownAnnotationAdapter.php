<?php

namespace PhMap\Exception;

use \PhMap\Exception;

/**
 * Class UnknownAnnotationAdapter
 * @package PhMap\Exception
 */
class UnknownAnnotationAdapter extends Exception {

    public function __construct() {
        parent::__construct('Unknown annotation adapter');
    }

}