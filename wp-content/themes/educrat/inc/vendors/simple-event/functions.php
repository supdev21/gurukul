<?php

if ( !function_exists('educrat_get_events') ) {
    function educrat_get_events( $args = array() ) {

        $args = wp_parse_args( $args, array(
            'categories' => array(),
            'event_type' => 'recent',
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
            'post_type' => 'simple_event',
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'paged' => $paged,
            'orderby'   => $orderby,
            'order' => $order
        );

        switch ($event_type) {
            case 'recent':
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC';
                break;
            case 'rand':
                $query_args['orderby'] = 'rand';
                break;
        }

        if ( !empty($categories) && is_array($categories) ) {
            $query_args['tax_query'][] = array(
                'taxonomy'      => 'simple_event_category',
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
        
        return $loop;
    }
}

function educrat_event_theme_folder($folder) {
    return "templates-event";
}
add_filter( 'apus-simple-event-theme-folder-name', 'educrat_event_theme_folder', 10 );

if ( !function_exists('educrat_event_content_class') ) {
    function educrat_event_content_class( $class ) {
        $prefix = 'events';
        if ( is_singular( 'simple_event' ) ) {
            $prefix = 'event';
        }
        if ( educrat_get_config($prefix.'_fullwidth') ) {
            return 'container-fluid';
        }
        return $class;
    }
}
add_filter( 'educrat_event_content_class', 'educrat_event_content_class', 1 , 1  );


if ( !function_exists('educrat_get_event_layout_configs') ) {
    function educrat_get_event_layout_configs() {
        $prefix = 'events';
        if ( is_singular( 'simple_event' ) ) {
            $prefix = 'event';
        }
        $left = educrat_get_config($prefix.'_left_sidebar');
        $right = educrat_get_config($prefix.'_right_sidebar');
        switch ( educrat_get_config($prefix.'_layout') ) {
            case 'left-main':
                if ( is_active_sidebar( $left ) ) {
                    $configs['left'] = array( 'sidebar' => $left, 'class' => 'col-lg-3 col-12'  );
                    $configs['main'] = array( 'class' => 'col-lg-9 col-12' );
                }
                break;
            case 'main-right':
                if ( is_active_sidebar( $right ) ) {
                    $configs['right'] = array( 'sidebar' => $right,  'class' => 'col-lg-3 col-12' ); 
                    $configs['main'] = array( 'class' => 'col-lg-9 col-12' );
                }
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-lg-12 col-12' );
                break;
            default:
                $configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => 'col-lg-3 col-12' ); 
                $configs['main'] = array( 'class' => 'col-lg-9 col-12' );
                break;
        }
        if ( empty($configs) ) {
            $configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => 'col-lg-3 col-12' ); 
            $configs['main'] = array( 'class' => 'col-lg-9 col-12' );
        }
        return $configs; 
    }
}

function educrat_event_metaboxes($fields, $prefix) {
    
    foreach ($fields as $key => $field) {
        if ( $field['id'] == 'apussimpleevent_event_map' ) {
            unset($fields[$key]);
        }
    }
    $fields[] = array(
        'id'          => $prefix.'speakers',
        'type'        => 'group',
        'options'     => array(
            'group_title'       => esc_html__( 'Speakers {#}', 'educrat' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => esc_html__( 'Add Another Speakers', 'educrat' ),
            'remove_button'     => esc_html__( 'Remove Speakers', 'educrat' ),
            'sortable'          => true,
        ),
        'fields' => array(
            array(
                'name' => esc_html__( 'Image', 'educrat' ),
                'id'   => 'image',
                'type' => 'file',
                'query_args' => array(
                    'type' => array(
                        'image/gif',
                        'image/jpeg',
                        'image/png',
                    ),
                ),
                'preview_size' => 'thumbnail'
            ),
            array(
                'name' => esc_html__( 'Name', 'educrat' ),
                'id'   => 'name',
                'type' => 'text',
                'desc'    => esc_html__('e.g. John Doe', 'educrat')
            ),
            array(
                'name' => esc_html__( 'Job', 'educrat' ),
                'id'   => 'job',
                'type' => 'text',
                'desc'    => esc_html__('e.g. Web Designer', 'educrat')
            ),
        )
    );
    $products = educrat_event_get_wc_products_db();

    $options = ['' => ''];
    foreach ( $products as $product ) {
        $options[$product->ID] = $product->post_title;
    }
    $fields[] = array(
        'name' => esc_html__( 'Select product', 'educrat' ),
        'id'          => $prefix.'product_id',
        'type'        => 'select',
        'options' => $options,
        'desc'    => esc_html__('(When selling the event) Sell your product, process by WooCommerce', 'educrat')
    );

    return $fields;
}
add_filter('apussimpleevent_postype_event_metaboxes_fields_management', 'educrat_event_metaboxes', 10, 2);

function educrat_event_get_wc_products_db() {
    global $wpdb;
    $query = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID,
                post_title
        FROM    {$wpdb->posts}
        WHERE   post_status = %s
                AND post_type = %s;
        ",
            'publish',
            'product'
        )
    );

    return $query;
}

