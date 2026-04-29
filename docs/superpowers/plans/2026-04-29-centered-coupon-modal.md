# Centered Coupon Modal Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a centered modal popup for available coupons in the WooCommerce cart.

**Architecture:** Update the backend to fetch all active coupons, then modify the frontend template to display them in a modal with custom CSS and JS.

**Tech Stack:** PHP (WooCommerce), JavaScript (jQuery), Vanilla CSS.

---

### Task 1: Backend Coupon Logic

**Files:**
- Modify: `remote_files/includes/front.php`

- [ ] **Step 1: Update get_available_coupons to return all active coupons**

Modify `get_available_coupons()` to fetch all active coupons and include validation status.

```php
		public function get_available_coupons() {
			if ( fkcart_is_preview() ) {
				return [
					[
						'code'          => 'SAVE10',
						'description'   => 'Get 10% off on your order',
						'amount'        => 10,
						'discount_type' => 'percent',
						'is_valid'      => true,
					],
					[
						'code'          => 'FREE_SHIPPING',
						'description'   => 'Free shipping on all orders',
						'amount'        => 0,
						'discount_type' => 'fixed_cart',
						'is_valid'      => false,
						'error_message' => 'Spend $20 more to unlock'
					]
				];
			}

			$args = [
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'asc',
				'post_type'      => 'shop_coupon',
				'post_status'    => 'publish',
			];

			$coupons           = get_posts( $args );
			$available_coupons = [];

			foreach ( $coupons as $coupon_post ) {
				$coupon = new \WC_Coupon( $coupon_post->ID );
				$is_valid = $coupon->is_valid();
				
				$available_coupons[] = [
					'code'          => $coupon->get_code(),
					'description'   => $coupon->get_description(),
					'amount'        => $coupon->get_amount(),
					'discount_type' => $coupon->get_discount_type(),
					'is_valid'      => $is_valid,
					'error_message' => ! $is_valid ? $coupon->get_error_message() : ''
				];
			}

			return $available_coupons;
		}
```

- [ ] **Step 2: Commit backend changes**

```bash
git add remote_files/includes/front.php
git commit -m "feat: update coupon logic to return all active coupons"
```

---

### Task 2: Update Available Coupons Template

**Files:**
- Modify: `remote_files/templates/cart/available-coupons.php`

- [ ] **Step 1: Update template to handle valid/invalid states**

```php
<?php
// ... existing checks ...
?>
<div class="fkcart-available-coupons-wrap">
    <div class="fkcart-available-coupons-title"><?php esc_html_e( 'Available Coupons', 'cart-for-woocommerce' ); ?></div>
    <div class="fkcart-available-coupons-list">
		<?php foreach ( $available_coupons as $coupon ) : 
            $is_valid = isset($coupon['is_valid']) ? $coupon['is_valid'] : true;
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
    /* ... existing styles ... */
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
```

- [ ] **Step 2: Commit template changes**

```bash
git add remote_files/templates/cart/available-coupons.php
git commit -m "feat: update available coupons template for locked states"
```

---

### Task 3: Modal Implementation in Coupon Box

**Files:**
- Modify: `remote_files/templates/cart/coupon-box.php`

- [ ] **Step 1: Add Modal HTML and update Trigger**

```php
<div class="fkcart-coupon-box-wrap fkcart-panel">
    <!-- ... header ... -->
    <div class="fkcart-coupon-input-group">
        <input type="text" name="coupon_code" class="fkcart-coupon-input" id="fkcart-coupon-code" ... />
        <button type="button" class="fkcart-apply-coupon-btn fkcart-btn-primary" id="fkcart-view-coupons-btn">
			<?php esc_html_e( 'View Coupons', 'cart-for-woocommerce' ); ?>
        </button>
        <!-- ... apply button ... -->
    </div>

    <!-- Modal Structure -->
    <div id="fkcart-coupon-modal" class="fkcart-coupon-modal fkcart-hide">
        <div class="fkcart-coupon-modal-backdrop"></div>
        <div class="fkcart-coupon-modal-content">
            <div class="fkcart-coupon-modal-header">
                <h3><?php esc_html_e( 'Available Coupons', 'cart-for-woocommerce' ); ?></h3>
                <span class="fkcart-coupon-modal-close">&times;</span>
            </div>
            <div class="fkcart-coupon-modal-body">
                <?php fkcart_get_template_part( 'cart/available-coupons' ); ?>
            </div>
        </div>
    </div>
</div>
```

- [ ] **Step 2: Update JavaScript for Modal**

```javascript
    // Open Modal
    $(document).on('click', '#fkcart-view-coupons-btn', function() {
        $('#fkcart-coupon-modal').removeClass('fkcart-hide').fadeIn();
    });

    // Close Modal
    $(document).on('click', '.fkcart-coupon-modal-close, .fkcart-coupon-modal-backdrop', function() {
        $('#fkcart-coupon-modal').fadeOut(function() {
            $(this).addClass('fkcart-hide');
        });
    });

    // Close on Escape
    $(document).on('keydown', function(e) {
        if (e.key === "Escape") {
            $('.fkcart-coupon-modal-close').trigger('click');
        }
    });
```

- [ ] **Step 3: Add Modal CSS**

```css
    .fkcart-coupon-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .fkcart-coupon-modal-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(2px);
    }
    .fkcart-coupon-modal-content {
        position: relative;
        background: white;
        width: 90%;
        max-width: 450px;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        overflow: hidden;
        animation: fkcart-modal-slide 0.3s ease-out;
    }
    @keyframes fkcart-modal-slide {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .fkcart-coupon-modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .fkcart-coupon-modal-header h3 {
        margin: 0;
        font-size: 18px;
    }
    .fkcart-coupon-modal-close {
        cursor: pointer;
        font-size: 24px;
        color: #888;
        line-height: 1;
    }
    .fkcart-coupon-modal-body {
        padding: 20px;
        max-height: 70vh;
        overflow-y: auto;
    }
```

- [ ] **Step 4: Commit UI changes**

```bash
git add remote_files/templates/cart/coupon-box.php
git commit -m "feat: implement coupon modal UI and logic"
```

---

### Task 4: Deployment

- [ ] **Step 1: Push changes to FTP**

Use the established FTP push mechanism to deploy the updated files.
