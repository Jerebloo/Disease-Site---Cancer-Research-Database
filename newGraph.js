

function createGraph(jsonData,tabDiv)
{

  console.log(jsonData);

  jsonData.sort(function(a,b){if(a.source>b.source){return 1;}
  else if(a.source<b.source){return-1;}
  else{
    if(a.target>b.target){return 1;}

    if(a.target<b.target) {
      return-1;
    }

    else{
      return 0;
    }
  }
});

for(var i=0;i<jsonData.length;i++) {
  if(i!=0&&jsonData[i].source==jsonData[i-1].source&&jsonData[i].target==jsonData[i-1].target) {
    jsonData[i].linknum=jsonData[i-1].linknum+1;
  }
  else
  {
    jsonData[i].linknum=1;
  }
}
    var nodes={};
    jsonData.forEach(function(link){
      link.source = nodes[link.source] || (nodes[link.source]={name:link.source});
      link.target = nodes[link.target] || (nodes[link.target]={name:link.target});
      //console.log(nodes[link.source], typeof nodes[link.target]);
      //console.log(nodes);
    });

    //console.log(Object.keys(nodes));
    for(var k in nodes) {
      if(nodes[k].name.indexOf("hsa") > -1) {
        nodes[k]["group"] = 0;
      }
      else {
        nodes[k]["group"] = 1;
      }
    }
    var arrows=[];

    for(var i=0;i<jsonData.length;i++){arrows[i]=jsonData[i].type;};

    //var w=1000,h=800;
    var w=500,h=800;
    var w = document.getElementById("graph").offsetWidth;

    var force=d3.layout.force()
    .nodes(d3.values(nodes))
    .links(jsonData)
    .size([w,h])
    .linkDistance(300)
    .charge(-300)
    .on("tick",tick)
    .start();

    var color = d3.scale.category10();

    var svg=d3.select(tabDiv)
    .append("svg:svg")
    .attr("width",w)
    .attr("height",h);

    svg.append("svg:defs").selectAll("marker")
    .data(arrows)
    .enter().append("svg:marker")
    .attr("id",String).attr("viewBox","0 -5 7 10")
    .attr("refX",15)
    .attr("refY",-1.5)
    .attr("markerWidth",2)
    .attr("markerHeight",6)
    .attr("orient","auto")
    .append("svg:path")
    .attr("d","M-100,-100L100,0L0,200");

    var path=svg.append("svg:g").selectAll("path")
    .data(force.links())
    .enter().append("svg:path")
    .attr("class",function(d){return"link "+d.type;})
    .attr("marker-end",function(d){return"url(#"+d.type+")";});

    var circle=svg.append("svg:g").selectAll("circle")
    .data(force.nodes())
    .enter().append("svg:circle")
    .attr("r",5)
    .style("fill", function(d) {
      return color(d.group);
    })
    .call(force.drag);

    var text=svg.append("svg:g").selectAll("g")
    .data(force.nodes())
    .enter().append("svg:g");
    text.append("svg:text")
    .attr("x",8)
    .attr("y",".31em")
    .attr("class","shadow")
    .text(function(d){return d.name;});

    text.append("svg:text")
    .attr("x",8).attr("y",".31em")
    .text(function(d){return d.name;});

    function tick() {
        path.attr("d",function(d)
        {
          var dx=d.target.x-d.source.x,dy=d.target.y-d.source.y,
          dr=1000/d.linknum;
          return"M"+d.source.x+","+d.source.y+"A"+dr+","+dr+" 0 0,1 "+d.target.x+","+d.target.y;
        });

      circle.attr("transform",function(d){return"translate("+d.x+","+d.y+")";});

      text.attr("transform",function(d){return"translate("+d.x+","+d.y+")";});
    }
  }
