<?php

function faqwd_register_settings() {
    global $faqwd_settings;

    $faqwd_settings = array(
        /* General Settings */
        'general' => array(
            'single_display_share_buttons' => array(
                'id' => 'single_display_share_buttons',
                'name' => __('Display social sharing buttons in the question page', 'faqwd'),
                'desc' => __('Check to display social sharing buttons in the question page', 'faqwd'),
                'type' => 'checkbox'
            ),
            'single_display_date' => array(
                'id' => 'single_display_date',
                'name' => __('Display date in the question page', 'faqwd'),
                'desc' => __('Check to display date in the question page', 'faqwd'),
                'type' => 'checkbox'
            ),
            'single_display_views' => array(
                'id' => 'single_display_views',
                'name' => __('Display the number of views in the question page', 'faqwd'),
                'desc' => __('Check to display the number of views in the question page', 'faqwd'),
                'type' => 'checkbox'
            ),
            'single_display_comments' => array(
                'id' => 'single_display_comments',
                'name' => __('Display the number of comments in the question page', 'faqwd'),
                'desc' => __('Check to display the number of comments in the question page', 'faqwd'),
                'type' => 'checkbox'
            ),
            'single_display_author' => array(
                'id' => 'single_display_author',
                'name' => __('Display author in the question page', 'faqwd'),
                'desc' => __('Check to display the number of comments in the question page', 'faqwd'),
                'type' => 'radio',
                'default' => 1
            ),
            'display_rating' => array(
                'id' => 'display_rating',
                'name' => __('Display the number of vote', 'faqwd'),
                'desc' => __('Check yes to display the number of vote', 'faqwd'),
                'type' => 'multi_radio',
                'labels' => array('Yes', 'No', 'Only for logged in users')
            ),
            'display_more_button' => array(
                'id' => 'display_more_button',
                'name' => __('Display More button', 'faqwd'),
                'desc' => __('Check yes to Display More button', 'faqwd'),
                'type' => 'radio',
                'default' => 1
            ),
            'enable_comments' => array(
                'id' => 'enable_comments',
                'name' => __('Enable Comments', 'faqwd'),
                'desc' => __('Check to enable comments', 'faqwd'),
                'type' => 'checkbox'
            ),
            'answer_scroll' => array(
                'id' => 'answer_scroll',
                'name' => __('Scroll long answer', 'faqwd'),
                'desc' => __("Add scroll if answer is longer then it's container", 'faqwd'),
                'type' => 'text',
                'end_text' => 'px'
            ),
            'meta_for_duplicate_content' => array(
                'id' => 'meta_for_duplicate_content',
                'name' => __('Add noindex/nofollow meta to FAQ posts', 'faqwd'),
                'desc' => __("", 'faqwd'),
                'type' => 'radio',
                'default' => 0
            ),
            'faq_search_autocomplete' => array(
                'id' => 'faq_search_autocomplete',
                'name' => __('Enable search with autocomplete', 'faqwd'),
                'desc' => __("", 'faqwd'),
                'type' => 'radio',
                'default' => 0
            ),
            'custom_css' => array(
                'id' => 'custom_css',
                'name' => __('Custom css', 'faqwd'),
                'desc' => __("", 'faqwd'),
                'type' => 'textarea',
                'cols' => '45',
                'rows' => '15'
            ),
        ),
    );

    foreach ($faqwd_settings as $key => $settings) {

        add_settings_section(
            'faqwd_settings_' . $key, __('General', 'faqwd'), '__return_false', 'faqwd_settings_' . $key
        );

        foreach ($settings as $option) {
            add_settings_field(
                'faqwd_settings_' . $key . '[' . $option['id'] . ']', $option['name'], function_exists('faqwd_' . $option['type'] . '_callback') ? 'faqwd_' . $option['type'] . '_callback' : 'faqwd_missing_callback', 'faqwd_settings_' . $key, 'faqwd_settings_' . $key, faqwd_get_settings_field_args($option, $key)
            );
        }

        /* Register all settings or we will get an error when trying to save */
        register_setting('faqwd_settings_' . $key, 'faqwd_settings_' . $key, 'faqwd_settings_sanitize');
    }
}

add_action('admin_init', 'faqwd_register_settings');


/*
 * Return generic add_settings_field $args parameter array.
 *
 * @param   string  $option   Single settings option key.
 * @param   string  $section  Section of settings apge.
 * @return  array             $args parameter to use with add_settings_field call.
 */

function faqwd_get_settings_field_args($option, $section) {
    $settings_args = array(
        'id' => $option['id'],
        'desc' => $option['desc'],
        'name' => $option['name'],
        'section' => $section,
        'size' => isset($option['size']) ? $option['size'] : null,
        'options' => isset($option['options']) ? $option['options'] : '',
        'std' => isset($option['std']) ? $option['std'] : '',
        'href' => isset($option['href']) ? $option['href'] : '',
        'default' => isset($option['default']) ? $option['default'] : '',
        'labels' => isset($option['labels']) ? $option['labels'] : array(),
        'end_text' => isset($option['end_text']) ? $option['end_text'] : '',
        'cols' => isset($option['cols']) ? $option['cols'] : '',
        'rows' => isset($option['rows']) ? $option['rows'] : '',
    );

    // Link label to input using 'label_for' argument if text, textarea, password, select, or variations of.
    // Just add to existing settings args array if needed.
    if (in_array($option['type'], array('text', 'select', 'textarea', 'password', 'number'))) {
        $settings_args = array_merge($settings_args, array('label_for' => 'faqwd_settings_' . $section . '[' . $option['id'] . ']'));
    }

    return $settings_args;
}

