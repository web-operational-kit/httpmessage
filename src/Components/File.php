<?php

    namespace WOK\HttpMessage\Components;

    class File {

        /**
         * @var   string            $path        Uploaded file path
        **/
        protected $path = array();

        /**
         * @var   null|int          $size         File client size
        **/
        protected $size = null;

        /**
         * @var   string            $type         File client media type
        **/
        protected $type = null;

        /**
         * @var   string            $type         File client media type
        **/
        // protected $type = null;


        /**
         * Instanciate a file object
         * @param   array       $file          File meta data
        **/
        public function __construct($path, $name, $type = null, $size = null, $error = null) {

            $this->path     = $path;
            $this->name     = $name;
            $this->type     = $type;
            $this->size     = ($size ?: null);
            $this->error    = ($error ?: null);

        }


        /**
         * Retrieve the error associated to the file
         * @return  integer     Returns one of the UPLOAD_ERR_XXX constants
        **/
        public function getError() {

            return $this->file['error'];

        }


        public function getClientFilename() {

            return $this->file['name'];

        }


        public function getClientMediaType() {

            return $this->file['type'];

        }


        public function getClientSize() {

            return $this->file['size'];

        }


    }
