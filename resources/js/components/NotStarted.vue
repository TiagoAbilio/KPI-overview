<template>
    <div id="naoestartada" style="width: 120px; height: 180px;">
        <div class="col" style="margin: 8px;">
            <canvas id="naoestartadaGrafico"></canvas>
            <h6 style="text-align: center;margin-top: 0px;">{{ "Not Started" | captaliza }}</h6>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';

export default {
    props:['result'],
    mounted() {
        new Chart(document.getElementById("naoestartadaGrafico"), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [this.result,100-this.result],
                    backgroundColor: [
                        'rgb(226, 0, 116)',
                        'rgb(217, 217, 217)',
                    ],
                    cutout:'85%',
                borderWidth: '0'
                }]
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
                plugins:{
                    legend:{
                        position:'bottom',
                        display:true
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1500
                },
            },


        });
    },
    filters:{
        captaliza(value) {
            return value.toUpperCase();
        }
    }
}
</script>