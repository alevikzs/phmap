#PhMap#

**Build Status:** [![Build Status](https://secure.travis-ci.org/alevikzs/phmap.png?branch=master)](http://travis-ci.org/alevikzs/phmap)

##About##

The PhMap is a PHP package for creating objects from JSON strings, associative arrays and other objects. The PhMap is 
based on [phalcon annotations](https://docs.phalconphp.com/en/latest/reference/annotations.html).

##Requirements##

* PHP >= 5.4 && < 7.0;
* [Phalcon framework](https://phalconphp.com) >= 2.0;
* If you will be using [APC](http://php.net/manual/en/book.apc.php) or [XCache](https://xcache.lighttpd.net/) adapters you need to install corresponding PHP extensions.

##Installation##

1. Require the package and its dependencies with composer: ```$ composer require alevikzs/phmap```
2. Install [Phalcon framework](https://phalconphp.com). Detail guide is [here](https://phalconphp.com/en/download).

##Examples##

```php
class Tree {

    private $height;
    private $name;
    private $branch;

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getBranch() {
        return $this->branch;
    }

    /**
     * @mapper(class="\Tests\Dummy\Branch")
     */
    public function setBranch(Branch $branch) {
        $this->branch = $branch;
    }
    
}
```

As you can see, if some property of you object has type of another class - you must declare an annotation ```@mapper``` 
for setter method of this property. This annotation has two arguments: ```class``` and ```isArray```. The first 
argument is a string with the class name and second is a boolean value that indicates whether your property value is an 
array or not.

```php
class Branch {

    private $length;
    private $leaves;
    
    public function getLength() {
        return $this->length;
    }

    public function setLength($length) {
        $this->length = $length;
    }

    public function getLeaves() {
        return $this->leaves;
    }

    /**
     * @mapper(class="\Tests\Dummy\Leaf", isArray=true)
     */
    public function setLeaves(array $leaves) {
        $this->leaves = $leaves;
    }

}

class Leaf {

    private $height;
    private $width;
    
    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

}
```

Create object of Tree class from JSON string:

```php
$result = (new \PhMap\Mapper\Json($json, 'Tree'))->map();
```

Create object of Tree class from associative array:

```php
$result = (new \PhMap\Mapper\Structure\Associative($array, 'Tree'))->map();
```

Create object of Tree class from another object:

```php
$result = (new \PhMap\Mapper\Structure\Object($object, 'Tree'))->map();
```

You can use ```\PhMap\Mapper\Smart``` if you don't know what type of you value. In this case mapping instructions 
will be applied automatically:

```php
$result = (new \PhMap\Mapper\Smart($value, 'Tree'))->map();
```

By default mapper use [memory adapter](https://docs.phalconphp.com/en/latest/api/Phalcon_Annotations_Adapter_Memory.html),
but also you can use [file adapter](https://docs.phalconphp.com/en/latest/api/Phalcon_Annotations_Adapter_Files.html),
[APC adapter](https://docs.phalconphp.com/en/latest/api/Phalcon_Annotations_Adapter_Apc.html) and
[XCache adapter](https://docs.phalconphp.com/en/latest/api/Phalcon_Annotations_Adapter_Xcache.html):

```php
new \PhMap\Mapper\Json($json, 'Tree', \PhMap\Mapper::MEMORY_ANNOTATION_ADAPTER);

new \PhMap\Mapper\Smart($json, 'Tree', \PhMap\Mapper::FILES_ANNOTATION_ADAPTER);

new \PhMap\Mapper\Structure\Associative($array, 'Tree', \PhMap\Mapper::APC_ANNOTATION_ADAPTER);

new \PhMap\Mapper\Structure\Object($object, 'Tree', \PhMap\Mapper::X_CACHE_ANNOTATION_ADAPTER);
```

Also, you can pass already existing object to the constructor:

```php
$tree = new Tree();

$result = (new \PhMap\Mapper\Smart($json, $tree))->map();
```

You can reuse mapper object. Just set the necessary properties, and call the method ```map()```:

```php
$mapper = new \PhMap\Mapper\Structure\Object($tree, 'Tree', \PhMap\Mapper::X_CACHE_ANNOTATION_ADAPTER)
$result = $mapper->map();

$mapper->setInputObject($branch)
    ->setOutputObject(new Branch())
    ->setAnnotationAdapterType(Mapper::MEMORY_ANNOTATION_ADAPTER);
$result = $mapper->map();
```

```map()``` method has two arguments. The first argument is a transforms object. This object is used to declare a set 
of rules in where each rule indicates what field of input value is correspond to the output field value. The second 
argument you can use for skip validation:

```php
$mapper = new \PhMap\Mapper\Structure\Object($tree, 'Tree')

$transforms = (new \PhMap\Transforms())
    ->add(
        (new \PhMap\Transform())
            ->setInputFieldName('nameIn')
            ->setOutputFieldName('name')
    )
    ->add(
        (new \PhMap\Transform())
            ->setInputFieldName('branchIn')
            ->setOutputFieldName('branch')
            ->setTransforms(
                (new \PhMap\Transforms())->add(
                    (new \PhMap\Transform())
                        ->setInputFieldName('leavesIn')
                        ->setOutputFieldName('leaves')
                )
            )
    );

$validation = false;

$result = $mapper->map($transforms, $validation);
```

If you want your class can map some value to itself you must use MapperTrait in your class declaration:

```php
class Tree {

   use \PhMap\MapperTrait;
   
   //other class declaration
   
}
```

and then you can call ```map()``` or ```staticMap()``` methods:

```php
$tree = new Tree();
$result = $tree->map($json);

$result = Tree::staticMap($json);
```

##The MIT License (MIT)##

**Copyright (c) 2016 Alexey Novikov <alekseeey@gmail.com>**

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.