<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Educrat_Elementor_Woo_Header_Cart extends Elementor\Widget_Base {

    public function get_name() {
        return 'apus_element_woo_header_cart';
    }

    public function get_title() {
        return esc_html__( 'Apus Header WooCoomerce Cart', 'educrat' );
    }
    
    public function get_categories() {
        return [ 'educrat-header-elements' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'educrat' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'educrat' ),
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon', 'educrat' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .mini-cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hv_color',
            [
                'label' => esc_html__( 'Icon Hover Color', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .mini-cart:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mini-cart:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => esc_html__( 'Color Count', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bg_count_color',
            [
                'label' => esc_html__( 'Bg Count', 'educrat' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .count' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        $add_class = '';
        if ( !empty($align) ) {
            $add_class = 'menu-'.$align;
        }
        ?>
        <div class="header-button-woo clearfix <?php echo esc_attr($add_class.' '.$el_class); ?>">
            <?php
            global $woocommerce;
            if (  is_object($woocommerce) && is_object($woocommerce->cart) ) {
            ?>
                <div class="pull-right">
                    <div class="apus-topcart">
                        <div class="cart">
                                <a class="dropdown-toggle mini-cart" data-bs-toggle="dropdown" aria-expanded="true" href="#" title="<?php esc_attr_e('View your shopping cart', 'educrat'); ?>">
                                    <i class="flaticon-shopping-bag"></i>
                                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="widget_shopping_cart_content">
                                        <?php woocommerce_mini_cart(); ?>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                </div>
            <?php
            } else {
                ?>
                <div class="pull-right">
                    <div class="apus-topcart">
                        <div class="cart">
                            <a class="dropdown-toggle mini-cart" data-toggle="dropdown" aria-expanded="true" href="#" title="<?php esc_attr_e('View your shopping cart', 'educrat'); ?>">
                                <i class="flaticon-shopping-bag"></i>
                                <span class="count">0</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
            } ?>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Educrat_Elementor_Woo_Header_Cart );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Educrat_Elementor_Woo_Header_Cart );
}