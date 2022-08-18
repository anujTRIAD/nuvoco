$(function() {
    "use strict";
    MorrisDonutChart();
});

// Morris donut chart
function MorrisDonutChart() {
    Morris.Donut({
        element: 'm_donut_chart',
        data: [
        {
            label: "Online Sales",
            value: 45,

        }, {
            label: "Store Sales",
            value: 35
        },{
            label: "Email Sales",
            value: 8
        }, {
            label: "Agent Sales",
            value: 12
        }],

        resize: true,
        colors: ['#ffd97f', '#fab2c0', '#80dad8', '#a1abb8']
    });
}
