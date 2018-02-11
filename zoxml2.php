<?php  
// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Startup
    require_once(DIR_SYSTEM . 'startup.php');
// Registry
    $registry = new Registry();
// Database
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $registry->set('db', $db);    

$load = 'ПУСТО';    
if (isset($_SERVER['QUERY_STRING'])) {
  $module = array();
  $query = $db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_module");
  foreach ($query->rows as $result) $module[$result['key']] = json_decode ($result['data']);

  $query = $db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_suppliers WHERE session_key = '" . $db->escape($_SERVER['QUERY_STRING']) . "'");
  if ($query->row) {
    $value  = json_decode ($query->row['data']);
    $server = HTTP_SERVER;
    if (isset($module['SSL'])) $server = HTTPS_SERVER;
    $load   = $server . 'index.php?route=zoxml2/' . $value->module . '/load';
    $action = array(
    			'session_key'  => $_SERVER['QUERY_STRING'],
    			'user'         => 'cron',
          );
    ignore_user_abort(true);
    ini_set('max_execution_time', 10000);
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $load);
    curl_setopt($ch, CURLOPT_NOBODY, true); 
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10000); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $action);
    if (isset($module['SSL'])) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
      }
    curl_exec  ($ch); 
    curl_close ($ch);
    }
  }




echo "You do not have permission to access this page: " . $load;
?>