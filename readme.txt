=== Imeem Search Plugin ===
Contributors: didyaknow
Donate link: http://www.didyaknow.tv/
Tags: Imeem, Music, Search
Requires at least: 2.0.0
Tested up to: 2.9.2
Stable tag: 1.0

Imeem Search Plugin developed by DidYaKnow LLC brings the power of Imeem Music Search to Wordpress based on the tag values of a post.

== Description ==

If you are like me and are tired of having to do so much work to add content to your wordpress site, then here is a plugin you might find useful. After playing around with Wordpress and a plugin called WP-TwitterSearch (great plugin by the way), it felt the need to create my own plugin.

The Imeem plugin, was designed for music lovers and musician. It looks at the tags used for the post (so pick them wisely) and then queries Imeem.com. It searches Imeem for all playlists which use the same tags as the ones provided in the post. If there is a match it uses the highest relevance value to then pull back information. Got it?



== Installation ==

1. Download the plugin...you can't expect it to work if you haven't downloaded it. 
2. You got it, install the darn plugin
3. In the bottom left column of the admin panel you will see tab for "ImeemSearch". Click on ImeemSearch to configure the plugin.  Once in the ImeemSearch configuration panel simple configure the width, height, number of records to search (higher the number the longer it takes), and whether you want to give me a little credit.  Don't forget to save your settings
4. Ok, here comes the hard part. If you have never messed with any code this part might be a little hard.  If you expect to put this player in the sidebar... 
5. Go to the 'Appearance' option in the Admin Panel
6. Go to Editor under 'Appearance'
7. Select 'Main Index Temlate' (index.php)
8. Add the following within the post loop and right after the following statement (<?php if (have_posts()) : while (have_posts()) : the_post(); ?>) 

Insert Code Below

`<?php
   $posttags = get_the_tags();
   if ($posttags) {
      foreach($posttags as $tag) {
         $mytag = $tag->name . " ";
      }
      $clean_tag = str_replace(" ",",",$mytag);
   }
?>`

9. Select sidebar.php
10. Add the following after the sidebar div... '<?php wp_imeemsearch_feed($clean_tag); ?>'
11. Once you have saved those changes you are done...
12. You Did It...Hope you enjoy the plugin

== Frequently Asked Questions ==
None


== Screenshots ==
1. Admin Panel `/tags/1.0/screenshot-1.jpg`
2. What the plugin looks like in the sidebar `/tags/1.0/screenshot-2.jpg`


== Changelog ==
1.0 Original Version


== Upgrade Notice ==
None

