<?php

namespace Tests;

use \stdClass,
    \PHPUnit_Framework_TestCase as TestCase,

    \Tests\Dummy\Tree,
    \Tests\Dummy\Branch,
    \Tests\Dummy\Leaf,

    \PhMap\Mapper,
    \PhMap\Mapper\Json,
    \PhMap\Mapper\Smart,
    \PhMap\Mapper\Structure\Object,
    \PhMap\Mapper\Structure\Associative;

/**
 * Class MapperTest
 * @package Tests
 */
class MapperTest extends TestCase {

    /**
     * @return Tree
     */
    private static function getTree() {
        static $tree;

        if (!$tree) {
            $tree = new Tree(2, 'Pear', new Branch(1, [new Leaf(2, 3), new Leaf(1, 2)]));
        }

        return $tree;
    }

    /**
     * @return string
     */
    private static function getTreeJson() {
        static $value;

        if (!$value) {
            $value = json_encode(self::getTree());
        }

        return $value;
    }

    /**
     * @return stdClass
     */
    private static function getTreeDecodedToObject() {
        return json_decode(self::getTreeJson());
    }

    /**
     * @return array
     */
    private static function getTreeDecodedToArray() {
        return json_decode(self::getTreeJson(), true);
    }

    public function testMapperWithMemoryAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Object(self::getTreeDecodedToObject(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Associative(self::getTreeDecodedToArray(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testMapperWithFilesAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Object(self::getTreeDecodedToObject(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Associative(self::getTreeDecodedToArray(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testMapperWithApcAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), $class, Mapper::APC_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Object(self::getTreeDecodedToObject(), $class, Mapper::APC_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Associative(self::getTreeDecodedToArray(), $class, Mapper::APC_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testSmartMapper() {
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

    public function testMustBeSimpleExceptionForObjectStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSimple');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $height = new stdClass();
        $height->foo = 1;
        $height->bar = 2;

        $object->height = $height;

        (new Object($object, $class))
            ->map();
    }

    public function testMustBeSimpleExceptionForAssociativeStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSimple');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['height'] = [
            'foo' => 1,
            'bar' => 2
        ];

        (new Associative($array, $class))
            ->map();
    }

    public function testMustBeSequenceExceptionForObjectStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSequence');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch->leaves = new stdClass();

        (new Object($object, $class))
            ->map();
    }

    public function testMustBeSequenceExceptionForAssociativeStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSequence');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = [
            'leaves' => ['foo' => 1]
        ];

        (new Associative($array, $class))
            ->map();
    }

    public function testMustBeObjectExceptionForObjectStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeObject');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch = 1;

        (new Object($object, $class))
            ->map();
    }

    public function testMustBeObjectExceptionForAssociativeStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeObject');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = 1;

        (new Associative($array, $class))
            ->map();
    }

    public function testUnknownFieldExceptionForObjectStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\UnknownField');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->foo = 1;

        (new Object($object, $class))
            ->map();
    }

    public function testUnknownFieldExceptionForAssociativeStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\UnknownField');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['foo'] = 1;

        (new Associative($array, $class))
            ->map();
    }

    public function testInvalidJson() {
        $json = 1;

        $this->setExpectedException(
            '\PhMap\Exception\InvalidJson',
            'Json "' . $json . '" is invalid'
        );

        $class = '\Tests\Dummy\Tree';

        (new Json($json, $class))
            ->map();
    }

    public function testSmartUnsupportedValue() {
        $value = 1.1;

        $this->setExpectedException(
            '\PhMap\Exception\SmartValidator',
            'Value is unsupported. Must be json string, object or array; ' . gettype($value) . ' given'
        );

        $class = '\Tests\Dummy\Tree';

        (new Smart($value, $class))
            ->map();
    }

    public function testUnknownAdapter() {
        $this->setExpectedException(
            '\PhMap\Exception\UnknownAnnotationAdapter',
            'Unknown annotation adapter'
        );

        $class = '\Tests\Dummy\Tree';

        $adapter = 10;

        (new Object(self::getTreeDecodedToObject(), $class, $adapter))
            ->map();
    }

}