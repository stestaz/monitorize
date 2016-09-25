$(document).ready(function() {
	$(".alert").hide();
    $("#loginBtn").click(function(){
		var user=$("#username").val();
		var pass=$("#password").val();
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: {"user":user,"pass":pass},
            dataType: 'html',
			})
			.done(function (result) {
				if(result === "1"){
				window.location.href = "admin.php";
				}else{
					$("#alert").fadeIn();
				}
            })
			.fail(function () {
                $("#alert").fadeIn();
         });
    });
});