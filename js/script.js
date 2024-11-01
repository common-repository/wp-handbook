/**
 * User: thomfort
 * Email: thomas.fortier@gmail.com
 * Date: 05 oct 2010
 */

jQuery(document).ready(function() {

    // Event preventDefault
    jQuery('a').live('click',function(e){
        if(jQuery(this).attr('href')=="#")
            e.preventDefault();
    });

    // Sortable row
    jQuery('#list_posts').sortable({
        placeholder: 'ui-state-highlight',
        update: function() {
            var serial = jQuery('#list_posts').sortable('serialize');
            jQuery.ajax({
                url: "/wp-content/plugins/wp-handbook/sort_handbook.php",
                type: "post",
                data: serial,
                error: function() {
                    alert("Error ajax");
                }
            });
        }
	});

    // Delete Row
    var noclick=false;
    jQuery('#list_posts li a').live('click', function() {
        var id = jQuery(this).parent().attr('id');
        var keyN = id.replace('listItem_','');
        if(!noclick){
            jQuery.ajax({
                url: '/wp-content/plugins/wp-handbook/deleterow.php',
                type: 'post',
                data: ({keyN : keyN}),
                beforeSend: function(){
                    jQuery('#listItem_'+ keyN +' a').text('...');
                    noclick=true
                },
                success: function(){
                    jQuery('#listItem_'+keyN).remove();
                    noclick=false;
                },
                error: function(){
                    alert("Error ajax for delete row!");
                    noclick=false;
                }
            })
        }

    });

});

