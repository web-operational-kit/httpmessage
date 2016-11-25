<?php

    /**
     * Web Operational Kit
     * The neither huger nor micro humble framework
     *
     * @copyright   All rights reserved 2015, Sébastien ALEXANDRE <sebastien@graphidev.fr>
     * @author      Sébastien ALEXANDRE <sebastien@graphidev.fr>
     * @license     BSD <license.txt>
    **/

    namespace WOK\HttpMessage\Components;

    use \WOK\Collection\Collection;

    /**
     * The Headers class provide a headers
     * collection manager interface.
    **/
    class Headers extends Collection {


        /**
         * Instanciate a Headers collection
         * @param array     $headers        Initial headers collection
        **/
        public function __construct(array $headers = array()) {

            foreach($headers as $name => $value) {
                $this->addHeader($name, $value);
            }

            $this->rewind();

        }

        /**
         * Get a header splitted value
         * @param   string    $name       Header name (insensitive)
         * @param   mixed     $default    Default header value
         * @return  string|mixed          Returns the header value
        **/
        public function getHeader($name, $default = false) {

            $name = $this->_getCaseInsensitiveName($name);

            if(!$this->has($name)) {
                return $default;
            }

            return $this->get($name);

        }


        /**
         * Get a header line value
         * @param   string    $name       Header name (insensitive)
         * @param   string    $default    Default header value
         * @return  string    Returns the header value
        **/
        public function getHeaderLine($name, $default = false) {

            return $this->getHeader($name, $default);

        }


        /**
         * Get a header array values (not ordered)
         * @param       string          $name       Header's name
         * @param       array           $default       Header's default values
         * @return      array           Returns values as an array or false if
         *                                  the header has not been defined.
        **/
        public function getHeaderValues($name, array $default = array()) {

            $values = $this->getHeaderLine($name, $default);

            if($values === $default)
                return $default;

            return array_map('trim', explode(',', $values));

        }


        /**
         * Get a header ordered values within an array
         * @param       string          $name       Header's name
         * @param       array           $default       Header's default values
         * @return      array|false     Returns values collection as an array
         *                                  or false if the header has not been
         *                                  defined.
        **/
        public function getHeaderOrderedValues($name, array $default = array()) {

            $values = $this->getHeaderValues($name, $default);

            if($values === $default)
                return $default;

            $quantified = array();
            foreach($values as $index => $item) {

                $qvalue = 1;
                if(($qpos = mb_strpos($item, $prefix = ';q=')) !== false) {

                    $qvalue         = mb_substr($item, $qpos + mb_strlen($prefix));
                    $item           = mb_substr($item, 0, $qpos); // Remove quality string

                    $values[$index] = $item;

                }
                $quantified[$item] = $qvalue;

            }

            uasort($values, function($a, $b) use($quantified) {
                return ($quantified[$a] > $quantified[$b] ? -1 : 1);
            });

            // Return reindexed values
            return array_values($values);

        }


        /**
         * Check if a header exists
         * @return bool     Returns wether the header exists or not
        **/
        public function hasHeader($name) {

            $name = $this->_getCaseInsensitiveName($name);

            return $this->has($name);

        }


        /**
         * Assign an all new header's value
         * @param   string              $name              Header's name
         * @param   string|array        $values            Header's values
        **/
        public function setHeader($name, $values) {

            $name   = $this->_getCaseInsensitiveName($name);

            if(is_array($values))
                $values = implode(', ', $values);

            $this->set($name, $values);

        }


        /**
         * Add a new header's value
         * @param   string              $name              Header's name
         * @param   string|array        $value             Header's values
        **/
        public function addHeader($name, $values) {

            $name = $this->_getCaseInsensitiveName($name);

            if(is_array($values))
                $values = implode(', ', $values);

            if($source = $this->getHeader($name)) {
                $values = $source.', '.$values;
            }

            $this->set($name, $values);

        }


        /**
         * Get a headers collection object copy with the new header definition.
         * @param   string      $name       Header's name
         * @return  self
        **/
        public function withHeader($name, $value) {

            $headers = clone $this;
            $headers->setHeader($name, $value);

            return $headers;

        }

        /**
         * Get a new instance of the collection object with an added header value.
         * @param   string      $name       Header's name
         * @return  self
        **/
        public function withAddedHeader($name, $value) {

            $headers = clone $this;
            $headers->addHeader($name, $value);

            return $headers;

        }


        /**
         * Remove a defined header
         * @param   string              $name              Header's name
        **/
        public function removeHeader($name) {

            $name = $this->_getCaseInsensitiveName($name);
            $this->remove($name);

        }


        /**
         * Get a new instance of the collection object
         * without a defined header.
         * @param   string      $name       Header's name
         * @return  self
        **/
        public function withoutHeader($name, $value) {

            $name = $this->_getCaseInsensitiveName($name);

            $headers = clone $this;
            $headers->remove($name, $value);

            return $headers;

        }


        /**
         * Get a case insensitive header name
         *
         * @param   string      $name           Headers case sensitive name
         * @return  string      Case insensitive header name
        **/
        protected function _getCaseInsensitiveName($name) {

            return str_replace('_', '-', mb_strtolower($name));

        }

    }