function faqwd_textarea_callback($args) {
    global $faqwd_options;
    if (isset($faqwd_options[$args['id']])) {
        $value = $faqwd_options[$args['id']];
    } else {
        $value = isset($args['default']) ? $args['default'] : '';
    }
    $rows = (isset($args['rows']) && !is_null($args['rows'])) ? 'rows="' . $args['rows'] . '"' : '';
    $cols = (isset($args['cols']) && !is_null($args['cols'])) ? 'cols="' . $args['cols'] . '"' : '';
    $size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : '';
    $html = "\n" . '<textarea type="text" ' . $rows . ' ' . $cols . ' class="' . $size . '" id="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']">' . esc_attr($value) . '</textarea>' . "\n";

    // Render and style description text underneath if it exists.
    if (!empty($args['desc'])) {
        $html .= '<p class="description">' . $args['desc'] . '</p>' . "\n";
    }

    echo $html;
}

function faqwd_checkbox_callback($args) {
    global $faqwd_options;
    $checked = isset($faqwd_options[$args['id']]) ? checked(1, $faqwd_options[$args['id']], false) : checked(1, $args['default'], false);
    $html = "\n" . '<div class="checkbox-div"><input type="checkbox" id="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="1" ' . $checked . '/><label for="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']"></label></div>' . "\n";
    // Render description text directly to the right in a label if it exists.
    if (!empty($args['desc'])) {
        $html .= '<p class="description">' . $args['desc'] . '</p>' . "\n";
    }

    echo $html;
}

/*
 * Function we can use to sanitize the input data and return it when saving options
 *
 * @since 2.0.0
 *
 */

function faqwd_settings_sanitize($input) {
    //add_settings_error( 'gce-notices', '', '', '' );
    return $input;
}

/*
 *  Default callback function if correct one does not exist
 *
 * @since 2.0.0
 *
 */

function faqwd_missing_callback($args) {
    printf(__('The callback function used for the <strong>%s</strong> setting is missing.', 'faqwd'), $args['id']);
}

function faqwd_radio_callback($args) {
    global $faqwd_options;
    $checked_no = isset($faqwd_options[$args['id']]) ? checked(0, $faqwd_options[$args['id']], false) : (isset($args['default']) ? checked(0, $args['default'], false) : '');

    $checked_yes = isset($faqwd_options[$args['id']]) ? checked(1, $faqwd_options[$args['id']], false) : (isset($args['default']) ? checked(1, $args['default'], false) : '');

    $html = "\n" . ' <input type="radio" id="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']_yes" name="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="1" ' . $checked_yes . '/><label for="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']_yes">Yes</label>' . "\n";
    $html .= '<input type="radio" id="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']_no" name="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="0" ' . $checked_no . '/><label for="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']_no">No</label>' . "\n";
    // Render description text directly to the right in a label if it exists.
    if (!empty($args['desc'])) {
        $html .= '<p class="description">' . $args['desc'] . '</p>' . "\n";
    }

    echo $html;
}

/**
 * Textbox callback function
 * Valid built-in size CSS class values:
 * small-text, regular-text, large-text
 *
 */
function faqwd_text_callback($args) {
    global $faqwd_options;

    if (isset($faqwd_options[$args['id']])) {
        $value = $faqwd_options[$args['id']];
    } else {
        $value = isset($args['default']) ? $args['default'] : '';
    }

    $size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : '';
    $html = "\n" . '<input type="text" class="' . $size . '" id="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']" name="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr($value) . '"/>' . $args['end_text'] . "\n";

    // Render and style description text underneath if it exists.
    if (!empty($args['desc'])) {
        $html .= '<p class="description">' . $args['desc'] . '</p>' . "\n";
    }

    echo $html;
}

function faqwd_multi_radio_callback($args) {
    global $faqwd_options;

    $html = "\n";
    $checked_value = (isset($faqwd_options[$args['id']])) ? $faqwd_options[$args['id']] : '0';
    foreach ($args['labels'] as $value => $label) {
        $checked = ($checked_value == $value) ? "checked" : "";
        $html .= '<input type="radio" id="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']_' . $label . '" name="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . $value . '" ' . $checked . '/><label for="faqwd_settings_' . $args['section'] . '[' . $args['id'] . ']_' . $label . '">' . $label . '</label>' . "\n";
    }
    echo $html;
}

function faqwd_get_settings() {

    // Set default settings
    // If this is the first time running we need to set the defaults
    if (!get_option('faqwd_upgrade_has_run')) {
        $general = get_option('faqwd_settings_general');
        if (!isset($general['save_settings'])) {
            $general['save_settings'] = 1;
            $general['single_display_share_buttons'] = 1;
            $general['single_display_comments'] = 1;
            $general['single_display_date'] = 1;
            $general['single_display_views'] = 1;
            $general['enable_comments'] = 1;
            $general['display_more_button'] = 1;
            $general['single_display_author'] = 1;
        }
        update_option('faqwd_upgrade_has_run', $general);
        update_option('faqwd_settings_general', $general);
    }

    $general_settings = is_array(get_option('faqwd_settings_general')) ? get_option('faqwd_settings_general') : array();
    return $general_settings;
}
