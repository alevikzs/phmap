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
    private $json;

    /**
     * @return string
     */
    public function getJson() {
        return $this->json;
    }

    /**
     * @param string $json
     * @return $this
     */
    public function setJson($json) {
        $this->json = $json;

        return $this;
    }

    /**
     * @param string $json
     */
    public function __construct($json) {
        $this->setJson($json);

        $message = 'Json "' . $this->getJson() . '" is invalid';

        parent::__construct($message);
    }

}