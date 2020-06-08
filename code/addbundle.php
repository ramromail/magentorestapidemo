<?php
/**
 * This file shows the form for adding BUndle product
 */

require 'configs.php';
require 'Products.php';

$item = new Products($APIUserName, $APIUserPass, $APIBaseUrl);

if (true === $item->getAuthToken()) {
    $attributeSetIds = $item->getAttributeSet();
} else {
    echo 'Failed to get auth. token';
    echo 'user: '.$APIUserName.' pass: '.$APIUserPass.' host: '.$APIBaseUrl;
    exit();
}

$customJSFile = 'addbundle.js';
require 'header.php';
?>

<div class="container-fluid">
    <div class="jumbotron custom">
        <h1>Add a Bundle product</h1>
    </div>
</div>

<div class="container">
    <form id="bundleform" method="POST" action="process.php">
        <p><mark>* Mandatory fields.</mark></p>
        <div class="form-group">
            <label for="attributeSetId">Attribute Set</label>
            <select
                class="custom-select"
                id="attributeSetId"
                name="attributeSetId"
                title="Select what kind of attribute class the product belongs to.">
                    <?php
                    foreach ($attributeSetIds as $attrId => $attrName) {
                        if ($attributeSetId === (int) $attrId) {
                            echo '<option selected value="' . $attrId . '">' . $attrName . '</option>';
                        } else {
                            echo '<option value="' . $attrId . '">' . $attrName . '</option>';
                        }
                    }
                    ?>
            </select>
        </div>

        <div class="form-group">
            <label for="productName">Product Name *</label>
            <input required type="text" class="form-control" name="productName" value="<?php echo $productName; ?>" id="productName" placeholder="Name of the product" title="Name of the product.">
        </div>

        <div class="form-group">
            <label for="productSku">SKU *</label>
            <input required type="text" class="form-control" name="productSku" value="<?php echo $productSku; ?>" id="productSku" placeholder="SKU of the product" title="SKU of the product.">
        </div>

        <div class="form-group">
            <label for="stockStatus">Stock Status</label>
            <select class="custom-select" id="stockStatus" name="stockStatus" title="Out of stock product will not be shown in catalouge">
                <?php
                if ($stockStatus === 0) {
                    echo '<option value="1">In Stock</option>
        <option selected value="0">Out of Stock</option>';
                } else {
                    echo '<option selected value="1">In Stock</option>
        <option value="0">Out of Stock</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="visibility">Visibility</label>
            <select class="custom-select" id="visibility" name="visibility" title="Select if product should be visible in catalouge">
                <?php
                foreach ($productVisibIdValues as $attrId => $attrName) {
                    if ($visibility === (int) $attrId) {
                        echo '<option selected value="' . $attrId . '">' . $attrName . '</option>';
                    } else {
                        echo '<option value="' . $attrId . '">' . $attrName . '</option>';
                    }
                }
                ?>
            </select>
        </div>


        <div class="form-group">
            <label for="productDesc">Description</label>
            <textarea class="form-control" id="productDesc" name="productDesc" placeholder="Add a description for the product." title="Add a description for the product."><?php echo $productDesc; ?></textarea>
        </div>

        <div class="form-group">
            <fieldset class="border p-2">
                <legend class="w-auto">Bundle Items</legend>

                <div class="form-group">
                    <button type="button" id="addOption" class="btn btn-primary btn-sm">Add option</button>
                </div>

                <div id="bundleOptions" class="form-group"></div>
            </fieldset>
        </div>

        <div class="form-group">
            <button type="button" id="bundleadd" class="btn btn-primary">Add to Catalouge</button>
            <button type="reset" class="btn btn-danger">Reset form</button>
        </div>
    </form>
    <br>
    <br>
    <!-- Search and add item Modal -->
    <div class="modal fade" id="addItemsModal" tabindex="-1" role="dialog" aria-labelledby="addItemsModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Choose items for Bundle product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="addItemsModalBody">
                    <div class="form-group">
                        <input id="itemSearchTxt" class="form-control" type="text" placeholder="Type and press enter to Search, double click to add.">
                    </div>

                    <div class="form-group" id="searchResultsList"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
