<?php
/**
 * The template for Custom Dashboard Speakers
 *
 * @package WordPress
 * @subpackage Educrat
 * @since Educrat 1.0
 */
/*
*Template Name: Custom Dashboard Speakers
*/
get_header();

$course_id = 0;

$currentUser = wp_get_current_user();
$checkUser = $currentUser->roles[0];
$checkUserId = $currentUser->ID;

if ($checkUser === 'lp_teacher') {
?>

<style>
    table, th, td {border: 1px solid black;}
    th, td {padding: 10px;}
    .custom-order-statuses{font-size: 14px;font-weight: 400;color: #000;}
    tr.status_wc-cancelled {background-color: #ff7c7c;}
    tr.status_trash {background-color: #aaa;}
    tr.status_wc-processing {background-color: #9e8de4;}
    tr.status_wc-completed {background-color: #8de496;}
    tr.status_wc-pending {background-color: #8dd0e4;}
    .custom-admin-section{ margin:20px;}
    #wpfooter {position: relative!important;}
    .wp-select {font-size: 14px;line-height: 2;color: #2c3338;border-color: #8c8f94;box-shadow: none;border-radius: 3px;padding: 0 24px 0 8px;min-height: 30px;max-width: 20rem;-webkit-appearance: none;background: #fff url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E) no-repeat right 5px top 55%;background-size: 16px 16px;cursor: pointer;vertical-align: middle;}
    input#fromDate{font-size: 14px;line-height: 2;color: #2c3338;border: 1px solid #8c8f94;box-shadow: none;border-radius: 3px;padding: 0 24px 0 8px;min-height: 30px;}
    input#toDate{font-size: 14px;line-height: 2;color: #2c3338;border: 1px solid #8c8f94;box-shadow: none;border-radius: 3px;padding: 0 24px 0 8px;min-height: 30px;}
</style>

    <section class="page-404 justify-content-center flex-middle">
        <div id="main-container" class="inner">
            <div id="main-content" class="main-page">
                <section class="error-404 not-found clearfix">
                    <div class="container">
                        <?php
                        
                        ?>
                        <div class="row d-md-flex align-items-center">
                            <div id="order_count_result"></div>
                            <div class="col-md-3 dash-box">
                                <h4>Box-1</h4><p>Total Orders</p>
                            </div>
                            <div class="col-md-3 dash-box"><h4>Box-2</h4><p>Complete Orders</p></div>
                            <div class="col-md-3 dash-box"><h4>Box-3</h4><p>Pending Orders</p></div>
                            <div class="col-md-3 dash-box"><h4>Box-4</h4><p>Cancel Orders</p></div>
                        </div>

                        <div class="row d-md-flex align-items-center">
                            <div class="col-md-12">
                                <?php
                                /* echo admin_url('admin-ajax.php'); */
                                // Assuming $teacher_id is the ID of the LearnPress teacher whose courses you want to display
                                // Get courses associated with the teacher
                                $args = array(
                                    'author'         => $checkUserId,
                                    'posts_per_page' => -1, // Retrieve all courses associated with the teacher
                                    'post_type'      => 'lp_course',
                                );
                                $courses_query = new WP_Query($args);

                                // Output the dropdown list
                                if ($courses_query->have_posts()) {

                                    echo '<form id="insert_order_form"> ';
                                    echo '<select id="course_selected" class="wp-select" onchange="updateField1()">';
                                    echo '<option value="0">Select Course</option>';
                                    while ($courses_query->have_posts()) {
                                        $courses_query->the_post();
                                        echo '<option value="' . esc_attr(get_the_ID()) . '">' . esc_html(get_the_title()) . '</option>';
                                    }
                                    echo '</select>';
                                    
                                    echo '<label for="fromDate">&nbsp;&nbsp; From:</label>
                                    <input type="text" id="fromDate" name="fromDate" placeholder="YYYY-MM-DD" onchange="updateField2()" required>
                                    <span class="error" id="fromDateError"></span>';

                                    echo '<label for="toDate">&nbsp;&nbsp; To:</label>
                                    <input type="text" id="toDate" name="toDate" placeholder="YYYY-MM-DD" onchange="updateField3()" required>
                                    <span class="error" id="toDateError"></span>';
                                    
                                    echo '<input type="submit" value="Submit">';
                                    
                                    echo '</form>';
                                    
                                    wp_reset_postdata(); 
 
                                    echo '<br>';

                                    echo 'Search : <input type="search" class="light-table-filter" data-table="order-table" placeholder="Filter">';

                                } else {
                                    echo 'No courses found for this teacher.';
                                }
                                ?>
                            </div>

                            <div class="col-md-12">
                                <div id="course_result">
                                    
                                </div> <!-- Container to display query results -->

                                <?php

                                /*if(isset($_POST['courseid'])) {
                                    $courseid = $_POST['courseid'];
                                } else {
                                    $courseid ='';
                                }

                                if(isset($_POST['coursefd'])) {
                                    $coursefd = $_POST['coursefd'];
                                } else {
                                    $coursefd = '';
                                }

                                if(isset($_POST['coursetd'])) {
                                    $coursetd = $_POST['coursetd'];
                                } else {
                                    $coursetd ='';
                                }*/

                                echo '<form id="export_order_form" action="'.get_stylesheet_directory().'/export.php" method="post">';
                                echo '<input type="text" id="course_selected_Export" name="course_selected_Export" value="7802" required>';
                                echo '<input type="text" id="fromDateExport" name="fromDateExport" value="2024-01-01" required>';
                                echo '<input type="text" id="toDateExport" name="toDateExport" value="2024-03-13" required>';
                                echo '<input id="" type="submit" value="Export Orders">';
                                echo '</form>';
                                ?>
                            </div>

                        </div>
                    </div>
                </section><!-- .error-404 -->
            </div><!-- .content-area -->
        </div>
    </section>
    
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


<script>
function updateField1() {
    var courseSel = document.getElementById("course_selected").value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("course_selected_Export").value = courseSel;
        }
    };
    xhr.send("courseid=" + courseSel);
}

function updateField2() {
    var coursefromDate = document.getElementById("fromDate").value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("fromDateExport").value = coursefromDate;
        }
    };
    xhr.send("coursefd=" + coursefromDate);
}

function updateField3() {
    var coursetoDate = document.getElementById("toDate").value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("toDateExport").value = coursetoDate;
        }
    };
    xhr.send("coursetd=" + coursetoDate);
}
</script>
    
<?php } else { ?>
    <script>window.location.replace("http://localhost/defguru/")</script>
<?php }

get_footer(); ?>