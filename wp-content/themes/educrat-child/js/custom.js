
/* ------------- Login Button JS -----------*/


jQuery(function ($) {
	
	waitForElm(".course-item").then(() => {
		
		const classNamePattern = /course-item-\d+\s+current/;
		
		// Find elements with class name matching the pattern
		const elements = document.querySelectorAll(".course-item");

		// Check if each element's class matches the pattern
		elements.forEach(function (element) {
			if (classNamePattern.test(element.className)) {
				// const closestParent = element.closest('li');
				const closestParent = element?.closest(".section")
				closestParent?.classList?.remove("closed");
				

				// Perform actions on the matching element
			}
		});
	})

	function waitForElm(selector) {
		return new Promise(resolve => {
			if (document.querySelector(selector)) {
				return resolve(document.querySelector(selector));
			}

			const observer = new MutationObserver(mutations => {
				if (document.querySelector(selector)) {
					observer.disconnect();
					resolve(document.querySelector(selector));
				}
			});

			observer.observe(document.body, {
				childList: true,
				subtree: true
			});
		});
	}

	$(document).ready(function() {
        $("#fromDate, #toDate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
    });


	$(document).ready(function() {
	    $("#insert_order_form").on("submit", function(event) {
	        event.preventDefault();

	        var courseId = $("#course_selected").val();
	        var fromDate = $("#fromDate").val();
	        var toDate = $("#toDate").val();

	        if (fromDate > toDate) {
	            $("#fromDateError").text("From date must be before To date");
	            return; // Prevent further execution if validation fails
	        } else {
	            $("#fromDateError").text("");
	        }

	        $.ajax({
	            url: 'http://localhost/defguru/wp-admin/admin-ajax.php',
	            method: 'POST',
	            data: {
	                action: 'get_order_count_results',
	                course_id: courseId,
	                from_Date: fromDate,
	                to_Date: toDate
	            },
	            success: function(response) {
	                $('#course_result').html(response);
	            },
	            error: function(xhr, status, error) {
	                console.error(error);
	            }
	        });
	    });
	});


	$(document).ready(function() {
	    $('input[name="custom_verification"]').change(function() {
	        if ($(this).is(':checked')) {
	            var keycloakLoginURL = 'https://signin.definedgebroking.com/auth/realms/debroking/protocol/openid-connect/auth';
	            var state = generateRandomState();
	            /*var codeVerifier = generateCodeVerifier();
	            var codeChallenge = generateCodeChallenge(codeVerifier);*/
	            var query_params = {
	                'response_type': 'code',
	                'scope': 'email profile openid offline_access',
	                'client_id': 'GURUKUL',
	                'state': state,
	                /*'code_challenge': codeChallenge,
	                'code_challenge_method': 'S256', // Use PKCE with SHA-256
	                'redirect_uri': 'http://gurukul.definedge.local:9096/validate-demat-account/'*/
	                'redirect_uri': 'http://gurukul.definedge.local:9096/checkout/'
	            };
	            var redirectURL = keycloakLoginURL + '?' + $.param(query_params);
	            console.log(redirectURL);

	            // Redirect the page
            	window.location.href = redirectURL;


	            /*// Open in a popup window
	            var popupWidth = 600;
	            var popupHeight = 400;
	            var left = (screen.width - popupWidth) / 2;
	            var top = (screen.height - popupHeight) / 2;
	            var popupWindow = window.open(redirectURL, '_blank', 'width=' + popupWidth + ', height=' + popupHeight + ', top=' + top + ', left=' + left);
	            
	            if (!popupWindow) {
	                alert('Popup blocked! Please enable popups for this site.');
	            }*/
	        }
	    });
	});

	function generateRandomState() {
	    // Generate a random string of characters
	    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    var state = '';
	    for (var i = 0; i < 32; i++) {
	        state += chars.charAt(Math.floor(Math.random() * chars.length));
	    }
	    return state;
	}

	/*function generateCodeVerifier() {
	    // Generate a random string of characters for code verifier
	    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._~';
	    var verifier = '';
	    for (var i = 0; i < 128; i++) {
	        verifier += chars.charAt(Math.floor(Math.random() * chars.length));
	    }
	    return verifier;
	}

	function generateCodeChallenge(verifier) {
	    // Generate code challenge from code verifier using SHA-256 and base64url encoding
	    var shaObj = new jsSHA('SHA-256', 'TEXT');
	    shaObj.update(verifier);
	    var hash = shaObj.getHash('B64');
	    var codeChallenge = base64urlencode(hash);
	    return codeChallenge;
	}*/

	function base64urlencode(str) {
	    // URL-safe base64 encoding
	    return str.replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
	}


	/*// After successful login, send user data to the parent window
	var userData = {
	    firstName: 'John',
	    lastName: 'Doe',
	    // Add other user data as needed
	};
	window.opener.postMessage(JSON.stringify(userData), 'http://gurukul.definedge.local:9096/validate-demat-account/');

	// Listen for messages from the popup window
	window.addEventListener('message', function(event) {
	    // Check if the message is from the expected origin
	    if (event.origin === 'http://gurukul.definedge.local:9096/validate-demat-account/') {
	        // Assuming the data sent from the popup is in JSON format
	        var userData = JSON.parse(event.data);

	        // Now you have the user data, you can do whatever you want with it
	        console.log(userData);

	        // Example: You can update the checkout page with the user's data
	        updateCheckout(userData);
	    }
	});*/

	
/*

	$(document).ready(function() {
	    $("#export_order_form").on("submit", function(event) {
	        event.preventDefault();

	        var courseId_csv = $("#course_selected_Export").val();
	        var fromDate_csv = $("#fromDateExport").val();
	        var toDate_csv = $("#toDateExport").val();

	        if (fromDate_csv > toDate_csv) {
	            $("#fromDateError").text("From date must be before To date");
	            return; 
	        } else {
	            $("#fromDateError").text("");
	        }

	        $.ajax({
	            url: 'http://localhost/defguru/wp-admin/admin-ajax.php',
	            method: 'POST',
	            data: {
	                action: 'coursewise_export_csv',
	                course_id_csv: courseId_csv,
	                from_Date_csv: fromDate_csv,
	                to_Date_csv: toDate_csv
	            },
	            success: function(response) {
	            	alert('success');
	                //$('#course_result').html(response);
	            },
	            error: function(xhr, status, error) {
	                console.error(error);
	            }


	        });


		});
	});
*/

	/*$(document).ready(function($) {
		$('#courseexportButton').click(function() {
	        $.ajax({
	            url: 'http://localhost/defguru/wp-admin/admin-ajax.php',
	            type: 'GET',
	            data: {
	                action: 'coursewise_export_csv'
	            },
	            success: function(response) {
	                // Trigger file download
	                var blob = new Blob([response]); 
	                var link = document.createElement('a');
	                link.href = window.URL.createObjectURL(blob);
	                link.download = 'exported_course_data.csv';
	                link.click();
	            }
	        });
	    });
	});
*/
	/*
	    // When the dropdown selection changes
            $('#course_selected').change(function() {
                var courseId = $(this).val(); // Get the selected course ID
                // Send AJAX request to the PHP script
                $.ajax({
                    url: 'http://localhost/defguru/wp-admin/admin-ajax.php',
                    method: 'POST',
                    data: {
                        action: 'get_course_results',
                        course_id: courseId
                    },
                    success: function(response) {
                        // Display the query results on the page
                        $('#course_result').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });


                $.ajax({
                    url: 'http://localhost/defguru/wp-admin/admin-ajax.php',
                    method: 'POST',
                    data: {
                        action: 'get_order_count_results',
                        course_id: courseId
                    },
                    success: function(response) {
                        // Display the query results on the page
                        $('#order_count_result').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });



            });
	*/


	/*$(".comment-reply-link").click(function() {
		$(".reply_comment_form").removeClass('hidden');
		$("#review_form").addClass('hidden');
        

    });*/





	/* 
	var nCourse = $(".course-single-tab.nav li").length;
	var wCourse = (100/nCourse);
	$(".course-single-tab.nav li").width(wCourse+'%');
	*/
	/*
	$(document).ready(function() {
	    
			$('.btn-add-course-to-cart').click(function(){
		    
			var cartCount = $('.mini-cart span.count').text();
			alert(cartCount);
			var timesClicked = cartCount;*/
	/*
	 $(document.body).trigger('wc_fragments_refreshed');
	 setTimeout(function () {
		location.reload(true);
	 }, 3000);
	 
    
	 }); 
 
}); 
*/


	/*$(document).ready(function() {
	  // Add class to all <li> elements except the first one
	  //$("li:not(:first)").addClass("closed");
	  $(".curriculum-sections li:not(:first)").addClass("closed");
	});*/



	/*
   $(document).ready(function($) {
	   // Function to check if form fields are empty
	   $('#billing_phone').on('input', function() {
			   var inputphone=$(this);
			   var rephone = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
			   var is_phone=rephone.test(inputphone.val());
			   if(is_phone){inputphone.removeClass("invalid").addClass("valid");}
			   else{inputphone.removeClass("valid").addClass("invalid");}
		   });
	   	
		   $('#billing_email').on('input', function() {
			   var input=$(this);
			   var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
			   var is_email=re.test(input.val());
			   if(is_email){input.removeClass("invalid").addClass("valid");}
			   else{input.removeClass("valid").addClass("invalid");}
		   });
   	
   	
	   function areFormFieldsEmpty() {
	   	
	   	
		   var isEmpty = false;
		   // Add the IDs or classes of the required form fields
		   var requiredFields = ['#billing_first_name', '#billing_last_name', '#billing_phone', '#billing_email'];
		   // Check if any of the required fields are empty
	   	
		   requiredFields.forEach(function(field) {
			   if ($(field).val() === '') {
				   isEmpty = true;
			   }
		   });
	   	
		   return isEmpty;
	   }

	   // Disable/enable the Place Order button based on form field status
	   function togglePlaceOrderButton() {
		   if (areFormFieldsEmpty()) {
			   $('#custom_Checkout_Button').attr('disabled', 'disabled');
		   } else {
			   $('#custom_Checkout_Button').removeAttr('disabled');
		   }
	   }

	   // Initial check on page load
	   togglePlaceOrderButton();

	   // Bind the check to form field changes
	   $('#billing_first_name, #billing_last_name, #billing_phone, #billing_email').on('input', function() {
		   togglePlaceOrderButton();
	   });
   });

   */



	/*
	$(document).ready(function() {
			$("#custom_Checkout_Button").click(function() {
			// Check all of the fields on the form
			if ($("#billing_first_name").val() == "") {
			  $("#billing_first_name").addClass("error");
			  $("#billing_first_name_error").html("Please enter your first name.");
			  return false;
			}

			if ($("#billing_last_name").val() == "") {
			  $("#billing_last_name").addClass("error");
			  $("#billing_last_name_error").html("Please enter your last name.");
			  return false;
			}

			if ($("#billing_email").val() == "") {
			  $("#billing_email").addClass("error");
			  $("#billing_email_error").html("Please enter your email address.");
			  return false;
			}

			if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i.test($("#billing_email").val())) {
			  $("#billing_email").addClass("error");
			  $("#billing_email_error").html("Please enter a valid email address.");
			  return false;
			}

			if ($("#billing_phone").val() == "") {
			  $("#billing_phone").addClass("error");
			  $("#billing_phone_error").html("Please enter your phone number.");
			  return false;
			}

			if (!/^\d{10}$/.test($("#billing_phone").val())) {
			  $("#billing_phone").addClass("error");
			  $("#billing_phone_error").html("Please enter a valid phone number.");
			  return false;
			}

			// Make an Ajax call to validate the form data
			$.ajax({
			  url: "/validate-checkout-form.php",
			  type: "POST",
			  data: $("#checkoutForm").serialize(),
			  success: function(response) {
				if (response == "valid") {
				  // The form is valid, so allow it to be submitted
				  return true;
				} else {
				  // The form is not valid, so prevent it from being submitted
				  $("#checkoutForm").addClass("error");
				  $("#checkout_error").html(response);
				  return false;
				}
			  }
			});
			
			
			});
	});
	*/

	
	







}); 