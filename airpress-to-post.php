<?php
/*
Plugin Name: Airpress to post
Description: Simple plugin, made for the site http://plugin.site. The plugin parse Airpress table row to Wordpress post.
Version: 1.0
*/
add_action( 'wp_enqueue_scripts', 'choco_scripts' );
function choco_scripts() {
    wp_register_style( 'custom-style', plugins_url( '/style.css', __FILE__ ), array(), 'all' );
    wp_enqueue_style( 'custom-style' );
}
// Add custom content type 'Choco' and init meta-box
add_action( 'init', 'choco_cpt' );
function choco_cpt() {
    register_post_type( 'choco', array(
        'labels' => array(
            'name' => 'Choco',
            'singular_name' => 'Choco',
        ),
        'public' => true,
        'menu_position' => 20,
        'capability_type' => 'post',
        'has_archive' => true,
        'supports' => array( 'title', 'thumbnail'),
        'register_meta_box_cb' => 'choco_meta_box',
    ));
}
// add meta-box with field to form
function choco_meta_box(WP_Post $post) {
    add_meta_box('choco_meta', 'Extra fields', function() use ($post) {
        function airpressTitles () {
            $query = new AirpressQuery();
            $query->setConfig("config");
            $query->table("Products");
            $list = new AirpressCollection($query);			
            $titles = [];
            foreach($list as $e){
                $titles[] = ($e['PRODUCTNAME']);
            }
            return $titles;
        }
        ?>
        <script>
            jQuery(document).ready(function($) {
                var availableTags = <?php echo json_encode(airpressTitles (), true); ?>;
                $('body.post-type-choco input#title').autocomplete({
                    source: availableTags,
                    minLength: 2
                });
            });
        </script>
        <?php
        $field_name = 'used_before';
        $field_value = get_post_meta($post->ID, $field_name, true);
        $second_field_name = 'manufacturing_date';
        $second_field_value =  get_post_meta($post->ID, $second_field_name, true);
        $third_field_name = 'net_o_weight';
        $third_field_value =  get_post_meta($post->ID, $third_field_name, true);
        ?>
        <table class="form-table">
            <tr>
                <th> <label for="<?php echo $field_name; ?>">Used before</label></th>
                <td>
                    <input id="<?php echo $field_name; ?>"
                           name="<?php echo $field_name; ?>"
                           type="date"
                           value="<?php echo esc_attr($field_value); ?>"
                    />
                </td>
            </tr>
            <tr>
                <th> <label for="<?php echo $second_field_name; ?>">Manufacturing date</label></th>
                <td>
                    <input id="<?php echo $second_field_name; ?>"
                           name="<?php echo $second_field_name; ?>"
                           type="date"
                           value="<?php echo esc_attr($second_field_value); ?>"
                    />
                </td>
            </tr>
            <tr>
                <th> <label for="<?php echo $third_field_name; ?>">Net o weight</label></th>
                <td>
                    <input id="<?php echo $third_field_name; ?>"
                           name="<?php echo $third_field_name; ?>"
                           type="text"
                           value="<?php echo esc_attr($third_field_value); ?>"
                    />
                </td>
            </tr>
        </table>		
        <?php
    });
}
// Check for empty string allowing for a value of `0`
function empty_str( $str ) {
    return ! isset( $str ) || $str === "";
}
// Save and delete meta but not when restoring a revision
add_action('save_post', function($post_id){
    $post = get_post($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $fields = [
        'first_field_name' => 'used_before',
        'second_field_name' => 'manufacturing_date',
        'third_field_name' => 'net_o_weight'
    ];
    // Do not save meta for a revision or on autosave
    if ( $post->post_type != 'choco' || $is_revision )
        return;
    foreach ($fields as $key => $item) {
        // Do not save meta if fields are not present,
        if( !isset($_POST[$item]))
            return;
        // Clean up data
        $field_value = trim($_POST[$item]);
        // Do the saving and deleting
        if( ! empty_str( $field_value ) ) {
            update_post_meta($post_id, $item, $field_value);
        } elseif( empty_str( $field_value ) ) {
            delete_post_meta($post_id, $item);
        }
    }
});
// add Single template to custom content type 'Choco'
add_filter('single_template', 'my_custom_template');
function my_custom_template($single) {
    global $wp_query, $post;
    /* Checks for single template by post type */
    $path =  plugin_dir_path( __FILE__ );
    if ( $post->post_type == 'choco' ) {
        if ( file_exists( $path . 'single-choco.php' ) ) {
            return $path . 'single-choco.php';
        }
    }
    return $single;
}
