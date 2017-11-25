<?php if (count($currencies) > 1) { ?>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-currency">
  <div class="btn-group">
    <button class="btn-link dropdown-toggle" data-toggle="dropdown"> <span>
    <?php if($bigshop_language_currency_title == 1) {?>
    <?php echo $text_currency; ?>:
    <?php } ?>
    <?php foreach ($currencies as $currency) { ?>
    <?php if ($currency['symbol_left'] && $currency['code'] == $code) { ?>
    <?php echo $currency['symbol_left']; ?> <?php echo $currency['code']; ?>
    <?php } elseif ($currency['symbol_right'] && $currency['code'] == $code) { ?>
    <?php echo $currency['code']; ?> <?php echo $currency['symbol_right']; ?>
    <?php } ?>
    <?php } ?>
    <i class="fa fa-caret-down"></i></span></button>
    <ul class="dropdown-menu">
      <?php foreach ($currencies as $currency) { ?>
      <?php if ($currency['symbol_left']) { ?>
      <li>
        <button class="currency-select btn btn-link btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left']; ?> <?php echo $currency['title']; ?></button>
      </li>
      <?php } else { ?>
      <li>
        <button class="currency-select btn btn-link btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right']; ?> <?php echo $currency['title']; ?></button>
      </li>
      <?php } ?>
      <?php } ?>
    </ul>
  </div>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
<?php } ?>
