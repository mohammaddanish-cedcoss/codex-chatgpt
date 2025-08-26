# Advanced AI Product Recommendations

This WordPress plugin integrates with WooCommerce to show AI-powered product recommendations on product, cart, and checkout pages. It works with both classic templates and the WooCommerce Cart and Checkout blocks.

## Installation
1. Copy the `ai-product-recommendations` folder into your WordPress `wp-content/plugins` directory.
2. Activate **Advanced AI Product Recommendations** in the WordPress admin.
3. Visit **Settings → AI Recommendations** to enter your OpenAI API key and choose where recommendations are displayed.

## How it works
The plugin gathers a list of other products and asks the OpenAI API to choose the most relevant items for the current context. The selected products are then rendered using WooCommerce's `[products]` shortcode.

If no API key is set or the API call fails, the plugin falls back to a random selection of products.
