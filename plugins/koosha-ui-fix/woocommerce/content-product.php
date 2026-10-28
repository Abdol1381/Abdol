<?php
/**
 * Product card — name, short desc, price visible (catalog layout)
 */
defined( 'ABSPATH' ) || exit;

global $product;
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$condition = function_exists( 'cafee_3d_product_condition' ) ? cafee_3d_product_condition( $product ) : '';
$label     = function_exists( 'cafee_3d_condition_label' ) ? cafee_3d_condition_label( $condition ) : '';
$url       = $product->get_permalink();
$cat_names = array();
$terms     = get_the_terms( $product->get_id(), 'product_cat' );
if ( $terms && ! is_wp_error( $terms ) ) {
	foreach ( $terms as $term ) {
		if ( in_array( $term->slug, array( 'no', 'karkarde', 'uncategorized', 'daste-bandi-nashode' ), true ) ) {
			continue;
		}
		$cat_names[ $term->name ] = $term->name;
	}
}
$cats  = implode( ' · ', array_slice( array_values( $cat_names ), 0, 2 ) );
$short = wp_strip_all_tags( $product->get_short_description() );
if ( function_exists( 'mb_substr' ) ) {
	$short = mb_substr( $short, 0, 90 );
} else {
	$short = substr( $short, 0, 90 );
}
$short = trim( $short );
?>
<article class="product-card product-card--min product-card--square product-card--catalog product-card--ui-fix<?php echo $condition ? ' is-' . esc_attr( $condition ) : ''; ?>">
	<a href="<?php echo esc_url( $url ); ?>" class="product-card-link">
		<span class="product-image">
			<span class="product-card-shade" aria-hidden="true"></span>
			<?php echo $product->get_image( 'cafee-product', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => esc_attr( $product->get_name() ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( $label ) : ?>
				<span class="product-badge condition condition--<?php echo esc_attr( $condition ); ?>"><?php echo esc_html( $label ); ?></span>
			<?php elseif ( $product->is_on_sale() ) : ?>
				<span class="product-badge sale"><?php esc_html_e( 'تخفیف', 'cafee-3d' ); ?></span>
			<?php endif; ?>
		</span>
		<span class="product-details">
			<span class="product-title"><?php echo esc_html( $product->get_name() ); ?></span>
			<?php if ( $cats ) : ?>
				<span class="product-cat-line"><?php echo esc_html( $cats ); ?></span>
			<?php endif; ?>
			<?php if ( $short ) : ?>
				<span class="product-short-desc"><?php echo esc_html( $short ); ?><?php echo ( function_exists( 'mb_strlen' ) ? mb_strlen( wp_strip_all_tags( $product->get_short_description() ) ) : strlen( wp_strip_all_tags( $product->get_short_description() ) ) ) > 90 ? '…' : ''; ?></span>
			<?php endif; ?>
			<span class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			<span class="product-view-hint"><?php esc_html_e( 'مشاهده جزئیات', 'cafee-3d' ); ?></span>
		</span>
	</a>
</article>
