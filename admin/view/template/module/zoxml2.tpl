<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-body">
        <form class="form-horizontal">
          <div class="col-sm-2">
            <h3 class="panel-title">ПОСТАВЩИКИ</h3>
            <hr />
            <ul class="nav nav-pills nav-stacked" id="suppliers">
              <?php $row = 0; ?>
              <?php foreach ($suppliers as $session_key => $supplier) { ?>
                <li>
                  <a href="#tab-supplier<?php echo $row; ?>" data-toggle="tab">
                    <i class="fa fa-minus-circle"  style="float: left;margin-right: 5px;" data-toggle="tooltip" title="Удалить поставщика" onclick="$('.del_dialog_for_<?php echo $row; ?>').show(); setTimeout(function(){$('.any_dialog').hide();},10000);"></i> <?php echo $supplier['settings']['name']; ?>
                    <i class="fa fa-save" style="float: right;" data-toggle="tooltip" title="Сохранить настройки" onclick="$(this).addClass('fa-spinner').addClass('fa-spin'); saveSupplier('<?php echo $session_key; ?>');"></i>
                    <i class="fa fa-exchange" style="float: right;margin: 0px 10px;" data-toggle="tooltip" title="Загрузить товары" onclick="$(this).addClass('fa-spinner').addClass('fa-spin'); $('#LOG<?php echo $row; ?> a:first').tab('show'); loadSupplier('<?php echo $session_key; ?>',<?php echo $supplier['settings']['log_last_id']; ?>);"></i>
                    </a>
                  <div class="row any_dialog del_dialog_for_<?php echo $row; ?>">
                    <div class="col-sm-12" style="text-align: center;"><br /><h4>УДАЛИТЬ ПОСТАВЩИКА?</h4></div>
                    <div class="col-sm-6" style="text-align: center;">
                      <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delFeed('<?php echo $session_key; ?>');" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;&nbsp;ДА</a>
                    </div>
                    <div class="col-sm-6" style="text-align: center;">
                      <a onclick="$('.any_dialog').hide();" class="btn btn-primary"><i class="fa fa-ban"></i>&nbsp;&nbsp;НЕТ</a>
                    </div>
                  </div>
                </li>
              <?php $row++; } ?>
              <li style="margin-top: 50px;"><a href="#tab-module" data-toggle="tab"><i class="fa fa-cog fa-fw" ></i> НАСТРОЙКИ МОДУЛЯ<i class="fa fa-save" style="float: right;" data-toggle="tooltip" title="Сохранить настройки" onclick="$(this).addClass('fa-spinner').addClass('fa-spin'); saveModule();"></i></a></li>
            </ul>
          </div>
          <div class="col-sm-10">
            <div class="tab-content">
              <?php $row = 0; ?>
              <?php foreach ($suppliers as $session_key => $supplier) { ?>
              <div class="tab-pane" id="tab-supplier<?php echo $row; ?>">
                <h3 class="panel-title" style="padding-left:25px;">НАСТРОЙКИ ПОСТАВЩИКА</h3>
                <hr />


          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general<?php echo $row; ?>" data-toggle="tab">УПРАВЛЕНИЕ</a></li>
            <li><a href="#tab-vendors<?php echo $row; ?>" data-toggle="tab">ПРОИЗВОДИТЕЛИ</a></li>
            <li><a href="#tab-category<?php echo $row; ?>" data-toggle="tab">КАТЕГОРИИ</a></li>
            <li><a href="#tab-attribute<?php echo $row; ?>" data-toggle="tab">АТРИБУТЫ\ОПЦИИ</a></li>
            <li id="LOG<?php echo $row; ?>"><a href="#tab-log<?php echo $row; ?>" data-toggle="tab">ЛОГ</a></li>
<?php if (isset($module['load_event_log'])) { ?>
            <li><a href="#tab-events<?php echo $row; ?>" data-toggle="tab">УВЕДОМЛЕНИЯ</a></li>
<?php } ?>
            <li><a href="#tab-replace<?php echo $row; ?>" data-toggle="tab">ПОДСТАНОВКИ</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="tab-general<?php echo $row; ?>">

                <div class="form-group">
                  <label class="col-sm-4 control-label">ДЕЙСТВИЯ:</label>
                  <div class="col-sm-8">
                    <div class="col-sm-12">
                      <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); $('#LOG<?php echo $row; ?> a:first').tab('show'); scanSupplier('<?php echo $session_key; ?>',<?php echo $supplier['settings']['log_last_id']; ?>);" class="btn btn-primary" style="width: 100%;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;Загрузить произодителей, категории и атрибуты</a>
                    </div>
                    <?php if(isset($extensions[$supplier['settings']['module']])&&$extensions[$supplier['settings']['module']]['can_do_link']=='yes') { ?>
                      <div class="col-sm-12">
                        <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); $('#LOG<?php echo $row; ?> a:first').tab('show'); linkSupplier('<?php echo $session_key; ?>',<?php echo $supplier['settings']['log_last_id']; ?>);" class="btn btn-primary" style="width: 100%;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;Привязка существующих товаров к поставщику</a>
                      </div>
                    <?php } ?>
                    <div class="col-sm-12">
                      <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); addAllVendors('<?php echo $session_key; ?>');" class="btn btn-primary" style="width: 100%;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;Создать всех производителей</a>
                    </div>
                    <div class="col-sm-12">
                      <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); addAllCategories('<?php echo $session_key; ?>');" class="btn btn-primary" style="width: 100%;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;Создать все категории</a>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ИМЯ:</label>
                  <div class="col-sm-8">
                    <input type="text" name="supplier[<?php echo $session_key; ?>][settings][name]" value="<?php echo $supplier['settings']['name']; ?>" class="form-control" />
                    <input type="hidden" name="supplier[<?php echo $session_key; ?>][settings][module]" value="<?php echo $supplier['settings']['module']; ?>" class="form-control" />
                  </div>
                </div>

                <?php if(isset($extensions[$supplier['settings']['module']])&&$extensions[$supplier['settings']['module']]['need_path']=='yes') { ?>
                <div class="form-group">
                  <label class="col-sm-4 control-label">URL:</label>
                  <div class="col-sm-8">
                    <input type="text" name="supplier[<?php echo $session_key; ?>][settings][url]" value="<?php echo $supplier['settings']['url']; ?>" class="form-control" />
                  </div>
                </div>
                <?php } else { ?>
                    <input type="hidden" name="supplier[<?php echo $session_key; ?>][settings][url]" value="<?php echo $supplier['settings']['url']; ?>" class="form-control" />
                <?php } ?>

                <?php if(isset($extensions[$supplier['settings']['module']])&&$extensions[$supplier['settings']['module']]['need_key']=='yes') { ?>
                <div class="form-group">
                  <label class="col-sm-4 control-label">ЛИЦЕНЗИЯ (дополнительные данные):</label>
                  <div class="col-sm-8">
                    <input type="text" name="supplier[<?php echo $session_key; ?>][settings][license]" value="<?php echo $supplier['settings']['license']; ?>" placeholder="Путь к товарам - ключи разделяются запятыми без пробелов. Например: shop,offers,offer" class="form-control" />
                  </div>
                </div>
                <?php } else { ?>
                    <input type="hidden" name="supplier[<?php echo $session_key; ?>][settings][license]" value="<?php echo $supplier['settings']['license']; ?>" class="form-control" />
                <?php } ?>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ИНФОРМАЦИЯ О ПОСТАВЩИКЕ:</label>
                  <div class="col-sm-8">
                    <select name="supplier[<?php echo $session_key; ?>][settings][supplier]" class="form-control">
                      <option value="nop"          <?php echo $supplier['settings']['supplier']=='nop'?'selected':''; ?>>Не сохранять</option>
                      <option value="location"     <?php echo $supplier['settings']['supplier']=='location'?'selected':''; ?>>В поле location</option>
                      <option value="mcg2"         <?php echo $supplier['settings']['supplier']=='mcg2'?'selected':''; ?>>В модуле Мультивалютные товары</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ДЕЙСТВИЕ ПЕРЕД ЗАГРУЗКОЙ:</label>
                  <div class="col-sm-4">
                    <select name="supplier[<?php echo $session_key; ?>][settings][before]" class="form-control">
                      <option value="nop"          <?php echo $supplier['settings']['before']=='nop'?'selected':''; ?>>Ничего не делать</option>
                      <option value="del"          <?php echo $supplier['settings']['before']=='del'?'selected':''; ?>>Удалить</option>
                      <option value="hide"         <?php echo $supplier['settings']['before']=='hide'?'selected':''; ?>>Отключить</option>
                      <option value="zero"         <?php echo $supplier['settings']['before']=='zero'?'selected':''; ?>>Сбросить количество в 0</option>
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <select name="supplier[<?php echo $session_key; ?>][settings][before_mode]" class="form-control">
                      <option value="all"          <?php echo $supplier['settings']['before_mode']=='all'?'selected':''; ?>>Для всех товаров</option>
                      <option value="supplier"     <?php echo $supplier['settings']['before_mode']=='supplier'?'selected':''; ?>>Только для товаров этого поставщика</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ДЕЙСТВИЕ ПРИ ЗАГРУЗКЕ:</label>
                  <div class="col-sm-4">
                    <div class="col-sm-2">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][insert]" value="1" <?php echo isset($supplier['settings']['insert'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-10">добавлять новые товары</div>
                    <div class="col-sm-2">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][hide_new]" value="1" <?php echo isset($supplier['settings']['hide_new'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-10">новые отключать в ожидании модерации</div>
                    <div class="col-sm-2">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][not_empty_only]" value="1" <?php echo isset($supplier['settings']['not_empty_only'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-10">не загружать товары с нулевым остатком</div>
                  </div>

                  <div class="col-sm-4">
                    <div class="col-sm-2">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update]" value="1" <?php echo isset($supplier['settings']['update'])?'checked':''; ?> class="form-control" /> 
                    </div>
                    <div class="col-sm-10">обновлять товары</div>
                    <div class="col-sm-2">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][no_update]" value="1" <?php echo isset($supplier['settings']['no_update'])?'checked':''; ?> class="form-control" /> 
                    </div>
                    <div class="col-sm-10">не обновлять отключенные товары</div>
                  </div>

                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">РАЗРЕШАТЬ ОБНОВЛЕНИЕ ПОЛЕЙ:</label>
                  <div class="col-sm-8">

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_price]" value="1" <?php echo isset($supplier['settings']['update_price'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">цена</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_quantity]" value="1" <?php echo isset($supplier['settings']['update_quantity'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">количество</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_image]" value="1" <?php echo isset($supplier['settings']['update_image'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">изображения</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_name]" value="1" <?php echo isset($supplier['settings']['update_name'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">название</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_description]" value="1" <?php echo isset($supplier['settings']['update_description'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">описание</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_category]" value="1" <?php echo isset($supplier['settings']['update_category'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">категория</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_atributes]" value="1" <?php echo isset($supplier['settings']['update_atributes'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">атрибуты</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_vendor]" value="1" <?php echo isset($supplier['settings']['update_vendor'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">производитель</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_sku]" value="1" <?php echo isset($supplier['settings']['update_sku'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">артикул</div>

                    <div class="col-sm-1">
                      <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][update_model]" value="1" <?php echo isset($supplier['settings']['update_model'])?'checked':''; ?> class="form-control" />
                    </div>
                    <div class="col-sm-5">модель</div>

                    <div class="col-sm-2">
                      <input type="text" name="supplier[<?php echo $session_key; ?>][settings][update_use_script]" value="<?php echo isset($supplier['settings']['update_use_script'])?$supplier['settings']['update_use_script']:''; ?>" class="form-control" />
                    </div>
                    <div class="col-sm-10">Пользовательские действия. Обратитесь к документации по использованию!</div>

                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ДЕЙСТВИЕ ПОСЛЕ ЗАГРУЗКИ:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][after]" value="1" <?php echo isset($supplier['settings']['after'])?'checked':''; ?> class="form-control" />
                  </div>
                  <div class="col-sm-7">не использовать для цен принцип "Комплектатора"</div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ИЗОБРАЖЕНИЯ:</label>
                  <div class="col-sm-8">
                    <select name="supplier[<?php echo $session_key; ?>][settings][images]" class="form-control">
                      <option value="nop"          <?php echo $supplier['settings']['images']=='nop'?'selected':''; ?>>Не загружать</option>
                      <option value="main"         <?php echo $supplier['settings']['images']=='main'?'selected':''; ?>>Загружать только основное изображение</option>
                      <option value="all"          <?php echo $supplier['settings']['images']=='all'?'selected':''; ?>>Загружать все изображения</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">СВЯЗУЮЩИЕ ПОЛЯ:</label>

                    <div class="col-sm-4">
                      <div class="col-sm-12">
                        <b>Обязательные:</b>
                      </div>
                      <div class="col-sm-12">
                        <select name="supplier[<?php echo $session_key; ?>][settings][link]" class="form-control">
                          <option value="sku"          selected                                                           >Артикул (SKU)</option>
                          <option value="model"        <?php echo $supplier['settings']['link']=='model'?'selected':''; ?>>Модель (model)</option>
                          <option value="name"         <?php echo $supplier['settings']['link']=='name'?'selected':''; ?>>Название (name)</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="col-sm-12">
                        <b>Опциональные:</b>
                      </div>
                      <div class="col-sm-2">
                        <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][link_vendor]" value="1" <?php echo isset($supplier['settings']['link_vendor'])?'checked':''; ?> class="form-control" />
                      </div>
                      <div class="col-sm-10">Производитель (manufacturer_id) </div>
                      <div class="col-sm-2">
                        <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][link_supplier]" value="1" <?php echo isset($supplier['settings']['link_supplier'])?'checked':''; ?> class="form-control" /> 
                      </div>
                      <div class="col-sm-10">Поставщик</div>
                    </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ФОРМУЛА МОДИФИКАЦИИ ЦЕНЫ:</label>
                  <div class="col-sm-8">
                    <div class="col-sm-12">
                      <div class="col-sm-4">
                        <div class="col-sm-12">Шаг 1 - прибавить к цене:</div>
                        <div class="col-sm-12">
                          <input type="text" name="supplier[<?php echo $session_key; ?>][settings][add_before]" value="<?php echo $supplier['settings']['add_before']; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="col-sm-12">Шаг 2 - умножить результат на:</div>
                        <div class="col-sm-12">
                          <input type="text" name="supplier[<?php echo $session_key; ?>][settings][mul_after]" value="<?php echo $supplier['settings']['mul_after']; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="col-sm-12">Шаг 3 - прибавить к результату:</div>
                        <div class="col-sm-12">
                          <input type="text" name="supplier[<?php echo $session_key; ?>][settings][add_after]" value="<?php echo $supplier['settings']['add_after']; ?>" class="form-control" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">КОЛИЧЕСТВО ПО УМОЛЧАНИЮ:</label>
                  <div class="col-sm-2">
                    <input type="text" name="supplier[<?php echo $session_key; ?>][settings][quantity]" value="<?php echo isset($supplier['settings']['quantity'])?$supplier['settings']['quantity']:'1'; ?>" class="form-control" />
                  </div>
                  <div class="col-sm-6">используется если в карточке товара не указано кол-во</div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ПОЛЬЗОВАТЕЛЬСКИЙ ФИЛЬТР:</label>
                  <div class="col-sm-2">
                    <input type="text" name="supplier[<?php echo $session_key; ?>][settings][user_filter]" value="<?php echo isset($supplier['settings']['user_filter'])?$supplier['settings']['user_filter']:''; ?>" class="form-control" />
                  </div>
                  <div class="col-sm-6">Обратитесь к документации по использованию!</div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ШАБЛОНЫ:
                      <br /><br /><b>ВАЖНО!</b><br />
                      1. Не создавайте шаблоны для полей, которых нет в вашей сборке!<br />
                      Пример: поле meta_h1 отсутствует в Опенкарт и создание шаблона для этого поля приведет к ошибке при записи данных в БД<br />
                      2. Шаблоны обрабатываются в указанном порядке. И если вы на первом шаге изменили SKU, то далее везде будет подставляться измененное значение.   </label>
                  <div class="col-sm-8">
                    <div class="col-sm-6">

                      <div class="col-sm-4">sku:</div>
                      <div class="col-sm-8">
                        <textarea rows="1" name="supplier[<?php echo $session_key; ?>][settings][sku]" class="form-control"><?php echo $supplier['settings']['sku']; ?></textarea>
                      </div>

                      <div class="col-sm-4">name:</div>
                      <div class="col-sm-8">
                        <textarea rows="1" name="supplier[<?php echo $session_key; ?>][settings][name_tpl]" class="form-control"><?php echo $supplier['settings']['name_tpl']; ?></textarea>
                      </div>

                      <div class="col-sm-4">meta_keyword:</div>
                      <div class="col-sm-8">
                        <textarea rows="1" name="supplier[<?php echo $session_key; ?>][settings][meta_keyword]" class="form-control"><?php echo $supplier['settings']['meta_keyword']; ?></textarea>
                      </div>

                      <div class="col-sm-4">meta_description:</div>
                      <div class="col-sm-8">
                        <textarea rows="1" name="supplier[<?php echo $session_key; ?>][settings][meta_description]" class="form-control"><?php echo $supplier['settings']['meta_description']; ?></textarea>
                      </div>

                      <div class="col-sm-4">meta_title:</div>
                      <div class="col-sm-8">
                        <textarea rows="1" name="supplier[<?php echo $session_key; ?>][settings][meta_title]" class="form-control"><?php echo $supplier['settings']['meta_title']; ?></textarea>
                      </div>

                      <div class="col-sm-4">meta_h1:</div>
                      <div class="col-sm-8">
                        <textarea rows="1" name="supplier[<?php echo $session_key; ?>][settings][meta_h1]" class="form-control"><?php echo $supplier['settings']['meta_h1']; ?></textarea>
                      </div>

                      <div class="col-sm-4">tag:</div>
                      <div class="col-sm-8">
                        <textarea rows="1" name="supplier[<?php echo $session_key; ?>][settings][tag]" class="form-control"><?php echo $supplier['settings']['tag']; ?></textarea>
                      </div>

                    </div>
                    <div class="col-sm-6">
                      <b>РАБОТА С ШАБЛОНАМИ</b><br />
                      {name} - заменяется на название товара<br />
                      {brand} - заменяется на название производителя товара<br />
                      {shop} - заменяется на название магазина<br />
                      {price} - заменяется на цену товара<br />
                      {sku} - заменяется на артикул товара<br />
                      {model} - заменяется на модель товара<br />
                       
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ТОВАРЫ С НУЛЕВЫМ ОСТАТКОМ:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" name="supplier[<?php echo $session_key; ?>][settings][hide]" value="1" <?php echo isset($supplier['settings']['hide'])?'checked':''; ?> class="form-control" />
                  </div>
                  <div class="col-sm-7">отключать</div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">НАСТРОЙКА CRON:</label>
                  <div class="col-sm-8">
