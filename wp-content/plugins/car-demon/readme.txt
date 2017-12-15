=== Car Demon ===
Contributors: Jay Chuck Mailen 
Donate link: http://www.cardemons.com/donate/
Tags: car, dealer, car-dealer, cardealer, automotive, sales, lots, auto, motorcycle, bike, boat, airplane, rvs, tractors, motorhomes, trailers
Requires at least: 3.4.2
Tested up to: 4.8.1
Stable tag: 1.7.6
License: GPLv2

Car Demon is a PlugIn designed for car dealer and vehicle sales. Inventory Management, Lead Forms, ADFxml support, Lead Routing, Staff Page and more!

== Description ==

The Car Demon PlugIn is full of features. It has a general contact form, service appointment form, service quote form, trade-in form, a finance application and a vehicle information form all with AdfXml support.

It also contains a powerful inventory management tool with optional VinQuery Decode Integration, compare vehicles tool, multiple location support and a whole lot more.

= What can Car Demon do? =

Car Demon is a powerful tool that manages the most important aspects of a vehicles sales website.

It's used for more than just Car Dealers, it's used for selling RVs, Boats, Campers, Motorcycles, Trailers, Semi-Trucks and even Planes.

If you're building a car dealer or vehicle sales website Car Demon gives you the wicked powers you need.

= Full Featured Inventory Control =

Use the inventory shortcode, [cd_inventory] and drop your vehicles onto any page.

The inventory shortcode accepts the following parameters;

title, year, make, model, condition, body_style, transmission, location, stock, criteria (keyword search), min_price, max_price, mileage (searches vehicles with less than the mileage entered) & show_sold

For example, you can create a page to show just ford trucks;
[cd_inventory title="Ford Trucks" make="ford" body_style="truck"]

Car Demon also supports custom theme pages by utilizing the custom post type cars_for_sale.

To give you even more power you can assign search forms to the page your shortcode is on and search just those items.

To make sure your search forms point to the correct result page you can set the result_page parameter for the search form widget or use it in the search form shortcode;

[search_form size=1 result_page="the url to your inventory shortcode page"]

You have the option to enable a compare vehicle tool and the power to enable an auto load feature that continually loads inventory without the need to click on the next page.

= Easy to Use Admin Area =

The settings area has a Startup Guide to walk through your initial setup and get you up and running as fast as possible.

Turn features on and off easily letting you customize your site with ease.

Inventory management is a snap, quickly add and remove vehicles, upload photos & change prices.


== Installation ==


Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

Go to your admin area and expand the menu for "Cars for Sale" and click on "Car Demon Settings".

Walk through the Startup Guide and adjust your settings as desired.

Use shortcodes to create your inventory page(s), forms, staff highlights & more.

Make sure you've setup your Location Settings: Dashboard->Cars For Sale->Location Settings

If you sell vehicles at more than one location then, click on locations from the Cars For Sale Menu and add an entry for each lot.

After you have setup your locations click on "Location Settings", fill out and save the form for each location, INCLUDING THE DEFAULT.

It is important to check and make sure your Finance Disclaimer and Descriptions are legal for your location. 

We take no responsibility for the legality of the default entries.

Adjust your theme settings and add widgets as desired.

You're now ready to start adding vehicles to your site.

Please make sure you add a price to your vehicles. If you don't wish the price to be seen you can select "Do Not Show Prices" on the location settings page.

Vehicles will not display in the search results until they have been marked published AND a price has been added to them, even if it's just a 0 price.

Finally, go to your admin area and click settings, then select permalinks. Add this as a custom permalink; /%postname%/%post_id%

Congratulations!


== Frequently Asked Questions ==


* How do I view just a basic list of my vehicles?

First go to your admin area and click settings, then select permalinks. Add this as a custom permalink; /%postname%/%post_id%

Then use the inventory shortcode, [cd_inventory] and drop your vehicles onto any page.

The inventory shortcode accepts the following parameters;

title, year, make, model, condition, body_style, transmission, location, stock, criteria (keyword search), min_price, max_price, mileage (searches vehicles with less than the mileage entered) & show_sold

For example, you can create a page to show just ford trucks;
[cd_inventory title="Ford Trucks" make="ford" body_style="truck"]

* How do I add people to my staff page?

You'll need to add them as users to the website, make them subscribers if you'd like.

On their profile page you'll see a section labeled "Extra Profile Information", add a photo (120x120 suggested), a job title, a location and then click a radio button that matches their duties.

Click save and you should now see them listed on your staff page.

* How do I route the different forms to different people?

