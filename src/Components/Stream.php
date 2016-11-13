<?php

    namespace WOK\HttpMessage\Components;

    use \Psr\Http\Message\StreamInterface;

    /**
     * The Stream class provide an interface
     * for both HTTP request and response body
    **/
    class Stream implements StreamInterface {

        /**
         * @var     resource    $stream         Stream resource
        **/
        protected $stream;

        /**
         * @var     array       $meta           Stream meta data
        **/
        protected $meta = array();

        /**
         * @var     bool        $seekable       Is stream seekable
         * @var     bool        $readable       Is stream readable
         * @var     bool        $writable       Is stream writable
        **/
        protected $seekable = false;
        protected $readable = false;
        protected $writable = false;

        /**
         * Instanciate Stream object
         * @param   resource        $resource         Stream
        **/
        public function __construct($resource) {

            if(!is_resource($resource))
                throw new \DomainException(__CLASS__.' interface requiert a stream as parameter');

            $this->stream   = $resource;
            $this->meta     = stream_get_meta_data($this->stream);

            $mode = str_replace(['b','t'], '', $this->meta['mode']);
            $this->seekable = $this->meta['seekable'];
            $this->readable = in_array($mode, ['r', 'r+', 'w+', 'a+', 'x+', 'c+']);
            $this->writable = in_array($mode, ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+']);


        }

        /**
         * Instanciate a new Stream object from a string
         * @param   string      $string         Stream initial content
        **/
        public static function createFromString($string = '') {

            $stream = fopen('php://temp', 'w+');
            $stream = new self($stream);

            $stream->write($string);

        }

        /**
         * Instanciate a new Stream object from a file path
         * @param   string      $filename         Stream initial content
        **/
        public static function createFromPath($filename, $mode = 'w+') {

            $stream = fopen($filename, $mode);
            return new self($stream);

        }


        /**
         * Get the stream itself
         * @return stream
        **/
        public function getStream() {
            return $this->stream;
        }

        /**
         * Is the stream readable
         * @return bool
        **/
        public function isReadable() {
            return $this->readable;
        }


        /**
         * Is the stream writable
         * @return bool
        **/
        public function isWritable() {
            return $this->writable;
        }

        /**
         * Is the stream seekable
         * @return bool
        **/
        public function isSeekable() {
            return $this->seekable;
        }


        /**
         * Get the stream size
         * @param   integer     $default        Default file size (should be null or zero)
         * @return  integer     File size
        **/
        public function getSize($default = null) {
            $stats = fstat($this->stream);
            return isset($stats['size']) ? $stats['size'] : $default;
        }


        /**
         * Get the stream meta data
         * @return array
        **/
        public function getMetaData() {
            return $this->meta;
        }


        /**
         * Get the stream content
         * @note The cursor will be reset to the start of the file if possible.
         * @return  string
        **/
        public function getContents() {

            if (!$this->isReadable() || ($contents = stream_get_contents($this->stream)) === false) {
                throw new \RuntimeException('Could not get contents of not readable stream');
            }

            // Prevent forgot
            if($this->isSeekable())
                rewind($this->stream);

            return $contents;

        }

        /**
         * Get the stream content as a string
         * @return   string
        **/
        public function __toString() {

            try {
                return $this->getContents();
            }
            catch(\Exception $e) {
                return false;
            }

        }


        public function seek($offset, $whence = SEEK_SET) {

            if(!$this->isSeekable())
                throw new \RuntimeException('Could not seek a not seekable stream');

            fseek($this->stream, $offset, $whence);

        }

        /**
         * Get the cursor position
         * @return  integer
        **/
        public function tell() {
            return ftell($this->stream);
        }


        /**
         * Check if the cursor is at the end of the file
         * @return  bool
        **/
        public function eof() {

            return foef($this->stream);

        }


        /**
         * Seek to the beginning of the stream;
        **/
        public function rewind(){

            if (!$this->isSeekable() || rewind($this->stream) === false) {
                throw new \RuntimeException('Could not rewind stream');
            }

        }

        /**
         * Read the stream
         * @param integer       $length         Reading length bytes
        **/
        public function read($length = null) {

            if(is_null($length))
                $length = $this->getSize(0);

            if (!$this->isReadable() || ($data = fread($this->stream, $length)) === false) {
                throw new \RuntimeException('Could not read from not readable stream');
            }

            return $data;

        }


        /**
         * Write in the stream
        **/
        public function write($string) {

            if (!$this->isWritable() || ($written = fwrite($this->stream, $string)) === false) {
                throw new \RuntimeException('Could not write in a not writable stream');
            }
            return $written;
        }


        public function close() {
            fclose($this->stream);
        }


        /**
         * Close the opened stream
        **/
        public function __destruct() {

            $this->close();

        }


    }
