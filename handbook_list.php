<?php
require_once($_SERVER['DOCUMENT_ROOT']."/wp-config.php");
session_start();

get_header();

$listPostID = $_SESSION['handbook'];
$listtitles = array();
if(isset($listPostID)) {
    global $wpdb;
    $listArray = array();
    $listPostIDString = implode(',',$listPostID);
    $listPostIDString = '('.$listPostIDString.')';

    // Need best way to sort my SESSION...
    $listtitles = $wpdb->get_results("SELECT ID, post_title FROM wp_posts WHERE ID IN ".$listPostIDString);
}
?>
<style type="text/css">
    .ui-state-highlight { height: 1.5em; line-height: 1.2em; }
    #form_generator ul {list-style:none;}
    #form_generator ul#list_posts {list-style-type:circle;}

    #form_generator ul li label {float:left;width:150px;}
    #form_generator ul li {padding: 5px 0}
</style>

<?php

echo '<div id="container">';
echo '<div id="content" role="main">';
echo '<h1>' . get_option('handbook_titleform') . '</h1>';

if(count($listtitles) > 0) {
    echo '<form id="form_generator" name="form_generator" action="'. get_bloginfo("wpurl") .'/wp-content/plugins/wp-handbook/generatepdf.php" method="POST" enctype="multipart/form-data">';

   // echo '<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />';

    echo '<ul>';

    echo '<li><label for="title_hb">' . get_option('handbook_titlepdflabel') . ' </label><input id="title_hb" type="text" name="title_handbook" size="25"/></li>';

    //echo '<li><label for="file_hb">' . get_option('handbook_uploadpictlabel') . ' </label><input id="file_hb" type="file" name="uploadfile" /></li>';

    echo '</ul>';

    $newlist = array();
    foreach($listPostID as $key=>$value) {
        foreach($listtitles as $value2) {
            if($value == $value2->ID) {
                $newlist[$value] = $value2->post_title;
            }
        }
    }

    echo '<h3>' . get_option('handbook_subtitleform') . '</h3>';
    echo '<ul id="list_posts">';
    foreach($newlist as $key=>$value) {
        echo '<li id=listItem_'.$key.'>' . $value . ' <a href="#" class="hb_deleteRow">x</a></li>';
    }
    echo '</ul>';
    //echo '<div id="info" style="background-color:#DEDEDE"><input type="text" id="infoText" /></div>';
    echo '<input type="submit" value="' . get_option('handbook_submitlabel') . '" name="submit_form" />';
    echo '</form>';
} else {
    echo "No posts found";
}

echo '</div>';
echo '</div>';

get_footer();
?>