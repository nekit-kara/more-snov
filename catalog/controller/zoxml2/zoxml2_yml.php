<?php
class ControllerZoXml2ZoXml2Yml extends Controller {
	public function index() {
    $json   = array();
    $json[] = "You do not have permission to access this page!";
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	public function add() {
    $json   = array();
    $json[] = "scan: You do not have permission to access this page!";
    $session_key = '0';
    if (empty($this->request->post['url'])) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'error', data = 'YML - осутствует URL!', user = '" . $this->db->escape($this->request->post['user']) . "'");
      }
    else {
      $data = array(
  			'module'       => $this->db->escape($this->request->post['module']),
  			'name'         => $this->db->escape($this->request->post['name']),
  			'url'          => $this->db->escape($this->request->post['url']),
  			'license'      => '',
        'supplier'     => 'location',
        'before'       => 'zero',
        'images'       => 'main',
        'link'         => 'sku',
        'insert'       => 1,
        'update'       => 1,
        'add_before'   => 0,
        'mul_after'    => 1,
        'add_after'    => 0,
        'before_mode'  => 'supplier',
        );
      $session_key    = 'YML' . md5 ($this->request->post['url']);
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_suppliers SET session_key = '" . $this->db->escape($session_key) . "', data = '" . $this->db->escape(json_encode($data)) ."'");
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape('YML-файл добавлен в список поставщиков.') ."', user = '" . $this->db->escape($this->request->post['user']) . "'");
      }


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	public function scan() {
    $json   = array();
    $json[] = "scan: You do not have permission to access this page!";

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      if (isset($this->request->post['session_key'])) {
        if (empty($this->request->post['url'])) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['session_key']) . "', type = 'error', data = 'YML - осутствует URL!', user = '" . $this->db->escape($this->request->post['user']) . "'");
          }
        else {
          $this->load->model('zoxml2/zoxml2');
          $data = $this->model_zoxml2_zoxml2->getDefSettings ($this->request->post['session_key'],$this->request->post['user']);
          $this->do_scan($data);
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['session_key']) . "', type = 'end', data = '" . $this->db->escape('YML - сканирование завершено.') ."', user = '" . $this->db->escape($this->request->post['user']) . "'");
          }
        }
      else {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'error', data = '" . $this->db->escape(json_encode($this->request->post)) . "', user = 'POST: unknown!'");
        }
      }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	public function do_scan($data) {
    $session_key = $data['session_key'];
    $user        = $data['user'];
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape('YML - начал сканировать фид ' . $data['settings']['url']) ."', user = '" . $this->db->escape($user) . "'");

    libxml_use_internal_errors(true);
    $xml = simplexml_load_file($data['settings']['url']);

    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape('Создание XML-объекта завершено') ."', user = '" . $this->db->escape($user) . "'");
    if (!$xml) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('SimpleXMLElement не смог обработать входной файл!') ."', user = '" . $this->db->escape($user) . "'");
      foreach(libxml_get_errors() as $error) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('Ошибка: ' . $error->message) ."', user = '" . $this->db->escape($user) . "'");
        }
      return null; 
      } 
    if ($data['settings']['tag_shop']&&!isset($xml->$data['settings']['tag_shop'])) { 
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('Ошибка: файл не является YML-файлом! Тег <shop> не найден!') ."', user = '" . $this->db->escape($user) . "'");
      return null; 
      }
    // ------
    $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_categories SET total = '0' WHERE `session_key` = '" . $this->db->escape($session_key) . "'");
    $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_vendors    SET total = '0' WHERE `session_key` = '" . $this->db->escape($session_key) . "'");
    $values = array ();
    $categories = array();
    $params = array ();
    if ($data['settings']['tag_shop']) $info_total = count($xml->$data['settings']['tag_shop']->$data['settings']['tag_offers']->$data['settings']['tag_offer']);    
    else {
      if ($data['settings']['tag_offers']) $info_total = count($xml->$data['settings']['tag_offers']->$data['settings']['tag_offer']);
      else                                 $info_total = count($xml->$data['settings']['tag_offer']);
      }
    $info_processed         = 0;    
    $info_progress          = 0;    
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape("Найдено товаров: " . $info_total . ". Старт обработки") . "', user = '" . $this->db->escape($user) . "'");
    // Категории
    $input_categories = $xml;
    if ($data['settings']['tag_shop'])        $input_categories = $input_categories->$data['settings']['tag_shop'];
    if ($data['settings']['tag_categories'])  $input_categories = $input_categories->$data['settings']['tag_categories'];
    if ($data['settings']['tag_category'])    $input_categories = $input_categories->$data['settings']['tag_category'];
    if ($input_categories&&($data['settings']['tag_shop']||$data['settings']['tag_categories']||$data['settings']['tag_category'])) {
      $txt = "Найдено категорий (всего): " . count($input_categories);
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($user) . "'");
      foreach ($input_categories as $item) {
        $info_processed ++;
        if (++$info_progress==$data['module']['step']) {
          $info_progress = 0; 
          $txt = "Обработано категорий: " . $info_processed;
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($user) . "'");
          }
        if ($item['id']!='') {
          $parent_id    = (isset($item['parentId'])&&$item['parentId']!='')?(string)$item['parentId']:0;  
          $categories[(string)$item['id']] = array( 
            'parent_id'    => $parent_id,  
            'total'        => 0,
            'name'         => (string)$this->db->escape($item),
            'data'         => (string)$item['id'],
            'parent'       => isset($categories[$parent_id])?$categories[$parent_id]['name']:'',
            );
          }
        }
      $txt = "Обработано категорий (всего): " . $info_processed;
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($user) . "'");
      }
    else {
      $txt = "Обработано категорий (всего): категории отсутствуют";
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($user) . "'");
      }
    // Производители и атрибуты
    $info_processed         = 0;    
    $info_progress          = 0;    
    $params['host']      = true;
    $params['offer_id']  = true;
    $params['available'] = true;

    if ($data['settings']['tag_shop']) $offers = $xml->$data['settings']['tag_shop']->$data['settings']['tag_offers']->$data['settings']['tag_offer'];
    else {
      if ($data['settings']['tag_offers']) $offers = $xml->$data['settings']['tag_offers']->$data['settings']['tag_offer'];
      else                                 $offers = $xml->$data['settings']['tag_offer'];
      }
    foreach ($offers as $item) {
      $info_processed ++;
      if (++$info_progress==$data['module']['step']) {
        $info_progress = 0; 
        $txt = "Обработано товаров: " . $info_processed;
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($user) . "'");
        }
      foreach ($item as $key => $value) if ($key!='params'&&$key!='param'&&$key!='specs') $params[$key] = true;
      if (isset($item->params)) foreach ($item->params as $item_value)      if (isset($item_value['name'])) $params['params_' . $item_value['name']] = true;
      if (isset($item->param))  foreach ($item->param  as $item_value)      if (isset($item_value['name'])) $params['param_'  . $item_value['name']] = true;
      if (isset($item->specs))  foreach ($item->specs->spec as $item_value) if (isset($item_value->name))   $params['spec_'   . $item_value->name]   = true;
      $cat_name     = '';
      $par_cat_name = '';
      $vendor       = '';
      if (isset($item->vendor)) $vendor = (string)$item->vendor;
      foreach ($item as $key => $value) {
        $option_key = $key;
        if ($key=='params') if (isset($value['name'])) $option_key = 'params_' . $value['name'];   
        if ($key=='param')  if (isset($value['name'])) $option_key = 'param_'  . $value['name'];   
        if (isset($data['options'][$option_key])&&$data['options'][$option_key]['dest_type']=='vendor')       $vendor = (string)$value;
        if (isset($data['options'][$option_key])&&$data['options'][$option_key]['dest_type']=='cat_name')     $cat_name = (string)$value;
        if (isset($data['options'][$option_key])&&$data['options'][$option_key]['dest_type']=='par_cat_name') $par_cat_name = (string)$value;
        if ($key=='specs')  {
          foreach ($value->spec as $item_value) {
            $option_key = 'spec_';
            if (isset($item_value->name)) $option_key .= (string)$item_value->name;
            if (isset($data['options'][$option_key])&&$data['options'][$option_key]['dest_type']=='vendor') $vendor = (string)$item_value->value;
            }
          }
        }
      if (!$vendor) $vendor = '(производитель не указан)';
      if ($vendor) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_vendors WHERE name = '" . $this->db->escape($vendor) . "' AND `session_key` = '" . $this->db->escape($session_key) . "'");
        if ($query->row) {
          $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_vendors SET total = (total + 1) WHERE name = '" . $this->db->escape($vendor) . "' AND `session_key` = '" . $this->db->escape($session_key) . "'");
          }
        else {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_vendors SET   session_key = '" . $this->db->escape($session_key) . "', `name` = '" . $this->db->escape($vendor) . "', `total` = '1'");
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_events  SET   session_key = '" . $this->db->escape($session_key) . "', type = 'new brand', data = '" . $this->db->escape('Новый производитель: ' . $vendor) ."'");
          }
        }
      // Добавляем псевдо-производителя HOST
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_vendors WHERE name = 'host' AND `session_key` = '" . $this->db->escape($session_key) . "'");
      if ($query->row) {
        $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_vendors SET total = (total + 1) WHERE name = 'host' AND `session_key` = '" . $this->db->escape($session_key) . "'");
        }
      else {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_vendors SET   session_key = '" . $this->db->escape($session_key) . "', `name` = 'host', `total` = '1'");
        }
      // Категории
      if (isset($item->categoryId)) {
        $categoryId = (int)$item->categoryId;
        if (isset($categories[$categoryId])) $categories[$categoryId]['total'] ++;
        else {
          $offer_id         = isset($item['id'])?(int)$item['id']:0;
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'warning', data = '" . $this->db->escape('Предупреждение: товар ' . $offer_id . ' ссылается не несуществующую категорию - ' . $categoryId) ."', user = '" . $this->db->escape($user) . "'");
          }
        }
      else {
        if ($cat_name) {
          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_categories WHERE name = '" . $this->db->escape($cat_name) . "' AND parent = '" . $this->db->escape($par_cat_name) . "' AND `session_key` = '" . $this->db->escape($session_key) . "'");
          if ($query->row) {
            $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_categories SET total = (total + 1) WHERE name = '" . $this->db->escape($cat_name) . "' AND parent = '" . $this->db->escape($par_cat_name) . "' AND `session_key` = '" . $this->db->escape($session_key) . "'");
            }
          else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_categories SET session_key = '" . $this->db->escape($session_key) . "', `name` = '" . $this->db->escape($cat_name) . "', `parent` = '" . $this->db->escape($par_cat_name) . "', `total` = '1'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_events     SET session_key = '" . $this->db->escape($session_key) . "', type = 'new category', data = '" . $this->db->escape('Новая категория: ' . $par_cat_name . ' -> ' . $cat_name) ."'");
            }
          }
        }
      }
    $txt = "Обработано товаров (всего): " . $info_processed;
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($user) . "'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape('Сохранение данных в БД') . "', user = '" . $this->db->escape($user) . "'");
    // СБРОС В БАЗУ
    foreach ($categories as $item) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_categories WHERE name = '" . $this->db->escape($item['name']) . "' AND parent = '" . $this->db->escape($item['parent']) . "' AND `data` = '" . $this->db->escape($item['data']) . "' AND `session_key` = '" . $this->db->escape($session_key) . "'");
        if (!$query->row) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_categories SET session_key = '" . $this->db->escape($session_key) . "', `name` = '" . $this->db->escape($item['name']) . "', `parent` = '" . $this->db->escape($item['parent']) . "', `data` = '" . $this->db->escape($item['data']) . "', `total` = '" . $this->db->escape($item['total']) . "'");
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_events     SET session_key = '" . $this->db->escape($session_key) . "', type = 'new category', data = '" . $this->db->escape('Новая категория: ' . $item['parent'] . ' -> ' . $item['name']) . "ID: " . $item['data'] . "'");
          }
        else {
          $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_categories SET total = '" . $this->db->escape($item['total']) . "' WHERE name = '" . $this->db->escape($item['name']) . "' AND parent = '" . $this->db->escape($item['parent']) . "' AND `session_key` = '" . $this->db->escape($session_key) . "'");
          }
        }
    foreach ($params as $key => $value) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_options WHERE `name` = '" . $this->db->escape($key) . "' AND `session_key` = '" . $this->db->escape($session_key) . "'");
      if (!$query->row) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_options SET   session_key = '" . $this->db->escape($session_key) . "', `name` = '" . $this->db->escape($key) . "', `dest_type` = 'attr', `data` = '" . $this->db->escape($key) . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_events  SET   session_key = '" . $this->db->escape($session_key) . "', type = 'new option', data = '" . $this->db->escape('Новый параметр: ' . $key) ."'");
        }
      }
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'end', data = '" . $this->db->escape('Сохранение данных в БД завершено!') . "', user = '" . $this->db->escape($user) . "'");
    }

	public function load() {
    $json   = array();
    $json[] = "load: You do not have permission to access this page!";

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      if (isset($this->request->post['session_key'])) {
        $this->load->model('zoxml2/zoxml2');
        $this->load->model('zoxml2/zoxml2filters');
        $this->load->model('zoxml2/zoxml2usescript');
        $data = $this->model_zoxml2_zoxml2->getDefSettings ($this->request->post['session_key'],$this->request->post['user']);
        $this->model_zoxml2_zoxml2->before($this->request->post['session_key'],$data,$this->request->post['user']);
        $this->do_load($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['session_key']) . "', type = 'end', data = '" . $this->db->escape('YML - загрузка завершена.') ."', user = '" . $this->db->escape($this->request->post['user']) . "'");
        }
      else {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'error', data = '" . $this->db->escape(json_encode($this->request->post)) . "', user = 'POST: unknown!'");
        }
      }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	public function do_load($data) {
    $session_key = $data['session_key'];

    if (!$data['settings']['url']) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = 'YML - осутствует URL!', user = '" . $this->db->escape($data['user']) . "'");
      return null; 
      }
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape('YML - начал загружать фид ' . $data['settings']['url']) ."', user = '" . $this->db->escape($data['user']) . "'");

    libxml_use_internal_errors(true);
    $xml = simplexml_load_file($data['settings']['url']);

    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape('Создание XML-объекта завершено') ."', user = '" . $this->db->escape($data['user']) . "'");
    if (!$xml) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('SimpleXMLElement не смог обработать входной файл!') ."', user = '" . $this->db->escape($data['user']) . "'");
      foreach(libxml_get_errors() as $error) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('Ошибка: ' . $error->message) ."', user = '" . $this->db->escape($data['user']) . "'");
        }
      return null; 
      } 
    if ($data['settings']['tag_shop']&&!isset($xml->$data['settings']['tag_shop']))        { 
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('Ошибка: файл не является YML-файлом! Тег <shop> не найден!') ."', user = '" . $this->db->escape($data['user']) . "'");
      return null; 
      }
    // ------

    $stock_status_id        = 5;
    $subtract               = 1;

    if ($data['settings']['tag_shop']) $info_total = count($xml->$data['settings']['tag_shop']->$data['settings']['tag_offers']->$data['settings']['tag_offer']);    
    else {
      if ($data['settings']['tag_offers']) $info_total = count($xml->$data['settings']['tag_offers']->$data['settings']['tag_offer']);
      else                                 $info_total = count($xml->$data['settings']['tag_offer']);
      }

    $info_insert            = 0;    
    $info_update            = 0;    
    $info_processed         = 0;    
    $info_progress          = 0;    
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape("Найдено товаров: " . $info_total . ". Старт обработки") . "', user = '" . $this->db->escape($data['user']) . "'");

    if ($data['settings']['tag_shop']) $offers = $xml->$data['settings']['tag_shop']->$data['settings']['tag_offers']->$data['settings']['tag_offer'];
    else {
      if ($data['settings']['tag_offers']) $offers = $xml->$data['settings']['tag_offers']->$data['settings']['tag_offer'];
      else                                 $offers = $xml->$data['settings']['tag_offer'];
      }
    foreach ($offers as $item) {
      $info_processed ++;
      if (++$info_progress==$data['module']['step']) {
        $info_progress = 0; 
        set_time_limit(20);
        $txt = "Обработано: " . $info_processed;
        $txt .= "  Добавлено: " . $info_insert;
        $txt .= "  Обновлено: " . $info_update;
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($data['user']) . "'");
        }
     // ОПЦИИ
      $output = $this->model_zoxml2_zoxml2->getDefOutput ($data);
      if (isset($item->description)) $output['description'] = $this->model_zoxml2_zoxml2->arrDamp ((array) $item->description);
      $output['sku']            = isset($item['id'])?(string)$item['id']:'';
      $output['model']          = isset($item->model)?(string)$item->model:''; 
      $output['price']          = isset($item->price)?(float)$item->price:''; 
      $output['name']           = isset($item->name)?(string)$item->name:''; 
      $output['vendor']         = isset($item->vendor)?(string)$item->vendor:''; 
      $output['location']       = $data['settings']['supplier']=='location'?$session_key:''; 
