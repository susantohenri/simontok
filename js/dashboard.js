$(document).ready(function () {
    drawPenyerapanChart()
    drawRealisasiChart()
    drawKomposisiChart()
    drawAlokasiChart()
})

function drawPenyerapanChart(){
    var dataPenyerapan = [
        { x: '2015-09-01', y: 70},
        { x: '2015-09-02', y: 75 },
        { x: '2015-09-03', y: 50},
        { x: '2015-09-04', y: 75 },
        { x: '2015-09-05', y: 50 },
        { x: '2015-09-06', y: 75 },
        { x: '2015-09-07', y: 86 }
    ];

    Morris.Line({
        element: 'dashboard-penyerapan-chart',
        data: dataPenyerapan,
        xkey: 'x',
        ykeys: ['y'],
        ymin: 'auto 40',
        labels: ['Penyerapan'],
        xLabels: "day",
        hideHover: 'auto',
        yLabelFormat: function (y) {
            if (y === parseInt(y, 10)) {
                return y;
            }
            else {
                return '';
            }
        },
        resize: true,
        lineColors: [
            'rgb(158, 216, 95)',
        ],
        pointFillColors: [
             'rgb(133, 206, 54)',
        ]
    });
}

function drawRealisasiChart () {
    var opts = {
      angle: -0.2, // The span of the gauge arc
      lineWidth: 0.02, // The line thickness
      radiusScale: 1, // Relative radius
      pointer: {
        length: 0.48, // // Relative to gauge radius
        strokeWidth: 0.018, // The thickness
        color: '#000000' // Fill color
      },
      limitMax: false,     // If false, max value increases automatically if value > maxValue
      limitMin: false,     // If true, the min value of the gauge will be fixed
      colorStart: '#6FADCF',   // Colors
      colorStop: '#8FC0DA',    // just experiment with them
      strokeColor: '#E0E0E0',  // to see which ones work best for you
      generateGradient: true,
      highDpiSupport: true,     // High resolution support
      
    };
    var target = document.getElementById('realisasi'); // your canvas element
    var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
    gauge.maxValue = 3000; // set max gauge value
    gauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 32; // set animation speed (32 is default value)
    gauge.set(1250); // set actual value
}

function drawKomposisiChart () {
    var dataDownloads = [
        {
            year: '2006',
            downloads: 1300
        },
        {
            year: '2007',
            downloads: 1526
        },
        {
            year: '2008',
            downloads: 2000
        },
        {
            year: '2009',
            downloads: 1800
        },
        {
            year: '2010',
            downloads: 1650
        },
        {
            year: '2011',
            downloads: 620
        },
        {
            year: '2012',
            downloads: 1000
        },
        {
            year: '2013',
            downloads: 1896
        },
        {
            year: '2014',
            downloads: 850
        },
        {
            year: '2015',
            downloads: 1500
        }
    ];
    Morris.Bar({
        element: 'dashboard-komposisi-chart',
        data: dataDownloads,
        xkey: 'year',
        ykeys: ['downloads'],
        labels: ['Downloads'],
        hideHover: 'auto',
        resize: true,
        barColors: [
            'rgb(133, 206, 54)',
            tinycolor('rgb(133, 206, 54)').darken(10).toString()
        ],
    })
}

function drawAlokasiChart () {
    var $dashboardAlokasiBreakdownChart = $('#dashboard-alokasi-chart')
    $dashboardAlokasiBreakdownChart.empty()
    Morris.Donut({
        element: 'dashboard-alokasi-chart',
        data: [{ label: "Download Alokasi", value: 12 },
            { label: "In-Store Alokasi", value: 30 },
            { label: "Mail-Order Alokasi", value: 20 } ],
        resize: true,
        colors: [
            tinycolor('rgb(133, 206, 54)'.toString()).lighten(10).toString(),
            tinycolor('rgb(133, 206, 54)'.toString()).darken(8).toString(),
            'rgb(133, 206, 54)'.toString()
        ],
    });
}