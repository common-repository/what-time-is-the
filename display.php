<?php
/*
Plugin Name: What Time Is The
Plugin URI: http://www.whattimeisthe.com/
Description: Plugin for displaying the dates and times of the events
Version: 1.0
Author: whattimeisthe
Author URI: http://www.whattimeisthe.com/
License: GPLv2
*/
include "lib/lib.php";

/*
 * ADMIN PANEL FUNCTIONS
*/
function admin_actions() {
    add_options_page("WhatTimeIsThe", "WhatTimeIsThe", 1, "WhatTimeIsThe", "admin_panel");
}

function admin_panel() {
    if($_POST['oscimp_hidden'] == 'Y') {
        //Form data sent
        $tags = $_POST['wtit_tags'];
        update_option('wtit_tags', $tags);

        $api_key = $_POST['wtit_api_key'];
        update_option('wtit_api_key', $api_key);

        $api_url = $_POST['wtit_api_url'];
        update_option('wtit_api_url', $api_url);

        $type = $_POST['wtit_type'];
        update_option('wtit_type', $type);

        $num = $_POST['wtit_num'];
        update_option('wtit_num', $num);

        $allow = $_POST['wtit_allow'];
        update_option('wtit_allow', $allow);
        ?>
<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
        <?php
    } else {
        //Normal page display
        $tags = get_option('wtit_tags');
        $api_key = get_option('wtit_api_key');
        $api_url = get_option('wtit_api_url');
        $type = get_option('wtit_type');
        $num = get_option('wtit_num');
        $allow = get_option('wtit_allow');

        if ($tags=="") {
            $tags = "nba-football";
            update_option('wtit_tags', $tags);
        }
        if ($api_key=="") {
            $api_key = "YOUR_API_KEY";
            update_option('wtit_api_key', $api_key);
        }
        if ($api_url=="") {
            $api_url = "http://www.whattimeisthe.com/API/";
            update_option('wtit_api_url', $api_url);
        }
        if ($type=="") {
            $type = "today";
            update_option('wtit_type', $type);
        }
        if ($num=="") {
            $num = "5";
            update_option('wtit_num', $num);
        }
        if ($allow=="") {
            $allow = "yes";
            update_option('wtit_allow', $allow);
        }
    }
    ?>
<div class="wrap">
        <?php echo "<h2>WhatTimeIsThe control panel</h2>"; ?>
    <form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="oscimp_hidden" value="Y">
        <p><?php _e("API url: " ); ?><input type="text" name="wtit_api_url" value="<?php echo $api_url; ?>" size="51"><?php _e(" default: http://www.whattimeisthe.com/API/" ); ?></p>
        <p><?php _e("API key: " ); ?><input type="text" name="wtit_api_key" value="<?php echo $api_key; ?>" size="50"></p>
        <p><a href="http://www.whattimeisthe.com/Home/api/" target="_blank">You can get the api key here.</a></p>
        <p><?php _e("Tags: " ); ?><input type="text" name="wtit_tags" value="<?php echo $tags; ?>" size="53"><?php _e(" tags should be separated with hyphen ie: nba-football" ); ?></p>
        <p><a href="http://www.whattimeisthe.com/Tags/" target="_blank">You can check the tags here.</a></p>
        <p><?php _e("Type: " ); ?><input type="text" name="wtit_type" value="<?php echo $type; ?>" size="53"><?php _e(" this option can be 'today' or 'next' without the quotes. 'today' returns only events in next 24 hours." ); ?></p>
        <p><?php _e("Number of events: " ); ?><input type="text" name="wtit_num" value="<?php echo $num; ?>" size="39"><?php _e(" number of events you want to be displayed. ex: 5" ); ?></p>
        <p><?php _e("External links: " ); ?><input type="text" name="wtit_allow" value="<?php echo $allow; ?>" size="44"><?php _e(" type 'yes' without quotes to allow the external links." ); ?></p>
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Update Options', 'oscimp_trdom' ) ?>" />
        </p>
    </form>
</div>
    <?php
}
add_action('admin_menu', 'admin_actions');

/*
 * CLIENT FUNCTIONS
*/

function widget_content() {
    $list = get_events();

    if ($list!=null) {
        echo "<ul id='wtit'>";
        $i = 0;
        foreach ($list as $item) {
            printf("<li class='wtit_li'><a href='%s'>%s<br><span id='wtitdate%s'>%s</span></a></li>",$item["link"], $item["q_text"], $i, $item["answer"]);
            $i++;
        }
        echo "</ul>";
    } else {
        echo "<p>No games.</p>";
    }
    
}

function widget_creator($args) {
    $allow = get_option('wtit_allow');
    if (strtolower($allow)=="yes") {
        extract($args);
        echo $before_widget;
        echo $before_title;?><a href='http://www.whattimeisthe.com/'>What time is the</a><?php echo $after_title;
        widget_content();
        echo $after_widget;
    } else {
        echo $before_widget;
        echo $before_title;?>What time is the<?php echo $after_title;
        echo "<p>Please allow the external links.</p>";
        echo $after_widget;
    }
}

function wtit_init() {
    register_sidebar_widget(__('What Time Is The'), 'widget_creator');
}
add_action("plugins_loaded", "wtit_init");

wp_enqueue_script( 'dates', '/wp-content/plugins/whattimeisthe/js/date.min.js', array( 'jquery' ));
wp_enqueue_script( 'base', '/wp-content/plugins/whattimeisthe/js/base.js', array( 'jquery' ));
?>
