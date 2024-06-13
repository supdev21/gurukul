<?php

function educrat_get_courses( $args = array() ) {

    $args = wp_parse_args( $args, array(
        'categories' => array(),
        'course_type' => 'recent_courses',
        'paged' => 1,
        'limit' => -1,
        'orderby' => '',
        'order' => '',
        'includes' => array(),
        'excludes' => array(),
        'author' => '',
        'fields' => '', // ids
    ));
    extract($args);
    
    $query_args = array(
        'post_type' => LP_COURSE_CPT,
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'paged' => $paged,
        'orderby'   => $orderby,
        'order' => $order
    );

    switch ($course_type) {
        case 'featured_courses':
            $meta_query = array(
                array(
                    'key' => '_lp_featured',
                    'value' => 'yes',
                    'compare' => '=',
                )
            );
            $query_args['meta_query'] = $meta_query;
            break;
        case 'recent_courses':
            $query_args['orderby'] = 'date';
            $query_args['order'] = 'DESC';
            break;
        case 'rand':
            $query_args['orderby'] = 'rand';
            break;
    }

    if ( !empty($categories) && is_array($categories) ) {
        $query_args['tax_query'][] = array(
            'taxonomy'      => 'course_category',
            'field'         => 'slug',
            'terms'         => $categories,
            'operator'      => 'IN'
        );
    }

    if (!empty($includes) && is_array($includes)) {
        $query_args['post__in'] = $includes;
    }
    
    if ( !empty($excludes) && is_array($excludes) ) {
        $query_args['post__not_in'] = $excludes;
    }

    if ( !empty($author) ) {
        $query_args['author'] = $author;
    }

    if ( !empty($fields) ) {
        $query_args['fields'] = $fields;
    }
    $loop = new WP_Query($query_args);
    $posts = array();
    if ( !empty($loop->posts) ) {
        $posts = $loop->posts;
    }
    return $posts;
}

function educrat_get_popular_courses( $args = array() ) {
    global $wpdb;

    $limit = ! empty( $args['limit'] ) ? $args['limit'] : - 1;
    $order = ! empty( $args['order'] ) ? $args['order'] : 'DESC';
    $categories = ! empty( $args['categories'] ) ? $args['categories'] : '';

    $join_cat = $where_cat = '';
    if ( !empty($categories) ) {
        $join_cat = "
            INNER JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id 
            INNER JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id 
            INNER JOIN {$wpdb->prefix}terms t ON tt.term_id = t.term_id 
        ";
        $where_cat = " AND t.slug IN ('".implode("','", $categories)."') ";
    }

    if ( $limit <= 0 ) {
        $limit = 0;
    }

    $query = apply_filters( 'learn-press/course-curd/query-popular-courses',
        $wpdb->prepare( "
            SELECT DISTINCT p.ID, COUNT(*) AS number_enrolled 
            FROM {$wpdb->prefix}learnpress_user_items ui
            INNER JOIN {$wpdb->posts} p ON p.ID = ui.item_id

            %s

            WHERE ui.item_type = %s
                AND ( ui.status = %s OR ui.status = %s )
                AND p.post_status = %s %s
            ORDER BY number_enrolled {$order}
            LIMIT %d
        ", $join_cat, LP_COURSE_CPT, 'enrolled', 'finished', 'publish', $where_cat, $limit )
    );

    return $wpdb->get_col( $query );
}

function educrat_course_get_instructors($limit = '') {
    $args = array(
        'role__in'     => array( 'lp_teacher' ),
        'orderby'      => 'login',
        'order'        => 'ASC',
        'number'       => $limit
    ); 
    $instructors = get_users( $args );
    return $instructors;
}

function educrat_course_override_templates($return) {
    return true;
}
add_filter( 'learn-press/override-templates', 'educrat_course_override_templates' );

function educrat_course_enqueue_scripts() {
    wp_enqueue_script( 'educrat-course', get_template_directory_uri() . '/js/course.js', array( 'jquery' ), '20150330', true );
    wp_localize_script( 'educrat-course', 'educrat_course_opts', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'ajax_nonce' => wp_create_nonce( "educrat-ajax-nonce" ),
    ));
}
add_action( 'wp_enqueue_scripts', 'educrat_course_enqueue_scripts', 10 );

