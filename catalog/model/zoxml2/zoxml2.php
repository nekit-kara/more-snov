<?php
class ModelZoXml2ZoXml2 extends Model {
	public function translit($text,$prefix)
	{
		$ru  = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ы', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ы', 'Э', 'Ю', 'Я', ' ', '/', '.', '"');
		$tr  = array('a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'kh', 'ts', 'ch', 'sh', 'sch', 'y', 'e', 'yu', 'ya', 'A', 'B', 'V', 'G', 'D', 'E', 'YO', 'ZH', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'KH', 'TS', 'CH', 'SH', 'SCH', 'Y', 'E', 'YU', 'YA', '-', '-', '-', '');
		$str = $prefix . strtolower (preg_replace('/[^A-Za-z0-9-_\/]+/', '', str_replace($ru, $tr, $text)));
    
    $i   = 0;
    do {
    $tmp = $str . ($i?$i:'');
    $i ++;
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $tmp . "'");
    } while ($query->row);
    $i --;
    
    return ($str . ($i?$i:''));
	}

public function link2category_id ($engine,$product_id,$category_id,$do_category_up) {
  if ($engine=='ocStore') {
  $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET 
          product_id = '" .       (int)$product_id . "', 
          main_category =         '1', 
          category_id = '" .      $category_id . "'");
  } else {
  $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET 
          product_id = '" .       (int)$product_id . "', 
          category_id = '" .      $category_id . "'");
          }
  if ($do_category_up) {
    $cat_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` WHERE category_id = '" . (int)$category_id . "'");
    while ($cat_query->row) {
      $category_id = $cat_query->row['parent_id']; 
      if ($category_id>0) $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET 
                product_id = '" .       (int)$product_id . "', 
                category_id = '" .      (int)$category_id . "'");
      else break;
      $cat_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` WHERE category_id = '" . (int)$category_id . "'");
      }
    }
  }

	public function getVendor($session_key,$name) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_vendors WHERE session_key = '" . $this->db->escape($session_key) . "' AND name = '" . $this->db->escape($name) . "'");
    if ($query->row) return $query->row['manufacturer_id'];
    return 0; // СДЕЛАТЬ ДОБАВЛЕНИЕ и УВЕДОМЛЕНИЕ!
  }

	public function getCategory($session_key,$name) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_categories WHERE session_key = '" . $this->db->escape($session_key) . "' AND name = '" . $this->db->escape($name) . "'");
    if ($query->row) return $query->row['category_id'];
    return 0; // СДЕЛАТЬ ДОБАВЛЕНИЕ и УВЕДОМЛЕНИЕ!
  }

	public function getCategoryByID($session_key,$id) {
//$debug = "SELECT * FROM " . DB_PREFIX . "zoxml2_categories WHERE session_key = '" . $this->db->escape($session_key) . "' AND `data` = '" . $this->db->escape($id) . "'"; 
//$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'debug', data = '" . $this->db->escape($debug) . "', user = 'admin'");
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_categories WHERE session_key = '" . $this->db->escape($session_key) . "' AND `data` = '" . $this->db->escape($id) . "'");
    if ($query->row) return $query->row['category_id'];
    return 0; // СДЕЛАТЬ ДОБАВЛЕНИЕ и УВЕДОМЛЕНИЕ!
  }

	public function before($session_key,$data,$user) {
    if ($data['settings']['before_mode']=='all') $table='product';
    else                                         $table=$data['settings']['supplier']=='mcg2'?'product_to_supplier':'product';
    
    if ($data['settings']['before_mode']=='all') $where='';
    else                                         $where=$data['settings']['supplier']=='mcg2'?(" WHERE supplier='" . $session_key . "'"):(" WHERE location='" . $session_key . "'");
    
    switch ($data['settings']['before']) {
      case 'nop': break;
      case 'del': {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . "Удаление продуктов" . "', user = '" . $this->db->escape($user) . "'");
    
        $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . $table . $where);
        foreach ($query->rows as $result) {
          $product_id = $result['product_id'];
    //$this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . "Удаление продукта: " . $product_id . "', user = '" . $this->db->escape($user) . "'");
          usleep (100);
          // Удалить все выбранные 
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
          if (isset($data['module']['product_filter'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
            }
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
          if (isset($data['module']['mcg2'])) {
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_multycurr WHERE product_id = '" . (int)$product_id . "'");
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_multycurr_special WHERE product_id = '" . (int)$product_id . "'");
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_multycurr_discount WHERE product_id = '" . (int)$product_id . "'");
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_multycurr_option WHERE product_id = '" . (int)$product_id . "'");
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_input_price WHERE product_id = '" . (int)$product_id . "'");
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_ym WHERE product_id = '" . (int)$product_id . "'");
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_supplier WHERE product_id = '" . (int)$product_id . "'");
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_sliv WHERE product_id = '" . (int)$product_id . "'");
        		$this->db->query("DELETE FROM " . DB_PREFIX . "product_country_of_origin WHERE product_id = '" . (int)$product_id . "'");
            }
          if (isset($data['module']['zoannouncement2'])) {
          		$this->db->query("DELETE FROM " . DB_PREFIX . "product_zoannouncement WHERE product_id = '" . (int)$product_id . "'");
              }
          if (isset($data['module']['zotuning2'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_tuning WHERE product_id = '" . (int)$product_id . "'");
            }
          if (isset($data['module']['oc2'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_parent WHERE product_id = '" . (int)$product_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_data WHERE product_id = '" . (int)$product_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_zo_special WHERE product_id = '" . (int)$product_id . "'");
            }
          }
         
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . "Удаление продуктов завершено" . "', user = '" . $this->db->escape($user) . "'");
        break;
        }
      case 'hide': {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . "Отключение продуктов" . "', user = '" . $this->db->escape($user) . "'");
    
        $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . $table . $where);
        foreach ($query->rows as $result) {
          $product_id = $result['product_id'];
          usleep (100);
          // Отключить все выбранные 
    			$this->db->query("UPDATE " . DB_PREFIX . "product SET status = '0' WHERE product_id = '" . (int)$product_id . "'");
          }
    
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . "Отключение продуктов завершено" . "', user = '" . $this->db->escape($user) . "'");
        break;
        }
      case 'zero': {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . "Сброс количества" . "', user = '" . $this->db->escape($user) . "'");
    
        $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . $table . $where);
        foreach ($query->rows as $result) {
          $product_id = $result['product_id'];
          usleep (100);
          // все выбранные кол-во -> 0
          $hide = $data['settings']['hide']?", status = '0'":'';
//      		$this->db->query("UPDATE " . DB_PREFIX . "product SET              price = '0', quantity = '0', stock_status_id = '5'" . $hide . " WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("UPDATE " . DB_PREFIX . "product SET              quantity = '0', stock_status_id = '5'" . $hide . " WHERE product_id = '" . (int)$product_id . "'");
      		$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET price = '0', quantity = '0'                        WHERE product_id = '" . (int)$product_id . "'");
          }
    
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($session_key) . "', type = 'info', data = '" . "Сброс количества завершен" . "', user = '" . $this->db->escape($user) . "'");
        break;
        }
      }
    }


