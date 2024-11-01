<?php
    if($_POST['handbook_hidden'] == 'Y') {
        //Form data sent - LINK VIEW
        $addtextvalue = $_POST['handbook_addtextvalue'];
        update_option('handbook_addtextvalue', $addtextvalue);

        $showtop = $_POST['handbook_showtop'];
        update_option('handbook_showtop', $showtop);

        $showbot = $_POST['handbook_showbot'];
        update_option('handbook_showbot', $showbot);


        //Form data sent - FORM VIEW
        $titleform = $_POST['handbook_titleform'];
        update_option('handbook_titleform', $titleform);

        $subtitleform = $_POST['handbook_subtitleform'];
        update_option('handbook_subtitleform', $subtitleform);

        $titlepdflabel = $_POST['handbook_titlepdflabel'];
        update_option('handbook_titlepdflabel', $titlepdflabel);

        $submitlabel = $_POST['handbook_submitlabel'];
        update_option('handbook_submitlabel', $submitlabel);

        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
        <?php
    } else {
        //Normal page display
        $addtextvalue = get_option('handbook_addtextvalue');
        $showtop = get_option('handbook_showtop');
        $showbot = get_option('handbook_showbot');

        $titleform = get_option('handbook_titleform');
        $subtitleform = get_option('handbook_subtitleform');
        $titlepdflabel = get_option('handbook_titlepdflabel');
        $submitlabel = get_option('handbook_submitlabel');
    }
?>

<style type="text/css">
    ul.listForm {list-style:none;}
    ul.listForm li {padding: 3px 0;}
    ul.listForm li label {float:left;width: 150px;}
</style>

<div class="wrap">

    <?php    echo "<h2>" . __( 'HandBook Display Options', 'handbook_dom' ) . "</h2>"; ?>

    <form name="handbook_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="handbook_hidden" value="Y">
    <?php    echo "<h4>" . __( 'HandBook Settings', 'handbook_dom' ) . "</h4>"; ?>

    <h2>Link view</h2>
    <ul class="listForm">
        <li><label for="handbook_addtextvalue"><?php _e("Link add " ); ?></label><input type="text" name="handbook_addtextvalue" id="handbook_addtextvalue" value="<?php echo $addtextvalue; ?>" size="20"><?php _e(" ex: Add this article" ); ?></li>
        <li><label for="handbook_showtop"><?php _e("Show at Top " ); ?></label><input type="checkbox" name="handbook_showtop" id="handbook_showtop" value="true" <?php echo (get_option('handbook_showtop') == 'true' ? 'checked' : ''); ?>/></li>
        <li><label for="handbook_showbot"><?php _e("Show at Bottom " ); ?></label><input type="checkbox" name="handbook_showbot" id="handbook_showbot" value="true" <?php echo (get_option('handbook_showbot') == 'true' ? 'checked' : ''); ?>/></li>
    </ul>

    <h2>Form view</h2>
    <ul class="listForm">
        <li><label for="handbook_titleform"><?php _e("Title form " ); ?></label><input type="text" name="handbook_titleform" id="handbook_titleform" value="<?php echo $titleform; ?>" size="20"><?php _e(" ex: Customize your Handbook" ); ?></li>
        <li><label for="handbook_subtitleform"><?php _e("Subtitle form list " ); ?></label><input type="text" name="handbook_subtitleform" id="handbook_subtitleform" value="<?php echo $subtitleform; ?>" size="20"><?php _e(" ex: List of your articles" ); ?></li>   
        <li><label for="handbook_titlepdflabel"><?php _e("Title PDF label " ); ?></label><input type="text" name="handbook_titlepdflabel" id="handbook_titlepdflabel" value="<?php echo $titlepdflabel; ?>" size="20"><?php _e(" ex: Title in PDF" ); ?></li>
        <li><label for="handbook_submitlabel"><?php _e("Submit label " ); ?></label><input type="text" name="handbook_submitlabel" id="handbook_submitlabel" value="<?php echo $submitlabel; ?>" size="20"><?php _e(" ex: View PDF" ); ?></li>
    </ul>

<!--    <h2>PDF view</h2> -->
<!--    <ul> <li></li> </ul> -->

    <p class="submit">
        <input type="submit" class="button-primary" name="Submit" value="<?php _e('Update Options', 'handbook_dom' ) ?>" />
        &nbsp;
        <?php $handbook_uninstall = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=wp-handbook%2Fwp_handbook.php', 'deactivate-plugin_wp-handbook/wp_handbook.php'); ?>

        <input type="button" name="handbook_uninstall" value="<?php _e('Uninstall', 'handbook') ?>" onClick="if(confirm('<?php _e('All settings will be lost. Are you sure?', 'handbook') ?>')) location.href = '<?php echo $handbook_uninstall; ?>&hb_uninstall=1'; " />
    </p>

    </form>

</div>
 
