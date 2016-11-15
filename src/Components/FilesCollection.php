<?php

    namespace WOK\HttpMessage\Components;

    use WOK\Collection\Collection;

    class FilesCollection extends Collection {


        /**
         * Instanciate files collection object
         * @param   array   $files      Files list

        **/
        public function __construct(array $files = array()) {

            $list = array();
            foreach($files as $key => $file) {

                // Multiple unordered files
                if(isset($file['name']) && is_array($file['name'])) {

                    $list[$key] = array();
                    foreach($file['name'] as $i => $value) {

                        $list[$key][] = new File(
                            $files[$key]['tmp_name'][$i],
                            $files[$key]['name'][$i],
                            $files[$key]['type'][$i],
                            $files[$key]['size'][$i],
                            $files[$key]['error'][$i]
                        );

                    }

                }

                // Already ordered multiple files (supposed)
                elseif(!isset($file['name'])) {

                    $list[$key] = array();
                    foreach($file as $item) {

                        $list[$key][] = new File(
                            $item['tmp_name'],
                            $item['name'],
                            $item['type'],
                            $item['size'],
                            $item['error']
                        );

                    }

                }

                // Standalone file
                else {

                    $list[$key] = new File(
                        $file['tmp_name'], $file['name'], $file['type'], $file['size'], $file['error']
                    );

                }

            }

            parent::__construct($list);

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
