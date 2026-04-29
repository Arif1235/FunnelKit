# View Available Coupons Design Spec

## Goal
Add an expandable "Available Coupons" section directly within the cart sidebar to allow users to easily discover and apply coupons without opening a modal.

## User Interface (UI)
- **Toggle Link**: A text link "View available coupons" with a chevron icon, placed below the coupon input group.
- **Expandable List**: A hidden container that slides down when the link is clicked.
- **Coupon Items**: Each coupon will show its code, description, and an "Apply" button.
- **Input Sync**: Clicking "Apply" on a coupon in the list will fill the main coupon input and trigger the application.

## Behavior
- **Smooth Animation**: Use `grid-template-rows` or `max-height` transition for a smooth slide-down effect.
- **Icon State**: The chevron icon rotates 180 degrees when the section is expanded.
- **Modal Removal**: The existing "View Coupons" button (that opens a modal) will be removed in favor of this inline expandable section.

## Technical Approach
- **Templates**: 
    - Modify `remote_files/templates/cart/coupon-box.php` to add the toggle link and the expandable container.
    - Keep using `remote_files/templates/cart/available-coupons.php` but ensure its styles fit the new inline layout.
- **Styles**: Add CSS to `coupon-box.php` (or a global CSS file if preferred) for the slide-down logic.
- **JavaScript**: Update the jQuery logic in `coupon-box.php` to handle the toggle and coupon selection.
