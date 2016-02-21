<?php

namespace Tests;

use \Tests\Dummy\Tree,

    \PhMap\Mapper;

/**
 * Class MapperTraitTest
 * @package Tests
 */
class MapperTraitTest extends MapperTest {

    public function testMain() {
        $objectMapped = Tree::staticMap(self::getTreeJson(), Mapper::FILES_ANNOTATION_ADAPTER);
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Tree())->map(self::getTreeJson(), Mapper::FILES_ANNOTATION_ADAPTER);
        $this->assertEquals($objectMapped, self::getTree());
    }

}