public function doParams($option_key,$output,$data,$value) {
  if (isset($data['options'][$option_key])) {
    switch ($data['options'][$option_key]['dest_type']) {
      case 'attr':          {
        if($data['options'][$option_key]['dest_id']>0) {
          $output['attr'][$data['options'][$option_key]['dest_id']] = (string)$value;
          }
        break;
        }
      case 'vendor':        { $output['vendor']      = (string)$value; break; } 
      case 'sku':           { $output['sku']         = (string)$value; break; } 
      case 'quantity':      { $output['quantity']    = (string)$value; break; } 
      case 'weight':        { $output['weight']      = (string)$value; break; } 
      case 'description':   { $output['description'] = (string)$value; break; } 
      case 'price':         { $output['price']       = (string)$value; break; } 
      case 'oldprice':      { $output['oldprice']    = (string)$value; break; } 
      case 'model':         { $output['model']       = (string)$value; break; } 
      case 'name':          { $output['name']        = (string)$value; break; } 
      case 'location':      { $output['location']    = (string)$value; break; } 
      case 'cat_name':      { $output['cat_name']    = (string)$value; break; } 
      case 'par_cat_name':  { $output['par_cat_name']= (string)$value; break; } 
      case 'image':         { $output['image'][]     = (string)$value; break; } 
      case 'images':        { $output['image']       = $this->arrDamp2Array ($output['image'],(array)$value); break; } 
      }
    }
  return $output;
  }

