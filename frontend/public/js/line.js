var last_months = 7

d3.csv("line_data?month=" + last_months, function(data) {
	var formatted_data = [];
	var color = [ '#ff7f0e', '#2ca02c', '#984ea3' ]
	
	var sumstat = d3.nest().key(function(d) {
		return d.name;
	}).entries(data);

	
	var j = 0;

	sumstat.forEach(function(line) {
		var sin = [];
		
		
		line.values.forEach(function(d) {
			if(d.name == line.key){
				if (j < 2) {
					if (j == 0) {
						sin.push({
							x : parseInt(d._m),
							y : Math.sin(parseInt(d.n) / 10)
						});

					} else {
						sin.push({
							x : parseInt(d._m),
							y : Math.sin(parseInt(d.n) / 10) * 0.25 + 0.5
						});

					}
				} else {
					sin.push({
						x : parseInt(d._m),
						y : Math.cos(parseInt(d.n) / 10)
					});

				}
				
			} 
		});
		formatted_data.push({
			values : sin,
			key : line.key,
			color : color[j]
		});
		j++;
	});
	nv.addGraph(function() {
		var chart = nv.models.lineChart().useInteractiveGuideline(true);

		chart.xAxis.axisLabel('Month').tickFormat(d3.format(',r'));

		chart.yAxis.axisLabel('Computers (n)').tickFormat(d3.format('.02f'));

		d3.select('#chart svg').datum(formatted_data).transition()
				.duration(500).call(chart);

		nv.utils.windowResize(chart.update);

		return chart;
	});
	

});
