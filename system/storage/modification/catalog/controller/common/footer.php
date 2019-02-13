<?php

class ControllerCommonFooter extends Controller
{
    private $ssl = 'NONSSL';

    public function __construct($registry)
    {
        parent::__construct($registry);

        if ($this->request->server['HTTPS']) {
            $this->ssl = 'SSL';
        }
    }

    public function index()
    {
        $this->load->language('common/footer');

        $data['scripts'] = $this->document->getScripts('footer');

        $data['text_information'] = $this->language->get('text_information');
        $data['text_service'] = $this->language->get('text_service');
        $data['text_extra'] = $this->language->get('text_extra');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_sitemap'] = $this->language->get('text_sitemap');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_special'] = $this->language->get('text_special');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_newsletter'] = $this->language->get('text_newsletter');

        $this->load->model('catalog/information');
        if (!isset($this->request->get['route']) || $this->request->get['route'] == 'common/home') {
            $data['ishome'] = 1;
        }

        $data['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            if ($result['bottom']) {
                $data['informations'][] = array(
                    'title' => $result['title'],
                    'href' => $this->url->link('information/information', 'information_id=' . $result['information_id'], $this->ssl)
                );
            }
        }

        $data['contact'] = $this->url->link('information/contact', '', $this->ssl);
        $data['return'] = $this->url->link('account/return/add', '', $this->ssl);
        $data['sitemap'] = $this->url->link('information/sitemap', '', $this->ssl);
        $data['manufacturer'] = $this->url->link('product/manufacturer', '', $this->ssl);
        $data['voucher'] = $this->url->link('account/voucher', '', $this->ssl);
        $data['affiliate'] = $this->url->link('affiliate/account', '', $this->ssl);
        $data['special'] = $this->url->link('product/special', '', $this->ssl);
        $data['account'] = $this->url->link('account/account', '', $this->ssl);
        $data['order'] = $this->url->link('account/order', '', $this->ssl);
        $data['wishlist'] = $this->url->link('account/wishlist', '', $this->ssl);
        $data['newsletter'] = $this->url->link('account/newsletter', '', $this->ssl);


        $data['config_language_id'] = $this->config->get('config_language_id');
        $data['bigshop_feature_box_per_row'] = $this->config->get('bigshop_feature_box_per_row');
        $data['bigshop_feature_box_homepage_only'] = $this->config->get('bigshop_feature_box_homepage_only');
        $data['bigshop_feature_box_show_header_footer'] = $this->config->get('bigshop_feature_box_show_header_footer');
        $data['bigshop_feature_box_per_row'] = $this->config->get('bigshop_feature_box_per_row');
        $data['bigshop_feature_box1_title'] = $this->config->get('bigshop_feature_box1_title');
        $data['bigshop_feature_box1_subtitle'] = $this->config->get('bigshop_feature_box1_subtitle');
        $data['bigshop_feature_box2_title'] = $this->config->get('bigshop_feature_box2_title');
        $data['bigshop_feature_box2_subtitle'] = $this->config->get('bigshop_feature_box2_subtitle');
        $data['bigshop_feature_box3_title'] = $this->config->get('bigshop_feature_box3_title');
        $data['bigshop_feature_box3_subtitle'] = $this->config->get('bigshop_feature_box3_subtitle');
        $data['bigshop_feature_box4_title'] = $this->config->get('bigshop_feature_box4_title');
        $data['bigshop_feature_box4_subtitle'] = $this->config->get('bigshop_feature_box4_subtitle');
        $data['bigshop_feature_box1_status'] = $this->config->get('bigshop_feature_box1_status');
        $data['bigshop_feature_box2_status'] = $this->config->get('bigshop_feature_box2_status');
        $data['bigshop_feature_box3_status'] = $this->config->get('bigshop_feature_box3_status');
        $data['bigshop_feature_box4_status'] = $this->config->get('bigshop_feature_box4_status');

        $data['bigshop_affiliate_status'] = $this->config->get('bigshop_affiliate_status');
        $data['bigshop_about_contact_details'] = $this->config->get('bigshop_about_contact_details');
        $data['bigshop_address'] = $this->config->get('bigshop_address');
        $data['bigshop_contact'] = $this->config->get('bigshop_contact');
        $data['bigshop_mobile'] = $this->config->get('bigshop_mobile');
        $data['bigshop_email'] = $this->config->get('bigshop_email');

        $data['bigshop_contact_status'] = $this->config->get('bigshop_contact_status');
        $data['bigshop_address_status'] = $this->config->get('bigshop_address_status');
        $data['bigshop_mobile_status'] = $this->config->get('bigshop_mobile_status');
        $data['bigshop_email_status'] = $this->config->get('bigshop_email_status');
        $data['bigshop_about_details'] = $this->config->get('bigshop_about_details');

        $data['bigshop_custom_payment_image_status'] = $this->config->get('bigshop_custom_payment_image_status');
        $data['bigshop_paypal'] = $this->config->get('bigshop_paypal');
        $data['bigshop_paypal_url'] = $this->config->get('bigshop_paypal_url');
        $data['bigshop_american'] = $this->config->get('bigshop_american');
        $data['bigshop_american_url'] = $this->config->get('bigshop_american_url');
        $data['bigshop_2checkout'] = $this->config->get('bigshop_2checkout');
        $data['bigshop_2checkout_url'] = $this->config->get('bigshop_2checkout_url');
        $data['bigshop_maestro'] = $this->config->get('bigshop_maestro');
        $data['bigshop_maestro_url'] = $this->config->get('bigshop_maestro_url');
        $data['bigshop_discover'] = $this->config->get('bigshop_discover');
        $data['bigshop_discover_url'] = $this->config->get('bigshop_discover_url');
        $data['bigshop_mastercard'] = $this->config->get('bigshop_mastercard');
        $data['bigshop_mastercard_url'] = $this->config->get('bigshop_mastercard_url');
        $data['bigshop_visa'] = $this->config->get('bigshop_visa');
        $data['bigshop_visa_url'] = $this->config->get('bigshop_visa_url');
        $data['bigshop_sagepay'] = $this->config->get('bigshop_sagepay');
        $data['bigshop_sagepay_url'] = $this->config->get('bigshop_sagepay_url');
        $data['bigshop_moneybookers'] = $this->config->get('bigshop_moneybookers');
        $data['bigshop_moneybookers_url'] = $this->config->get('bigshop_moneybookers_url');
        $data['bigshop_cirrus'] = $this->config->get('bigshop_cirrus');
        $data['bigshop_cirrus_url'] = $this->config->get('bigshop_cirrus_url');
        $data['bigshop_delta'] = $this->config->get('bigshop_delta');
        $data['bigshop_delta_url'] = $this->config->get('bigshop_delta_url');
        $data['bigshop_direct'] = $this->config->get('bigshop_direct');
        $data['bigshop_direct_url'] = $this->config->get('bigshop_direct_url');
        $data['bigshop_google'] = $this->config->get('bigshop_google');
        $data['bigshop_google_url'] = $this->config->get('bigshop_google_url');
        $data['bigshop_solo'] = $this->config->get('bigshop_solo');
        $data['bigshop_solo_url'] = $this->config->get('bigshop_solo_url');
        $data['bigshop_switch'] = $this->config->get('bigshop_switch');
        $data['bigshop_switch_url'] = $this->config->get('bigshop_switch_url');
        $data['bigshop_western_union'] = $this->config->get('bigshop_western_union');
        $data['bigshop_western_union_url'] = $this->config->get('bigshop_western_union_url');
        $data['bigshop_ebay'] = $this->config->get('bigshop_ebay');

        $data['bigshop_powered'] = $this->config->get('bigshop_powered');

        $data['bigshop_facebook_id'] = $this->config->get('bigshop_facebook_id');
        $data['bigshop_twitter_username'] = $this->config->get('bigshop_twitter_username');
        $data['bigshop_gplus_id'] = $this->config->get('bigshop_gplus_id');
        $data['bigshop_pint_id'] = $this->config->get('bigshop_pint_id');
        $data['bigshop_rss'] = $this->config->get('bigshop_rss');
        $data['bigshop_blogger'] = $this->config->get('bigshop_blogger');
        $data['bigshop_myspace'] = $this->config->get('bigshop_myspace');
        $data['bigshop_linkedin'] = $this->config->get('bigshop_linkedin');
        $data['bigshop_evernote'] = $this->config->get('bigshop_evernote');
        $data['bigshop_dopplr'] = $this->config->get('bigshop_dopplr');
        $data['bigshop_ember'] = $this->config->get('bigshop_ember');
        $data['bigshop_flickr'] = $this->config->get('bigshop_flickr');
        $data['bigshop_picasa_web'] = $this->config->get('bigshop_picasa_web');
        $data['bigshop_youtube'] = $this->config->get('bigshop_youtube');
        $data['bigshop_technorati'] = $this->config->get('bigshop_technorati');
        $data['bigshop_grooveshark'] = $this->config->get('bigshop_grooveshark');
        $data['bigshop_vimeo'] = $this->config->get('bigshop_vimeo');
        $data['bigshop_sharethis'] = $this->config->get('bigshop_sharethis');
        $data['bigshop_yahoobuzz'] = $this->config->get('bigshop_yahoobuzz');
        $data['bigshop_viddler'] = $this->config->get('bigshop_viddler');
        $data['bigshop_skype'] = $this->config->get('bigshop_skype');
        $data['bigshop_google_googletalk'] = $this->config->get('bigshop_google_googletalk');
        $data['bigshop_digg'] = $this->config->get('bigshop_digg');
        $data['bigshop_reddit'] = $this->config->get('bigshop_reddit');
        $data['bigshop_delicious'] = $this->config->get('bigshop_delicious');
        $data['bigshop_stumbleupon'] = $this->config->get('bigshop_stumbleupon');
        $data['bigshop_friendfeed'] = $this->config->get('bigshop_friendfeed');
        $data['bigshop_tumblr'] = $this->config->get('bigshop_tumblr');
        $data['bigshop_yelp'] = $this->config->get('bigshop_yelp');
        $data['bigshop_posterous'] = $this->config->get('bigshop_posterous');
        $data['bigshop_bebo'] = $this->config->get('bigshop_bebo');
        $data['bigshop_virb'] = $this->config->get('bigshop_virb');
        $data['bigshop_last_fm'] = $this->config->get('bigshop_last_fm');
        $data['bigshop_pandora'] = $this->config->get('bigshop_pandora');
        $data['bigshop_mixx'] = $this->config->get('bigshop_mixx');
        $data['bigshop_newsvine'] = $this->config->get('bigshop_newsvine');
        $data['bigshop_openid'] = $this->config->get('bigshop_openid');
        $data['bigshop_readernaut'] = $this->config->get('bigshop_readernaut');
        $data['bigshop_xing_me'] = $this->config->get('bigshop_xing_me');
        $data['bigshop_instagram'] = $this->config->get('bigshop_instagram');
        $data['bigshop_spotify'] = $this->config->get('bigshop_spotify');
        $data['bigshop_forrst'] = $this->config->get('bigshop_forrst');
        $data['bigshop_viadeo'] = $this->config->get('bigshop_viadeo');
        $data['bigshop_vk_com'] = $this->config->get('bigshop_vk_com');

        $data['bigshop_custom_column_footer_status'] = $this->config->get('bigshop_custom_column_footer_status');
        $data['bigshop_custom_column_footer'] = $this->config->get('bigshop_custom_column_footer');
        $data['bigshop_back_to_top'] = $this->config->get('bigshop_back_to_top');
        $data['bigshop_facebook_block_status'] = $this->config->get('bigshop_facebook_block_status');
        $data['bigshop_facebook_box_align'] = $this->config->get('bigshop_facebook_box_align');
        $data['bigshop_facebook_block_sort_order'] = $this->config->get('bigshop_facebook_block_sort_order');
        $data['bigshop_facebook_id_box'] = $this->config->get('bigshop_facebook_id_box');
        $data['bigshop_twitter_block_status'] = $this->config->get('bigshop_twitter_block_status');
        $data['bigshop_twitter_box_align'] = $this->config->get('bigshop_twitter_box_align');
        $data['bigshop_twitter_block_sort_order'] = $this->config->get('bigshop_twitter_block_sort_order');
        $data['twitter_username'] = $this->config->get('twitter_username');
        $data['bigshop_widget_id'] = $this->config->get('bigshop_widget_id');
        $data['bigshop_video_box_status'] = $this->config->get('bigshop_video_box_status');
        $data['bigshop_video_box_align'] = $this->config->get('bigshop_video_box_align');
        $data['bigshop_video_box_sort_order'] = $this->config->get('bigshop_video_box_sort_order');
        $data['bigshop_video_box_content'] = $this->config->get('bigshop_video_box_content');
        $data['bigshop_custom_column_status'] = $this->config->get('bigshop_custom_column_status');
        $data['bigshop_custom_column'] = $this->config->get('bigshop_custom_column');
        $data['bigshop_custom_side_block_align'] = $this->config->get('bigshop_custom_side_block_align');
        $data['bigshop_custom_side_block_sort_order'] = $this->config->get('bigshop_custom_side_block_sort_order');


        $data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

        // Whos Online
        if ($this->config->get('config_customer_online')) {
            $this->load->model('tool/online');

            if (isset($this->request->server['REMOTE_ADDR'])) {
                $ip = $this->request->server['REMOTE_ADDR'];
            } else {
                $ip = '';
            }

            if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
                $url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
            } else {
                $url = 'https://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
            }

            if (isset($this->request->server['HTTP_REFERER'])) {
                $referer = $this->request->server['HTTP_REFERER'];
            } else {
                $referer = '';
            }

            $this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
        }

        return $this->load->view('common/footer', $data);
    }
}
