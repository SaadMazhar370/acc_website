<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.6.0
 */

defined( 'ABSPATH' ) || exit;

$lg_items = 5;
if ( 'none' === Edumall_Global::instance()->get_sidebar_status() ) {
	$lg_items--;
}

if ( $upsells ) : ?>

	<div class="up-sells upsells products">
		<?php
		$heading = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'edumall' ) );

		if ( $heading ) :
			?>
			<h2 class="product-section-heading"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<div class="tm-swiper tm-slider edumall-product style-grid"
		     data-lg-items="<?php echo esc_attr( $lg_items ); ?>"
		     data-md-items="3"
		     data-sm-items="2"
		     data-lg-gutter="30"
		     data-nav="1"
		>
			<div class="swiper-inner">
				<div class="swiper">
					<div class="swiper-wrapper">

						<?php foreach ( $upsells as $upsell ) : ?>

							<?php
							$post_object = get_post( $upsell->get_id() );
							setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
							?>
							<div class="swiper-slide">
								<?php wc_get_template_part( 'content', 'product' ); ?>
							</div>

						<?php endforeach; ?>

					</div>
				</div>
			</div>
		</div>

	</div>

<?php endif;

wp_reset_postdata();
