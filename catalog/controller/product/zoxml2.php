<?php  
class ControllerProductZoXml2 extends Controller {

public function cron() {
    $json   = array();
    $json[] = "You do not have permission to access this page!";
    $module = array();
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_module");
    foreach ($query->rows as $result) $module[$result['key']] = json_decode ($result['data']);

		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
      if (isset($this->request->get['supplier'])) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zoxml2_suppliers WHERE session_key = '" . $this->db->escape($this->request->get['supplier']) . "'");
        if ($query->row) {
          $value  = json_decode ($query->row['data']);
          $server = HTTP_SERVER;
          if (isset($module['SSL'])) $server = HTTPS_SERVER;
					$load   = $server . 'index.php?route=zoxml2/' . $value->module . '/load';
          $this->db->query("INSERT INTO " . DB_PREFIX . "zoxml2_log SET session_key = '" . $this->db->escape($this->request->get['supplier']) . "', type = 'info', data = '" . $this->db->escape('cron - передача управления в обработчик: ' . $load) ."', user = 'cron'");
          $action = array(
          			'session_key'  => $this->request->get['supplier'],
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
      }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

	public function index() {
    $json   = array();
    $json[] = "You do not have permission to access this page!";
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

}
?>