Для работы необходимо прописать на вашем хостинге в планировщике задач следующую строку:<br />
<b>wget -o /dev/null -O/dev/null <?php echo HTTP_CATALOG; ?>zoxml2.php?<?php echo $session_key; ?></b><br />    
Примечания:<br />
1. Приведен пример формата команды для ISPmanager. Уточните формат команды у вашего хостинг-провайдера<br />
2. В "ЛОГ" задачи выполненные через планировщик отображаются как инициированные пользователем <b>cron</b>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">НАСТРОЙКА ФОРМАТА:</label>
                  <div class="col-sm-8">
                    <div class="col-sm-6" style="text-align: center;">тег <b>shop</b></div>
                    <div class="col-sm-6">
                      <input type="text" name="supplier[<?php echo $session_key; ?>][settings][tag_shop]" value="<?php echo $supplier['settings']['tag_shop']; ?>" class="form-control" />
                    </div>
                    <div class="col-sm-6" style="text-align: center;">тег <b>offers</b></div>
                    <div class="col-sm-6">
                      <input type="text" name="supplier[<?php echo $session_key; ?>][settings][tag_offers]" value="<?php echo $supplier['settings']['tag_offers']; ?>" class="form-control" />
                    </div>
                    <div class="col-sm-6" style="text-align: center;">тег <b>offer</b></div>
                    <div class="col-sm-6">
                      <input type="text" name="supplier[<?php echo $session_key; ?>][settings][tag_offer]" value="<?php echo $supplier['settings']['tag_offer']; ?>" class="form-control" />
                    </div>
                    <div class="col-sm-6" style="text-align: center;">тег <b>categories</b></div>
                    <div class="col-sm-6">
                      <input type="text" name="supplier[<?php echo $session_key; ?>][settings][tag_categories]" value="<?php echo $supplier['settings']['tag_categories']; ?>" class="form-control" />
                    </div>
                    <div class="col-sm-6" style="text-align: center;">тег <b>category</b></div>
                    <div class="col-sm-6">
                      <input type="text" name="supplier[<?php echo $session_key; ?>][settings][tag_category]" value="<?php echo $supplier['settings']['tag_category']; ?>" class="form-control" />
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">ШАБЛОНЫ ФОРМАТОВ:</label>
                  <div class="col-sm-8" style="text-align: center;">
                    <a onclick="$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_shop]\']').val('shop');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_offers]\']').val('offers');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_offer]\']').val('offer');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_categories]\']').val('categories');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_category]\']').val('category');" class="btn btn-primary"><i class="fa fa-bolt"></i>&nbsp;&nbsp;YML</a>
                    <a onclick="$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_shop]\']').val('');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_offers]\']').val('');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_offer]\']').val('item');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_categories]\']').val('');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_category]\']').val('');" class="btn btn-primary"><i class="fa fa-bolt"></i>&nbsp;&nbsp;api.textiloptom.net v.4</a>
                    <a onclick="$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_shop]\']').val('store');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_offers]\']').val('items');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_offer]\']').val('item');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_categories]\']').val('');$('input[name=\'supplier[<?php echo $session_key; ?>][settings][tag_category]\']').val('');" class="btn btn-primary"><i class="fa fa-bolt"></i>&nbsp;&nbsp;ТоварДеньгиТовар</a>
                  </div>
                </div>

            </div>
            <div class="tab-pane" id="tab-vendors<?php echo $row; ?>">
                <div class="form-group">
                  <div class="col-sm-2">ЛОГОТИП</div>
                  <div class="col-sm-2">НАЗВАНИЕ</div>
                  <div class="col-sm-2">ТОВАРОВ</div>
                  <div class="col-sm-2">URL</div>
                  <div class="col-sm-4">ПРАВИЛО</div>
                </div>
              <?php if (isset($supplier['vendors'])) foreach ($supplier['vendors'] as $vendor) { ?>
                <div class="form-group">
                  <div class="col-sm-2">
                    <img src="<?php echo $vendor['thumb']; ?>" title="<?php echo $vendor['name']; ?>" />
                  </div>
                  <div class="col-sm-2">
                    <p class="form-control"><?php echo $vendor['name']; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <p class="form-control"><?php echo $vendor['total']; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <p class="form-control"><?php echo $vendor['url']; ?></p>
                  </div>
                  <div class="col-sm-3">
                    <select name="supplier[<?php echo $session_key; ?>][vendors][<?php echo $vendor['id']; ?>][manufacturer_id]" class="form-control">
                      <?php foreach ($manufacturers as $manufacturer_id => $name) { ?>
                        <option value="<?php echo $manufacturer_id; ?>" <?php echo $vendor['manufacturer_id']==$manufacturer_id?'selected':''; ?>><?php echo $name; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-sm-1">
                    <?php if ($vendor['create']) { ?>
                      <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); addVendor (<?php echo $vendor['id']; ?>);" class="btn btn-primary"><i class="fa fa fa-plus"></i>&nbsp;&nbsp;Добавить</a>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            </div>

            <div class="tab-pane" id="tab-category<?php echo $row; ?>">
                <div class="form-group">
                  <div class="col-sm-5">НАЗВАНИЕ</div>
                  <div class="col-sm-1">ТОВАРОВ</div>
                  <div class="col-sm-6">ПРАВИЛО</div>
                </div>
              <?php if (isset($supplier['categories'])) foreach ($supplier['categories'] as $category) { ?>
                <?php if ($category['total']||isset($module['do_category_empty'])){ ?>
                  <div class="form-group">
                    <div class="col-sm-5">
                      <p class="form-control" style="height: initial;">
                      <?php echo $category['parent']?('(' . $category['parent'] . ' >) '):''; ?>
                      <?php echo $category['name']; ?>
                      <?php echo $category['data']?(' (id=' . $category['data'] . ')'):''; ?>
                      </p>
                    </div>
                    <div class="col-sm-1">
                      <p class="form-control"><?php echo $category['total']; ?></p>
                    </div>
                    <div class="col-sm-6">
                      <select name="supplier[<?php echo $session_key; ?>][categories][<?php echo $category['id']; ?>][category_id]" class="form-control">
                        <?php foreach ($categories as $category_id => $name) { ?>
                          <option value="<?php echo $category_id; ?>" <?php echo $category['category_id']==$category_id?'selected':''; ?> ><?php echo $name; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>

            <div class="tab-pane" id="tab-attribute<?php echo $row; ?>">
                <div class="form-group">
                  <div class="col-sm-4">НАЗВАНИЕ</div>
                  <div class="col-sm-4">ПРАВИЛО</div>
                </div>
              <?php if (isset($supplier['options'])) foreach ($supplier['options'] as $option) { ?>
                <div class="form-group">
                  <div class="col-sm-4">
                    <p class="form-control" style="height: initial;"><?php echo $option['name']; ?></p>
                  </div>
                  <div class="col-sm-4">
                    <select name="supplier[<?php echo $session_key; ?>][options][<?php echo $option['id']; ?>][dest_type]" class="form-control">
                      <option value=""              <?php echo $option['dest_type']==''?'selected':''; ?>             >Не обрабатывать</option>
                      <option value="option"        <?php echo $option['dest_type']=='option'?'selected':''; ?>       >Опция</option>
                      <option value="attr"          <?php echo $option['dest_type']=='attr'?'selected':''; ?>         >Атрибут</option>
                      <option value="vendor"        <?php echo $option['dest_type']=='vendor'?'selected':''; ?>       >Производитель</option>
                      <option value="model"         <?php echo $option['dest_type']=='model'?'selected':''; ?>        >Модель</option>
                      <option value="sku"           <?php echo $option['dest_type']=='sku'?'selected':''; ?>          >Артикул</option>
                      <option value="quantity"      <?php echo $option['dest_type']=='quantity'?'selected':''; ?>     >Кол-во товаров</option>
                      <option value="weight"        <?php echo $option['dest_type']=='weight'?'selected':''; ?>       >Вес товара</option>
                      <option value="name"          <?php echo $option['dest_type']=='name'?'selected':''; ?>         >Название товара</option>
                      <option value="price"         <?php echo $option['dest_type']=='price'?'selected':''; ?>        >Цена</option>
                      <option value="image"         <?php echo $option['dest_type']=='image'?'selected':''; ?>        >Изображение товара</option>
                      <option value="images"        <?php echo $option['dest_type']=='images'?'selected':''; ?>       >Изображениz товара (массив)</option>
                      <option value="o_description" <?php echo $option['dest_type']=='o_description'?'selected':''; ?>>Описание опции</option>
                      <option value="description"   <?php echo $option['dest_type']=='description'?'selected':''; ?>  >Описание товара</option>
                      <option value="location"      <?php echo $option['dest_type']=='location'?'selected':''; ?>     >location (расположение)</option>
                      <option value="cat_name"      <?php echo $option['dest_type']=='cat_name'?'selected':''; ?>     >Название категории</option>
                      <option value="par_cat_name"  <?php echo $option['dest_type']=='par_cat_name'?'selected':''; ?> >Родительская категория</option>
                      <option value="oldprice"      <?php echo $option['dest_type']=='oldprice'?'selected':''; ?>     >Старая цена</option>
                      <option value="o_sku"         <?php echo $option['dest_type']=='o_sku'?'selected':''; ?>         disabled>Артикул опции</option>
                      <option value="country"       <?php echo $option['dest_type']=='country'?'selected':''; ?>       disabled>Страна происхождения</option>
                      <option value="sliv"          <?php echo $option['dest_type']=='sliv'?'selected':''; ?>          disabled>Слив товара</option>
                      <option value="ym"            <?php echo $option['dest_type']=='ym'?'selected':''; ?>            disabled>Выгружать в ЯМ</option>
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <select name="supplier[<?php echo $session_key; ?>][options][<?php echo $option['id']; ?>][dest_id]" class="form-control">
                      <option value="0">Не опредерено</option>
                      <?php foreach ($all_options as $option_id => $option_name) { ?>
                        <option value="<?php echo $option_id; ?>" <?php echo $option['dest_type']=='option'&&$option['dest_id']==$option_id?'selected':''; ?>>ОПЦИЯ: <?php echo $option_name; ?></option>
                      <?php } ?>
                      <?php foreach ($attr_groups as $option_id => $option_name) { ?>
                        <option value="<?php echo $option_id; ?>" <?php echo $option['dest_type']=='attr'&&$option['dest_id']==$option_id?'selected':''; ?>>АТРИБУТ: <?php echo $option_name; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php } ?>
            </div>

            <div class="tab-pane" id="tab-log<?php echo $row; ?>">
                <div class="form-group" id="log_progress_<?php echo $session_key; ?>">
                  <div class="col-sm-2">ТИП СОБЫТИЯ</div>
                  <div class="col-sm-2">ПОЛЬЗОВАТЕЛЬ</div>
                  <div class="col-sm-2">ВРЕМЯ СОБЫТИЯ</div>
                  <div class="col-sm-4">ДАННЫЕ</div>
                  <div class="col-sm-2">
                    <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delRecords ('<?php echo $session_key; ?>');" class="btn btn-primary"><i class="fa fa fa-minus"></i>&nbsp;&nbsp;ОЧИСТИТЬ ЛОГ</a>
                  </div>
                </div>
              <?php if (isset($supplier['log'])) foreach ($supplier['log'] as $id => $record) { ?>
                <div class="form-group">
                  <div class="col-sm-2">
                    <p class="form-control"><?php echo $record['type']; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <p class="form-control"><?php echo $record['user']; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <p class="form-control"><?php echo $record['time']; ?></p>
                  </div>
                  <div class="col-sm-4">
                    <p class="form-control" style="height: initial;"><?php echo $record['data']; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delRecord (<?php echo $id; ?>);" class="btn btn-primary"><i class="fa fa fa-minus"></i>&nbsp;&nbsp;Удалить</a>
                  </div>
                </div>
              <?php } ?>
            </div>

<?php if (isset($module['load_event_log'])) { ?>
            <div class="tab-pane" id="tab-events<?php echo $row; ?>">
                <div class="form-group">
                  <div class="col-sm-2">ТИП СОБЫТИЯ</div>
                  <div class="col-sm-2">ВРЕМЯ СОБЫТИЯ</div>
                  <div class="col-sm-6">ДАННЫЕ</div>
                  <div class="col-sm-2">
                    <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delEvents ('<?php echo $session_key; ?>');" class="btn btn-primary"><i class="fa fa fa-minus"></i>&nbsp;&nbsp;ОЧИСТИТЬ СПИСОК</a>
                  </div>
                </div>
              <?php if (isset($supplier['events'])) foreach ($supplier['events'] as $id => $event) { ?>
                <div class="form-group">
                  <div class="col-sm-2">
                    <p class="form-control"><?php echo $event['type']; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <p class="form-control"><?php echo $event['time']; ?></p>
                  </div>
                  <div class="col-sm-6">
                    <p class="form-control" style="height: initial;"><?php echo $event['data']; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delEvent (<?php echo $id; ?>);" class="btn btn-primary"><i class="fa fa fa-minus"></i>&nbsp;&nbsp;Удалить новость</a>
                  </div>
                </div>
              <?php } ?>
            </div>
<?php } ?>
            <div class="tab-pane" id="tab-replace<?php echo $row; ?>">
                <div class="form-group">
                  <div class="col-sm-12">
                    <p class="form-control" style="width: 100%;">Укажите список слов или фраз в описании товара, которые нужно изменить на нужные вам</p>
                    <p class="form-control" style="width: 100%;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;Перевод осуществляется с помощью <a href="http://translate.yandex.ru/">«Яндекс.Переводчик»</a>. Ознакомиться с условиями использования, тарифами и получить ключ можно на <a href="https://yandex.ru/legal/translate_api/">официальном сайте</a></p>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-1">ПОРЯДОК</div>
                  <div class="col-sm-2">ПРИМЕНИТЬ К</div>
                  <div class="col-sm-2">ИСКАТЬ</div>
                  <div class="col-sm-3">ПРАВИЛО</div>
                  <div class="col-sm-2">ДАННЫЕ</div>
                  <div class="col-sm-1">
                    <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delReplaces ('<?php echo $session_key; ?>');" class="btn btn-primary"><i class="fa fa fa-minus"></i>&nbsp;&nbsp;ОЧИСТИТЬ СПИСОК</a>
                  </div>
                </div>
              <?php if (isset($supplier['replace'])) foreach ($supplier['replace'] as $replace) { ?>
                <div class="form-group">
                  <div class="col-sm-1">
                    <input type="text" name="supplier[<?php echo $session_key; ?>][replace][<?php echo $replace['id']; ?>][sort_order]" value="<?php echo $replace['sort_order']; ?>" class="form-control" />
                  </div>
                  <div class="col-sm-2">
                    <select name="supplier[<?php echo $session_key; ?>][replace][<?php echo $replace['id']; ?>][type]" class="form-control">
                      <option value=""              <?php echo $replace['type']==''?'selected':''; ?>>Правило отключено</option>
                      <option value="description"   <?php echo $replace['type']=='description'?'selected':''; ?> >Описание товара</option>
                      <option value="name"          <?php echo $replace['type']=='name'?'selected':''; ?> >Название товара</option>
                      <option value="model"         <?php echo $replace['type']=='model'?'selected':''; ?> >Модель</option>
                      <option value="attr"          <?php echo $replace['type']=='attr'?'selected':''; ?> >Значение атрибута</option>
                      <option value="option"        <?php echo $replace['type']=='option'?'selected':''; ?> >Значение опции</option>
                      <option value="price"         <?php echo $replace['type']=='price'?'selected':''; ?> >Цена товара</option>
                      <option value="quantity"      <?php echo $replace['type']=='quantity'?'selected':''; ?> >Кол-во товара</option>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <textarea rows="1" name="supplier[<?php echo $session_key; ?>][replace][<?php echo $replace['id']; ?>][txt_before]" class="form-control"><?php echo $replace['txt_before']; ?></textarea>
                  </div>
                  <div class="col-sm-3">
                    <select name="supplier[<?php echo $session_key; ?>][replace][<?php echo $replace['id']; ?>][mode]" class="form-control" >
                      <option value="replace"       <?php echo $replace['mode']=='replace'?'selected':''; ?>   >заменить найденное на</option>
                      <option value="before"        <?php echo $replace['mode']=='before'?'selected':''; ?>    >вставить перед найденным</option>
                      <option value="after"         <?php echo $replace['mode']=='after'?'selected':''; ?>     >вставить после найденного</option>
                      <option value="preg"          <?php echo $replace['mode']=='preg'?'selected':''; ?>      >обработать регуляным выражением</option>
                      <option value="translate"     <?php echo $replace['mode']=='translate'?'selected':''; ?> >перевести</option>
                      <option value="htmlentities"  <?php echo $replace['mode']=='htmlentities'?'selected':''; ?> >преобразовать символы в соответствующие HTML сущности</option>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <textarea rows="1" name="supplier[<?php echo $session_key; ?>][replace][<?php echo $replace['id']; ?>][txt_after]" class="form-control"><?php echo $replace['txt_after']; ?></textarea>
                  </div>
                  <div class="col-sm-1">
                    <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delReplace (<?php echo $replace['id']; ?>);" class="btn btn-primary"><i class="fa fa fa-minus"></i>&nbsp;&nbsp;Удалить</a>
                  </div>
                </div>
              <?php } ?>
                <div class="form-group">
                  <div class="col-sm-1">
                    <input type="text" name="supplier[<?php echo $session_key; ?>][replace][new][sort_order]" value="0" class="form-control" />
                  </div>
                  <div class="col-sm-2">
                    <select name="supplier[<?php echo $session_key; ?>][replace][new][type]" class="form-control" >
                      <option value=""              >Правило отключено</option>
                      <option value="description"   >Описание товара</option>
                      <option value="name"          >Название товара</option>
                      <option value="model"         >Модель</option>
                      <option value="attr"          >Значение атрибута</option>
                      <option value="option"        >Значение опции</option>
                      <option value="price"         >Цена товара</option>
                      <option value="quantity"      >Кол-во товара</option>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <textarea rows="1" name="supplier[<?php echo $session_key; ?>][replace][new][txt_before]" class="form-control" placeholder="Искать"></textarea>
                  </div>
                  <div class="col-sm-3">
                    <select name="supplier[<?php echo $session_key; ?>][replace][new][mode]" class="form-control" >
                      <option value="replace"       >заменить найденное на</option>
                      <option value="before"        >вставить перед найденным</option>
                      <option value="after"         >вставить после найденного</option>
                      <option value="preg"          >обработать регуляным выражением</option>
                      <option value="translate"     >перевести</option>
                      <option value="htmlentities"  >преобразовать символы в соответствующие HTML сущности</option>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <textarea rows="1" name="supplier[<?php echo $session_key; ?>][replace][new][txt_after]" class="form-control" placeholder="данные"></textarea>
                  </div>
                  <div class="col-sm-1">
                    <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); addReplace ('<?php echo $session_key; ?>');" class="btn btn-primary"><i class="fa fa fa-plus"></i>&nbsp;&nbsp;Добавить</a>
                  </div>
                </div>
            </div>

          </div>

              </div>
            <?php $row++; } ?>
              <div class="tab-pane" id="tab-module">
                <h3 class="panel-title" style="padding-left:25px;">НАСТРОЙКИ МОДУЛЯ</h3>
                <hr />
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab-module-general" data-toggle="tab">СИСТЕМА</a></li>
                  <li><a href="#tab-module-add" data-toggle="tab">ПОДКЛЮЧИТЬ ПОСТАВШИКА</a></li>
                  <li><a href="#tab-module-log" data-toggle="tab">СИСТЕМНЫЙ ЛОГ</a></li>
                </ul>
      
                <div class="tab-content">

                  <div class="tab-pane active" id="tab-module-general">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">СИСТЕМА:</label>
                      <div class="col-sm-10">
                        <select name="module[engine]" class="form-control">
                          <option value="Opencart"  <?php echo $module['engine']=='Opencart'?'selected':''; ?> >Opencart</option>
                          <option value="ocStore"   <?php echo $module['engine']=='ocStore'?'selected':''; ?>  >ocStore</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">ТАБЛИЦЫ:</label>
                      <div class="col-sm-10">
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[product_filter]" value="1" <?php echo isset($module['product_filter'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">product_filter существует</div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">ДОПОЛНИТЕЛЬНЫЕ МОДУЛИ:</label>
                      <div class="col-sm-10">
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[mcg2]" value="1" <?php echo isset($module['mcg2'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10"><a target="_blank" href="http://opencart.zone/modules-2-0/mcg-2.html">Мультивалютные товары 2.Х</a></div>
                        </div>
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[oc2]" value="1" <?php echo isset($module['oc2'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10"><a target="_blank" href="http://opencart.zone/modules-2-0/optionsclasters2.html">Каскадные опции 2.Х</a></div>
                        </div>
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[zoannouncement2]" value="1" <?php echo isset($module['zoannouncement2'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10"><a target="_blank" href="http://opencart.zone/modules-2-0/announcement-2.html">Анонсы продуктов 2.Х</a></div>
                        </div>
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[zotuning2]" value="1" <?php echo isset($module['zotuning2'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10"><a target="_blank" href="http://opencart.zone/modules-2-0/tp-2-0.html">Тюнинг продуктов 2.Х</a></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">КАТЕГОРИИ:</label>
                      <div class="col-sm-10">
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[do_category_up]" value="1" <?php echo isset($module['do_category_up'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">Привязать товар ко всем вышестоящим категориям</div>
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[do_category_sort]" value="1" <?php echo isset($module['do_category_sort'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">Сортировать категории по алфавиту (только для версий 1.5.5.Х и выше)</div>
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[do_category_empty]" value="1" <?php echo isset($module['do_category_empty'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">Отображать в списке категории без товаров</div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">ВИЗУАЛИЗАЦИЯ:</label>
                      <div class="col-sm-10">
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[progress]" value="1" <?php echo isset($module['progress'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">Отображать ход процесса (не все хостинги это поддерживают!)</div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">УВЕДОМЛЕНИЯ:</label>
                      <div class="col-sm-10">
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[load_event_log]" value="1" <?php echo isset($module['load_event_log'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">Загружать</div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">ЛОГ:</label>
                      <div class="col-sm-10">
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[load_end_log]" value="1" <?php echo isset($module['load_end_log'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">Загружать только завершающие уведомления</div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">ШАГ ВИЗУАЛИЗАЦИИ:</label>
                      <div class="col-sm-10">
                        <input type="text" name="module[step]" value="<?php echo isset($module['step'])?$module['step']:'10'; ?>"  class="form-control" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">ЗАДЕРЖКИ:</label>
                      <div class="col-sm-10">
                          <input type="text" name="module[sleep]" value="<?php echo isset($module['sleep'])?$module['sleep']:'100'; ?>"  class="form-control" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">КЛЮЧ АПИ ЯНДЕКС ПЕРЕВОДЧИКА:</label>
                      <div class="col-sm-8">
                          <input type="text" name="module[ya_translate]" value="<?php echo isset($module['ya_translate'])?$module['ya_translate']:''; ?>"  class="form-control" />
                      </div>
                      <div class="col-sm-2">
                        <a target="_blank" class="btn btn-primary" href="https://tech.yandex.ru/keys/get/?service=trnsl">Получить ключ!</a>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">SSL:</label>
                      <div class="col-sm-10">
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[SSL]" value="1" <?php echo isset($module['SSL'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">Использовать SSL</div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">DEBUG:</label>
                      <div class="col-sm-10">
                        <div class="col-sm-12">
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[DEBUG]" value="1" <?php echo isset($module['DEBUG'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">Включить отладчик</div>
                          <div class="col-sm-2">
                            <input type="checkbox" name="module[HARD_DEBUG]" value="1" <?php echo isset($module['HARD_DEBUG'])?'checked':''; ?> class="form-control" />
                          </div>
                          <div class="col-sm-10">HARD_DEBUG!</div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">        
                      <label class="col-sm-2 control-label">PHP:</label>
                      <div class="col-sm-10">
                        <?php echo phpinfo(); ?>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="tab-module-add">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">ВЫБЕРИТЕ ТИП ПОСТАВЩИКА ИЗ СПИСКА:</label>
                        <div class="col-sm-8">
                        <select onchange="$('.any_feed').hide(); $('.add_data_for_' + $(this).val() ).show();" class="form-control">
                            <option value="">-- СПИСОК УСТАНОВЛЕННЫХ ПОСТАВЩИКОВ -- </option>
                          <?php foreach ($extensions as $key => $extension) { ?>
                            <option value="<?php echo $key; ?>"><?php echo $extension['name']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <?php foreach ($extensions as $key => $extension) { ?>
                      <div class="any_feed add_data_for_<?php echo $key; ?>">

                        <div class="form-group">
                          <label class="col-sm-4 control-label">ИМЯ:</label>
                          <div class="col-sm-8">
                            <input type="text" name="add[<?php echo $key; ?>][name]" value="<?php echo $extension['name']; ?>" class="form-control" />
                          </div>
                        </div>
                          
                      <?php if ($extension['need_path']=='yes') { ?>
                        <div class="form-group">
                          <label class="col-sm-4 control-label">URL:</label>
                          <div class="col-sm-8">
                            <input type="text" name="add[<?php echo $key; ?>][url]" value="" class="form-control" />
                          </div>
                        </div>
                      <?php } else { ?>
                            <input type="hidden" name="add[<?php echo $key; ?>][url]" value="" class="form-control" />
                      <?php } ?>
  
                      <?php if ($extension['need_key']=='yes') { ?>
                        <div class="form-group">
                          <label class="col-sm-4 control-label">ЛИЦЕНЗИЯ (дополнительные параметры):</label>
                          <div class="col-sm-8">
                            <input type="text" name="add[<?php echo $key; ?>][key]" value="" class="form-control" />
                          </div>
                        </div>
                      <?php } else { ?>
                            <input type="hidden" name="add[<?php echo $key; ?>][key]" value="" class="form-control" />
                      <?php } ?>
  
                        <div class="form-group">
                          <div class="col-sm-4"></div>
                          <div class="col-sm-8">
                            <?php if ($extension['disabled']=='no') { ?>
                              <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); addFeed ('<?php echo $key; ?>');" class="btn btn-primary"><i class="fa fa fa-plus"></i>&nbsp;&nbsp;ДОБАВИТЬ</a>
                            <?php } else { ?>
                              <a onclick="" class="btn btn-primary"><i class="fa fa fa-plus"></i>&nbsp;&nbsp;ДОБАВИТЬ НЕВОЗМОЖНО (РАЗРАБОТКА НЕ ЗАВЕРШЕНА)</a>
                            <?php } ?>
                          </div>
                        </div>
                          

                      </div>
                    <?php } ?>

                  </div>

                  <div class="tab-pane" id="tab-module-log">
                    <div class="form-group">
                      <div class="col-sm-2">ТИП СОБЫТИЯ</div>
                      <div class="col-sm-2">ПОЛЬЗОВАТЕЛЬ</div>
                      <div class="col-sm-2">ВРЕМЯ СОБЫТИЯ</div>
                      <div class="col-sm-4">ДАННЫЕ</div>
                      <div class="col-sm-2">
                        <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delRecords ('0');" class="btn btn-primary"><i class="fa fa fa-minus"></i>&nbsp;&nbsp;ОЧИСТИТЬ ЛОГ</a>
                      </div>
                    </div>

                    <?php if (isset($system_log)) foreach ($system_log as $id => $record) { ?>
                      <div class="form-group">
                        <div class="col-sm-2">
                          <p class="form-control"><?php echo $record['type']; ?></p>
                        </div>
                        <div class="col-sm-2">
                          <p class="form-control"><?php echo $record['user']; ?></p>
                        </div>
                        <div class="col-sm-2">
                          <p class="form-control"><?php echo $record['time']; ?></p>
                        </div>
                        <div class="col-sm-4">
                          <p class="form-control" style="height: initial;"><?php echo $record['data']; ?></p>
                        </div>
                        <div class="col-sm-2">
                          <a onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); delRecord (<?php echo $id; ?>);" class="btn btn-primary"><i class="fa fa fa-minus"></i>&nbsp;&nbsp;Удалить</a>
                        </div>
                      </div>
                    <?php } ?>

                  </div>

                </div>


              </div>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
var row = <?php echo $row; ?>;
$('#suppliers a:first').tab('show');  

function delFeed (supplier) {
var pData = {supplier:supplier};
var url = 'index.php?route=module/zoxml2/delfeed&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function addFeed (extension) {
var pData = $('input[type=\'text\'][name*=\'add[' + extension + ']\'], input[type=\'hidden\'][name*=\'add[' + extension + ']\']');
var url = 'index.php?route=module/zoxml2/addfeed&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function saveModule () {
var pData = $('select[name*=\'module\'], input[type=\'radio\'][name*=\'module\'], input[type=\'text\'][name*=\'module\'], input[type=\'hidden\'][name*=\'module\'], input[type=\'checkbox\'][name*=\'module\']:checked');
var url = 'index.php?route=module/zoxml2/module&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function addReplace (session_key) {
var pData = { supplier    :session_key,
              type        :$('select[name=\'supplier[' + session_key + '][replace][new][type]\']').val(),
              mode        :$('select[name=\'supplier[' + session_key + '][replace][new][mode]\']').val(),
              txt_before  :$('textarea[name=\'supplier[' + session_key + '][replace][new][txt_before]\']').val(),
              txt_after   :$('textarea[name=\'supplier[' + session_key + '][replace][new][txt_after]\']').val(),
              sort_order  :$('input[type=\'text\'][name=\'supplier[' + session_key + '][replace][new][sort_order]\']').val()
              };
var url = 'index.php?route=module/zoxml2/addreplace&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function delReplaces (session_key) {
var pData = { supplier    :session_key};
var url = 'index.php?route=module/zoxml2/delreplaces&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function delReplace (id) {
var pData = { id    :id};
var url = 'index.php?route=module/zoxml2/delreplace&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function saveSupplier (session_key) {
var pData = $('textarea[name*=\'supplier[' + session_key + ']\'], select[name*=\'supplier[' + session_key + ']\'], input[type=\'radio\'][name*=\'supplier[' + session_key + ']\'], input[type=\'text\'][name*=\'supplier[' + session_key + ']\'], input[type=\'hidden\'][name*=\'supplier[' + session_key + ']\'], input[type=\'checkbox\'][name*=\'supplier[' + session_key + ']\']:checked');
var url = 'index.php?route=module/zoxml2/settings&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function addVendor (id) {
var pData = {id:id};
var url = 'index.php?route=module/zoxml2/addvendor&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function delRecord (id) {
var pData = {id:id};
var url = 'index.php?route=module/zoxml2/delrecord&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function delRecords (supplier) {
var pData = {supplier:supplier};
var url = 'index.php?route=module/zoxml2/delrecords&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function delEvent (id) {
var pData = {id:id};
var url = 'index.php?route=module/zoxml2/delevent&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function delEvents (supplier) {
var pData = {supplier:supplier};
var url = 'index.php?route=module/zoxml2/delevents&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function scanSupplier (session_key,start_id) {
var pData = {supplier:session_key};
var url = 'index.php?route=module/zoxml2/scansupplier&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
<?php if (!isset($module['progress'])) { ?>
  success: function() { 
    location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
    },
  error: function(xhr, ajaxOptions, thrownError) { 
    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
    location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
    },
<?php } ?>
data: pData
  });
<?php if (isset($module['progress'])) { ?>
  html =  '<div class="form-group">';

  html += '<div class="col-sm-10">';            
  html += '<p class="form-control" style="background-color: lightgreen;text-align: center;">Загрузка запущена в фоновом режиме. Вы можете покинуть эту страницу или дождаться окончания загрузки.</p>';            
  html += '</div>';            

  html += '</div>';            
  $('#log_progress_' + session_key).after(html);
  doLog (session_key,start_id);
<?php } ?>
}

function linkSupplier (session_key,start_id) {
var pData = {supplier:session_key};
var url = 'index.php?route=module/zoxml2/linksupplier&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
<?php if (!isset($module['progress'])) { ?>
  success: function() { 
    location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
    },
  error: function(xhr, ajaxOptions, thrownError) { 
    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
    location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
    },
<?php } ?>
data: pData
  });
