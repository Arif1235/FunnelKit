<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = \FKCart\Includes\Data::get_settings();
$front    = \FKCart\Includes\Front::get_instance();

$coupon_placeholder = isset( $settings['coupon_placeholder_text'] ) ? $settings['coupon_placeholder_text'] : __( 'Coupon Code', 'cart-for-woocommerce' );
$coupon_button_text = isset( $settings['coupon_button_text'] ) ? $settings['coupon_button_text'] : __( 'Apply', 'cart-for-woocommerce' );
$coupon_heading     = isset( $settings['coupon_heading'] ) ? $settings['coupon_heading'] : __( 'Got a discount code?', 'cart-for-woocommerce' );
?>

<div class="fkcart-coupon-box-wrap fkcart-panel">
    <div class="fkcart-coupon-heading"><?php echo esc_html( $coupon_heading ); ?></div>

    <div class="fkcart-coupon-input-group">
        <input type="text" name="coupon_code" class="fkcart-coupon-input" id="fkcart-coupon-code" placeholder="<?php echo esc_attr( $coupon_placeholder ); ?>" value="" />
        <button type="button" class="fkcart-apply-coupon-btn fkcart-btn-primary" id="fkcart-apply-coupon">
			<?php esc_html_e( 'Apply', 'cart-for-woocommerce' ); ?>
        </button>
    </div>

	<div class="fkcart-view-coupons-toggle-wrap">
		<button type="button" class="fkcart-view-coupons-toggle" id="fkcart-view-coupons-toggle">
			<?php esc_html_e( 'View available coupons', 'cart-for-woocommerce' ); ?>
			<span class="fkcart-toggle-icon">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
		</button>
	</div>

    <div id="fkcart-coupon-expandable" class="fkcart-coupon-expandable">
		<div class="fkcart-coupon-expandable-content">
			<?php fkcart_get_template_part( 'cart/available-coupons' ); ?>
		</div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Toggle Expandable Section
    $(document).on('click', '#fkcart-view-coupons-toggle', function() {
        var $btn = $(this);
        var $content = $('#fkcart-coupon-expandable');
        
        $btn.toggleClass('active');
        $content.toggleClass('open');
    });

    // Show Apply button when typing a code (Optional: you can keep this empty if you want it always visible)
    $(document).on('input', '#fkcart-coupon-code', function() {
        // Apply button is always visible now
    });

    // Handle manual coupon application from the list
    $(document).on('click', '.fkcart-apply-available-coupon', function() {
        var couponCode = $(this).data('coupon');
        $('#fkcart-coupon-code').val(couponCode);
        $('#fkcart-apply-coupon').removeClass('fkcart-hide').trigger('click');
        
        // Optionally close the list after applying
        $('#fkcart-view-coupons-toggle').removeClass('active');
        $('#fkcart-coupon-expandable').removeClass('open');
    });
});
</script>

<style>
    .fkcart-coupon-box-wrap {
        padding: 16px;
        border-top: 1px solid var(--fkcart-border-color);
    }
    .fkcart-coupon-heading {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 12px;
    }
    .fkcart-coupon-input-group {
        display: flex;
        gap: 8px;
    }
    .fkcart-coupon-input {
        flex: 1;
        padding: 10px;
        border: 1px solid var(--fkcart-border-color);
        border-radius: 4px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }
    .fkcart-coupon-input:focus {
        border-color: var(--fkcart-primary-bg-color);
    }
    .fkcart-apply-coupon-btn {
        padding: 0 24px;
        height: 40px;
        border-radius: 30px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background-color: var(--fkcart-primary-bg-color);
        color: #fff;
        transition: opacity 0.2s;
    }
    .fkcart-apply-coupon-btn:hover {
        opacity: 0.9;
    }
    .fkcart-hide {
        display: none;
    }

    .fkcart-view-coupons-toggle-wrap {
        margin-top: 12px;
    }
    .fkcart-view-coupons-toggle {
        display: flex;
        align-items: center;
        gap: 6px;
        background: none;
        border: none;
        padding: 0;
        color: var(--fkcart-primary-bg-color);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .fkcart-view-coupons-toggle:hover {
        opacity: 0.8;
    }
    .fkcart-toggle-icon {
        display: flex;
        transition: transform 0.3s ease;
    }
    .fkcart-view-coupons-toggle.active .fkcart-toggle-icon {
        transform: rotate(180deg);
    }

    .fkcart-coupon-expandable {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 0.3s ease-out;
        overflow: hidden;
    }
    .fkcart-coupon-expandable.open {
        grid-template-rows: 1fr;
        margin-top: 8px;
    }
    .fkcart-coupon-expandable-content {
        min-height: 0;
    }
</style>
