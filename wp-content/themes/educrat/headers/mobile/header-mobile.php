<div id="apus-header-mobile" class="header-mobile d-block d-xl-none clearfix">   
    <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-5">
                    <?php
                        $logo_url = educrat_get_config('media-mobile-logo');
                    ?>
                    <?php if( !empty($logo_url) ): ?>
                        <div class="logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="logo logo-theme">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <img src="<?php echo esc_url( get_template_directory_uri().'/images/logo-white.svg'); ?>" alt="<?php bloginfo( 'name' ); ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-7 d-flex align-items-center justify-content-end">

                        <?php if ( educrat_get_config('header_mobile_menu', true) ) { ?>
                            <a href="#navbar-offcanvas" class="btn-showmenu">
                                <i class="mobile-menu-icon"></i>
                            </a>
                        <?php } ?>
                </div>
            </div>
    </div>
</div>