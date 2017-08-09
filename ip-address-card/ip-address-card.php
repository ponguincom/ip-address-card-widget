<?php
/*
  Plugin Name: IP Address Card Widget
  Plugin URI: http://ip-address-card.ponguin.com
  Description: Show the visitor's IP address, country, city, region, location in a widget. You can specify the information to be shown.
  Author: Ponguin
  Author URI: http://www.ponguin.com
  Version: 1.0.0
 */

function ip_address_card_check_options($args) {
    $def = array(
        'title' => __('Your Information', 'ip-address-card'),
        'ip_address' => 1,
        'country_name' => '1',
        'country_flag' => '1',
        'city_name' => '1',
        'region_name' => '1',
        'zip_code' => '1',
        'time_zone' => '1',
        'map' => '1',
        'style' => '',
    );
    if (!is_array($args)) {
        $args = array();
    }
    return array_merge($def, $args);
}

function ip_address_card_info($attr) {
    $options = ip_address_card_check_options($attr);
    $optionsJS = array(
        'ip_address' => '',
        'country_name' => ($options["country_name"])?__('Country', 'ip-address-card'):false,
        'country_flag' => ($options["country_flag"])?__('Flag', 'ip-address-card'):false,
        'city_name' => ($options["city_name"])?__('City', 'ip-address-card'):false,
        'region_name' => ($options["region_name"])?__('Region', 'ip-address-card'):false,
        'zip_code' => ($options["zip_code"])?__('Zip Code', 'ip-address-card'):false,
        'time_zone' => ($options["time_zone"])?__('Time Zone', 'ip-address-card'):false,
        'map' => ($options["map"])?__('Map', 'ip-address-card'):false,
        'style' => ($options["style"])?$options["style"]:false
    );
    
    wp_register_script('ip-address-card-js', plugins_url('ip-address-card.js', __FILE__), array('jquery'), null, true);   
    wp_enqueue_script('ip-address-card-js');
    
    wp_register_style('ip-address-card-css', plugins_url('ip-address-card.css', __FILE__));
    wp_enqueue_style('ip-address-card-css');
    
    $optionsJson = json_encode($optionsJS);
    echo <<<EOT
        <script>
        jQuery(function(){
            jQuery('div#ip-address-card-w').ipAddressCard({$optionsJson});
        });
        </script>
EOT;
    

    $out = '<div id="ip-address-card-w"></div>';
    return $out;
}

function card_ip_address($args) {
    extract($args);
    $options = ip_address_card_check_options(get_option("ip_address_card"));
    echo $before_widget;
    echo $before_title;
    echo $options['title'];
    echo $after_title;
    echo ip_address_card_info($options);
    echo $after_widget;
}

