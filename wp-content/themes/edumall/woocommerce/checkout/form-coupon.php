<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-wrap">
	<div class="woocommerce-form-coupon-toggle">
		<?php
		/**
		 * Filter checkout coupon message.
		 *
		 * @param string $message coupon message.
		 *
		 * @return string Filtered message.
		 *
		 * @since 1.0.0
		 */
		echo apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'edumall' ) . ' <a href="#" role="button" aria-label="' . esc_attr__( 'Enter your coupon code',
				'edumall' ) . '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon link-transition-02">' . esc_html__( 'Click here to enter your code', 'edumall' ) . '</a>' );
		?>
	</div>

	<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none" id="woocommerce-checkout-form-coupon">
		<div class="form-group">
			<input type="text" name="coupon_code" class="input-text"
			       placeholder="<?php esc_attr_e( 'Coupon code', 'edumall' ); ?>" id="coupon_code" value=""/>
			<button type="submit" class="button btn-apply-coupon" name="apply_coupon"
			        value="<?php esc_attr_e( 'Apply coupon', 'edumall' ); ?>"><span
					class="btn-icon fa-light fa-gift"></span><?php esc_html_e( 'Apply coupon', 'edumall' ); ?></button>
			<div class="clear"></div>
		</div>
	</form>
</div>
