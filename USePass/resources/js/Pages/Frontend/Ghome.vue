<template>
    <div>
        <!-- Fixed Red Header -->
        <div class="fixed top-0 left-0 w-full h-20 bg-[#760000] z-40 flex items-center justify-between px-4 sm:px-6 md:px-8">
            <img src="/images/logo1.png" alt="Logo" class="w-20 sm:w-28 h-auto object-contain" />
            <div class="flex items-center space-x-2 sm:space-x-4">
                <div class="text-right">
                    <div class="text-white font-extrabold text-sm sm:text-base leading-tight">Joan M.</div>
                    <div class="text-white text-opacity-80 italic text-xs sm:text-sm -mt-1">Security Guard</div>
                </div>
                <img src="/images/profile.png" alt="Profile" class="h-10 w-10 sm:h-12 sm:w-12 rounded-full object-cover border-2 border-white" />
                <div class="relative">
                    <button @click="menuOpen = !menuOpen" class="focus:outline-none" aria-label="Toggle menu">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <transition name="fade">
                        <div v-if="menuOpen" class="absolute right-0 top-full mt-2 w-40 sm:w-48 bg-[#760000] rounded-lg shadow-lg border border-[#a00000] z-50">
                            <div class="flex items-center space-x-2 sm:space-x-3 px-3 py-2 border-b border-[#a00000]">
                                <img src="/images/profile.png" alt="Profile" class="h-8 w-8 sm:h-10 sm:w-10 rounded-full object-cover border-2 border-white" />
                                <div>
                                    <div class="text-white font-extrabold text-xs sm:text-sm">JOHN DOE</div>
                                    <div class="text-white text-opacity-80 italic text-[10px] sm:text-xs -mt-1">Security Guard</div>
                                </div>
                            </div>
                            <nav class="flex flex-col px-3 py-2 space-y-1 sm:space-y-2">
                                <a href="#" class="text-white font-bold text-xs sm:text-sm hover:underline">HOME</a>
                                <a href="#" class="text-white font-bold text-xs sm:text-sm hover:underline">SCANNER</a>
                                <a href="#" class="text-white font-bold text-xs sm:text-sm hover:underline">LOGS</a>
                                <a href="#" class="text-white font-bold text-xs sm:text-sm hover:underline">LOGOUT</a>
                            </nav>
                        </div>
                    </transition>
                </div>
            </div>
        </div>

        <!-- Fixed Blue Bar -->
        <div class=" fixed top-20 w-full z-30 h-12 flex items-center justify-between pt-5 sm:px-10 md:px-20">
            <h1 class="font-bold text-base sm:text-lg text-black">Home</h1>
            <div class="inline-flex text-[0.625rem] sm:text-[0.75rem] bg-gray-100 p-0.5 rounded-md shadow space-x-1">
                <button @click="selectUnit('Tagum')" :class="buttonClass('Tagum')">Tagum</button>
                <button @click="selectUnit('Mabini')" :class="buttonClass('Mabini')">Mabini</button>
            </div>
        </div>

        <!-- Fixed Yellow Section -->
        <div class=" fixed top-[9rem] w-full z-20 flex flex-col sm:flex-row items-center justify-between px-4 sm:px-10 md:px-20 py-2 space-y-2 sm:space-y-0">
            <!-- Search Input -->
            <div class="relative w-full sm:w-auto">
                <input
                    type="text"
                    v-model="searchText"
                    placeholder="Search a student..."
                    class="pl-9 pr-3 py-1 border border-gray-400 rounded-full text-sm w-full sm:w-60 focus:outline-none focus:ring-2 focus:ring-yellow-600"
                />
                <svg class="absolute left-2.5 top-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M15.5 10.5a5 5 0 11-10 0 5 5 0 0110 0z" />
                </svg>
            </div>

            <!-- Date and Course Select -->
            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                <input
                    type="date"
                    v-model="selectedDate"
                    class="border border-gray-400 rounded px-2 py-1 text-sm w-full sm:w-auto focus:outline-none"
                />
                <div class="relative w-full sm:w-64">
                    <select class="no-default-arrow appearance-none border border-gray-400 rounded px-2 py-1 pr-8 text-sm w-full focus:outline-none">
                        <option>Bachelor of Science in Information Technology</option>
                        <option>Bachelor of Science in Computer Science</option>
                        <option>Bachelor of Science in Information Systems</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Red Content Area -->
        <div
            class="fixed  bottom-0 left-0 right-0 p-4 sm:px-10 md:px-20 flex flex-col"
            style="top: calc(9rem + 3.25rem);"
        >
            <!-- Student Display Area -->
            <div class="w-full h-full bg-red border border-gray-700 rounded-lg p-2 sm:p-4 space-y-3 sm:space-y-4 overflow-y-auto">
                <div
                    v-for="(student, index) in paginatedStudents"
                    :key="index"
                    class="flex items-center justify-between bg-white rounded-lg shadow px-2 py-1 sm:px-3 sm:py-1.5 border border-gray-300"
                >
                    <!-- Profile & Info -->
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#760000] flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                            <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col text-[10px] sm:text-xs leading-tight">
                            <span class="font-bold text-[#760000] uppercase truncate">{{ student.name }}</span>
                            <span class="text-gray-700 truncate">{{ student.course }}</span>
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="hidden sm:block text-[10px] sm:text-xs text-gray-600 font-medium text-center w-28 truncate">
                        {{ student.subject }}
                    </div>

                    <!-- Timestamp -->
                    <div class="text-[10px] sm:text-xs text-gray-700 text-right w-28 sm:w-auto">
                        {{ student.time }} | {{ student.date }}
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center space-x-4 mt-4 text-gray-700 text-sm select-none pb-2">
                <button @click="prevPage" :disabled="currentPage === 1" class="hover:underline disabled:opacity-40">&lt;</button>
                <span class="font-semibold text-black">{{ currentPage }}</span>
                <button @click="nextPage" :disabled="currentPage === totalPages" class="hover:underline disabled:opacity-40">&gt;</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            menuOpen: false,
            selectedUnit: "Tagum",
            currentPage: 1,
            itemsPerPage: 7,
            searchText: '',
            selectedDate: '2025-07-03', // <-- date filter
            students: [
                { name: "Alice D.", course: "BS in IT", subject: "Data Structures", time: "08:00 AM", date: "2025-07-03" },
                { name: "Brian S.", course: "BS in CS", subject: "Algorithms", time: "08:30 AM", date: "2025-07-03" },
                { name: "Carla M.", course: "BS in IS", subject: "Project Management", time: "09:00 AM", date: "2025-07-03" },
                { name: "David P.", course: "BS in IT", subject: "Web Dev", time: "09:30 AM", date: "2025-07-03" },
                { name: "Ella G.", course: "BS in CS", subject: "OOP", time: "10:00 AM", date: "2025-07-03" },
                { name: "Frank L.", course: "BS in IS", subject: "Systems Analysis", time: "10:30 AM", date: "2025-07-03" },
                { name: "Grace K.", course: "BS in IT", subject: "Networking", time: "11:00 AM", date: "2025-07-03" },
                { name: "Harry T.", course: "BS in CS", subject: "Database Systems", time: "11:30 AM", date: "2025-07-03" },
                { name: "Isla R.", course: "BS in IS", subject: "E-Commerce", time: "12:00 PM", date: "2025-07-03" },
                { name: "Jake H.", course: "BS in IT", subject: "Cybersecurity", time: "12:30 PM", date: "2025-07-03" },
                { name: "Kate B.", course: "BS in CS", subject: "Machine Learning", time: "01:00 PM", date: "2025-07-03" },
                { name: "Leo C.", course: "BS in IS", subject: "IT Ethics", time: "01:30 PM", date: "2025-07-03" },
                { name: "Mia Z.", course: "BS in IT", subject: "Cloud Computing", time: "02:00 PM", date: "2025-07-03" },
                { name: "Noah N.", course: "BS in CS", subject: "AI Development", time: "02:30 PM", date: "2025-07-03" },
                { name: "Olivia J.", course: "BS in IS", subject: "MIS", time: "03:00 PM", date: "2025-07-03" },
                { name: "Paul V.", course: "BS in IT", subject: "Software Eng", time: "03:30 PM", date: "2025-07-03" },
                { name: "Queenie T.", course: "BS in CS", subject: "Data Mining", time: "04:00 PM", date: "2025-07-03" },
                { name: "Ralph W.", course: "BS in IS", subject: "Info Systems", time: "04:30 PM", date: "2025-07-03" },
                { name: "Sara Y.", course: "BS in IT", subject: "Operating Systems", time: "05:00 PM", date: "2025-07-03" },
                { name: "Tom X.", course: "BS in CS", subject: "Computer Graphics", time: "05:30 PM", date: "2025-07-03" },
                { name: "Uma F.", course: "BS in IS", subject: "Human-Computer Interaction", time: "06:00 PM", date: "2025-07-03" },
                { name: "Victor D.", course: "BS in IT", subject: "Mobile Dev", time: "06:30 PM", date: "2025-07-03" },
                { name: "Wendy C.", course: "BS in CS", subject: "Game Dev", time: "07:00 PM", date: "2025-07-03" },
                { name: "Xander M.", course: "BS in IS", subject: "IT Governance", time: "07:30 PM", date: "2025-07-03" },
                { name: "Yara L.", course: "BS in IT", subject: "Big Data", time: "08:00 PM", date: "2025-07-03" },
                { name: "Zane K.", course: "BS in CS", subject: "Network Security", time: "08:30 PM", date: "2025-07-03" }
            ]
        };
    },
    computed: {
        filteredStudents() {
            const search = this.searchText.toLowerCase();
            const date = this.selectedDate;

            return this.students.filter(student => {
                const matchesSearch =
                    student.name.toLowerCase().includes(search) ||
                    student.course.toLowerCase().includes(search) ||
                    student.subject.toLowerCase().includes(search);

                const matchesDate = !date || student.date === date;

                return matchesSearch && matchesDate;
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
        }
    },
    methods: {
        selectUnit(unit) {
            this.selectedUnit = unit;
        },
        buttonClass(unit) {
            return [
                'px-2 py-1 rounded-[5px] font-semibold transition text-center',
                this.selectedUnit === unit ? 'bg-white text-black shadow' : 'bg-transparent text-black',
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

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
