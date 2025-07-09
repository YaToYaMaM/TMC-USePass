<template>
    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full text-left border-collapse">
            <tbody>
            <tr v-for="(student, index) in students" :key="index" class="border-b">
                <td class="py-2 px-4"style="color: #828282;">{{ student.course }}</td>
                <td class="py-2 px-4 text-right"style="color: #828282;">{{ student.count }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
const students = [
    { course: 'BSIT', count: 689 },
    { course: 'BEED', count: 343 },
    { course: 'BSED', count: 543 },
    { course: 'BTVTED', count: 793 },
    { course: 'BSABE', count: 1120 },
]
</script>
