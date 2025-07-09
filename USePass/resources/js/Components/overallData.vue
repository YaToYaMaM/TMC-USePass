<template>
    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full text-left border-collapse">
            <tbody>
            <tr v-for="(overall, index) in overalls" :key="index" class="border-b">
                <td class="py-2 px-4" style="color: #828282;">{{ overall.dataSubject }}</td>
                <td class="py-2 px-4 text-right" style="color: #828282;">{{ overall.count }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
const overalls = [
    { dataSubject: 'STUDENTS', count: 3435 },
    { dataSubject: 'TEACHERS', count: 58 },
    { dataSubject: 'VISITORS', count: 0 },
    { dataSubject: 'ALUMNI', count: 0 },
]
</script>
