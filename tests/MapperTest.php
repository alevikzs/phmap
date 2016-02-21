<?php

namespace Tests;

use \stdClass,
    \PHPUnit_Framework_TestCase as TestCase,

    \Tests\Dummy\Tree,
    \Tests\Dummy\TreeTransformed,
    \Tests\Dummy\Branch,
    \Tests\Dummy\BranchTransformed,
    \Tests\Dummy\Leaf;

/**
 * Class MapperTest
 * @package Tests
 */
abstract class MapperTest extends TestCase {

    /**
     * @return Tree
     */
    protected static function getTree() {
        static $tree;

        if (!$tree) {
            $tree = new Tree(2, 'Pear', self::getBranch());
        }

        return clone $tree;
    }

    /**
     * @return Branch
     */
    protected static function getBranch() {
        static $branch;

        if (!$branch) {
            $branch = new Branch(1, [self::getLeaf(), self::getLeaf()]);
        }

        return clone $branch;
    }

    /**
     * @return TreeTransformed
     */
    protected static function getTreeTransformed() {
        static $tree;

        if (!$tree) {
            $tree = new TreeTransformed(2, 'Pear', self::getBranchTransformed());
        }

        return clone $tree;
    }

    /**
     * @return BranchTransformed
     */
    protected static function getBranchTransformed() {
        static $branch;

        if (!$branch) {
            $branch = new BranchTransformed(1, [self::getLeaf(), self::getLeaf()]);
        }

        return clone $branch;
    }

    /**
     * @return Leaf
     */
    protected static function getLeaf() {
        static $leaf;

        if (!$leaf) {
            $leaf = new Leaf(2, 3);
        }

        return clone $leaf;
    }

    /**
     * @return string
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
     */
    protected static function getBranchJson() {
        static $branchJson;

        if (!$branchJson) {
            $branchJson = json_encode(self::getBranch());
        }

        return $branchJson;
    }

}