public function doReplase($output,$data) {
  foreach ($data['replace'] as $result) {
    if ($result['type']=='description') $output['description'] = $this->doReplaceAction ( $data, $result['mode'], $result['txt_before'] , $result['txt_after'] , (string)$output['description'] );
    if ($result['type']=='name')        $output['name'] =        $this->doReplaceAction ( $data, $result['mode'], $result['txt_before'] , $result['txt_after'] , (string)$output['name'] );
    if ($result['type']=='model')       $output['model'] =       $this->doReplaceAction ( $data, $result['mode'], $result['txt_before'] , $result['txt_after'] , (string)$output['model'] );
    if ($result['type']=='price')       $output['price'] =       $this->doReplaceAction ( $data, $result['mode'], $result['txt_before'] , $result['txt_after'] , (string)$output['price'] );
    if ($result['type']=='quantity')    $output['quantity'] =    $this->doReplaceAction ( $data, $result['mode'], $result['txt_before'] , $result['txt_after'] , (string)$output['quantity'] );
    if ($result['type']=='option')      {
      foreach ($output['option'] as $key => $option) {
        $output['option'][$key]['value'] = $this->doReplaceAction ( $data, $result['mode'], $result['txt_before'] , $result['txt_after'] , $option['value'] );
        }
      }
    if ($result['type']=='attr')      {
      foreach ($output['attr'] as $key => $text) {
        $output['attr'][$key] = $this->doReplaceAction ( $data, $result['mode'], $result['txt_before'] , $result['txt_after'] , $text );
        }
      }
    }
  return $output;
}


public function doSQL($output,$data,$manufacturer_id) {
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
    $where .= "p.sku = '" . $this->db->escape($output['sku']) . "'";
    }
  if ($data['settings']['link'] == 'name') {
    if ($where) $where .= " AND ";
    $where .= "pd.name = '" . $this->db->escape($output['name']) . "'";
    $where .= " AND pd.language_id = '" . (int)$this->config->get( 'config_language_id' ) . "'";
    }
  if ($data['settings']['link'] == 'model') {
    if ($where) $where .= " AND ";
    $where .= "p.model = '" . $this->db->escape($output['model'] ) . "'";
    }
  if (isset($data['settings']['link_vendor'])) {
    if ($where) $where .= " AND ";
    $where .= "p.manufacturer_id = '" . $this->db->escape($manufacturer_id) . "'";
    }
  if (isset($data['settings']['link_supplier'])) {
    if ($where) $where .= " AND ";
    if ($data['settings']['supplier']=='mcg2') $where .= "p2s.supplier = '" . $this->db->escape($data['session_key']) . "'";
    else                                       $where .= "p.location = '" .   $this->db->escape($data['session_key']) . "'";
    }
  $sql .= " WHERE " . $where . " GROUP BY p.product_id";
  return $sql;
  }


