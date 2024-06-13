<?php

function educrat_tutor_get_courses( $args = array() ) {

    $course_post_type = tutor()->course_post_type;
    $args = wp_parse_args( $args, array(
        'categories' => array(),
        'paged' => 1,
        'limit' => -1,
        'id'          => '',
        'exclude_ids' => '',
        'category'    => '',

        'orderby'     => 'ID',
        'order'       => 'DESC',
        'includes' => '',
        'excludes' => '',
        'author' => '',
        'fields' => '',
    ));
    extract($args);
    
    $query_args = array(
        'post_type' => $course_post_type,
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'paged' => $paged,
        'orderby'   => $orderby,
        'order' => $order
    );
    
    if ( !empty($categories) && is_array($categories) ) {
        $query_args['tax_query'][] = array(
            'taxonomy'      => 'course-category',
            'field'         => 'term_id',
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
    return $loop;
}

function educrat_tutor_course_enqueue_scripts() {
    wp_enqueue_script( 'educrat-course', get_template_directory_uri() . '/js/course.js', array( 'jquery' ), '20150330', true );
    wp_localize_script( 'educrat-course', 'educrat_course_opts', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'ajax_nonce' => wp_create_nonce( "educrat-ajax-nonce" ),
    ));
}
add_action( 'wp_enqueue_scripts', 'educrat_tutor_course_enqueue_scripts', 10 );

if ( !function_exists('educrat_tutor_course_content_class') ) {
    function educrat_tutor_course_content_class( $class ) {
        $page = 'courses';
        
        if ( educrat_get_config('tutor_'.$page.'_fullwidth') ) {
            return 'container-fluid';
        }
        return $class;
    }
}
add_filter( 'educrat_tutor_course_content_class', 'educrat_tutor_course_content_class', 1 , 1  );

if ( !function_exists('educrat_tutor_get_course_archive_layout_configs') ) {
    function educrat_tutor_get_course_archive_layout_configs() {
        
        $page = 'courses';
        $classcourse = 'sidebar-course';
        

        $left = educrat_get_config('tutor_'.$page.'_left_sidebar');
        $right = educrat_get_config('tutor_'.$page.'_right_sidebar');

        switch ( educrat_get_config('tutor_'.$page.'_layout', 'main') ) {
            case 'left-main':
                
                $configs['left'] = array( 'sidebar' => $left, 'class' => $classcourse.' col-lg-3 col-12'  );
                $configs['main'] = array( 'class' => 'col-lg-9 col-12 pull-right' );
                
                break;
            case 'main-right':
                
                $configs['right'] = array( 'sidebar' => $right,  'class' => $classcourse.' col-lg-3 col-12 pull-right' ); 
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

function educrat_tutor_course_body_classes( $classes ) {
    global $post;
    if ( is_singular( 'courses' ) ) {
        $layout_type = educrat_tutor_course_layout_type();
        if ( empty($layout_type) ) {
            $layout_type = 'v1';
        }
        $classes[] = 'course-single-layout-'.$layout_type;
    }
    return $classes;
}
add_filter( 'body_class', 'educrat_tutor_course_body_classes' );


function educrat_tutor_course_metaboxes(array $metaboxes) {
    $prefix = '_lp_';
    $fields = array(
        
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
        'object_types'              => array( 'courses' ),
        'context'                   => 'normal',
        'priority'                  => 'high',
        'show_names'                => true,
        'fields'                    => $fields
    );

    return $metaboxes;
}
add_filter( 'cmb2_meta_boxes', 'educrat_tutor_course_metaboxes' );


function educrat_tutor_single_course_tabs($tabs, $course_id) {
    $return = [];
    foreach ($tabs as $key => $value) {
        if ( $key == 'info' ) {
            $return['info'] = $value;
            $return['instructor'] = array(
                'title' => esc_html__('Instructor', 'educrat'),
                'method' => 'educrat_tutor_single_course_instructor',
            );
        } else {
            $return[$key] = $value;
        }
    }
    return $return;
}
add_filter( 'tutor_course/single/nav_items', 'educrat_tutor_single_course_tabs', 10, 2 );

function educrat_tutor_single_course_instructor() {
    tutor_course_instructors_html();
}

function educrat_tutor_single_course_heading() {
    $layout_type = educrat_tutor_course_layout_type();

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
    get_template_part( 'tutor/single/course/header'.$header_prefix);
}

function educrat_tutor_course_layout_type() {
    if ( is_singular('courses') ) {
        global $post;
        $layout_type = get_post_meta($post->ID, '_lp_layout_type', true);
    }
    if ( empty($layout_type) ) {
        $layout_type = educrat_get_config('tutor_course_layout_type', 'v1');
    }
    return $layout_type;
}

add_filter( 'tutor_course/single/nav_items', 'educrat_tutor_course_single_nav_items', 10, 2 );
function educrat_tutor_course_single_nav_items($items, $course_id){
    if ( !empty($items) ) {
        foreach ($items as $key => &$item) {
            if ( $key == 'info' ) {
                $item['method'] = 'educrat_tutor_course_info_tab';
            }
        }
    }
    return $items;
}

function educrat_tutor_course_info_tab() {
    tutor_course_content();
    tutor_course_benefits_html();
    tutor_course_requirements_html();
    tutor_course_target_audience_html();
    tutor_course_topics();
    tutor_course_tags_html(); 
}