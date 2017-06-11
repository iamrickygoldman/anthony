jQuery(function($){
$(document).ready(function(){
	jQuery(document).ready(function($){
		$(".sumo").each(function(){
			$(this).SumoSelect({
				placeholder: $(this).attr("data-placeholder"),
				floatWidth: 0,
				nativeOnDevice: ["Android", "BlackBerry", "Opera Mini", "IEMobile", "Silk"],
			});
		});
	});
});
});