if ( !function_exists('educrat_course_content_class') ) {
    function educrat_course_content_class( $class ) {
        $page = 'courses';
        
        if ( educrat_get_config($page.'_fullwidth') ) {
            return 'container-fluid';
        }
        return $class;
    }
}
add_filter( 'educrat_course_content_class', 'educrat_course_content_class', 1 , 1  );

if ( !function_exists('educrat_get_course_archive_layout_configs') ) {
    function educrat_get_course_archive_layout_configs() {
        
        $page = 'courses';
        $classcourse = 'sidebar-course';
        

        $left = educrat_get_config($page.'_left_sidebar');
        $right = educrat_get_config($page.'_right_sidebar');

        switch ( educrat_get_config($page.'_layout', 'main') ) {
            case 'left-main':
                
                $configs['left'] = array( 'sidebar' => $left, 'class' => $classcourse.' col-lg-3 col-12'  );
                $configs['main'] = array( 'class' => 'col-lg-9 col-12' );
                
                break;
            case 'main-right':
                
                $configs['right'] = array( 'sidebar' => $right,  'class' => $classcourse.' col-lg-3 col-12' ); 
                $configs['main'] = array( 'class' => 'col-lg-9 col-12' );
                
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-md-12 col-12' );
                break;
            default:
                $configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => $classcourse.' col-lg-3 col-12' ); 
                $configs['main'] = array( 'class' => 'col-lg-9 col-12' );
                break;
        }
       
        return $configs; 
    }
}

function educrat_course_body_classes( $classes ) {
    global $post;
    if ( is_singular( LP_COURSE_CPT ) ) {
        $layout_type = educrat_course_layout_type();
        if ( empty($layout_type) ) {
            $layout_type = 'v1';
        }
        $classes[] = 'course-single-layout-'.$layout_type;
    }
    if ( learn_press_is_courses() || learn_press_is_course_tag() || learn_press_is_course_category() || learn_press_is_course_tax() || learn_press_is_search() ) {
        $display_mode = educrat_get_config('courses_display_mode', 'grid');
        if ( $display_mode == 'list' ) {
            $classes[] = 'body-display-mode-list';
        }
    }
    return $classes;
}
add_filter( 'body_class', 'educrat_course_body_classes' );


