<?php
    echo '<div class="custom-admin-section">';
    echo '<h2>Export Orders to CSV</h2>
    <p>Click the button below to export orders to CSV:</p>
    <button id="exportButton" class="button">Export Orders</button>';
    echo '</div>';
 ?>
<script>
jQuery(function ($) {  
    $(document).ready(function($) {
        $('#exportButton').click(function() {
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            $.ajax({
                url: ajaxurl, // WordPress AJAX handler URL
                type: 'GET',
                data: {
                    action: 'export_csv'
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
</script>

