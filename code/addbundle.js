/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

"user strict";
var optWhereToAdd;
var addOptTempl = `
<fieldset class="border p-2 my-4">
    <legend class="w-auto">Option</legend>
    <div name="bundleItem">
        <div class="row">
            <div class="col">
                <label for="email" class="mr-sm-2">Option title *</label>
                <input type="text" name="optionTitle" class="form-control" placeholder="Option title">
            </div>
            <div class="col">
                <label for="optInputType">Input type</label>
                <select class="custom-select" name="optionInputType">
                    <option value="select">Drop-down</option>
                    <option value="radio">Radio Buttons</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="multi">Multiple Select</option>
                </select>
            </div>
        </div>
        <ul class="my-4 list-group sortable" name="sortableList">
            <!--items will be added dynamically-->
        </ul>
        <div class="form-group text-right">
            <button type="button" class="btn btn-primary btn-sm" onClick="addProdToOption(this)">Add product to option</button>
            <button type="reset" class="btn btn-danger btn-sm" onClick="removeOption(this)">Delete option</button>
        </div>
    </div>
</fieldset>`;
$(document).ready(function () {

	/*
	 * Add option
	 */
	$("#addOption").click(function () {
		$("#bundleOptions").append(addOptTempl);
	});


	$("#searchResultsList").on("dblclick", ".list-group > a", function () {
		const parentElem = $(optWhereToAdd).parent().siblings('ul');
		var sku = $(this).data('sku');
		var name = $(this).data('name');
		var price = $(this).data('price');
		var elem = `<li class="list-group-item-action ui-state-default"
        data-sku="` + sku + `"
        data-name="` + name + `"
        data-price="` + price + `">
        <span class="ui-icon ui-icon-grip-dotted-vertical"></span>` +
			name + ` unit price: ` + price +
			`<span class="ui-icon ui-icon-closethick" onclick="removeItem(this)"></span>
        </li>`;
		$(parentElem).append(elem);
		$(parentElem).sortable();
		$(parentElem).disableSelection();
	});


	/*
	 * Search products
	 */
	$("#itemSearchTxt").on("keyup", function (ev) {
		var search = $("#itemSearchTxt").val();
		var keycode = (ev.keyCode ? ev.keyCode : ev.which);
		if (keycode === 13) {
			if (search.length >= 3) {
				$.ajax({
					method: "POST",
					url: "process.php",
					data: { "key": search }
				}).done(function (response) {
					response = JSON.parse(response);
					if (response.error) {
						alert(response.error);
					} else if (response.result === false) {
						alert('No items found, try again');
					} else {
						var list = '';
						$.each(response.result, function (key, value) {
							list += '<a class="list-group-item list-group-item-action" \n\
                            data-sku="' + value.sku + '"\n\
                            data-name="' + value.name + '"\n\
                            data-price="' + value.price + '">' +
								value.name + ' unit price:' + value.price +
								'</a>';
						});
						list = '<div class="list-group">' + list + '</div>';
						$("#searchResultsList").html(list);
					}
				});
			}
		}
	});


	/*
	 * handle form submit
	 */
	$('#bundleadd').click(function () {
		if(validateForm() !== true) {
			return false
		}
		const bundle = [];
		$('#bundleOptions > fieldset').each(function (i, option) {
			const optionTitle = $(this).children().find('input[name="optionTitle"]').val();
			const optionInputType = $(this).children().find('[name="optionInputType"]').val();
			const bundleItems = [];
			
			$(this).children().find('[name="sortableList"] > li').each(function () {
				var sku = $(this).data('sku');
				var name = $(this).data('name');
				var price = $(this).data('price');
				bundleItems.push({ 'sku': sku, 'name': name, 'price': price });
			});
			
			bundle.push({
				'optionTitle': optionTitle,
				'inputType': optionInputType,
				'items': bundleItems
			});
		});
		var data = {
			attributeSetId: $("#attributeSetId").val(),
			productName: $("#productName").val(),
			productSku: $("#productSku").val(),
			stockStatus: $("#stockStatus").val(),
			visibility: $("#visibility").val(),
			productDesc: $("#productDesc").val(),
			bundleItems: bundle,
			bundleProduct: true
		};
		
		$.ajax({
			url: 'process.php',
			type: "POST",
			data: data,
			success: function (data) {
				const result = JSON.parse(data);
				// console.log(result);
				if(result.status.toString() === 'success') {
					alert('Added Successfully.\nID:'+result.message.id+'\nSKU:'+result.message.sku+'\nName:'+result.message.name);
				}
				else {
					alert('Failed to add.\n'+result.message.toString());

				}
			}
		});
	});
});

function validateForm() {
	var string = "", focusSet = false;

	if($("#productName").val() === "") {
		string += "\nProduct Name cannot be empty";
		
		if(focusSet === false) {
			$("#productName").focus();
			focusSet = true;
		}
	}
	if($("#productSku").val() === "") {
		string += "\nSKU cannot be empty";

		if(focusSet === false) {
			$("#productSku").focus();
			focusSet = true;
		}
	}
	
	if($('#bundleOptions > fieldset').length) {
		$('#bundleOptions > fieldset').each(function (i, option) {
			const optionTitle = $(this).children().find('input[name="optionTitle"]');
			if(optionTitle.val() === "") {
				string += "\nOption title cannot be empty";

				if(focusSet === false) {
					$(optionTitle).focus();
					focusSet = true;
				}
				return false;
			}
		});
	}

	if(string === "") {
		return true;
	} else {
		string = "Please fix these problems first\n" + string;
		alert(string);
	}

	return false;
}

function removeItem(elem) {
	$(elem).parent('li').remove()
}

function removeOption(elem) {
	$(elem).closest('fieldset').remove()
}

function addProdToOption(elem) {
	optWhereToAdd = elem;
	$("#addItemsModal").modal('show');
}