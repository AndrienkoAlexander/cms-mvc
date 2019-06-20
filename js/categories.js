$.fn.exists = function () {
    return this.length !== 0;
}

$(document).ready(function(){
	if($('.category').exists())
		$('.category').dcAccordion();
});