<template>
    <div id="topsolver">
        <canvas id="topSolverGrafico"></canvas>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';
export default {
    props:['topsolverdata'],
    data(){
        return {
            labelsData : [],
            valueData : [],
        }
    },
    mounted() {
        for (const [key, value] of Object.entries(this.topsolverdata)) {
            this.labelsData.push(value['valor']);
            this.valueData.push(value['nome']);
        }
        
        const ctx = document.getElementById('topSolverGrafico');
        const config = {
            type: 'bar',
            data: {
                labels: this.valueData,
                datasets: [{
                    barPercentage: 0.10,
                    barThickness: 18,
                    maxBarThickness: 18,
                    minBarLength: 2,
                    data: this.labelsData,
                    backgroundColor: 'rgb(226, 0, 116)',
                }]
            },
            plugins: [
                {
                    afterDatasetsDraw: (chart, args, pluginOptions) => {
                        const { ctx, chartArea: { left, right, top, bottom, width, height }, scales: { x, y }} = chart;
                        ctx.save();
                        chart.data.datasets[0].data.forEach((datapoint,index) => {
                            ctx.font = 'bold 12px Arial';
                            ctx.fillStyle = 'rgb(255, 255, 255)';
                            ctx.textAlign = 'center';
                            ctx.fillText(datapoint,left + 10,y.getPixelForValue(index) + 5);
                        });
                    },
                }
            ],
            options: {
                indexAxis: 'y',
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide
                plugins: {
                    legend: {
                        display: false
                    },
                },
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        },
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    }
                }
            },
        };
        new Chart(ctx, config);
    }
}
</script>