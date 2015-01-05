jQuery(document).ready(function($) {
    $('#payboard-apikey-form').submit(function() {        
        
            $("#payboard-apikey-form-spiner").show();
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: jQuery("#payboard-apikey-form").serialize(),
                success: function(data) {
                    $("#payboard-apikey-form-spiner").hide();
                    
                    if(data.status){
                        jQuery("#apikey_status_message").css('color','green');
                    }else{
                        jQuery("#apikey_status_message").css('color','red');
                    }
                    
                    jQuery("#apikey_status_message").html(data.message);
                    jQuery("#apikey_status_message").show();
                }
            });
        
        return false; //prevent default submit thing
    }); 
});