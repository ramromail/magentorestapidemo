/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

"user strict";

$(document).ready(function () {

	/*
	 * handle form submit
	 */
	$('#simpleAdd').click(function () {
		if(validateForm() !== true) {
			return false
		}
		
		var data = {
			attributeSetId: $("#attributeSetId").val(),
			productName: $("#productName").val(),
			productSku: $("#productSku").val(),
			productPrice: $("#productPrice").val(),
			taxClass: $("#taxClass").val(),
			productQty: $("#productQty").val(),
			stockStatus: $("#stockStatus").val(),
			weight: $("#weight").val(),
			visibility: $("#visibility").val(),
			productDesc: $("#productDesc").val(),
			simpleProduct: true
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
	if($("#productPrice").val() === "") {
		string += "\nPrice cannot be empty";

		if(focusSet === false) {
			$("#productPrice").focus();
			focusSet = true;
		}
	}

	if(string === "") {
		return true;
	} else {
		string = "Please fix these problems first\n" + string;
		alert(string);
	}

	return false;
}
