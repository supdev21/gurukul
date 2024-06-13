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
    input#fromDateC{font-size: 14px;line-height: 2;color: #2c3338;border: 1px solid #8c8f94;box-shadow: none;border-radius: 3px;padding: 0 24px 0 8px;min-height: 30px;}
    input#toDateC{font-size: 14px;line-height: 2;color: #2c3338;border: 1px solid #8c8f94;box-shadow: none;border-radius: 3px;padding: 0 24px 0 8px;min-height: 30px;}
    .admin-buttons{color: #2271b1;border: 1px solid #2271b1;background: #f6f7f7;vertical-align: top;display: inline-block;text-decoration: none;font-size: 13px;line-height: 2.15384615;min-height: 30px;margin-left: 10px;padding: 0 10px;cursor: pointer;border-radius: 3px;white-space: nowrap;box-sizing: border-box;}
</style>
<link rel='stylesheet' id='admin-course-css' href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' type='text/css'/>
<?php
$courses = get_learnpress_courses();
echo '<pre>';
print_r($courses);
echo '</pre>';
?>
<div class="wrap">
    <h2>Course Wise Search</h2>
    <form method="post">
        <label for="course">Filter by Course:</label>
        <select name="course" id="course" required>
            <?php
            echo '<option value="">Select Course</option>';
            foreach ($courses as $course) {
            echo '<option value="'.$course['id'].'_'.esc_html($course['name']).'">'.esc_html($course['name']).'</option>';
            }
            ?>
        </select>
        <select name="status-order" id="status-order" required>
            <option value="">Select Order Status</option>
            <option value="wc-completed">Completed</option>
            <option value="wc-pending">Pending</option>
            <option value="wc-cancelled">Cancelled</option>
        </select>
        <?php
            echo 'From: <input type="text" id="fromDateC" name="fromDateC" placeholder="YYYY-MM-DD" required><span class="error" id="fromDateErrorNew"></span>';
            echo 'To: <input type="text" id="toDateC" name="toDateC" placeholder="YYYY-MM-DD" required><span class="error" id="toDateErrorNew"></span>';
        ?>
        <input type="submit" name="submit" class="button" value="Filter">

        <button id="expData" class="admin-buttons">Export Data</button>
    </form>
        <a href="<?php echo home_url() ?>/wp-admin/admin.php?page=order_list_child_coursewise" class="admin-buttons">New Search</a>
    <?php
    if (isset($_POST['submit'])) {
        $aaa = $_POST['course'];
        $bbb = explode('_',$aaa);
        $selected_course = $bbb[0];
        $statusorder = $_POST['status-order'];
        $fromdate_select = $_POST['fromDateC'];
        $todate_select = $_POST['toDateC'];
        global $wpdb;
        $results = $wpdb->get_results("SELECT oi.order_item_name, wo.id, wo.status, wo.total_amount, wo.billing_email, wo.date_created_gmt, wo.date_updated_gmt, woa.first_name, woa.last_name, woa.phone FROM wp_learnpress_order_items AS oi JOIN wp_learnpress_order_itemmeta AS om ON oi.order_item_id = om.learnpress_order_item_id JOIN wp_wc_orders AS wo ON oi.order_id-1 = wo.id JOIN wp_wc_order_addresses AS woa ON woa.order_id = wo.id WHERE oi.item_id = $selected_course AND DATE(wo.date_created_gmt) BETWEEN '$fromdate_select' AND '$todate_select' AND wo.status = '$statusorder' GROUP BY oi.order_item_id ORDER BY `order_item_id` DESC;");
        echo '<div class="custom-admin-section">';
        echo '<h3>'.esc_html($bbb[1]).'</h3>';
        echo '<input type="search" class="light-table-filter" data-table="order-table" placeholder="Filter">';
        $srno = 1;
            if ($results) {
                echo '<table class="custom-order-statuses order-table table" id="myTable">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Sr. No.</th>';
                echo '<th>Order ID</th>';
                echo '<th>Course Name</th>';
                echo '<th>Amount</th>';
                echo '<th>Status</th>';
                echo '<th>Date</th>';
                echo '<th>Name</th>';
                echo '<th>Email</th>';
                echo '<th>Contact</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($results as $result) {
                    if($result->status!= 'trash' && $result->status!= 'wc-processing'){
                    echo '<tr class="status_'.$result->status.'">';
                    echo '<td>' . $srno . '</td>';
                    echo '<td>' . $result->id . '</td>';
                    echo '<td>' . $result->order_item_name . '</td>';
                    echo '<td>' . $result->total_amount . '</td>';
                    echo '<td>' . $result->status . '</td>';
                    echo '<td>' . $result->date_created_gmt . '</td>';
                    echo '<td>' . $result->first_name . ' ' . $result->last_name . '</td>';
                    echo '<td>' . $result->billing_email . '</td>';
                    echo '<td>' . $result->phone . '</td>';
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
            }
            ?>
</div>
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