function educrat_course_pre_get_posts($query) {

    $suppress_filters = ! empty( $query->query_vars['suppress_filters'] ) ? $query->query_vars['suppress_filters'] : '';

    $is_correct_taxonomy = false;
    if ( is_tax( 'course_category' ) || is_tax( 'course_tag' ) ) {
        $is_correct_taxonomy = true;
    }

    if ( ( (is_post_type_archive( LP_COURSE_CPT ) && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == LP_COURSE_CPT ) || $is_correct_taxonomy) && $query->is_main_query() && !is_admin()  ) {
        
        $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : '';
        switch ( $orderby ) {
            case 'newest':
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'DESC' );
                break;
            case 'oldest':
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'ASC' );
                break;
        }
        
        if ( ! empty( $_GET['c_search'] ) ) {
            $query->set( 's', sanitize_text_field($_GET['c_search']) );
        }
        
        $query->set( 'posts_per_page', LP_Settings::get_option( 'archive_course_limit', 6 ) );

        $tax_query = $query->get( 'tax_query' );
        if ( empty($tax_query) ) {
            $tax_query = array();
        }
        if ( ! empty( $_GET['filter-category'] ) ) {
            if ( is_array($_GET['filter-category']) ) {
                $tax_query[] = array(
                    'taxonomy'  => 'course_category',
                    'field'     => 'id',
                    'terms'     => array_map('sanitize_text_field', $_GET['filter-category']),
                    'compare'   => 'IN',
                );
            } else {
                $tax_query[] = array(
                    'taxonomy'  => 'course_category',
                    'field'     => 'id',
                    'terms'     => sanitize_text_field($_GET['filter-category']),
                    'compare'   => '==',
                );
            }
        }

        if ( $tax_query ) {
            $query->set( 'tax_query', $tax_query );
        }

        if ( ! empty( $_GET['filter-instructor'] ) ) {
            if ( is_array($_GET['filter-instructor']) ) {
                $query->set( 'author__in', array_map('sanitize_text_field', $_GET['filter-instructor']) );
            } else {
                $query->set( 'author', sanitize_text_field($_GET['filter-instructor']) );
            }
        }

        $meta_query = $query->get( 'meta_query' );
        if ( empty($meta_query) ) {
            $meta_query = array();
        }
        if ( ! empty( $_GET['filter-level'] ) ) {
            if ( is_array($_GET['filter-level']) ) {
                $meta_query[] = array(
                    'key' => '_lp_level',
                    'value' => array_map('sanitize_text_field', $_GET['filter-level']),
                    'compare' => 'IN',
                );
            } else {
                $meta_query[] = array(
                    'key' => '_lp_level',
                    'value' => sanitize_text_field($_GET['filter-level']),
                    'compare' => '=',
                );
            }
        }
        if ( ! empty( $_GET['filter-price'] ) ) {
            if ( $_GET['filter-price'] == 'free' ) {
                $meta_query[] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_lp_price',
                        'value' => '0',
                        'compare' => '=',
                    ),
                    array(
                        'key' => '_lp_price',
                        'value' => '',
                        'compare' => '=',
                    ),
                    array(
                        'key' => '_lp_price',
                        'compare' => 'NOT EXISTS',
                    )
                );
            } else {
                $meta_query[] = array(
                    'key' => '_lp_price',
                    'value' => '0',
                    'compare' => '>',
                );
            }
        }
        if ( ! empty( $_GET['filter-rating'] ) ) {
            if ( is_array($_GET['filter-rating']) ) {
                $t_meta_query = array( 'relation' => 'OR' );
                foreach ($_GET['filter-rating'] as $key => $rating) {
                    $t_meta_query[] = array(
                        array(
                            'key' => '_average_rating',
                            'value' => sanitize_text_field($rating),
                            'compare' => '>=',
                        ),
                        array(
                            'key' => '_average_rating',
                            'value' => (sanitize_text_field($rating) + 1),
                            'compare' => '<',
                        ),
                    );
                }
                $meta_query[] = $t_meta_query;
            } else {
                $meta_query[] = array(
                    array(
                        'key' => '_average_rating',
                        'value' => sanitize_text_field($_GET['filter-rating']),
                        'compare' => '>=',
                    ),
                    array(
                        'key' => '_average_rating',
                        'value' => (sanitize_text_field($_GET['filter-rating']) + 1),
                        'compare' => '<',
                    ),
                );
            }
        }
        if ( $meta_query ) {
            $query->set( 'meta_query', $meta_query );
        }

        return $query;
    } else {
        return;
    }
}
add_action( 'pre_get_posts', 'educrat_course_pre_get_posts', 100 );

function educrat_course_custom_metas($fields) {
    if ( class_exists('LP_Meta_Box_Text_Field') ) {
        $fields['_lp_language'] = new LP_Meta_Box_Text_Field(
            esc_html__( 'Language', 'educrat' ),
            esc_html__( 'The language of the course.', 'educrat' ),
            '',
        );
    }
    if ( class_exists('LP_Meta_Box_Checkbox_Field') ) {
        $fields['_lp_certificate'] = new LP_Meta_Box_Checkbox_Field(
            esc_html__( 'Certificate', 'educrat' ),
            esc_html__( 'Set certificate course.', 'educrat' ),
            'no'
        );
    }

    return $fields;
}
add_filter('lp/course/meta-box/fields/general', 'educrat_course_custom_metas', 100);


