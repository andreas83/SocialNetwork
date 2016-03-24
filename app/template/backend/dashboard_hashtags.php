<h2>Relation of Hashtags</h2>
<div class="graph"></div>
<style>
    
.node {
  stroke: #000;
  stroke-width: 1px;
}

.link {
  stroke: #999;
  stroke-opacity: .6;
}


text{

  fill:#000;
}

</style>
<script>

var width = $("h1").width();
    height = $(window).height();

var color = d3.scale.category20();

var force = d3.layout.force()
    .charge(-120)
    .linkDistance(80)
    .size([width, height]);



var svg = d3.select(".graph").append("svg")
    .attr("width", width)
    .attr("height", height);

d3.json("/backend/dashboard/hashtags/", function(error, graph) {
  force
      .nodes(graph.nodes)
      .links(graph.links)
      .start();

  var link = svg.selectAll(".link")
      .data(graph.links)
      .enter().append("line")
      .attr("class", "link")
      .style("stroke-width", function(d) { return Math.sqrt(d.value); });

  var node = svg.selectAll(".node")
      .data(graph.nodes)
      .enter().append("circle")
      .attr("class", "node")
      .attr("r", 5)
      .style("fill", function(d) { return color(d.group); })
      .call(force.drag)
      .on("mouseover", function(d){ $($("g").find("text")[d.index]).show(); })
      .on("mouseout", function(d){ $($("g").find("text")[d.index]).hide(); });


/*  node.append("title")
      .text(function(d) { return d.name; });
*/

var text = svg.append("g").selectAll("text")
    .data(force.nodes())
    .enter().append("text")
    .attr("x", 0)
    .attr("y", 0)
    .text(function(d) { return d.name; });

  force.on("tick", function() {
    text.attr("x", function(d) { return d.x+13; })
        .attr("y", function(d) { return d.y+4; });

    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
  });
});

</script>
