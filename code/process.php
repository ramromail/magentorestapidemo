<?php

require_once 'configs.php';

if (!empty($_POST['key'])) {
    $key = filter_input(INPUT_POST, 'key', FILTER_SANITIZE_STRING);

    include 'Products.php';
    $item = new Products($APIUserName, $APIUserPass, $APIBaseUrl);

    if (true === $item->getAuthToken()) {
        $array = $item->searchSimpleProductsList($key);
        echo json_encode(array('result' => $array));
    } else {
        echo json_encode(array('error' => 'Error occured'));
    }
    exit();
} 
elseif (!empty($_POST['simpleProduct'])) {
    // handle addition for simple products

    $attributeSetId = (int) filter_input(INPUT_POST, 'attributeSetId', FILTER_SANITIZE_NUMBER_INT);
    $productName = (string) filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_STRING);
    $productSku = (string) filter_input(INPUT_POST, 'productSku', FILTER_SANITIZE_STRING);
    $productPrice = (float) filter_input(INPUT_POST, 'productPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $taxClass = (int) filter_input(INPUT_POST, 'taxClass', FILTER_SANITIZE_NUMBER_INT);
    $productQty = (int) filter_input(INPUT_POST, 'productQty', FILTER_SANITIZE_NUMBER_INT);
    $stockStatus = (int) filter_input(INPUT_POST, 'stockStatus', FILTER_SANITIZE_NUMBER_INT);
    $weight = (float) filter_input(INPUT_POST, 'weight', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $visibility = (int) filter_input(INPUT_POST, 'visibility', FILTER_SANITIZE_NUMBER_INT);
    $productDesc = (string) filter_input(INPUT_POST, 'productDesc', FILTER_SANITIZE_STRING);

    $jsonArray = array(
        "product" => array(
            "sku" => $productSku,
            "name" => $productName,
            "attribute_set_id" => $attributeSetId,
            "price" => $productPrice,
            "status" => 0,
            "visibility" => $visibility,
            "type_id" => "simple",
            "weight" => $weight,
            "extension_attributes" => array(
                "stock_item" => array(
                    "qty" => $productQty,
                    "is_in_stock" => true
                )
            ),
            "custom_attributes" => array(
                array(
                    "attribute_code" => "description",
                    "value" => $productDesc
                ),
                array(
                    "attribute_code" => "tax_class_id",
                    "value" => $taxClass
                )
            )
        )
    );

    $jsonObject = json_encode($jsonArray);

    include 'Products.php';
    $item = new Products($APIUserName, $APIUserPass, $APIBaseUrl);

    if (true === $item->getAuthToken()) {
        $result = $item->addProductToStore($jsonObject);

        if ('success' === $result['status']) {
            echo json_encode($result);
        } else {
            echo json_encode(array(
                'status' => 'failed',
                'message' => (string) $result['message']
            ));
        }
    } else {
        echo json_encode(array(
            'status' => 'failed',
            'message' => 'Cannot get Authentication keys. 
            Please check user name and password.'
        ));
    }

    exit();
} 
else if (!empty($_POST['bundleProduct'])) {
    // handle complex product

    $attributeSetId = (int) filter_input(INPUT_POST, 'attributeSetId', FILTER_SANITIZE_NUMBER_INT);
    $productName = (string) filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_STRING);
    $productSku = (string) filter_input(INPUT_POST, 'productSku', FILTER_SANITIZE_STRING);
    $stockStatus = (int) filter_input(INPUT_POST, 'stockStatus', FILTER_SANITIZE_NUMBER_INT);
    $visibility = (int) filter_input(INPUT_POST, 'visibility', FILTER_SANITIZE_NUMBER_INT);
    $productDesc = (string) filter_input(INPUT_POST, 'productDesc', FILTER_SANITIZE_STRING);

    $bundleItems = filter_input(INPUT_POST, 'bundleItems', FILTER_SANITIZE_STRING, FILTER_FORCE_ARRAY);

    $jsonArray = array(
        "product" => array(
            "sku" => $productSku,
            "name" => $productName,
            "attribute_set_id" => $attributeSetId,
            "status" => $stockStatus,
            "visibility" => $visibility,
            "type_id" => "bundle",
            "extension_attributes" => array(
                "stock_item" => array(
                    "is_in_stock" => true
                ),
                "bundle_product_options" => array(),
            ),
            "custom_attributes" => array(
                array(
                    "attribute_code" => "description",
                    "value" => $productDesc
                ),
                array(
                    "attribute_code" => "price_view",
                    "value" => 0
                ),
            )
        ),
        "saveOptions" => true
    );

    foreach ($bundleItems as $idx1 => $items) {
        $productLinks = array();

        foreach ($items["items"] as $idx2 => $item) {
            $productLinks[] = array(
                "id" => (string) (1 + (int) $idx2),
                "sku" => (string) $item["sku"],
                "option_id" => (1 + (int) $idx1),
                "qty" => 1,
                "position" => (1 + (int) $idx2),
                "price" => null,
                "price_type" => null,
                "can_change_quantity" => 1
            );
        }

        $jsonArray["product"]["extension_attributes"]["bundle_product_options"][] = array(
            "option_id" => (1 + (int) $idx1),
            "title" => (string) $items["optionTitle"],
            "type" => (string) $items["inputType"],
            "position" => (1 + (int) $idx1),
            "sku" => (string) $productSku,
            "product_links" => $productLinks
        );
    }

    $jsonObject = json_encode($jsonArray);

    include 'Products.php';
    $item = new Products($APIUserName, $APIUserPass, $APIBaseUrl);

    if (true === $item->getAuthToken()) {
        $result = $item->addProductToStore($jsonObject);
        if ('success' === $result['status']) {
            echo json_encode($result);
        } else {
            echo json_encode(array(
                'status' => 'failed',
                'message' => (string) $result['message']
            ));
        }
    } else {
        echo json_encode(array(
            'status' => 'failed',
            'message' => 'Cannot get Authentication keys. 
            Please check user name and password.'
        ));
    }

    exit();
} 