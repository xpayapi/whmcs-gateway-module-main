<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

if (!extension_loaded('bcmath')) {
    die("BCMath module is not enabled.");
}

$gatewayModuleName = "xpayapi";

require_once __DIR__ . '/xpayapi/index.php';

function xpayapi_MetaData()
{
    return array(
        'DisplayName' => 'xPayapi.com Merchant Gateway Module',
        'APIVersion' => '1.0.7',
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

function xpayapi_config()
{
    return array(
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'xPayapi.com',
        ),
        'merchant_id' => array(
            'FriendlyName' => 'Merchant ID',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => '',
        ),
        'merchant_password' => array(
            'FriendlyName' => 'Merchant Password',
            'Type' => 'text',
            'Size' => '255',
            'Default' => '',
            'Description' => '',
        ),
    );
}

/**
 * https://developers.whmcs.com/payment-gateways/third-party-gateway/
 * https://github.com/WHMCS/sample-gateway-module
 */
function xpayapi_link($params)
{

    if (basename($_SERVER['SCRIPT_NAME']) !== "viewinvoice.php") {
        $query = http_build_query([
            "id" => $params['invoiceid'],
        ]);
        header('Location: /viewinvoice.php?' . $query);
        exit();
    }
    global $gatewayModuleName;
    $xpayapi = new \xPayapi\xPayapiSCI(
        $params['merchant_id'],
        $params['merchant_password']
    );


    ob_start();
    if ("GET" === $_SERVER['REQUEST_METHOD'] || empty($_POST["pscur"])) {
        $list = \xPayapi\xPayapiSCI::getPaymentSystems("crypto");
        ?>
        <form action="" method="POST">
            <label>Choose payment system and currency</label>
            <select name="pscur" id="pscur" autocomplete="off" required>
                <option value="">---</option>
                <?php foreach ($list as $item) { ?>
                    <?php foreach ($item["currency_list"] as $currency) { ?>
                        <option value="<?php echo htmlspecialchars(
                            sprintf("%s_%s", mb_strtolower($item["system"]), mb_strtolower($currency)),
                            ENT_QUOTES, "UTF-8"); ?>">
                            <?php echo htmlspecialchars(sprintf("%s %s", $item["display_name"], $currency),
                                ENT_QUOTES, "UTF-8"); ?>
                        </option>
                    <?php } ?>
                <?php } ?>
            </select>

            <button><?php echo htmlspecialchars($params['langpaynow'], ENT_QUOTES, "UTF-8"); ?></button>
        </form>

        <script>
            document.getElementById('pscur').addEventListener('change', function () {
                this.form.submit();
            });
        </script>

        <?php
    } else {

        @list($system, $currency) = preg_split('~_(?=[^_]*$)~', $_POST["pscur"]);

        $res = $xpayapi->createOrder(
            xpayapi_convert_amount($params["amount"], $params["currency"], $currency),
            $system,
            $currency,
            $params['invoiceid'],
            $params["description"]
        );

        if ($res["error"]) {
            xpayapi_log($res);
            return xpayapi_set_error($res["message"]);
        }

        header('Location: ' . $res["data"]["pay_link"]);
        exit();

        ?>
        <?php
    }

    return ob_get_clean();
}