//      foreach ($item->picture as $next_img) $output['image'][] = (string)$next_img;
      $cat_id                   = (string)$item->categoryId;
      $status                   = 1;
      if (isset($data['module']['DEBUG'])) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("Товар sku: " . $output['sku'] . ". Старт обработки") . "', user = '" . $this->db->escape($data['user']) . "'");
        }
      //  param_name & param_value  
      foreach ($item as $option_key => $value) {
        if ($option_key=='params') {
            if (isset($value['name'])) {
              $output = $this->model_zoxml2_zoxml2->doParams('params_' . $value['name'],$output,$data,$value);
              }
          continue;
          } 
        if ($option_key=='param') {
            if (isset($value['name'])) {
              $output = $this->model_zoxml2_zoxml2->doParams('param_' . $value['name'],$output,$data,$value);
              }
          continue;
          } 
        if ($option_key=='specs') {
          foreach ($value->spec as $item_value) {
            if (isset($item_value->name)) {
              $output = $this->model_zoxml2_zoxml2->doParams('spec_'  . $item_value->name,$output,$data,$item_value->value);
              }
            }
          continue;
          }
        $output = $this->model_zoxml2_zoxml2->doParams($option_key,$output,$data,$value);
        }
      if (!$output['vendor'])   $output['vendor'] = '(производитель не указан)';
      $output = $this->model_zoxml2_zoxml2->doParams('host',$output,$data,'host');
      $output = $this->model_zoxml2_zoxml2->doParams('offer_id',$output,$data,$output['sku']);
      $output = $this->model_zoxml2_zoxml2->doParams('available',$output,$data,isset($item['available'])?(string)$item['available']:'');
      $output = $this->model_zoxml2_zoxml2->doReplase($output,$data);
      $output['price']   += $data['settings']['add_before'];
      $output['price']   *= $data['settings']['mul_after'];
      $output['price']   += $data['settings']['add_after'];
      if (isset($output['oldprice'])) {
        $output['oldprice']   += $data['settings']['add_before'];
        $output['oldprice']   *= $data['settings']['mul_after'];
        $output['oldprice']   += $data['settings']['add_after'];
        $output['special']     = $output['price'];
        $output['price']       =  $output['oldprice'];
        }
      $output = $this->model_zoxml2_zoxml2->doMeta($output,$data); // ШАБЛОНЫ
      // Блок проверок
      if (!$output['name']&&($data['settings']['link'] == 'name')) {
        if (isset($data['module']['DEBUG'])) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("Товар №: " . $info_processed . ". Пропуск - name") . "', user = '" . $this->db->escape($data['user']) . "'");
          }
        continue; 
        }
      if (!$output['sku']&&($data['settings']['link'] == 'sku')) {
        if (isset($data['module']['DEBUG'])) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("Товар №: " . $info_processed . ". Пропуск - sku: ") . "', user = '" . $this->db->escape($data['user']) . "'");
          }
        continue; 
        }
      if (!$output['model'] &&($data['settings']['link'] == 'model')) {
        if (isset($data['module']['DEBUG'])) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("Товар №: " . $info_processed . ". Пропуск - model") . "', user = '" . $this->db->escape($data['user']) . "'");
          }
        continue; 
        }
      if (!$output['vendor']) {
        if (isset($data['module']['DEBUG'])) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("Товар №: " . $info_processed . ". Пропуск - vendor") . "', user = '" . $this->db->escape($data['user']) . "'");
          }
        continue; 
        }
