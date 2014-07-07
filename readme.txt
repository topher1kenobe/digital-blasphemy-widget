=== Digital Blasphemy Widget ===
Contributors: topher1kenobe
Tags: widget
Requires at least: 3.0
Tested up to: 3.9.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to render a random or latest Freebie image from Digital Blasphemy.

== Description ==

Digital Blasphemy ( at <a href="http://digitalblasphemy.com/">http://digitalblasphemy.com/</a> ) is a great desktop wallpaper site.

There are always about 10 free wallpapers available.  This WordPress widget allows you to show thumbnails of the free wallpapers.

You can choose to show a single random thumbnail from the pool, or the latest freebie.

== Installation ==

1. Upload the `/digital-blasphemy-widget/` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit Appearance -> Widgets in the admin and place the widget in a sidebar

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).

== Usage ==

Some basic CSS is included.  If you'd like to turn it off, drop this code into your theme functions.php file or a plugin of your choosing.

`function db_styles() {
    return false;
}
add_filter( 'digitalblasphemy-styles', 'db_styles' );`

== Changelog ==

= 1.0 =
* Initial release