function educrat_course_metaboxes(array $metaboxes) {
    $prefix = '_lp_';
    $fields = array(
        array(
            'name' => esc_html__( 'Video url', 'educrat' ),
            'id'   => '_lp_video_url',
            'type' => 'text',
            'desc' => esc_html__( 'Enter youtube or vimeo video.', 'educrat' ),
        ),
        array(
            'name'              => esc_html__( 'More Information', 'educrat' ),
            'id'                => $prefix.'more_info',
            'type'              => 'text',
            'repeatable'        => true
        ),
        array(
            'name'              => esc_html__( 'Layout Type', 'educrat' ),
            'id'                => $prefix . 'layout_type',
            'type'              => 'select',
            'options'           => array(
                '' => esc_html__('Global Settings', 'educrat'),
                'v1' => esc_html__('Layout 1', 'educrat'),
                'v2' => esc_html__('Layout 2', 'educrat'),
                'v3' => esc_html__('Layout 3', 'educrat'),
                'v4' => esc_html__('Layout 4', 'educrat'),
                'v5' => esc_html__('Layout 5', 'educrat'),
                'v6' => esc_html__('Layout 6', 'educrat'),
            ),
        )
    );
    
    $metaboxes[$prefix . 'more_information'] = array(
        'id'                        => $prefix . 'more_information',
        'title'                     => esc_html__( 'More Information', 'educrat' ),
        'object_types'              => array( LP_COURSE_CPT ),
        'context'                   => 'normal',
        'priority'                  => 'high',
        'show_names'                => true,
        'fields'                    => $fields
    );

    return $metaboxes;
}
add_filter( 'cmb2_meta_boxes', 'educrat_course_metaboxes' );


add_action( 'template_redirect', 'educrat_course_track_job_view', 20 );
function educrat_course_track_job_view() {
    if ( ! is_singular( LP_COURSE_CPT ) ) {
        return;
    }

    global $post;
    
    // views count
    $views = intval(get_post_meta($post->ID, '_views', true));
    $views++;
    update_post_meta($post->ID, '_views', $views);
}

// remove div
remove_all_actions( 'learn-press/course-content-summary', 10 );
remove_all_actions( 'learn-press/course-content-summary', 15 );
remove_all_actions( 'learn-press/course-content-summary', 35 );
remove_all_actions( 'learn-press/course-content-summary', 30 );
// remove course_extra_boxes
remove_all_actions( 'learn-press/course-content-summary', 40 );
remove_all_actions( 'learn-press/course-content-summary', 70 );
remove_all_actions( 'learn-press/course-content-summary', 75 );

// remove course sidebar
remove_all_actions( 'learn-press/course-content-summary', 85 );
// remove div
remove_all_actions( 'learn-press/course-content-summary', 100 );

// remove breadcrumbs
remove_action( 'learn-press/before-main-content', LP()->template( 'general' )->func( 'breadcrumb' ) );

remove_all_actions( 'learn-press/after-courses-loop', 10 );

function educrat_learnpress_single_course_action() {
    add_action( 'educrat_single_course_header', 'educrat_single_course_heading', 10 );
    
    add_action(
        'learn-press/course-content-summary',
        LP()->template( 'course' )->text( '<div class="lp-entry-content apus-lp-content-area">', 'lp-entry-content-open' ),
        30
    );

    add_action( 'learn-press/after-single-course-description', LP()->template( 'course' )->func( 'course_extra_boxes' ), 10 );
}
add_action( 'init', 'educrat_learnpress_single_course_action' );

function educrat_single_course_heading() {
    $layout_type = educrat_course_layout_type();

    $header_prefix = '';
    switch ($layout_type) {
        case 'v1':
            $header_prefix = '';
            break;
        case 'v2':
            $header_prefix = '';
            break;
        case 'v3':
            $header_prefix = '-3';
            break;
        case 'v4':
            $header_prefix = '-4';
            break;
        case 'v5':
            $header_prefix = '-5';
            break;
        case 'v6':
            $header_prefix = '-6';
            break;

        default:
            $header_prefix = '';
            break;
    }
    learn_press_get_template( 'single-course/header'.$header_prefix.'.php' );
}

function educrat_course_layout_type() {
    if ( is_singular(LP_COURSE_CPT) ) {
        global $post;
        $layout_type = get_post_meta($post->ID, '_lp_layout_type', true);
    }
    if ( empty($layout_type) ) {
        $layout_type = educrat_get_config('course_layout_type', 'v1');
    }
    return $layout_type;
}

add_filter( 'learn-press/course-tabs', 'educrat_single_course_tabs', 100 );
function educrat_single_course_tabs($defaults) {
    if ( educrat_get_config('show_course_review_tab', true) ) {
        $defaults['reviews'] = array(
            'title'    => esc_html__( 'Reviews', 'educrat' ),
            'priority' => 50,
            'callback' => 'educrat_single_course_reivews',
        );
    }
    if ( isset($defaults['faqs']) ) {
        $defaults['faqs']['callback'] = 'educrat_single_course_faqs';
    }
    return $defaults;
}

