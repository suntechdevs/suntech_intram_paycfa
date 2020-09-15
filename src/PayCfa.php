<?php
/**
 * Created by PhpStorm.
 * User: sodjinnin
 * Date: 21/07/20
 * Time: 13:47
 */


namespace suntechintram\PayCfa;

class PayCfa
{
    // Public Api key
    private $public_key;

    // Account Private Key
    private $private_key;

    // Account Secret
    private $secret;

    // Account Marchand
    private $marchand_id;


    private $sandbox;

    private $curl;

    private $const;

    private $redirectionUrl;
    private $items;
    private $amount;
    private $devise;
    private $cancelUrl;
    private $returnUrl;
    private $generateUrl;
    private $tvaAmount;
    private $description;
    private $nameStore;
    private $postalAdressStore;
    private $phoneStore;
    private $logoUrlStore;
    private $webSiteUrlStore;
    private $header;
    private $keys;
    private $currency;
    private $template;

    private $BASE_URL = "http://192.168.8.112:4200/api/v1/";
    private $BASE_URLSANBOX = "http://192.168.8.112:4200/api/v1/";
    private $verify_URL = "/transactions/confirm/";
    private $setPayout_URL = "payments/request";
    private $refund_URL = "getvalue";


    /**
     * PayCfa constructor.
     * @param $public_key
     * @param $private_key
     * @param $secret
     * @param $sandbox
     */
    public function __construct($public_key, $private_key, $secret, $marchand_id, $sandbox)
    {
        $this->public_key = $public_key;
        $this->private_key = $private_key;
        $this->secret = $secret;
        $this->const = !$sandbox?$this->BASE_URL:$this->BASE_URLSANBOX;
        $this->sandbox = $sandbox ? "sandbox" : "live";
        $this->curl = new \GuzzleHttp\Client();
        $this->marchand_id = $marchand_id;

        $this->redirectionUrl = null;
        $this->items = [];
        $this->amount = 0;
        $this->devise = null;
        $this->cancelUrl = null;
        $this->returnUrl = null;
        $this->generateUrl = null;
        $this->tvaAmount = 0;
        $this->description = null;
        $this->nameStore = null;
        $this->phoneStore = null;
        $this->logoUrlStore = null;
        $this->webSiteUrlStore = null;

        $this->header = [
            "X-API-KEY:" . $this->public_key,
            "X-PRIVATE-KEY: " . $this->private_key,
            "X-SECRET-KEY:" . $this->secret,
            "X-MARCHAND-KEY: " . $this->marchand_id,
            'Content-Type: Application/json'
        ];

        $this->keys = [
            'public' => $this->public_key,
            'private' => $this->private_key,
            'secret' => $this->secret
        ];


    }

    public function getTransactionStatus($transactionId)
    {

        $reponse = null;

        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->const . $this->verify_URL."".$transactionId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_PORT => 4200,
                CURLOPT_HTTPHEADER => $this->header
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);


            if ($err) {
                $response = json_encode(array("error" => true,"message"=>$err));;
            }

        } catch (\Exception $e) {
            $response = json_encode(array("error" => true));
        }
        return $response;

    }


    public function setRequestPayment()
    {
        $reponse = null;

        try {

            $invoice = null;
            $actions = null;
            $store = null;
            $invoice = [
                "keys" => $this->keys,
                "currency" => $this->getCurrency(),
                "items" => $this->getItems(),
                "taxes" => ["name" => "tva", "amount" => $this->tvaAmount],
                "amount" => $this->getAmount(),
                "description" => $this->getDescription()
            ];
            $actions = [
                "cancel_url" => $this->getCancelUrl(),
                "return_url" => $this->getReturnUrl(),
                "callback_url" => $this->getRedirectionUrl()
            ];

            $store = [
                "name" => $this->getNameStore(),
                "postal_adress" => $this->getPostalAdressStore(),
                "logo_url" => $this->getLogoUrlStore(),
                "web_site_url" => $this->getWebSiteUrlStore(),
                "phone" => $this->getPhoneStore(),
                "template" => $this->getTemplate()
            ];


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->const . $this->setPayout_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode(["invoice" => $invoice,"store"=>$store,"actions"=>$actions]),
                CURLOPT_HTTPHEADER => $this->header
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $response = json_encode(array("error" => true,"message"=>$err));;
            }

        } catch (\Exception $e) {
            $response = json_encode(array("error" => true));
        }
        return $response;
    }


    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }



    /**
     * @return int
     */
    public function getTvaAmount()
    {
        return $this->tvaAmount;
    }

    /**
     * @param int $tvaAmount
     */
    public function setTvaAmount($tvaAmount)
    {
        $this->tvaAmount = $tvaAmount;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }

    /**
     * @param mixed $public_key
     */
    public function setPublicKey($public_key)
    {
        $this->public_key = $public_key;
    }

    /**
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->private_key;
    }

    /**
     * @param mixed $private_key
     */
    public function setPrivateKey($private_key)
    {
        $this->private_key = $private_key;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @param mixed $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @param \GuzzleHttp\Client $curl
     */
    public function setCurl($curl)
    {
        $this->curl = $curl;
    }

    /**
     * @return string
     */
    public function getConst()
    {
        return $this->const;
    }

    /**
     * @param string $const
     */
    public function setConst($const)
    {
        $this->const = $const;
    }

    /**
     * @return mixed
     */
    public function getRedirectionUrl()
    {
        return $this->redirectionUrl;
    }

    /**
     * @param mixed $redirectionUrl
     */
    public function setRedirectionUrl($redirectionUrl)
    {
        $this->redirectionUrl = $redirectionUrl;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * @param mixed $devise
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;
    }

    /**
     * @return mixed
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * @param mixed $cancelUrl
     */
    public function setCancelUrl($cancelUrl)
    {
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param mixed $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return mixed
     */
    public function getGenerateUrl()
    {
        return $this->generateUrl;
    }

    /**
     * @param mixed $generateUrl
     */
    public function setGenerateUrl($generateUrl)
    {
        $this->generateUrl = $generateUrl;
    }

    /**
     * @return null
     */
    public function getNameStore()
    {
        return $this->nameStore;
    }

    /**
     * @param null $nameStore
     */
    public function setNameStore($nameStore)
    {
        $this->nameStore = $nameStore;
    }

    /**
     * @return mixed
     */
    public function getPostalAdressStore()
    {
        return $this->postalAdressStore;
    }

    /**
     * @param mixed $postalAdressStore
     */
    public function setPostalAdressStore($postalAdressStore)
    {
        $this->postalAdressStore = $postalAdressStore;
    }

    /**
     * @return null
     */
    public function getPhoneStore()
    {
        return $this->phoneStore;
    }

    /**
     * @param null $phoneStore
     */
    public function setPhoneStore($phoneStore)
    {
        $this->phoneStore = $phoneStore;
    }

    /**
     * @return null
     */
    public function getLogoUrlStore()
    {
        return $this->logoUrlStore;
    }

    /**
     * @param null $logoUrlStore
     */
    public function setLogoUrlStore($logoUrlStore)
    {
        $this->logoUrlStore = $logoUrlStore;
    }

    /**
     * @return null
     */
    public function getWebSiteUrlStore()
    {
        return $this->webSiteUrlStore;
    }

    /**
     * @param null $webSiteUrlStore
     */
    public function setWebSiteUrlStore($webSiteUrlStore)
    {
        $this->webSiteUrlStore = $webSiteUrlStore;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }



}