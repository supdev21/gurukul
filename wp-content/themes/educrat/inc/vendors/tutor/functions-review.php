<?php

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class Educrat_Tutor_Course_Review {
	
	public static function print_review( $rate, $type = '', $nb = 0 ) {
		$rate = $rate ? $rate : 0;
	    ?>
	    <div class="review-stars-rated-wrapper">
	        <div class="review-stars-rated">
	            <ul class="review-stars">
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	            </ul>
	            
	            <ul class="review-stars filled"  style="<?php echo esc_attr( 'width: ' . ( $rate * 20 ) . '%' ) ?>" >
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	                <li><span class="fa fa-star"></span></li>
	            </ul>
	        </div>
	        <?php if ($type == 'detail') { ?>
	            <span class="nb-review"><?php echo sprintf(_n('(%d)', '(%d)', $nb, 'educrat'), $nb); ?></span>
	        <?php } elseif ($type == 'list') { ?>
	            <span class="nb-review"><?php echo sprintf('(%d)', $nb); ?></span>
	        <?php } ?>
	    </div>
	    <?php
	}
	
	public static function print_review_star( $rate, $type = '', $nb = 0 ) {
		$rate = $rate ? $rate : 0;
	    ?>
	    <div class="review-stars-rated-wrapper">
	        <i class="fa fa-star text-warning"></i>
            <span class="nb-rate"><?php echo number_format($rate, 1); ?></span>
            <?php if ( $type == 'detail' ) { ?>
            	<span class="nb-review">(<?php echo number_format($nb); ?>)</span>
            <?php } ?>
	    </div>
	    <?php
	}

}