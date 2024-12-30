

## Manage Guest Checkout Coupon Code


Scriptlodges Guest Coupon Manager Restricting coupon with maximum uses for guest checkout.


## Installation

#### Step 1
##### Using Composer (recommended)
```
composer require scriptlodges/magento2-guest-coupon-manager
```
##### Manually
 * Download the extension
 * Unzip the file
 * Create a folder {Magento 2 root}/app/code/Scriptlodges/GuestCouponManager
 * Copy the content from the unzip folder

#### Step 2 - Enable Module (from {Magento root} folder)
 * php -f bin/magento module:enable --clear-static-content Scriptlodges_GuestCouponManager
 * php -f bin/magento setup:upgrade

#### Step 3 - Configuration

 Log into your Magento 2 Admin, then goto Stores -> Configuration -> Scriptlodges -> Checkout ->

Contribution
---
Want to contribute to this extension? The quickest way is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).


Support:
---
Get Data record for guestEmail used coupon code:

    SELECT increment_id AS order_id, customer_email AS guest_email, coupon_code,created_at,grand_total FROM  sales_order WHERE customer_id IS NULL AND coupon_code IS NOT NULL ORDER BY  created_at DESC limit 10;


    SELECT rule_id,name,description,from_date,to_date,uses_per_customer, times_used FROM `salesrule` WHERE name="Kaffee15";


Support:
---
If you encounter any problems or bugs, please open an issue on [GitHub](https://github.com/scriptlodges/magento2-reindex/issues).

Need help setting up or want to customize this extension to meet your business needs? Please email support@scriptlodge.com and if we like your idea we will add this feature for free or at a discounted rate.

Other Extensions
---
[Manage Guest Checkout Coupon](https://www.scriptlodge.com/magento2/extensions/guest-coupon-manager.html) |

© Scriptlodges Inc. | [www.scriptlodge.com](https://www.scriptlodge.com)



======
