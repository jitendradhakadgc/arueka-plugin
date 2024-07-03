
	 
		//Confirm approve
	 function confirmApprove(message) {
            if (confirm(message)) {
                return true; // Proceed with link action
            } else {
                return false; // Cancel link action
            }
        }
var newPrefix;

function updateInputNames(selectedDropdown) {
	
    var newPrefix = $(selectedDropdown).val(); // Get the selected value
    //var addonContainer = $(selectedDropdown).closest('.gc-wrap'); // Find the closest container for the addon group
    
    var permision = $(selectedDropdown).siblings('.gc-addon-permission'); 
	$(permision).attr('name', newPrefix + '_permission');
	
	var addonWrapper = $(selectedDropdown).closest('.gc-dropdown-addon').siblings('.gc-input-row'); 
	// Find the sibling input row container
    
    addonWrapper.find('.gc-addon-input').each(function(index, input) {
        var originalName = $(input).data('original-name'); // Get the original name from data attribute
		//alert(originalName);
        $(input).attr('name', newPrefix + '_' + originalName); // Set the new name with the updated prefix within the correct sibling container
    });
}
    function addNewAddon() {
		    // Clone the original form
		var originalForm = document.querySelector('.gc-settings-form-cont');
		
		var newForm = originalForm.cloneNode(true);

		// Get the container
		//var container = document.querySelector('.gc-wrap');

		// Insert the new form at the top of the container
		//container.insertBefore(newForm, container.firstChild);
		document.querySelector('.gc-settings-form-new').appendChild(newForm);
		// Show the new form
		newForm.style.display = 'block';
		var save_btn = document.querySelector('.gc-save-addons-first');
		save_btn.style.display = 'block'; 
		
           /*  // Clone the original form
            var originalForm = document.querySelector('#gc-settings-form');
            var newForm = originalForm.cloneNode(true);
            // Append the new form to the DOM
            document.querySelector('.gc-wrap').appendChild(newForm);
            // Show the new form
            newForm.style.display = 'block';
            // Move the "Add New Addon" button below the new form
           //document.querySelector('.gc-wrap').appendChild(document.querySelector('.add-addon')); */

        
    }
	function addNewAddon2() {
            // Clone the original form
		var originalForm = document.querySelector('.gc-settings-form-cont');
		
		var newForm = originalForm.cloneNode(true);

		// Get the container
		//var container = document.querySelector('.gc-wrap');

		// Insert the new form at the top of the container
		//container.insertBefore(newForm, container.firstChild);
		document.querySelector('.gc-settings-form-new').appendChild(newForm);
		// Show the new form
		newForm.style.display = 'block';
		var save_btn = document.querySelector('.gc-save-addons-first');
		save_btn.style.display = 'block';    
    }

        function removeAddon(button) {
            // Remove the entire addon section
            $(button).closest('.gc-top-panel').parent().remove();
        }

        function toggleAddon(button) {
            // Toggle the visibility of the addon content
            var addonContent = $(button).closest('.gc-top-panel').siblings('.gc-dropdown-addon, .gc-input-row');
            addonContent.toggle();

            // Update the caret icon based on visibility
            var caretIcon = $(button).find('i');
            if (addonContent.is(':visible')) {
                // Add class for upside-down caret when visible
                caretIcon.removeClass('fa-caret-down').addClass('fa-caret-up');
            } else {
                // Add class for normal caret when hidden
                caretIcon.removeClass('fa-caret-up').addClass('fa-caret-down');
            }
        }
