<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require 'configs.php';
require 'Products.php';

$item = new Products($APIUserName, $APIUserPass, $APIBaseUrl);

if (true === $item->getAuthToken()) {
    $attributeSetIds = $item->getAttributeSet();
    $taxClassIds = $item->getTaxClasses();
} else {
    echo 'Failed to get auth. token';
    echo 'user: '.$APIUserName.' pass: '.$APIUserPass.' host: '.$APIBaseUrl;
    exit();
}

$customJSFile = 'addsimple.js';
require 'header.php';
?>

<div class="container-fluid">
    <div class="jumbotron custom">
        <h1>Add a simple product</h1>
    </div>
</div>

<div class="container">

    <form id="addform" method="post" action="process.php">
        <p><mark>* Mandatory fields.</mark></p>
        <div class="form-group">
            <label for="attributeSetId">Attribute Set</label>
            <select class="custom-select" id="attributeSetId" name="attributeSetId" title="Select what kind of attribute class the product belongs to.">
                <?php
                foreach ($attributeSetIds as $attrId => $attrName) {
                    echo '<option value="' . $attrId . '">' . $attrName . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="productName">Product Name *</label>
            <input required type="text" class="form-control" name="productName" id="productName" placeholder="Name of the product" title="Name of the product.">
        </div>

        <div class="form-group">
            <label for="productSku">SKU *</label>
            <input required type="text" class="form-control" name="productSku" id="productSku" placeholder="SKU of the product" title="SKU of the product.">
        </div>

        <div class="form-group">
            <label for="productPrice">Product Price *</label>
            <input required type="number" step="0.01" class="form-control" name="productPrice" id="productPrice" placeholder="Unit price of the product, do not add currency symbol" title="Unit price of the product, do not add currency symbol">
        </div>

        <div class="form-group">
            <label for="taxClass">Tax class</label>
            <select class="custom-select" id="taxClass" name="taxClass" title="Choose which tax category the product belongs.">
                <?php
                if (!array_key_exists(0, $taxClassIds)) {
                    $taxClassIds[0] = "None";
                }
                foreach ($taxClassIds as $attrId => $attrName) {
                    echo '<option value="' . $attrId . '">' . $attrName . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="productQty">Quantity</label>
            <input type="number" class="form-control" name="productQty" id="productQty" placeholder="Number of items" title="Number of items">
        </div>

        <div class="form-group">
            <label for="stockStatus">Stock Status</label>
            <select class="custom-select" id="stockStatus" name="stockStatus" title="Out of stock product will not be shown in catalouge">
                <option value="1">In Stock</option>
                <option selected value="0">Out of Stock</option>
            </select>
        </div>

        <div class="form-group">
            <label for="weight">Weight</label>
            <input type="number" step="0.01" class="form-control" name="weight" id="weight" placeholder="Leave empty if product does not need weight" title="Leave empty if product does not need weight">
        </div>


        <div class="form-group">
            <label for="visibility">Visibility</label>
            <select class="custom-select" id="visibility" name="visibility" title="Select if product should be visible in catalouge">
                <?php
                foreach ($productVisibIdValues as $attrId => $attrName) {
                    echo '<option value="' . $attrId . '">' . $attrName . '</option>';
                }
                ?>
            </select>
        </div>


        <div class="form-group">
            <label for="productDesc">Description</label>
            <textarea class="form-control" id="productDesc" name="productDesc" placeholder="Add a description for the product." title="Add a description for the product."></textarea>
        </div>

        <div class="form-group">
            <button type="button" id="simpleAdd" class="btn btn-primary">Add to Catalouge</button>
            <button type="reset" class="btn btn-danger">Reset form</button>
        </div>
        <div class="form-group">
        </div>
    </form>
</div>