<?php if ($module['position'] == 'content_top' || $module['position'] == 'content_bottom') { ?>
<?php if($bigshop_specials_slider_product_style == '1') { ?>
<h3><?php echo $heading_title; ?></h3>
<div class="row products-category special_carousel mod">
  <?php foreach ($products as $product) { ?>
  <div class="product-layout">
    <div class="product-thumb b-product-custom clearfix">
      <div class="image b-product-custom__image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption b-product-custom__caption">
          <h4 class="b-product-custom__title"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
          <div class="b-product-custom__params">

			<div style="padding-top:5px;">Модель: <?php echo $product['model']; ?></div>
			<div style="padding-top:5px;">Производитель: <?php echo $product['manufacturer']; ?></div>
			
              <?php $params_block; ?>
          </div>
      </div>
      <div class="b-product-size b-product-size_small">
          <div class="b-product-like" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"></div>
          <div class="b-product-compare" onclick="compare.add('<?php echo $product['product_id']; ?>');"></div>
      </div>
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
          <?php if($bigshop_percentage_discount_badge == 1) { ?><span class="saving">-<?php echo $product['saving']; ?>%</span><?php } ?>
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
  <?php } ?>
</div>
<?php if($bigshop_specials_slider_product_per_row == '3') { ?>
<script type="text/javascript">
$(document).ready(function(){
$('#content .special_carousel .product-layout').attr('class', 'product-grid col-lg-4 col-md-4 col-sm-4 col-xs-6');
$('#content .special_carousel').css('display', 'block');
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category.special_carousel > .clearfix").remove();
		$('.products-category.special_carousel .product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category.special_carousel > .clearfix").remove();
		$('.products-category.special_carousel .product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
});
});
</script>
<?php } elseif ($bigshop_specials_slider_product_per_row == '4') {?>
<script type="text/javascript">
$(document).ready(function(){
$('#content .special_carousel .product-layout').attr('class', 'product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6');
$('#content .special_carousel').css('display', 'block');
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category.special_carousel > .clearfix").remove();
		$('.products-category.special_carousel .product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category.special_carousel > .clearfix").remove();
		$('.products-category.special_carousel .product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
});
});
</script>
<?php } elseif ($bigshop_specials_slider_product_per_row == '5') {?>
<script type="text/javascript">
$(document).ready(function(){
$('#content .special_carousel .product-layout').attr('class', 'product-grid col-lg-5ths col-md-5ths col-sm-4 col-xs-6');
$('#content .special_carousel').css('display', 'block');
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(5n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(5n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category.special_carousel > .clearfix").remove();
		$('.products-category.special_carousel .product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(4n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');

    }
	if ($screensize < 767) {
        $(".products-category.special_carousel > .clearfix").remove();
		$('.products-category.special_carousel .product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
});
});
</script>
<?php } elseif ($bigshop_specials_slider_product_per_row == '6') {?>
<script type="text/javascript">
$(document).ready(function(){
$('#content .special_carousel .product-layout').attr('class', 'product-grid col-lg-2 col-md-2 col-sm-4 col-xs-6');
$('#content .special_carousel').css('display', 'block');
$screensize = $(window).width();
    if ($screensize > 1199) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(6n)').after('<span class="clearfix visible-lg-block"></span>');
    }
    if ($screensize < 1199) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(6n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
		$(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category.special_carousel > .clearfix").remove();
		$('.products-category.special_carousel .product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
$( window ).resize(function() {
    $screensize = $(window).width();
    if ($screensize > 1199) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(6n)').after('<span class="clearfix visible-lg-block"></span>');
    } 
    if ($screensize < 1199) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(6n)').after('<span class="clearfix visible-lg-block visible-md-block"></span>');
    }
	if ($screensize < 991) {
        $(".products-category.special_carousel > .clearfix").remove();
        $('.products-category.special_carousel .product-grid:nth-child(3n)').after('<span class="clearfix visible-lg-block visible-sm-block"></span>');
    }
	if ($screensize < 767) {
        $(".products-category.special_carousel > .clearfix").remove();
		$('.products-category.special_carousel .product-grid:nth-child(2n)').after('<span class="clearfix visible-lg-block visible-xs-block"></span>');
    }
});
});
</script>
<?php } ?>

<?php } else { ?>
<h3><?php echo $heading_title; ?></h3>
<div class="owl-carousel special_carousel">
  <?php foreach ($products as $product) { ?>
    <div class="product-thumb clearfix">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <!--<p><?php echo $product['description']; ?></p>-->
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php if($bigshop_percentage_discount_badge == 1) { ?><span class="saving">-<?php echo $product['saving']; ?>%</span><?php } ?>
          <?php } ?>
        </p>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <div class="button-group">
        <button class="btn-primary" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><span><?php echo $button_cart; ?></span></button>
        <div class="add-to-links">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#content .owl-carousel.special_carousel").owlCarousel({
		itemsCustom : [[320, 2],[600, 3],[768, 3],[992, <?php echo $bigshop_specials_slider_product_per_row; ?>],[1199, <?php echo $bigshop_specials_slider_product_per_row; ?>]],											   
		lazyLoad : true,
		navigation : true,
		navigationText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		scrollPerPage : true
    }); 
		});
</script>
<?php } ?>

<?php } else { ?>
<h3><?php echo $heading_title; ?></h3>
<div class="row products-category mod">
  <?php foreach ($products as $product) { ?>
  <div class="product-layout">
    <div class="product-thumb clearfix">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <!--<p><?php echo $product['description']; ?></p>-->
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php if($bigshop_percentage_discount_badge == 1) { ?><span class="saving">-<?php echo $product['saving']; ?>%</span><?php } ?>
          <?php } ?>
        </p>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
    </div>
  <?php } ?>
</div>
<?php } ?>