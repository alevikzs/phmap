<?php

namespace Tests;

use \Tests\Dummy\Tree,

    \PhMap\Mapper;

/**
 * Class MapperTraitTest
 * @package Tests
 */
class MapperTraitTest extends MapperTest {

    /**
     * @return array
     * @static
     */
    private static function getTreeDecodedToArray() {
        return json_decode(self::getTreeJson(), true);
    }

    /**
     * @return void
     */
    public function testMain() {
        $objectMapped = Tree::staticMapper(self::getTreeJson(), Mapper::FILES_ANNOTATION_ADAPTER)
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = Tree::staticMapper(self::getTreeDecodedToArray(), Mapper::FILES_ANNOTATION_ADAPTER)
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Tree())->mapper(self::getTreeJson(), Mapper::FILES_ANNOTATION_ADAPTER)
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

}