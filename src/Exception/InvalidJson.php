<?php

namespace PhMap\Exception;

use \PhMap\Exception;

/**
 * Class InvalidJson
 * @package PhMap\Exception
 */
class InvalidJson extends Exception {

    /**
     * @var string
     */
    private $string;

    /**
     * @return string
     */
    public function getString() {
        return $this->string;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function setString($string) {
        $this->string = $string;

        return $this;
    }

    /**
     * @param string $string
     */
    public function __construct($string) {
        $this->setString($string);

        $message = 'Json "' . $this->getString() . '" is invalid';

        parent::__construct($message);
    }

}