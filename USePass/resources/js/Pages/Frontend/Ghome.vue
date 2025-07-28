<template>
    <Head title="USePass" />
   <Frontend>
       <div class="h-full flex flex-col">
           <!-- Header Bar -->
           <div class="bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 md:px-8 py-4 flex-shrink-0">
               <h1 class="font-bold text-lg sm:text-xl text-gray-800">Home</h1>
               <div class="inline-flex text-sm bg-gray-100 p-1 rounded-lg shadow-sm">
                   <button @click="selectUnit('Tagum')" :class="buttonClass('Tagum')">Tagum</button>
                   <button @click="selectUnit('Mabini')" :class="buttonClass('Mabini')">Mabini</button>
               </div>
           </div>

           <!-- Filter Section -->
           <div class="bg-white border-b border-gray-200 flex flex-col sm:flex-row items-center justify-between px-4 sm:px-6 md:px-8 py-4 space-y-2 sm:space-y-0 flex-shrink-0">
               <!-- Search Input -->
               <div class="relative w-full sm:w-auto">
                   <input
                       type="text"
                       v-model="searchText"
                       placeholder="Search a student..."
                       class="pl-9 pr-3 py-2 border border-gray-400 rounded-full text-sm w-full sm:w-60 focus:outline-none focus:ring-2 focus:ring-red-600"
                   />
                   <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M15.5 10.5a5 5 0 11-10 0 5 5 0 0110 0z" />
                   </svg>
               </div>

               <!-- Date and Course Select -->
               <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                   <input
                       type="date"
                       v-model="selectedDate"
                       class="border border-gray-400 rounded px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-red-600"
                   />
                   <div class="relative w-full max-w-full sm:w-90">
                       <select
                           v-model="selectedCourse"
                           class="appearance-none border border-gray-400 rounded px-3 py-2 pr-8 text-sm w-full focus:outline-none focus:ring-2 focus:ring-red-600"
                       >
                           <option value="">All Courses</option>
                           <option v-for="course in availableCourses" :key="course" :value="course">
                               {{ course }}
                           </option>
                       </select>
                       <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                           </svg>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Content Area -->
           <div class="flex-1 flex flex-col p-4 sm:p-6 md:p-8 overflow-hidden">
               <!-- Student Table -->
               <div class="flex-1 bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                   <div class="overflow-auto h-full">
                       <table class="w-full">
                           <!-- Table Header -->
                           <thead class="bg-gray-50 sticky top-0 z-10">
                           <tr>
                               <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Student</th>
                               <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Major</th>
                               <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Unit</th>
                               <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Time & Date</th>
                           </tr>
                           </thead>

                           <!-- Table Body -->
                           <tbody class="divide-y divide-gray-100">
                           <tr
                               v-for="(student, index) in paginatedStudents"
                               :key="index"
                               class="hover:bg-gray-50 transition-colors duration-150"
                           >
                               <!-- Student Column -->
                               <td class="px-4 py-3">
                                   <div class="flex items-center space-x-4">
                                       <div class="w-10 h-10 rounded-full bg-red-800 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                           <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                                               <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                           </svg>
                                       </div>
                                       <div class="flex flex-col min-w-0">
                                           <div class="font-semibold text-red-800 text-sm truncate">{{ student.name }}</div>
                                           <div class="text-gray-600 text-xs truncate">{{ student.course }}</div>
                                       </div>
                                   </div>
                               </td>

                               <!-- Major Column -->
                               <td class="px-4 py-3">
                                   <div class="text-gray-700 text-sm truncate">{{ student.major }}</div>
                               </td>

                               <!-- Unit Column -->
                               <td class="px-4 py-3">
                                   <div class="text-gray-700 text-sm font-medium">{{ student.unit }}</div>
                               </td>

                               <!-- Time & Date Column -->
                               <td class="px-4 py-3">
                                   <div class="text-gray-600 text-sm">
                                       <div>{{ student.time }}</div>
                                       <div class="text-xs text-gray-500">{{ student.date }}</div>
                                   </div>
                               </td>
                           </tr>
                           </tbody>
                       </table>
                   </div>
               </div>

               <!-- Pagination -->
               <div class="flex justify-center items-center space-x-6 mt-4 bg-white rounded-lg border border-gray-200 py-3 shadow-sm flex-shrink-0">
                   <button
                       @click="prevPage"
                       :disabled="currentPage === 1"
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-150 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-100 border border-gray-300"
                   >
                       Previous
                   </button>
                   <span class="text-sm text-gray-700">
                    Page <span class="font-semibold text-red-800">{{ currentPage }}</span> of <span class="font-semibold">{{ totalPages }}</span>
                </span>
                   <button
                       @click="nextPage"
                       :disabled="currentPage === totalPages"
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-150 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-100 border border-gray-300"
                   >
                       Next
                   </button>
               </div>
           </div>
       </div>

   </Frontend>
</template>
<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue'
import {Head} from "@inertiajs/vue3";

</script>
<script>

