<?php

class ControllerProductCategory extends Controller
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
        $this->load->language('product/category');

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $data['bigshop_refine_categories'] = $this->config->get('bigshop_refine_categories');
        $data['bigshop_refine_categories_images'] = $this->config->get('bigshop_refine_categories_images');
        $data['bigshop_percentage_discount_badge'] = $this->config->get('bigshop_percentage_discount_badge');
        $data['bigshop_category_product_per_row'] = $this->config->get('bigshop_category_product_per_row');


        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
        } else {
            $limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', $this->ssl)
        );

        if (isset($this->request->get['path'])) {
            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $path = '';

            $parts = explode('_', (string)$this->request->get['path']);
            $category_id = (int)array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = (int)$path_id;
                } else {
                    $path .= '_' . (int)$path_id;
                }

                $category_info = $this->model_catalog_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $path . $url, $this->ssl)
                    );
                }
            }
        } else {
            $category_id = 0;
        }

        $category_info = $this->model_catalog_category->getCategory($category_id);

        if ($category_info) {
            $this->document->setTitle($category_info['meta_title']);
            $this->document->setDescription($category_info['meta_description']);
            $this->document->setKeywords($category_info['meta_keyword']);

            if ($category_info['meta_h1']) {
                $data['heading_title'] = $category_info['meta_h1'];
            } else {

                if ($category_info['meta_h1'] == "") {
                    $data['heading_title'] = $category_info['name'];
                } else {
                    $data['heading_title'] = $category_info['meta_h1'];
                }

            }
            $data['text_select'] = $this->language->get('text_select');
            $data['text_refine'] = $this->language->get('text_refine');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['text_quantity'] = $this->language->get('text_quantity');
            $data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $data['text_model'] = $this->language->get('text_model');
            $data['text_price'] = $this->language->get('text_price');
            $data['text_tax'] = $this->language->get('text_tax');
            $data['text_points'] = $this->language->get('text_points');
            $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
            $data['text_sort'] = $this->language->get('text_sort');
            $data['text_limit'] = $this->language->get('text_limit');

            $data['button_cart'] = $this->language->get('button_cart');
            $data['button_wishlist'] = $this->language->get('button_wishlist');
            $data['button_compare'] = $this->language->get('button_compare');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['button_list'] = $this->language->get('button_list');
            $data['button_grid'] = $this->language->get('button_grid');

            // Set the last category breadcrumb
            $data['breadcrumbs'][] = array(
                'text' => $category_info['name'],
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'], $this->ssl)
            );

            if ($category_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
            } else {
                $data['thumb'] = '';
            }

            $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
            $data['compare'] = $this->url->link('product/compare', '', $this->ssl);

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['categories'] = array();

            $results = $this->model_catalog_category->getCategories($category_id);

            foreach ($results as $result) {
                $filter_data = array(
                    'filter_category_id' => $result['category_id'],
                    'filter_sub_category' => true
                );

                if (isset($result)) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
                }
                $data['categories'][] = array(
                    'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url, $this->ssl), 'thumb' => $image
                );
            }

            $data['products'] = array();

            $filter_data = array(
                'filter_category_id' => $category_id,
                'filter_filter' => $filter,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            );

            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

                    if ($this->currency->has($this->config->get('config_currency2')) && ($this->config->get('config_currency2') != $this->session->data['currency'])) {
                        $price .= ' (' . $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency2')) . ')';
                    }


                    if ($this->currency->has($this->config->get('config_currency2')) && ($this->config->get('config_currency2') != $this->session->data['currency'])) {
                        $price .= ' (' . $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency2')) . ')';
                    }

                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

                    if ($this->currency->has($this->config->get('config_currency2')) && ($this->config->get('config_currency2') != $this->session->data['currency'])) {
                        $special .= ' (' . $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency2')) . ')';
                    }


                    if ($this->currency->has($this->config->get('config_currency2')) && ($this->config->get('config_currency2') != $this->session->data['currency'])) {
                        $special .= ' (' . $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency2')) . ')';
                    }

                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }

                $product_info = $this->model_catalog_product->getProduct($result['product_id']);
                $data['points'] = $product_info['points'];
                $data['price'] = $price;
                $data['special'] = $special;
                $data['tax'] = $tax;

                $discounts = $this->model_catalog_product->getProductDiscounts($result['product_id']);

                $data['discounts'] = array();

                foreach ($discounts as $discount) {
                    $data['discounts'][] = array(
                        'quantity' => $discount['quantity'],
                        'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                    );
                }

                $data['raw_price'] = $data['price'];
                $data['raw_special'] = $data['special'];

                if ($data['price']) {
                    $data['price'] = '<span class=\'autocalc-product-price\'>' . $data['price'] . '</span>';
                }
                if ($data['special']) {
                    $data['special'] = '<span class=\'autocalc-product-special\'>' . $data['special'] . '</span>';
                }
                if ($data['points']) {
                    $data['points'] = '<span class=\'autocalc-product-points\'>' . $data['points'] . '</span>';
                }

                $data['price_value'] = $product_info['price'];
                $data['special_value'] = $product_info['special'];
                $data['tax_value'] = (float)$product_info['special'] ? $product_info['special'] : $product_info['price'];
                $data['points_value'] = $product_info['points'];

                $var_currency = array();
                $currency_code = !empty($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');
                $var_currency['value'] = $this->currency->getValue($currency_code);
                $var_currency['symbol_left'] = $this->currency->getSymbolLeft($currency_code);
                $var_currency['symbol_right'] = $this->currency->getSymbolRight($currency_code);
                $var_currency['decimals'] = $this->currency->getDecimalPlace($currency_code);
                $var_currency['decimal_point'] = $this->language->get('decimal_point');
                $var_currency['thousand_point'] = $this->language->get('thousand_point');
                $data['autocalc_currency'] = $var_currency;

                $currency2_code = $this->config->get('config_currency2');
                if ($this->currency->has($currency2_code) && $currency2_code != $currency_code) {
                    $var_currency = array();
                    $currency_code = $currency2_code;
                    $var_currency['value'] = $this->currency->getValue($currency_code);
                    $var_currency['symbol_left'] = $this->currency->getSymbolLeft($currency_code);
                    $var_currency['symbol_right'] = $this->currency->getSymbolRight($currency_code);
                    $var_currency['decimals'] = $this->currency->getDecimalPlace($currency_code);
                    $var_currency['decimal_point'] = $this->language->get('decimal_point');
                    $var_currency['thousand_point'] = $this->language->get('thousand_point');
                    $data['autocalc_currency2'] = $var_currency;
                }

                $data['dicounts_unf'] = $discounts;

                $data['tax_class_id'] = $product_info['tax_class_id'];
                $data['tax_rates'] = $this->tax->getRates(0, $product_info['tax_class_id']);

                $data['autocalc_option_special'] = $this->config->get('config_autocalc_option_special');
                $data['autocalc_option_discount'] = $this->config->get('config_autocalc_option_discount');
                $data['autocalc_not_mul_qty'] = $this->config->get('config_autocalc_not_mul_qty');
                $data['autocalc_select_first'] = $this->config->get('config_autocalc_select_first');

                $data[$result['product_id']]['options'] = array();
                foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
                    $product_option_value_data = array();
                    foreach ($option['product_option_value'] as $option_value) {
                        if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                            if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                                $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);

                                if ($this->currency->has($this->config->get('config_currency2')) && ($this->config->get('config_currency2') != $this->session->data['currency'])) {
                                    $price .= ' (' . $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency2')) . ')';
                                }


                                if ($this->currency->has($this->config->get('config_currency2')) && ($this->config->get('config_currency2') != $this->session->data['currency'])) {
                                    $price .= ' (' . $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency2')) . ')';
                                }

                            } else {
                                $price = false;
                            }

                            $product_option_value_data[] = array(

                                'price_value' => $option_value['price'],
                                'points_value' => intval($option_value['points_prefix'] . $option_value['points']),

                                'product_option_value_id' => $option_value['product_option_value_id'],
                                'option_value_id' => $option_value['option_value_id'],
                                'name' => $option_value['name'],
                                'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                                'price' => $price,
                                'price_prefix' => $option_value['price_prefix']
                            );
                        }
                    }

                    $data[$result['product_id']]['options'][] = array(
                        'product_option_id' => $option['product_option_id'],
                        'product_option_value' => $product_option_value_data,
                        'option_id' => $option['option_id'],
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'value' => $option['value'],
                        'required' => $option['required']
                    );
                }
                if (array_key_exists(0, $parts) && $parts) {
                    $path_seo = $parts[0];
                } else {
                    $path_seo = $this->request->get['path'];
                }

                $data['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $data['price'],
                    'default_price' => $result['price'],
                    'special' => $data['special'],
                    'tax' => $data['tax'],
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $result['rating'],

                    'model' => $result['model'],
                    'manufacturer' => $result['manufacturer'],

                    //'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url),
                    'saving' => $result['price'] == 0 ? 100 : round((($result['price'] - $result['special']) / $result['price']) * 100, 0),
                    'href' =>
                        count($parts) >= 2
                            ?
                            $this->url->link('product/product', 'path=' . $parts[0] . '_' . $category_id . '&product_id=' . $result['product_id'] . $url, $this->ssl)
                            :
                            $this->url->link('product/product', 'path=' . $path_seo . '&product_id=' . $result['product_id'] . $url, $this->ssl),
                    'options' => $data[$result['product_id']]['options']
                );
            }
            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $data['sorts'][] = array(
                'text' => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url, $this->ssl)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url, $this->ssl)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url, $this->ssl)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url, $this->ssl)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url, $this->ssl)
            );

            if ($this->config->get('config_review_status')) {
                $data['sorts'][] = array(
                    'text' => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url, $this->ssl)
                );

                $data['sorts'][] = array(
                    'text' => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url, $this->ssl)
                );
            }

            $data['sorts'][] = array(
                'text' => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url, $this->ssl)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url, $this->ssl)
            );

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

            sort($limits);

            foreach ($limits as $value) {
                $data['limits'][] = array(
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value, $this->ssl)
                );
            }

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }


            //begin_devos_attribute_ext
            $this->load->model('catalog/devos_attribute_ext');
            $data['products'] = $this->model_catalog_devos_attribute_ext->daeCatalog($data['products'], array('category_id' => $category_id));
            //end_devos_attribute_ext

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}', $this->ssl);

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
            if ($page == 1) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], $this->ssl), 'canonical');
            } elseif ($page == 2) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], $this->ssl), 'prev');
            } else {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page - 1), $this->ssl), 'prev');
            }

            if ($limit && ceil($product_total / $limit) > $page) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page + 1), $this->ssl), 'next');
            }

            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;

            $data['continue'] = $this->url->link('common/home', '', $this->ssl);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('product/category', $data));
        } else {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/category', $url, $this->ssl)
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home', $this->ssl);

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }
}
