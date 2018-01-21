<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if($bigshop_refine_categories == 1) { ?>
      <?php if ($categories) { ?>
      <h3><?php echo $text_refine; ?></h3>
      <?php if($bigshop_refine_categories_images == 1) { ?>
      <div class="category-list-thumb row" style="margin-bottom:0px;">
        <?php $counter = 0; foreach ($categories as $category) {?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class_thumb = 'col-lg-3 col-md-3 col-sm-2 col-xs-4'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class_thumb = 'col-lg-2 col-md-2 col-sm-2 col-xs-4'; ?>
        <?php } else { ?>
        <?php $class_thumb = 'col-lg-1 col-md-2 col-sm-2 col-xs-4'; ?>
        <?php } ?>
        <div class="<?php echo $class_thumb; ?>">
          <?php if ($category['thumb']) { ?>
          <a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" /></a>
          <?php } else { ?>
          <a href="<?php echo $category['href']; ?>"><img src="catalog/view/theme/bigshop/image/no_image.jpg" alt="<?php echo $category['name']; ?>" /></a>
          <?php } ?>
          <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a> </div>
        <?php $counter++; } ?>
      </div>
      <?php if ($column_left && $column_right) { ?>
  <script>
$(document).ready(function(){
$screensize = $(window).width();
    
	if ($screensize > 991) {
        $('.category-list-thumb > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $('.category-list-thumb > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-md-block visible-xs-block"></div>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
   
	if ($screensize > 991) {
        $(".category-list-thumb > .clearfix.visible-lg-block").remove();
        $('.category-list-thumb > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $(".category-list-thumb > .clearfix.visible-lg-block").remove();
        $('.category-list-thumb > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-md-block visible-xs-block"></div>');
    }
});});
</script>
  <?php } elseif ($column_left || $column_right) { ?>
  <script>
$(document).ready(function(){
$screensize = $(window).width();
    
	if ($screensize > 991) {
        $('.category-list-thumb > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $('.category-list-thumb > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-md-block visible-xs-block"></div>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
   
	if ($screensize > 991) {
        $(".category-list-thumb > .clearfix.visible-lg-block").remove();
        $('.category-list-thumb > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $(".category-list-thumb > .clearfix.visible-lg-block").remove();
        $('.category-list-thumb > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-md-block visible-xs-block"></div>');
    }
});});
</script>
  <?php } else { ?>
  <script>
$(document).ready(function(){
$screensize = $(window).width();
    
	if ($screensize > 991) {
        $('.category-list-thumb > div:nth-child(12n)').after('<div class="clearfix visible-lg-block visible-md-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $('.category-list-thumb > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-md-block visible-xs-block"></div>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
   
	if ($screensize > 991) {
        $(".category-list-thumb > .clearfix.visible-lg-block").remove();
        $('.category-list-thumb > div:nth-child(12n)').after('<div class="clearfix visible-lg-block visible-md-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $(".category-list-thumb > .clearfix.visible-lg-block").remove();
        $('.category-list-thumb > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-md-block visible-xs-block"></div>');
    }
});});
</script>
  <?php } ?>
      <?php } else { ?>
      <div class="category-list row">
        <?php if (count($categories) <= 5) { ?>
        <div class="col-sm-3">
          <ul class="list-item">
            <?php foreach ($categories as $category) { ?>
            <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <?php } else { ?>
        <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
        <div class="col-sm-3">
          <ul class="list-item">
            <?php foreach ($categories as $category) { ?>
            <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
        <?php } ?>
      </div>
      <?php if ($column_left && $column_right) { ?>
  <script>
$(document).ready(function(){
$screensize = $(window).width();
    if ($screensize > 1199) {
        $('.category-list > div:nth-child(4n)').after('<div class="clearfix visible-lg-block"></div>');
    }
    if ($screensize < 1199) {
        $('.category-list > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
	if ($screensize < 991) {
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $('.category-list > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(4n)').after('<div class="clearfix visible-lg-block"></div>');
    } 
    if ($screensize < 1199) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(4n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
	if ($screensize < 991) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
});});
</script>
  <?php } elseif ($column_left || $column_right) { ?>
  <script>
$(document).ready(function(){
$screensize = $(window).width();
    if ($screensize > 1199) {
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block"></div>');
    }
    if ($screensize < 1199) {
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
	if ($screensize < 991) {
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $('.category-list > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block"></div>');
    } 
    if ($screensize < 1199) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
	if ($screensize < 991) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
});});
</script>
  <?php } else { ?>
  <script type="text/javascript">
$(document).ready(function(){
$screensize = $(window).width();
    if ($screensize > 1199) {
        $('.category-list > div:nth-child(12n)').after('<div class="clearfix visible-lg-block"></div>');
    }
    if ($screensize < 1199) {
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
	if ($screensize < 991) {
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $('.category-list > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(12n)').after('<div class="clearfix visible-lg-block"></div>');
    } 
    if ($screensize < 1199) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-md-block"></div>');
    }
	if ($screensize < 991) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(6n)').after('<div class="clearfix visible-lg-block visible-sm-block"></div>');
    }
	if ($screensize < 767) {
        $(".category-list > .clearfix.visible-lg-block").remove();
        $('.category-list > div:nth-child(3n)').after('<div class="clearfix visible-lg-block visible-xs-block"></div>');
    }
});});
</script>
  <?php } ?>
      <?php } ?>
      <?php } ?>
      <?php } ?>
      <?php if ($products) { ?>
      <div class="product-filter">
        <div class="row">
          <div class="col-md-5">
            <div class="btn-group">
              <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
              <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
            </div>
            <a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a> </div>
            
            <div class="col-md-4 col-xs-6">
          <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-sort"><?php echo $text_sort; ?></label>
            <select id="input-sort" class="form-control" onchange="location = this.value;">
              <?php foreach ($sorts as $sorts) { ?>
              <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
              <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-md-3 col-xs-6">
          <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-limit"><?php echo $text_limit; ?></label>
            <select id="input-limit" class="form-control" onchange="location = this.value;">
              <?php foreach ($limits as $limits) { ?>
              <?php if ($limits['value'] == $limit) { ?>
              <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        
          
        </div>
      </div>
      <br />
      <div class="row products-category">
        <?php foreach ($products as $product) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb b-product-custom" data-id="<?=$product['product_id']?>">
            <div class="image b-product-custom__image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
                <div class="caption b-product-custom__caption">
                  <h4 class="b-product-custom__title"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                  <div class="b-product-custom__params">

			<div style="padding-top:5px;">Модель: <?php echo $product['model']; ?></div>
			<div style="padding-top:5px;">Производитель: <?php echo $product['manufacturer']; ?></div>
			
                      <?php $params_block; ?>
                  </div>
                </div><!--/.b-product-custom__caption-->
                <?php $options = $product['options'] ?>

<script type="text/javascript"><!--
$(document).ready(function() {
(function($container){
function price_format(price)
{ 
    c = <?php echo (empty($autocalc_currency['decimals']) ? "0" : $autocalc_currency['decimals'] ); ?>;
    d = '<?php echo $autocalc_currency['decimal_point']; ?>'; // decimal separator
    t = '<?php echo $autocalc_currency['thousand_point']; ?>'; // thousands separator
    s_left = '<?php echo str_replace("'", "\'", $autocalc_currency['symbol_left']); ?>';
    s_right = '<?php echo str_replace("'", "\'", $autocalc_currency['symbol_right']); ?>';
    n = price * <?php echo $autocalc_currency['value']; ?>;
    i = parseInt(n = Math.abs(n).toFixed(c)) + ''; 
    j = ((j = i.length) > 3) ? j % 3 : 0; 
    price_text = s_left + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '') + s_right; 
    
    <?php if (!empty($autocalc_currency2)) { ?>
    c = <?php echo (empty($autocalc_currency2['decimals']) ? "0" : $autocalc_currency2['decimals'] ); ?>;
    d = '<?php echo $autocalc_currency2['decimal_point']; ?>'; // decimal separator
    t = '<?php echo $autocalc_currency2['thousand_point']; ?>'; // thousands separator
    s_left = '<?php echo str_replace("'", "\'", $autocalc_currency2['symbol_left']); ?>';
    s_right = '<?php echo str_replace("'", "\'", $autocalc_currency2['symbol_right']); ?>';
    n = price * <?php echo $autocalc_currency2['value']; ?>;
    i = parseInt(n = Math.abs(n).toFixed(c)) + ''; 
    j = ((j = i.length) > 3) ? j % 3 : 0; 
    price_text += '  <span class="currency2">(' + s_left + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '') + s_right + ')</span>'; 
    <?php } ?>
    
    return price_text;
}

function calculate_tax(price)
{
    <?php // Process Tax Rates
      if (isset($tax_rates) && !empty($tax)) {
         foreach ($tax_rates as $tax_rate) {
           if ($tax_rate['type'] == 'F') {
             echo 'price += '.$tax_rate['rate'].';';
           } elseif ($tax_rate['type'] == 'P') {
             echo 'price += (price * '.$tax_rate['rate'].') / 100.0;';
           }
         }
      }
    ?>
    return price;
}

function process_discounts(price, quantity)
{
    <?php
      foreach ($dicounts_unf as $discount) {
        echo 'if ((quantity >= '.$discount['quantity'].') && ('.$discount['price'].' < price)) price = '.$discount['price'].';'."\n";
      }
    ?>
    return price;
}


animate_delay = 20;

main_price_final = calculate_tax(<?php echo $price_value; ?>);
main_price_start = calculate_tax(<?php echo $price_value; ?>);
main_step = 0;
main_timeout_id = 0;

function animateMainPrice_callback(product) {

    main_price_start += main_step;
    if ((main_step > 0) && (main_price_start > main_price_final)){
        main_price_start = main_price_final;
    } else if ((main_step < 0) && (main_price_start < main_price_final)) {
        main_price_start = main_price_final;
    } else if (main_step == 0) {
        main_price_start = main_price_final;
    }
    if(product) {
      main_price_start = parseFloat($(product.target).find('option:selected').attr('data-price')) + parseFloat($(product.target).find('option:first').attr('data-price'));
      var autoCalc = $(product.target).closest('.b-product-custom').find('.autocalc-product-price');
      autoCalc.html(price_format(main_price_start));
    } else {
      //$container.find('.autocalc-product-price').html( price_format(main_price_start) );
    }
    
    
    if (main_price_start != main_price_final) {
        main_timeout_id = setTimeout(animateMainPrice_callback, animate_delay);
    }
}

function animateMainPrice(price, product) {
    main_price_start = main_price_final;
    main_price_final = price;
    main_step = (main_price_final - main_price_start) / 10;
    
    clearTimeout(main_timeout_id);
    main_timeout_id = setTimeout(animateMainPrice_callback(product), animate_delay);
}


<?php if ($special) { ?>
special_price_final = calculate_tax(<?php echo $special_value; ?>);
special_price_start = calculate_tax(<?php echo $special_value; ?>);
special_step = 0;
special_timeout_id = 0;

function animateSpecialPrice_callback() {
    special_price_start += special_step;
    
    if ((special_step > 0) && (special_price_start > special_price_final)){
        special_price_start = special_price_final;
    } else if ((special_step < 0) && (special_price_start < special_price_final)) {
        special_price_start = special_price_final;
    } else if (special_step == 0) {
        special_price_start = special_price_final;
    }
    
    $container.find('.autocalc-product-special').html( price_format(special_price_start) );
    
    if (special_price_start != special_price_final) {
        special_timeout_id = setTimeout(animateSpecialPrice_callback, animate_delay);
    }
}

function animateSpecialPrice(price) {
    special_price_start = special_price_final;
    special_price_final = price;
    special_step = (special_price_final - special_price_start) / 10;
    
    clearTimeout(special_timeout_id);
    special_timeout_id = setTimeout(animateSpecialPrice_callback, animate_delay);
}
<?php } ?>


function recalculateprice(product)
{
    var main_price = <?php echo (float)$price_value; ?>;
    var input_quantity = Number($container.find('input[name="quantity"]').val());
    var special = <?php echo (float)$special_value; ?>;
    var tax = 0;
    discount_coefficient = 1;
    
    if (isNaN(input_quantity)) input_quantity = 0;
    
    <?php if ($special) { ?>
        special_coefficient = <?php echo ((float)$price_value/(float)$special_value); ?>;
    <?php } else { ?>
        <?php if (empty($autocalc_option_discount)) { ?>
            main_price = process_discounts(main_price, input_quantity);
            tax = process_discounts(tax, input_quantity);
        <?php } else { ?>
            if (main_price) discount_coefficient = process_discounts(main_price, input_quantity) / main_price;
        <?php } ?>
    <?php } ?>
    
    
    var option_price = 0;
    
    <?php if ($points) { ?>
      var points = <?php echo (float)$points_value; ?>;
      $container.find('input:checked,option:selected').each(function() {
          if ($(this).data('points')) points += Number($(this).data('points'));
      });
      $container.find('.autocalc-product-points').html(points);
    <?php } ?>
    
    $container.find('input:checked,option:selected').each(function() {
      if ($(this).data('prefix') == '=') {
        option_price += Number($(this).data('price'));
        main_price = 0;
        special = 0;
      }
    });
    
    $container.find('input:checked,option:selected').each(function() {
      if ($(this).data('prefix') == '+') {
        option_price += Number($(this).data('price'));
      }
      if ($(this).data('prefix') == '-') {
        option_price -= Number($(this).data('price'));
      }
      if ($(this).data('prefix') == 'u') {
        pcnt = 1.0 + (Number($(this).data('price')) / 100.0);
        option_price *= pcnt;
        main_price *= pcnt;
        special *= pcnt;
      }
      if ($(this).data('prefix') == 'd') {
        pcnt = 1.0 - (Number($(this).data('price')) / 100.0);
        option_price *= pcnt;
        main_price *= pcnt;
        special *= pcnt;
      }
      if ($(this).data('prefix') == '*') {
        option_price *= Number($(this).data('price'));
        main_price *= Number($(this).data('price'));
        special *= Number($(this).data('price'));
      }
      if ($(this).data('prefix') == '/') {
        option_price /= Number($(this).data('price'));
        main_price /= Number($(this).data('price'));
        special /= Number($(this).data('price'));
      }
    });
    
    special += option_price;
    main_price += option_price;

    <?php if ($special) { ?>
      <?php if (empty($autocalc_option_special))  { ?>
        main_price = special * special_coefficient;
      <?php } else { ?>
        special = main_price / special_coefficient;
      <?php } ?>
      tax = special;
    <?php } else { ?>
      <?php if (!empty($autocalc_option_discount)) { ?>
          main_price *= discount_coefficient;
      <?php } ?>
      tax = main_price;
    <?php } ?>
    
    // Process TAX.
    main_price = calculate_tax(main_price);
    special = calculate_tax(special);
    
    <?php if (!$autocalc_not_mul_qty) { ?>
    if (input_quantity > 0) {
      main_price *= input_quantity;
      special *= input_quantity;
      tax *= input_quantity;
    }
    <?php } ?>

    // Display Main Price
    animateMainPrice(main_price, product);
      
    <?php if ($special) { ?>
      animateSpecialPrice(special);
    <?php } ?>
}

$container.find('input[type="checkbox"]').on('change', function() { recalculateprice(); });
$container.find('input[type="radio"]').on('change', function() { recalculateprice(); });
$container.find('select').on('change', function(e) { recalculateprice(e); });

(function($quantity){
$quantity.data('val', $quantity.val());
(function() {
    if ($quantity.val() != $quantity.data('val')){
        $quantity.data('val',$quantity.val());
        recalculateprice();
    }
    setTimeout(arguments.callee, 250);
})();
})($container.find('input[name="quantity"]'));

<?php if ($autocalc_select_first) { ?>
$container.find('select[name^="option"] option[value=""]').remove();
last_name = '';
$container.find('input[type="radio"][name^="option"]').each(function(){
    if ($(this).attr('name') != last_name) $(this).prop('checked', true);
    last_name = $(this).attr('name');
});
<?php } ?>

recalculateprice();

})($('#quick_order').length ? $('#quick_order') : $('.b-product-custom'));
});

//--></script>
      
                <?php if ($options) { ?>
                <? //print_r($product) ?>
                  <? foreach($options as $option) { ?>
                    <?php if ($option['type'] == 'select') { ?>
                    <div class="b-product-size b-product-size_small">
                      <select data-name="<?php echo $option['product_option_id']; ?>" name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="b-product-size__select">
                          <option class="b-product-size__option" value="<?=$product['default_price']?>" data-price="<?=$product['default_price']?>" disabled>Размер
                          </option>
                        <?php foreach ($option['product_option_value'] as  $key => $option_value) { ?>
                        <? if($key==0) { ?>
                          <option class="b-product-size__option" value="<?php echo $option_value['product_option_value_id']; ?>" data-points="<?php echo (isset($option_value['points_value']) ? $option_value['points_value'] : 0); ?>" data-prefix="<?php echo $option_value['price_prefix']; ?>" data-price="<?php echo $option_value['price_value']; ?>" ><?php echo $option_value['name']; ?>
                          </option>
                          <? } else { ?>
                            <option class="b-product-size__option" value="<?php echo $option_value['product_option_value_id']; ?>" data-points="<?php echo (isset($option_value['points_value']) ? $option_value['points_value'] : 0); ?>" data-prefix="<?php echo $option_value['price_prefix']; ?>" data-price="<?php echo $option_value['price_value']; ?>"><?php echo $option_value['name']; ?>
                            </option>
                          <? } ?>
                        <? } ?>
                      </select>
                      <div class="b-product-like" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"></div>
                      <div class="b-product-compare" onclick="compare.add('<?php echo $product['product_id']; ?>');"></div>
                      <input style="display: none" type="text" name="quantity" value="1" size="2" id="input-quantity" class="form-control" />
                    </div>
                    <? } ?>
                  <? } ?>
                  <? } else { ?>
                  <div class="b-product-size b-product-size_small">
                    <div class="b-product-like" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"></div>
                    <div class="b-product-compare" onclick="compare.add('<?php echo $product['product_id']; ?>');"></div>
                  </div>
                  <? } ?>
                <div class="clear"></div>
                <?php if ($product['price']) { ?>
                <div class="b-product-buy b-product-buy_small">
                  <div class="b-product-price b-product-price_small">
                    <?php if (!$product['special']) { ?>
                        <div class="b-product-price__new"><?php echo $product['price']; ?></div>
                    <?php } else { ?>
                        <div class="b-product-price__old"><?php echo $product['price']; ?></div>
                        <div class="b-product-price__new"><?php echo $product['special']; ?></div>
                    <?php } ?>
                  </div>
                  <div class="b-buy-button b-buy-button_small">
                      <div class="cart">
                          <div>
                              <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');" data-loading-text="Загрузка..." class="b-buy-button__link"><?php echo $button_cart; ?></button>
                          </div>
                      </div>
                  </div>
                  <div class="clear"></div>
                </div><!-- /.b-product-buy-->
                <?php } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } ?>
    <?php if ($description) { ?>
        <br>
    <div class="row">
        <!--<?php if ($thumb) { ?>
        <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
        <?php } ?>-->
        <?php if ($description) { ?>
        <div class="col-sm-12"><?php echo $description; ?></div>
        <?php } ?>
    </div>
    <?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php if($bigshop_category_product_per_row == 'pr3') { ?>
<script type="text/javascript">
$(document).ready(function(){
$(document).on('click', '#grid-view', function(e){
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-4 col-xs-6');
		
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
		$('.product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
		$('.product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
});
localStorage.setItem('display', 'grid');
$('.btn-group').find('#grid-view').addClass('selected');
$('.btn-group').find('#list-view').removeClass('selected');
	});   
if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}
});
</script>
<?php } elseif ($bigshop_category_product_per_row == 'pr4') {?>
<script type="text/javascript">
$(document).ready(function(){
$(document).on('click', '#grid-view', function(e){
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6');
		
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
		$('.product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
		$('.product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
});
localStorage.setItem('display', 'grid');
$('.btn-group').find('#grid-view').addClass('selected');
$('.btn-group').find('#list-view').removeClass('selected');
	});   
if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}
});
</script>
<?php } elseif ($bigshop_category_product_per_row == 'pr5') {?>
<script type="text/javascript">
$(document).ready(function(){
$(document).on('click', '#grid-view', function(e){
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-5ths col-md-5ths col-sm-4 col-xs-6');
		
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(5n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(5n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
		$('.product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(5n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(5n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
		$('.product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
});
localStorage.setItem('display', 'grid');
$('.btn-group').find('#grid-view').addClass('selected');
$('.btn-group').find('#list-view').removeClass('selected');
	});   
if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}
});
</script>
<?php } elseif ($bigshop_category_product_per_row == 'pr6') {?>
<script type="text/javascript">
$(document).ready(function(){
$(document).on('click', '#grid-view', function(e){
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-2 col-md-2 col-sm-4 col-xs-6');
		
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(6n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(6n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
		$('.product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(6n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(6n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category > .clearfix").remove();
        $('.product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category > .clearfix").remove();
		$('.product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
});
localStorage.setItem('display', 'grid');
$('.btn-group').find('#grid-view').addClass('selected');
$('.btn-group').find('#list-view').removeClass('selected');
	});   
if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}
});
</script>
<?php } ?>
<?php echo $footer; ?>