<?php
include("header.php");
?>
<h2>Content per Month </h2>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js" ></script>
    
    <canvas id="myChart" width="" height="400"></canvas>
    <script>        
        $("#myChart").width($("h2").width()/1.3).height(400);
        
    var ctx = $("#myChart").get(0).getContext("2d");
    var opt={

    ///Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,

    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,

    //Boolean - Whether the line is curved between points
    bezierCurve : true,

    //Number - Tension of the bezier curve between points
    bezierCurveTension : 0.4,

    //Boolean - Whether to show a dot for each point
    pointDot : true,

    //Number - Radius of each point dot in pixels
    pointDotRadius : 4,

    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth : 1,

    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,

    //Boolean - Whether to show a stroke for datasets
    datasetStroke : true,

    //Number - Pixel width of dataset stroke
    datasetStrokeWidth : 2,

    //Boolean - Whether to fill the dataset with a colour
    datasetFill : true,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

};

    
    var data = {
    labels: [],
    datasets: [
        {
            label: "Post per Month",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: []
        },
        {
            label: "Overall",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: []
        }
    ]
};

    $.get("/backend/dashboard/json/content/", function(res){
        var all=0;
        $(res).each(function(key, val){
            all=all+parseInt(val.cnt)
            data.datasets[0].data.push(parseInt(val.cnt));
            data.datasets[1].data.push(all);
            
            data.labels.push(val.Month +"."+val.Year);
            
        });
        $("#overall").html(all);
        var record=data.datasets[0].data;
        var lastrecord=record[record.length-1];
        $("#lastmonth").html(lastrecord);
        var myLineChart = new Chart(ctx).Line(data, opt);
    });
    
    
</script>
    <h3>Stats</h3>
    <p>Overall: <span id="overall"></span></p> 
    <p>Last month: <span id="lastmonth"></span></p> 
    
<?php
include("footer.php");
?>