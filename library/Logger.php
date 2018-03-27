<?php
/**
 * Logger
 */
class Logger {

    /**
     * How to end the row,
     * Linux or Windows versions
     *
     * @var string
     */
    public static $endRow = "\n";

    /**
     *
     * @var string
     */
    public static $endRowDouble = "\n\n";

    /**
     * Log function. Wrapper to file_put_contents()
     *
     *
     * @param $file_name
     * @param $msg
     */
    private static function _log($file_name, $msg) {
        // path to Logs
        $fname = LOG_PATH  . '/' . $file_name . ".txt";
        // record time and the message with new line at the end
        $log_msg =  "==================== " . date('Y-m-d H:i:s') . "===================="
            . self::$endRowDouble . $msg . self::$endRow
            . "============================================================" . self::$endRow;
        // log to file
        file_put_contents($fname, $log_msg, FILE_APPEND | LOCK_EX);
    }

    /**
     * Log $_SERVER array
     *
     */
    public static function logServer() {
        // printable array
        $server = print_r($_SERVER,true);
        // call to private _log
        self::_log("SERVER", $server);
    }

    /**
     * Print Headers
     *
     */
    public static function logHeaders() {
        // get the headers
        $headers = getallheaders();
        // printable array
        $h = print_r($headers,true);
        // call to private _log
        self::_log("HEADERS", $h);
    }

    /**
     * Generic Method to log messages
     *
     * @param $fname string filename
     * @param $msg string message
     */
    public static function logMsg($fname, $msg) {
        // printable array
        $msg = print_r($msg,true);
        // call to private _log
        self::_log($fname, $msg);
    }

    /**
     * Log data send to phphttp
     *
     * @param $method
     * @param $content_type
     * @param $url
     * @param $json_data
     */
    public static function log_http_send($method, $content_type, $url, $json_data)
    {
        // make message
        $msg = "URL: " . $url . self::$endRow;
        $msg .= "Method: " . $method . self::$endRow;
        $msg .= "Content Type: " . $content_type . self::$endRow;
        // print it to array first
        $d = print_r($json_data, true);
        $msg .= "Data: " . $d . self::$endRow;

        // give it to _log
        self::_log("HTTP_OUT",$msg);
    }

    /**
     * Log data received from phphttp
     *
     * @param $msg
     */
    public static function log_http_receive($msg)
    {
        // print it first
        $m = print_r($msg,true);
        // give it to _log
        self::_log("HTTP_IN",$m);
    }

    public static function logVisitors(array $server)
    {
        // security header
        $headers = [
            "ApiToken" => "WRCdma(&#_)*@$$@@$@#Sch38E2*$%G"
        ];

        $restCall = new RestCall("Socket");
        $restCall->setUrl("http://crystalpure.ddns.net/web-api/index.php/crystalpure/1001")
            ->setContentType("application/json")
            ->setMethod("POST")
            ->setHeaders($headers)
            ->setJsonData($server)
            ->isWaitingForResponse(false);

        $restCall->send();
    }

}// end Logger class

