<?php

class ControllerExtensionModuleSpecial extends Controller
{
    private $ssl = 'NONSSL';

    public function __construct($registry)
    {
        parent::__construct($registry);

        if ($this->request->server['HTTPS']) {
            $this->ssl = 'SSL';
        }
    }

    public function index($setting)
    {
        $this->load->language('extension/module/special');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_tax'] = $this->language->get('text_tax');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');

        $this->document->addStyle('catalog/view/theme/bigshop/stylesheet/owl.carousel.css');
        $this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
        $this->load->model('catalog/product');

        $this->load->model('tool/image');
        $data['module'] = $setting;

        $data['products'] = array();

        $data['bigshop_specials_slider_product_style'] = $this->config->get('bigshop_specials_slider_product_style');
        $data['bigshop_percentage_discount_badge'] = $this->config->get('bigshop_percentage_discount_badge');
        $data['bigshop_specials_slider_product_per_row'] = $this->config->get('bigshop_specials_slider_product_per_row');


        $filter_data = array(
            'sort' => 'pd.name',
            'order' => 'ASC',
            'start' => 0,
            'limit' => $setting['limit']
        );

        $results = $this->model_catalog_product->getProductSpecials($filter_data);

        if ($results) {
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }

                $data['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    'rating' => $rating,

                    'model' => $result['model'],
                    'manufacturer' => $result['manufacturer'],

                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'], $this->ssl),
                    'saving' => $result['price'] == 0 ? 100 : round((($result['price'] - $result['special']) / $result['price']) * 100, 0)
                );
            }


            //begin_devos_attribute_ext
            $this->load->model('catalog/devos_attribute_ext');
            $data['products'] = $this->model_catalog_devos_attribute_ext->daeCatalog($data['products']);
            //end_devos_attribute_ext

            return $this->load->view('extension/module/special', $data);
        }
    }
}