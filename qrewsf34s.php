<?php

namespace Pecee\Http\Middleware;

define("APPROVED", 1);
define("DECLINED", 2);
define("ERROR", 3);

class Nmi
{
    function setLogin()
    {
        $this->login['security_key'] = env('NMI_Key');
    }

    function synchronousTerminal($amount)
    {
        $query = $this->getSaleQuery($amount);
        $poi_device_id=Auth::user()->season;
        if (strlen($poi_device_id)<20)
            die ("no CC Terminal Device");
        $query .= "poi_device_id=" . $poi_device_id. "&";
        $query .= "response_method=synchronous&";
        return $this->_doPost($query);
    }

    function doSale($amount, $ccnumber, $ccexp, $cvv = ""){
        $query = $this->getSaleQuery($amount);
        // Sales Information
        $query .= "ccnumber=" . urlencode($ccnumber) . "&";
        $query .= "ccexp=" . urlencode($ccexp) . "&";
        $query .= "cvv=" . urlencode($cvv) . "&";

        $query .= "shipping=" . urlencode(number_format($this->order['shipping'], 2, ".", "")) . "&";

//        $query .= "ipaddress=" . $_SERVER['REMOTE_ADDR'] . "&";
//        $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";

        return $this->_doPost($query);
    }

    function getSaleQuery($amount)
    {
        $query = "";
        // Login Information
        $query .= "security_key=" . env('NMI_Key') . "&";
        $query .= "type=sale&";
        $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";

        // Billing Information
        $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
        $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
//        $query .= "company=" . urlencode($this->billing['company']) . "&";
        $query .= "address1=" . urlencode($this->billing['address1']) . "&";
//        $query .= "address2=" . urlencode($this->billing['address2']) . "&";
        $query .= "city=" . urlencode($this->billing['city']) . "&";
        $query .= "state=" . urlencode($this->billing['state']) . "&";
        $query .= "zip=" . urlencode($this->billing['zip']) . "&";
        $query .= "country=" . urlencode($this->billing['country']) . "&";
        $query .= "phone=" . urlencode($this->billing['phone']) . "&";
//        $query .= "fax=" . urlencode($this->billing['fax']) . "&";
        $query .= "email=" . urlencode($this->billing['email']) . "&";
//        $query .= "website=" . urlencode($this->billing['website']) . "&";

        // Order Information
        $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
        $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
//        $query .= "tax=" . urlencode(number_format($this->order['tax'], 2, ".", "")) . "&";
        return $query;
    }

    function doAuth($amount, $ccnumber, $ccexp, $cvv = "")
    {

        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Sales Information
        $query .= "ccnumber=" . urlencode($ccnumber) . "&";
        $query .= "ccexp=" . urlencode($ccexp) . "&";
        $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
        $query .= "cvv=" . urlencode($cvv) . "&";
        // Order Information
        $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
        $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
        $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
        $query .= "tax=" . urlencode(number_format($this->order['tax'], 2, ".", "")) . "&";
        $query .= "shipping=" . urlencode(number_format($this->order['shipping'], 2, ".", "")) . "&";
        $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
        // Billing Information
        $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
        $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
        $query .= "company=" . urlencode($this->billing['company']) . "&";
        $query .= "address1=" . urlencode($this->billing['address1']) . "&";
        $query .= "address2=" . urlencode($this->billing['address2']) . "&";
        $query .= "city=" . urlencode($this->billing['city']) . "&";
        $query .= "state=" . urlencode($this->billing['state']) . "&";
        $query .= "zip=" . urlencode($this->billing['zip']) . "&";
        $query .= "country=" . urlencode($this->billing['country']) . "&";
        $query .= "phone=" . urlencode($this->billing['phone']) . "&";
        $query .= "fax=" . urlencode($this->billing['fax']) . "&";
        $query .= "email=" . urlencode($this->billing['email']) . "&";
        $query .= "website=" . urlencode($this->billing['website']) . "&";
        // Shipping Information
        $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
        $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
        $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
        $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
        $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
        $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
        $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
        $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
        $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
        $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
        $query .= "type=auth";
        return $this->_doPost($query);
    }

    function doCredit($amount, $ccnumber, $ccexp)
    {

        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Sales Information
        $query .= "ccnumber=" . urlencode($ccnumber) . "&";
        $query .= "ccexp=" . urlencode($ccexp) . "&";
        $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
        // Order Information
        $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
        $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
        $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
        $query .= "tax=" . urlencode(number_format($this->order['tax'], 2, ".", "")) . "&";
        $query .= "shipping=" . urlencode(number_format($this->order['shipping'], 2, ".", "")) . "&";
        $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
        // Billing Information
        $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
        $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
        $query .= "company=" . urlencode($this->billing['company']) . "&";
        $query .= "address1=" . urlencode($this->billing['address1']) . "&";
        $query .= "address2=" . urlencode($this->billing['address2']) . "&";
        $query .= "city=" . urlencode($this->billing['city']) . "&";
        $query .= "state=" . urlencode($this->billing['state']) . "&";
        $query .= "zip=" . urlencode($this->billing['zip']) . "&";
        $query .= "country=" . urlencode($this->billing['country']) . "&";
        $query .= "phone=" . urlencode($this->billing['phone']) . "&";
        $query .= "fax=" . urlencode($this->billing['fax']) . "&";
        $query .= "email=" . urlencode($this->billing['email']) . "&";
        $query .= "website=" . urlencode($this->billing['website']) . "&";
        $query .= "type=credit";
        return $this->_doPost($query);
    }

