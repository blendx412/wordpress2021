=== WooCommerce Sticky Product Bar ===
Contributors: OneTeamSoftware
Donate link: https://goo.gl/BVVWC2
Tags: woocommerce, product, woocommerce product, woocommerce bar, woocommerce sticky bar, woocommerce product bar, woocommerce checkout bar, woocommerce cart bar, improve conversion, add to cart, sticky add to cart, woocommerce sticky product bar, sticky bar, sticky product bar, sticky cart bar, sticky checkout bar
Requires at least: 4.0
Tested up to: 5.3
Stable tag: 1.0.14
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The WooCommerce Sticky Product Bar is a highly configurable sticky bar that can show product title, price, rating and Add to Cart / Checkout / Pay button to streamline customers purchasing experience and improve conversion.



== Description ==
When you have a product listing that has a lot of text, reviews, image gallery and all kinds of other information, which is meant to convience customer to place an order, 
then you want to make sure that **Add to Cart** button will be easily accessible, in order not to lose momentum, when customer is finally ready to place an order.

**WooCommerce Sticky Product Bar** plugin is constantly presenting **Add to Cart** button with additional information such as product thumbnail, title and the rating, customers can easily initiate an order.
In addition to that it also adds sticky bar with **Checkout** button to the Cart and **Pay Now** button to checkout pages.

== Features of Woocommerce Sticky Product Bar ==
* Display sticky bar for Desktop, Mobile or both
* Control if it will be displayed for Product listing, Cart and Checkout pages
* Enable/disable for **Out of Stock** products
* Always show it or only when **Action** button is outside of the visible range of the screen
* Control what production information has to be displayed (Image, Name, Rating, Quantity)
* Control if you want to Cart and Checkout Total to be displayed
* Control if you want **Accept terms and conditions** checkbox to be displayed
* Customize **Out of Stock** text
* Customize **Choose and option** text
* Support for custom CSS to match your theme
* You can overwrite plugin template in your theme

[Demo of how it can look for the product can be seen here](https://flexrc.com/product/owl-3-frame/)

== PREMIUM PLUGINS ==
* [ChitChats Shipping](https://1teamsoftware.com/product/woocommerce-chitchats-shipping/) - Ship your packages from Canada via USA as if you were physically there.
* [Marketplace Cart](https://1teamsoftware.com/product/woocommerce-marketplace-cart/) - Offer Amazon like cart/checkout experience for your customers.
* [Package Orders](https://1teamsoftware.com/product/woocommerce-package-orders/) - Automatically create separate orders for items shipped from different locations.
* [Product Categories Menu](https://1teamsoftware.com/product/woocommerce-product-categories-menu/) - Automatically add entire structure of product categories to any menu.

== FREE PLUGINS ==
* [Sticky Product Bar plugin](https://1teamsoftware.com/product/woocommerce-sticky-product-bar/) - Display sticky bar with product details, rating and add to cart button.
* [Shipping Packages](https://1teamsoftware.com/product/woocommerce-shipping-packages/) - Split your cart into packages that can be shipped with different shipping methods.

== Installation ==
1. Upload "Woocommerce Sticky Product Cart" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Installation complete

== Configuration ==
1. Go to Woocommerce -> Settings
2. Choose **Sticky Product Bar** tab
3. Set the settings you prefer
4. Check **Enable** checkbox
5. Hit **Save**

== Customization of Sticky Product Bar Template ==
1. Copy **/wp-content/plugins/wc-sticky-product-bar/templates/wc-sticky-product-bar/product-bar.php** file to your theme **woocommerce/wc-sticky-product-bar/** subfolder
2. Customize **product-bar.php** to your likings

== Screenshots ==
1. Example of how Woocommerce Sticky Product Bar might look for the Product page
2. Admin UI which lets you change Woocommerce Sticky Product Bar settings
3. Example of how Woocommerce Sticky Product Bar might look for the Cart page
4. Example of how Woocommerce Sticky Product Bar might look for the Checkout page

= Feedback =
* We are open for your suggestions and feedback - Thank you for using or trying out one of our plugins!
* With any questions and requests, please feel free to contact us at: http://1teamsoftware.com/

== Changelog ==
= 1.0 =
* Initial release.

= 1.0.4 =
* Hide quantity selection for ultra small screens
* Listen to submit event instead of click to make it work for on mobile
* Fixed bug when js logic has not been enabled for mobile

= 1.0.5 =
* Added support for Cart and Checkout pages
* Renamed bar.php template to product-bar.php

= 1.0.5 =
* Improved support for dynamic cart actions like delete, update
* Show bar only when cart has items
* Hide bar when all items have been deleted

= 1.0.9 =
* Fixed add to cart for mobile devices
* Added support for composite products total

= 1.0.10 =
* Bug fixes and improvements

= 1.0.12 =
* Use flex display type for css instead of absolute positioning
* Ability to display / hide price range for variable products, which can be enabled in the plugin settings
* Support for RTL languages, which can be enabled in the plugin settings
* Automatically update price when product options are changed

= 1.0.13 = 
* Minor style adjustment

= 1.0.14 =
* Changed rule used to pick up price change on the product page
