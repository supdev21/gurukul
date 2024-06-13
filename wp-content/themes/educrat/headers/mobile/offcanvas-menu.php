<div id="apus-mobile-menu" class="apus-offcanvas d-block d-xl-none"> 
    <div class="apus-offcanvas-body flex-column d-flex">
            <div class="header-offcanvas">
                <div class="container">
                    <div class="d-flex align-items-center">
                        <?php if ( educrat_get_config('header_mobile_login', true) ) {
                            
                            if ( educrat_is_learnpress_activated()) {
                                $profile_page_id = get_option('learn_press_profile_page_id');
                                if ( is_user_logged_in() ) {
                                    $user_id = get_current_user_id();
                                    ?>
                                    <div class="top-wrapper-menu author-verify">
                                        <a class="drop-dow" href="<?php echo esc_url( get_permalink( $profile_page_id ) ); ?>">
                                            <div class="infor-account d-flex align-items-center">
                                                <div class="avatar-wrapper">
                                                    <?php echo get_avatar($user_id, 50); ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class="top-wrapper-menu">
                                        <a class="login" href="<?php echo esc_url( get_permalink( $profile_page_id ) ); ?>">
                                            <?php echo esc_html__('Log in / Sign Up','educrat'); ?>
                                        </a>
                                    </div>
                                <?php } ?>

                            <?php } elseif ( educrat_is_tutor_activated() ) {
                                $profile_page_url = tutor_utils()->get_tutor_dashboard_page_permalink();
                                if ( is_user_logged_in() ) {
                                    $user_id = get_current_user_id();
                                    ?>
                                    <div class="top-wrapper-menu author-verify">
                                        <a class="drop-dow" href="<?php echo esc_url( $profile_page_url ); ?>">
                                            <div class="infor-account d-flex align-items-center">
                                                <div class="avatar-wrapper">
                                                    <?php echo get_avatar($user_id, 50); ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class="top-wrapper-menu">
                                        <a class="login" href="<?php echo esc_url( $profile_page_url ); ?>">
                                            <?php echo esc_html__('Log in / Sign Up','educrat'); ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>


                        <div class="ms-auto">
                            <a class="btn-toggle-canvas" data-toggle="offcanvas">
                                <i class="ti-close"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="offcanvas-content">
                <div class="middle-offcanvas">

                    <nav id="menu-main-menu-navbar" class="navbar navbar-offcanvas" role="navigation">
                        <?php
                            $mobile_menu = 'primary';
                            $menus = get_nav_menu_locations();
                            if( !empty($menus['mobile-primary']) && wp_get_nav_menu_items($menus['mobile-primary'])) {
                                $mobile_menu = 'mobile-primary';
                            }
                            $args = array(
                                'theme_location' => $mobile_menu,
                                'container_class' => '',
                                'menu_class' => '',
                                'fallback_cb' => '',
                                'menu_id' => '',
                                'container' => 'div',
                                'container_id' => 'mobile-menu-container',
                                'walker' => new Educrat_Mobile_Menu()
                            );
                            wp_nav_menu($args);

                        ?>
                    </nav>

                </div>
            </div>
            <?php if ( is_active_sidebar( 'header-mobile-bottom' ) ){ ?>
                <div class="mt-auto header-offcanvas-bottom">
                    <?php dynamic_sidebar( 'header-mobile-bottom' ); ?>
                </div>
            <?php } ?>
    </div>
</div>
<div class="over-dark"></div>