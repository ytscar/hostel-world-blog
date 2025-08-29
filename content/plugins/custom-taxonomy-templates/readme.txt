=== Custom Taxonomy Templates ===
Contributors: shazdeh
Plugin Name: Custom Taxonomy Templates
Tags: template, taxonomy, category, tag, term, theme, custom-template, category-template, tag-template, archive
Requires at least: 4.4
Tested up to: 4.6.1
Stable tag: 0.2.1

Define custom templates for taxonomy archive views.

== Description ==

Just like the way you can create custom page templates, this plugin enables you to build custom taaxonomy archive templates by adding this bit to the top of your file:

<code>
<?php
/**
 * {Taxonomy Singular Label} Template: Grid
 */
?>
</code>
where {Taxonomy Singular Label} should be replaced with the singular label registered for the taxonomy (for example "Category" or "Tag"). Now when you're adding or editing terms in that taxonomy, you can choose the desired template file.

You can safely include this plugin in your theme and ship custom templates for taxonomy archives with the theme.


== Installation ==

1. Upload the whole plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!


== Screenshots ==

1. Assigning custom templates to a term in custom taxonomy

== Changelogs ==

= 0.2.1 =
* Allow using taxonomy slug in the template header, fix issue with templates not being found in multilingual websites. Much thanks to <a href="https://wordpress.org/support/users/mkdgs/">Mkdgs</a> for the fix!

= 0.2 =
* Fix fatal error when using child themes
* added "custom_taxonomy_templates" filter for extendibility