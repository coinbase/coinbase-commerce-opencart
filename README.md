# Coinbase Commerce Payment Module
Accept Cryptocurrencies on your Opencart store with Coinbase Commerce module.

## Prerequisite
- SSL enabled.

## Accepted Cryptocurrencies
1. It will accept Cryptocurrencies payment on your store with ease.
2. Secure payment, goes directly into your own Cryptocurrency wallet.
3. Following Cryptocurrencies are accepted by the Coinbase Commerce.
    - Bitcoin
    - Bitcoin Cash
    - Ethereum
    - Litecoin

## Create an Account
If you don't have a Coinbase Commerce account, <a href="https://commerce.coinbase.com/dashboard/settings">Sign Up</a>.

## Manual installation

### Download the module and unzip it in the root directiry of your "Opencart Project".
### Clear Opencart Cache to apply the changes
1. Open your store content via File Manager or FTP.
2. Go to the folder system/storage/cache/.
3. Delete all files except index.html

## Admin installation

### Goto -> Extensions -> installer -> Click the upload button and locate the extension file of type ".ocmod".
Note: If extension is not showing after installation. Perform above step again (upload extension).
### Clear Opencart Cache to apply the changes
1. Open your store content via File Manager or FTP.
2. Go to the folder system/storage/cache/.
3. Delete all files except index.html

## Generate API Credentials

1. Create an API Key <a href="https://commerce.coinbase.com/dashboard/settings"> Coinbase Commerce Dashboard </a> -> API keys -> Create an API key.
2. Get the API Secret <a href="https://commerce.coinbase.com/dashboard/settings"> Coinbase Commerce Dashboard </a> -> Show Shared Secrets.

## Enable Module in Opencart Admin

1.	Configure module in Extensions -> Extensions -> Choose the extension type.
2.	Select “Payments” from dropdown.
3.	Scroll down to 'Coinbase Commerce'. Note: If extension is not showing "clear the cache"
4.	Payment Method Enabled - Set "Enabled" to activate it.
5.	Generate the API’s using the Sign in or Sign Up Page.
6.	API Key - paste the API key. 
7.	API Secret - paste the API secret.
8.	Test Mode – No (By Default).
9. CALLBACK URL - copy the given link to <a href="https://commerce.coinbase.com/dashboard/settings"> Coinbase Commerce Dashboard </a> -> Webhook subscriptions -> Add an endpoint.
10.	Total – The amount Order must reach before this payment method becomes active.
11.	Order Status - Pending (By default – The status assigned to new orders).
12.	Completed Status – If order Payment received -> Processing state will be assigned to it.
13.	Unresolved Status – If the Payment is unresolved -> Denied state will be assigned to it.
14.	Confirmed Status – If previously unresolved order will be resolved -> Complete state will be assigned.
15.	 Expired Status – If payment time out -> Expired state will be assigned to it.
(For 10-14 – You can select any state so that state will be assigned to new orders)
16.	Sort Order - (Optional) enter integer value, Order with 0 shows at top in the list.

Click "Save" on the upper right part of the screen.

## Step by Step Details:
- At Checkout Page customer will enter his/her shipping address.
- Select the payment method "Coinbase Commerce" and hit the "Pay with Coinbase Commerce" button.
- Coinbase Commerce module will redirect the customer to the Payment Interface. 
- Under this payment window customer will have to pay within 15 minutes. 
- Once paid customer will be redirected to Opencart store with a Success or Failure message.
- Order status will be "Denied" in the following UNRESOLVED cases: (Multiple, Underpaid or Overpaid paymnet).
- If payment is not received within 15 minutes, Order will be Cancelled.

## Resolving the Order Status Manually
To resolve the order status of “Denied” Order. Merchant/Admin will have to follow the given steps in sequence. 
1.	Navigate to Sales -> Orders -> Click "view".
2.	Locate the section “Add Order History”.
3.	Add Comment and notify the customer.
4.	Generate the invoice manually.

## License
[Open Source License](LICENSE)

## Integrate with other e-commerce platforms
[Coinbase Commerce Integrations](https://commerce.coinbase.com/integrate)
