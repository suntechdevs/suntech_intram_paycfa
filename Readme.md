# INTRAM PHP SDK



The [PHP](https://www.php.net) library for [INTRAM (intram.com)](https://intram.com).

Built on the INTRAM HTTP API (beta).

## Installation via composer

```sh
composer require suntechintram/paycfa
```

## API configuration

Setup intram API keys.

```php
$paycfa = new PayCfa(
            "5b06f06a0aad7d0163c414926b635ee9cdf41438de0f09d70a4acf153083b7ed375a691e3513b42544530469e1ff8657b34508dc61927048444dd6dc9ccbb87f",
            "pk_9c0410014969f276e8b3685fec7b1b2ab41fc760db2976c75e32ec0fdc3b7d5575a7087f9aeb4d8a29a949ac4cac11363b39ff6a6d9dc3bc6ce0f328c62c3c58",
            "sk_08bd75f9468b484d8a9f24daddff4638d6513fdcf3ff4dd533e72ce55c22eac3207c12af49400ecddb1969ad3db152b0c338c0050c4540f9d0cb8c3cd3cb8c26",
            "marchand_id",
            true)
```
Log in to your Intram account, click on Developer, then on API at this level, get the API keys and give them as arguments to the controller.
Initialize Intram PayCfa by entering in order: `PUBLIC_KEY`,` PRIVATE_KEY`, `INTRAM_SECRET`,` INTRAM_MARCHAND_KEY`, `MODE`
The mode: `true` for live mode and `false` for test mode.


##Configure your department / company information



###### Setting Store name
(required)
```php
$paycfa->setNameStore("Suntech Store"); 
```



###### Setting Store Logo Url

```php
$paycfa->setLogoUrlStore("https://www.suntechshop/logo.png");
```



###### Setting Store Web site

```php
$paycfa->setWebSiteUrlStore("https://www.suntechshop");
```



###### Setting Store phone

```php
$paycfa->setPhoneStore("97000000");
```



###### Setting Store Postal adress

```php
$paycfa->setPostalAdressStore("BP 35");
```

##Create a request paiement
In order to allow the user to make a payment on your store, you must create the transaction and then send them the payment url or the qr code to scan. 
For that :

###### Add Invoice Items
Add the different products of the purchase (required)
```php
$paycfa->setItems([
            ['name'=>"T-shirt",'qte'=>"2",'price'=>"500",'totalamount'=>"1000"],
            ['name'=>"trouser",'qte'=>"1",'price'=>"12500",'totalamount'=>"12500"],
        ]);
```

###### Setting TVA Amount
TVA (optional)
```php
$paycfa->setTvaAmount(100);
```

###### Setting Total Amount 
Order total (required)
```php
$paycfa->setAmount(13600);
```
###### Setting Currency 
Currency of paiement (required)
```php
$paycfa->setCurrency("XOF");
```

###### Setting Description 
Description of operation (required)
```php
$paycfa->setDescription("Pretty and suitable for your waterfall");
```


###### Setting Store Redirection Url

```php
$paycfa->setRedirectionUrl("https://www.suntechshop/redirection-url");
```


###### Setting Store Return Url

```php
$paycfa->setReturnUrl("https://www.suntechshop/return-url");
```


###### Setting Store Cancel Url

```php
$paycfa->setCancelUrl("https://www.suntechshop/cancel-url");
```


###### Make the payment request

```php
$response = json_decode($paycfa->setRequestPayment());
```
###### Expected response

```php
{
              +"status": "PENDING"
              +"transaction_id": "5f2d7a96b97d9d3fea912c11"
              +"receipt_url": "localhost:3000/payment/gate/5f2d7a96b97d9d3fea912c11"
              +"total_amount": 1000
              +"message": "Transaction created successfully"
              +"error": false
}
```

###### Get data
```php
$transaction_id = $response->transaction_id;
$status = $response->status;
$receipt_url = $response->receipt_url;
$total_amount = $response->total_amount;
$message = $response->message;
$error = $response->error;
```

##Get transaction status

Give the transaction identifier as an argument to the function (required)
```php
$paycfa->getTransactionStatus(5f2d7a96b97d9d3fea912c11); 
```

# Running Tests
To run tests just setup the API configuration environment variables. An internet connection is required for some of the tests to pass.

## License
MIT