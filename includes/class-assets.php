<?php

/**
 * The scripts class
 *
 * @since 3.0.0
 */
class Dokan_Assets {

    /**
     * The constructor
     */
    function __construct() {

        add_action( 'init', array( $this, 'register_all_scripts' ), 10 );
        add_filter( 'dokan_localized_args', array( $this, 'conditional_localized_args' ) );

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        } else {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ) );
        }
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts( $hook ) {
        global $post;

        wp_enqueue_style( 'dokan-admin-css' );
        wp_enqueue_script( 'dokan-tooltip' );
        wp_enqueue_script( 'dokan-admin' );

        if ( get_post_type( $post ) == 'dokan_slider' ) {
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_style( 'thickbox' );
        }

        if ( 'plugins.php' == $hook ) {
            wp_enqueue_style( 'dokan-plugin-list-css' );
        }

        do_action( 'dokan_enqueue_admin_scripts' );
    }

    /**
     * Register all Dokan scripts and styles
     */
    public function register_all_scripts() {
        $styles = $this->get_styles();
        $scripts = $this->get_scripts();

        $this->register_styles( $styles );
        $this->register_scripts( $scripts );

        do_action( 'dokan_register_scripts' );
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = array(
            'dokan-style' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/css/style.css',
                'version' => filemtime( DOKAN_DIR . '/assets/css/style.css' ),
            ),
            'jquery-ui' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/vendors/jquery-ui/jquery-ui-1.10.0.custom.css',
            ),
            'dokan-fontawesome' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/vendors/font-awesome/font-awesome.min.css',
            ),
            'dokan-chosen-style' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/vendors/chosen/chosen.min.css',
            ),
            'dokan-magnific-popup' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/vendors/magnific/magnific-popup.css',
            ),
            'dokan-select2-css' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/vendors/select2/select2.css',
            ),
            'dokan-rtl-style' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/css/rtl.css',
            ),
            'dokan-plugin-list-css' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/css/plugin.css',
            ),
            'dokan-admin-css' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/css/admin.css',
                'version' => filemtime( DOKAN_DIR . '/assets/css/admin.css' ),
            ),
            'dokan-vue-vendor' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/css/vendor.css',
                'version' => filemtime( DOKAN_DIR . '/assets/css/vendor.css' ),
            ),
            'dokan-vue-admin' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/css/vue-admin.css',
                'version' => filemtime( DOKAN_DIR . '/assets/css/vue-admin.css' ),
            ),
            'dokan-vue-frontend' => array(
                'src'     => DOKAN_PLUGIN_ASSEST . '/css/vue-frontend.css',
                'version' => filemtime( DOKAN_DIR . '/assets/css/vue-frontend.css' ),
            ),
        );

        return $styles;
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $prefix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';
        $asset_url  = DOKAN_PLUGIN_ASSEST;
        $asset_path = DOKAN_DIR . '/assets/';

        $scripts = array(
            'dokan-chart' => array(
                'src'       => $asset_url . '/vendors/chart/Chart.min.js',
            ),
            'dokan-tabs' => array(
                'src'       => $asset_url . '/vendors/easytab/jquery.easytabs.min.js',
                'deps'      => array( 'jquery' ),
            ),
            'dokan-chosen' => array(
                'src'       => $asset_url . '/vendors/chosen/chosen.jquery.min.js',
                'deps'      => array( 'jquery' ),
            ),
            'dokan-popup' => array(
                'src'       => $asset_url . '/vendors/magnific/jquery.magnific-popup.min.js',
                'deps'      => array( 'jquery' ),
            ),
            'dokan-tooltip' => array(
                'src'       => $asset_url . '/vendors/tooltips/tooltips.js',
                'deps'      => array( 'jquery' ),
            ),
            'dokan-form-validate' => array(
                'src'       => $asset_url . '/vendors/form-validate/form-validate.js',
                'deps'      => array( 'jquery' ),
            ),
            'dokan-select2-js' => array(
                'src'       => $asset_url . '/vendors/select2/select2.full.min.js',
                'deps'      => array( 'jquery' ),
            ),

            // customize scripts
            'customize-base' => array(
                'src'       => site_url( 'wp-includes/js/customize-base.js' ),
                'deps'      => array( 'jquery', 'json2', 'underscore' )
            ),
            'customize-model' => array(
                'src'       => site_url( 'wp-includes/js/customize-models.js' ),
                'deps'      => array( 'underscore', 'backbone' )
            ),

            // Register core scripts
            'dokan-flot' => array(
                'src'       => $asset_url . '/js/flot-all.min.js',
                'deps'      => array( 'jquery' ),
            ),
            'speaking-url' => array(
                'src'       => $asset_url . '/js/speakingurl.min.js',
                'deps'      => array( 'jquery' ),
            ),
            'dokan-admin' => array(
                'src'       => $asset_url . '/js/dokan-admin.js',
                'deps'      => array( 'jquery' ),
                'version'   => filemtime( $asset_path . '/js/dokan-admin.js' ),
            ),
            'dokan-script' => array(
                'src'       => $asset_url . '/js/dokan.js',
                'deps'      => array( 'imgareaselect', 'customize-base', 'customize-model' ),
                'version'   => filemtime( $asset_path . '/js/dokan.js' ),
            ),
            'vue-vendor' => array(
                'src'       => $asset_url . '/js/vue-vendor.js',
                'version'   => filemtime( $asset_path . '/js/vue-vendor.js' ),
            ),
            'vue-admin' => array(
                'src'       => $asset_url . '/js/vue-admin.js',
                'deps'      => array( 'jquery' ),
                'version'   => filemtime( $asset_path . '/js/vue-admin.js' ),
            ),
            'vue-frontend' => array(
                'src'       => $asset_url . '/js/vue-frontend.js',
                'deps'      => array( 'jquery' ),
                'version'   => filemtime( $asset_path . '/js/vue-frontend.js' ),
            ),
        );

        return $scripts;
    }

    /**
     * Enqueue front-end scripts
     */
    public function enqueue_front_scripts() {

        if ( !function_exists( 'WC' ) ) {
            return;
        }

        // load dokan style on every pages. requires for shortcodes in other pages
        if ( DOKAN_LOAD_STYLE ) {
            wp_enqueue_style( 'dokan-style' );
            wp_enqueue_style( 'dokan-fontawesome' );

            if ( is_rtl() ) {
                wp_enqueue_style( 'dokan-rtl-style' );
            }
        }

        $default_script = array(
            'ajaxurl'            => admin_url( 'admin-ajax.php' ),
            'nonce'              => wp_create_nonce( 'dokan_reviews' ),
            'ajax_loader'        => DOKAN_PLUGIN_ASSEST.'/images/ajax-loader.gif',
            'seller'             => array(
                'available'     => __( 'Available', 'dokan-lite' ),
                'notAvailable'  => __( 'Not Available', 'dokan-lite' )
            ),
            'delete_confirm'     => __( 'Are you sure?', 'dokan-lite' ),
            'wrong_message'      => __( 'Something went wrong. Please try again.', 'dokan-lite' ),
            'vendor_percentage'  => dokan_get_seller_percentage( dokan_get_current_user_id() ),
            'commission_type'    => dokan_get_commission_type( dokan_get_current_user_id() ),
            'rounding_precision' => wc_get_rounding_precision(),
            'mon_decimal_point'  => wc_get_price_decimal_separator(),
        );

        $localize_script = apply_filters( 'dokan_localized_args', $default_script );

        wp_localize_script( 'jquery', 'dokan', $localize_script );

        // load only in dokan dashboard and product edit page
        if ( ( dokan_is_seller_dashboard() || ( get_query_var( 'edit' ) && is_singular( 'product' ) ) ) || apply_filters( 'dokan_forced_load_scripts', false ) ) {
            $this->dokan_dashboard_scripts();
        }

        // store and my account page
        if ( dokan_is_store_page() || dokan_is_store_review_page() || is_account_page() || is_product() ) {

            if ( DOKAN_LOAD_STYLE ) {
                wp_enqueue_style( 'dokan-select2-css' );
            }

            if ( DOKAN_LOAD_SCRIPTS ) {

                $this->load_form_validate_script();
                $this->load_gmap_script();

                wp_enqueue_script( 'jquery-ui-sortable' );
                wp_enqueue_script( 'jquery-ui-datepicker' );
                wp_enqueue_script( 'dokan-tooltip' );
                wp_enqueue_script( 'dokan-chosen' );
                wp_enqueue_script( 'dokan-form-validate' );
                wp_enqueue_script( 'speaking-url' );
                wp_enqueue_script( 'dokan-script' );
                wp_enqueue_script( 'dokan-select2-js' );
            }
        }

        do_action( 'dokan_enqueue_scripts' );
    }

    /**
     * Load form validate script args
     *
     * @since 2.5.3
     *
     */
    function load_form_validate_script() {

        $form_validate_messages = array(
            'required'        => __( "This field is required", 'dokan-lite' ),
            'remote'          => __( "Please fix this field.", 'dokan-lite' ),
            'email'           => __( "Please enter a valid email address.", 'dokan-lite' ),
            'url'             => __( "Please enter a valid URL.", 'dokan-lite' ),
            'date'            => __( "Please enter a valid date.", 'dokan-lite' ),
            'dateISO'         => __( "Please enter a valid date (ISO).", 'dokan-lite' ),
            'number'          => __( "Please enter a valid number.", 'dokan-lite' ),
            'digits'          => __( "Please enter only digits.", 'dokan-lite' ),
            'creditcard'      => __( "Please enter a valid credit card number.", 'dokan-lite' ),
            'equalTo'         => __( "Please enter the same value again.", 'dokan-lite' ),
            'maxlength_msg'   => __( "Please enter no more than {0} characters.", 'dokan-lite' ),
            'minlength_msg'   => __( "Please enter at least {0} characters.", 'dokan-lite' ),
            'rangelength_msg' => __( "Please enter a value between {0} and {1} characters long.", 'dokan-lite' ),
            'range_msg'       => __( "Please enter a value between {0} and {1}.", 'dokan-lite' ),
            'max_msg'         => __( "Please enter a value less than or equal to {0}.", 'dokan-lite' ),
            'min_msg'         => __( "Please enter a value greater than or equal to {0}.", 'dokan-lite' ),
        );

        wp_localize_script( 'dokan-form-validate', 'DokanValidateMsg', apply_filters( 'DokanValidateMsg_args', $form_validate_messages ) );
    }

    /**
     * Load Dokan Dashboard Scripts
     *
     * @since 2.5.3
     *
     * @global type $wp
     */
    function dokan_dashboard_scripts() {
        global $wp;

        if ( DOKAN_LOAD_STYLE ) {
            wp_enqueue_style( 'jquery-ui' );
            wp_enqueue_style( 'dokan-magnific-popup' );
            wp_enqueue_style( 'woocommerce-general' );
            wp_enqueue_style( 'dokan-select2-css' );
            wp_enqueue_style( 'dokan-chosen-style' );
        }

        if ( DOKAN_LOAD_SCRIPTS ) {
            $this->load_form_validate_script();
            $this->load_gmap_script();

            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui' );
            wp_enqueue_script( 'jquery-ui-autocomplete' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( 'underscore' );
            wp_enqueue_script( 'post' );
            wp_enqueue_script( 'dokan-tooltip' );
            wp_enqueue_script( 'dokan-form-validate' );
            wp_enqueue_script( 'dokan-tabs' );
            wp_enqueue_script( 'dokan-chart' );
            wp_enqueue_script( 'dokan-flot' );
            wp_enqueue_script( 'dokan-chosen' );
            wp_enqueue_script( 'dokan-select2-js' );
            wp_enqueue_media();
            wp_enqueue_script( 'serializejson' );
            wp_enqueue_script( 'dokan-popup' );
            wp_enqueue_script( 'wc-password-strength-meter' );
            wp_enqueue_script( 'dokan-script' );
        }

        if ( isset( $wp->query_vars['settings'] ) == 'store' ) {
            wp_enqueue_script( 'wc-country-select' );
        }
    }

    /**
     * Load google map script
     *
     * @since 2.5.3
     */
    function load_gmap_script() {

        $scheme  = is_ssl() ? 'https' : 'http';
        $api_key = dokan_get_option( 'gmap_api_key', 'dokan_general', false );

        if ( $api_key ) {
            wp_enqueue_script( 'google-maps', $scheme . '://maps.google.com/maps/api/js?key=' . $api_key );
        }
    }

    /**
     * Filter 'dokan' localize script's arguments
     *
     * @since 2.5.3
     *
     * @param array $default_args
     *
     * @return $default_args
     */
    function conditional_localized_args( $default_args ) {

        if ( dokan_is_seller_dashboard()
            || ( get_query_var( 'edit' ) && is_singular( 'product' ) )
            || dokan_is_store_page()
            || is_account_page()
            || is_product()
            || apply_filters( 'dokan_force_load_extra_args', false )
        )
        {
            $general_settings = get_option( 'dokan_general', array() );

            $banner_width    = !empty( $general_settings['store_banner_width'] ) ? $general_settings['store_banner_width'] : 625;
            $banner_height   = !empty( $general_settings['store_banner_height'] ) ? $general_settings['store_banner_height'] : 300;
            $has_flex_width  = !empty( $general_settings['store_banner_flex_width'] ) ? $general_settings['store_banner_flex_width'] : true;
            $has_flex_height = !empty( $general_settings['store_banner_flex_height'] ) ? $general_settings['store_banner_flex_height'] : true;

            $custom_args = array(
                'i18n_choose_featured_img'            => __( 'Upload featured image', 'dokan-lite' ),
                'i18n_choose_file'                    => __( 'Choose a file', 'dokan-lite' ),
                'i18n_choose_gallery'                 => __( 'Add Images to Product Gallery', 'dokan-lite' ),
                'i18n_choose_featured_img_btn_text'   => __( 'Set featured image', 'dokan-lite' ),
                'i18n_choose_file_btn_text'           => __( 'Insert file URL', 'dokan-lite' ),
                'i18n_choose_gallery_btn_text'        => __( 'Add to gallery', 'dokan-lite' ),
                'duplicates_attribute_messg'          => __( 'Sorry, this attribute option already exists, Try a different one.', 'dokan-lite' ),
                'variation_unset_warning'             => __( 'Warning! This product will not have any variations if this option is not checked.', 'dokan-lite' ),
                'new_attribute_prompt'                => __( 'Enter a name for the new attribute term:', 'dokan-lite' ),
                'remove_attribute'                    => __( 'Remove this attribute?', 'dokan-lite' ),
                'dokan_placeholder_img_src'           => wc_placeholder_img_src(),
                'add_variation_nonce'                 => wp_create_nonce( 'add-variation' ),
                'link_variation_nonce'                => wp_create_nonce( 'link-variations' ),
                'delete_variations_nonce'             => wp_create_nonce( 'delete-variations' ),
                'load_variations_nonce'               => wp_create_nonce( 'load-variations' ),
                'save_variations_nonce'               => wp_create_nonce( 'save-variations' ),
                'bulk_edit_variations_nonce'          => wp_create_nonce( 'bulk-edit-variations' ),
                'i18n_link_all_variations'            => esc_js( sprintf( __( 'Are you sure you want to link all variations? This will create a new variation for each and every possible combination of variation attributes (max %d per run).', 'dokan-lite' ), defined( 'WC_MAX_LINKED_VARIATIONS' ) ? WC_MAX_LINKED_VARIATIONS : 50 ) ),
                'i18n_enter_a_value'                  => esc_js( __( 'Enter a value', 'dokan-lite' ) ),
                'i18n_enter_menu_order'               => esc_js( __( 'Variation menu order (determines position in the list of variations)', 'dokan-lite' ) ),
                'i18n_enter_a_value_fixed_or_percent' => esc_js( __( 'Enter a value (fixed or %)', 'dokan-lite' ) ),
                'i18n_delete_all_variations'          => esc_js( __( 'Are you sure you want to delete all variations? This cannot be undone.', 'dokan-lite' ) ),
                'i18n_last_warning'                   => esc_js( __( 'Last warning, are you sure?', 'dokan-lite' ) ),
                'i18n_choose_image'                   => esc_js( __( 'Choose an image', 'dokan-lite' ) ),
                'i18n_set_image'                      => esc_js( __( 'Set variation image', 'dokan-lite' ) ),
                'i18n_variation_added'                => esc_js( __( "variation added", 'dokan-lite' ) ),
                'i18n_variations_added'               => esc_js( __( "variations added", 'dokan-lite' ) ),
                'i18n_no_variations_added'            => esc_js( __( "No variations added", 'dokan-lite' ) ),
                'i18n_remove_variation'               => esc_js( __( 'Are you sure you want to remove this variation?', 'dokan-lite' ) ),
                'i18n_scheduled_sale_start'           => esc_js( __( 'Sale start date (YYYY-MM-DD format or leave blank)', 'dokan-lite' ) ),
                'i18n_scheduled_sale_end'             => esc_js( __( 'Sale end date (YYYY-MM-DD format or leave blank)', 'dokan-lite' ) ),
                'i18n_edited_variations'              => esc_js( __( 'Save changes before changing page?', 'dokan-lite' ) ),
                'i18n_variation_count_single'         => esc_js( __( '%qty% variation', 'dokan-lite' ) ),
                'i18n_variation_count_plural'         => esc_js( __( '%qty% variations', 'dokan-lite' ) ),
                'i18n_no_result_found'                => esc_js( __( 'No Result Found', 'dokan-lite' ) ),
                'variations_per_page'                 => absint( apply_filters( 'dokan_product_variations_per_page', 10 ) ),
                'store_banner_dimension'              => [ 'width' => $banner_width, 'height' => $banner_height, 'flex-width' => $has_flex_width, 'flex-height' => $has_flex_height ],
                'selectAndCrop'                       => __( 'Select and Crop', 'dokan-lite' ),
                'chooseImage'                         => __( 'Choose Image', 'dokan-lite' ),
                'product_title_required'              => __( 'Product title is required', 'dokan-lite' ),
                'product_category_required'           => __( 'Product category is required', 'dokan-lite' ),
                'search_products_nonce'               => wp_create_nonce( 'search-products' ),
                'i18n_matches_1'                      => __( 'One result is available, press enter to select it.', 'dokan-lite' ),
                'i18n_matches_n'                      => __( '%qty% results are available, use up and down arrow keys to navigate.', 'dokan-lite' ),
                'i18n_no_matches'                     => __( 'No matches found', 'dokan-lite' ),
                'i18n_ajax_error'                     => __( 'Loading failed', 'dokan-lite' ),
                'i18n_input_too_short_1'              => __( 'Please enter 1 or more characters', 'dokan-lite' ),
                'i18n_input_too_short_n'              => __( 'Please enter %qty% or more characters', 'dokan-lite' ),
                'i18n_input_too_long_1'               => __( 'Please delete 1 character', 'dokan-lite' ),
                'i18n_input_too_long_n'               => __( 'Please delete %qty% characters', 'dokan-lite' ),
                'i18n_selection_too_long_1'           => __( 'You can only select 1 item', 'dokan-lite' ),
                'i18n_selection_too_long_n'           => __( 'You can only select %qty% items', 'dokan-lite' ),
                'i18n_load_more'                      => __( 'Loading more results&hellip;', 'dokan-lite' ),
                'i18n_searching'                      => __( 'Searching&hellip;', 'dokan-lite' )
            );

            $default_args = array_merge( $default_args, $custom_args );
        }

        return $default_args;
    }

    /**
     * Get file prefix
     *
     * @return string
     */
    public function get_prefix() {
        $prefix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        return $prefix;
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    public function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : true;
            $version   = isset( $script['version'] ) ? $script['version'] : DOKAN_PLUGIN_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps    = isset( $style['deps'] ) ? $style['deps'] : false;
            $version = isset( $style['version'] ) ? $style['version'] : DOKAN_PLUGIN_VERSION;

            wp_register_style( $handle, $style['src'], $deps, $version );
        }
    }

    /**
     * Enqueue the scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    public function enqueue_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            wp_enqueue_script( $handle );
        }
    }

    /**
     * Enqueue styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function enqueue_styles( $styles ) {
        foreach ( $styles as $handle => $script ) {
            wp_enqueue_style( $handle );
        }
    }

}
