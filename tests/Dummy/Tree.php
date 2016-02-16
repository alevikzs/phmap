<?php

namespace Tests\Dummy;

use \JsonSerializable,

    \PhMap\MapperTrait;

/**
 * Class Tree
 * @package Tests\Dummy
 */
class Tree implements JsonSerializable {

    use MapperTrait;

    /**
     * @var double
     */
    private $height;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Branch
     */
    private $branch;

    /**
     * @var Branch
     */
    private $something;

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
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return Branch
     */
    public function getBranch() {
        return $this->branch;
    }

    /**
     * @param Branch $branch
     * @mapper(class="\Tests\Dummy\Branch")
     */
    public function setBranch(Branch $branch) {
        $this->branch = $branch;
    }

    /**
     * @param double $height
     * @param string $name
     * @param Branch $branch
     */
    public function __construct($height = null, $name = null, Branch $branch = null) {
        $this->height = $height;
        $this->name = $name;
        $this->branch = $branch;
        $this->something = 'something';
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'name' => $this->getName(),
            'height' => $this->getHeight(),
            'branch' => $this->getBranch()
        ];
    }

}