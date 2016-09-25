$(document).ready(function() { 
	isLogged(1);
	getConfiguration();  
	getSystemsAndRaspi();
	$("body").on("click", "#editDevice", function(){
		$("#typeForm").hide();
		$(".myModalHeader").children().children("h2").text("Edit Device");
		$("#modDevId").text($(this).next(".devId").text());
		getDeviceData($(this).next(".devId").text());
	});
	$("body").on("click", "#removeDevice", function(){
		removeDevice("#device"+$(this).next().next(".devId").text());
	});
	$("body").on("click", ".addDeviceBtn", function(){
		$("#typeForm").show();
		$("#modDevTower").val($(this).next(".towId").text());
		$(".myModalHeader").children().children("h2").text("New Device");
	});
	$("#addTower").click(function() {
		addTower(parseInt($("#curTowerId").text())+1);
    });
	$("#submitDevice").click(function(){
		if($(".myModalHeader").children().children("h2").text() === "New Device"){
			addDevice();
		}else{
			updateDevice($("#modDevId").text());
		}
	});
	$("#submitSystem").click(function(e) {
        updateConfiguration();
    });
	$(".close").click(function() {
        modalClean();
    });
	$("body").on("click", ".removeTower", function(){
		$(this).parent().next().children().each(function(index, element) {
			removeDevice("#"+$(this).attr('id'));
        });
		var tower = "#tower"+$(this).next().text();
		$(tower).remove();
	});
});

function modalClean(){
	$("#modDevName").val("");
	$("#modDevId").val("");
	$("#modDevColor").val(0);
	$("#modDevTower").val("");
	$("#modDevPosition").val("");
	$("#modDevName").text("");
	$("#modDevId").text("");
	$("#modDevTower").text("");
	$("#modDevPosition").text("");
	$("#cpuFrom").text("");
	$("#cpuTo").text("");
	$("#memFrom").text("");
	$("#memTo").text("");
	$("#diskMem").text("");
	$("#diskTo").text("");
	$("#tempFrom").text("");
	$("#tempTo").text("");
	$("#cpuFrom").val("");
	$("#cpuTo").val("");
	$("#memFrom").val("");
	$("#memTo").val("");
	$("#diskMem").val("");
	$("#diskTo").val("");
	$("#tempFrom").val("");
	$("#tempTo").val("");	
								
}
function getConfiguration(){
	$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var id = res.id;
				var server = res.server;
				$.ajax({
					url: server+'/getConfiguration/',
					type: 'GET',
					data: {key:key,id:id},
					dataType: 'json',
					success: function(resApi){
						if(resApi[0].result === "success"){
							
							$("#key").val(resApi[1].apiKey);
							$("#directory").val(resApi[1].folder);
							$("#twConsKey").val(resApi[1].twitterConsumerKey);
							$("#twConsSec").val(resApi[1].twitterConsumerSecret);
							$("#twAccTok").val(resApi[1].twitterAccessToken);
							$("#twAccTokSec").val(resApi[1].twitterAccessTokenSecret);
							$("#twContact").val(resApi[1].twitterContactName);
						}
					},
					error: function(){
						alert("Dati Errati API");
					}
				});
			},
			error: function(){
				alert("Dati Errati");
			}
		});
}

function updateConfiguration(){
	var key = $("#key").val();
	var directory =	$("#directory").val();
	var twConsKey = $("#twConsKey").val();
	var twConsSec = $("#twConsSec").val();
	var twAccTok = $("#twAccTok").val();
	var twAccTokSec = $("#twAccTokSec").val();
	var twContact = $("#twContact").val();
	$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var id = res.id;
				var server = res.server;
				$.ajax({
					url: server+'/setEnvironment/',
					type: 'POST',
					data: {key:key,
					instId:id,
					apiKey:key,
					folder:directory,
					twitterConsumerKey:twConsKey,
					twitterConsumerSecret:twConsSec,
					twitterAccessToken:twAccTok,
					twitterAccessTokenSecret:twAccTokSec,
					twitterContactName:twContact},
					dataType: 'json',
					success: function(resApi){
						if(resApi[0].result === "success"){

						}
					},
					error: function(){
						alert("Dati Errati API");
					}
				});
			},
			error: function(){
				alert("Dati Errati");
			}
		});
}
function getDeviceData(raspiId){
	$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var server = res.server;
				$.ajax({
					url: server+'/getRaspi/',
					type: 'GET',
					data: {key:key,id:raspiId},
					dataType: 'json',
					success: function(resApi){
						if(resApi[0].result === "success"){
							$("#modDevName").val(resApi[1].name);
							$("#modDevColor").val(resApi[1].color);
							$("#modDevTower").val(resApi[1].tower);
							$("#modDevPosition").val(resApi[1].position);
						}
					},
					error: function(){
						alert("Dati Errati API");
					}
				});
			},
			error: function(){
				alert("Dati Errati");
			}
		});
}

function getSystemsAndRaspi(){
$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var id = res.id;
				var server = res.server;
				$.ajax({
					url: server+'/getRaspiFromInstId/',
					type: 'GET',
					data: {key:key,instId:id},
					dataType: 'json',
					success: function(resApi){
						var tower = 0;
						var i = 1;
						if(resApi[0].result === "success"){
							for(i=1; i<resApi.length;i++){
								if(tower != resApi[i].tower){
									tower = resApi[i].tower;
									addTower(tower);
								}
								addDeviceToTower(tower,resApi[i].name,resApi[i].id);
							}
						}
					},
					error: function(){
						alert("Dati Errati API");
					}
				});
			},
			error: function(){
				alert("Dati Errati");
			}
		});	
}

