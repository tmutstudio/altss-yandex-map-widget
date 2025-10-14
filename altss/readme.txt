=== Alternative Site Settings ===
Tags: settings, custom records, duplicate post, cookie banner, analytics scripts, seo meta fields, reviews, contact forms, disable all comments
Requires at least: 5.9
Tested up to: 6.8.2
Stable tag: 1.2.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin for managing website settings, including feedback forms, post duplicator, cookie banners, personalized SEO fields for pages and posts, reviews, and contacts.

== Description ==

The "Alternative Site Settings" plugin was originally conceived as a starter feature set for further development of a single project. As a result, it has been optimized and can be used for small, standard projects, such as landing pages or business cards. Since version 1.2.0, the plugin includes cookie banner functionality.

The plugin includes the following features:

* Editing basic settings, such as the site name and description, meta tags (title and description) for the homepage, og:image for the homepage, copyright information, and additional text fields for the header and footer.
* Editing meta tags (title and description) for pages and posts, and og:image for pages and posts.
* Post duplication functionality.
* Cookie banner with the ability to customize the design and text.
* Connecting Google Tag and Yandex.Metrica analytics scripts based on user selection via the cookie banner.
* Fields for the contact information section, which is typically located at the bottom of the landing page or in the footer. The contact section can also include a map via a third-party plugin shortcode or embed a static image with an office location map. * Five pre-defined user post types: "News," "Promotions," "Documents," "Books," and "Videos." Each post type is disabled by default.
* Contact forms with a minimum set of seven fields. They meet the basic needs of pop-up feedback forms.
* The "Reviews" section allows you to organize moderated reviews from site visitors.
* Loadable footer section of the website.
* The top admin panel on the frontend can be collapsed to the upper left corner. * Version 1.1.0 adds the ability to completely disable comments on the site.

Attention! The plugin is focused on working with classic themes.

== Changelog ==

= 1.2.0 =
Version 1.2.0 adds a lot of interesting features:
* 1. A fully functional cookie banner with style customization and text editing options has been added. The admin panel also allows you to enable or disable categories of cookies used on the site.
* 2. To make the cookie banner more user-friendly and manageable, analytics scripts for Google Tag and Yandex.Metrica have been added. In the admin panel, you can completely disable each script and choose how to respond to user consent: either not load the script at all if the user declines, or manage its operation via JavaScript.
* 3. An SEO Meta block with the following fields has been added for each blog post, each page, and each post from the list of custom posts the plugin can create: title, description, and og:image. If fields are left blank, they will be generated automatically, and og:image will be replaced with the thumbnail file if one is defined for the record.
* 4. The appearance in the admin panel has been slightly changed.
* 5. Some issues with saving full-text fields have been corrected.

= 1.1.5 =
For the altss_cform_generator() function, a 7th optional parameter, $height, has been added. It allows you to set the initial height of the editor field. Minimum values: 50 for the newvisual mode and 100 for other modes.

= 1.1.4 =
Fixed a bug with displaying custom placeholder values for each field.

= 1.1.3 =
Minor inaccuracies in the code have been fixed.

= 1.1.2 =
Some other minor inaccuracies in the code have been fixed.

= 1.1.1 =
Minor inaccuracies in the code have been fixed.

= 1.1.0 =
* Added a PHP class, which allows you to disable all the comments on the site. The class is activated if the corresponding Chekbox is checked in the Admin panel.
* Minor changes have been made to the altss_add_editior_field() function, allowing for more flexible control over the connection of the classic editor.
* Fixed errors in HTML code on the Form Sets page in the admin panel.

= 1.0.1 =
* Initial release.

== Frequently Asked Questions ==

= How contact forms are displayed in a theme? =

During plugin activation, the cf-style.tss and cf-script.js files are copied to the “css” and “js” directories located in the “assets” directory of the active theme, respectively. The files do not
overwrite existing files - this is done so that you can set individual styles for forms, unique to each theme. When activating a new theme, the plugin will also have to be activated again.
The display of buttons and forms is carried out either using a shortcode, or by directly registering the buttons in the header file of the theme itself.

Shortcodes:

* [ass_cform_button cfid=1] - Button shortcode
* [ass_cform cfid=1] - Form shortcode

= How are Reviews displayed on the frontend? =

When the plugin is activated, a page type record is created in the posts table with the "reviews" slug and the shortcode [reviews_page] added to the post body.
Also, when activating the plugin, just like in the case of contact forms, the reviews-style.tss and reviews-form.js files are copied to the “css” and “js” directories located in the “assets” directory of the active theme, respectively. Existing files are also not
are overwritten, so you can also set your own review styles unique to each theme. When activating a new theme, the plugin will also have to be activated again.

= How do I insert a button into the policy text to call a cookie banner? =

* To insert a button into the text, the following shortcode is provided:
  [ass_cookie_consent]

* To change the text on the button, the shortcode has a title parameter:
  [ass_cookie_consent title='New text']

= What is this Loadable footer section of the website? =

The footer section allows you to create a ready-made website footer with essential content, such as:
* Company logo,
* Footer menu,
* Copyright,
* Contact form button,
* Block with contact information and map.


= How to include a footer section to the theme? =

You can insert a section into a classic theme using the altss_the_footer_section() function:
<?php if( function_exists( 'altss_the_footer_section' ) ) altss_the_footer_section(); ?>

To insert a footer section into a block theme, you can use the shortcode [ass_footer_section];

Do not forget to check on the checkbox on the first settings tab!



= How can a developer use this plugin in his individual project? =

In order to start building their project, the developer simply needs to rename the plugin directory, the main plugin file and the plugin prefix (altss_). Attention! This must be done before activating the plugin.

Also, additional tips and recipes for embedding code into the theme, changing functionality, etc. will be published on the page:
https://github.com/tmutstudio/alternative-site-settings/blob/master/recipes_and_tips.md


== Screenshots ==
1. Admin Panel -> ASS Plugin site settings start page -> tab "Main settings".
2. Admin Panel -> ASS Plugin site settings start page -> tab "Main settings" - full page screenshot.
3. Admin Panel -> ASS Plugin site settings start page -> tab "Custom records".
4. Admin Panel -> ASS Plugin site settings start page -> tab "Text blocks".
5. Admin Panel -> ASS Plugin Contact Forms Settings Page -> tab "Messages from forms".
6. Admin Panel -> ASS Plugin Contact Forms Settings Page -> tab "Messages from forms" -> Modal window for viewing message details.
7. Admin Panel -> ASS Plugin Contact Forms Settings Page -> tab "Form sets" - All forms are collapsed.
8. Admin Panel -> ASS Plugin Contact Forms Settings Page -> tab "Form sets" - One of the forms is expanded.
9. Admin Panel -> ASS Plugin Contact Forms Settings Page -> tab "Form sets" -> Modal window with a set of form fields.
10. Admin Panel -> ASS Plugin Contact Forms Settings Page -> tab "Forms fields".
11. Admin Panel -> ASS Plugin REVIES Page.
12. Admin Panel -> ASS Plugin REVIES Page -> Reply to review.
13. Frontend -> REVIES Page (TAMA WP Theme).