public function doMeta($output,$data) {
      if ($data['settings']['sku'])              {
        $output['sku'] =    str_replace ( '{sku}' ,   $output['sku'],                    $data['settings']['sku'] );
        $output['sku'] =    str_replace ( '{brand}' , $output['vendor'],                 $output['sku'] );
        $output['sku'] =    str_replace ( '{shop}' ,  $this->config->get('config_name'), $output['sku'] );
        $output['sku'] =    str_replace ( '{price}' , $output['price'],                  $output['sku'] );
        $output['sku'] =    str_replace ( '{model}',  $output['model'],                  $output['sku'] );
        $output['sku'] =    str_replace ( '{name}',   $output['name'],                   $output['sku'] );
        } 
      if ($data['settings']['name_tpl'])              {
        $output['name'] =    str_replace ( '{name}' ,  $output['name'],                   $data['settings']['name_tpl'] );
        $output['name'] =    str_replace ( '{brand}' , $output['vendor'],                 $output['name'] );
        $output['name'] =    str_replace ( '{shop}' ,  $this->config->get('config_name'), $output['name'] );
        $output['name'] =    str_replace ( '{price}' , $output['price'],                  $output['name'] );
        $output['name'] =    str_replace ( '{model}',  $output['model'],                  $output['name'] );
        $output['name'] =    str_replace ( '{sku}',    $output['sku'],                    $output['name'] );
        } 
      if (isset($output['meta_keyword']))     {
        $output['meta_keyword'] =    str_replace ( '{name}',   $output['name'],                   $output['meta_keyword'] );
        $output['meta_keyword'] =    str_replace ( '{brand}',  $output['vendor'],                 $output['meta_keyword'] );
        $output['meta_keyword'] =    str_replace ( '{shop}',   $this->config->get('config_name'), $output['meta_keyword'] );
        $output['meta_keyword'] =    str_replace ( '{price}',  $output['price'],                  $output['meta_keyword'] );
        $output['meta_keyword'] =    str_replace ( '{model}',  $output['model'],                  $output['meta_keyword'] );
        $output['meta_keyword'] =    str_replace ( '{sku}',    $output['sku'],                    $output['meta_keyword'] );
        }        
      if (isset($output['meta_description'])) {
        $output['meta_description'] =    str_replace ( '{name}' ,  $output['name'],                   $output['meta_description'] );
        $output['meta_description'] =    str_replace ( '{brand}' , $output['vendor'],                 $output['meta_description'] );
        $output['meta_description'] =    str_replace ( '{shop}' ,  $this->config->get('config_name'), $output['meta_description'] );
        $output['meta_description'] =    str_replace ( '{price}',  $output['price'],                 $output['meta_description'] );
        $output['meta_description'] =    str_replace ( '{model}',  $output['model'],                  $output['meta_description'] );
        $output['meta_description'] =    str_replace ( '{sku}',    $output['sku'],                    $output['meta_description'] );
        }         
      if (isset($output['meta_title']))       {
        $output['meta_title'] =    str_replace ( '{name}' ,  $output['name'],                   $output['meta_title'] );
        $output['meta_title'] =    str_replace ( '{brand}' , $output['vendor'],                 $output['meta_title'] );
        $output['meta_title'] =    str_replace ( '{shop}' ,  $this->config->get('config_name'), $output['meta_title'] );
        $output['meta_title'] =    str_replace ( '{price}' , $output['price'],                  $output['meta_title'] );
        $output['meta_title'] =    str_replace ( '{model}',  $output['model'],                  $output['meta_title'] );
        $output['meta_title'] =    str_replace ( '{sku}',    $output['sku'],                    $output['meta_title'] );
        }         
      if (isset($output['meta_h1']))          {
        $output['meta_h1'] =    str_replace ( '{name}' ,  $output['name'],                   $output['meta_h1'] );
        $output['meta_h1'] =    str_replace ( '{brand}' , $output['vendor'],                 $output['meta_h1'] );
        $output['meta_h1'] =    str_replace ( '{shop}' ,  $this->config->get('config_name'), $output['meta_h1'] );
        $output['meta_h1'] =    str_replace ( '{price}',  $output['price'],                  $output['meta_h1'] );
        $output['meta_h1'] =    str_replace ( '{model}',  $output['model'],                  $output['meta_h1'] );
        $output['meta_h1'] =    str_replace ( '{sku}',    $output['sku'],                    $output['meta_h1'] );
        }        
      if (isset($output['tag']))              {
        $output['tag'] =    str_replace ( '{name}' ,  $output['name'],                   $output['tag'] );
        $output['tag'] =    str_replace ( '{brand}' , $output['vendor'],                 $output['tag'] );
        $output['tag'] =    str_replace ( '{shop}' ,  $this->config->get('config_name'), $output['tag'] );
        $output['tag'] =    str_replace ( '{price}' , $output['price'],                  $output['tag'] );
        $output['tag'] =    str_replace ( '{model}',  $output['model'],                  $output['tag'] );
        $output['tag'] =    str_replace ( '{sku}',    $output['sku'],                    $output['tag'] );
        } 
  return $output;
}