function addTower(towerId){
	$("#curTowerId").text(towerId);
	$(".towerContainer").append("<div id='tower"+towerId+"' class=\"col-xs-12 text-center \"><fieldset class=\"colContainer\"><legend class=\"towerLegend \">Tower "+towerId+"</legend>"+
				"<div class=\"col-xs-2 col-xs-offset-10 pull-right\">"+
                        "<span class=\"glyphicon glyphicon-remove redGreyBtn removeTower\"></span>"+
						"<p class=\"hidden towId\">"+towerId+"</p>"+ 
                    "</div>"+
				"<div class=\"deviceContainer col-xs-12\">"+
				"</div>"+
			"<div class=\"row\">"+
				"<div class=\"addDevice\">"+
					"<form class=\"form-inline\" role=\"form\">"+
						"<div>"+
							"<button type=\"button\" class=\"btn addDeviceBtn btn-info pull-right\" data-toggle=\"modal\" data-target=\"#myModal\">ADD DEVICE</button>"+
							"<p class=\"hidden towId\">"+towerId+"</p>"+
						"</div>"+
					"</form>"+
				"</div>"+
			"</fieldset></div>");
}

function addDevice(){
	var name = $("#modDevName").val();
	var color = $("#modDevColor").val();
	var tower = $("#modDevTower").val();
	var position =$("#modDevPosition").val();
	$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var server = res.server;
				var instId = res.id;
				$.ajax({
					url: server+'/addRaspi/',
					type: 'POST',
					data: {key:key,
							name:name,
							position:position,
							tower:tower,
							color:color,
							instId:instId
							},
					dataType: 'json',
					success: function(resApi){
						if(resApi != -1){
							addDeviceToTower(tower,name,resApi);
							if($("#cpuFrom").val()!="" && $("#cpuTo").val()!=""){
								addSensor(resApi,1,$("#cpuFrom").val(),$("#cpuTo").val());
							}
							if($("#memFrom").val()!="" && $("#memTo").val()!=""){
								addSensor(resApi,2,$("#memFrom").val(),$("#memTo").val());
							}
							if($("#tempFrom").val()!="" && $("#tempTo").val()!=""){
								addSensor(resApi,3,$("#tempFrom").val(),$("#tempTo").val());
							}
							if($("#diskFrom").val()!="" && $("#diskTo").val()!=""){
								addSensor(resApi,4,$("#diskFrom").val(),$("#diskTo").val());
							}
							modalClean();
						}else{
							alert("unable to add");
						}
					},
					error: function(){
						alert("Dati Errati API");
					}
				});
			},
			error: function(){
				alert("Dati Errati");
			}
		});
}
function removeDevice(deviceId){
	var id = deviceId.substring(7);
	$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var server = res.server;
				var instId = res.id;
				$.ajax({
					url: server+'/editRaspi/',
					type: 'POST',
					data: {key:key,
							id:id,
							enable:0
							},
					dataType: 'json',
					success: function(resApi){
						if(resApi != -1){
							$(deviceId).remove();
						}else{
							alert("unable to remove");
						}
					},
					error: function(){
						alert("Dati Errati API");
					}
				});
			},
			error: function(){
				alert("Dati Errati");
			}
		});
}
function updateDevice(deviceId){
	var name = $("#modDevName").val();
	var color = $("#modDevColor").val();
	var tower = $("#modDevTower").val();
	var position =$("#modDevPosition").val();					
	$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var server = res.server;
				var instId = res.id;
				$.ajax({
					url: server+'/editRaspi/',
					type: 'POST',
					data: {key:key,
							name:name,
							position:position,
							tower:tower,
							color:color,
							id:deviceId
							},
					dataType: 'json',
					success: function(resApi){
						if(resApi != -1){
							$("#info"+deviceId).text("Device: #"+name);
						}else{
							alert("unable to edit");
						}
					},
					error: function(){
						alert("Dati Errati API");
					}
				});
			},
			error: function(){
				alert("Dati Errati");
			}
		});
}
function addDeviceToTower(towerId,deviceName,deviceId){
			var tower = "#tower"+towerId;
			$(tower).children().find(".deviceContainer").append("<div id=\"device"+deviceId+"\" class=\"col-xs-12 col-md-6 device\">"+
			"<form class=\"form-inline\" role=\"form\">"+
					"<div class=\"col-xs-3\">"+
	                    "<span class=\"input-group-btn\"><img src=\"img/device.png\" aria-hidden=\"true\" alt=\"device icon\"/></span>"+
					"</div>"+
					"<div class=\"col-xs-7\">"+
                    	"<p value=\""+deviceId+"\" id=\"info"+deviceId+"\"class=\"deviceInfo\">Device: #"+deviceName+"</p>"+
					"</div>"+
						"<span id=\"removeDevice\" class=\"glyphicon glyphicon-remove myGrey redGreyBtn\"></span>"+
						"<span id=\"editDevice\" class=\"glyphicon glyphicon-pencil myGrey redGreyBtn\" data-toggle=\"modal\" data-target=\"#myModal\"></span>"+
						"<p class=\"hidden devId\">"+deviceId+"</p>"+
            "</form>"+	
			"</div>");
}
function addSensor(raspId,type,from,to){
	$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var server = res.server;
				var instId = res.id;
				$.ajax({
					url: server+'/addSensor/',
					type: 'POST',
					data: {key:key,
							raspId:raspId,
							sensType:type,
							from:from,
							to:to
							},
					dataType: 'json',
					success: function(resApi){
						if(resApi != -1){
							//sensore aggiunto
						}else{
							alert("unable to remove");
						}
					},
					error: function(){
						alert("Dati Errati API");
					}
				});
			},
			error: function(){
				alert("Dati Errati");
			}
		});
}