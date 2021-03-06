# Usage

### Execute the command in the terminal:
`composer require veselin/settle-online-sdk`

### You need to copy the contents of the config-copy.php file and paste it into a new config.php file at the same level.
```
// In the config.php file, replace: 
  define('MERCHANT_ID', ''); // with your merchant id
  define('USER_ID', ''); // with your user id
  define('SECRET', ''); // with your secret
```

### PHP codes for working with the SDK package.
```
// The PaymentGateway object requires an object that it has implemented the iPaymentStatus interface.
    $status = new PaymentStatus(); //PaymentStatus is an example. You need to create your own class that implements the iPayment interface.
    $paymentGateway = new PaymentGateway($status); //In the PaymentGateway class we will make sure to call the success or fail method.
    $paymentGateway->getToken(); // Save the token.
``` 
```
// If you do not specify a phone as a parameter it will only return paymentId. Otherwise it will return paymentId and qrString.
    $paymentGateway->pay($amount, $description, $phone);
    $status = $paymentGateway->checkPaymentStatus($paymentId, $token); // Use your implementation if SUCCESS or FAIL. 
```
