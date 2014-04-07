<?php

/*

Plugin Name: WordSpinner

Plugin URI: http://www.kythin.com/web/wordspinner-2-7/

Description: Word Spinner is a FREE plugin for Wordpress that can be used to 'spin' the content in your blog. Spinning is an SEO term which means that you write articles in a way so that when they are viewed, some words or sentences are replaces with synonyms to produce a 'randomly' worded, but still gramatically correct article that always means the same thing. <strong><a href="plugins.php?page=wordspinner/wordspinner.php">See Settings for info and examples.</a></strong>

Version: 2.7.3

Author: Kythin

Author URI: http://www.kythin.com/

--------------------------------------------------

 
Created by Kythin
kythin@gmail.com
www.kythin.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.


This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.


*/



add_option('wspin_splitchar', ",");
add_option('wspin_leftchar', "{");
add_option('wspin_rightchar', "}");
add_option('wspin_spintitles', "");
add_option('wspin_spinoncepersession', "");


add_action( 'widgets_init', 'wordspinner_load_widgets' );
add_action( 'init', 'setup_wordspinner_filters' );

function setup_wordspinner_filters() {
	global $wspin_spintitles;
	$wspin_spintitles = get_option('wspin_spintitles');
	
	
	add_filter('the_content','run_spinner');
	add_filter('the_content_feed','run_spinner');
	add_filter('the_excerpt','run_spinner');
	add_filter('the_excerpt_rss','run_spinner');
	
	
	add_filter('wpseo_metadesc', 'cache_run_spinner', 10, 1); //YOAST
	
	
	if ($wspin_spintitles) {
		add_filter('wpseo_title', 'spin_the_titles', 10, 1); //YOAST
		
		add_filter( 'the_title', 'spin_the_titles', 50); 
		add_filter( 'wp_title', 'spin_the_titles', 50); //added priority to run AFTER any seo plugins change the title.
		add_filter( 'the_title_rss', 'spin_the_titles', 50);
	}
}

function spin_the_titles($title) {
	$title = str_replace('Private: ','',$title);
	return cache_run_spinner($title);
}

function cache_run_spinner($str) {
	//check if we have run this exact spinner before, so that it's consistent across each page refresh.
	
	if (!$_REQUEST[md5($str)]) {
		$_REQUEST[md5($str)] = run_spinner($str);
	}
	
	return $_REQUEST[md5($str)];
}





/**
 * Adds an action link to the Plugins page
 */
function wordspinner_plugin_actions($links, $file){
	static $this_plugin;
	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);
	if( $file == $this_plugin ){
		$settings_link = '<a href="plugins.php?page=wordspinner/wordspinner.php">' . __('Settings') . '</a>';
		$links = array_merge( array($settings_link), $links); // before other links
	}
	return $links;
}
add_filter( 'plugin_action_links', 'wordspinner_plugin_actions', 10, 2 );



/* Function that registers our widget. */

function wordspinner_load_widgets() {

	register_widget('TextSpinnerWidget');

}


// [spin code="SPINNER CODE HERE"]
function spin_func( $atts ) {
	extract( shortcode_atts( array(
		'code' => ''
	), $atts ) );
	
	return run_spinner($code);
}
add_shortcode( 'spin', 'spin_func' );


//Debug function to spit out the session data
function spin_session() {
	
	return wspin_print_d($_SESSION, true);
}
add_shortcode( 'show_wspin_session', 'spin_session' );


function wspin_print_d($obj, $suppressOutput=false) {
    $str = "<pre>".htmlentities(print_r($obj, true))."</pre>";
    if (!$suppressOutput) echo $str;
    return $str;
}



// Hook for adding admin menus

add_action('admin_menu', 'wspin_add_pages');

// action function for above hook

function wspin_add_pages() {

    // Add a new submenu under Plugins:

    //add_theme_page("WordSpinner Config", "WordSpinner", 8, __FILE__, "wspin_admin");
	
	//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function) 
	add_submenu_page('plugins.php', "WordSpinner Settings", "WordSpinner", 8, __FILE__, "wspin_admin");
}







