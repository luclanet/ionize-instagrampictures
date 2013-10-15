Ionize Instagram Pictures
========================

Add instagram pictures to your page on Ionize CMS


Installation
========================

Copy "Instagram.php" to /themes/your-theme/libraries/Tagmanager/Instagram.php

Folder files and cache must be writable.

Features
========================

- Request to instagram api cached
- Pictures cached
- Image can be resized with original media module

Usage:

&gt;ion:instagram access_token="instagramaccesstoken123" limit="9">
    &gt;img src="<ion:media size="80,80" />">
&gt;/ion:instagram>


&gt;ion:instagram access_token="instagramaccesstoken123" range="2,3">
    &gt;img src="<ion:media size="80,80" />">
&gt;/ion:instagram>
