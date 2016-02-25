<?php

namespace Tests;

use \stdClass,

    \Tests\Dummy\Tree,
    \Tests\Dummy\Branch,

    \PhMap\Mapper,
    \PhMap\Transform,
    \PhMap\Transforms,
    \PhMap\Mapper\Structure\Object;

/**
 * Class ObjectMapperTest
 * @package Tests
 */
class ObjectMapperTest extends MapperTest {

    /**
     * @return stdClass
     */
    protected static function getTreeDecodedToObject() {
        return json_decode(self::getTreeJson());
    }

    /**
     * @return stdClass
     */
    protected static function getTreeTransformedDecodedToObject() {
        return json_decode(self::getTreeTransformedJson());
    }

    /**
     * @return stdClass
     */
    protected static function getBranchDecodedToObject() {
        return json_decode(self::getBranchJson());
    }

    public function testWithMemoryAdapter() {
        $class = '\Tests\Dummy\Tree';

        $objectMapped = (new Object(self::getTreeDecodedToObject(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testWithFilesAdapter() {
        $class = '\Tests\Dummy\Tree';

        $objectMapped = (new Object(self::getTreeDecodedToObject(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testWithInstance() {
        $objectMapped = (new Object(self::getTreeDecodedToObject(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testReusable() {
        $mapper = new Object(self::getTreeDecodedToObject(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);

        $objectMapped = $mapper->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = $mapper
            ->setInputObject(self::getBranchDecodedToObject())
            ->setOutputObject(new Branch())
            ->setAnnotationAdapterType(Mapper::MEMORY_ANNOTATION_ADAPTER)
            ->map();

        $this->assertEquals($objectMapped, self::getBranch());
    }

    public function testMappingObjectWithPrivateFields() {
        $objectMapped = (new Object(self::getTree(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testTransforms() {
        $inputStructure = self::getTreeTransformed();

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

        $objectMapped = (new Object($inputStructure, new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->setTransforms($transforms)
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testMustBeSimpleException() {
        $class = '\Tests\Dummy\Tree';

        $this->setExpectedException(
            '\PhMap\Exception\FieldValidator\MustBeSimple',
            'Passed "height" field of "' . $class . '" class must be a simple type'
        );

        $object = self::getTreeDecodedToObject();

        $height = new stdClass();
        $height->foo = 1;
        $height->bar = 2;

        $object->height = $height;

        (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeSequenceException() {
        $class = '\Tests\Dummy\Tree';

        $this->setExpectedException(
            '\PhMap\Exception\FieldValidator\MustBeSequence',
            'Passed "leaves" field of "\Tests\Dummy\Branch" class must be sequence'
        );

        $object = self::getTreeDecodedToObject();

        $object->branch->leaves = new stdClass();

        (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeObjectException() {
        $class = '\Tests\Dummy\Tree';

        $this->setExpectedException(
            '\PhMap\Exception\FieldValidator\MustBeObject',
            'Passed "branch" field of "' . $class . '" class must be an object'
        );

        $object = self::getTreeDecodedToObject();

        $object->branch = 1;

        (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testUnknownFieldException() {
        $class = '\Tests\Dummy\Tree';

        $this->setExpectedException(
            '\PhMap\Exception\FieldValidator\UnknownField',
            '"' . $class . '" class has not a "foo" field'
        );

        $object = self::getTreeDecodedToObject();

        $object->foo = 1;

        (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
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

    public function testDisableMustBeSimpleException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $height = new stdClass();
        $height->foo = 1;
        $height->bar = 2;

        $object->height = $height;

        $objectMapped = (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->disableValidation()
            ->map();
        $objectExpected = self::getTree();
        $objectExpected->setHeight(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeSequenceException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch->leaves = new stdClass();

        $objectMapped = (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->disableValidation()
            ->map();
        $objectExpected = self::getTree();
        $objectExpected->getBranch()->setLeaves();
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeObjectException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch = 1;

        $objectMapped = (new Object($object, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->disableValidation()
            ->map();
        $objectExpected = self::getTree();
        $objectExpected->setBranch(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableUnknownFieldException() {
        $class = '\Tests\Dummy\Tree';

        $inputObject = self::getTreeTransformedDecodedToObject();

        $objectMapped = (new Object($inputObject, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->disableValidation()
            ->map();
        $objectExpected = self::getTree()->setBranch(null)->setName(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

}