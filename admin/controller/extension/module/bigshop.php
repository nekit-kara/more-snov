<?php
class ControllerExtensionModuleBigshop extends Controller {
    private $error = array(); 
    
    public function index() {   
	
		$language = $this->load->language('extension/module/bigshop');
        $data = array_merge($language);
		

        $this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/admin-bigshop.css');
		$this->document->addScript('view/javascript/jscolor/jscolor.js');
		
        
        $this->load->model('setting/setting');
        
        $this->load->model('tool/image');  
        
		
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('bigshop', $this->request->post);    

            $this->session->data['success'] = $this->language->get('text_success');
        
            if(isset($this->request->post['save_stay']) and $this->request->post['save_stay']=1)
			$this->response->redirect($this->url->link('extension/module/bigshop', 'token=' . $this->session->data['token'], 'SSL'));
			else
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
			
        }
		
		
		
            $data['text_image_manager'] = 'Image manager';
            $data['token'] = $this->session->data['token'];       
        
        $text_strings = array(
                'heading_title',
                'text_enabled',
                'text_disabled',
                'text_content_top',
                'text_content_bottom',
                'text_column_left',
                'text_column_right',
                'entry_status',
                'entry_sort_order',
                'button_save',
                'button_cancel',
				
        );
		
		$data['button_save_stay'] = $this->language->get('button_save_stay');
        $data['result_success'] = $this->language->get('result_success');
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
        
        foreach ($text_strings as $text) {
            $data[$text] = $this->language->get($text);
        }
        

        // store config data
        
