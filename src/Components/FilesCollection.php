<?php

    namespace WOK\HttpMessage\Components;

    use WOK\Collection\Collection;

    class FilesCollection extends Collection {


        /**
         * Instanciate files collection object
         * @param   array   $files      Files list

        **/
        public function __construct(array $files = array()) {

            // @see notes in <http://php.net/manual/fr/reserved.variables.files.php>
            /*

                $_FILES = [

                ]

            */
            $files = $this->_orderFilesList($files);

            parent::__construct($files);

        }

        /**
         * @note    This function reorder files list in a more usable one
        **/
        static public function createFromUnorderedList($files) {



            return self::__construct($files);

        }

        /**
         * Clone behavior
        **/
        public function __clone() {

            foreach($this->data as $key => $file) {
                $this->data[$key] = clone $file;
            }

        }


    }
