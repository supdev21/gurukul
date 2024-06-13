<style>
   table, th, td {border: 1px solid black;}
    th, td {padding: 10px;}
    .custom-order-statuses{font-size: 16px;font-weight: 600;color: #000;}
    tr.status_wc-cancelled {background-color: #ff7c7c;}
    tr.status_trash {background-color: #aaa;}
    tr.status_wc-processing {background-color: #9e8de4;}
    tr.status_wc-completed {background-color: #8de496;}
    tr.status_wc-pending {background-color: #8dd0e4;}
    .custom-admin-section{ margin:20px;}
    #wpfooter {position: relative!important;}
</style> 
<?php
global $wpdb;
$results1 = $wpdb->get_results("SELECT oi.order_item_name, COUNT(oi.order_id) AS order_count,oc.status FROM wp_learnpress_order_items AS oi LEFT JOIN wp_learnpress_order_itemmeta AS im ON oi.order_item_id = im.learnpress_order_item_id RIGHT JOIN wp_wc_orders AS oc ON (oi.order_id - 1) = oc.id WHERE oi.item_type = 'lp_course' GROUP BY oi.order_item_name, oc.status ORDER BY oi.order_item_name ASC");
echo '<div class="custom-admin-section">';
echo '<input type="search" class="light-table-filter" data-table="order-table" placeholder="Filter">';
$srno = 1;
    if ($results1) {
        echo '<table class="custom-order-statuses order-table table" id="myTable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Sr. No.</th>';
        echo '<th>Item Name</th>';
        echo '<th>Status</th>';
        echo '<th>Total</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($results1 as $result1) {
            if($result1->status!= 'trash' && $result1->status!= 'wc-processing'){
            echo '<tr class="status_'.$result1->status.'">';
            echo '<td>' . $srno . '</td>';
            echo '<td>' . $result1->order_item_name . '</td>';
            echo '<td>' . $result1->status . '</td>';
            echo '<td>' . ($result1->order_count /4 ) . '</td>';
            echo '</tr>';
            $srno++;
            }
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'No orders found.';
    }
    echo '</div>';
 ?>
<script>   
(function(document) {
    'use strict';
    var LightTableFilter = (function(Arr) {
        var _input;
        function _onInputEvent(e) {
            _input = e.target;
            var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
            Arr.forEach.call(tables, function(table) {
                Arr.forEach.call(table.tBodies, function(tbody) {
                    Arr.forEach.call(tbody.rows, _filter);
                });
            });
        }
        function _filter(row) {
            var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
            row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
        }
        return {
            init: function() {
                var inputs = document.getElementsByClassName('light-table-filter');
                Arr.forEach.call(inputs, function(input) {
                    input.oninput = _onInputEvent;
                });
            }
        };
    })(Array.prototype);
    document.addEventListener('readystatechange', function() {
        if (document.readyState === 'complete') {
            LightTableFilter.init();
        }
    });
})(document);
</script>