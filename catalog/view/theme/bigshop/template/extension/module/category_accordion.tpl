<script type="text/javascript" src="catalog/view/theme/bigshop/js/jquery.dcjqaccordion.min.js"></script> 
<div class="typeh3"><?php echo $heading_title; ?></div>
<div class="box-category"><?php echo $category_accordion; ?></div>
<script type="text/javascript">
$(document).ready(function() {
	$('#cat_accordion').cutomAccordion({
		classExpand : 'custom_id<?php echo $category_accordion_cid; ?>',
		menuClose: false,
		autoClose: true,
		saveState: false,
		disableLink: false,		
		autoExpand: true
	});
});
</script>