function educrat_event_map_api_key($key) {
    $key = educrat_get_config('google_map_api_key');
    return $key;
}
add_filter('apussimpleevent_map_api_key', 'educrat_event_map_api_key');

function educrat_event_loop_result_count() {

    ?>

    <div class="results-count">
        <?php
            global $wp_query;
            $total = $wp_query->found_posts;
            $per_page = $wp_query->query_vars['posts_per_page'];
            $current = max( 1, $wp_query->get( 'paged', 1 ) );
            
            if ( $total <= $per_page || -1 === $per_page ) {
                /* translators: %d: total results */
                printf( _n( 'Showing the single result', 'Showing all %d results', $total, 'educrat' ), $total );
            } else {
                $first = ( $per_page * $current ) - $per_page + 1;
                $last  = min( $total, $per_page * $current );
                /* translators: 1: first result 2: last result 3: total results */
                printf( _nx( 'Showing the single result', 'Showing <span class="first">%1$d</span> &ndash; <span class="last">%2$d</span> of %3$d results', $total, 'with first and last result', 'educrat' ), $first, $last, $total );
            }
        ?>
    </div>
    <?php
}

function educrat_event_loop_orderby() {

    $orderby_options = apply_filters( 'educrat_events_orderby', array(
        ''    => esc_html__( 'Default', 'educrat' ),
        'newest'        => esc_html__( 'Newest', 'educrat' ),
        'oldest'        => esc_html__( 'Oldest', 'educrat' ),
    ) );

    $orderby = isset( $_GET['orderby'] ) ? wp_unslash( $_GET['orderby'] ) : '';
    ?>

    <div class="orderby d-flex align-items-center">
        <label><?php echo esc_html__('Sort By:','educrat') ?></label>
        <form class="events-ordering" method="get">
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

function educrat_event_pre_get_posts($query) {
    $suppress_filters = ! empty( $query->query_vars['suppress_filters'] ) ? $query->query_vars['suppress_filters'] : '';

    $is_correct_taxonomy = false;
    if ( is_tax( 'simple_event_category' ) || is_tax( 'simple_event_tags' ) ) {
        $is_correct_taxonomy = true;
    }

    if ( ( (is_post_type_archive( 'simple_event' ) && !$suppress_filters && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'simple_event' ) || $is_correct_taxonomy) && $query->is_main_query() && !is_admin()  ) {

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

        $tax_query = $query->get( 'tax_query' );
        if ( empty($tax_query) ) {
            $tax_query = array();
        }
        if ( ! empty( $_GET['filter-category'] ) ) {
            $tax_query[] = array(
                'taxonomy'  => 'simple_event_category',
                'field'     => 'id',
                'terms'     => sanitize_text_field($_GET['filter-category']),
                'compare'   => '==',
            );
        }

        if ( $tax_query ) {
            $query->set( 'tax_query', $tax_query );
        }

        return $query;
    } else {
        return;
    }
}
add_action( 'pre_get_posts', 'educrat_event_pre_get_posts', 100 );