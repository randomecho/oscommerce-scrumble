# Clean Slate - Upgrade

Line numbers are based on a fresh install of osCommerce version 2.3, 
so use them as guides if other modules or enhancements have already been installed.


## New files to upload

- catalog/admin/clean_slate.php
- catalog/admin/includes/languages/english/clean_slate.php


## Files to edit

- catalog/admin/includes/boxes/tools.php
- catalog/admin/includes/filenames.php
- catalog/admin/includes/languages/english.php


### catalog/admin/includes/boxes/tools.php

Add on line 66, or wherever you want it to appear on the Tools sidebar:

    array(
      'code' => FILENAME_CLEAN_SLATE,
      'title' => BOX_TOOLS_CLEAN_SLATE,
      'link' => tep_href_link(FILENAME_CLEAN_SLATE)
    ),


### catalog/admin/includes/filenames.php

Add on line 22:

    define('FILENAME_CLEAN_SLATE', 'clean_slate.php');


### catalog/admin/includes/languages/english.php

Add on line 105:

    define('BOX_TOOLS_CLEAN_SLATE', 'Clean Slate');
