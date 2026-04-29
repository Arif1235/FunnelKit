<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$front             = \FKCart\Includes\Front::get_instance();
$available_coupons = $front->get_available_coupons();

if ( empty( $available_coupons ) ) {
	return;
}
?>
<div class="fkcart-available-coupons-wrap">
    <div class="fkcart-available-coupons-title"><?php esc_html_e( 'Available Coupons', 'cart-for-woocommerce' ); ?></div>
    <div class="fkcart-available-coupons-list">
		<?php foreach ( $available_coupons as $coupon ) :
			$is_valid = isset( $coupon['is_valid'] ) ? $coupon['is_valid'] : true;
			?>
            <div class="fkcart-available-coupon-item <?php echo ! $is_valid ? 'fkcart-coupon-locked' : ''; ?>">
                <div class="fkcart-available-coupon-info">
                    <span class="fkcart-available-coupon-code"><?php echo esc_html( $coupon['code'] ); ?></span>
					<?php if ( ! empty( $coupon['description'] ) ) : ?>
                        <p class="fkcart-available-coupon-desc"><?php echo esc_html( $coupon['description'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! $is_valid && ! empty( $coupon['error_message'] ) ) : ?>
                        <p class="fkcart-coupon-error"><?php echo esc_html( $coupon['error_message'] ); ?></p>
					<?php endif; ?>
                </div>
				<?php if ( $is_valid ) : ?>
                    <button type="button" class="fkcart-apply-available-coupon fkcart-btn-primary" data-coupon="<?php echo esc_attr( $coupon['code'] ); ?>">
						<?php esc_html_e( 'Apply', 'cart-for-woocommerce' ); ?>
                    </button>
				<?php else : ?>
                    <div class="fkcart-coupon-status-tag"><?php esc_html_e( 'Locked', 'cart-for-woocommerce' ); ?></div>
				<?php endif; ?>
            </div>
		<?php endforeach; ?>
    </div>
</div>
<style>
    .fkcart-available-coupons-wrap {
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
    }
    .fkcart-available-coupons-title {
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 14px;
        color: #333;
    }
    .fkcart-available-coupon-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .fkcart-available-coupon-item:last-child {
        border-bottom: none;
    }
    .fkcart-available-coupon-code {
        font-weight: bold;
        color: var(--fkcart-primary-bg-color);
        border: 1px dashed var(--fkcart-primary-bg-color);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 13px;
    }
    .fkcart-available-coupon-desc {
        font-size: 12px;
        color: #666;
        margin: 5px 0 0;
    }
    .fkcart-apply-available-coupon {
        padding: 6px 12px;
        font-size: 12px;
        cursor: pointer;
    }
    .fkcart-coupon-locked {
        opacity: 0.7;
        background: #fafafa;
    }
    .fkcart-coupon-error {
        font-size: 11px;
        color: #888;
        margin: 4px 0 0;
        font-style: italic;
    }
    .fkcart-coupon-status-tag {
        font-size: 10px;
        color: #aaa;
        text-transform: uppercase;
        font-weight: bold;
    }
</style>
