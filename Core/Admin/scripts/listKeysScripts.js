$(document).ready(function() {
	$("#alertKO").hide();
	$("#alertOK").hide();
	$("#closeBtn").click(function(){
		location.reload();
	});
	$(".editBtn").click(function() {
		var id = $(this).next().text();
		$.ajax({
            url: 'php/getEle.php',
            type: 'POST',
            data: {"id":id},
            dataType: 'json',
			})
			.done(function (result) {
				$("#keyEdit").text(result.key);
				$("#descriptionEdit").val(result.description);
				$("#levelEdit").val(result.level);
				$("#saveBtn").val(result.id);
				$("#enableEdit").val(result.enable);
            })
			.fail(function () {
				alert("Errore");
         });
    });
	$("#saveBtn").click(function(){
		var desc=$("#descriptionEdit").val();
		var lvl=$("#levelEdit").val();
		var id = $(this).val();
		var enable = $("#enableEdit").val();
        $.ajax({
            url: 'php/editEle.php',
            type: 'POST',
            data: {"id":id,
				"level":lvl,
				"description":desc,
				"enable":enable},
            dataType: 'html',
			})
			.done(function (result) {
				if(result ==="1"){
					$("#alertOK").fadeIn();
				}else{
					$("#alertKO").fadeIn();
				}
            })
			.fail(function () {
				$("#alertKO").fadeIn();
         });
    });
});