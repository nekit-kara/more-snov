<!-- 
	Ajax Quick Checkout 
	v6.0.0
	Dreamvention.com 
	d_quickcheckout/payment_address.tpl 
-->
<div id="payment_address" class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>"></div>
<script type="text/html" id="payment_address_template">
<div class="b-form">
	<div class="b-form__title"><%= model.config.title %></div>
</div>

<form id="payment_address_form" class="form-horizontal">

</form>
</script>

<script>
$(function() {
	qc.paymentAddress = $.extend(true, {}, new qc.PaymentAddress(<?php echo $json; ?>));
	qc.paymentAddressView = $.extend(true, {}, new qc.PaymentAddressView({
		el:$("#payment_address"), 
		model: qc.paymentAddress, 
		template: _.template($("#payment_address_template").html())
	}));
	qc.paymentAddressView.setZone(qc.paymentAddress.get('payment_address.country_id'));

})
</script>