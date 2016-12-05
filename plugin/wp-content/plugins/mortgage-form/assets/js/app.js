jQuery(document).on("click", "#refinance_btn", function(e) {
	var flag = new Array();
	$form = jQuery(this).closest('form');
	jQuery($form).find('.form-control:visible').each(function(){
		jQuery(this).next('.alert-danger').remove();
		
		$email = jQuery(this).val();
		if(jQuery(this).attr('type') == 'email'){
			if($email != ''){
				if(!validateEmail($email)) { 
					jQuery(this).after('<div class="text-left alert-danger">Enter Valid Email Address.</div>');
					flag.push('0');
				}
				else{
					flag.push('1');
					jQuery(this).next('.alert-danger').remove();
				}
			}
			else{
				jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
				flag.push('0');
			}
		}
		else{
			if(jQuery(this).val() != ''){
				flag.push('1');
				jQuery(this).next('.alert-danger').remove();
			}
			else{
				jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
				flag.push('0');
			}
		}
	});

	if(jQuery.inArray('0', flag) < 0){
		jQuery(this).after('<img src="'+mg_ajax.loader_image+'" class="loader_image_sec" />');
		jQuery(this).hide();
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_refinance&mode=step-1",
			type: 'post',
			data: data,
			success: function(response) {
				if(response != 'error'){
					response = jQuery.parseJSON(response);
					$response = jQuery(response.result);
					jQuery('#refinance_btn').closest('form').replaceWith($response);
					jQuery('#refinance_step_2').after('<input type="hidden" name="id" value="'+response.id+'" />'); 
					jQuery(this).show();
					remove_error_message();
				}
			}            
		});
	}
	return false;
});

jQuery(document).on("click", "#reverse_mortage_btn", function(e) {
	var flag = new Array();
	$form = jQuery(this).closest('form');
	jQuery($form).find('.form-control:visible').each(function(){
		jQuery(this).next('.alert-danger').remove();
		
		$email = jQuery(this).val();
		if(jQuery(this).attr('type') == 'email'){
			if($email != ''){
				if(!validateEmail($email)) { 
					jQuery(this).after('<div class="text-left alert-danger">Enter Valid Email Address.</div>');
					flag.push('0');
				}
				else{
					flag.push('1');
					jQuery(this).next('.alert-danger').remove();
				}
			}
			else{
				jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
				flag.push('0');
			}
		}
		else{
			if(jQuery(this).val() != ''){
				flag.push('1');
				jQuery(this).next('.alert-danger').remove();
			}
			else{
				jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
				flag.push('0');
			}
		}
	});

	if(jQuery.inArray('0', flag) < 0){
		jQuery(this).after('<img src="'+mg_ajax.loader_image+'" class="loader_image_sec" />');
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_reverse&mode=step-1",
			type: 'post',
			data: data,
			success: function(response) {
				if(response != 'error'){
					response = jQuery.parseJSON(response);
					$response = jQuery(response.result);
					jQuery('#reverse_mortage_btn').closest('form').replaceWith($response);
					jQuery('#reverse_mortgage_step_2').after('<input type="hidden" name="id" value="'+response.id+'" />'); 
					jQuery(this).show();
					remove_error_message();
				}
			}            
		});
	}
	return false;
});

jQuery(document).on("click", "#reverse_mortgage_step_2", function(e) {
	var flag = false;
	$form = jQuery(this).closest('form');
	jQuery($form).find('.form-control:visible').each(function(){
		jQuery(this).next('.alert-danger').remove();
		if(jQuery(this).val() != ''){
			flag = true;
			jQuery(this).next('.alert-danger').remove();
		}
		else{
			jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
			flag = false;
		}
	});
	
	if(flag){
		jQuery(this).after('<img src="'+mg_ajax.loader_image+'" class="loader_image_sec" />');
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_reverse&mode=step-2",
			type: 'post',
			data: data,
			success: function(response) {
				if(response){
					response = jQuery.parseJSON(response);
					$response = jQuery(response.result);
					jQuery('#reverse_mortgage_step_2').closest('form').replaceWith($response);
					jQuery('#reverse_mortgage_submit').after('<input type="hidden" name="id" value="'+response.id+'" />'); 
					remove_error_message();
				}
			}            
		});
	}
	return false;
});

