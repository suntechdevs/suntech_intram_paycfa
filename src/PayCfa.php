<?php
/**
 * Created by PhpStorm.
 * User: sodjinnin
 * Date: 21/07/20
 * Time: 13:47
 */


namespace suntechintram\PayCfa;

class Functions
{
    // Public Api key
    private $public_key;

    // Account Private Key
    private $private_key;

    // Account Secret
    private $secret;


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

    private $BASE_URL = "http://localhost:8002/api/";
    private $verify_URL = "getvalue";
    private $setPayout_URL = "getvalue";
    private $refund_URL = "getvalue";


    /**
     * PayCfa constructor.
     * @param $public_key
     * @param $private_key
     * @param $secret
     * @param $sandbox
     */
    public function __construct($public_key, $private_key, $secret, $sandbox)
    {
        $this->public_key = $public_key;
        $this->private_key = $private_key;
        $this->secret = $secret;
        $this->sandbox = $sandbox ? "sandbox" : "live";
        $this->curl = new \GuzzleHttp\Client();
        $this->const = $this->BASE_URL;

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
            'Content-Type: Application/json',
        ];

        $this->keys = [
            'public' => $this->public_key,
            'private' => $this->private_key,
            'secret' => $this->secret
        ];



    }

    public function verify($transactionId)
    {

        $reponse = null;
        $invoice = array(
            "transactionId" => $transactionId,
            "sandbox" => $this->sandbox,
            "keys" => $this->keys
        );

        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->BASE_URL . $this->verify_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode(["invoice"=>$invoice]),
                CURLOPT_HTTPHEADER => $this->header
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $response = "cURL Error #:" . $err;
            }

        } catch (\Exception $e) {
            $response = json_encode(array("status" => "error"));
        }
        return json_encode($response);

    }


    public function setPayout()
    {
        $reponse = null;

        try {

            $invoice = null;
            $invoice = [
                "keys" => $this->keys,
                "items" => $this->getItems(),
                "taxes" => ["name" => "tva", "amount" => $this->tvaAmount],
                "amount" => $this->getAmount(),
                "description" => $this->getDescription(),
                "action" => [
                    "cancel_url" => $this->getCancelUrl(),
                    "return_url" => $this->getReturnUrl(),
                    "callback_url" => $this->getRedirectionUrl()
                ],
                "store" => [
                    "name" => $this->getNameStore(),
                    "postal_adress" => $this->getPostalAdressStore(),
                    "logo_url" => $this->getLogoUrlStore(),
                    "web_site_url" => $this->getWebSiteUrlStore(),
                    "phone" => $this->getPhoneStore()
                ]
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->BASE_URL . $this->setPayout_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode(["invoice"=>$invoice]),
                CURLOPT_HTTPHEADER => $this->header
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $response = "cURL Error #:" . $err;
            }

        } catch (\Exception $e) {
            $response = json_encode(array("status" => "error"));
        }
        return json_encode($response);
    }


    public function refund($transactionId){
        $reponse = null;
        $invoice = array(
            "transactionId" => $transactionId,
            "keys" => $this->keys,
            "sandbox" => $this->sandbox
        );

        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->BASE_URL . $this->refund_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode(["invoice"=>$invoice]),
                CURLOPT_HTTPHEADER => $this->header
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $response = "cURL Error #:" . $err;
            }

        } catch (\Exception $e) {
            $response = json_encode(array("status" => "error"));
        }
        return json_encode($response);
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


}