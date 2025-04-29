# xPayapi.com - WHMCS Crypto Payment Gateway Module

This module integrates the xPayapi.com payment gateway with WHMCS, providing a secure and convenient way to process cryptocurrency payments.

## Requirements

- WHMCS version **7.6.1 or higher**
- PHP version **7.2 or higher**

## Installation

### Step 1: Module Activation

1. Download and unpack the xPayapi.com WHMCS module into your WHMCS installation.
2. Log into your WHMCS Admin Area.
3. Navigate to **Addons → Apps & Integrations**.
4. Use the search bar to find **xPayapi**, select it, and click **Activate**.
5. Ensure you check **"Show on Order Form"**.

### Step 2: Merchant Configuration

To obtain your Merchant ID and Password:

1. Log in to your xPayapi.com account at [https://xPayapi.com/login/](https://xPayapi.com/login/).
2. From your Profile page, go to the **Merchants** section: [https://xPayapi.com/merchant/](https://xPayapi.com/merchant/).
3. Click **"Add merchant"**: [https://xPayapi.com/merchant/add/](https://xPayapi.com/merchant/add/).
4. Complete the form with the following information:
    - **Title:** Enter a unique name (no spaces or special characters).
    - **Domain:** Your website URL without "http://", "https://", or trailing slashes.
    - **Email support for your merchant:** Provide your support email address.
    - **The Merchant Password:** Either use the auto-generated password or create a strong one, and save it securely for future use.
    - **URL of Invoice Payment Notifications:** `https://yourdomain.com/modules/gateways/xpayapi/ipn/index.php`
    - **URL of successful payment page:** `https://yourdomain.com/modules/gateways/xpayapi/redirect/success.php`
    - **URL of failed payment page:** `https://yourdomain.com/modules/gateways/xpayapi/redirect/fail.php`
    - **URL of Cryptocurrency Transaction Processor (optional):** `https://yourdomain.com/modules/gateways/xpayapi/ipn/index.php`
    - **Shop description:** A brief description of your website or service.

5. Click **"Add merchant"** at the bottom of the form.
6. Note down and securely store your **Merchant ID** and **Merchant Password** displayed on the following page.


### Step 3: Enter Merchant ID and Password into WHMCS

1. Return to your WHMCS Admin Area.

2. Go to Addons → Apps & Integrations → xPayapi.

3. Fill in the saved Merchant ID and Merchant Password into the respective fields and save your changes.


Configuration is now complete.

## Contributing

If you encounter a bug or have suggestions for improvement, please open a GitHub issue in this repository. Your feedback helps us improve this integration module. Please note that the xPayapi.com bug bounty does not extend to this WHMCS module.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

