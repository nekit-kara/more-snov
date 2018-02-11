<!-- 
	Ajax Quick Checkout 
	v6.0.0
	Dreamvention.com 
	d_quickcheckout/confirm.tpl 
-->
<div id="confirm_view" class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>"></div>
<script type="text/html" id="confirm_template">
	<div class="b-order-cart">
		<div class="b-order-cart__button">
			<div class="b-buy-button">
				<button id="qc_confirm_order" class="btn btn-primary btn-lg btn-block b-buy-button__link" <%= model.show_confirm ? '' : 'disabled="disabled"' %>><% if(Number(model.payment_popup)) { %><?php echo $button_continue; ?><% }else{ %><?php echo $button_confirm; ?><% } %></span></button>
			</div>
		</div>
		<div class="clear"></div>
		<div class="b-order-cart__notice">
			Подтверждая заказ, я принимаю пользовательское соглашение и <br> соглашаюсь на обработку персональных данных.
		</div>
	</div>
	<div class="clear"></div>
</script>
<script>

    $(function() {
        qc.confirm = $.extend(true, {}, new qc.Confirm(<?php echo $json; ?>));
        qc.confirmView = $.extend(true, {}, new qc.ConfirmView({
            el:$("#confirm_view"),
            model: qc.confirm,
            template: _.template($("#confirm_template").html())
        }));
    });

</script>