protected function doReplaceAction ($data, $mode, $a, $b, $c) {
  $musor = '{QWERTY}';
  switch ($mode) {
    case 'htmlentities':  return htmlentities ( $c, ENT_QUOTES ); 
    case 'replace':       return str_replace ( $a, $b, $c ); 
    case 'preg':          return preg_replace ( $a, $b, $c );
    case 'after':         return str_replace ( $musor, $b, str_replace ( $a, $a . $musor, $c ) );
    case 'before':        return str_replace ( $musor, $b, str_replace ( $a, $musor . $a, $c ) );
    case 'translate': {
      if (empty($data['module']['ya_translate'])) return "Перевод невозможен - ключ АПИ Яндекс.Переводчика отсутствует.";
      $ch = curl_init(); 
      $action = array (
        'key'   => $data['module']['ya_translate'],
        'text'  => $a. '-' . $c,
        'lang'  => $b,
        );
      curl_setopt($ch, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/translate');
      curl_setopt($ch, CURLOPT_NOBODY, true); 
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_TIMEOUT, 59); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $action);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	$res = curl_exec($ch);
      $result = json_decode ($res);
      if (isset($module['DEBUG'])) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'debug', data = '" . $this->db->escape("CURL 2 YANDEX") . "'");
     	  $errNo = curl_errno($ch);
      	$err   = curl_error($ch);
      	$http_response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'debug', data = '" . $this->db->escape("http_response_status - " . $http_response_status) . "'");
        }
      $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '0', type = 'tranclation1', data = '" . $this->db->escape($res) . "'");
      $c = '';
      foreach ($result->text as $text) $c .= $text;
      curl_close ($ch);
      }
    }
  return $c;
}

public function arrDamp ($item) {
  $txt = '';
  if (is_array($item)) {
    foreach ($item as $key => $value) {
      if (is_array($value)) $txt .= $this->arrDamp ($value);
      else { 
        if($txt) $txt .= '<br />';
        $txt .= $value;
        }
      }
    }
  else   $txt = $item;
  return $txt;
  }

public function arrDamp2Array ($txt, $item) {
  if (is_array($item)) {
    foreach ($item as $key => $value) {
      if (is_array($value)) $txt =  $this->arrDamp2Array ($txt, $value);
      else                  $txt[] =  (string)$value;
      }
    }
  else                      $txt[] = (string)$item;
  return $txt;
  }

public function getDefOutput ($data) {
  $output = array ();
  if ($data['settings']['meta_keyword'])      $output['meta_keyword']     = $data['settings']['meta_keyword'];
  if ($data['settings']['meta_description'])  $output['meta_description'] = $data['settings']['meta_description'];
  if ($data['settings']['meta_title'])        $output['meta_title']       = $data['settings']['meta_title'];
  if ($data['settings']['meta_h1'])           $output['meta_h1']          = $data['settings']['meta_h1'];
  if ($data['settings']['tag'])               $output['tag']              = $data['settings']['tag'];
  $output['description']    = '';
  $output['sku']            = '';
  $output['model']          = ''; 
  $output['weight']         = 0;  
  $output['price']          = ''; 
  $output['quantity']       = $data['settings']['quantity']; 
  $output['name']           = ''; 
  $output['vendor']         = '';   
  $output['location']       = '';   
  $output['cat_name']       = '';   
  $output['par_cat_name']   = '';   
  $output['attr']           = array();  
  $output['option']         = array(); 
  $output['image']          = array(); 
  return $output;
  }


