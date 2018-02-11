<?php
################################################################################################
#  СТРУКТУРА ДАННЫХ ПОСТАВЩИКА
#  
#   supplier[session_key][settings]     - массив общих настроек (сохраняется в zoxml2_suppliers)
#   supplier[session_key][vendors]      - массив поставщиков и их настроек
#   supplier[session_key][categories]   - массив категорий и их настроек 
#   supplier[session_key][options]      - массив атрибутов\опций  и их настроек
#   supplier[session_key][log]          - массив истории операций
#  
#   supplier[session_key][settings]     - массив общих настроек (детализация с примером)
#   			'module'       => "zoxml2_textiloptom",
#   			'name'         => "Текстиль оптом",
#   			'session_key'  => 'textiloptom',
#   			'url'          => 'http://api.textiloptom.net/v4/api/productsVal.xml?api_key=',
#   			'license'      => 'f6913b4b400c260f4015a50f869a11fc',
################################################################################################
# ТАБЛИЦА В БД            - zoxml2_module
# id                      - уникальный id строки 
# key                     - ключ массива
# data                    - данные (JSON)
#  
# ТАБЛИЦА В БД            - zoxml2_suppliers
# id                      - уникальный id строки 
# session_key             - идентификатор поставщика
# data                    - данные (array)
#  
# ТАБЛИЦА В БД            - zoxml2_vendors
# id                      - уникальный id строки 
# session_key             - идентификатор поставщика
# name                    - оригинальное название производителя у поставщика
# manufacturer_id         - id производителя в БД 
# total                   - кол-во товаров
# data                    - прочие данные (опционально)
#  
# ТАБЛИЦА В БД            - zoxml2_categories
# id                      - уникальный id строки 
# session_key             - идентификатор поставщика
# name                    - оригинальное название категории у поставщика        
# parent                  - родитель категории у поставщика        
# category_id             - id категории в БД 
# total                   - кол-во товаров
# data                    - прочие данные (опционально)
#  
# ТАБЛИЦА В БД            - zoxml2_options
# id                      - уникальный id строки 
# session_key             - идентификатор поставщика
# name                    - оригинальное название опции у поставщика        
# dest_type               - пусто - игнорировать, option, attr, o_description, o_sku, country, sliv, ym, iprice       
# dest_id                 - id опции\атрибута в БД 
# data                    - прочие данные (опционально)
#  
# ТАБЛИЦА В БД            - zoxml2_replace
# id                      - уникальный id строки 
# session_key             - идентификатор поставщика
# sort_order              - порядок использования 
# type                    - тип
# txt_before              - искомая строка       
# txt_after               - подстановка     
#  
# ТАБЛИЦА В БД            - zoxml2_log
# id                      - уникальный id строки 
# session_key             - идентификатор поставщика
# time                    - метка времени события
# user                    - user
# data                    - данные
################################################################################################
class ControllerModuleZoXml2 extends Controller {
	private $error = array();