        $config_data = array(

		'bigshop_skin',
		
		//Body Background
        'bigshop_background_color',
		'bigshop_pattern_overlay',
        'bigshop_custom_image',
        'bigshop_image_preview',
        'bigshop_pattern_preview',
		'bigshop_custom_image_position',
		'bigshop_custom_image_attachment',
		'bigshop_custom_image_repeat',
		
		//Button
        'bigshop_button_color',
        'bigshop_button_hover_color',
        'bigshop_button_text_color',
		
		//Footer
        'bigshop_footer_heading_text_color',
        'bigshop_sub_link_text_color',
        'bigshop_powerd_by_text_color',
		
		'bigshop_footer_bg_color',
		'bigshop_footer_titles_color',
		'bigshop_footer_text_color',
		'bigshop_footer_link_color',
		'bigshop_footer_link_hover_color',
		
		'bigshop_contact_icon_color',
		'bigshop_contact_icon_bg_color',
		
		'bigshop_footer_second_bg_color',
		'bigshop_footer_second_text_color',
		'bigshop_footer_second_link_color',
		'bigshop_footer_second_link_hover_color',
		'bigshop_footer_second_fst_separator_status',
		'bigshop_footer_second_separator_size',
		'bigshop_footer_second_separator_color',
		'bigshop_footer_second_separator_style',
		
		'bigshop_footer_second_2nd_separator_status',
		'bigshop_footer_second_2nd_separator_size',
		'bigshop_footer_second_2nd_separator_style',
		'bigshop_footer_second_2nd_separator_color',
		
		'bigshop_product_box_border_hover_color',
		
		'bigshop_product_name_color',
		'bigshop_product_name_hover_color',
		//Price
		'bigshop_price_color',
		'bigshop_old_price_color',
		'bigshop_new_price_color',
		
		'bigshop_old_price_product_page_color',
		'bigshop_price_bg_color',
		'bigshop_price_text_color',
		
		'bigshop_saving_percentage_bg_color',
		'bigshop_saving_percentage_text_color',
		
		'bigshop_wishlist_compare_bg_color',
		'bigshop_wishlist_compare_text_color',
		'bigshop_wishlist_compare_hover_bg_color',
		'bigshop_wishlist_compare_hover_text_color',
		
		//Buttons
		'bigshop_button_bg_color',
		'bigshop_button_bg_hover_color',
		'bigshop_button_text_color',
		'bigshop_button_text_hover_color',
		
		'bigshop_default_button_bg_color',
		'bigshop_default_button_bg_hover_color',
		'bigshop_default_button_text_color',
		'bigshop_default_button_text_hover_color',
		
		'bigshop_excl_button_bg_color',
		'bigshop_excl_button_bg_hover_color',
		'bigshop_excl_button_text_color',
		'bigshop_excl_button_text_hover_color',
		
		'bigshop_cart_button_bg_color',
		'bigshop_cart_button_bg_hover_color',
		'bigshop_cart_button_text_color',
		'bigshop_cart_button_text_hover_color',		
		
		'bigshop_body_text_color',
		'bigshop_heading_color',
		
		//Social Icon
		'bigshop_facebook_id',
        'bigshop_twitter_username',
        'bigshop_gplus_id',
		'bigshop_pint_id',
		'bigshop_rss',
		'bigshop_blogger',		
		'bigshop_myspace',
		'bigshop_linkedin',
		'bigshop_evernote',
		'bigshop_dopplr',
		'bigshop_ember',
		'bigshop_flickr',
		'bigshop_picasa_web',
		'bigshop_youtube',
		'bigshop_technorati',
		'bigshop_grooveshark',
		'bigshop_vimeo',
		'bigshop_sharethis',
		'bigshop_yahoobuzz',
		'bigshop_viddler',
		'bigshop_skype',
		'bigshop_google_googletalk',
		'bigshop_digg',
		'bigshop_reddit',
		'bigshop_delicious',
		'bigshop_stumbleupon',
		'bigshop_friendfeed',
		'bigshop_tumblr',
		'bigshop_yelp',
		'bigshop_posterous',
		'bigshop_bebo',
		'bigshop_virb',
		'bigshop_last_fm',
		'bigshop_pandora',
		'bigshop_mixx',
		'bigshop_newsvine',
		'bigshop_openid',
		'bigshop_readernaut',
		'bigshop_xing_me',
		'bigshop_instagram',
		'bigshop_spotify',
		'bigshop_forrst',
		'bigshop_viadeo',
		'bigshop_vk_com',

		//Fonts		
		'bigshop_title_font',
		'bigshop_title_font_weight',
		'bigshop_title_font_uppercase',
		'bigshop_title_font_size',
		
        'bigshop_body_font',
		'bigshop_main_menu_font',
		'bigshop_main_menu_font_weight',
		'bigshop_main_menu_font_uppercase',
		'bigshop_main_menu_font_size',
		
		'bigshop_top_bar_font',
		'bigshop_top_bar_font_weight',
		'bigshop_top_bar_font_size',
		'bigshop_top_bar_font_uppercase',
		
		'bigshop_secondary_titles_font',
		'bigshop_secondary_titles_font_weight',
		'bigshop_secondary_titles_font_uppercase',
		'bigshop_secondary_titles_font_size',
		
		'bigshop_footer_titles_font',
		'bigshop_footer_titles_font_weight',
		'bigshop_footer_titles_font_uppercase',
		'bigshop_footer_titles_font_size',
		'bigshop_footer_backtotop_bg_color',
		
		//Socia Fun Box
		'bigshop_facebook_id_box',		
        'twitter_username',

		'bigshop_custom_column',
		'bigshop_widget_id',		
		'bigshop_powered',
		
		'bigshop_custom_column_footer_status',
		'bigshop_custom_column_footer',
		
		'bigshop_custom_block2_status',
		'bigshop_custom_block2_title',
		'bigshop_custom_block2_content',
		
		'bigshop_custom_block3_status',
		'bigshop_custom_block3_title',
		'bigshop_custom_block3_content',
		'bigshop_custom_block_bg_color',
		'bigshop_video_block_bg_color',
		
		
		//Feature Box
		'bigshop_feature_box_per_row',
		'bigshop_feature_box_homepage_only',
		'bigshop_feature_box_show_header_footer',
		
		'bigshop_feature_box1_status',
		'bigshop_feature_box1_title',
		'bigshop_feature_box1_subtitle',
		
		'bigshop_feature_box2_status',
		'bigshop_feature_box2_title',
		'bigshop_feature_box2_subtitle',
		
		'bigshop_feature_box3_status',
		'bigshop_feature_box3_title',
		'bigshop_feature_box3_subtitle',
		
		'bigshop_feature_box4_status',
		'bigshop_feature_box4_title',
		'bigshop_feature_box4_subtitle',
		
		'bigshop_feature_box1_bg_color',
		'bigshop_feature_box2_bg_color',
		'bigshop_feature_box3_bg_color',
		'bigshop_feature_box4_bg_color',
		
		'bigshop_feature_box1_border_color',
		'bigshop_feature_box2_border_color',
		'bigshop_feature_box3_border_color',
		'bigshop_feature_box4_border_color',
		
		'bigshop_feature_box1_title_color',
		'bigshop_feature_box2_title_color',
		'bigshop_feature_box3_title_color',
		'bigshop_feature_box4_title_color',
		
		'bigshop_feature_box1_subtitle_color',
		'bigshop_feature_box2_subtitle_color',
		'bigshop_feature_box3_subtitle_color',
		'bigshop_feature_box4_subtitle_color',
		
		'bigshop_feature_box_title_font_size',
		'bigshop_feature_box_title_font_weight',
		'bigshop_feature_box_title_font_transform',
		
		'bigshop_feature_box_title_font_size',
		'bigshop_feature_box_title_font_weight',
		'bigshop_feature_box_title_font_transform',
		
		'bigshop_feature_box_subtitle_font_size',
		'bigshop_feature_box_subtitle_font_weight',
		'bigshop_feature_box_subtitle_font_transform',

		//Contact Details
		'bigshop_contact',
		'bigshop_address',
		'bigshop_mobile',
		'bigshop_email',
		
		'bigshop_contact_status',
		'bigshop_address_status',
		'bigshop_mobile_status',
		'bigshop_email_status',
		'bigshop_about_contact_details',
		'bigshop_about_details',
		
		//Payment Methods Image
		'bigshop_custom_payment_image_status',
		'bigshop_custom_payment_image_url',
		'bigshop_custom_payment_image',
		'bigshop_custom_payment_image_preview',
		'bigshop_paypal',
		'bigshop_paypal_url',
		'bigshop_american',
		'bigshop_american_url',
		'bigshop_2checkout',
		'bigshop_2checkout_url',
		'bigshop_discover',
		'bigshop_discover_url',
		'bigshop_maestro',
		'bigshop_maestro_url',
		'bigshop_mastercard',
		'bigshop_mastercard_url',
		'bigshop_visa',
		'bigshop_visa_url',
		'bigshop_sagepay',
		'bigshop_sagepay_url',
		'bigshop_moneybookers',
		'bigshop_moneybookers_url',
		'bigshop_cirrus',
		'bigshop_cirrus_url',
		'bigshop_delta',
		'bigshop_delta_url',
		'bigshop_direct',
		'bigshop_direct_url',
		'bigshop_google',
		'bigshop_google_url',
		'bigshop_solo',
		'bigshop_solo_url',
		'bigshop_switch',
		'bigshop_switch_url',
		'bigshop_western_union',
		'bigshop_western_union_url',
		'bigshop_ebay',
		'bigshop_ebay_url',
		
		//Custom CSS
        'bigshop_custom_css',
		'bigshop_top_bar_link_color',
		'bigshop_top_bar_link_hover_color',
		'bigshop_top_bar_tele_email_color',
		'bigshop_top_bar_sub_link_color',
		'bigshop_top_bar_sub_link_hover_color',
		'bigshop_top_bar_contact_status',
		'bigshop_top_bar_contact',
		'bigshop_top_bar_email_status',
		'bigshop_top_bar_email',
		
		'bigshop_header_bg_color',
		
		'bigshop_mini_cart_icon_color',
		'bigshop_mini_cart_link_color',
		'bigshop_mini_cart_active_link_color',
		
		'bigshop_search_bar_background_color',
		'bigshop_search_bar_border_color',
		'bigshop_search_bar_text_color',
		'bigshop_search_bar_border_hover_color',
		'bigshop_search_bar_icon_color',
		'bigshop_search_bar_icon_bg_color',
		
		//Top Menu
        'bigshop_top_menu',
		
		'bigshop_menu_bg_color_status',
		'bigshop_menu_bg_color',
		'bigshop_menu_link_color',
		'bigshop_menu_link_hover_color',
		'bigshop_menu_link_hover_bg_color',
		
		'bigshop_home_page_link_background_color',
		'bigshop_home_page_link_background_color_hover',
		'bigshop_home_page_link_link_color',
		'bigshop_home_page_link_link_color_hover',
				
		'bigshop_categories_section_background_color',
		'bigshop_categories_section_background_color_hover',
		'bigshop_categories_section_link_color',
		'bigshop_categories_section_link_color_hover',
		
		'bigshop_brands_section_background_color',
		'bigshop_brands_section_background_color_hover',
		'bigshop_brands_section_link_color',
		'bigshop_brands_section_link_color_hover',
		
		'bigshop_custom_link_section_background_color',
		'bigshop_custom_link_section_background_color_hover',
		'bigshop_custom_link_section_link_color',
		'bigshop_custom_link_section_link_color_hover',
		
		'bigshop_custom_block_section_background_color',
		'bigshop_custom_block_section_background_color_hover',
		'bigshop_custom_block_section_link_color',
		'bigshop_custom_block_section_link_color_hover',
		
		'bigshop_custom_block_section_background_color',
		'bigshop_custom_block_section_background_color_hover',
		'bigshop_custom_block_section_link_color',
		'bigshop_custom_block_section_link_color_hover',
		
		'bigshop_information_section_background_color',
		'bigshop_information_section_background_color_hover',
		'bigshop_information_section_link_color',
		'bigshop_information_section_link_color_hover',
		
		'bigshop_contact_section_background_color',
		'bigshop_contact_section_background_color_hover',
		'bigshop_contact_section_link_color',
		'bigshop_contact_section_link_color_hover',
		
		'bigshop_sub_menu_background_color',
		'bigshop_sub_menu_heading_text_color',
		'bigshop_sub_menu_heading_text_separator_color',
		'bigshop_sub_menu_heading_text_separator_style',
		'bigshop_sub_menu_link_hover_color',
		'bigshop_sub_menu_link_color',
		'bigshop_sub_menu_link_separator_color',
		'bigshop_sub_menu_link_separator_style',
		'bigshop_menu_link_separator_color',
		'bigshop_menu_link_separator_status',
		'bigshop_menu_link_separator_size',
		'bigshop_menu_link_separator_style',
		
		'bigshop_menu_link_border_top_color',
		'bigshop_menu_link_border_top_status',
		'bigshop_menu_link_border_top_size',
		'bigshop_menu_link_border_top_style',
		
		'bigshop_menu_link_border_bottom_color',
		'bigshop_menu_link_border_bottom_status',
		'bigshop_menu_link_border_bottom_size',
		'bigshop_menu_link_border_bottom_style',
		
		'bigshop_custom_link1_top',
		'bigshop_custom_link1_top_url',
		'bigshop_custom_link1_top_title',
		'bigshop_target_link1_top',
		
		'bigshop_custom_link2_top',
		'bigshop_custom_link2_top_url',
		'bigshop_custom_link2_top_title',
		'bigshop_target_link2_top',
		
		'bigshop_custom_link3_top',
		'bigshop_custom_link3_top_url',
		'bigshop_custom_link3_top_title',
		'bigshop_target_link3_top',
		
		
		'bigshop_custom_block_top',
		'bigshop_custom_block_top_status',
		'bigshop_custom_block_top_title',
		'bigshop_custom_block_top_content',
		
		'bigshop_custom_block2_top_status',
		'bigshop_custom_block2_top_title',
		'bigshop_custom_block2_top_content',
		
		'bigshop_wishlist_top_link',
		'bigshop_checkout_top_link',
		
		'bigshop_header_margin',
		'bigshop_logo_margin',
		'bigshop_layout_style',
		'bigshop_main_menu_style',
		'bigshop_layout_border_radius',
		'bigshop_layout_top_margin',
		'bigshop_custom_link1',
		'bigshop_custom_link1_url',
		'bigshop_custom_link1_title',
		'bigshop_target_link1',
		
		'bigshop_custom_link2',
		'bigshop_custom_link2_url',
		'bigshop_custom_link2_title',
		'bigshop_target_link2',
		
		'bigshop_custom_link3',
		'bigshop_custom_link3_url',
		'bigshop_custom_link3_title',
		'bigshop_target_link3',
		
		'bigshop_custom_link4',
		'bigshop_custom_link4_url',
		'bigshop_custom_link4_title',
		'bigshop_target_link4',
		
		'bigshop_custom_link5',
		'bigshop_custom_link5_url',
		'bigshop_custom_link5_title',
		'bigshop_custom_block',
		'bigshop_custom_block_status',
		'bigshop_custom_block_title',
		'bigshop_custom_block_content',
		
		'bigshop_target_link5',
		'bigshop_main_menu_align',
		'bigshop_home_page_link',
		'bigshop_home_page_link_icon',
		'bigshop_information_page',
		'bigshop_contact_us',
		'bigshop_menu_brands',
				
		'bigshop_menu_brands_title',
		'bigshop_menu_categories_title',
		
		'bigshop_twitter_box_align',
		'bigshop_facebook_box_align',
		'bigshop_facebook_block_status',
		'bigshop_twitter_block_status',
		'bigshop_twitter_block_sort_order',
		'bigshop_facebook_block_sort_order',
		'bigshop_custom_side_block_sort_order',
		'bigshop_custom_column_status',
		'bigshop_custom_side_block_align',
		'bigshop_video_box_status',
		'bigshop_video_box_align',
		'bigshop_video_box_content',
		'bigshop_video_box_sort_order',
		
		'bigshop_back_to_top',
		'bigshop_mobile_menu_title',
		'bigshop_search_auto_complete',
		'bigshop_affiliate_status',
		'bigshop_language_currency_title',
		
		'bigshop_refine_categories',
		'bigshop_refine_categories_images',
		'bigshop_brands_display_style',
		
		'bigshop_category_product_per_row',
		'bigshop_manufacturer_product_per_row',
		'bigshop_special_product_per_row',
		'bigshop_search_product_per_row',
		
		'bigshop_featured_slider_product_per_row',
		'bigshop_bestsellers_slider_product_per_row',
		'bigshop_latest_slider_product_per_row',
		'bigshop_specials_slider_product_per_row',
		'bigshop_related_slider_product_per_row',
		'bigshop_product_tab_slider_product_per_row',
		
		'bigshop_featured_slider_product_style',
		'bigshop_bestsellers_slider_product_style',
		'bigshop_latest_slider_product_style',
		'bigshop_specials_slider_product_style',
		'bigshop_related_slider_product_style',
		'bigshop_product_tab_slider_product_style',
		
		'bigshop_general_links_color',
		'bigshop_general_links_hover_color',
		'bigshop_secondary_heading_color',
		'bigshop_secondary_heading_border_color',
		'bigshop_secondary_heading_border_hl_color',
		
		'bigshop_cloud_zoom',
		'bigshop_cloud_zoom_gallery',
		'bigshop_cloud_zoom_gallery_text',
		'bigshop_cloud_zoom_inner',
		'bigshop_share_plugin',
		'bigshop_share_plugin_style',
		'bigshop_percentage_discount_badge',
		'bigshop_homepage_slideshow',
        );
        