//$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape(json_encode($output))     . "', user = '" . $this->db->escape($data['user']) . "'");      if (!$cat_id||!$output['cat_name']) {
      if (!$cat_id&&!$output['cat_name']) {
        if (isset($data['module']['DEBUG'])) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("Товар №: " . $info_processed . ". Пропуск - cat_id") . "', user = '" . $this->db->escape($data['user']) . "'");
          }
        continue; 
        }
      // Обрабатываем запись
      usleep ($data['module']['sleep']);
      $manufacturer_id = $this->model_zoxml2_zoxml2->getVendor($session_key,$output['vendor']);
      $category_id     = 0;
      if ($cat_id>0)                $category_id     = $this->model_zoxml2_zoxml2->getCategoryByID($session_key,$cat_id);
      else if ($output['cat_name']) $category_id     = $this->model_zoxml2_zoxml2->getCategory($session_key,$output['cat_name']);
      if (!$manufacturer_id || !$category_id) {
        if (isset($data['module']['DEBUG'])) {
          if (!$manufacturer_id) $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("sku: " . $output['sku'] . ". Пропуск. manufacturer_id=0") . "', user = '" . $this->db->escape($data['user']) . "'");
          if (!$category_id)     $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("sku: " . $output['sku'] . ". Пропуск. category_id=0")     . "', user = '" . $this->db->escape($data['user']) . "'");
          }
        continue; 
        }
      // ПОЛЬЗОВАТЕЛЬСКИЙ ФИЛЬТР
      if (!empty($data['settings']['user_filter'])) {
        $output = $this->model_zoxml2_zoxml2filters->doUserFilter($output,$data,$data['settings']['user_filter'],$item);
        if (!$output) {
          if (isset($data['module']['DEBUG'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("sku: " . $output['sku'] . ". Пропуск. Пользовательский фильтр")     . "', user = '" . $this->db->escape($data['user']) . "'");
            }
          continue;
          } 
        }
      // Производитель и категория известны - обрабатываем товар
      if ($output['quantity']<=0&&$data['settings']['hide']) $status = 0;
      $sql = $this->model_zoxml2_zoxml2->doSQL($output,$data,$manufacturer_id);
      $query = $this->db->query($sql);
      // ---------------------------------------------------------------------
      if ($query->row) { // Обновление продукта
        if (count($query->rows)>1) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'warning', data = '" . $this->db->escape("sql запрос: " . $sql . " вернул более 1-й строки!") . "', user = '" . $this->db->escape($data['user']) . "'");
          }
        if (!isset($data['settings']['update'])) continue;
        if (isset($data['settings']['no_update'])&&$query->row['status']==0) continue;
        // Обновление разрешено!
        $product_id = $query->row['product_id'];
        // КАТЕГОРИЯ
        if (isset($data['settings']['update_category'])) {
          $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
          $this->model_zoxml2_zoxml2->link2category_id ($data['module']['engine'],$product_id,$category_id,isset($data['module']['do_category_up'])?true:false);
          }
        // ПРОДУКТ
        $sql = "UPDATE " . DB_PREFIX . "product SET status = '" . (int)$status . "', date_modified = NOW()";
        if (isset($data['settings']['update_price']))    $sql .= ", price = '" . (float)$output['price'] . "'";
        if (isset($data['settings']['update_quantity'])) $sql .= ", quantity = '" . (int)$output['quantity'] . "'";
        if (isset($data['settings']['update_vendor']))   $sql .= ", manufacturer_id = '" . (int)$manufacturer_id . "'";
        if (isset($data['settings']['update_sku']))      $sql .= ", sku = '" . $this->db->escape($output['sku']) . "'";
        if (isset($data['settings']['update_model']))    $sql .= ", model = '" . $this->db->escape($output['model']) . "'";
        
        $sql .= " WHERE product_id = '" . (int)$product_id . "'";
	      $this->db->query($sql);
        if (isset($data['settings']['update_price'])) {
    	    $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
          if (isset($output['special'])) {
            foreach ($data['customer_groups'] as $customer_group_id) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', priority = '1', price = '" . (float)$output['special'] . "'");
              }
            }
          }
        // МЕТА
        if (isset($data['settings']['update_name'])) {
		      $this->db->query("UPDATE " . DB_PREFIX . "product_description SET name = '" . $this->db->escape($output['name']) ."' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get( 'config_language_id' ) . "'");
          }
        if (isset($data['settings']['update_description'])) {
		      $this->db->query("UPDATE " . DB_PREFIX . "product_description SET description = '" . $this->db->escape($output['description']) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get( 'config_language_id' ) . "'");
          }
        if ($data['settings']['images']!='nop' && isset($data['settings']['update_image']) && isset($output['image'][0])) {
          $data = $this->model_zoxml2_zoxml2->loadImage ($output['image'][0], $data, $category_id);
		      $this->db->query("UPDATE " . DB_PREFIX . "product SET image  = '" . $this->db->escape($data['dest_image']) . "' WHERE product_id = '" . (int)$product_id . "'");
          if ($data['settings']['images']=='all' && isset($data['settings']['update_image']) && isset($output['image'][1])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
            $order = 0;
            foreach ($output['image'] as $next_img) {
              if (!$order++) continue;
              $data = $this->model_zoxml2_zoxml2->loadImage ($next_img, $data, $category_id);
              if ($data['dest_image']) $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($data['dest_image']) . "', sort_order = '" . (int)$order . "'");
              }
            }
          }
        if (isset($data['settings']['update_atributes'])) {
          $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
          foreach ($output['attr'] as $attribute_id => $text) {
  					$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$this->config->get( 'config_language_id' ) . "', text = '" .  $this->db->escape($text) . "'");
            }
          }
        $info_update ++;
        }
      else {             // Добавление продукта
        if (!isset($data['settings']['insert'])) continue;
        if (isset($data['settings']['not_empty_only'])&&$output['quantity']<=0) continue;
        // Добавление разрешено!
        $data['dest_image'] = '';
        if ($data['settings']['images']!='nop' && isset($output['image'][0])) $data = $this->model_zoxml2_zoxml2->loadImage ($output['image'][0], $data, $category_id);
        if ($data['settings']['hide_new']==1) $status = 0;
        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET 
               model = '" .           $this->db->escape($output['model'] ) . "', 
               sku = '" .             $this->db->escape($output['sku']) . "', 
               location = '" .        $output['location'] . "', 
               quantity = '" .        $output['quantity'] . "', 
               minimum = '1', 
               subtract = '" .        $subtract . "', 
               image  = '" .          $this->db->escape($data['dest_image']) . "',
               stock_status_id = '" . $stock_status_id . "', 
               date_available = NOW(), 
               manufacturer_id = '" . $manufacturer_id . "', 
               shipping = '1', 
               price = '" .           $output['price'] . "', 
               points = '0', 
               weight = '" .          $output['weight'] . "', 
               weight_class_id = '" . $this->config->get( 'config_weight_class' ) . "', 
               length = '0', 
               width = '0', 
               height = '0', 
               length_class_id = '" . $this->config->get( 'config_length_class' ) . "', 
               status = '" .          $status . "', 
               tax_class_id = '0', 
               sort_order = '0', 
               date_added = NOW()");
        $product_id = $this->db->getLastId();
        // АКЦИИ
        if (isset($output['special'])) {
          foreach ($data['customer_groups'] as $customer_group_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', priority = '1', price = '" . (float)$output['special'] . "'");
            }
          }
        // Атрибуты
        foreach ($output['attr'] as $attribute_id => $text) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$this->config->get( 'config_language_id' ) . "', text = '" .  $this->db->escape($text) . "'");
          }
        // дополнительные изображения
        $order = 0;
        foreach ($output['image'] as $next_img) {
          if (!$order++) continue;
          $data = $this->model_zoxml2_zoxml2->loadImage ($next_img, $data, $category_id);
          if ($data['dest_image']) $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($data['dest_image']) . "', sort_order = '" . (int)$order . "'");
          }
        // Описание товара
        $sql = "INSERT INTO " . DB_PREFIX . "product_description SET 
                product_id = '" .       (int)$product_id . "', 
                language_id = '" .      (int)$this->config->get( 'config_language_id' ) . "', 
                name = '" .             $this->db->escape($output['name']) . "',";
        if (isset($output['meta_keyword']))     $sql .= "meta_keyword = '" . $this->db->escape($output['meta_keyword']) . "',";         
        if (isset($output['meta_description'])) $sql .= "meta_description = '" . $this->db->escape($output['meta_description']) . "',";         
        if (isset($output['meta_title']))       $sql .= "meta_title = '" . $this->db->escape($output['meta_title']) . "',";         
        if (isset($output['meta_h1']))          $sql .= "meta_h1 = '" . $this->db->escape($output['meta_h1']) . "',";         
        if (isset($output['tag']))              $sql .= "tag = '" . $this->db->escape($output['tag']) . "',"; 
        $sql .= "description = '" .      $this->db->escape($output['description']) . "'";        
        $this->db->query($sql);

        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET 
                product_id = '" .       (int)$product_id . "', 
                store_id = '0'");
        $this->model_zoxml2_zoxml2->link2category_id ($data['module']['engine'],$product_id,$category_id,isset($data['module']['do_category_up'])?true:false);
        $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                query = 'product_id=" . (int)$product_id . "', 
                keyword = '" . $this->model_zoxml2_zoxml2->translit ($output['name'],'') . "'");
        $info_insert ++;
        }
      // ПОЛЬЗОВАТЕЛЬСКИЙ СКРИТП
      if (!empty($data['settings']['update_use_script'])) {
        $this->model_zoxml2_zoxml2usescript->doUserScript($output,$data,$data['settings']['update_use_script'],$item,$product_id);
        }
      // Общие действия
		  $this->cache->delete('product');
      // Общие действия - конец
      }
    // Завершающее сообщение
    $txt = "Обработано: " . $info_processed;
    $txt .= "  Добавлено: " . $info_insert;
    $txt .= "  Обновлено: " . $info_update;
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'end', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($data['user']) . "'");
    }

	public function link() {
    $json   = array();
    $json[] = "scan: You do not have permission to access this page!";

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      if (isset($this->request->post['session_key'])) {
        if (empty($this->request->post['url'])) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['session_key']) . "', type = 'error', data = 'YML - осутствует URL!', user = '" . $this->db->escape($this->request->post['user']) . "'");
          }
        else {
          $url = $this->request->post['url']; 
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['session_key']) . "', type = 'info', data = '" . $this->db->escape('YML - начал загружать фид ' . $url) ."', user = '" . $this->db->escape($this->request->post['user']) . "'");
          $this->load->model('zoxml2/zoxml2');
          $data = $this->model_zoxml2_zoxml2->getDefSettings ($this->request->post['session_key'],$this->request->post['user']);
          $this->do_link($data,$url,$this->request->post['session_key'],$this->request->post['user']);
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['session_key']) . "', type = 'end', data = '" . $this->db->escape('YML - загрузка завершена.') ."', user = '" . $this->db->escape($this->request->post['user']) . "'");
          }
        }
      else {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'error', data = '" . $this->db->escape(json_encode($this->request->post)) . "', user = 'POST: unknown!'");
        }
      }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	public function do_link($data,$url,$session_key,$user) {
    libxml_use_internal_errors(true);
    $xml = simplexml_load_file($url);

    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape('Создание XML-объекта завершено') ."', user = '" . $this->db->escape($user) . "'");
    if (!$xml) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('SimpleXMLElement не смог обработать входной файл!') ."', user = '" . $this->db->escape($user) . "'");
      foreach(libxml_get_errors() as $error) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('Ошибка: ' . $error->message) ."', user = '" . $this->db->escape($user) . "'");
        }
      return null; 
      } 
    if ($data['settings']['tag_shop']&&!isset($xml->$data['settings']['tag_shop']))        { 
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'error', data = '" . $this->db->escape('Ошибка: файл не является YML-файлом! Тег <shop> не найден!') ."', user = '" . $this->db->escape($user) . "'");
      return null; 
      }
    // ------
    if ($data['settings']['tag_shop']) $info_total = count($xml->$data['settings']['tag_shop']->$data['settings']['tag_offers']->$data['settings']['tag_offer']);    
    else {
      if ($data['settings']['tag_offers']) $info_total = count($xml->$data['settings']['tag_offers']->$data['settings']['tag_offer']);
      else                                 $info_total = count($xml->$data['settings']['tag_offer']);
      }

    $info_processed         = 0;    
    $info_progress          = 0;    
    $info_linked            = 0;    
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape("Найдено товаров: " . $info_total . ". Старт обработки") . "', user = '" . $this->db->escape($user) . "'");

    if ($data['settings']['tag_shop']) $offers = $xml->$data['settings']['tag_shop']->$data['settings']['tag_offers']->$data['settings']['tag_offer'];
    else {
      if ($data['settings']['tag_offers']) $offers = $xml->$data['settings']['tag_offers']->$data['settings']['tag_offer'];
      else                                 $offers = $xml->$data['settings']['tag_offer'];
      }
    foreach ($offers as $item) {
      if ($info_progress==$data['module']['step']) {
        set_time_limit(20);
        $info_progress = 0; 
        $txt = "Обработано: " . $info_processed;
        $txt .= "  Привязано: " . $info_linked;
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($user) . "'");
        }
      // Обрабатываем запись
      usleep ($data['module']['sleep']);
      $info_processed ++;
      if (isset($data['module']['DEBUG'])) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'DEBUG', data = '" . $this->db->escape("Товар №: " . $info_processed . ". Старт обработки") . "', user = '" . $this->db->escape($user) . "'");
        }
      $info_progress  ++;
      $sku                    = '';  
      $model                  = isset($item->model) ?(string)$item->model:'';  
      $vendor                 = isset($item->vendor)?(string)$item->vendor:'';  
      $name                   = isset($item->name)  ?(string)$item->name:'';  
      //  param_name & param_value  
      foreach ($item as $key => $value) {
        $option_key = $key;
        if ($key=='params') if (isset($value['name'])) $option_key = 'params_' . $value['name'];   
        if ($key=='param')  if (isset($value['name'])) $option_key = 'param_'  . $value['name'];   
        if (!isset($data['options'][$option_key])) continue; 
        switch ($data['options'][$option_key]['dest_type']) {
          case 'vendor':        { $vendor = (string)$value; break; } 
          case 'model':         { $model  = (string)$value; break; } 
          case 'sku':           { $sku    = (string)$value; break; } 
          case 'name':          { $name   = (string)$value; break; } 
          }
        }
      if (isset($data['options']['host'])&&$data['options']['host']['dest_type']=='vendor') $vendor = 'host';
      if (isset($data['options']['offer_id'])&&$data['options']['offer_id']['dest_type']=='sku')   $sku      = isset($item['id'])?(string)$item['id']:0;
      if (isset($data['options']['offer_id'])&&$data['options']['offer_id']['dest_type']=='model') $model    = isset($item['id'])?(string)$item['id']:0;
      $manufacturer_id = $this->model_zoxml2_zoxml2->getVendor($session_key,$vendor);
     // Формируем поисковый запрос
      $where = '';
      $sql   = "SELECT * FROM " . DB_PREFIX . "product p ";
      if ($data['settings']['supplier']=='mcg2') {
        $sql .= " LEFT JOIN  " . DB_PREFIX . "product_to_supplier p2s ON (p.product_id = p2s.product_id)";
        }
      if ($data['settings']['link']=='name') {
        $sql .= " LEFT JOIN  " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
        }
      if ($data['settings']['link'] == 'sku') {
        if ($where) $where .= " AND ";
        $where .= "p.sku = '" . $this->db->escape($sku) . "'";
        }
      if ($data['settings']['link'] == 'name') {
        if ($where) $where .= " AND ";
        $where .= "pd.name = '" . $this->db->escape($name) . "'";
        $where .= " AND pd.language_id = '" . (int)$this->config->get( 'config_language_id' ) . "'";
        }
      if ($data['settings']['link'] == 'model') {
        if ($where) $where .= " AND ";
        $where .= "p.model = '" . $this->db->escape($model) . "'";
        }
      if (isset($data['settings']['link_vendor'])) {
        if ($where) $where .= " AND ";
        $where .= "p.manufacturer_id = '" . $this->db->escape($manufacturer_id) . "'";
        }
      $sql .= " WHERE " . $where . " GROUP BY p.product_id";
      $query = $this->db->query($sql);
      // ---------------------------------------------------------------------
      if ($query->row) { // Привязка продукта
        if (count($query->rows)>1) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'warning', data = '" . $this->db->escape("sql запрос: " . $sql . " вернул более 1-й строки!") . "', user = '" . $this->db->escape($user) . "'");
          }
        foreach ($query->rows as $value) {
          $product_id = $value['product_id'];
          if ($data['settings']['supplier']=='location') {  
            if ($value['location']&&$value['location']!=$session_key) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'warning', data = '" . $this->db->escape("Товар: " . $name . " уже привязан к другому поставщику! пропуск...") . "', user = '" . $this->db->escape($user) . "'");
              }
            else {
  		        $this->db->query("UPDATE " . DB_PREFIX . "product SET location = '" . $this->db->escape($session_key) . "' WHERE product_id = '" . (int)$product_id . "'");
              $info_linked ++;
              }
            } 
          if ($data['settings']['supplier']=='mcg2') {
            // Доделать!
            } 
          }
        }
      }
    // Завершающее сообщение
    $txt = "Обработано: " . $info_processed;
    $txt .= "  Привязано: " . $info_linked;
    $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'end', data = '" . $this->db->escape($txt) . "', user = '" . $this->db->escape($user) . "'");
    }


}
?>