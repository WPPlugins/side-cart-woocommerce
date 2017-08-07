<?php

/**
 * Side Cart HTML
 *
 * @since      1.0.0
*/

//User Settings
$options 	= get_option('xoo-wsc-gl-options');
$sy_options = get_option('xoo-wsc-sy-options');

$show_basket	= isset( $options['bk-show-basket']) ? $options['bk-show-basket'] : 1; //Show Basket
$show_count 	= isset( $options['bk-show-bkcount']) ? $options['bk-show-bkcount'] : 1; //Show Count
$head_title 	= isset($options['sc-head-text']) ? $options['sc-head-text']: __("Your Cart",XOO_WSC_DOMAIN); //Head Title
$cart_txt 		= isset($options['sc-cart-text']) ? $options['sc-cart-text'] : __("View Cart",XOO_WSC_DOMAIN); //Cart Text
$chk_txt 		= isset($options['sc-checkout-text']) ? $options['sc-checkout-text']: __("Checkout",XOO_WSC_DOMAIN); //Checkout Text
$cont_txt 		= isset($options['sc-continue-text']) ? $options['sc-continue-text'] :__( "Continue Shopping",XOO_WSC_DOMAIN); //Continue Text
?>

<div class="xoo-wsc-modal">
	<div class="xoo-wsc-opac"></div>
	<div class="xoo-wsc-container">

		<?php if($show_basket == 1): ?>
			<div class="xoo-wsc-basket">
				<?php if($show_count == 1): 
					$count_value = WC()->cart->get_cart_contents_count();
				?>
					<span class="xoo-wsc-items-count"><?php echo $count_value ?></span>
				<?php endif; ?>
				<span class="xoo-wsc-icon-basket1 xoo-wsc-bki"></span>
			</div>
		<?php endif; ?>

		<div class="xoo-wsc-header">
			<span class="xoo-wsc-ctxt"><?php echo $head_title; ?></span>
			<span class="xoo-wsc-icon-cross xoo-wsc-close"></span>
		</div>
		<div class="xoo-wsc-body">
			<div class="xoo-wsc-content">
				<?php do_action('xoo_wsc_cart_content'); ?>
			</div>
			<div class="xoo-wsc-updating">
					<span class="xoo-wsc-icon-spinner2" aria-hidden="true"></span>
					<span class="xoo-wsc-uopac"></span>
			</div>
		</div>

		<?php if(!empty($cart_txt) || !empty($chk_txt) || !empty($cont_txt)): // If any footer button exists , add footer div ?>

		<div class="xoo-wsc-footer">

			<?php if(!empty($cart_txt)): ?>
			<a href="<?php echo WC()->cart->get_cart_url(); ?>" class="button xoo-wsc-chkt"><?php echo esc_attr($cart_txt); ?></a>
			<?php endif; ?>

			<?php if(!empty($chk_txt)): ?>
			<a  href="<?php echo WC()->cart->get_checkout_url(); ?>" class="button xoo-wsc-cart"><?php echo esc_attr($chk_txt); ?></a>
			<?php endif; ?>

			<?php if(!empty($cont_txt)): ?>
			<a  href="#" class="button xoo-wsc-cont"><?php echo esc_attr($cont_txt); ?></a>
			<?php endif; ?>

		</div>

		<?php endif; ?>

	</div>
</div>
