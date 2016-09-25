$(document).ready(function(e) {
	isLogged(0);
	getSystemsAndRaspi();
	//(setInterval(function(){refreshData()},30000);
	google.charts.load('current', {'packages':['corechart']});
	$("body").on("click",".deviceIcon",function(){
		
		populateModal($(this).siblings(".devId").text());
	});
	$("body").on("click",".singleDataCont",function(){
		populateChart($(this).children(".valGet").siblings(".hiddenVal").text(),$(this).children(".valGet").siblings(".hiddenType").text());
	});
	$("body").on("click",".valGetFromTo",function(){
		populateChartFromTo($(this).next().text(),$("#startDate").val(),$("#endDate").val(),$(this).text());
	});
});

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
								addDeviceToTower(tower,resApi[i].name,resApi[i].id,resApi[i].color);
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
	$(".towerContainer").append("<div id='tower"+towerId+"' class=\"col-xs-12 col-sm-6\"> <fieldset class=\"colContainer\"><legend class=\"towerLegend \">Tower "+towerId+"</legend>"+
			"<div class=\"row\">"+
				"<div class=\"deviceContainer\">"+
				"</div>"+
			"</div>"+
    	"</fieldset></div>");
}

function addDeviceToTower(towerId,deviceName,deviceId,deviceColor){
			var tower = "#tower"+towerId;
			var device = "device"+deviceId;
			$(tower).children().find(".deviceContainer").append("<div class=\"row\"><div class=\"col-xs-12 device\">"+
			"<form class=\"form-inline\" role=\"form\">"+
					"<div class=\"col-xs-3 myDevIconCont\">"+
                    	"<span>"+
							"<img src=\"img/"+deviceColor+"Icon.png\" aria-hidden=\"true\" class=\"deviceIcon img-responsive\" data-toggle=\"modal\" data-target=\"#myStatsModal\" alt=\"device icon\">"+
							"<p class=\"hidden devId\">"+deviceId+"</p>"+
						"</span>"+
					"</div>"+
					"<div class=\"col-xs-9\">"+
						"<div class=\"row\">"+
							"<div class=\"col-xs-12\">"+
                    			"<p id=\""+device+"\" class=\"deviceInfo\">Device #"+deviceName+"</p>"+
							"</div>"+
						"</div>"+
            "</form>"+	
			"</div></div>");
			setRaspiLastValues(deviceId,deviceName);
}

function setRaspiLastValues(deviceId,deviceName){
	var device = "#device"+deviceId;
	$(device).html("Device #"+deviceName+"</p>");
	$.ajax({
	url: '/scripts/getConfiguration.php',
	type: 'POST',
	dataType: 'json',
	success: function(res){
		var key = res.key;
		var server = res.server;
		$.ajax({
			url: server+'/getSensors/',
			type: 'GET',
			data: {key:key,id:deviceId},
			dataType: 'json',
			success: function(resApi){
				if(resApi[0].result === "success"){
					var i = 1;
					for(i=1; i<resApi.length;i++){
						var sensorId = resApi[i].id;
						var sensorType = resApi[i].type;
						
						$.ajax({
							url: server+'/getLastData/',
							async:false,
							type: 'GET',
							data: {key:key,id:sensorId},
							dataType: 'json',
							success: function(resData){
								if(resData[0].result === "success"){
									var f = 1;
									for(f=1; f<resData.length;f++){
										var typeIcon;
										switch(sensorType){
											case "1":
												typeIcon="CPU";
												break;
											case "2":
												typeIcon="MEM";
												break;
											case "3":
												typeIcon="TEMP";
												break;
											case "4":
												typeIcon="DISK";
												break;
										}
										$(device).append("<div class=\"col-xs-12 col-sm-6 input-group singleDataCont\" data-toggle=\"modal\" data-target=\"#myChartModal\">"+
													"<div class=\"col-xs-3 col-sm-5 col-md-4 col-lg-5\">"+
														"<span class=\"input-group-addon myResIcon\">"+
															"<img src=\"img/"+typeIcon+"Icon.png\" aria-hidden=\"true\" class=\"img myIcon\" alt=\""+typeIcon+" icon\">"+
														"</span>"+
													"</div>"+
													"<h6 value="+resData[f].value+" class=\"valGet\" >"+typeIcon+": "+resData[f].value+"</h6>"+
													"<p class=\"hiddenVal hidden\">"+resData[f].sensorId+"</p>"+
													"<p class=\"hiddenType hidden\">"+typeIcon+"</p>"+
											"</div>");
									}
								}
							},
							error: function(){
								alert("Dati Errati API");
							}
						});
					}
				}
			},
			error: function(){
				alert("Dati Errati API");
			}
		});
	},
	error: function(){
		alert("Dati Errati API");
	}
	});
}

