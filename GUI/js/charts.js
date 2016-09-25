function drawChart(chartData,destination) {
//var data = google.visualization.arrayToDataTable(chartArr);

var options = {
  curveType: "function",
  colors: ["#55b77f"],
  legend: "none",
  pointSize: "0"
};

var chart = new google.visualization.LineChart(document.getElementById(destination));

chart.draw(chartData, options);
}