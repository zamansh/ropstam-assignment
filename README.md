# Ropstam Assignment — Shopify & WordPress

This repository contains my solutions for the Shopify and WordPress development assignments.

## 1. Shopify Bundle Builder

**Development Store:** https://devassignment.myshopify.com/  
**Store Password:** `dev-beta`  
**Section File:** `Shopify/bundle-builder.liquid`

### Features

- **Three Product Bundles:** Displays up to three product groups for customers to build a bundle.
- **One Product per Bundle:** Customers can select one product from each group.
- **Live Price Calculation:** Automatically updates the total price based on selected products.
- **Add to Cart:** Adds all selected products to the cart in a single request without reloading the page.
- **Sold-Out Product Handling:** Prevents unavailable products from being selected or added to the cart.
- **Loading State:** Displays a loading state while the bundle is being added to the cart.
- **Error Handling:** Handles cart request failures and displays an appropriate error message.
- **Responsive Design:** Works across desktop, tablet, and mobile devices.
- **Vanilla JavaScript:** Implements interactive functionality without additional JavaScript libraries.

### How to Test

1. Open the development store and enter the store password.
2. Navigate to the page containing the Bundle Builder.
3. Select **one product from each of the three product groups**.
4. Verify that the total bundle price updates when selections change.
5. Click **Add to Cart** and confirm that all three selected products are added to the cart.
6. Check the cart to verify the selected products, quantities, and prices.
7. Test sold-out products and confirm that unavailable products cannot be purchased.
8. Test the loading state and error handling when a cart request fails.

### Technologies

Liquid, HTML, CSS, and vanilla JavaScript.

## 2. WordPress Dynamic Product Add-on

**Live Preview:** https://dev-ropstam-solutions.pantheonsite.io/  
**Plugin Folder:** `Wordpress/custom-product-addon/`  
**Plugin ZIP:** `Wordpress/custom-product-addon.zip`

### Features
- Custom Services post type with base price and delivery time fields.
- `[cpa_recent_services]` shortcode displays the three most recently updated services.
- Live price calculator with optional Priority Turnaround (+$50).
- Secure input handling, output escaping, and nonce validation.
- Responsive service cards and service details pages.

### Installation & Testing
1. Upload the plugin ZIP through **WordPress Admin → Plugins → Add New Plugin → Upload Plugin**.
2. Activate the plugin and add at least three services.
3. Add `[cpa_recent_services]` to a page.
4. Test service details and the calculator, including the Priority Turnaround option.

## Technologies
- **Shopify:** Liquid, HTML, CSS, vanilla JavaScript.
- **WordPress:** PHP, WordPress APIs, CSS, vanilla JavaScript.

