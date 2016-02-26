<?php

namespace Valerian\Ispis;

use Httpful\Request;
use Httpful\Response;
use Valerian\Ispis\Exception\IspisException;

abstract class Base
{

    const DEBUG_REGISTER = 'Devel';

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $username
     * @param string $password
     * @param bool $debug
     */
    public function __construct($username, $password, $debug = false)
    {
        $this->debug = (bool) $debug;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        $register = $this->debug === true ? self::DEBUG_REGISTER : $this::REGISTER;

        return "https://ispis.cz/api/lustraceSearchSubject?username={$this->username}&password={$this->password}&profile={$register}";
    }

    /**
     * @param  string $urlPart
     * @return Response
     * @throws IspisException
     */
    protected function query($urlPart)
    {
        $errorMessage = 'Unknown error';
        $url = $this->getBaseUrl() . $urlPart;
        $response = Request::get($url)->send();

        if ($response->code !== 200) {
            if (isset($response->headers['x-ispis-error'])) {
                $errorMessage = $response->headers['x-ispis-error'];
            }

            throw new IspisException($errorMessage);

        } elseif ($this->debug) {
            if (!isset($response->body->detail->CasLustrace)) {
                throw new IspisException($errorMessage);
            }

            echo $response->body->detail->CasLustrace;
            die();
        }

        return $response;
    }

}
