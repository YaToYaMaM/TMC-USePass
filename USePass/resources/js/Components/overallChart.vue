<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Overall</h3>
        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const chartData = {
    labels: ['STUDENTS', 'TEACHERS', 'VISITORS', 'ALUMNI'],
    datasets: [
        {
            label: 'Overall',
            data: [3435, 58, 0, 0],
            backgroundColor: '#760000',
            barThickness: 50,
        },
    ],
}

const chartOptions = {
    responsive: true,
    scales: {
        y: {
            beginAtZero: true,
        },
    },
}
</script>


