$(document).ready(function(e) {
    $(".containerLogin").hide();
	$("#loginBtn").click(function(e) {
		if($(this).text() === "Login"){
			$(this).parent().toggleClass("liGrey");
			$(".containerLogin").toggle();
		}else{
			logout();
		}
		
    });
	$("#loginBtnGo").click(function(e) {
		var username = $("#username").val();
		var password = btoa($("#password").val());
		$.ajax({
			url: '/scripts/login.php',
			type: 'POST',
			data: {username:username,password:password},
			dataType: 'html',
			success: function(res){
				if(res==0){
					alert("Wrong Login");
				}else{
					$(".loginHref").text("Hi "+username+"!");
					$(".loginHref").attr('href', 'admin.php');
					$(".containerLogin").hide();
					$("#username").val("");
					$("#password").val("");
					$("#loginBtn").parent().toggleClass("liGrey");	
					$("#loginBtn").text("Logout");
				}
			},
			error: function(){
				//alert("Dati Errati");
			}
		});
	});
});

function logout(){
	$.ajax({
			url: '/scripts/logout.php',
			type: 'POST',
			dataType: 'html',
			success: function(res){
				if(res==0){
				}else{
					$("#loginBtn").text("Login");
					isLogged(1);
				}
			},
			error: function(){
				//alert("Dati Errati");
			}
		});
}

function isLogged(goBack){
		$.ajax({
			url: '/scripts/isLogged.php',
			type: 'POST',
			dataType: 'html',
			success: function(res){
				if(res==""){
					if(goBack == 1){
						window.location = "http://raspein.stestaz.com";
					}
				}else{
					$("#loginBtn").text("Logout");
					$(".loginHref").text("Hi "+res+"!");
					$(".loginHref").attr('href', 'admin.php');
				}
			},
			error: function(){
				//alert("Dati Errati");
			}
		});
}