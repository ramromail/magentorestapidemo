<?php

require 'configs.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Products
 *
 * @author kumarra
 */
class Products {

    //put your code here
    private $apiUserName;
    private $apiUserPass;
    private $apiBaseUrl;
    private $authToken;
    private $attributeSet = array();
    private $taxClassIds = array();

    public function __construct($user, $pass, $restUrl) {
        $this->apiUserName = $user;
        $this->apiUserPass = $pass;
        $this->apiBaseUrl = $restUrl;
    }

    /*
     * Use this function to create options necessary to make a bundle product
     */

    public function searchSimpleProductsList($keyword) {
        $keyword = preg_replace('/[^\w]/', '', $keyword);


        $apiGetProductUrl = $this->apiBaseUrl . '/rest/default/V1/products?'
                . 'searchCriteria%5Bfilter_groups%5D%5B0%5D%5Bfilters%5D%5B0%5D%5Bfield%5D=type_id&searchCriteria%5Bfilter_groups%5D%5B0%5D%5Bfilters%5D%5B0%5D%5Bcondition_type%5D=eq&searchCriteria%5Bfilter_groups%5D%5B0%5D%5Bfilters%5D%5B0%5D%5Bvalue%5D=simple&searchCriteria%5Bfilter_groups%5D%5B1%5D%5Bfilters%5D%5B0%5D%5Bfield%5D=name&searchCriteria%5Bfilter_groups%5D%5B1%5D%5Bfilters%5D%5B0%5D%5Bcondition_type%5D=like&searchCriteria%5Bfilter_groups%5D%5B1%5D%5Bfilters%5D%5B0%5D%5Bvalue%5D=%' . $keyword . '%25&fields=items%5Bname,sku,price%5D&searchCriteria[pageSize]=10';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiGetProductUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->authToken,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (false !== $response) {

            $response = json_decode($response);
            if (!empty($response->items) && !empty($response->items[0]->sku)) {

                $array = array();
                foreach ($response->items as $item) {
                    $array[] = array(
                        'sku' => (string) $item->sku,
                        'name' => (string) $item->name,
                        'price' => (float) $item->price,
                    );
                }
                return $array;
            }
        }

        return false;
    }

    public function addProductToStore($jsonObject) {
        $apiAddProductUrl = $this->apiBaseUrl . '/rest/V1/products';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiAddProductUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $jsonObject,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->authToken,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        $return = array();

        if (false !== $response) {
            $response = json_decode($response);

            if (!empty($response->id) && !empty($response->sku)) {
                $return = array(
                    'status' => 'success',
                    'message' => array(
                        'id' => (int) $response->id,
                        'sku' => (string) $response->sku,
                        'name' => (string) $response->name
                    )
                );
            } else {
                $return = array(
                    'status' => 'failed',
                    'message' => (string) $response->message
                );
            }
        } else {
            $return = array(
                'status' => 'failed',
                'message' => (string) $error_msg
            );
        }

        return $return;
    }

    public function getAttributeSet() {
        $apiAttribueSetUrl = $this->apiBaseUrl . '/rest/V1/products/attribute-sets/sets/list?searchCriteria';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiAttribueSetUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->authToken
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (false !== $response) {
            $response = json_decode($response);
            if (!empty($response->items)) {
                foreach ($response->items as $item) {
                    $this->attributeSet[(string) $item->attribute_set_id] = (string) $item->attribute_set_name;
                }

                return $this->attributeSet;
            }
        }

        return false;
    }

    public function getTaxClasses() {
        $apiTaxClassesUrl = $this->apiBaseUrl . '/rest/V1/taxClasses/search?searchCriteria';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiTaxClassesUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->authToken
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (false !== $response) {
            $response = json_decode($response);
            if (!empty($response->items)) {
                foreach ($response->items as $item) {
                    if ('PRODUCT' === (string) $item->class_type) {
                        $this->taxClassIds[(string) $item->class_id] = (string) $item->class_name;
                    }
                }

                return $this->taxClassIds;
            }
        }

        return false;
    }

    public function getAuthToken() {
        $response = false;

        $apiAuthUrl = $this->apiBaseUrl . '/rest/default/V1/integration/admin/token';

        $postData = json_encode(array(
            'username' => $this->apiUserName,
            'password' => $this->apiUserPass,
        ));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiAuthUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (false !== $response) {
            if (strlen($response) === 34) {
                $this->authToken = str_replace('"', '', (string) $response);
                return true;
            } else {
                error_log('len:



          ' . strlen((string) $response) . PHP_EOL . (string) $response);
            }
        } else {
            $this->authToken = false;
        }

        return false;
    }

}
