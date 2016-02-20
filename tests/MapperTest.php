<?php

namespace Tests;

use \stdClass,
    \PHPUnit_Framework_TestCase as TestCase,

    \Tests\Dummy\Tree,
    \Tests\Dummy\Branch,
    \Tests\Dummy\Leaf,

    \PhMap\Mapper,
    \PhMap\Transform,
    \PhMap\Transforms,
    \PhMap\Wrapper\Json,
    \PhMap\Wrapper\Smart,
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
            $tree = new Tree(2, 'Pear', self::getBranch());
        }

        return clone $tree;
    }

    /**
     * @return Branch
     */
    private static function getBranch() {
        static $branch;

        if (!$branch) {
            $branch = new Branch(1, [self::getLeaf(), self::getLeaf()]);
        }

        return clone $branch;
    }

    /**
     * @return Branch
     */
    private static function getLeaf() {
        static $leaf;

        if (!$leaf) {
            $leaf = new Leaf(2, 3);
        }

        return clone $leaf;
    }

    /**
     * @return string
     */
    private static function getTreeJson() {
        static $treeJson;

        if (!$treeJson) {
            $treeJson = json_encode(self::getTree());
        }

        return $treeJson;
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

    /**
     * @return string
     */
    private static function getBranchJson() {
        static $branchJson;

        if (!$branchJson) {
            $branchJson = json_encode(self::getBranch());
        }

        return $branchJson;
    }

    /**
     * @return stdClass
     */
    private static function getBranchDecodedToObject() {
        return json_decode(self::getBranchJson());
    }

    /**
     * @return array
     */
    private static function getBranchDecodedToArray() {
        return json_decode(self::getBranchJson(), true);
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

    public function testMapperWithInstances() {
        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Object(self::getTreeDecodedToObject(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Associative(self::getTreeDecodedToArray(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testSmartMapper() {
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

    public function testMapperTrait() {
        $objectMapped = Tree::staticMap(self::getTreeJson(), Mapper::FILES_ANNOTATION_ADAPTER);
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = (new Tree())->map(self::getTreeJson(), Mapper::FILES_ANNOTATION_ADAPTER);
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testReusable() {
        $mapper = new Json(self::getTreeJson(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);

        $objectMapped = $mapper->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = $mapper
            ->setInputJsonString(self::getBranchJson())
            ->setOutputObject(new Branch())
            ->map();
        $this->assertEquals($objectMapped, self::getBranch());

        $mapper = new Object(self::getTreeDecodedToObject(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);

        $objectMapped = $mapper->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = $mapper
            ->setInputObject(self::getBranchDecodedToObject())
            ->setOutputObject(new Branch())
            ->map();

        $this->assertEquals($objectMapped, self::getBranch());

        $mapper = new Associative(self::getTreeDecodedToArray(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);

        $objectMapped = $mapper->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = $mapper
            ->setInputArray(self::getBranchDecodedToArray())
            ->setOutputObject(new Branch())
            ->map();

        $this->assertEquals($objectMapped, self::getBranch());

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

    public function testMappingObjectWithPrivateFields() {
        $objectMapped = (new Object(self::getTree(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testTransforms() {
        $inputStructure = self::getTreeDecodedToArray();
        $inputStructure['nameTransformed'] = $inputStructure['name'];
        unset($inputStructure['name']);
        $inputStructure['branch']['leavesTransformed'] = $inputStructure['branch']['leaves'];
        unset($inputStructure['branch']['leaves']);
        $inputStructure['branchTransformed'] = $inputStructure['branch'];
        unset($inputStructure['branch']);

        $transforms = (new Transforms())
            ->add(
                (new Transform())->setInputFieldName('nameTransformed')->setOutputFieldName('name')
            )
            ->add(
                (new Transform())->setInputFieldName('branchTransformed')->setOutputFieldName('branch')
                    ->setTransforms(
                        (new Transforms())->add(
                            (new Transform())->setInputFieldName('leavesTransformed')->setOutputFieldName('leaves')
                        )
                    )
            );

        $objectMapped = (new Associative($inputStructure, new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map($transforms);
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

        (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
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

        (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeSequenceExceptionForObjectStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSequence');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch->leaves = new stdClass();

        (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeSequenceExceptionForAssociativeStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSequence');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = [
            'leaves' => ['foo' => 1]
        ];

        (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeObjectExceptionForObjectStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeObject');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch = 1;

        (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeObjectExceptionForAssociativeStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeObject');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = 1;

        (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testUnknownFieldExceptionForObjectStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\UnknownField');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->foo = 1;

        (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testUnknownFieldExceptionForAssociativeStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\UnknownField');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['foo'] = 1;

        (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testInvalidJson() {
        $json = 1;

        $this->setExpectedException(
            '\PhMap\Exception\InvalidJson',
            'Json "' . $json . '" is invalid'
        );

        $class = '\Tests\Dummy\Tree';

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
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

    public function testDisableMustBeSimpleExceptionForObjectStructure() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $height = new stdClass();
        $height->foo = 1;
        $height->bar = 2;

        $object->height = $height;

        $objectMapped = (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setHeight(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeSimpleExceptionForAssociativeStructure() {
        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['height'] = [
            'foo' => 1,
            'bar' => 2
        ];

        $objectMapped = (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setHeight(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeSequenceExceptionForObjectStructure() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch->leaves = new stdClass();

        $objectMapped = (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->getBranch()->setLeaves([]);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeSequenceExceptionForAssociativeStructure() {
        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = [
            'leaves' => ['foo' => 1]
        ];

        $objectMapped = (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setBranch(new Branch());
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeObjectExceptionForObjectStructure() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch = 1;

        $objectMapped = (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setBranch(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeObjectExceptionForAssociativeStructure() {
        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = 1;

        $objectMapped = (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setBranch(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableUnknownFieldExceptionForAssociativeStructure() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\UnknownField');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['foo'] = 1;

        $objectMapped = (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $objectExpected = self::getTree();
        $this->assertEquals($objectMapped, $objectExpected);
    }

}