function card_ip_address_control() {
    $options = ip_address_card_check_options(get_option("ip_address_card"));

    if ($_POST['ip_address_card-submit']) {
        $options['title'] = htmlspecialchars($_POST['ip_address_card-title']);
        $options['ip_address'] = (isset($_POST['ip_address_card-ip_address'])) ? "1" : "0";
        $options['country_name'] = (isset($_POST['ip_address_card-country_name'])) ? "1" : "0";
        $options['country_flag'] = (isset($_POST['ip_address_card-country_flag'])) ? "1" : "0";
        $options['city_name'] = (isset($_POST['ip_address_card-city_name'])) ? "1" : "0";
        $options['region_name'] = (isset($_POST['ip_address_card-region_name'])) ? "1" : "0";
        $options['zip_code'] = (isset($_POST['ip_address_card-zip_code'])) ? "1" : "0";
        $options['time_zone'] = (isset($_POST['ip_address_card-time_zone'])) ? "1" : "0";
        $options['map'] = (isset($_POST['ip_address_card-map'])) ? "1" : "0";
        $options['style'] = htmlspecialchars($_POST['ip_address_card-style']);
        update_option("ip_address_card", $options);
    }
    ?>
    <p style="text-align: left;"><?php echo __('Widget Title', 'ip-address-card'); ?>:
    <input type="text" id="widgettitle" name="ip_address_card-title" value="<?php echo $options['title']; ?>" /></p>         
    <input type="checkbox" <?php if ($options['ip_address'] == 1) echo "checked=\"checked\""; ?>" value="1" id="ip_address_card-ip_address" name="ip_address_card-ip_address"/> <?php echo __('Ip Address', 'ip-address-card') ?><br>
    <input type="checkbox" <?php if ($options['country_name'] == 1) echo "checked=\"checked\""; ?>" value="1" id="ip_address_card-country_name" name="ip_address_card-country_name"/> <?php echo __('Country', 'ip-address-card') ?><br>         
    <input type="checkbox" <?php if ($options['country_flag'] == 1) echo "checked=\"checked\""; ?>" value="1" id="ip_address_card-country_flag" name="ip_address_card-country_flag"/> <?php echo __('Flag', 'ip-address-card') ?><br>
    <input type="checkbox" <?php if ($options['city_name'] == 1) echo "checked=\"checked\""; ?>" value="1" id="ip_address_card-city_name" name="ip_address_card-city_name"/> <?php echo __('City', 'ip-address-card') ?><br>
    <input type="checkbox" <?php if ($options['region_name'] == 1) echo "checked=\"checked\""; ?>" value="1" id="ip_address_card-region_name" name="ip_address_card-region_name"/> <?php echo __('Region', 'ip-address-card') ?><br>
    <input type="checkbox" <?php if ($options['zip_code'] == 1) echo "checked=\"checked\""; ?>" value="1" id="ip_address_card-zip_code" name="ip_address_card-zip_code"/> <?php echo __('Zip Code', 'ip-address-card') ?><br>	
    <input type="checkbox" <?php if ($options['time_zone'] == 1) echo "checked=\"checked\""; ?>" value="1" id="ip_address_card-time_zone" name="ip_address_card-time_zone"/> <?php echo __('Time Zone', 'ip-address-card') ?><br>	
    <input type="checkbox" <?php if ($options['map'] == 1) echo "checked=\"checked\""; ?>" value="1" id="ip_address_card-map" name="ip_address_card-map"/> <?php echo __('Map', 'ip-address-card') ?><br>	
    Style : <select name="ip_address_card-style">
        <option value="">Default</option>
        <option value="ip-address-card-style-1" <?php if ($options['style'] == "ip-address-card-style-1") echo "selected"; ?>>Style 1</option>
        <option value="ip-address-card-style-2" <?php if ($options['style'] == "ip-address-card-style-2") echo "selected"; ?>>Style 2</option>
        <option value="ip-address-card-style-3" <?php if ($options['style'] == "ip-address-card-style-3") echo "selected"; ?>>Style 3</option>
        <option value="ip-address-card-style-4" <?php if ($options['style'] == "ip-address-card-style-4") echo "selected"; ?>>Style 4</option>
        <option value="ip-address-card-style-5" <?php if ($options['style'] == "ip-address-card-style-5") echo "selected"; ?>>Style 5</option>
        <option value="ip-address-card-style-6" <?php if ($options['style'] == "ip-address-card-style-6") echo "selected"; ?>>Style 6</option>
        <option value="ip-address-card-style-7" <?php if ($options['style'] == "ip-address-card-style-7") echo "selected"; ?>>Style 7</option>
        <option value="ip-address-card-style-8" <?php if ($options['style'] == "ip-address-card-style-8") echo "selected"; ?>>Style 8</option>
        <option value="ip-address-card-style-9" <?php if ($options['style'] == "ip-address-card-style-9") echo "selected"; ?>>Style 9</option>
    </select>
    <input type="hidden" id="ip_address_card-submit" name="ip_address_card-submit" value="1" />
    <?php
}

function ip_address_card_init() {
    load_plugin_textdomain('ip-address-card', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
    register_sidebar_widget('IP Address Card', 'card_ip_address');
    register_widget_control('IP Address Card', 'card_ip_address_control', 250, 100);
}

function ip_address_card_display($args = array()) {
    echo ip_address_card_info($args);
}

add_action('init', 'ip_address_card_init');
?>