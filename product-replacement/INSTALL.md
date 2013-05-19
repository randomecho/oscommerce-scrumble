# Product replacement - Install

File replacements are based on a fresh install of osCommerce version 2.3.
If you have otherwise tinkered with your install, read through the UPGRADE
notes instead.


## Files to replace

- catalog/product_info.php
- catalog/includes/languages/english/product_info.php
- catalog/admin/categories.php
- catalog/admin/includes/functions/general.php
- catalog/admin/includes/languages/english/categories.php


## Database

Run the following SQL command against the database

    ALTER TABLE products
     ADD COLUMN products_replacement INT (11) DEFAULT '0';

This adds a new column to the PRODUCTS table that allows the 
module to know where the old product should redirect to.