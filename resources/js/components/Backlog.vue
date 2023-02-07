<template>
    <div id="backlog">
        <canvas id="backlogGrafico" v-if="this.databack.length > 0"></canvas>
        <p style="display: inline;" v-else>Os dados de Backlog são processados de forma Semanal. 
            Aguarde um ciclo de 7 dias corridos, para visualizar as informações</p>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';
export default {
    props: ['databack'],
    mounted() {
        if (this.databack.length > 0) {
            let abertos = [];
            let fechados = [];
            let backlogs = [];
            let semanas = [];

            for (const [key, value] of Object.entries(this.databack)) {
                abertos.push(value['open']);
                fechados.push(value['resolved']);
                backlogs.push(value['backlog']);
                semanas.push('SE' + value['semana']);
            }
            var Data1 = {
                label: 'Abertos',
                data: abertos,
                backgroundColor: [
                    'rgb(0, 64, 128)', 'rgb(0, 64, 128)', 'rgb(0, 64, 128)', 'rgb(0, 64, 128)', 'rgb(0, 64, 128)'
                ],
            }
            var Data2 = {
                label: 'Fechados',
                data: fechados,
                backgroundColor: [
                    'rgb(226, 0, 116)', 'rgb(226, 0, 116)', 'rgb(226, 0, 116)', 'rgb(226, 0, 116)', 'rgb(226, 0, 116)'
                ],
            }
            var Data3 = {
                label: 'Backlog',
                data: backlogs,
                backgroundColor: [
                    'rgb(166, 166, 166)', 'rgb(166, 166, 166)', 'rgb(166, 166, 166)', 'rgb(166, 166, 166)', 'rgb(166, 166, 166)'
                ],
            }

            const ctx = document.getElementById('backlogGrafico');
            const config = {
                type: 'bar',
                data: {
                    labels: semanas,
                    datasets: [Data1, Data2, Data3],
                    datalabels:{
                        align:'end',
                        anchor:'end'
                    }
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,

                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    },
                }
            };
            new Chart(ctx, config);
        }
    }
}
</script>