function populateChart(sensorId,sensType){
	$.ajax({
	url: '/scripts/getConfiguration.php',
	type: 'POST',
	dataType: 'json',
	success: function(res){
		var key = res.key;
		var server = res.server;
			$.ajax({
				url: server+'/getTodayData/',
				type: 'GET',
				data: {key:key,id:sensorId},
				dataType: 'json',
				success: function(resData){
					if(resData[0].result === "success"){
						var chartData = new google.visualization.DataTable();
						var f=1;
						chartData.addColumn("datetime","date");
						chartData.addColumn("number",sensType+" Value");
						for(f=1; f<resData.length;f++){
							chartData.addRows(resData.length-1);
							chartData.setCell(f-1,1,resData[f].value);
							chartData.setCell(f-1,0,new Date(resData[f].date));
						}
						google.charts.setOnLoadCallback(drawChart(chartData,"curve_chart"));
						$("#modalH3").text(sensType + " values");
					}
				},
				error: function(){
					alert("Dati Errati API");
				}
			});
	},
	error: function(){
		alert("Dati Errati API");
	}
	});
}

function populateModal(deviceId){
	$.ajax({
	url: '/scripts/getConfiguration.php',
	type: 'POST',
	dataType: 'json',
	success: function(res){
		var key = res.key;
		var server = res.server;
			$.ajax({
				url: server+'/getSensors/',
				type: 'GET',
				data: {key:key,id:deviceId},
				dataType: 'json',
				success: function(resData){
					
					if(resData[0].result === "success"){
						var s=1;
						$("#statSensorPlace").html("<div class=\"btn-group\" role=\"group\" aria-label=\"...\">");
						for(s=1;s<resData.length;s++){
							var thisType;
							switch(resData[s].type){
								case "1":
									thisType="CPU";
									break;
								case "2":
									thisType="MEM";
									break;
								case "3":
									thisType="TEMP";
									break;
								case "4":
									thisType="DISK";
									break;
							}
							/*
							$("#statSensorPlace").append("<div class=\"row\">"+
								"<p class=\"text-center valGetFromTo\" >"+thisType+"</p>"+
								"<p class=\"hidden\">"+resData[s].id+"</p>"+
							"</div>");
							*/
							$("#statSensorPlace").append("<button type=\"button\" class=\"btn btn-default valGetFromTo\">"+thisType+"</button>"+
								"<p class=\"hidden\">"+resData[s].id+"</p>");
						}
					}
				},
				error: function(){
					alert("Dati Errati API");
				}
			});
	},
	error: function(){
		alert("Dati Errati API");
	}
	});
}

function populateChartFromTo(sensorId,from,to,sensType){
	$.ajax({
	url: '/scripts/getConfiguration.php',
	type: 'POST',
	dataType: 'json',
	success: function(res){
		var key = res.key;
		var server = res.server;
			$.ajax({
				url: server+'/getFromToData/',
				type: 'GET',
				data: {key:key,id:sensorId,from:from,to:to},
				dataType: 'json',
				success: function(resData){
					if(resData[0].result === "success"){
						var chartData = new google.visualization.DataTable();
						var f=1;
						chartData.addColumn("datetime","date");
						chartData.addColumn("number","value");
						for(f=1; f<resData.length;f++){
							chartData.addRows(resData.length-1);
							chartData.setCell(f-1,1,resData[f].value);
							chartData.setCell(f-1,0,new Date(resData[f].date));
						}
						google.charts.setOnLoadCallback(drawChart(chartData,"stat_chart"));
						
					}
				},
				error: function(){
					alert("Dati Errati API");
				}
			});
	},
	error: function(){
		alert("Dati Errati API");
	}
	});
}

function refreshData(){
	$.ajax({
			url: '/scripts/getConfiguration.php',
			type: 'POST',
			dataType: 'json',
			success: function(res){
				var key = res.key;
				var id = res.id;
				var server = res.server;
				$.ajax({
					url: server+'/getRaspi/',
					type: 'GET',
					data: {key:key,instId:id},
					dataType: 'json',
					success: function(resApi){
						var i = 1;
						if(resApi[0].result === "success"){
							for(i=1; i<resApi.length;i++){
								setRaspiLastValues(resApi[i].id,resApi[i].name);
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