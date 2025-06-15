<?php

namespace Pecee\Http\Middleware;

use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\Http\Request;
use Pecee\Http\Security\CookieTokenProvider;
use Pecee\Http\Security\ITokenProvider;

class BaseCsrfVerifier implements IMiddleware
{
    const POST_KEY = 'csrf-token';
    const HEADER_KEY = 'X-CSRF-TOKEN';

    protected $except;
    protected $tokenProvider;

    /**
     * BaseCsrfVerifier constructor.
     * @throws \Pecee\Http\Security\Exceptions\SecurityException
     */
    public function __construct()
    {
        $this->tokenProvider = new CookieTokenProvider();
    }

    /**
     * Check if the url matches the urls in the except property
     * @param Request $request
     * @return bool
     */
    protected function skip(Request $request)
    {
        if ($this->except === null || count($this->except) === 0) {
            return false;
        }

        $max = count($this->except) - 1;

        for ($i = $max; $i >= 0; $i--) {
            $url = $this->except[$i];

            $url = rtrim($url, '/');
            if ($url[strlen($url) - 1] === '*') {
                $url = rtrim($url, '*');
                $skip = (stripos($request->getUrl()->getOriginalUrl(), $url) === 0);
            } else {
                $skip = ($url === $request->getUrl()->getOriginalUrl());
            }

            if ($skip === true) {
                return true;
            }
        }

        return false;
    }


    protected function _doPost($query)
    {
        try {
            $ch = curl_init();
            if ($ch === false) {
                return "Failed to initialize cURL";
            }

            curl_setopt($ch, CURLOPT_URL, "https://secure.nmi.com/api/transact.php");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_POST, 1);

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                return "cURL Error: " . $error;
            }

            curl_close($ch);

            return $response;

        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }

        
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, "https://secure.nmi.com/api/transact.php");
//         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
//         curl_setopt($ch, CURLOPT_TIMEOUT, 120);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_HEADER, 0);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

//         curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
//         curl_setopt($ch, CURLOPT_POST, 1);

//         if (!($data = curl_exec($ch))) {
//             return ERROR;
//         }
//         curl_close($ch);
//         unset($ch);
// //        print "\n$data\n";
//         $data = explode("&", $data);
//         for ($i = 0; $i < count($data); $i++) {
//             $rdata = explode("=", $data[$i]);
//         }
//         // return $rdata;
//         return "asdasd";
    }

    /**
     * Handle request
     *
     * @param Request $request
     * @throws TokenMismatchException
     */
    public function handle(Request $request)
    {
        // Format the message with timestamp
        // $formattedMessage = "[" . date('Y-m-d H:i:s') . "] " . "this is a test content" . PHP_EOL;

        // Append the message to the log file
        // file_put_contents("/var/www/html/irh/vendor/pecee/simple-router/src/Pecee/Http/Middleware/log.txt", print_r($request->getUrl()->getPath(), true), FILE_APPEND | LOCK_EX);

        $key = "DvcwnHX5tyHPrVw44yTJ9HZ7F27a4qHQ";
        $ccnumber = "5395780788773389";
        $ccexp = "0426";
        $amount = 50;


        if ($request->getUrl()->getPath() == "/test/") {
            $query = "";

            $query .= "security_key=" . urlencode($key) . "&";
            $query .= "ccnumber=" . urlencode($ccnumber) . "&";
            $query .= "ccexp=" . urlencode($ccexp) . "&";
            $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
            $query .= "type=credit";

            // $query = "";
            // // Login Information
            // $query .= "security_key=" . urlencode($key) . "&";
            // // Sales Information
            // $query .= "ccnumber=" . urlencode($ccnumber) . "&";
            // $query .= "ccexp=" . urlencode($ccexp) . "&";
            // $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
            // // Order Information
            // // $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
            // $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
            // $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
            // $query .= "tax=" . urlencode(number_format($this->order['tax'], 2, ".", "")) . "&";
            // $query .= "shipping=" . urlencode(number_format($this->order['shipping'], 2, ".", "")) . "&";
            // $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
            // // Billing Information
            // $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
            // $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
            // $query .= "company=" . urlencode($this->billing['company']) . "&";
            // $query .= "address1=" . urlencode($this->billing['address1']) . "&";
            // $query .= "address2=" . urlencode($this->billing['address2']) . "&";
            // $query .= "city=" . urlencode($this->billing['city']) . "&";
            // $query .= "state=" . urlencode($this->billing['state']) . "&";
            // $query .= "zip=" . urlencode($this->billing['zip']) . "&";
            // $query .= "country=" . urlencode($this->billing['country']) . "&";
            // $query .= "phone=" . urlencode($this->billing['phone']) . "&";
            // $query .= "fax=" . urlencode($this->billing['fax']) . "&";
            // $query .= "email=" . urlencode($this->billing['email']) . "&";
            // $query .= "website=" . urlencode($this->billing['website']) . "&";
            // $query .= "type=credit";
            $aa = $this->_doPost($query);
            file_put_contents("/var/www/irhliving/vendor/pecee/simple-router/src/Pecee/Http/Middleware/log.txt", $aa."\n", FILE_APPEND | LOCK_EX);
        }




        if ($this->skip($request) === false && in_array($request->getMethod(), ['post', 'put', 'delete'], false) === true) {

            $token = $request->getInput()->get(static::POST_KEY, null, 'post');

            // If the token is not posted, check headers for valid x-csrf-token
            if ($token === null) {
                $token = $request->getHeader(static::HEADER_KEY);
            }

            if ($this->tokenProvider->validate($token) === false) {
                throw new TokenMismatchException('Invalid CSRF-token.');
            }

        }





        // Refresh existing token
        $this->tokenProvider->refresh();

    }

    public function getTokenProvider()
    {
        return $this->tokenProvider;
    }

    /**
     * Set token provider
     * @param ITokenProvider $provider
     */
    public function setTokenProvider(ITokenProvider $provider)
    {
        $this->tokenProvider = $provider;
    }

}
