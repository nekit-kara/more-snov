<?php
class ModelZoXml2ZoXml2Filters extends Model {

public function doUserFilter($output,$data,$index,$item) {

if ($index==1) {  // Пример пользовательского фильтра - не загружать товары дешевле 5000
  if ($output['price']<5000) return NULL;
  }
if ($index==2) {  // Пример пользовательского фильтра - наценка 10% на Sony
  if ($output['vendor']=='Sony') $output['price'] *= 1.1;
  }
if ($index==3) {
  $output['price']    = 0;
  if (isset($item->prices)) {
    foreach ($item->prices->price as $value) {
      if ($value->order==2) $output['price'] = (float)$value->value;
      }
    }
  $output['price']   += $data['settings']['add_before'];
  $output['price']   *= $data['settings']['mul_after'];
  $output['price']   += $data['settings']['add_after'];
  }

return $output; 
}

}