function educrat_single_course_faqs() {
    $course = LP_Course::get_course( get_the_ID() );

    if ( ! $faqs = $course->get_faqs() ) {
        return;
    }

    learn_press_get_template( 'single-course/tabs/faqs-new', array('faqs' => $faqs) );
}

function educrat_single_course_reivews() {
    learn_press_get_template( 'single-course/tabs/comment' );
}

function educrat_single_course_video() {
    learn_press_get_template( 'single-course/video.php' );
}

// Archives
function educrat_courses_display_modes(){
    global $wp;
    $current_url = learn_press_get_page_link( 'courses' );

    $url_grid = add_query_arg( 'display-mode', 'grid', remove_query_arg( 'display-mode', $current_url ) );
    $url_list = add_query_arg( 'display-mode', 'list', remove_query_arg( 'display-mode', $current_url ) );

    $display_mode = educrat_courses_get_display_mode();

    echo '<div class="display-mode d-flex align-items-center">';
    echo '<a href="'.  $url_grid  .'" class=" change-view '.($display_mode == 'grid' ? 'active' : '').'"><i class="ti-layout-grid2"></i></a>';
    echo '<a href="'.  $url_list  .'" class=" change-view '.($display_mode == 'list' ? 'active' : '').'"><i class="ti-view-list"></i></a>';
    echo '</div>'; 
}

function educrat_courses_get_display_mode() {
    $display_mode = educrat_get_config('courses_display_mode', 'grid');

    $args = array( 'grid', 'list' );
    if ( isset($_COOKIE['educrat_courses_display_mode']) && in_array($_COOKIE['educrat_courses_display_mode'], $args) ) {
        $display_mode = $_COOKIE['educrat_courses_display_mode'];
    }

    if ( defined('EDUCRAT_DEMO_MODE') && EDUCRAT_DEMO_MODE ) {
        if ( isset($_GET['display-mode']) ) {
            $display_mode = $_GET['display-mode'];
        }
    }
    return $display_mode;
}

function educrat_courses_init() {
    if( isset($_GET['display-mode']) && ($_GET['display-mode']=='list' || $_GET['display-mode']=='grid') ){  
        setcookie( 'educrat_courses_display_mode', trim($_GET['display-mode']) , time()+3600*24*100,'/' );
        $_COOKIE['educrat_courses_display_mode'] = trim($_GET['display-mode']);
    }
}
add_action( 'init', 'educrat_courses_init' );

function educrat_course_loop_found_post() {
    global $wp_query;
    $count = $wp_query->found_posts;
    ?>
    <div class="course-found"><?php echo sprintf(_n('<span>%d</span> course found', '<span>%d</span> courses found', $count, 'educrat'), $count); ?></div>
    <?php
}
function educrat_course_loop_orderby() {

    $orderby_options = apply_filters( 'educrat_courses_orderby', array(
        ''    => esc_html__( 'Default', 'educrat' ),
        'newest'        => esc_html__( 'Newest', 'educrat' ),
        'oldest'        => esc_html__( 'Oldest', 'educrat' ),
    ) );

    $orderby = isset( $_GET['orderby'] ) ? wp_unslash( $_GET['orderby'] ) : '';
    ?>

    <div class="orderby d-flex align-items-center">
        <label><?php echo esc_html__('Sort By:','educrat') ?></label>
        <form class="courses-ordering" method="get">
            <select name="orderby" class="orderby">
                <?php foreach ( $orderby_options as $id => $name ) : ?>
                    <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="paged" value="1" />
            <?php educrat_query_string_form_fields( null, array( 'orderby', 'submit', 'paged' ) ); ?>
        </form>
    </div>
    <?php
}

add_filter( 'learn_press_course_loop_begin', 'educrat_course_loop_begin', 100 );
function educrat_course_loop_begin() {
    return '<div class="row">';
}

add_filter( 'learn_press_course_loop_end', 'educrat_course_loop_end', 100 );
function educrat_course_loop_end() {
    return '</div>';
}