jQuery(document).on("click", "#reverse_mortgage_submit", function(e) {
	var flag = false;
	$form = jQuery(this).closest('form');
	jQuery($form).find('.form-control:visible').each(function(){
		jQuery(this).next('.alert-danger').remove();
		if(jQuery(this).val() != ''){
			flag = true;
			jQuery(this).next('.alert-danger').remove();
		}
		else{
			jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
			flag = false;
		}
	});

	remove_error_message();
	
	if(flag){
		jQuery(this).after('<img src="'+mg_ajax.loader_image+'" class="loader_image_sec" />');
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_reverse&mode=step-3",
			type: 'post',
			data: data,
			success: function(response) {
				if(response){
					jQuery($form).submit();
				}
			}            
		});
	}
	return false;
});


jQuery(document).on("click", "#refinance_step_2", function(e) {
	var flag = false;
	$form = jQuery(this).closest('form');
	jQuery($form).find('.form-control:visible').each(function(){
		jQuery(this).next('.alert-danger').remove();
		if(jQuery(this).val() != ''){
			flag = true;
			jQuery(this).next('.alert-danger').remove();
		}
		else{
			jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
			flag = false;
		}
	});
	
	if(flag){
		jQuery(this).after('<img src="'+mg_ajax.loader_image+'" class="loader_image_sec" />');
		jQuery(this).hide();
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_refinance&mode=step-2",
			type: 'post',
			data: data,
			success: function(response) {
				if(response){
					response = jQuery.parseJSON(response);
					$response = jQuery(response.result);
					jQuery('#refinance_step_2').closest('form').replaceWith($response);
					jQuery('#refinance_submit').after('<input type="hidden" name="id" value="'+response.id+'" />'); 
					remove_error_message();
					autocomplete_city_value('city');
				}
			}            
		});
	}
	return false;
});

jQuery(document).on("click", "#refinance_submit", function(e) {
	var flag = false;
	$form = jQuery(this).closest('form');
	jQuery($form).find('.bankruptcy_forecloser_value:visible').each(function(){
		jQuery(this).find('.radio').next('.alert-danger').remove();
		if(jQuery(this).find('input[name=bankruptcy_forecloser_value]:checked').length <= 0){
			jQuery(this).find('.radio').after('<div class="text-left alert-danger">This is required field.</div>');
			flag = false;
		}
		else{
			flag = true;
			jQuery(this).find('.radio').next('.alert-danger').remove();
		}
	});
	jQuery($form).find('.form-control:visible').each(function(){
		jQuery(this).next('.alert-danger').remove();
		if(jQuery(this).val() != ''){
			flag = true;
			jQuery(this).next('.alert-danger').remove();
		}
		else{
			jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
			flag = false;
		}
	});

	remove_error_message();

	
	if(flag){
		jQuery(this).after('<img src="'+mg_ajax.loader_image+'" class="loader_image_sec" />');
		jQuery(this).hide();
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_refinance&mode=step-3",
			type: 'post',
			data: data,
			success: function(response) {
				if(response){
					jQuery($form).submit();
				}
			}            
		});
	}
	return false;
});

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}

jQuery(document).ready(function($){
	remove_error_message();
	autocomplete_city_value('city');
});

function remove_error_message(){
	jQuery('.refinance_form_container').find('.form-control').each(function(i, v){
		
		jQuery(this).on('keyup change', function(){

			jQuery(this).next('.alert-danger').remove();
		});
	});

	jQuery('.mortgage_form_container').find('.form-control').each(function(i, v){
		jQuery(this).on('keyup change', function(){
			jQuery(this).next('.alert-danger').remove();
		});
	});
}

jQuery(document).on('click', '#if_second_mortgage', function(e){
	if(jQuery(this).is(':checked')){
		jQuery('#second_mortgage_section').show();
	}
	else{
		jQuery('#second_mortgage_section').hide();
		jQuery('#second_mortgage_section').next('.alert-danger').remove();
	}
});

jQuery(document).on('click', '#military_serve', function(e){
	if(jQuery(this).is(':checked')){
		jQuery('.va_loan_section.vc_active').show();
	}
	else{
		jQuery('.va_loan_section').hide();
		jQuery('.va_loan_section input').removeAttr('checked');
	}
});

jQuery(document).on('click', '#va_loan', function(e){
	if(jQuery(this).is(':checked')){
		jQuery('.bankruptcy_forecloser.bank_active').show();
	}
	else{
		jQuery('.bankruptcy_forecloser').hide();
		jQuery('.bankruptcy_forecloser input').removeAttr('checked');
	}
});

jQuery(document).on('click', '#bankruptcy_forecloser', function(e){
	if(jQuery(this).is(':checked')){
		jQuery('.bankruptcy_forecloser_value.bank_sec_active').show();
	}
	else{
		jQuery('.bankruptcy_forecloser_value').hide();
		jQuery('.bankruptcy_forecloser_value input').removeAttr('checked');
	}
});

