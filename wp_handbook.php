<?php
session_start();
/*
    Plugin Name: wp-handbook
    Plugin URI: http://wordpress.org/extend/plugins/wp-handbook/
    Description: You can add posts in your handbook (favorites list) in order to print them or create a PDF.
    Version: 0.9.6
    Author: Thomas Fortier
    Author URI: http://www.petitesmerveillesduweb.com/wordpress-plugin-handbook-avoir-une-liste-des-articles-preferes/
    License: GPL2
*/

/*  Copyright 2010  Thomas Fortier  (email : thomas.fortier@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

register_activation_hook(__FILE__,'handbook_init');
if(isset($_REQUEST["hb_uninstall"])) register_deactivation_hook(__FILE__, 'handbook_uninstall');

function handbook_init()
{
    add_option('handbook_addtextvalue', 'Add this article', '', 'no');
    add_option('handbook_showtop', 'true', '', 'no');
    add_option('handbook_showbot', '', '', 'no');

    add_option('handbook_titleform', 'Customize your Handbook', '', 'no');
    add_option('handbook_subtitleform', 'List of your articles', '', 'no');
    add_option('handbook_titlepdflabel', 'My Handbook', '', 'no');
    add_option('handbook_submitlabel', 'View PDF', '', 'no');

}

function handbook_uninstall()
{
    delete_option('handbook_addtextvalue');
    delete_option('handbook_showtop');
    delete_option('handbook_showbot');

    delete_option('handbook_titleform');
    delete_option('handbook_subtitleform');
    delete_option('handbook_titlepdflabel');
    delete_option('handbook_submitlabel');
}

function is_already_there($post_id) {
    if($_SESSION['handbook']) {
        if (in_array($post_id, $_SESSION['handbook'])) {
            return true;
        } else {
            return false;
        }
    }
}

function add_sel() {
    //wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', WP_PLUGIN_URL . '/wp-handbook/js/jquery-1.3.2.min.js', '', '1.3.2' );
    wp_enqueue_script('jquery');

    wp_register_script( 'jquery-ui-ui', WP_PLUGIN_URL . '/wp-handbook/js/jquery-ui-1.7.3.custom.min.js', '', '1.7.3');
    wp_enqueue_script('jquery-ui-ui');

    wp_register_script( 'script', WP_PLUGIN_URL . '/wp-handbook/js/script.js', '', '1.0' );
    wp_enqueue_script('script');
}
add_action('init','add_sel');

function handbookPlugin_admin() {
    include('handbookPlugin_admin.php');
}

function handbookPlugin_AdminMenu(){
    add_options_page("Handbook", "Handbook", 1, "Handbook", "handbookPlugin_admin");
}
add_action('admin_menu', 'handbookPlugin_AdminMenu');

$booklet = 0;
function myplugin_js_header() // this is a PHP function
{
    global $booklet;
  // Define custom JavaScript function
?>
<script type="text/javascript">
//<![CDATA[
function myplugin_cast_vote( post_id, results_div )
{
    jQuery.ajax({
		type: "post",url: "<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php",data: { action: 'add_post_in_booklet', postid: post_id },
		beforeSend: function() {jQuery('#loading_'+post_id).html('<img src="http://www.frankfurt-tourismus.de/cms/export/sites/tcf-backoffice/res/ibe/img/i_ajax_loader.gif"/>');
                                jQuery("#loading_"+post_id).show();},
		complete: function() { jQuery("#loading_"+post_id).hide();},
		success: function(vall){

            if(vall == '1') {
                var numberB = parseInt(jQuery(".numberBooklet").text())+1;
                jQuery(".numberBooklet").text(numberB);
                
                jQuery(".handbook_link_add_"+post_id).html('<span style="color:green">Article added!</span>');
            } else {
                jQuery(".handbook_link_add_"+post_id).html('<span style="color:red">This article is already inserted!</span>');
            }
		}
	});
}
//]]>
</script>
<?php
} // end of PHP function myplugin_js_header
add_action('wp_head', 'myplugin_js_header' );
// Action AJAX
add_action('wp_ajax_add_post_in_booklet', 'add_post_in_booklet');
add_action('wp_ajax_nopriv_add_post_in_booklet', 'add_post_in_booklet');

if(isset($_GET['flush'])){
    session_unset();
}

function add_post_in_booklet() {
    if(count($_SESSION['handbook']) > 0) {
        if (!in_array($_POST['postid'], $_SESSION['handbook'])) {
            $_SESSION['handbook'][] = $_POST['postid'];
            echo '1'; // Pas présent
        } else
            echo '0'; // Présent
    }
    else {
        echo '1'; // Pas présent
        $_SESSION['handbook'][] = $_POST['postid'];
    }
    die();
}

// Link view to add booklet
function link_add_booklet($content) {
    global $wp_query;
    $showTop = get_option('handbook_showtop');
    $showBot = get_option('handbook_showbot');

    $post_id = $wp_query->post->ID;
    $retval = '';
    $handbook_addtextvalue = get_option('handbook_addtextvalue');

    $retval .= '<div class="handbook_link_add_'.$post_id.'">';
    $retval .= '<a href="#" onclick="myplugin_cast_vote(' . $post_id . ', \'hbresult\')">' . $handbook_addtextvalue . '</a> ';
    $retval .= '</div>
                <div id="loading_'.$post_id.'" style="display:none">...</div>
                <div id="hbresult_'.$post_id.'" style="display:none">
                
                </div>
                <div id="result">
                </div>
                ';
    if($showTop == 'true')
        $content = $retval.$content;

    if($showBot == 'true')
        $content = $content.$retval;


    return $content;
}
add_filter('the_content', 'link_add_booklet');

// Link to view list of booklet
function show_numb_booklet() {
    $retval = '';
    $numberBooklet = count($_SESSION['handbook']);
    //$textAdd = get_option('handbook_linkviewbooklet');
    $textAdd = get_option('widget_handbook-widget');
    foreach($textAdd as $value){
        if(count($value['title']) > 0)
            $textAdd = $value['title'];
    }
    $textAdd = str_replace("{x}", ($numberBooklet>0)?'<span class="numberBooklet">'.$numberBooklet.'</span>':'<span class="numberBooklet">0</span>', $textAdd);
    $retval .= '<h3><a href="'. WP_PLUGIN_URL .'/wp-handbook/handbook_list.php" onclick="">';
    $retval .= $textAdd; 
    $retval .= '</a></h3>';

    return $retval;
}

### Class: WP-handbook Widget
 class WP_Widget_Handbook extends WP_Widget {
	// Constructor
	function WP_Widget_Handbook() {
		$widget_ops = array('description' => __('WP-Handbook', 'wp-handbook'));
		$this->WP_Widget('handbook-widget', __('Handbook', 'wp-handbook'));
	}

	// Display Widget
	function widget($args, $instance) {
		extract($args);
        echo show_numb_booklet();
	}

	// When Widget Control Form Is Posted
	function update($new_instance, $old_instance) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
                                                           
	// DIsplay Widget Control Form
	function form($instance) {
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('title' => __('You have {x} booklet', 'wp-handbook'), 'handbook_id' => 0));
		$title = $instance['title'];
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-handbook'); ?> <input class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
		    <br/>
            <span style="font-size:11px">
                (ex: You have {x} booklet)
            </span>
        </p>

		<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php
	}
}

### Function: Init WP-Polls Widget
add_action('widgets_init', 'widget_handbook_init');
function widget_handbook_init() {
	register_widget('WP_Widget_Handbook');

}
