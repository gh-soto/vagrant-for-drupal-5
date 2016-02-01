/**
 * uc_subscribe: Subscriptions module for Ubercart
 * 
 * By Joe Turgeon [http://arithmetric.com]
 * For Sundays Energy [http://sundaysenergy.com]
 * Licensed under GPL version 2
 * Version 2008/04/11
 */

uc_subscribe provides a framework for tracking and acting on subscriptions.

To get started using Subscriptions:

1. Check the admin settings:
/admin/store/subscriptions/settings

Set the 'Order status required for subscription registration' to a status at
the point you want to grant the user the role change. If you set it to
'Pending', it will happen once the order is submitted. If you set it to
'Payment received', it will happen when the order's status is changed to
that.

2. Check the actions page:
/admin/store/subscriptions/actions

You can configure some notifications and default actions here.

3. Add the 'Generic subscription' product feature to a product. Either
configure a product attribute to define the contract length (so that the
user can choose different lengths), or set a default value. The value or 
the attribute option names should be something like: '1 hour' or '30 days' 
or '5 months'.

4. Optionally, make a Workflow-ng configuration for changing the role of a
subscription's customer when the subscription is registered, renewed, or
expires.

Notes:

Recent development is moving away from using "Attributes to use as
subscription period" and "Attributes to use as activation fee" in the
site-wide admin options, and instead use the contract length setting in the
product feature options. (Activation fees are caught in the transition and
not currently reliable.)

You can only use one attribute for the contract length of a given product.
So, in the product feature options, select 'Product attribute' as
'Subscription length is defined by', and then selct either 'Yearly' or
'Monthly' under 'Product attribute for subscription length'.

The option for setting auto-renew is under the Store administration >>
Subscriptions >> Actions.

Subscriptions are activated on each product with the required attribute in 
an order when the order first reaches a specified order status. Optionally, 
uc_subscribe can cancel a subscription when the order is cancelled.

uc_subscribe provides a hook (hook_product_subscribe) that enables modules 
to process four operations related to subscriptions: new registration, 
renewal, expiration, and when a subscription reaches its warning or 
notification threshold. uc_subscribe provides some basic actions for these 
events, including e-mail notifications and automatic renewals.

uc_subscribe also adds a 'Subscriptions' tab to the user's account page. 
This page allows users to renew subscriptions, as well as set options per 
subscription for receiving e-mail notices and automatically renewing the 
subscription.

uc_subscribe uses three methods for repeating an order when a subscription 
is renewed:

1. When an administrator selects 'reorder' on the subscription management 
page, a new order is created with the billing and delivery addresses and 
payment method from the previous order as well as the product being renewed.

2. If the customer clicks on 'renew', the product is added to the shopping 
cart and the customer is directed to the checkout page.

3. If the customer or administrator choose the setting to automatically 
renew products, the order is created similarly to the administrator's 
reorder function and the payment is processed. This provides a method for 
automatically recurring charges. [Note: This has only been implemented for 
credit card payments, and it likely will not work with encrypted credit 
card numbers.]

This module evolved from uc_expiry, coded by aymerick.

This module should be considered alpha. If you are interested in this 
module, please test it, and post your suggestions or contributions to its
Ubercart contribution page.

Thanks for your interest.

