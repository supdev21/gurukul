jQuery(document).ready(function($) {
   	$(document).ready(function() {
        $("#fromDateC, #toDateC").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
    });
    $(document).ready(function($) {
        $('#expData').click(function() {
        	event.preventDefault();
	        var courseIdNew = $("#course").val();
	        var orderStatusNew = $("#status-order").val();
	        var fromDateNew = $("#fromDateC").val();
	        var toDateNew = $("#toDateC").val();
	        if (fromDateNew > toDateNew) {
	            $("#fromDateErrorNew").text("From date must be before To date");
	            return; // Prevent further execution if validation fails
	        } else {
	            $("#fromDateErrorNew").text("");
	        }
            var ajaxurl = 'http://localhost/defguru/wp-admin/admin-ajax.php';
            $.ajax({
                url: ajaxurl, // WordPress AJAX handler URL
                type: 'POST',
                data: {
                    action: 'export_csv_new', idcourse: courseIdNew, statusorder: orderStatusNew, DateNewform: fromDateNew, DateNewto: toDateNew
                },
                success: function(response) {
                    // Trigger file download
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'exported_data.csv';
                    link.click();
                }
            });
        });
    });
	
});



/*(function( $ ) {

	'use strict';
	var $ = jQuery;
	$.fn.extend({
		filterTable: function(){
			return this.each(function(){
				$(this).on('keyup', function(e){
					$('.filterTable_no_results').remove();
					var $this = $(this), 
                        search = $this.val().toLowerCase(), 
                        target = $this.attr('data-filters'), 
                        $target = $(target), 
                        $rows = $target.find('tbody tr');
                        
					if(search == '') {
						$rows.show(); 
					} else {
						$rows.each(function(){
							var $this = $(this);
							$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
						})
						if($target.find('tbody tr:visible').size() === 0) {
							var col_count = $target.find('tr').first().find('td').size();
							var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
							$target.find('tbody').append(no_results);
						}
					}
				});
			});
		}
	});
	$('[data-action="filter"]').filterTable();
	})(jQuery);

	$(function(){
	    // attach table filter plugin to inputs
		$('[data-action="filter"]').filterTable();
		
		$('.container').on('click', '.panel-heading span.filter', function(e){
			var $this = $(this), 
				$panel = $this.parents('.panel');
			
			$panel.find('.panel-body').slideToggle();
			if($this.css('display') != 'none') {
				$panel.find('.panel-body input').focus();
			}
		});
		$('[data-toggle="tooltip"]').tooltip();
	})
*/