(function($) {
        jQuery(document).ready(function () {
			
			jQuery(document).on("click", ".gc-deny-user", function(event) {
				jQuery('.gcunique-popup').css('display', 'none');
				event.preventDefault();
				 var vendorId = jQuery(this).attr('vendor_id');
				 var denyUrl = 'admin.php?page=vendor_view_page&vendor_id=' + vendorId + '&deny_vendor=true';
				//alert(vendorId);
				//jQuery('.gc-'+vendorId).css('display', 'block');
				jQuery('.gcunique-popup').css('display', 'block');
				jQuery('.gcunique-form').attr('action', denyUrl);
				
				
			});
			
			var responseObjectmail; 
			jQuery(document).on("click", ".gc-view-users", function(event) {
				jQuery('.gcunique-popup2').css('display', 'none');
				jQuery('.gc-business-hide').css('display', 'none');
				event.preventDefault();
				 var vendorId = jQuery(this).attr('vendor_id');
				 jQuery('.loading-indicator').show();
				 $.ajax({
					url: ajax_object.ajax_url, // WordPress AJAX URL
					type: 'post',
					data: { 
						action: 'vendor_view_popup', // Action to be performed
						vendor_id: vendorId // Data to be sent
					},
					success: function(response) {
					  var responseObject = JSON.parse(response);
					  console.log(response);
					  responseObjectmail = responseObject.business_email[0];
					 
					  if(responseObject.business_license[0]=='yes'){
						
						jQuery('.gc-business-hide').css('display', 'block');
						
						jQuery(".gc-business-licence-link").attr("href", responseObject.cert_name[0])
					  }
					  if (responseObject.telephone_number && responseObject.telephone_number.length > 0 && responseObject.telephone_number[0] !== "") {
						jQuery('#telephone_numberpop').val(responseObject.telephone_number[0]);
					  }
					  if(responseObject.businessTelephone[0]){
						jQuery('#telephone_numberpop').val(responseObject.businessTelephone[0]);
					  }
					  jQuery('.gcunique-popup2').css('display', 'block');
					  jQuery('#store_name').val(responseObject.store_name[0]);
                      jQuery('#business_license').val(responseObject.business_license[0]);
					  jQuery('#business_country').val(responseObject.business_country[0]);
                      jQuery('#business_city').val(responseObject.business_city[0]);
					  jQuery('#business_settlement').val(responseObject.business_settlement[0]);
					  jQuery('#gc-pop-industry').val(responseObject.industry);
					  
					  jQuery('.id_img_url').val(responseObject.id_img_url[0]);
					  jQuery('.id_img_url_preview').attr('src', responseObject.id_img_url[0]);
					  jQuery('#business_telephone').val(responseObject.business_telephone[0]);
					  jQuery('#owner_first_namepop').val(responseObject.owner_first_name[0]);
					  jQuery('#owner_last_namepop').val(responseObject.owner_last_name[0]);
                      jQuery('#business_emailpop').val(responseObject.business_email[0]);
					  jQuery('#date_of_birthpop').val(responseObject.gc_dob[0]);
					  jQuery('.loading-indicator').hide();
					},
					error: function(xhr, status, error) {
						console.error('Error:', error);
						// Hide loading indicator
						jQuery('.loading-indicator').hide();
					}
				});
				
			});
							
				// Define a function to show the loader
				function showLoader() {
					$('.loading-indicator').show();
				}

				// Define a function to hide the loader
				function hideLoader() {
					$('.loading-indicator').hide();
				}

				$('#send-mail-btn').click(function() {
					var email = responseObjectmail;
					
					// Show the loader before sending the email
					showLoader();
					
					if (email) {
						$.ajax({
							url: ajax_object.ajax_url,
							method: 'POST',
							data: {
								action: 'vendor_view_send_mail_popup',
								business_email: email
							},
							success: function(response) {
								// Hide the loader after the email is sent
								hideLoader();
								$('#mail-message-pop').text('Email sent successfully.').removeClass('error').addClass('success').show(); // Make sure to show the message
							},
							error: function(xhr, status, error) {
								console.error('Error sending email:', error);
								// Hide the loader in case of an error
								hideLoader();
								$('#mail-message-pop').text('Error sending email.').removeClass('success').addClass('error').show(); // Make sure to show the message
							}
						});
					} else {
						// Hide the loader if email address is not available
						hideLoader();
						$('#mail-message-pop').text('Email address is not available.').removeClass('success').addClass('error').show(); // Make sure to show the message
						alert('Email address is not available.');
					}
				});
			
			jQuery(document).on("click", ".gcunique-close-btn-x", function() {
				jQuery('.gcunique-popup').css('display', 'none');
				
			});
			jQuery(document).on("click", ".gcunique-close-btn-xx", function() {
				jQuery('.gcunique-popup2').css('display', 'none');
				jQuery('#mail-message-pop').css('display', 'none');
			});
			
			jQuery(document).on("click", ".gcunique-close-btn-pop", function() {
				jQuery('.gcunique-popup2').css('display', 'none');
				jQuery('#mail-message-pop').css('display', 'none');
			});
			
            // Trigger updateInputNames for initial state
            jQuery('.gc-addon-dropdown').each(function(index, dropdown) {
                updateInputNames(dropdown);
            });
			
			
			/* subscription */
			
			/**
	 * Handles the click event for decrement buttons.
	 * Decrements the value of the input field by 1, respecting the minimum value if specified.
	 *
	 * @param {Object} event - The click event object.
	 */
	jQuery(document).on("click", ".gc-decrement", function(event) {
		// Get the input field and its current value
		var inputField = jQuery(this).siblings('.gc-quantity-input');
		var addonPrice = parseFloat(jQuery(this).siblings('.gc-quantity-input').attr('addon_price')); // Use parseFloat for addonPrice
		var currentValue = parseInt(inputField.val());

		// Get the minimum value from the input field's min attribute
		var minValue = parseInt(inputField.attr('min'));

		// Check if the current value is greater than the minimum
		if (currentValue > minValue) {
			// Decrement the value by 1
			var decQuantity = currentValue - 1;
			inputField.val(decQuantity);
			var totalPrice = decQuantity * addonPrice;
			jQuery(this).parent('.gc-quantity-controls').siblings('.gc-addon-price').children('p').children('.gc_change_price').text(totalPrice);
			var price = jQuery(this).parent('.gc-quantity-controls').siblings('.Gc_add_check').show();
			
			var price = jQuery(this).parent('.gc-quantity-controls').siblings('.gc-hide-button').hide();
		}
		else{
			//var price = jQuery(this).parent('.gc-quantity-controls').siblings('.Gc_add_check').hide();
			
			//var price = jQuery(this).parent('.gc-quantity-controls').siblings('.gc-hide-button').show();
		}
	});
	/**
	 * Handles the click event for increment buttons.
	 * Increments the value of the input field by 1, respecting the maximum value if specified.
	 */
	jQuery(document).on("click", ".gc-increment", function() {
		// Get the input field and its current value
		var inputField = jQuery(this).siblings('.gc-quantity-input');
		var addonPrice = parseFloat(jQuery(this).siblings('.gc-quantity-input').attr('addon_price')); // Use parseFloat for addonPrice
		var currentQuantity = parseInt(inputField.val());
		var maxValue = parseInt(inputField.attr('max'));

		// Check if the current value is less than the maximum
		if (currentQuantity < maxValue) {
			// Increment the value by 1
			var incrementedQuantity = currentQuantity + 1;
			inputField.val(incrementedQuantity);
			var totalPrice = incrementedQuantity * addonPrice;
			jQuery(this).parent('.gc-quantity-controls').siblings('.gc-addon-price').children('p').children('.gc_change_price').text(totalPrice);
			var price = jQuery(this).parent('.gc-quantity-controls').siblings('.Gc_add_check').show();
			
			var price = jQuery(this).parent('.gc-quantity-controls').siblings('.gc-hide-button').hide();
			
			
			jQuery('#gcunique-quantity-msg').val(incrementedQuantity);
			//get the name
		var title = jQuery(this).parent('.gc-quantity-controls').siblings('.gc-addon-title').attr('addon_title');
		
		jQuery('#gcunique-addon').val(title);
			
			
		}
		else{
			var price = jQuery(this).parent('.gc-quantity-controls').siblings('.Gc_add_check').hide();
			
			var price = jQuery(this).parent('.gc-quantity-controls').siblings('.gc-hide-button').show();
			
			
		}
	}); 
			
			
			/* subscription */			
/**
 * Handles the click event on the "" button.
 */

    $('.Gc_send_a_quote').on('click', function() {
        var addonData = [];
		var checked=false;
		var vendor_email= $(this).attr("vendor_email");
        // Iterate over each addon container
        $('.gc-addon-container').each(function(index) {
            var isChecked = $(this).find('.Gc_add_check').is(':checked');
            
            // If checkbox is checked, collect addon data
            if (isChecked) {
				checked=true;
				var addonName = $(this).find('.gc-addon-title').attr('addon_title');
				var productId = $(this).find('.Gc_add_check').data('product-id');
				 var quantity = $(this).find('.gc-quantity-input').val();
				var price = $(this).find('.gc_change_price').val();
				
                addonData.push({
                    addon_name: addonName,
                    addon_quantity: quantity,
                    addon_price: price,
                    product_id: productId
                });
            }
        });
		console.log(addonData);
if(checked==true){
	jQuery('#loader_image').show();
	// Send addonData to server using AJAX
         $.ajax({
            url: blog.ajaxurl,
            method: 'POST',
            data: {
				action: 'gc_send_a_quote',
                addon_data: addonData,
                vendor_email: vendor_email,
				main: 'no',
            },
            success: function(response) {
				
                // Handle success response
                //console.log(response);
				alert('Email send to vendor with url and addons details');
				jQuery('#loader_image').hide();
				//window.location.href = response.data;
				
            },
            error: function(xhr, status, error) {
				jQuery('#loader_image').hide();
                // Handle error
                console.error(xhr.responseText);
            }
        });
}
else{
	alert('please select an addons');
}    
    });		
jQuery(document).ready(function($) {
    var post_Id;
    var post_Mail;
    var vendor_auther_mail;
    
    jQuery(document).on("click", ".gcunique-popupindustry", function(event) {
        jQuery('.gcunique-popup-industry').css('display', 'none');
        event.preventDefault();
        var cat_post_id = jQuery(this).data('edit-post-id');
        var post_title = jQuery(this).data('post-title');
        var postId = jQuery(this).data('post-id');
        var vendor_auther_mail = jQuery(this).data('auther-mail');
        post_Id = postId;
        post_Mail = vendor_auther_mail;
        console.log('cat_post_id:', cat_post_id);
        console.log('post_Id:', post_Id);
        console.log('vendor_auther_mail:', vendor_auther_mail);
        jQuery('.gcunique-popup-industry').css('display', 'block');
        jQuery('.popup-post-title').val(post_title);
        jQuery('.popup-user-mail').val(vendor_auther_mail);
    });

    jQuery(document).on("click", ".gcunique-close-btn-industry", function() {
        jQuery('.gcunique-popup-industry').css('display', 'none');
    });

    $('#assign-category-btn').on('click', function(event) {
        event.preventDefault();
        var selectedCategoryId = $('#gc-industry').val(); // Get the selected category ID
        var post_id = post_Id; // Use the previously declared variable
        console.log('selectedCategoryId:', selectedCategoryId);
        console.log('post_Id:', post_Id); // Use the previously declared variable
        console.log('post_Mail:', post_Mail); // Use the previously declared variable
        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            type: 'POST',
            dataType: 'json', // Expect JSON response
            data: {
                action: 'save_selected_category',
                category_id: selectedCategoryId,
                post_id: post_id,
                vendor_auther_mail: post_Mail // Use the previously declared variable
            },
            success: function(response) {
                console.log('AJAX Response:', response.data);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
});
});
})(jQuery);


