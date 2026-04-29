# Design: Centered Coupon Modal

## Goal
Implement a centered popup (modal) that displays all active coupons when a user clicks "View Coupons" in the side cart. This replaces the existing slide-toggle behavior with a more premium, focused experience that also serves as an upsell tool by showing locked coupons.

## User Experience
1. User clicks "View Coupons" in the coupon section of the side cart.
2. A modal fades in with a dark semi-transparent backdrop.
3. The modal lists all active coupons:
   - **Unlocked Coupons:** High visibility, blue "Apply" button.
   - **Locked Coupons:** Grayed out, showing the requirement to unlock (e.g., "Spend $X more").
4. User can click "Apply" to instantly apply a valid coupon.
5. User can close the modal by clicking the "X" button, the backdrop, or pressing Escape.

## Technical Details

### Backend Changes
- **File:** `includes/front.php`
- **Method:** `get_available_coupons()`
- **Logic:**
  - Currently filters only for `is_valid()`.
  - Modify to return all active `shop_coupon` posts.
  - Each coupon object will include:
    - `code`: The coupon code.
    - `description`: The coupon description.
    - `is_valid`: Boolean indicating if it's currently applicable.
    - `error_message`: If invalid, the reason why (e.g., "Minimum spend $50").

### Template Changes
- **File:** `templates/cart/coupon-box.php`
- **Changes:**
  - Add modal HTML structure (hidden by default).
  - Update JS to handle modal opening/closing instead of `slideToggle`.
  - Add CSS for modal positioning, backdrop, and styling.
- **File:** `templates/cart/available-coupons.php`
  - Update to handle the `is_valid` flag and display locked states.

## Verification Plan
1. Click "View Coupons" and ensure the modal appears.
2. Verify backdrop prevents interaction with the background.
3. Check that valid coupons show the "Apply" button.
4. Check that invalid coupons show the locked state and reason.
5. Click "Apply" and ensure the coupon is applied to the cart.
6. Verify modal closing via all methods (X, backdrop, Escape).