You do this on the "Location Settings Page" under "Cars for Sale".

* How do I setup a sales person with an affiliate link?

On each user profile page there is a segment called "Advanced Sales Information", the custom URL can be used by the sales person to customize the site with their information.

"Under Custom Site Leads" you'll need to put a check next to each type of form they should receive.

If you have multiple locations you can also decide if they should receive leads for just their location or for all locations.

For example, let's say we have a sales person name Bill.

He only sells new cars and has permission to bid on trades, but doesn't have permission to work finance.

You would check the "New Car Sales" and "Trade" boxes.

Bill will now receive all "New Car" & "Trade" contact forms from anyone who entered the site using his affiliate link.

His name and phone number will also appear on all new vehicle pages.

The querystring for the affiliate link can be used on almost any page. Simply add it to the end of the url and you're set.

If you're using the Car Demon theme or Car Demon front page you can add custom headers for each sales person and even a custom slide on the homepage.

* What happens if someone using an affiliate link uses the email a friend button?

All links in the email they recieve will contain the affiliate link, so the original sales person will still receive any future contacts from their friend.

This is especially handy if a sales person wants to email a car page to a potential customer.

If the sales person is viewing the site with their own affiliate link then any vehicle they email to a customer with the "Email a Friend" feature will contain all of their contact info.

* How do I clear an affiliate link?

If you add ?sales_code=0 to the url for your site it will remove the cookie that stores the sales persons affiliate information and will restore the site to normal lead routing.

* Why don't my vehicles appear in the search even though I've published them?

Please make sure you add an "Asking Price" to your vehicles, even if it's a 0 price.

If you don't wish the price to be seen you can select "Do Not Show Prices" on the location settings page.

* Why don't my vehicles have titles even though I've entered a title for them?

By default Car Demon will display vehicles titles as Year Make Model Stock #, the title field is currently only used for descriptive purposes on the back end.

You can tell Car Demon to use the vehicle title field in the admin settings.

* How do I get the inventory to load new vehicles when I scroll to the bottom of the page?

Make sure you install the WP-Pagenavi PlugIn. It not only creates awesome pagination, but by default it will help the autoload feature function.

The Car Demon PlugIn looks for the existing page navigation with specific id tags and uses those as an indicator for when the page has ended and which set of vehicles to load next.

The auto inventory load has a few options in the backend that can be tweaked to work with different layouts and without WP-Pagenavi if needed.

* Does Car Demon have any hooks, filters or shortcodes for developers to use?

Yes it does. Please visit our website CarDemons.com and look in our blog for developer resources.

* I have created a language translation and would like to share it, where do I send it?

We would love to include your translation! Please visit our website CarDemons.com and fill out a contact request form and let us know, we will be more than happy to test your file and include it in our next release.

* Does Car Demon use taxonomies for categorizing vehicles and how can I leverage that?

Yes, Car Demon uses several custom taxonomies; condition, body_style, vehicle_year, make, model and location.

These can be used to create pages containing vehicle categories, for example;

Let's say you want to provide links to all the different body styles, to link to all of the coupes in inventory.

You would create a link to http:/yoursite.com/body_style/coupe to link to all trucks it would be http:/yoursite.com/body_style/truck.

You can use the same logic to leverage the other custom taxonomies as well.

You will need to create custom template pages for your theme/child theme to take full advantage of this feature.

Please check our website, http://CarDemons.com, for more information.

* I'm having trouble using custom taxonomies, I keep getting a 404 or page not found error.

Make sure you update your permalinks to /%postname%/%post_id% this should resolve most issues with using custom taxonomies.

* How do I override the vehicle inventory and display pages?

Car Demon has several hooks & filters you can take advantage of and tweak or completely redesign the vehicle display, search forms and more.

Please check our website, http://CarDemons.com, for more information.

* How do I use the shortcodes to include forms in my pages?

Shortcodes can be used without arguments and will display a radio selection to determine the location to send the form.

Shortcodes for [part_request], [service_request], [service_quote], [trade] & [finance_form] now have the optional argument "location" added to them.

The location argument accepts the name assigned to that form for the location you wish to send the form to and hides the radio selection.

For example, let's say you have a location called "Our Used Car Lot" and you have two different part departments, one that handle domestic vehicle parts and one that handles imported vehicle parts.

You will need to create 2 locations "Our Used Car Lot Domestic" and "Our Used Car Lot Imports".

Under location settings you would enter a different name for Parts under both locations, ie "Domestic Parts" & "Import Parts".

