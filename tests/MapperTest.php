<?php

namespace Tests;

use \PHPUnit_Framework_TestCase as TestCase,

    \Tests\Dummy\Tree,
    \Tests\Dummy\TreeTransformed,
    \Tests\Dummy\Branch,
    \Tests\Dummy\BranchTransformed,
    \Tests\Dummy\Leaf;

/**
 * Class MapperTest
 * @abstract
 * @package Tests
 */
abstract class MapperTest extends TestCase {

    /**
     * @return Tree
     * @static
     */
    protected static function getTree() {
        return new Tree(2, 'Pear', self::getBranch());
    }

    /**
     * @return Branch
     * @static
     */
    protected static function getBranch() {
        return new Branch(1, [self::getLeaf(), self::getLeaf()]);
    }

    /**
     * @return TreeTransformed
     * @static
     */
    protected static function getTreeTransformed() {
        return new TreeTransformed(2, 'Pear', self::getBranchTransformed());
    }

    /**
     * @return BranchTransformed
     * @static
     */
    protected static function getBranchTransformed() {
        return new BranchTransformed(1, [self::getLeaf(), self::getLeaf()]);
    }

    /**
     * @return Leaf
     * @static
     */
    protected static function getLeaf() {
        return new Leaf(2, 3);
    }

    /**
     * @return string
     * @static
     */
    protected static function getTreeJson() {
        static $treeJson;

        if (!$treeJson) {
            $treeJson = json_encode(self::getTree());
        }

        return $treeJson;
    }

    /**
     * @return string
     * @static
     */
    protected static function getTreeTransformedJson() {
        static $treeJson;

        if (!$treeJson) {
            $treeJson = json_encode(self::getTreeTransformed());
        }

        return $treeJson;
    }

    /**
     * @return string
     * @static
     */
    protected static function getBranchJson() {
        static $branchJson;

        if (!$branchJson) {
            $branchJson = json_encode(self::getBranch());
        }

        return $branchJson;
    }

}