	public function index() {
    $data['VERSION']    = 0;
    if (defined('VERSION')) {
      $parts     = explode(".",VERSION);
      $data['VERSION']    = isset($parts[0])?$parts[0]:0;
      }

  
		$this->load->language('module/zoxml2');
		$this->document->setTitle($this->language->get('heading_title2'));
		$data['heading_title']  = $this->language->get('heading_title2');
		$data['button_cancel']  = $this->language->get('button_cancel');
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title2'),
			'href' => $this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['token']  = $this->session->data['token'];

// -----------------------------------------------------------------------------
$data['module'] = array ();
$data['module']['engine'] = 'Opencart';
$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_module");
foreach ($query->rows as $result) $data['module'][$result['key']] = json_decode ($result['data']);
// -----------------------------------------------------------------------------
$data['extensions']  = $this->extensions();

$data['suppliers'] = array();
$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_suppliers");
foreach ($query->rows as $result) {
  $value = json_decode ($result['data']);
  $data['suppliers'][$result['session_key']]['settings'] = array(              
  			'module'       => $value->module,
  			'name'         => $value->name,
  			'url'          => $value->url,
  			'license'      => $value->license,
  			'session_key'  => $result['session_key'],
        'supplier'     => $value->supplier,
        'before'       => $value->before,
        'images'       => $value->images,
        'link'         => $value->link,
        'add_before'   => $value->add_before,
        'mul_after'    => $value->mul_after,
        'add_after'    => $value->add_after,
        'before_mode'  => $value->before_mode,
        'meta_keyword'      => isset($value->meta_keyword)?$value->meta_keyword:'',
        'meta_description'  => isset($value->meta_description)?$value->meta_description:'',
        'meta_title'        => isset($value->meta_title)?$value->meta_title:'',
        'meta_h1'           => isset($value->meta_h1)?$value->meta_h1:'',
        'tag'               => isset($value->tag)?$value->tag:'',
        'sku'               => isset($value->sku)?$value->sku:'',
        'name_tpl'          => isset($value->name_tpl)?$value->name_tpl:'',
        'tag_shop'          => isset($value->tag_shop)?$value->tag_shop:'shop',
        'tag_offers'        => isset($value->tag_offers)?$value->tag_offers:'offers',
        'tag_offer'         => isset($value->tag_offer)?$value->tag_offer:'offer',
        'tag_categories'    => isset($value->tag_categories)?$value->tag_categories:'categories',
        'tag_category'      => isset($value->tag_category)?$value->tag_category:'category',
        );
  if (isset($value->quantity))      $data['suppliers'][$result['session_key']]['settings']['quantity'] = $value->quantity;
  else                              $data['suppliers'][$result['session_key']]['settings']['quantity'] = 1;
  if (isset($value->hide))          $data['suppliers'][$result['session_key']]['settings']['hide'] = 1;
  if (isset($value->hide_new))      $data['suppliers'][$result['session_key']]['settings']['hide_new'] = 1;
  if (isset($value->insert))        $data['suppliers'][$result['session_key']]['settings']['insert'] = 1;
  if (isset($value->update))        $data['suppliers'][$result['session_key']]['settings']['update'] = 1;
  if (isset($value->update_price))           $data['suppliers'][$result['session_key']]['settings']['update_price'] = 1;
  if (isset($value->update_image))           $data['suppliers'][$result['session_key']]['settings']['update_image'] = 1;
  if (isset($value->update_quantity))        $data['suppliers'][$result['session_key']]['settings']['update_quantity'] = 1;
  if (isset($value->update_name))            $data['suppliers'][$result['session_key']]['settings']['update_name'] = 1;
  if (isset($value->update_description))     $data['suppliers'][$result['session_key']]['settings']['update_description'] = 1;
  if (isset($value->update_category))        $data['suppliers'][$result['session_key']]['settings']['update_category'] = 1;
  if (isset($value->update_atributes))       $data['suppliers'][$result['session_key']]['settings']['update_atributes'] = 1;
  if (isset($value->update_vendor))          $data['suppliers'][$result['session_key']]['settings']['update_vendor'] = 1;
  if (isset($value->update_sku))             $data['suppliers'][$result['session_key']]['settings']['update_sku'] = 1;
  if (isset($value->update_model))           $data['suppliers'][$result['session_key']]['settings']['update_model'] = 1;
  if (isset($value->no_update))              $data['suppliers'][$result['session_key']]['settings']['no_update'] = 1;
  if (isset($value->not_empty_only))         $data['suppliers'][$result['session_key']]['settings']['not_empty_only'] = 1;
  if (isset($value->user_filter))            $data['suppliers'][$result['session_key']]['settings']['user_filter'] = $value->user_filter;
  if (isset($value->update_use_script))      $data['suppliers'][$result['session_key']]['settings']['update_use_script'] = $value->update_use_script;
  if (isset($value->after))         $data['suppliers'][$result['session_key']]['settings']['after']  = 1;
  if (isset($value->link_vendor))   $data['suppliers'][$result['session_key']]['settings']['link_vendor']  = 1;
  if (isset($value->link_supplier)) $data['suppliers'][$result['session_key']]['settings']['link_supplier']  = 1;
  $zoxml2_log = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_log WHERE session_key = '" . $this->db->escape($result['session_key']) . "' ORDER by id DESC LIMIT 1");
  if ($zoxml2_log->row)  $data['suppliers'][$result['session_key']]['settings']['log_last_id'] = $zoxml2_log->row['id'];
  else                   $data['suppliers'][$result['session_key']]['settings']['log_last_id'] = 0;
  }

$this->load->model('catalog/manufacturer');
$this->load->model('catalog/category');
$this->load->model('catalog/option');
$this->load->model('catalog/attribute');
$this->load->model('tool/image');
$data['manufacturers'] = array();
$data['manufacturers'][0] = 'Производитель отключен';
$data['all_options']   = array();
$data['attr_groups']   = array();
$types                 = array('select','radio','checkbox','image','checkbox_qty','radio_qty','image_qty');

$manufacturer_info     = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY name ASC");
foreach ($manufacturer_info->rows as $result) $data['manufacturers'][$result['manufacturer_id']] = $result['name'];

$options = $this->model_catalog_option->getOptions($data['all_options']);
foreach ($options as $result) if (in_array ($result['type'],$types)) $data['all_options'][$result['option_id']] = $result['name'];

$groups = $this->model_catalog_attribute->getAttributes($data['attr_groups']);
foreach ($groups as $result) $data['attr_groups'][$result['attribute_id']] = $result['name'];

$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_vendors ORDER BY name ASC");
foreach ($query->rows as $result) {
  $thumb = '';
  $url   = '';
  if ($result['manufacturer_id']>0) {
    $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);
    if ($manufacturer_info) {
      $url   = $manufacturer_info['keyword'];
      $thumb = $this->model_tool_image->resize($manufacturer_info['image'], 40, 40);
      }
    }
  $data['suppliers'][$result['session_key']]['vendors'][$result['id']] = array(
  			'id'               => $result['id'],
  			'session_key'      => $result['session_key'],
  			'name'             => $result['name'],
  			'create'           => !in_array($result['name'],$data['manufacturers']),
  			'manufacturer_id'  => $result['manufacturer_id'],
  			'total'            => $result['total'],
    // -------------------------------------------------------------------------
        'thumb'            => $thumb,
        'url'              => $url,
        );
  }

$ask = array ('sort' => 'name');
$data['categories'] = array ();
if (isset($data['module']['do_category_sort'])) $category_info = $this->model_catalog_category->getCategories($ask);
else                                            $category_info = $this->model_catalog_category->getCategories(0);
$data['categories'][0] = 'Категория отключена';
foreach ($category_info as $result) $data['categories'][$result['category_id']] = $result['name'];

$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_categories ORDER by parent,name ASC");
foreach ($query->rows as $result) {
  $data['suppliers'][$result['session_key']]['categories'][$result['id']] = array(
  			'id'               => $result['id'],
  			'name'             => $result['name'],
  			'parent'           => $result['parent'],
  			'category_id'      => $result['category_id'],
  			'total'            => $result['total'],
  			'data'             => $result['data'],
        );
  }

$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_options");
foreach ($query->rows as $result) {
  $data['suppliers'][$result['session_key']]['options'][$result['id']] = array(
  			'id'               => $result['id'],
  			'name'             => $result['name'],
  			'dest_type'        => $result['dest_type'],
  			'dest_id'          => $result['dest_id'],
  			'data'             => json_decode ($result['data']),
        );
  }

$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "zoxml2_replace");
$is_mode = false;
foreach ($query->rows as $result) if ($result['Field']=='mode') $is_mode = true; 
if (!$is_mode) $this->db->query("ALTER TABLE " . DB_PREFIX . "zoxml2_replace ADD  `mode` VARCHAR(63) NOT NULL");

