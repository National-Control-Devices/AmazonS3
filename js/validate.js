jQuery(document).ready(function(){
	alert('sdfsdfsdf');
	jQuery("#amazons-credentials-my-form").validate({
	  rules: {
	  	id:{
		    required: true  		
	  	},
	  	key:{
		    required: true	
	  	}
	  },
	messages: {
		id:{
			required: "Please Enter ID."
		},
		key:{
			required: "Please Enter Key.",
		}
	  }
	});
});
