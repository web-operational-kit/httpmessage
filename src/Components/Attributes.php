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
     * The Attributes class provide an attributes
     * collection manager interface.
    **/
    class Attributes extends Collection {


        /**
         * Instanciate a Attributes collection
         * @param array     $attributes        Initial attributes collection
        **/
        public function __construct(array $attributes = array()) {

            foreach($attributes as $name => $value) {
                $this->setAttribute($name, $value);
            }

            $this->rewind();

        }

        /**
         * Get a attribute splitted value
         * @param   string    $name       Attribute name (insensitive)
         * @param   mixed     $default    Default attribute value
         * @return  string|mixed          Returns the attribute value
        **/
        public function getAttribute($name, $default = false) {

            if(!$this->has($name)) {
                return $default;
            }

            return $this->get($name);

        }


        /**
         * Check if a attribute exists
         * @return bool     Returns wether the attribute exists or not
        **/
        public function hasAttribute($name) {

            return $this->has($name);

        }


        /**
         * Assign an all new attribute's value
         * @param   string              $name              Attribute's name
         * @param   string|array        $values            Attribute's values
        **/
        public function setAttribute($name, $values) {

            $this->set($name, $values);

        }


        /**
         * Get a attributes collection object copy with the new attribute definition.
         * @param   string      $name       Attribute's name
         * @return  self
        **/
        public function withAttribute($name, $value) {

            $attributes = clone $this;
            $attributes->setAttribute($name, $value);

            return $attributes;

        }

        /**
         * Get a new instance of the collection object with an added attribute value.
         * @param   string      $name       Attribute's name
         * @return  self
        **/
        public function withAddedAttribute($name, $value) {

            $attributes = clone $this;
            $attributes->addAttribute($name, $value);

            return $attributes;

        }


        /**
         * Remove a defined attribute
         * @param   string              $name              Attribute's name
        **/
        public function removeAttribute($name) {

            $this->remove($name);

        }


        /**
         * Get a new instance of the collection object
         * without a defined attribute.
         * @param   string      $name       Attribute's name
         * @return  self
        **/
        public function withoutAttribute($name, $value) {

            $attributes = clone $this;
            $attributes->remove($name, $value);

            return $attributes;

        }

    }
