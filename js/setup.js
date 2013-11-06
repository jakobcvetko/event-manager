$().ready(function() {
	$(".dynamic_input").focus(function() {
		//console.log("Focus in!");
		if(this.value == this.defaultValue) {
			this.value = '';
			$(this).removeClass("empty");
		}
	});
	$(".dynamic_input").focusout(function() {
		//console.log("Focus out!");
		if(this.value == '') {
			this.value = this.defaultValue;
			$(this).addClass("empty");
		}
	});
	$(".fancybox_item").fancybox();
});