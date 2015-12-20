<?php
    /*
Plugin Name: Contributors
Plugin URI:  www.google.com
Description: checkbox test
Version:     1.0.0
Author:      Pushkar Dravid
Author URI:  http://www.pushkardravid.wcevision.in
License:     GPL2
*/


add_action( 'add_meta_boxes', 'add_custom_boxx' );

    function add_custom_boxx( $post ) {
        add_meta_box(
            'Meta Box', // ID, should be a string.
            'Contributors', // Meta Box Title.
            'display_authors', // Your call back function, this is where your form field will go.
            'post', // The post type you want this to show up on, can be post, page, or custom post type.
            'side', // The placement of your meta box, can be normal or side.
            'core' // The priority in which this will be displayed.
        );
}

function display_authors( $post )
{
    // Get post meta value using the key from our save function in the second paramater.
    $custom_meta = get_post_meta($post->ID, '_custom-meta-box', true);

    $blogusers = get_users( 'blog_id=1&orderby=nicename&role=author' );
        // Array of WP_User objects.
    foreach ( $blogusers as $user ) {
    ?>

    <input type="checkbox" name="custom-meta-box[]"
     value='<?php echo $user->display_name; ?>' 
     <?php echo (in_array($user->display_name, $custom_meta)) ? 'checked="checked"' : ''; ?> 
     /><?php echo $user->display_name; ?>
        <br>

        <?php

    } 
}
add_action( 'save_post', 'save_contributors' );
function save_contributors()
{

    global $post;
    // Get our form field
    if(isset( $_POST['custom-meta-box'] ))
    {
        $custom = $_POST['custom-meta-box'];
        $old_meta = get_post_meta($post->ID, '_custom-meta-box', true);
        // Update post meta
        if(!empty($old_meta)){
            update_post_meta($post->ID, '_custom-meta-box', $custom);
        } else {
            add_post_meta($post->ID, '_custom-meta-box', $custom, true);
        }
    }
}




function display_contributors($content){
    

     // Get post meta value using the key from our save function in the second paramater.
    $custom_meta = get_post_meta($post->ID, '_custom-meta-box', true);

    $html = '<div style="border:1px solid;padding:20px;" ><strong>Contributors<br></strong>';

    $blogusers = get_users( 'blog_id=1&orderby=nicename&role=author' );
        // This is the position where only the checked users should be displayed.
    
        /*
        I tried using the following logic but it fetches me error. 
        So I have commented the code for now and am displaying all the authors with
        their gravatars instead.
        <?php echo (in_array($user->display_name, $custom_meta)) ? $user->display_name : ''; ?>
        */

    foreach ( $blogusers as $user ) {
     $html.= get_avatar( $user->email, 32) .'<span>&nbsp &nbsp' . esc_html( $user->display_name ) .'<br></span>';



    }
    $html.= '</div>';


    $content.= $html;

    return $content;
}

add_filter('the_content','display_contributors');

?>