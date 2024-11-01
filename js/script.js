jQuery(document).ready(function(){	
	jQuery(".wp-quick-contact-area").css('display','none');
	jQuery(".wp-quick-contact-head").click(function(){
		if(jQuery(".wp-quick-contact-area").css('display') == 'none' ){
			jQuery(".wp-quick-contact-area").show("slow");
			jQuery(".slide-indicate").addClass("slideUp");
		}else{
			jQuery(".wp-quick-contact-area").hide("slow");
			jQuery(".slide-indicate").removeClass("slideUp");
		}
	});
	
	jQuery('.wp-qc-loading').css('visibility','hidden');
	jQuery('.wpqc-success').css('display','none');	
	
	jQuery("#wp-quick-contact-form").submit(function(){
		jQuery('.wpqc-success').css('display','none');										 
		var str = jQuery(this).serialize();
		jQuery('.wp-qc-loading').css('visibility','visible');
		jQuery.ajax({
			type: "POST",
			url: WPCVajax.ajaxurl,
			data: 'action=wpQuickContact&'+str,
			dataType: "json",
			success: function(data){
			 if(data.result == 'OK'){
			 //  jQuery('form').clearForm();
			   jQuery('.wpqc-success').fadeIn(1000);
			 }else{
			    jQuery(':input.wpqc-error').removeClass('wpqc-error');
				jQuery.each(data.errors, function(i,item){
				  jQuery(item.id).addClass('wpqc-error');
				});
			 }
			 jQuery('.wp-qc-loading').css('visibility','hidden');	
			}
	    });
		return false;
	});	
	
});



jQuery.fn.clearForm = function() {
  jQuery('cname').val('Full Name');
  jQuery('cphone').val('Phone Number');
  jQuery('cemail').val('Email Address');
  jQuery('csubject').val('Subject');
  jQuery('cmsg').val('Message');
};

