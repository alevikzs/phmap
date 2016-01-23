<?php

namespace Tests\Dummy;

use \JsonSerializable;

/**
 * Class Leaf
 * @package Tests\Dummy
 */
class Leaf implements JsonSerializable {

    /**
     * @var double
     */
    private $height;

    /**
     * @var double
     */
    private $width;

    /**
     * @return float
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height) {
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     * @param double $height
     * @param double $width
     */
    public function __construct($height = null, $width = null) {
        $this->height = $height;
        $this->width = $width;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'height' => $this->getHeight(),
            'width' => $this->getWidth()
        ];
    }

}