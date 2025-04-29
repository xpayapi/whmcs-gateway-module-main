<?php

if (isset($_REQUEST['order_id'])) {
    $query = http_build_query([
        "id" => $_REQUEST['order_id'],
        "paymentsuccess" => true,
    ]);
    header('Location: /viewinvoice.php?' . $query);
} else {
    header('Location: /clientarea.php');
}