$(document).ready(function() {
	$("#addBtn").click(function(){
		var desc=$("#description").val();
		var lvl=$("#level").val();
		desc = desc.replace("'","");
        $.ajax({
            url: 'php/newKey.php',
            type: 'POST',
            data: {"desc":desc,"lvl":lvl},
            dataType: 'json',
			})
			.done(function (result) {
				$("#newKeyP").text(result);
            })
			.fail(function () {
				$("#newKeyP").text("Error try again");
         });
    });
	
	$("#okCreated").click(function(){
		location.href = 'admin.php';
	});
});