        foreach ($config_data as $conf) {
            if (isset($this->request->post[$conf])) {
                $data[$conf] = $this->request->post[$conf];
            } else {
                $data[$conf] = $this->config->get($conf);
            }
        }
    
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        $data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
        
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/module/bigshop', 'token=' . $this->session->data['token'], true)
        );
		
        
        $data['action'] = $this->url->link('extension/module/bigshop', 'token=' . $this->session->data['token'], true);
        
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
    
        //This code handles the situation where you have multiple instances of this module, for different layouts.
        if (isset($this->request->post['bigshop_module'])) {
            $modules = explode(',', $this->request->post['bigshop_module']);
        } elseif ($this->config->get('bigshop_module') != '') {
            $modules = explode(',', $this->config->get('bigshop_module'));
        } else {
            $modules = array();
        }           
                
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
        
        $data['modules'] = $modules;
        
        if (isset($this->request->post['bigshop_module'])) {
            $data['bigshop'] = $this->request->post['bigshop'];
        } else {
            $data['bigshop'] = $this->config->get('bigshop');

		}
		
		$data['bigshop_modules'] = array();


       if (isset($this->request->post['bigshop_custom_image']) && is_file(DIR_IMAGE . $this->request->post['bigshop_custom_image'])) {
			$data['bigshop_image_preview'] = $this->model_tool_image->resize($this->request->post['bigshop_custom_image'], 100, 100);
		} elseif ($this->config->get('bigshop_custom_image') && is_file(DIR_IMAGE . $this->config->get('bigshop_custom_image'))) {
			$data['bigshop_image_preview'] = $this->model_tool_image->resize($this->config->get('bigshop_custom_image'), 100, 100);
		} else {
			$data['bigshop_image_preview'] = $this->model_tool_image->resize('no_image.png', 100, 100);;
		}
		
		if (isset($this->request->post['bigshop_custom_payment_image']) && is_file(DIR_IMAGE . $this->request->post['bigshop_custom_payment_image'])) {
			$data['bigshop_custom_payment_image_preview'] = $this->model_tool_image->resize($this->request->post['bigshop_custom_payment_image'], 100, 100);
		} elseif ($this->config->get('bigshop_custom_payment_image') && is_file(DIR_IMAGE . $this->config->get('bigshop_custom_payment_image'))) {
			$data['bigshop_custom_payment_image_preview'] = $this->model_tool_image->resize($this->config->get('bigshop_custom_payment_image'), 100, 100);
		} else {
			$data['bigshop_custom_payment_image_preview'] = $this->model_tool_image->resize('no_image.png', 100, 100);;
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/bigshop', $data));
    }
    
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/bigshop')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
 