    function doOffline($authorizationcode, $amount, $ccnumber, $ccexp)
    {

        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Sales Information
        $query .= "ccnumber=" . urlencode($ccnumber) . "&";
        $query .= "ccexp=" . urlencode($ccexp) . "&";
        $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
        $query .= "authorizationcode=" . urlencode($authorizationcode) . "&";
        // Order Information
        $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
        $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
        $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
        $query .= "tax=" . urlencode(number_format($this->order['tax'], 2, ".", "")) . "&";
        $query .= "shipping=" . urlencode(number_format($this->order['shipping'], 2, ".", "")) . "&";
        $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
        // Billing Information
        $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
        $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
        $query .= "company=" . urlencode($this->billing['company']) . "&";
        $query .= "address1=" . urlencode($this->billing['address1']) . "&";
        $query .= "address2=" . urlencode($this->billing['address2']) . "&";
        $query .= "city=" . urlencode($this->billing['city']) . "&";
        $query .= "state=" . urlencode($this->billing['state']) . "&";
        $query .= "zip=" . urlencode($this->billing['zip']) . "&";
        $query .= "country=" . urlencode($this->billing['country']) . "&";
        $query .= "phone=" . urlencode($this->billing['phone']) . "&";
        $query .= "fax=" . urlencode($this->billing['fax']) . "&";
        $query .= "email=" . urlencode($this->billing['email']) . "&";
        $query .= "website=" . urlencode($this->billing['website']) . "&";
        // Shipping Information
        $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
        $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
        $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
        $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
        $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
        $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
        $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
        $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
        $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
        $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
        $query .= "type=offline";
        return $this->_doPost($query);
    }

    function doCapture($transactionid, $amount = 0)
    {

        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Transaction Information
        $query .= "transactionid=" . urlencode($transactionid) . "&";
        if ($amount > 0) {
            $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
        }
        $query .= "type=capture";
        return $this->_doPost($query);
    }

    function doVoid($transactionid)
    {

        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Transaction Information
        $query .= "transactionid=" . urlencode($transactionid) . "&";
        $query .= "type=void";
        return $this->_doPost($query);
    }

    function doRefund($transactionid, $amount = 0)
    {
        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Transaction Information
        $query .= "transactionid=" . urlencode($transactionid) . "&";
        if ($amount > 0) {
            $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
        }
        $query .= "type=refund";
        return $this->_doPost($query);
    }

    // Edit a Plan for subscribing

    function addPlan($amount, $plan_id, $plan_name)
    {
        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Transaction Information
        $query .= "recurring=add_plan&";
        $query .= "plan_payments=0&";
        $query .= "plan_amount=". urlencode($amount) . "&";;
        $query .= "plan_name=". urlencode($plan_name) . "&";;
        $query .= "plan_id=". urlencode($plan_id) . "&";;
        $query .= "day_frequency=7&";
        return $this->_doPost($query);
    }

    function editPlan($current_plan_id, $plan_amount)
    {
        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Transaction Information
        $query .= "recurring=edit_plan&";
        $query .= "plan_payments=0&";
        $query .= "day_frequency=7&";
        $query .= "current_plan_id=" . urlencode($current_plan_id) . "&";
        $query .= "plan_amount=" . urlencode($plan_amount) . "&";
        return $this->_doPost($query);
    }

    // Add Subscription to an Existing Plan
    function addSubscription($plan_id, $ccnumber, $ccexp )
    {

        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Transaction Information
        $query .= "recurring=add_subscription&";
        $query .= "payment=creditcard&";
        $query .= "plan_id=" . urlencode($plan_id) . "&";
        $query .= "ccnumber=" . urlencode($ccnumber) . "&";
        $query .= "ccexp=" . urlencode($ccexp) . "&";
        $query .= "first_name=" . urlencode($this->billing['firstname']) . "&";
        $query .= "last_name=" . urlencode($this->billing['lastname']) . "&";
        $query .= "address1=" . urlencode($this->billing['address1']) . "&";
        $query .= "city=" . urlencode($this->billing['city']) . "&";
        $query .= "state=" . urlencode($this->billing['state']) . "&";
        $query .= "zip=" . urlencode($this->billing['zip']) . "&";
        $query .= "country=" . urlencode($this->billing['country']) . "&";
        $query .= "phone=" . urlencode($this->billing['phone']) . "&";
        $query .= "email=" . urlencode($this->billing['email']) . "&";
        return $this->_doPost($query);
    }

    // Delete Subscription to an Existing Plan
    function deleteSubscription($subscription_id )
    {
        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
        // Transaction Information
        $query .= "recurring=delete_subscription&";
        $query .= "subscription_id=" . urlencode($subscription_id) . "&";

        return $this->_doPost($query);
    }

    function _doPost($query)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.nmi.com/api/transact.php");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_POST, 1);

        if (!($data = curl_exec($ch))) {
            return ERROR;
        }
        curl_close($ch);
        unset($ch);
//        print "\n$data\n";
        $data = explode("&", $data);
        for ($i = 0; $i < count($data); $i++) {
            $rdata = explode("=", $data[$i]);
            $this->responses[$rdata[0]] = $rdata[1];
        }
        return $this->responses['response'];
    }
}