public function loadImage ($input_img, $data, $category_id) {
  $data['dest_image'] = '';
  $image = '';
  $img   = html_entity_decode ($input_img);
//  $img   = mb_convert_encoding($input_img, 'utf-8', mb_detect_encoding($input_img));
  $ext   = 'jpg';

  $folder = $data['VERSION']==2?"catalog/":"data/";
  if (!isset($data['url_alias'][(int)$category_id])) {
    $url_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
    if ($url_query->row) $data['url_alias'][(int)$category_id] = $url_query->row['keyword'];
    else                 $data['url_alias'][(int)$category_id] = (string)$category_id;
    }
  $tmp     = $data['url_alias'][(int)$category_id];                
  $folder .= "$tmp/";
  if (!file_exists(DIR_IMAGE . $folder)) @mkdir(DIR_IMAGE . $folder, 0777);
    $src   = file_get_contents ($img);
    if (isset($http_response_header)) {
      foreach ($http_response_header as $value) {
        if (strpos ($value,'ontent-type')) {
          $parts     = explode("/",$value);
          if (isset($parts[1])) $ext = $parts[1];
          }
        }
      }
    if ($src) {
      $dest = 'IMG' . md5 ($img) . "." . $ext;
      if (file_put_contents (DIR_IMAGE . $folder .  $dest, $src )===FALSE) $data['dest_image'] = '';
      else                                                                 $data['dest_image'] = $folder . $dest; 
      } 
  return $data;
}
public function getDefSettings ($session_key,$user) {
  ignore_user_abort(true);
  ini_set('max_execution_time', 0);

  $data = array();
  $data['user']          = $user;
  $data['session_key']   = $session_key;
  $data['module'] = array ();
  $data['replace'] = array();
  $data['VERSION']          = 0;
  if (defined('VERSION')) {
    $parts             = explode(".",VERSION);
    $data['VERSION']   = isset($parts[0])?$parts[0]:0;
    }
  $data['dest_image'] = '';
  
  $data['module']['engine'] = 'Opencart';
  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_suppliers WHERE session_key = '" . $this->db->escape($session_key) . "'");
  if ($query->row) {
    $value = json_decode ($query->row['data']);
    $data['settings'] = array(
    			'module'       => $value->module,
    			'name'         => $value->name,
    			'url'          => $value->url,
    			'license'      => $value->license,
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
    if (isset($value->user_filter))            $data['settings']['user_filter']       = $value->user_filter;
    if (isset($value->update_use_script))      $data['settings']['update_use_script'] = $value->update_use_script;
    if (isset($value->quantity))      $data['settings']['quantity'] = $value->quantity;
    else                              $data['settings']['quantity'] = 1;
    if (isset($value->hide))          $data['settings']['hide'] = 1;
    else                              $data['settings']['hide'] = 0;
    if (isset($value->hide_new))      $data['settings']['hide_new'] = 1;
    else                              $data['settings']['hide_new'] = 0;
    if (isset($value->insert))        $data['settings']['insert'] = 1;
    if (isset($value->update))        $data['settings']['update'] = 1;
  if (isset($value->update_price))           $data['settings']['update_price'] = 1;
  if (isset($value->update_image))           $data['settings']['update_image'] = 1;
  if (isset($value->update_quantity))        $data['settings']['update_quantity'] = 1;
  if (isset($value->update_name))            $data['settings']['update_name'] = 1;
  if (isset($value->update_description))     $data['settings']['update_description'] = 1;
  if (isset($value->update_category))        $data['settings']['update_category'] = 1;
  if (isset($value->update_atributes))       $data['settings']['update_atributes'] = 1;
  if (isset($value->update_vendor))          $data['settings']['update_vendor'] = 1;
  if (isset($value->update_sku))             $data['settings']['update_sku'] = 1;
  if (isset($value->update_model))           $data['settings']['update_model'] = 1;
    if (isset($value->no_update))            $data['settings']['no_update'] = 1;
    if (isset($value->not_empty_only))       $data['settings']['not_empty_only'] = 1;
    if (isset($value->after))         $data['settings']['after']  = 1;
    if (isset($value->link_vendor))   $data['settings']['link_vendor']  = 1;
    if (isset($value->link_supplier)) $data['settings']['link_supplier']  = 1;
    }

  // customer_group_id
  $data['customer_groups'] = array ();
  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group ORDER by customer_group_id ASC");
  foreach ($query->rows as $result) {
    $data['customer_groups'][] = $result['customer_group_id'];
    }
  
  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_module");
  foreach ($query->rows as $result) $data['module'][$result['key']] = json_decode ($result['data']);
  if (!isset($data['module']['step']))  $data['module']['step']  = 10;
  if (!isset($data['module']['sleep'])) $data['module']['sleep'] = 100;
  
  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_options WHERE session_key = '" . $this->db->escape($session_key) . "'");
  foreach ($query->rows as $result) {
    $data['options'][$result['name']] = array(
    			'dest_type'        => $result['dest_type'],
    			'dest_id'          => $result['dest_id'],
          );
    }
  
  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_replace WHERE session_key = '" . $this->db->escape($session_key) . "' ORDER by sort_order ASC");
  foreach ($query->rows as $result) {
    $data['replace'][] = array(
    			'type'             => $result['type'],
          'mode'             => $result['mode']?$result['mode']:'replace',
    			'txt_before'       => $result['txt_before'],
    			'txt_after'        => $result['txt_after'],
          );
    }
  $data['url_alias'] = array();

  return $data;
  }

}