<?php if (isset($module['progress'])) { ?>
  html =  '<div class="form-group">';

  html += '<div class="col-sm-10">';            
  html += '<p class="form-control" style="background-color: lightgreen;text-align: center;">Загрузка запущена в фоновом режиме. Вы можете покинуть эту страницу или дождаться окончания загрузки.</p>';            
  html += '</div>';            

  html += '</div>';            
  $('#log_progress_' + session_key).after(html);
  doLog (session_key,start_id);
<?php } ?>
}

function addAllVendors (session_key) {
var pData = {supplier:session_key};
var url = 'index.php?route=module/zoxml2/addallvendors&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function addAllCategories (session_key) {
var pData = {supplier:session_key};
var url = 'index.php?route=module/zoxml2/addallcategories&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function() { 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function doLog (supplier,start_id) {
var pData = {supplier:supplier,start_id:start_id};
var url = 'index.php?route=module/zoxml2/progress&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
data: pData,
success: function(json) { 
  for (var i in json) {
    start_id = json[i]['id'];
    html =  '<div class="form-group">';

    html += '<div class="col-sm-2">';            
    html += '<p class="form-control">' + json[i]['type'] + '</p>';            
    html += '</div>';            

    html += '<div class="col-sm-2">';            
    html += '<p class="form-control">' + json[i]['user'] + '</p>';            
    html += '</div>';            

    html += '<div class="col-sm-2">';            
    html += '<p class="form-control">' + json[i]['time'] + '</p>';            
    html += '</div>';            

    html += '<div class="col-sm-4">';            
    html += '<p class="form-control" style="height: initial;">' + json[i]['data'] + '</p>';            
    html += '</div>';            

    html += '<div class="col-sm-2">';            
    html += '</div>';            
    html += '</div>';            
    $('#log_progress_' + supplier).after(html);
    if (json[i]['type']=='end') location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
    }
  setTimeout(
    function(){
      doLog (supplier,start_id);
      },
    2000);
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
  }
  });
}

function loadSupplier (session_key,start_id) {
var pData = {supplier:session_key};
var url = 'index.php?route=module/zoxml2/loadsupplier&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
<?php if (!isset($module['progress'])) { ?>
  success: function() { 
    location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>'; 
    },
  error: function(xhr, ajaxOptions, thrownError) { 
    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
    location = 'index.php?route=module/zoxml2&token=<?php echo $token; ?>';
    },
<?php } ?>
data: pData
  });
<?php if (isset($module['progress'])) { ?>
  html =  '<div class="form-group">';

  html += '<div class="col-sm-10">';            
  html += '<p class="form-control" style="background-color: lightgreen;text-align: center;">Загрузка запущена в фоновом режиме. Вы можете покинуть эту страницу или дождаться окончания загрузки.</p>';            
  html += '</div>';            

  html += '</div>';            
  $('#log_progress_' + session_key).after(html);
  doLog (session_key,start_id);
<?php } ?>
}
//--></script>
</div>
<?php echo $footer; ?>