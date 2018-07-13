<?php

/**
 * Sets a cookie on the clients computer,
 * that expires in a certain amount of time.
 * The cookie holds a hash that "uniquely"
 * identifies not authenticated user.
 * It actually identifies clients machine,
 * or multiple machines on the same subnet mask.
 */
class CookieSetter
{
    /**
     * @var
     * Used for creation
     * of hash, for user
     * identification.
     * It is concatenation of
     * $_SERVER['REMOTE_ADDR']
     * $_SERVER['HTTP_USER_AGENT']
     * $_SERVER['HTTP_ACCEPT']. Unique Value
     */
    private $hashToken;

    /**
     * @var
     * Time taken
     * to identify
     * cookie expiration.
     */
    private $time;

    /**
     * @var
     * Created hash, used
     * for user identification.
     */
    private $hash;

    /**
     * @var
     * The internet domain
     * of the cookie.
     * Initial value is root
     * "/".
     */
    private $domain = "/";

    /**
     * @var string
     * The name of the cookie.
     */
    private $cookieName = "";

    /**
     * @var integer
     * Time in days
     * for cookie expiration.
     */
    private $days;

    /**
     * Retrieves number of days
     * the cookie needs to be active
     * from config file, then it
     * sets the time in days.
     */
    private function setTime()
    {
        $this->time = time() + 60 * 60 * 24 * $this->days;
    }

    /**
     * Makes hash from
     * hash token.
     */
    private function makeHash()
    {
       $this->hash = hash('ripemd128', $this->hashToken);
    }

    /**
     * Produces hash token from:
     * $_SERVER['REMOTE_ADDR']
     * $_SERVER['HTTP_USER_AGENT']
     * $_SERVER['HTTP_ACCEPT']
     */
    private function makeHashToken()
    {
        $this->hashToken = $_SERVER['REMOTE_ADDR'] . ":" .
            $_SERVER['HTTP_USER_AGENT'] . ":" .
            $_SERVER['HTTP_ACCEPT'];
        // TODO add unique value to hashToken!!!!
    }

    /**
     * CookieSetter constructor.
     */
    public function __construct(string $cookieName, int $days = 1)
    {
        $this->cookieName = $cookieName;
        $this->days = $days;
    }

    /**
     * Sets cookie on the client's
     * computer.Manually sets the
     * super-global $_COOKIE
     * for readability.
     */
    public function setCookie()
    {
        // then make hash token
        $this->makeHashToken();

        // set time
        $this->setTime();

        // set hash
        $this->makeHash();

        // set cookie
        setcookie($this->cookieName, $this->hash, $this->time, $this->domain);

        // at this point the cookie isn't set until the response is sent back to the client,
        // and isn't available  until the next request from the client after that.
        // that is the reason for a manual setup as:
        $_COOKIE[$this->cookieName] = $this->hash;
    }

    /**
     * Getter.
     * Gives the hash produced.
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Getter.
     * Gives the name of the cookie,
     * which happens to be the same
     * as the name of a session.
     * @return string
     */
    public function getCookieName()
    {
        return $this->cookieName;
    }
}