jQuery(document).on("click", "#mortage_btn", function(e) {
	var flag = new Array();
	$form = jQuery(this).closest('form');
	jQuery($form).find('.form-control:visible').each(function(){
		jQuery(this).next('.alert-danger').remove();
		
		$email = jQuery(this).val();
		if(jQuery(this).attr('type') == 'email'){
			if($email != ''){
				if(!validateEmail($email)) { 
					jQuery(this).after('<div class="text-left alert-danger">Enter Valid Email Address.</div>');
					flag.push('0');
				}
				else{
					flag.push('1');
					jQuery(this).next('.alert-danger').remove();
				}
			}
			else{
				jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
				flag.push('0');
			}
		}
		else{
			if(jQuery(this).val() != ''){
				flag.push('1');
				jQuery(this).next('.alert-danger').remove();
			}
			else{
				jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
				flag.push('0');
			}
		}
	});

	if(jQuery.inArray('0', flag) < 0){
		jQuery(this).after('<img src="'+mg_ajax.loader_image+'" class="loader_image_sec" />');
		jQuery(this).hide();
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_mortgage&mode=step-1",
			type: 'post',
			data: data,
			success: function(response) {
				if(response != 'error'){
					response = jQuery.parseJSON(response);
					$response = jQuery(response.result);
					jQuery('#mortage_btn').closest('form').replaceWith($response);
					jQuery('#mortgage_step_2').after('<input type="hidden" name="id" value="'+response.id+'" />'); 
					remove_error_message();
					autocomplete_city_value('property_city');
				}
			}            
		});
	}
	return false;
});

jQuery(document).on("click", "#mortgage_step_2", function(e) {
	var flag = new Array();
	$form = jQuery(this).closest('form');
	jQuery($form).find('.form-control:visible').each(function(){
		jQuery(this).next('.alert-danger').remove();
		if(jQuery(this).val() != ''){
			flag.push('1');
			jQuery(this).next('.alert-danger').remove();
		}
		else{
			jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
			flag.push('0');
		}
	});

	if(jQuery.inArray('0', flag) < 0){
		jQuery(this).after('<img src="'+mg_ajax.loader_image+'" class="loader_image_sec" />');
		jQuery(this).hide();
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_mortgage&mode=step-2",
			type: 'post',
			data: data,
			success: function(response) {
				if(response != 'error'){
					response = jQuery.parseJSON(response);
					$response = jQuery(response.result);
					jQuery('#mortgage_step_2').closest('form').replaceWith($response);
					jQuery('#mortgage_submit').after('<input type="hidden" name="id" value="'+response.id+'" />'); 
					remove_error_message();
					autocomplete_city_value('city');
				}
			}            
		});
	}
	return false;
});

jQuery(document).on("click", "#mortgage_submit", function(e) {
	var flag = false;
	$form = jQuery(this).closest('form');
	jQuery($form).find('.form-control').each(function(){
		jQuery(this).next('.alert-danger').remove();
		if(jQuery(this).val() != ''){
			flag = true;
			jQuery(this).next('.alert-danger').remove();
		}
		else{
			jQuery(this).after('<div class="text-left alert-danger">This is required field.</div>');
			flag = false;
		}
	});

	remove_error_message();

	
	if(flag){
		data = $form.serialize();
		jQuery.ajax({
			url: mg_ajax.url+"?action=mg_mortgage&mode=step-3",
			type: 'post',
			data: data,
			success: function(response) {
				if(response != 'error'){
					jQuery($form).submit();
				}
			}            
		});
	}
	return false;
});

function log( message ) {
	jQuery( "#city" ).val( message );
}

function autocomplete_city_value(elem) {
	jQuery( "#"+elem ).autocomplete({
		source: function( request, response ) {
			jQuery.ajax({
				url: "http://gd.geobytes.com/AutoCompleteCity?callback=?&filter=US",
				dataType: "jsonp",
				data: {
					q: request.term
				},
				success: function( data ) {
					response( data );
				}
			});
		},
		minLength: 3,
		select: function( event, ui ) {
			log( ui.item ?
			"Selected: " + ui.item.label :
			"Nothing selected, input was " + this.value);
		},
		open: function() {
			jQuery( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			jQuery( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
}

function ValidateUsPhoneNumber() {
    document.getElementById('Telephone').addEventListener('input', function (e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });
}

function checkphonenumbervalidation(elem) {
	jQuery(elem).next('.alert-danger').remove();
	if(jQuery(elem).val().length > 0){
		if(jQuery(elem).val().length < 14){
			jQuery(elem).after('<div class="text-left alert-danger">Enter Valid Phone Number.</div>');
		}
		else{
			jQuery(elem).next('.alert-danger').remove();
		}
	}
}
