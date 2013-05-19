# Repatriator - Address book fixer

Fixes up address book entries where the country ID column is incorrect and 
you don't want to edit the database row by affected row.

This might happen if you bulk import customer details from a CSV source, 
or another shopping cart database, and the country mapping did not correctly 
line up for customers.

If customers try and checkout using an address that to them looks correct, 
but doesn't have the correct country ID in the column, it may result in 
blank customer details in the Orders section in admin.


## Install

Just drop this file into your catalogue directory. Or your admin. 
Doesn't really matter.

    
## Usage

1. Load the page in your browser. For example,

        http://example.com/admin/repatriator.php

2. Select the correct country per address
3. Select the correct state/province where/if applicable
4. Click "Save"
5. Repeat steps 2 to 4 until the madness is gone
6. Delete the Repatriator file
