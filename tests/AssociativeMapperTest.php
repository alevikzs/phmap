<?php

namespace Tests;

use \Tests\Dummy\Tree,
    \Tests\Dummy\Branch,

    \PhMap\Mapper,
    \PhMap\Transform,
    \PhMap\Transforms,
    \PhMap\Mapper\Structure\Associative;

/**
 * Class AssociativeMapperTest
 * @package Tests
 */
class AssociativeMapperTest extends MapperTest {

    /**
     * @return array
     */
    private static function getTreeDecodedToArray() {
        return json_decode(self::getTreeJson(), true);
    }

    /**
     * @return array
     */
    protected static function getBranchDecodedToArray() {
        return json_decode(self::getBranchJson(), true);
    }

    /**
     * @return array
     */
    protected static function getTreeTransformedDecodedToArray() {
        return json_decode(self::getTreeTransformedJson(), true);
    }

    public function testMapperWithMemoryAdapter() {
        $class = '\Tests\Dummy\Tree';

        $objectMapped = (new Associative(self::getTreeDecodedToArray(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testMapperWithFilesAdapter() {
        $class = '\Tests\Dummy\Tree';

        $objectMapped = (new Associative(self::getTreeDecodedToArray(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testMapperWithInstances() {
        /** @var Tree $objectMapped */
        $objectMapped = (new Associative(self::getTreeDecodedToArray(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testReusable() {
        $mapper = new Associative(self::getTreeDecodedToArray(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);

        $objectMapped = $mapper->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = $mapper
            ->setInputArray(self::getBranchDecodedToArray())
            ->setOutputObject(new Branch())
            ->setAnnotationAdapterType(Mapper::MEMORY_ANNOTATION_ADAPTER)
            ->map();

        $this->assertEquals($objectMapped, self::getBranch());
    }

    public function testTransforms() {
        $inputStructure = self::getTreeTransformedDecodedToArray();

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

    public function testMustBeSimpleException() {
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

    public function testMustBeSequenceException() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSequence');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = [
            'leaves' => ['foo' => 1]
        ];

        (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeObjectException() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeObject');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = 1;

        (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testUnknownFieldException() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\UnknownField');

        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['foo'] = 1;

        (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testUnknownAdapter() {
        $this->setExpectedException(
            '\PhMap\Exception\UnknownAnnotationAdapter',
            'Unknown annotation adapter'
        );

        $class = '\Tests\Dummy\Tree';

        $adapter = 10;

        (new Associative(self::getTreeDecodedToArray(), $class, $adapter))
            ->map();
    }

    public function testDisableMustBeSimpleException() {
        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();
        $array['height'] = [
            'foo' => 1,
            'bar' => 2,
        ];

        $objectMapped = (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setHeight(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeSequenceException() {
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

    public function testDisableMustBeObjectException() {
        $class = '\Tests\Dummy\Tree';

        $array = self::getTreeDecodedToArray();

        $array['branch'] = 1;

        $objectMapped = (new Associative($array, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setBranch(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableUnknownFieldException() {
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