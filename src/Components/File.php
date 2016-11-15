<?php

    namespace WOK\HttpMessage\Components;

    use Psr\Http\Message\UploadedFileInterface;

    class File implements \UploadedFileInterface {


        /**
         * @var     array       $file       File meta data
        **/
        protected $file = array();


        /**
         * Instanciate a file object
         * @param   array       $file          File meta data
        **/
        public function __construct(array $file) {

            $this->file = $file;

        }

    }
