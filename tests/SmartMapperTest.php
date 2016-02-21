<?php

namespace Tests;

use \stdClass,

    \Tests\Dummy\Tree,
    \Tests\Dummy\Branch,

    \PhMap\Mapper,
    \PhMap\Wrapper\Smart;

/**
 * Class SmartMapperTest
 * @package Tests
 */
class SmartMapperTest extends MapperTest {

    /**
     * @return stdClass
     */
    protected static function getTreeDecodedToObject() {
        return json_decode(self::getTreeJson());
    }

    /**
     * @return array
     */
    private static function getTreeDecodedToArray() {
        return json_decode(self::getTreeJson(), true);
    }

    public function testWithMemoryAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Smart(self::getTreeJson(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Smart(self::getTreeDecodedToObject(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Smart(self::getTreeDecodedToArray(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testWithFileAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Smart(self::getTreeJson(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Smart(self::getTreeDecodedToObject(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Smart(self::getTreeDecodedToArray(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testReusable() {
        $mapper = new Smart(self::getTreeDecodedToArray(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);

        $objectMapped = $mapper->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = $mapper
            ->setInputValue(self::getBranchJson())
            ->setOutputObject(new Branch())
            ->setAnnotationAdapterType(Mapper::MEMORY_ANNOTATION_ADAPTER)
            ->map();

        $this->assertEquals($objectMapped, self::getBranch());

        $objectMapped = $mapper
            ->setInputValue(self::getTreeDecodedToObject())
            ->setOutputObject(new Tree())
            ->setAnnotationAdapterType(Mapper::FILES_ANNOTATION_ADAPTER)
            ->map();

        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testSmartUnsupportedValue() {
        $value = 1.1;

        $this->setExpectedException(
            '\PhMap\Exception\SmartValidator',
            'Value is unsupported. Must be json string, object or array; ' . gettype($value) . ' given'
        );

        $class = '\Tests\Dummy\Tree';

        (new Smart($value, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

}