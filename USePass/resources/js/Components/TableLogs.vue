<template>
    <div class="log-container ">
        <div class="date-picker">
            <label for="date">Select Date: </label>
            <input class="rounded-[6px] max-h-8" type="date" id="date" v-model="selectedDate" />
        </div>

        <table class="log-table">
            <thead>
            <tr>
                <th>Time</th>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(log, index) in filteredLogs" :key="index">
                <td>
                    <span class="badge-green">{{ log.time }}</span>
                </td>
                <td>{{ log.user }}</td>
                <td>{{ log.action }}</td>
                <td>{{ log.description }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "LogTable",
    data() {
        return {
            selectedDate: "2025-06-27",
            logs: [
                { time: "06/27/2025 | 11:35 AM", user: "John Doe Supranes ", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe Vaughn Rhommer Theodore R. Gucor", action: "Submit Report", description: "John Doe Submit Report" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe", action: "Validate Report", description: "John Doe Validate Report" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe", action: "Submit Report", description: "John Doe Submit Report" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe", action: "Validate Report", description: "John Doe Validate Report" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:35 AM", user: "John Doe", action: "QR Scan", description: "Froilan Canete Enter" },
            ],
        };
    },
    computed: {
        filteredLogs() {
            return this.logs.filter(log =>
                log.time.startsWith(this.formatDate(this.selectedDate))
            );
        },
    },
    methods: {
        formatDate(dateStr) {
            const date = new Date(dateStr);
            const mm = String(date.getMonth() + 1).padStart(2, "0");
            const dd = String(date.getDate()).padStart(2, "0");
            const yyyy = date.getFullYear();
            return `${mm}/${dd}/${yyyy}`;
        },
    },
};
</script>

<style scoped>
.log-container {
    padding: 20px;
    border-radius: 10px;
    width: 100%;
    max-width: 100%;
    margin: auto;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
.badge-green {
    @apply bg-red-400 text-white rounded-[20px] px-3 py-1 text-sm;
}

.date-picker {
    margin-bottom: 10px;
    text-align: right;
}

.log-table {
    width: 100%;
    border-collapse: collapse;
    font-family: sans-serif;
}

.log-table th,
.log-table td {
    padding: 10px;
    text-align: left;
}

.log-table th {
    background-color: #ffffff;
    border-bottom: solid 1px #919191;
}
</style>
