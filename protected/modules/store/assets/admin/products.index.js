
$('#StoreCategoryTreeFilter').bind('loaded.jstree', function (event, data) {
	//data.inst.open_all(0);
}).delegate("a", "click", function (event) {
	try{
		var id = $(this).parent("li").attr('id').replace('StoreCategoryTreeFilterNode_', '');
	}catch(err){
		// 'All Categories' clicked
		var id = 0;
	}
	var obj = $('#productsListGrid .filters td')[0];
	$(obj).append('<input name="category" type="text" value="'+id+'">');
	$('#productsListGrid .filters :input').first().trigger('change');
});