function wspin_admin() {
	$disallowed = array('|','||','+');

    // variables for the field and option names 

    $field1 = 'wspin_split';

	$field2 = 'wspin_left';

	$field3 = 'wspin_right';
	
	$field4 = 'wspin_spintitles';
	
	$field5 = 'wspin_spinoncepersession';
	
	$hidden_field_name = 'wspin_hidden';
	



    // See if the user has posted us some information

    // If they did, this hidden field will be set to 'Y'

    if( $_POST[ $hidden_field_name ] == 'Y' ) {

		if ((in_array($_POST[$field1],$disallowed)) || (in_array($_POST[$field2],$disallowed)) || (in_array($_POST[$field3],$disallowed))) {
			// Put an options updated message on the screen
			echo ('<div class="updated"><p><strong>OPTIONS WERE NOT UPDATED!</strong></p>
				  <p>Part of that combination has been found to produce unexpected results. Please dont use any of the following:<br /><br />');
			
			
			foreach ($disallowed as $value) {
				echo $value."&nbsp;&nbsp;";
			}
			
			echo ('</p></div>');


		} else {
			// Save the posted value in the database
	
			update_option( 'wspin_splitchar', $_POST[ $field1 ] );
	
			update_option( 'wspin_leftchar', $_POST[ $field2 ] );
	
			update_option( 'wspin_rightchar', $_POST[ $field3 ] );
	
			update_option( 'wspin_spintitles', $_POST[ $field4 ] );
			
			update_option( 'wspin_spinoncepersession', $_POST[ $field5 ] );
			
			// Put an options updated message on the screen
			echo ('<div class="updated"><p><strong>Options Saved</strong></p></div>');
		}


    }



    // Now display the options editing screen



    echo '<div class="wrap">';



    // header



    echo "<h2>WordSpinner Options</h2>";



    // options form

    

    ?>



<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">



<p> 

Split Character:

<input type="text" name="<?php echo $field1; ?>" value="<?php echo get_option('wspin_splitchar'); ?>" size="2" maxlength="1" /> 

<br />Left Character:

<input type="text" name="<?php echo $field2; ?>" value="<?php echo get_option('wspin_leftchar'); ?>" size="2" maxlength="2" />

<br />Right Character:

<input type="text" name="<?php echo $field3; ?>" value="<?php echo get_option('wspin_rightchar'); ?>" size="2" maxlength="2" />

<br /><br />Spin the Titles:<br />
<small>Please note that this currently only works on titles using the "get_title()" wordpress function. If your theme is using any other method to get the page title, this may not work.<br />
Another thing to remember is that WordSpinner does not save anything to your posts, it only translates what is already saved in the post.</small>

<br /><input type="checkbox" name="<?php echo $field4; ?>" value="YES" <?php if (get_option('wspin_spintitles')) {echo('checked="checked"');} ?> /> Yes, spin the titles please!<br />



<br /><br />Spin only once per session:<br />
<small>This will ensure that when a user or bot enters your site, the spins they see are the same when they refresh the page. It will only re-spin the content when the user's session has been destroyed or expired (depends entirely on your server setup, usually a few days or sometimes whenever they close the browser or do a clear-cache).</small>

<br /><input type="checkbox" name="<?php echo $field5; ?>" value="YES" <?php if (get_option('wspin_spinoncepersession')) {echo('checked="checked"');} ?> /> Yes, spin only once per user session.<br />

</p>



<p class="submit">

	<input type="submit" name="Submit" value="Save" />

</p>

<p>
    <small>
    Though I have tested this plugin extensively and continue to recieve very helpful feedback from it's users, it's imposible for me to test every combination. If you find a character or combination that produces errors, please leave a message at <a href="http://www.kythin.com/web/wordspinner-2-7/">http://www.kythin.com/web/wordspinner-2-7/</a>.<br />
    </small>
</p>




</form>

<p>
<strong>Quick Reference</strong><br /><br />
    
    Your spinner format for articles:<br />
    <?php $l = get_option('wspin_leftchar'); $r = get_option('wspin_rightchar'); $s = get_option('wspin_splitchar'); ?>
    One Level: <strong><?=$l?>word<?=$s?>spinner<?=$s?>example<?=$r?></strong><br />
    <br />
    Multi Level: <strong><?=$l?>word<?=$s?>spinner<?=$s?>example<?=$l?>word<?=$s?>spinner<?=$s?>example<?=$r?><?=$r?></strong><br />
    <br />
    Don't forget you can use it for big or small blocks of text and other code (like Javascript) in the widget, so you can easily spin affiliate ads and banners with the same format!<br />
    <br />
    Shortcode:<br />
    This will not work inside article content (it's already spun) or anywhere that shortcodes don't usually work, but any other plugins that allow shortcodes should work fine.<br />
    <br />
    Inside Wordpress:<br />
    <strong>[spin code="<?=$l?>word<?=$s?>spinner<?=$s?>example<?=$r?>"]</strong><br />
    <br />
    In templates you can also wrap anything in a php function to manually run the spinner, e.g. around Titles or Comments:<br />
    <strong>&lt;?php run_spinner("<?=$l?>word<?=$s?>spinner<?=$s?>example<?=$r?>"); ?&gt;</strong>

</p>

<p><br />
<br />
<br />

<strong>Tip Your Developer?</strong><br />
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="JWM5K2THAMZ7J">
    <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
    </form>
</p>
</div>



<?php

 

} //wspin_admin


$leftchar = get_option('wspin_leftchar');
$rightchar = get_option('wspin_rightchar');	
$splitchar = get_option('wspin_splitchar');


function spinParse($pos, &$data) {
	
	global $leftchar, $rightchar, $splitchar;
	$startPos = $pos;
	//print "Start Position: $startPos | Length: ".strlen($data)."<br>";
	
	while ($pos++ < strlen($data)) {
		//print "Current Position: $pos | Character: ".substr($data, $pos, 1)."<br>";
		
		if (substr($data, $pos, strlen($leftchar)) == $leftchar) {
			$data = spinParse($pos, $data);
		} elseif (substr($data, $pos, strlen($rightchar)) == $rightchar) {
			$entirespinner = substr($data, $startPos+strlen($leftchar), ($pos - $startPos)-strlen($rightchar));
			$processed = spinProcess($entirespinner);
			$data = str_replace($leftchar.$entirespinner.$rightchar,$processed,$data);
		}
		
		//echo ($data."<br>");
	}
	
	
	return $data;
	
}


function spinProcess($input) {
	global $splitchar;
	
	//echo ("Process Request: '$input'");
	$txt = split($splitchar,$input);
	
	$selection = $txt[mt_rand(0,count($txt)-1)];
	//echo (" | Result: '$selection'<br>");
	return $selection;
	
}


function run_spinner($text) {
	if (!session_id()) {
    	@session_start();
	}
	
	$runonce = get_option('wspin_spinoncepersession');
	
	$hash = md5($text);
	
	if (($runonce) && ($_SESSION['wordspinner'][$hash])) {
		
		return $_SESSION['wordspinner'][$hash];
		
	} else {
	
			
		global $leftchar, $rightchar, $splitchar;
		$startTime = time();
	
		$leftchar = get_option('wspin_leftchar');
		$rightchar = get_option('wspin_rightchar');	
		$splitchar = get_option('wspin_splitchar');
		//return the block of modified text
	
		$thearticle = spinParse(-1, $text);
		
		if ($runonce) {
			$_SESSION['wordspinner'][$hash] = $thearticle;	
		}
		
		//echo ("<br><br>Exec time: ".(time()-$startTime)." seconds.");
		return $thearticle;

	}
}





class TextSpinnerWidget extends WP_Widget {



	function TextSpinnerWidget() {

		$widget_ops = array('classname' => 'widget_text_spinner', 'description' => __('Spinnable text(s), with opening and closing chars'));

		$control_ops = array('width' => 400, 'height' => 350);

		$this->WP_Widget('textspinner', __('Spin Text'), $widget_ops, $control_ops);

	}



	function widget( $args, $instance ) {

		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);

		$text = apply_filters( 'widget_text', run_spinner($instance['text']) );

		echo $before_widget;

		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

			<div class="textwidget"><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>

		<?php

		echo $after_widget;

	}



	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		if ( current_user_can('unfiltered_html') )

			$instance['text'] =  $new_instance['text'];

		else

			$instance['text'] = wp_filter_post_kses( $new_instance['text'] );

		$instance['filter'] = isset($new_instance['filter']);

		return $instance;

	}



	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );

		$title = strip_tags($instance['title']);

		$text = format_to_edit($instance['text']);

?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>

		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>



		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>



		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked($instance['filter']); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs.'); ?></label></p>

<?php

	}

	

}
