<?php

abstract class Type
{
    const Error = "Error";
    const Debug = "Debug";
    const Message = "Message";
    const Warning = "Warning";
}

class Logger
{
    /**
     * How to end the row,
     * Linux or Windows versions
     *
     * @var string
     */
     private $endRow = "\n";

     /**
      * @var object
      * Provides file system
      * functionality
      */
     private $file;

     private $path = LOG_PATH;

    /**
     * Main log method.
     * @param string $fileName
     * @param string $message
     * @param string $type
     * @throws Exception
     */
    private function log(string $fileName, string $message, string $type)
    {
        // path to Logs
        $path = $this->path  . '/' . $fileName . ".txt";
        // get date
        $date = date('Y-m-d H:i:s');

        // record type, time and the message with new line at the end
        $logMsg = "<{$type} {$date} >" . $message . $this->endRow;

        $this->file->writeFileContent($path, $logMsg, FILE_APPEND | LOCK_EX);
    }

    /**
     * Checks if given argument
     * is an array. If so it converts
     * the elements to a string
     * and concatenates one string
     * representing the array elements
     * as strings separated by space
     * @param $args
     * @return string representation of the array
     */
    private function retrieveMessage($args) : string
    {
        $args = implode(" ", $args);

        return $args;
    }

     /**
      * Logger constructor.
      * @param FileManager $file
      * @throws Exception
      */
     public function __construct(FileManager $file)
     {
         if (!isset($file))
             throw new Exception("Bad file object in Logger Constructor!");

         $this->file = $file;

         if (PHP_OS === 'WINNT')
             $this->endRow = "\r\n";
     }

    /**
     * @param array $args
     * @throws Exception
     */
    public function logError($args) : void
     {
         $msg = $this->retrieveMessage(func_get_args());

         $fileName = "Errors";

         $this->log($fileName, $msg, Type::Error);
     }

    /**
     * @param $args
     * @throws Exception
     */
    public function logDebug($args) : void
     {
         $msg = $this->retrieveMessage(func_get_args());

         $fileName = "Debug";

         $this->log($fileName, $msg, Type::Debug);
     }

     /**
      * @param array $args
      * @throws Exception
      */
     public function logWarning($args) : void
     {
         $msg = $this->retrieveMessage(func_get_args());

         $fileName = "Warnings";

         $this->log($fileName, $msg, Type::Warning);
     }

     /**
      * @param array $args
      * @throws Exception
      */
     public function logMessage($args)
     {
         $msg = $this->retrieveMessage(func_get_args());

         $fileName = "Messages";

         $this->log($fileName, $msg, Type::Message);
     }
}