<?php 
/*
Plugin Name: WP-ImeemSearch
Version: 1.0
Plugin URI: http://didyaknow.tv/wp_plugins/WP-ImeemSearch
Description: Displays playlist based on Tags provided for a post and a response from Imeem.com.  At some point it will be configureable for height, width, tags.
Author: Bob Kumar
Author URI: http://www.didyaknow.tv
*/

/*  Copyright 2009  DidYaKnow.TV (dev@didyaknow.tv)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//lets go ahead and define a few things for easy updating...
define(WPTS_CURRENT_VERSION, "0.1");
define(WPTS_PLUGIN_URL, "http://didyaknow.tv/wp_plugins/WP-ImeemSearch");

//guess we should add some wordpress hooks and actions
register_activation_hook(__FILE__,'set_wpimeemsearch_options');
register_deactivation_hook(__FILE__,'unset_wpimeemsearch_options');
add_action('admin_menu', 'wp_imeem_search_menu');
add_action("plugins_loaded", "wp_imeem_search_widget_init");

  //on plugin activation create options in db
  function set_wpimeemsearch_options() {
    add_option('wpimeemsearch_limit', '1'); //add option to wp_options -> limit default
    add_option('wpimeemsearch_terms', 'Pink Martini'); //add option to wp_options -> terms default
    add_option('wpimeemsearch_width', '590'); //add option to wp_options -> width default (sidebar:300x340, main:590x340)
    add_option('wpimeemsearch_height', '340'); //add option to wp_options -> height default
    add_option('wpimeemsearch_linklove', '0'); //add option to wp_options -> link love default
  }
  
  //on plugin deactivation delete options from db
  function unset_wpimeemsearch_options() {
    delete_option('wpimeemsearch_limit');
    delete_option('wpimeemsearch_terms');
    delete_option('wpimeemsearch_width');
    delete_option('wpimeemsearch_height');
    delete_option('wpimeemsearch_linklove');
  }
  
  //create menu items
  function wp_imeem_search_menu() {
    add_menu_page('WP-imeemSearch Options', 'imeemSearch', 8, __FILE__, 'wp_imeem_search_options', plugins_url('wp-twittersearch/wptwittersearch_18.png'));
    add_submenu_page(__FILE__, 'WP-imeemSearch -> Options', 'Options', 8, __FILE__, 'wp_imeem_search_options');
    add_submenu_page(__FILE__, 'WP-imeemSearch -> About', 'About', 8, 'wp-imeem-search-about.php', 'wp_imeem_search_about');
  }
  
  //the sidebar widget
  function wp_imeem_search_widget($args) {
    extract($args, EXTR_SKIP);
    echo $before_widget;
    wp_imeemsearch_feed($clean_tag);
    echo $after_widget;
  }
  
  //register sidebar widget
  function wp_imeem_search_widget_init() {
    //wp_register_sidebar_widget(wpimeemsearch_widget, __('WP-imeemSearch'), 'wp_imeem_search_widget');
    wp_register_sidebar_widget(wpimeemsearch_widget, 'imeemSearch', 'wp_imeem_search_widget', array('description' => __('Add imeemSearch To Your Sidebar.')) );
  }
  
  //user defined options (values are stored in database in wp_options)
  function wp_imeem_search_options() {
    // If updated options were saved.
    if ( isset($_GET['updated']) ) {
      echo '<div id="message" class="updated fade"><p>Options Saved!</p></div>';
    }
?>

    <div class="wrap">
      <h2>WP-imeemSearch Options</h2>

        <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>

          <table class="form-table">
            <tr valign="top">
              <th scope="row">Search Terms</th>
              <td><input type="text" name="wpimeemsearch_terms" value="<?php echo get_option('wpimeemsearch_terms'); ?>" /></td>
            </tr>
            <tr valign="top">
              <th scope="row">Player Width (sidebar:300, main:590)</th>
              <td><input type="text" name="wpimeemsearch_width" value="<?php echo get_option('wpimeemsearch_width'); ?>" /></td>
            </tr>
            <tr valign="top">
              <th scope="row">Player Height(sidebar:255, main:340)</th>
              <td><input type="text" name="wpimeemsearch_height" value="<?php echo get_option('wpimeemsearch_height'); ?>" /></td>
            </tr>
            <tr valign="top">
              <th scope="row">Limit imeems</th>
              <td><input type="text" name="wpimeemsearch_limit" value="<?php echo get_option('wpimeemsearch_limit'); ?>" /></td>
            </tr>       
            <tr valign="top">
              <th scope="row">Credit WP-imeemSearch</th>
              <td><select name="wpimeemsearch_linklove">
                <option value="1"<?php if (get_option('wpimeemsearch_linklove') == '1') { echo ' selected'; } ?>>Yes</option>
								<option value="0"<?php if (get_option('wpimeemsearch_linklove') == '0') { echo ' selected'; } ?>>No</option>
							</select></td>
            </tr>
          </table>

          <input type="hidden" name="action" value="update" />
          <input type="hidden" name="page_options" value="wpimeemsearch_terms,wpimeemsearch_width,wpimeemsearch_height,wpimeemsearch_limit,wpimeemsearch_linklove" />

          <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
          </p>
        </form>
        <h2>Search Preview</h2>
          <span><?php echo wp_imeemsearch_feed(); ?></span>
    </div>

<?php   
  } //wp_imeem_search_options

  //about wpimeemsearch plugin
  function wp_imeem_search_about() {
?>
    <div class="wrap">
      <h2>WP-imeemSearch About</h2>
        <p>WP-imeemSearch gives you an easy way to display imeem search results based on defined keywords. You are currently able to use the template code: <code>&lt;?php wp_imeemsearch_feed(); ?&gt;</code> anywhere in your template files to display the results.</p>
      <h3>To Do's</h3>
        <ul style="list-style:square inside;padding:0 0 0 15px">
          <li>Add Widget Support</li>
          <li>Identify when there are no results and exclude insert</li>
        </ul>
          
        <p>Current Version: v<?php echo WPTS_CURRENT_VERSION; ?> | Developed by: <a href="http://didyaknow.tv">DidYaKnow.tv</a> | 
          Latest version and docs can be found at <a href="<?php echo WPTS_PLUGIN_URL; ?>">didyaknow.tv</a>.</p>
    </div>
<?php } //wp_imeem_search_credits
  
  // The heart of the plugin.
  // BK Modifications
  function wp_imeemsearch_feed($arg='') {
    //added the $arg variable above
    if(!$arg){
        $search_terms = get_option('wpimeemsearch_terms');
    }else{
        $search_terms = $arg;
    }
    //limit the width of imeems
    $width_imeems = get_option('wpimeemsearch_width');
    //limit the height of imeems
    $height_imeems = get_option('wpimeemsearch_height');
    //limit the number of imeems
    $limit_imeems = get_option('wpimeemsearch_limit');
    //combine search terms using OR operator
    $search_terms = str_replace(' ', '+OR+', $search_terms);
    //searching imeem...
    $imeem_feed = 'http://www.imeem.com/api/xml/playlistsSearch?&query='.$search_terms.'&playlistType=musicPlaylist&numResults='.$limit_imeems;
    //$imeem_feed = 'http://search.imeem.com/search.atom?q=' . $search_terms . '&from=' . $search_author . '&rpp=' . $limit_imeems;
    $imeem_feed = file_get_contents($imeem_feed);
    $imeem_feed = str_replace('&', '&', $imeem_feed);
    $imeem_feed = str_replace('&lt;', '<', $imeem_feed);
    $imeem_feed = str_replace('&gt;', '>', $imeem_feed);
    $clean = explode('<item>', $imeem_feed);
    $amount = count($clean) - 1;
    
    //for ($i = 1; $i <= $amount; $i++) {
      $entry_close           = explode('</item>', $clean[1]);
      //lets get the PlayerURL
      $clean_playerURL_1     = explode('<playlistEmbedUrl>', $entry_close[0]);
      $clean_playerURL       = explode('</playlistEmbedUrl>', $clean_playerURL_1[1]);
      //output the results into a player
      print '
<div style="width:'.$width_imeems.'px; height:'.$height_imeems.'px;">
<object width="'.$width_imeems.'" height="'.$height_imeems.'">
<param name="movie" value="'.$clean_playerURL[0].'aus=false/"></param>
<param name="wmode" value="transparent"></param>
<embed src="'.$clean_playerURL[0].'aus=false/" type="application/x-shockwave-flash" width="'.$width_imeems.'" height="'.$height_imeems.'" wmode="transparent"></embed>
</object>
</div>      
      ';
      //echo '</div>';
    //}
    //share the love man...
    if (get_option('wpimeemsearch_linklove') == '1') {
      echo '<li class="wpts_linklove">Powered by <a href="' . WPTS_PLUGIN_URL .'">WP-imeemSearch</a></li>';
    }
    //echo '</ul>';
  } //wp_imeemsearch_feed
?>