You can now use the part_request shortcode on two different pages and route each one to the correct department.
Exp. [part_request location="Domestic Parts"] and [part_request location="Import Parts"]

* I only have one location, how do I hide the location radio buttons on my forms?

You will need to set the location argument for your form shortcodes to the form's contact name entered under the default location.
Exp. [part_request location="Default Part Name"], this will hide the location radio buttons.

* How do I get rid of the drop down on the Contact Us Form? I want it to always go to the same person.

The contact form shortcode, [contact_us], has an argument of "send_to" that accepts a single email address.

If you set this argument in your shortcode it will hide the drop down and send the contact form to the address you supplied.
Exp. [contact_us send_to="me@my_site.com"]

* How do I add a form as a Popup?

Two new optional arguments have been added to several of the form shortcodes; popup_id and popup_button.

These may be used with the following shortcodes; contact_us, part_request, service_form & service_quote
At this time they are NOT available for; trade or finance_form

By setting the popup_id argument to a unique value you tell Car Demon to simply add a button to the page that opens your form in a popup lightbox.

The popup_button argument allows you to customize what the button says.

For Example [part_request popup_id="1" popup_button="Request Parts"] this would create a button that says "Request Parts" which opens the parts request form in a popup lightbox.

== Screenshots ==
1. This is a quick look at the inventory management screen. You can quickly change prices and mark vehicles sold without opening each vehicle.

2. Here's a glance of what you can do with Car Demon and some of it's extensions. The site you see here is using the basic Car Demon Theme.
For more information visit our website; http://CarDemons.com

