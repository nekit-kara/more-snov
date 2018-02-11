$(document).ready(function() {
	//all params click in product.tpl
	$('.b-all-params').on('click', 'a', function() {
		$('html,body').animate({scrollTop: $('.nav-tabs').offset().top}, 700);
		$('a[href*="#tab-specification"]:first').trigger('click');
		return false;
	});
});