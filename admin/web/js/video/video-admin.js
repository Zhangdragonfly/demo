$(function(){
	// 固定modal框调样式
	// $('#modal-dialog').modal({backdrop: 'static', keyboard: false});


	// 选择艺人分类(最多选择6个标签)
	var account=0,limit=6;
	$(".actor-classify").on("click","input",function(){
		this.checked?account++:account--; 
		if(account>limit){ 
			this.checked=false;
			swal({title: "最多选6个标签!", text: "", type: "error"});
			account--;
		} 
	})

// ~~~~~~~审核资源和自媒体主~~~~~~~
	$(".btn-vendor-to-verify").on("click",function(){
		$(".media-vendor-detail").slideToggle();
	})
})