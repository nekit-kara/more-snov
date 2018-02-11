<!-- 
	Ajax Quick Checkout 
	v6.0.0
	Dreamvention.com 
	d_quickcheckout/cart.tpl 
-->
<div id="cart_view" class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>"></div>
<script type="text/html" id="cart_template">
	<div class="b-order-cart">
		<% _.each(model.products, function(product) { %>
			<div class="b-order-cart__item">
				<div class="b-cart-item">
					<div class="b-cart-item__image">
						<a  href="<%= product.href %>" data-container="body" data-toggle="popover" data-placement="top" data-content='<img src="<%= product.image %>" />' data-trigger="hover">
							<img src="<%= product.thumb %>" class="img-responsive"/>
						</a>
					</div>
					<div class="b-cart-item__content">
						<div class="b-cart-item__heading">
							<a href="<%= product.href %>" <%=  model.config.columns.image ? '' : 'rel="popup" data-help=\'<img src="' + product.image + '"/>\'' %>> 
							<%= product.name %> <%= product.stock ? '' : '<span class="out-of-stock">***</span>' %>
							</a>
						</div>
			            <div class="b-cart-item__sizes">
			                <div class="b-product-size b-product-size_cart">
			                    <select name="color" id="" class="b-product-size__select">
			                    	<% _.each(product.option, function(option) { %>
			                        <option value="<%= option.value %>" class="b-product-size__option" disabled="true" selected="true"><%= option.value %></option>
			                        <% }) %>
			                    </select>
			                    <div class="b-product-like" onclick="wishlist.add('<%= product.id %>');"></div>
			                    <div class="b-product-compare" onclick="compare.add('<%= product.id %>');"></div>
			                </div>
			            </div>
		            	<div class="b-cart-item__params">
			                <div class="b-product-params">
								<% if(parseInt(model.config.columns.model)){ %>
									<div class="b-product-params__item">Модель: <%= product.model %></div>
								<% } %>
			                </div>
			            </div>
			            <div class="b-cart-item__count-price">
			                <div class="b-cart-item__count">
			                    <div class="b-cart-count">
			                        <div class="b-cart-count__input">
										<input type="text" data-mask="9?999999999999999" value="<%= product.quantity %>" class="qc-product-qantity form-control text-center" name="cart.<%= product.key %>"  data-refresh="2"/>
			                        </div>
			                        <div class="b-cart-count__text">шт.</div>
			                    </div>
			                </div>
			                <div class="b-cart-item__price">
			                    <div class="b-cart-price">
			                        <div class="b-cart-price__text">Цена: </div>
			                        <div class="b-cart-price__price"><%= product.price %></div>
			                    </div>
			                </div>
			            </div>
					</div>
				</div>
			</div>
		<% }) %>
		<% if(model.config.description){ %><p class="text"><%= model.config.description %></p><% } %>
		<% if(model.error){ %>
			<% if(model.config_stock_warning){ %>
				<div class="save-alert save-alert-danger">
					<i class="fa fa-exclamation-circle"></i> <%= model.error %>
				</div>
			<% } else { %>
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-circle"></i> <%= model.error %>
				</div>
			<% } %>
		<% } %>
		<% if(model.show_price){ %>
			<% _.each(model.totals, function(total, i) { %>
				<% if(i == 2) { %>
				<div class="b-order-cart__total-price total-line total-line_last">
				    <div class="total-line__text"><%= total.title %>:</div>
				    <div class="total-line__price"><%= total.text %></div>
				</div>
				<% } else { %>
				<div class="b-order-cart__total total-line">
				    <div class="total-line__text"><%= total.title %>:</div>
				    <div class="total-line__price"><%= total.text %></div>
				</div>
				<% } %>
			<% }) %>
		<% } %>
		<div class="clear"></div>
	</div><!--/.b-order-cart-->
</script>
<script>
$(function() {
	qc.cart = $.extend(true, {}, new qc.Cart(<?php echo $json; ?>));
	qc.cartView = $.extend(true, {}, new qc.CartView({
		el:$("#cart_view"), 
		model: qc.cart, 
		template: _.template($("#cart_template").html())
	}));

});

</script>