export default {
    data() {
        return {
            selectedUnit: "Tagum",
            currentPage: 1,
            itemsPerPage: 10,
            searchText: '',
            selectedDate: '2025-07-03',
            selectedCourse: '',
            students: [
                { name: "Alice D.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Tagum", time: "08:00 AM", date: "2025-07-03" },
                { name: "Brian S.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Mabini", time: "08:30 AM", date: "2025-07-03" },
                { name: "Carla M.", course: "Bachelor of Science in Information System", major: "N/A", unit: "Tagum", time: "09:00 AM", date: "2025-07-03" },
                { name: "David P.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Mabini", time: "09:30 AM", date: "2025-07-03" },
                { name: "Ella G.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Tagum", time: "10:00 AM", date: "2025-07-03" },
                { name: "Frank L.", course: "Bachelor of Science in Information System", major: "N/A", unit: "Mabini", time: "10:30 AM", date: "2025-07-03" },
                { name: "Grace K.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Tagum", time: "11:00 AM", date: "2025-07-03" },
                { name: "Harry T.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Mabini", time: "11:30 AM", date: "2025-07-03" },
                { name: "Isla R.", course: "Bachelor of Science in Information System", major: "N/A", unit: "Tagum", time: "12:00 PM", date: "2025-07-03" },
                { name: "Jake H.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Mabini", time: "12:30 PM", date: "2025-07-03" },
                { name: "Kate B.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Tagum", time: "01:00 PM", date: "2025-07-03" },
                { name: "Leo C.", course: "Bachelor of Science in Information System", major: "N/A", unit: "Mabini", time: "01:30 PM", date: "2025-07-03" },
                { name: "Mia Z.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Tagum", time: "02:00 PM", date: "2025-07-03" },
                { name: "Noah N.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Mabini", time: "02:30 PM", date: "2025-07-03" },
                { name: "Olivia J.", course: "Bachelor of Science in Information System", major: "N/A", unit: "Tagum", time: "03:00 PM", date: "2025-07-03" },
                { name: "Paul V.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Mabini", time: "03:30 PM", date: "2025-07-03" },
                { name: "Queenie T.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Tagum", time: "04:00 PM", date: "2025-07-03" },
                { name: "Ralph W.", course: "Bachelor of Science in Information System", major: "N/A", unit: "Mabini", time: "04:30 PM", date: "2025-07-03" },
                { name: "Sara Y.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Tagum", time: "05:00 PM", date: "2025-07-03" },
                { name: "Tom X.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Mabini", time: "05:30 PM", date: "2025-07-03" },
                { name: "Uma F.", course: "Bachelor of Science in Information System", major: "N/A", unit: "Tagum", time: "06:00 PM", date: "2025-07-03" },
                { name: "Victor D.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Mabini", time: "06:30 PM", date: "2025-07-03" },
                { name: "Wendy C.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Tagum", time: "07:00 PM", date: "2025-07-03" },
                { name: "Xander M.", course: "Bachelor of Science in Information System", major: "N/A", unit: "Mabini", time: "07:30 PM", date: "2025-07-03" },
                { name: "Yara L.", course: "Bachelor of Science in Information Technology", major: "Information Security", unit: "Tagum", time: "08:00 PM", date: "2025-07-03" },
                { name: "Zane K.", course: "Bachelor of Science in Computer Science", major: "N/A", unit: "Mabini", time: "08:30 PM", date: "2025-07-03" }
            ]
        };
    },
    computed: {
        availableCourses() {
            const courses = [...new Set(this.students.map(student => student.course))];
            return courses.sort();
        },
        filteredStudents() {
            const search = this.searchText.toLowerCase();
            const date = this.selectedDate;
            const course = this.selectedCourse;
            const unit = this.selectedUnit;

            return this.students.filter(student => {
                const matchesSearch =
                    student.name.toLowerCase().includes(search) ||
                    student.course.toLowerCase().includes(search) ||
                    student.major.toLowerCase().includes(search);

                const matchesDate = !date || student.date === date;
                const matchesCourse = !course || student.course === course;
                const matchesUnit = !unit || student.unit === unit;

                return matchesSearch && matchesDate && matchesCourse && matchesUnit;
            });
        },
        totalPages() {
            return Math.ceil(this.filteredStudents.length / this.itemsPerPage);
        },
        paginatedStudents() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredStudents.slice(start, start + this.itemsPerPage);
        }
    },
    watch: {
        searchText() {
            this.currentPage = 1;
        },
        selectedDate() {
            this.currentPage = 1;
        },
        selectedCourse() {
            this.currentPage = 1;
        },
        selectedUnit() {
            this.currentPage = 1;
        }
    },
    methods: {
        selectUnit(unit) {
            this.selectedUnit = unit;
        },
        buttonClass(unit) {
            return [
                'px-3 py-1.5 rounded-lg font-medium transition-all duration-150',
                this.selectedUnit === unit
                    ? 'bg-white text-black shadow-sm'
                    : 'bg-transparent text-gray-600 hover:bg-gray-200',
            ];
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        }
    }
};
</script>
