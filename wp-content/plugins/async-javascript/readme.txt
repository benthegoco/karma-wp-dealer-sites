=== Async JavaScript ===
Contributors: (cloughit)
Donate link: http://www.cloughit.com.au/donate/ (coming soon)
Tags: async,javascript,google,pagespeed,js,speed,performance,boost,render,blocking,above-the-fold
Requires at least: 2.8
Tested up to: 4.9
Stable tag: 2.17.11.15
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Async JavaScript adds a 'async' or 'defer' attribute to JavaScripts loaded via wp_enqueue_script

== Description ==

When a JavaScript file is loaded via the wp_enqueue_script function, Async JavaScript will add an async or defer attribute.

There are several ways an external JavaScript file can be executed:

* If async is present: The script is executed asynchronously with the rest of the page (the script will be executed while the page continues the parsing)
* If defer is present and async is not present: The script is executed when the page has finished parsing
* If neither async or defer is present: The script is fetched and executed immediately, before the browser continues parsing the page

Using async or defer helps to eliminate render-blocking JavaScript in above-the-fold content. This can also help to increase your pagespeed which in turn can assist in improving your page ranking.

<em>Want more control? </em><strong>Async JavaScript Pro</strong> allows you to:

* Selective ‘async’ – choose which JavaScripts to apply ‘async’ to
* Selective ‘defer’ – choose which JavaScripts to apply ‘defer’ to
* Exclude individual scripts – choose which JavaScripts to ignore
* Exclude plugins – choose local plugin JavaScripts to ignore
* Exclude themes – choose local theme JavaScripts to ignore

<a href="http://cloughit.com.au/product/async-javascript-pro/" target="_blank">Read more...</a>

== Installation ==

Just install from your WordPress "Plugins | Add New" screen and all will be well. Manual installation is very straight forward as well:

1. Upload the zip-file and unzip it in the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to `Settings` - `Async JavaScript` menu to load setings page

== Frequently Asked Questions ==

= Which browsers support the 'async' and 'defer' attributes =

The 'async' attribute is new in HTML5. It is supported by the following browsers:

* Chrome
* IE 10 and higher
* Firefox 3.6 and higher
* Safari
* Opera

= Where can I get help? =

Async JavaScript is supported exclusively via our Support Ticketing System at <a href="https://cloughit.com.au/support/?wpsc_category=8">https://cloughit.com.au/support/</a>

= What support do you provide? =

We will provide support for any functionality of the Async JavaScript plugin itself, suggestions for theme / plugin support and suggestions on how Async JavaScript could be improved.

= What support don't you provide? =

We will not provide support for questions such as 'Why is Async JavaScript not making any improvement on my site?'. If you need this level of support we offer a bundled Async JavaScript Pro plus Installation & Configuration (homepage only) AUD $155.00 (<a href="https://cloughit.com.au/product/async-javascript-pro-plus-install/">buy now!</a>)

= Can I use the WordPress Forums to get support for Async JavaScript? =

No. Support is provided via our Support Ticketing System at <a href="https://cloughit.com.au/support/?wpsc_category=8">https://cloughit.com.au/support/</a>

= Can I email the author directly to get support for Async JavaScript? =

No. Support is provided via our Support Ticketing System at <a href="https://cloughit.com.au/support/?wpsc_category=8">https://cloughit.com.au/support/</a>

= What about CSS? =

As the name implies, Async JavaScript is built to enhance JavaScript loading only. Async JavaScript does not have any impact on CSS.

We recommend using the awesome <a href="https://wordpress.org/plugins/autoptimize/">Autoptimize</a> plugin alongside Async JavaScript for CSS optimization.

= Do you have a pro version? =

Yes we do! Here are some of the benefits of Async JavaScript Pro:

* Selective ‘async’ – choose which JavaScripts to apply ‘async’ to
* Selective ‘defer’ – choose which JavaScripts to apply ‘defer’ to
* Exclude individual scripts – choose which JavaScripts to ignore
* Exclude plugins – choose local plugin JavaScripts to ignore
* Exclude themes – choose local theme JavaScripts to ignore

<a href="https://cloughit.com.au/product/async-javascript-pro/">Buy Now!</a>

= I want out, how should I remove Async JavaScript? =

* Disable the plugin
* Delete the plugin

== Screenshots ==

Coming soon!

== Changelog ==

= 2.17.11.15 =

* MOD: Added User Agent to GTMetrix requests

= 2.17.11.03 =

* MOD: Check for GTMetrix class existance prior to including class

= 2.17.09.31 =

* FIX: Sanitise all $_REQUEST
* MOD: Remove notices

= 2.17.09.30 =

* FIX: Sanitise all $_GET and all $_POST
* FIX: Add nonce to ajax calls

= 2017.06.13 =

* MOD: Dashboard Widget and Notices only available to Administrators

= 2.17.05.07 =

* MOD: Remove front facing JS and CSS as not needed

= 2.17.05.05 =

* FIX: Incorrect textarea identifier preventing exclusion save

= 2.17.05.04 =

* FIX: CSS / JS not loading

= 2.17.05.03 =

* MOD: Add test to advise running Wizard is not mandatory

= 2.17.04.25 =

* massive Massive MASSIVE rewrite of Async JavaScript!!!
* Now includes a setup Wizard, Status page, Settings page and a help page.
* Communicates directly with GTmetrix (account required)

= 1.17.02.06 =

* FIX: Remove variable notice

= 1.17.01.22 =

* MOD: Changes in notice functionality

= 1.17.01.14 =

* MOD: Update readme.txt information
* MOD: Minify plugin JS & CSS

= 1.16.12.12 =

* MOD: WordPress 4.7 Support
* AD: Christmas Sale Sale

= 1.16.10.25 =

* AD: Crazy One Week Sale

= 1.16.09.30 =

* MOD: Better detection of jQuery core file

= 1.16.08.17 =

* FIX: Typo in variable name

= 1.16.08.11 =

* NEW: Select jQuery handler
* NEW: Select Autoptimize handler

= 1.16.08.10 =

* FIX: Return $tag instead of $src

= 1.16.08.09 =

* MOD: Added ability to check for spaces in comma separated exclusion list
* MOD: Added support link

= 1.16.06.22 =

* MOD: Remove admin message marketing
* MOD: Moved menu item to Settings menu
* MOD: Fixed marketing image css
* MOD: Fixed spelling of 'JavaScript' to 'JavaScript'

= 1.16.06.21 =

* MOD: converted from 'clean_url' to 'script_loader_tag' filter

= 1.16.03.23 =

* FIX: added check for empty string entered in exclusions

= 1.16.03.13 =

* FIX: Fixed autoptomize settings
* FIX: Removed redundant settings

= 1.16.03.12 =

* FIX: Adjust code flow for registered settings

= 1.16.03.11 =

* FIX: Properly register options

= 1.16.02.18 =

* NEW: Added dismissable upgrade notice

= 1.16.02.17 =

* NEW: Added information for Async JavaScript Pro

= 1.15.02.23.1 =

* FIX: Code error fix

= 1.15.02.23 =

* NEW: Tested for WordPress v4.1.1
* NEW: Added ability to provide a comma seperated list of scripts to be excluded from async/defer (thanks to Nico Ryba for this suggestion)

= 1.14.12.19 =

* NEW: Tested for Wordpress v4.1

= 1.14.12.11.2 =

* FIX: Repaired broken SVN issue preventing plugin install

= 1.14.12.11.1 =

* FIX: Repaired broken SVN issue preventing plugin install

= 1.14.12.11 =

* FIX: Updated minor versioning issue

= 1.14.12.10 =

* Genesis