== Changelog ==
= 1.7.6 =
*
* Added post object to array returned by cd_get_car( $post_id ) function 
* Added array check to vehicle tabs
* Compare widget now uses cd_get_car()
* Fixed issue with default zero price text not being shown unless prices are turned off
*
= 1.7.6 =
*
* Changed vehicle search drop down to use variable for taxonomy in car_demon_get_my_tax( $taxonomy, $val, $settings = array() )
* Compare function now uses cd_get_car( $post_id ) to get all its data
*
= 1.7.5 =
*
* Added full localization to "sold" field in admin area
*
= 1.7.4 =
*
* Removed old php4 class constructor calls
* FIX: Add multiple taxonomy ability to get_cd_term() function
* Added function cd_tag_filter( $post_id, $content ) for filtering tags using brackets exp: {year} {make} {model}
*
= 1.7.3 =
*
* Fix: Corrected duplicate srp display
*
= 1.7.2 =
*
* FIX: Resolved issue with recent WP security update listing duplicate custom taxonomy items
*
= 1.7.1 =
*
* FIX: Added deprecated filter car_demon_display_car_list_filter to $html variable
* FIX: Changed vehicle tabs to use cd_get_car() for options rather than decode_string meta field
*
= 1.7.0 =
*
* Public release version
*
= 1.6.992 =
*
* Modified flush rewrite code on activation
* Added CSS classes to the different vehicle options when tabs are used
* Deprecated old filters starting with car_demon_
* All new filters will start with cd_
*
= 1.6.991 =
*
* Added patch to resolve Yoast SEO issue
*
= 1.6.99 =
*
* Added multiple taxonomy search support for make, model, condition, body style & vehicle tag.
* Multiple taxonomies of the same type can now be added to the shortcode using a pipe | between them.
* Example [cd_inventory make="ford|gmc"] - this will list all Ford and GMC vehicles
* If year range is searched and the min and max year are the same it will now search the single year and not a false range.
* Added do_actions 'cd_before_content_srp_action' & 'cd_after_content_srp_action' with parameter $atts
*
= 1.6.98 =
*
* Added filter for vehicle title: car_title_filter
*
= 1.6.97 =
*
* Added constant CD_DEFAULT_CONDITION. If defined Car Demon will set the defined condition to any vehicle that doesn't have a condition added.
* By default Car Demon will assign preowned to any vehicle that does not have a condition, unless set by the above constant.
* Fixes Issue #25
* Corrected issue preventing 0 price and 0 mileage from saving by default
* If no stock number is saved then post id is added as stock number.
* If sort by price is disabled the initial sort now defaults to mileage. Fixes Issue #26.
* Added vehicle_tag to cars_for_sale custom post type and query. Fixes Issue #17.
*
= 1.6.96 =
*
* Corrected issue with apostrophes in about us tab. Fixes Issue #2.
* Added constant CD_LEGEND_ON_BOTTOM, if defined the option legend will appear on the bottom. Fixes issue #6.
* Added stripslashes_deep to searched by items. Fixes Issue #16
* Added div with clear float to end of VDP. Fixes Issue #23
*
= 1.6.95 =
*
* Added filter cd_price_format_filter to allow filtering number format before display
* Added filter cd_nav_filter to allow filtering the vehicle navigation
* Localized sold yes / no field
*
= 1.6.94 =
*
* Minor change to finance form handler
* Added cd_single_car_content_filter to allow filtering of vehicle description
* Added cd_pre_specs_filter to allow filtering right before the specs are listed
*
= 1.6.93 =
*
* Added constant CD_USE_WPMAIL, if defined Car Demon forms will use wp_mail() instead of mail()
* Added filter cd_lightbox to make it easy to swap out the default lightbox
*
= 1.6.92 =
*
* Added role capabilty to vehicle specs and options Fixes Issue #22
* User must have role edit_posts by default to edit each section
* Added filter for cd_cap_settings_filters so developers can manipulate Car Demon capabilities
* Added filter for cd_cap_default_settings_filter so developers can manipulate the default Car Demon capabilities
* Added constant CD_RESTRICT_OPTIONS_MSG, if defined it will use the option's capability to determine if a visitor can see data
* Example: define( 'CD_RESTRICT_OPTIONS_MSG', 'Register to see details' );
* Added constant CD_RESTRICT_SPECS_MSG if defined it will use the specs's capabilityy to determine if a visitor can see data
* Example: define( 'CD_RESTRICT_SPECS_MSG', 'Register to see specs' );
* Added constant CD_RESTRICT_SPECS_ALL_MSS if defined the specs message will only display one time per section
* Example: define( 'CD_RESTRICT_SPECS_ALL_MSS', true );
* Changed default field height on SRP to 14px
*
= 1.6.91 =
* Corrected layout issue on manage locations screen
* Updated TGM-Plugin-Activation to version 2.6.1
*
= 1.6.9 =
* Added ability to hide vehicle condition on default templates 
* Added unique type based CSS classes on the vehicle fields and labels
* Added constant CD_NO_SESSION - if defined session_start() won't be called even if no session_id has been generated
* Added constant CD_NO_AFFILIATES - if defined the sales affiliate code and car_demon_subdomains will be disabled
* Added constants CD_PRICE_START, CD_PRICE_STOP, CD_PRICE_gap to control price drop down options available in search form
* Added constants CD_MILEAGE_START, CD_MILEAGE_STOP, CD_MILEAGE_GAP to control mileage drop down options available in search form
* Added filter cd_search_price_filter for filtering all price search options available in the search form
* Added filter cd_search_mileage_filter for filtering all mileage search options available in the search form
* Fixes issue #20
* Added filter cd_price_format for filtering price format in search form
* Added filter cd_mileage_filter for filtering mileage format in search form
* Fixes issue #21
* Added javascript to prevent users from entering non-numeric prices
* Added Constant CD_NON_NUMERIC_PRICE to disable javascript to prevent non numeric prices from being entered
* Fixes issue #18
* Added confirmation to reset button to make sure user is aware it will reset everything
* Fixes issue #7
*
= 1.6.85 =
* Localized shortcodes and their parameters
* Added constant CD_FORM_PROVIDER - if defined it will change the ADFxml lead form provider to item defined
*
= 1.6.84 =
* Added constant CAR_DEMON_ADMIN to control settings page access without using roles
* Define with user id(s) divided by commas (if more than one) - exp: define( 'CAR_DEMON_ADMIN', '1,3' );
* Minor text changes on location edit page
* Added 2 funtions to get-contact-info.php to use the oldest location if no default location exists
* This should allow users to rename the default location
* Also added 2 constants to force custom location as default
* CD_DEFAULT_LOCATION_NAME & CD_DEFAULT_LOCATION_SLUG
* If defined these will force the default location information to the values defined
* When a new location is added from vehicle edit page all default settings will be applied
*
= 1.6.83 =
* Thanks to the efforts of @DrScoot (and the rest of the Polyglots team) we now have full Dutch language support
* If you would like to help translate Car Demon please vist:
* https://translate.wordpress.org/projects/wp-plugins/car-demon
* Changed "Car Demon Settings" to just "Settings" in admin menu Dashboard->Cars For Sale->Settings
* Reset button in settings now resets custom labels and all option fields
* Updated yes / no drop downs in settings to use tertiary statements
*
= 1.6.82 =
* Changed content filter so you can now use shortcodes inside the vehicle description
* Adjusted compare ajax handler to return correct image for vehicles
* Added filter to Single Vehicle Page for detail output
* apply_filters( 'cd_single_car_detail_output_filter', $detail_output, $post_id );
* Added filter to specs tab
* apply_filters( 'cd_specs_filter', $specs, $post_id );
* Added spinner and message to forms when submit buttons are clicked
* If no stock number is found then return post_id as stock number
*
= 1.6.81 =
* Adjusted shortcode variables to match new $atts method
*
= 1.6.8 =
* Archived change log items prior to 1.6.0 in changelog.txt file
* Moved "Use Included Theme Files" to Legacy Options
* Changed VinQuery.com text
*
= 1.6.74 =
* Added abiltiy to set user capibility for editing default spec fields
*
= 1.6.73 =
* Fixed issue with description formatting not respecting line breaks
*
= 1.6.72 =
* Added function to control main image display
* Added constant CD_LINK_MAIN_IMAGE that can be used to pull main image from image_links
*
= 1.6.71 =
* Code review and misc tweaks to match WordPress.org coding standards
* Primary focus on adding spaces before and after parenthesis
* Removed Mobile_Detect.php
* Moved files during code reorganization
* Disabled ability to show or hide tabs on a per vehicle basis
* Vehicles now resaves meta fields on save / update on edit screen
* Added filter cd_validate_option_filter to provide custom validation on vehicle option save
* apply_filters('cd_validate_option_filter', $val);
* Added slugs to shortcodes
* Removed last of the extract functions in shortcodes
* Added is_home() || is_front_page() check to search query to set homepage paged to page
*
= 1.6.7 =
* js Hook adjustments in lead forms
* Changed timing of scripts and styles loaded in car-demon-header.php
* Added constant CAR_DEMON_VER to use when loading scripts and styles
* Added default custom options list - cd_get_default_options()
* Added more docblocks
* Added spaces before and after parameters to match standards in core files
*
= 1.6.6 =
* Corrected issue with show_sold parameter not being respected in shortcode cd_inventory
* Began process of adding docblocks to code
* General code reorganization
* Removed Car Demon comment management
* Removed popup calculator form
* Removed javascript files no longer used
* Changed Tab options to Vehicle Options
* Changed Edit Vehicle Options to Edit Vehicle Labels
* Modified default options
* Admin area hides dynamic load options if dynamic load is set to no
* Added constant CAR_DEMON_NO_WELCOME to stop welcome redirect for users using older versions of WordPress
* Added filter car_demon_trade_options_filter to allow changing options on trade form.
*
= 1.6.5 =
* Added sample vehicle import button and option to insert up to 30 sample vehicles
* Define CD_IMPORT_SAMPLE_PHOTOS in wp-confirg.php to import all photos rather than link gallery
* Localized search by results
* Added decoded_ flag check on custom specs to allow reuse of existing fields
* Corrected misspelling of object on settings page
* Added filter for single vehicle dislaimer - apply_filters('car_demon_disclaimer_filter', $disclaimer )
* Filter car_demon_disclaimer_filter replaces entire disclaimer section
* Added filter to single vehicle dislaimer text - apply_filters('car_demon_disclaimer_text_filter', $disclaimer )
* Filter car_demon_disclaimer_text_filter filters just the disclaimer text
* minor form adjustments (email a friend and vehicle contact)
*
= 1.6.4 =
* Adjusted transmission field to utilize short description if no long description available
* Adjusted transmission search form field to match short description
* Improved admin area data sanitation
* Renamed admin css file from car-demon-admin.css to cd-admin.css
* Allow default archive template to use car_demon_query_search if search sent to template
* Encapsulated search by results in span to contain each item
* Preparing to move to language packs for all translations
* Replaced dynamic js.php file for content replacement with localized js file
* Adjusted single vehicle lightbox main image
*
= 1.6.3 =
* Added post type check for admin columns
* Added new Portuguese language files
*
= 1.6.2 =
* Added register post type check for function cdcs_register_cpt_projects()
* Added capibility check on user profile for extra field management
* If no stock number entered post_id is saved on post save
* Added span and class to vehicle edit hide tabs option
* Defined $compare if use compare option is false
* Updated TGM-Plugin-Activation to version 2.5.2
*
= 1.6.1 =
* Changed path to theme-files in the template redirect
*
= 1.6.0 =
* All location management fields now display in the taxonomy page
* Default location will now be added after install
* If all locations have been removed an admin alert is now displayed if the default nag has not been hidden
*

== Upgrade Notice ==
* Follow best practices when updating your site.
* Please backup your site files and database before updating.