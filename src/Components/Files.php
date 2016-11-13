<?php

    namespace WOK\HttpMessage\Components;

    use WOK\Collection\Collection;
    use Psr\Http\Message\UploadedFileInterface;

    class Files extends Collection implements \UploadedFileInterface {


        /**
         * Instanciate files collection object
         * @param   array   $files      Files list from $_FILES
        **/
        public function __construct(array $files = array()) {

            parent::__construct($files);

        }


        /**
         * Check a file data
         * @param   string      $name       File name
        **/
        public function getFile($name) {

            if(!$this->hasFile($name)) {
                throw new \DomainException('File '.$name.' has not been defined');
            }

            return $this->get($name);

        }


        /**
         * Check if a file is available
         * @param   string      $name       File name
        **/
        public function hasFile($name) {
            return $this->has($name);
        }


        /**
         * Check if a file has been uploaded without error
         * @param   string      $name       File name
        **/
        public function isFileUploaded($name) {

            if(!$this->hasFile($name)) {
                return false;
            }

            $file = $this->getFile($name);

            if(is_uploaded_file($file['tmp_name'])) {
                return true;
            }
            elseif($file['error'] != 0) {
                return false;
            }

            return true;

        }


    }