$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_replace ORDER by sort_order ASC");
foreach ($query->rows as $result) {
  $data['suppliers'][$result['session_key']]['replace'][$result['id']] = array(
  			'id'               => $result['id'],
  			'sort_order'       => $result['sort_order'],
  			'type'             => $result['type'],
        'mode'             => $result['mode']?$result['mode']:'replace',
  			'txt_before'       => $result['txt_before'],
  			'txt_after'        => $result['txt_after'],
        );
  }

if (isset($data['module']['load_end_log'])) $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_log WHERE type='end' ORDER by id DESC");
else                                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_log ORDER by id DESC");
foreach ($query->rows as $result) {
  if ($result['session_key']=='0') {
    $data['system_log'][$result['id']] = array(
  			'type'             => $result['type'],
  			'data'             => $result['data'],
  			'time'             => $result['time'],
  			'user'             => $result['user'],
        );
    }
  else {
    $data['suppliers'][$result['session_key']]['log'][$result['id']] = array(
  			'type'             => $result['type'],
  			'data'             => $result['data'],
  			'time'             => $result['time'],
  			'user'             => $result['user'],
        );
    }
  }

if (isset($data['module']['load_event_log'])) {
  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_events ORDER by id DESC");
  foreach ($query->rows as $result) {
    $data['suppliers'][$result['session_key']]['events'][$result['id']] = array(
    			'type'             => $result['type'],
    			'data'             => $result['data'],
    			'time'             => $result['time'],
          );
    }
  }
