/**
 * uc_wishlist: Wish List / Gift Registry module for Ubercart
 *
 * developed by Joe Turgeon (http://arithmetric.com)
 * sponsored by Sundays Energy (http://sundaysenergy.com)
 * version 0.6 (March/18/2008)
 * licensed under GPLv2
 */

1. ABOUT

uc_wishlist is a module that adds wish list/gift registry functionality
to Ubercart.

This module has been tested with Ubercart beta 7.

2. INSTALLATION

Download the latest release of this module from:
http://www.ubercart.org/contrib/1403

Uncompress the archive in your Ubercart contrib directory:
[your Drupal root]/sites/all/modules/ubercart/contrib

Enable the wish list module under 'Ubercart - extra' in the 
Drupal module administration page.

3. FEATURES

This module enables users to create a gift list for use as a personal 
shopping list or a public gift registry. An 'Add to wish list' button
is added wherever there is an 'Add to cart' button.

4. DEVELOPMENT

The number of products purchased from a user's wish list is tracked
through a uc_order hook. When an item is added to the shopping cart
from a wish list, extra information (the wish list and wish list
product id) is added to the data array. When a sale is completed
(and the order state changes to post_checkout) the order is checked 
for items that came from a wish list. If any are found, the corresponding
wish list items are updated, and information about the order (the order
id, order product id, user id, and time) are added to the purchase field
in the wish list products table.

The module introduces two new tables:

{uc_wishlists}
wid (int) -- wish list id
uid (text) -- user id or session id
title (text) -- wish list title
date (int) -- wish list expiration time
address (text) -- serialized object of delivery address fields

{uc_wishlist_products}
wpid (int) -- wish list product id
wid (int) -- wish list id
nid (int) -- product node id
qty (int) -- quantity wanted
changed (int) -- timestamp of last modification
data (text) -- serialized array of product attributes and data
purchase (text) -- serialized array of purchase records

Please send questions, comments, or suggestions to: joe @ sundaysenergy.com

