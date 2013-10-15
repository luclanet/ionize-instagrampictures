Ionize Instagram Pictures
========================

Add instagram pictures to your page on Ionize CMS


Installation
========================

Copy "Instagram.php" to /themes/your-theme/libraries/Tagmanager/Instagram.php

Folder files and cache must be writable.

Features
========================

- Cached instagram requests
- Cached pictures
- Image can be resized with original media module

Usage:

&lt;ion:instagram access_token="instagramaccesstoken123" limit="9"><br>
    &lt;img src="&lt;ion:media size="80,80" />"><br>
&lt;/ion:instagram><br>


&lt;ion:instagram access_token="instagramaccesstoken123" range="2,3"><br>
    &lt;img src="&lt;ion:media size="80,80" />"><br>
&lt;/ion:instagram>