unset ($data['suppliers'][0]);
// -----------------------------------------------------------------------------
    if ($data['VERSION']<2) {
  		$data['column_left'] = '';
      $this->data = array_merge($this->data, $data);
      unset ($data);

		  $this->document->addScript('view/javascript_oz/jquery/jquery-2.1.1.min.js');
		  $this->document->addScript('view/javascript_oz/bootstrap/js/bootstrap.min.js');
		  $this->document->addScript('view/javascript_oz/jquery/superfish.js');
		  $this->document->addStyle('view/javascript_oz/bootstrap.css');
		  $this->document->addStyle('view/javascript_oz/font-awesome/css/font-awesome.min.css');
		  $this->document->addStyle('view/javascript_oz/stylesheet.css');
		  $this->document->addStyle('view/javascript_oz/zoxml2.css');

  		$this->template = 'module/zoxml2.tpl';
  		$this->children = array(  'common/header','common/footer', );
  		$this->response->setOutput($this->render());
      }
    else {
		  $this->document->addStyle('view/javascript_oz/zoxml2.css');
  		$data['header'] = $this->load->controller('common/header');
  		$data['column_left'] = $this->load->controller('common/column_left');
  		$data['footer'] = $this->load->controller('common/footer');
      $this->response->setOutput($this->load->view('module/zoxml2.tpl', $data));
      }
	}

  public function progress() {
    $json = array ();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])&&isset($this->request->post['start_id'])) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_log WHERE session_key = '" . $this->db->escape($this->request->post['supplier']) . "' AND id > '" . (int)$this->request->post['start_id'] . "' ORDER by id ASC");
        foreach ($query->rows as $result) {
          $json[$result['id']] = array(
          			'id'               => $result['id'],
          			'type'             => $result['type'],
          			'data'             => $result['data'],
          			'time'             => $result['time'],
          			'user'             => $result['user'],
                );
          }
        }
      }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
    
  public function addvendor() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['id'])) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_vendors WHERE `id` = '" . (int)$this->request->post['id'] . "'");
        if ($query->row) {
      		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($query->row['name']) . "', sort_order = '0'");
      		$manufacturer_id = $this->db->getLastId();
          if ($manufacturer_id) {
				    $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");
            $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_vendors SET `manufacturer_id` = '" . (int)$manufacturer_id . "' WHERE `id` = '" . (int)$this->request->post['id'] . "'");
            }
          else  $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($query->row['session_key']) . "', type = 'error', data = 'Не удалось добавить производителя!', user = '" . $this->db->escape($this->request->post['user']) . "'");
          }
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function delrecord() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['id'])) {
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_log WHERE `id` = '" . $this->db->escape($this->request->post['id']) . "'");
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function delrecords() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_log WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function addfeed() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['add'])) {
        $action = array();
        foreach ($this->request->post['add'] as $extension => $add) {
          $action['module']   = $extension;
          $action['name']     = $add['name'];
          $action['url']      = $add['url'];
          $action['license']  = $add['key'];
          $action['user']     = $this->user->getUserName();
          }

    		$extensions  = $this->extensions();
        if (!isset($extensions[$action['module']])) {
          $err_txt = "Провайдер " . $action['module'] . " не найден!";
        	$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'error', data = '" . $this->db->escape($err_txt) . "', user = '" . $this->db->escape($action['user']) . "'");
          }
        else {
          $action['run'] = $extensions[$action['module']]['add'];
          $mquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_module");
          foreach ($mquery->rows as $result) $module[$result['key']] = json_decode ($result['data']);
          // RUN SCAN
        	$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'info', data = 'Начало операции подключения поставщика', user = '" . $this->db->escape($action['user']) . "'");
          $ch = curl_init(); 
          curl_setopt($ch, CURLOPT_URL, $action['run']);
          curl_setopt($ch, CURLOPT_NOBODY, true); 
          curl_setopt($ch, CURLOPT_HEADER, false);
          curl_setopt($ch, CURLOPT_TIMEOUT, 59); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $action);
          if (isset($module['SSL'])) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            }
        	$res = curl_exec($ch);
          if (isset($module['DEBUG'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'debug', data = '" . $this->db->escape("CURL - " . $action['run']) . "', user = '" . $this->db->escape($action['user']) . "'");
         	  $errNo = curl_errno($ch);
          	$err   = curl_error($ch);
          	$http_response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'debug', data = '" . $this->db->escape("http_response_status - " . $http_response_status) . "', user = '" . $this->db->escape($action['user']) . "'");
            }
          curl_close ($ch);
          }
        }
			$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
		}
  }
    
  public function delfeed() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_suppliers  WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_vendors    WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_categories WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_options    WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_log        WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_events     WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_replace    WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function delevent() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['id'])) {
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_events WHERE `id` = '" . $this->db->escape($this->request->post['id']) . "'");
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function delevents() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_events WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function addreplace() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])&&
          isset($this->request->post['txt_before'])&&
          isset($this->request->post['txt_after'])&&
          isset($this->request->post['type'])&&
          isset($this->request->post['mode'])&&
          isset($this->request->post['sort_order'])) 
          {
  			  $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_replace SET   `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "', type = '" . $this->db->escape($this->request->post['type']) . "', mode = '" . $this->db->escape($this->request->post['mode']) . "', txt_before = '" . $this->db->escape($this->request->post['txt_before']) . "', txt_after = '" . $this->db->escape($this->request->post['txt_after']) . "', sort_order = '" . (int)$this->request->post['sort_order'] . "'");
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
    }

  public function delreplace() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['id'])) {
  		  $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_replace  WHERE `id` = '" . $this->db->escape($this->request->post['id']) . "'");
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
    }

  public function delreplaces() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
  		  $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_replace  WHERE `session_key` = '" . $this->db->escape($this->request->post['supplier']) . "'");
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
    }

  public function settings() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        foreach ($this->request->post['supplier'] as $session_key => $supplier) {
          // Настройки
          $this->db->query("DELETE FROM " . DB_PREFIX . "zoxml2_suppliers WHERE `session_key` = '" . $this->db->escape($session_key) . "'");
  			  $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_suppliers SET   `session_key` = '" . $this->db->escape($session_key) . "', `data` = '" . $this->db->escape(json_encode($supplier['settings'])) . "'");
          // Производители
          if(isset($supplier['vendors'])) foreach ($supplier['vendors'] as $id => $result) {
    			  $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_vendors SET   `manufacturer_id` = '" . $this->db->escape($result['manufacturer_id']) . "' WHERE `id` = '" . $this->db->escape($id) . "'");
            }
          // Категории
          if(isset($supplier['categories'])) foreach ($supplier['categories'] as $id => $result) {
    			  $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_categories SET   `category_id` = '" . $this->db->escape($result['category_id']) . "' WHERE `id` = '" . $this->db->escape($id) . "'");
            }
          // Подстановки
          if(isset($supplier['replace'])) foreach ($supplier['replace'] as $id => $result) {
    			  $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_replace SET   txt_before = '" . $this->db->escape($result['txt_before']) . "', txt_after = '" . $this->db->escape($result['txt_after']) . "', type = '" . $this->db->escape($result['type']) . "', mode = '" . $this->db->escape($result['mode']) . "', sort_order = '" . (int)$result['sort_order'] . "' WHERE `session_key` = '" . $this->db->escape($session_key) . "' AND  `id` = '" . $this->db->escape($id) . "'");
            }
          // Опции
          if(isset($supplier['options'])) foreach ($supplier['options'] as $id => $result) {
    			  $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_options SET   `dest_type` = '" . $this->db->escape($result['dest_type']) . "', `dest_id` = '" . $this->db->escape($result['dest_id']) . "' WHERE `id` = '" . $this->db->escape($id) . "'");
            }
          }
        }
		  }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function module() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['module'])) {
        $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "zoxml2_module");
        foreach ($this->request->post['module'] as $key => $data) {
  			  $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_module SET   `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "'");
          }
        }
		  }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function install() {
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "zoxml2_module     (id INT(11) AUTO_INCREMENT, `key` VARCHAR(63)       NOT NULL, data    TEXT NOT NULL,                                                                                                                               PRIMARY KEY (id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "zoxml2_suppliers  (id INT(11) AUTO_INCREMENT, session_key VARCHAR(63) NOT NULL, data    TEXT NOT NULL,                                                                                                                               PRIMARY KEY (id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "zoxml2_vendors    (id INT(11) AUTO_INCREMENT, session_key VARCHAR(63) NOT NULL, name    VARCHAR(127) NOT NULL,   manufacturer_id INT(11) NOT NULL, total INT(11) NOT NULL,                                                              PRIMARY KEY (id), KEY  `session_key` (`session_key`), KEY  `name` (`name`)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "zoxml2_categories (id INT(11) AUTO_INCREMENT, session_key VARCHAR(63) NOT NULL, data    VARCHAR(127) NOT NULL, name VARCHAR(127) NOT NULL,   parent    VARCHAR(127) NOT NULL,   category_id     INT(11) NOT NULL, total INT(11) NOT NULL, PRIMARY KEY (id), KEY  `session_key` (`session_key`), KEY  `name` (`name`), KEY  `parent` (`parent`), KEY  `data` (`data`)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "zoxml2_options    (id INT(11) AUTO_INCREMENT, session_key VARCHAR(63) NOT NULL, data    TEXT NOT NULL,    name VARCHAR(127) NOT NULL,   dest_type VARCHAR(31)  NOT NULL,   dest_id         INT(11) NOT NULL,                         PRIMARY KEY (id), KEY  `session_key` (`session_key`)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "zoxml2_log        (id INT(11) AUTO_INCREMENT, session_key VARCHAR(63) NOT NULL, data    TEXT NOT NULL,    type VARCHAR(15)  NOT NULL,   time TIMESTAMP NOT NULL,       user VARCHAR(31),                                             PRIMARY KEY (id), KEY  `session_key` (`session_key`)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "zoxml2_events     (id INT(11) AUTO_INCREMENT, session_key VARCHAR(63) NOT NULL, data    TEXT NOT NULL,    type VARCHAR(15)  NOT NULL,   time TIMESTAMP NOT NULL,                                                                     PRIMARY KEY (id), KEY  `session_key` (`session_key`)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "zoxml2_replace    (id INT(11) AUTO_INCREMENT, session_key VARCHAR(63) NOT NULL, type VARCHAR(63) NOT NULL, mode VARCHAR(63) NOT NULL, txt_before  TEXT NOT NULL, sort_order INT(11)  NOT NULL,   txt_after  TEXT NOT NULL,                                      PRIMARY KEY (id), KEY  `session_key` (`session_key`)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "zoxml2_log");
  	$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "zoxml2_events");
    // -- Регистрация модуля в системе --
    $data = "module=Диспетчер XML v. 2.0";
    $ch = curl_init(); 
    $data.= "&mail=";
    $data.= $this->config->get('config_email');
    curl_setopt($ch, CURLOPT_URL, "http://igg-eco.ru/index.php?route=module/igglic"); 
    $data.= "&store=";
    $data.= $this->config->get('config_name');
    curl_setopt($ch, CURLOPT_POST, 1);
    $data.= "&www=";
    $data.= $_SERVER['SERVER_NAME'];
    $data.= "&ip=";
    $data.= $_SERVER['SERVER_ADDR'];
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data ); curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); $server_output = curl_exec ($ch); curl_close ($ch);    
    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/zoxml2')) $this->error['warning'] = $this->language->get('error_permission');
		return !$this->error;
	}

	protected function extensions() {
		$extensions = array();
		$files = glob(DIR_CATALOG . 'controller/zoxml2/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('zoxml2/' . $extension);
				$extensions[$extension] = array(
					'module'               => $extension,
					'name'                 => $this->language->get('heading_title'),
					'need_path'            => $this->language->get('need_path'),
					'need_key'             => $this->language->get('need_key'),
					'disabled'             => $this->language->get('disabled'),
					'can_do_link'          => $this->language->get('can_do_link'),
					'path'                 => HTTP_CATALOG . 'index.php?route=zoxml2/' . $extension,
					'add'                  => HTTP_CATALOG . 'index.php?route=zoxml2/' . $extension . '/add',
					'scan'                 => HTTP_CATALOG . 'index.php?route=zoxml2/' . $extension . '/scan',
					'load'                 => HTTP_CATALOG . 'index.php?route=zoxml2/' . $extension . '/load',
					'link'                 => HTTP_CATALOG . 'index.php?route=zoxml2/' . $extension . '/link',
				);
			}
		}
    return $extensions;
  }

  public function scansupplier () {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_suppliers WHERE session_key = '" . $this->db->escape($this->request->post['supplier']) . "'");
        if ($query->row) {
          $value  = json_decode ($query->row['data']);
          $action = array(
          			'module'       => $value->module,
          			'name'         => $value->name,
          			'user'         => $this->user->getUserName(),
          			'url'          => $value->url,
          			'license'      => $value->license,
          			'session_key'  => $query->row['session_key'],
                );
      		$extensions  = $this->extensions();
          if (!isset($extensions[$action['module']])) {
            $err_txt = "Провайдер " . $action['module'] . " не найден!";
          	$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'error', data = '" . $this->db->escape($err_txt) . "', user = '" . $this->db->escape($action['user']) . "'");
            }
          else {
            $action['run'] = $extensions[$action['module']]['scan'];
            $mquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_module");
            foreach ($mquery->rows as $result) $module[$result['key']] = json_decode ($result['data']);
            // RUN SCAN
          	$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'info', data = 'Начало операции загрузки данных', user = '" . $this->db->escape($action['user']) . "'");
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $action['run']);
            curl_setopt($ch, CURLOPT_NOBODY, true); 
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, isset($module['progress'])?5:10000); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $action);
            if (isset($module['SSL'])) {
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
              }

            $res = curl_exec($ch);
            if (isset($module['DEBUG'])) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'debug', data = '" . $this->db->escape("CURL - " . $action['run']) . "', user = '" . $this->db->escape($action['user']) . "'");
            	$errNo = curl_errno($ch);
            	$err   = curl_error($ch);
            	$http_response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
              $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'debug', data = '" . $this->db->escape("http_response_status - " . $http_response_status) . "', user = '" . $this->db->escape($action['user']) . "'");
              }
            curl_close ($ch);
            }
          }
        }
			$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
		}
  }

  public function linksupplier () {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_suppliers WHERE session_key = '" . $this->db->escape($this->request->post['supplier']) . "'");
        if ($query->row) {
          $value  = json_decode ($query->row['data']);
          $action = array(
          			'module'       => $value->module,
          			'name'         => $value->name,
          			'user'         => $this->user->getUserName(),
          			'url'          => $value->url,
          			'license'      => $value->license,
          			'session_key'  => $query->row['session_key'],
                );
      		$extensions  = $this->extensions();
          if (!isset($extensions[$action['module']])) {
            $err_txt = "Провайдер " . $action['module'] . " не найден!";
          	$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'error', data = '" . $this->db->escape($err_txt) . "', user = '" . $this->db->escape($action['user']) . "'");
            }
          else {
            $action['run'] = $extensions[$action['module']]['link'];
            $mquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_module");
            foreach ($mquery->rows as $result) $module[$result['key']] = json_decode ($result['data']);
            // RUN SCAN
          	$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'info', data = 'Начало операции привязки', user = '" . $this->db->escape($action['user']) . "'");
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $action['run']);
            curl_setopt($ch, CURLOPT_NOBODY, true); 
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, isset($module['progress'])?5:10000); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $action);
            if (isset($module['SSL'])) {
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
              }

          	$res = curl_exec($ch);
            if (isset($module['DEBUG'])) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'debug', data = '" . $this->db->escape("CURL - " . $action['run']) . "', user = '" . $this->db->escape($action['user']) . "'");
            	$errNo = curl_errno($ch);
            	$err   = curl_error($ch);
            	$http_response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
              $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'debug', data = '" . $this->db->escape("http_response_status - " . $http_response_status) . "', user = '" . $this->db->escape($action['user']) . "'");
              }
            curl_close ($ch);
            }
          }
        }
			$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
		}
  }

  public function addallvendors () {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        $manufacturers = array ();
        $manufacturer_info     = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY name ASC");
        foreach ($manufacturer_info->rows as $result) $manufacturers[] = $result['name'];
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_vendors WHERE session_key = '" . $this->db->escape($this->request->post['supplier']) . "'");
        foreach ($query->rows as $result) {
          if ($result['name']!='host' && $result['manufacturer_id']==0 && !in_array($result['name'],$manufacturers)) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($result['name']) . "', sort_order = '0'");
        		$manufacturer_id = $this->db->getLastId();
            if ($manufacturer_id) {
        	    $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");
              $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_vendors SET `manufacturer_id` = '" . (int)$manufacturer_id . "' WHERE `id` = '" . (int)$result['id'] . "'");
              }
            }
          }
        }
			$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
		}
  }

  protected function addCategory ($name,$parent) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE name = '" . $this->db->escape($name) . "'");
    if ($query->row) return $query->row['category_id'];
    $data['parent_id'] = 0;
    if ($parent) $data['parent_id'] = $this->addCategory ($parent,'');
    // Создание категории
    $data['top']        = $data['parent_id']?0:1;
    $data['column']     = 1;
    $data['sort_order'] = 0;
    $data['status']     = 1;
    $data['image']      = '';
    $data['category_store'] = array (0);
    $data['keyword']    = '';
    $data['category_description'] = array ();
    $data['category_description'][$this->config->get( 'config_language_id' )] = array (
      'name'              => $name,
      'description'       => '',
      'meta_title'        => '',
      'meta_description'  => '',
      'meta_keyword'      => '',
      'meta_h1'           => '',
      );
    
    return $this->model_catalog_category->addCategory($data);
    }

  public function addallcategories () {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        $this->load->model('catalog/category');
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_categories WHERE session_key = '" . $this->db->escape($this->request->post['supplier']) . "' ORDER by parent,name ASC");
        foreach ($query->rows as $result) {
          if (!$result['category_id']) {
            $category_id = $this->addCategory ($result['name'],$result['parent']);
            $this->db->query("UPDATE " . DB_PREFIX . "zoxml2_categories SET `category_id` = '" . (int)$category_id . "' WHERE `id` = '" . (int)$result['id'] . "'");
            }
          }
        }
      }
		$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function loadsupplier () {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (isset($this->request->post['supplier'])) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_suppliers WHERE session_key = '" . $this->db->escape($this->request->post['supplier']) . "'");
        if ($query->row) {
          $value  = json_decode ($query->row['data']);
          $action = array(
          			'module'       => $value->module,
          			'name'         => $value->name,
          			'user'         => $this->user->getUserName() . " (" . $_SERVER['REMOTE_ADDR'] . ")",
          			'url'          => $value->url,
          			'license'      => $value->license,
          			'session_key'  => $query->row['session_key'],
                );
          $mquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_module");
          foreach ($mquery->rows as $result) $module[$result['key']] = json_decode ($result['data']);
      		$extensions  = $this->extensions();
          if (!isset($extensions[$action['module']])) {
            $err_txt = "Провайдер " . $action['module'] . " не найден!";
          	$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'error', data = '" . $this->db->escape($err_txt) . "', user = '" . $this->db->escape($action['user']) . "'");
            }
          else {
            $action['run'] = $extensions[$action['module']]['load'];
            // RUN SCAN
          	$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'info', data = 'Начало операции загрузки данных', user = '" . $this->db->escape($action['user']) . "'");
            ignore_user_abort(true);
            ini_set('max_execution_time', 10000);
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $action['run']);
            curl_setopt($ch, CURLOPT_NOBODY, true); 
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, isset($module['progress'])?5:10000); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $action);
            if (isset($module['SSL'])) {
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
              }
          	$res = curl_exec($ch);
            if (isset($module['DEBUG'])) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'debug', data = '" . $this->db->escape("CURL - " . $action['run']) . "', user = '" . $this->db->escape($action['user']) . "'");
            	$errNo = curl_errno($ch);
            	$err   = curl_error($ch);
            	$http_response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
              $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->post['supplier']) . "', type = 'debug', data = '" . $this->db->escape("http_response_status - " . $http_response_status) . "', user = '" . $this->db->escape($action['user']) . "'");
              }
            }
          }
        }
			$this->response->redirect($this->url->link('module/zoxml2', 'token=' . $this->session->data['token'], 'SSL'));
		}
  }

}
?>