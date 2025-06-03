

( function ( $ ) {
	"use strict";


    
	//Team chart
	var ctx = document.getElementById( "team-chart" );
	ctx.height = 150;
	var myChart = new Chart( ctx, {
		type: 'line',
		data: {
			labels: [ "Week 1", "Week 2", "Week 3", "Week 4", "Week 5", "Week 6", "Week 7" ],
			type: 'line',
			defaultFontFamily: 'Montserrat',
			datasets: [ {
				data: [ 7, 7, 3, 5, 2, 10, 7 ],
				label: "Ratings",
				backgroundColor: 'rgba(0,103,255,.15)',
				borderColor: 'rgba(0,103,255,0.5)',
				borderWidth: 3.5,
				pointStyle: 'circle',
				pointRadius: 5,
				pointBorderColor: 'transparent',
				pointBackgroundColor: 'rgba(0,103,255,0.5)',
                    }, ]
		},
		options: {
			responsive: true,
			tooltips: {
				mode: 'index',
				titleFontSize: 12,
				titleFontColor: '#000',
				bodyFontColor: '#000',
				backgroundColor: '#fff',
				titleFontFamily: 'Montserrat',
				bodyFontFamily: 'Montserrat',
				cornerRadius: 3,
				intersect: false,
			},
			legend: {
				display: false,
				position: 'top',
				labels: {
					usePointStyle: true,
					fontFamily: 'Montserrat',
				},


			},
			scales: {
				xAxes: [ {
					display: true,
					gridLines: {
						display: false,
						drawBorder: false
					},
					scaleLabel: {
						display: false,
						labelString: 'Month'
					}
                        } ],
				yAxes: [ {
					display: true,
					gridLines: {
						display: false,
						drawBorder: false
					},
					scaleLabel: {
						display: true,
						labelString: 'Value'
					}
                        } ]
			},
			title: {
				display: false,
			}
		}
	} );



} )( jQuery );