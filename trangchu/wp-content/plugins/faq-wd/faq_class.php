<?php

class faq_class {

    protected static $instance = null;
    public $cpt;
    public $shortcode_tag = 'faq_wd';
    public $post_type = 'faq_wd';
    public $version = '1.0.23';

    private function __construct() {
        $this->includes();
        require_once 'lang/SLangClass.php';
        FAQWDLangClass::get_instance('faq_wd', 'faq-wd');
        add_action('init', array($this, 'add_localization'));
        add_action('wp_enqueue_scripts', array($this, 'register_front_end_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_front_end_scripts'));
        add_action('wp_ajax_faq_wd_vote', array($this, 'faq_wd_vote'));
        add_action('wp_ajax_nopriv_faq_wd_vote', array($this, 'faq_wd_vote'));
        add_filter('the_content', array($this, 'faqwd_custom_template'));
        add_action('wp_head', array($this, 'add_meta_in_header'));
    }

    public function add_meta_in_header() {
        global $faqwd_options;
        global $post;
        if (isset($faqwd_options['meta_for_duplicate_content']) && $faqwd_options['meta_for_duplicate_content'] == '1') {
            if ($post && isset($post->post_type) && $post->post_type == 'faq_wd') {
                echo '<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">';
            }
        }
    }

    public function add_localization() {
        $contLDomain = "faqwd";
        $locale = get_locale();
        $t = explode('/', plugin_basename(__FILE__));
        $path = WP_CONTENT_DIR . '/uploads/Languages_WD/' . $t[0] . '/faq-wd-' . $locale . '.mo';
        $loaded = load_textdomain($contLDomain, $path);
        if (isset($_GET['page']) && $_GET['page'] == basename(__FILE__) && !$loaded) {
            echo '<div class="error"> Staff Directory WD ' . __('Could not load the localization file: ' . $path, $contLDomain) . '</div>';
            return;
        }
    }

    public function includes() {
        global $faqwd_options;
        include_once('includes/register_settings.php');
        $faqwd_options = faqwd_get_settings();
        include_once('includes/faq_cpt_class.php');
        $this->cpt = faq_cpt::get_instance();
        include_once('includes/shortcode.php');
    }

    function register_front_end_styles() {
        global $faqwd_options;
        wp_register_style('front_end_style', FAQ_URL . 'css/front_end_style.css', array(), $this->version);
        wp_enqueue_style('front_end_style');
        wp_register_style('front_end_default_style', FAQ_URL . 'css/default.css', array(), $this->version);
        wp_enqueue_style('front_end_default_style');

        $custom_css = (isset($faqwd_options['custom_css'])) ? $faqwd_options['custom_css'] : "";
        if (isset($faqwd_options['answer_scroll']) && $faqwd_options['answer_scroll'] != "" && intval($faqwd_options['answer_scroll'] != 0)) {
            $height = $faqwd_options['answer_scroll'];
            $custom_css .= ".faqwd_answer_container{overflow-y: scroll;max-height:" . $height . "px;height:" . $height . "px}";
        }
        wp_add_inline_style('front_end_style', $custom_css);
    }

    function register_front_end_scripts() {
        global $faqwd_options;
        wp_register_script('vote_button', FAQ_URL . 'js/vote.js', array('jquery',
            'jquery-ui-widget'), $this->version, true);
        wp_enqueue_script('vote_button');
        wp_register_script('front_js', FAQ_URL . 'js/faq_wd_front_end.js', array('jquery',
            'jquery-ui-widget'), $this->version, true);
        wp_enqueue_script('front_js');

        wp_localize_script('vote_button', 'faqwd', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ajaxnonce' => wp_create_nonce('faqwd_ajax_nonce'),
            'loadingText' => __('Loading...', 'faqwd'),
            'options' =>  $faqwd_options
        ));
    }

    function faq_wd_vote() {
        check_ajax_referer('faqwd_ajax_nonce', 'security');
        $post_id = intval($_POST['post_id']);
        $post = get_post($post_id);
        if ($post == null || ($post != null && $post->post_type != 'faq_wd')) {
            die;
        }
        $type = sanitize_text_field($_POST['type']);
        if (!($type == 'hits' || $type == 'useful' || $type == 'non_useful')) {
            die;
        }
        if ($type == 'hits') {
            $hits = 1;
            $faqwd_hits = get_post_meta($post_id, 'faqwd_hits', true);
            if ($faqwd_hits != null) {
                $hits = (int) $faqwd_hits + 1;
            }

            update_post_meta($post_id, 'faqwd_hits', $hits);
            echo json_encode(array("hits" => $hits));
            die;
        } else {

            $count = array();
            $current_ip = $_SERVER['REMOTE_ADDR'];
            $exists_ips = get_option('faqwd_voted_ips');
            $exists_ips = json_decode($exists_ips, true);
            if (isset($exists_ips[$post_id]) && is_array($exists_ips[$post_id])) {
                if (!in_array($current_ip, $exists_ips[$post_id])) {
                    $exists_ips[$post_id][] = $current_ip;
                    $count = $this->useful_non_useful($type, $post_id);
                    update_option('faqwd_voted_ips', json_encode($exists_ips));
                } else {
                    $count_useful = get_post_meta($post_id, 'faqwd_useful', true);
                    (isset($count_useful) && $count_useful != '') ? $count['useful'] = $count_useful : $count['useful'] = 0;
                    $count_non_useful = get_post_meta($post_id, 'faqwd_non_useful', true);
                    (isset($count_non_useful) && $count_non_useful != '') ? $count['non_useful'] = $count_non_useful : $count['non_useful'] = 0;
                }
            } else {
                $exists_ips[$post_id] = array($current_ip);
                update_option('faqwd_voted_ips', json_encode($exists_ips));
                $count = $this->useful_non_useful($type, $post_id);
            }
            echo json_encode(array('useful' => $count['useful'], 'non_useful' => $count['non_useful']));
            die;
        }
    }

    function useful_non_useful($type, $id) {
        $useful_arr = get_post_meta($id, 'faqwd_useful');
        $non_useful_arr = get_post_meta($id, 'faqwd_non_useful');
        ( isset($useful_arr[0]) ) ? $useful = $useful_arr[0] : $useful = 0;
        ( isset($non_useful_arr[0]) ) ? $non_useful = $non_useful_arr[0] : $non_useful = 0;


        if ($type == 'useful') {
            $useful = (int) $useful + 1;
            update_post_meta($id, 'faqwd_useful', $useful);
        } else {
            $non_useful = (int) $non_useful + 1;
            update_post_meta($id, 'faqwd_non_useful', $non_useful);
        }
        $count = array('useful' => $useful, 'non_useful' => $non_useful);
        return $count;
    }

    public function faqwd_custom_template($content) {
        global $post;
        if (is_single()) {
            if ($post->post_type == 'faq_wd') {
                ob_start();
                include(FAQ_DIR . '/views/faq_wd_content.php');
                $faq_content = ob_get_clean();
                $content = $faq_content;
            }
        }
        return $content;
    }

    /**
     * Return an instance of this class.
     */
    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

}

?>