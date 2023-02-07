<template>
    <div id="progressoTask" style="width: 120px; height: 180px;">
        <div class="col" style="margin: 8px;">
            <canvas id="progressoTaskGrafico"></canvas>
            <h6 style="text-align: center;margin-top: 0px;">{{ "In Progress" | captaliza }}</h6>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';

export default {
    props: ['result'],
    mounted() {
        new Chart(document.getElementById("progressoTaskGrafico"), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [this.result, 100 - this.result],
                    backgroundColor: [
                        'rgb(226, 0, 116)',
                        'rgb(217, 217, 217)',
                    ],
                cutout:'85%',
                borderWidth: '0'

                }],
            },
            plugins: [
                {
                    afterDraw: (chart) => {
                        const {
                            ctx, chartArea: { left, right, top, bottom, width, height }
                        } = chart;
                        ctx.save();
                        ctx.font = 'bolder 18px Arial';
                        ctx.fillStyle = 'rgb(226, 0, 116)';
                        ctx.textAlign = 'center';
                        ctx.fillText(this.result + '%', width / 2 + 2, height / 2 + top);

                    },
                }
            ],
            options: {
                responsive: true,
                animation: {
                    animateRotate: true,
                    duration: 1500
                },
                legend: {
                    display: false
                }
            },


        });
    },
    filters: {
        captaliza(value) {
            return value.toUpperCase();
        }
    }
}
</script>