function view_this_item(id){
	jQuery.ajax({
		url: mg_ajax.url+"?action=get_record",
		type: 'post',
		data: {id:id},
		success: function(response) {
			if(response){
				jQuery('#view_result_block').html(response);
				 tb_show( 'Refinance Form Record', '#TB_inline?width=750&height=480&inlineId=view_result_block' );
			}
		}            
	});
}

function view_this_item_mortgage(id){
	jQuery.ajax({
		url: mg_ajax.url+"?action=get_record_mortgage",
		type: 'post',
		data: {id:id},
		success: function(response) {
			if(response){
				jQuery('#view_result_block').html(response);
				 tb_show( 'Mortgage Form Record', '#TB_inline?width=750&height=480&inlineId=view_result_block' );
			}
		}            
	});
}

function view_this_reverse_item(id){
	jQuery.ajax({
		url: mg_ajax.url+"?action=get_record_reverse",
		type: 'post',
		data: {id:id},
		success: function(response) {
			if(response){
				jQuery('#view_result_block').html(response);
				 tb_show( 'Reverse Mortgage Form Record', '#TB_inline?width=750&height=480&inlineId=view_result_block' );
			}
		}            
	});
}

jQuery(document).ready(function(){
	jQuery('#form_filter').change(function(){
		jQuery(this).closest('form').submit();
	});
});
