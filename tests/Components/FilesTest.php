<?php

    use PHPUnit\Framework\TestCase;

    use \WOK\HttpMessage\Components\File;
    use \WOK\HttpMessage\Components\FilesCollection;

    class FilesTest extends TestCase {


        /**
         * Instanciate files collection
         * ---
        **/
        public function __construct() {

            $lists = require dirname(__DIR__).'/data/files.php';

            $this->ordered = $lists['ordered'];
            $this->unordered = $lists['unordered'];

            $this->files = new FilesCollection($this->unordered);

        }


        /**
         * Test reordering files in a cleanest way
         * ---
        **/
        public function testReordering() {

            $ordered = new FilesCollection($this->ordered);
            $this->assertEquals($ordered, $this->files, 'Files should be reordered on instanciation');

        }

        /**
         * Test collection methods
         * ---
        **/
        public function testCollectionMethods() {

            $this->assertTrue($this->files->has('multiple'));
            $this->assertInstanceOf(File::class, $this->files->get('single'));

        }


    }
