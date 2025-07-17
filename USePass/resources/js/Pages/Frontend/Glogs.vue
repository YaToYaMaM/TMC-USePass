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
                <Link :href="route('guard.ghome')" class="text-white font-bold text-xs sm:text-sm hover:underline">HOME</Link>
                <Link :href="route('scan')" class="text-white font-bold text-xs sm:text-sm hover:underline">SCANNER</Link>
                <Link :href="route('logs')" class="text-white font-bold text-xs sm:text-sm hover:underline">LOGS</Link>
                <a href="#" class="text-white font-bold text-xs sm:text-sm hover:underline">LOGOUT</a>
              </nav>
            </div>
          </transition>
        </div>
      </div>
    </div>

    <!-- Fixed Yellow Section -->
    <div class=" fixed top-[9rem] w-full z-20 flex flex-col sm:flex-row items-center justify-between px-4 sm:px-10 md:px-20 py-2 space-y-2 sm:space-y-0">

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
      <!-- logs Display Area -->
      <div class="w-full h-full bg-red border border-gray-700 rounded-lg p-2 sm:p-4 space-y-3 sm:space-y-4 overflow-y-auto">
        <div
            v-for="(logs, index) in paginatedStudents"
            :key="index"
            class="flex items-center justify-between bg-white rounded-lg shadow px-2 py-1 sm:px-3 sm:py-1.5 border border-gray-300"
        >
          <div class="flex items-center space-x-2 sm:space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#760000] flex items-center justify-center text-white font-bold text-xs sm:text-sm">
              <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
            </div>
            <div class="flex flex-col text-[10px] sm:text-xs leading-tight">
              <span class="font-bold text-[#760000] uppercase truncate">{{ logs.time }}</span>
              <span class="text-gray-700 truncate">{{ logs.user }}</span>
            </div>
          </div>

          <!-- Department -->
          <div class="hidden sm:block text-[10px] sm:text-xs text-gray-600 font-medium text-center w-28 truncate">
            {{ logs.action }}
          </div>

          <!-- Timestamp -->
          <div class="text-[10px] sm:text-xs text-gray-700 text-right w-28 sm:w-auto">
            {{ logs.description }}
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
import { Link } from '@inertiajs/vue3'

export default {
  components: {
    Link
  },
  data() {
    return {
      menuOpen: false,
      currentPage: 1,
      itemsPerPage: 7,
      searchText: '',
      selectedDate: '2025-06-27',
      logs: [
        { time: "06/27/2025|11:35 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" },
        { time: "06/27/2025|11:36 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" },
        { time: "06/27/2025|11:37 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" },
        { time: "06/27/2025|11:38 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" },
        { time: "06/27/2025|11:39 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" },
        { time: "06/27/2025|11:40 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" },
        { time: "06/27/2025|11:41 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" },
        { time: "06/27/2025|11:42 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" },
        { time: "06/27/2025|11:43 AM", user: "John Doe", action: "Barcode Scan", description: "Froilan Canete Enter the Campus" }
      ]
    };
  },

  computed: {
    filteredlogs() {
      const date = this.selectedDate;

      return this.logs.filter(student => {
        const matchesDate = !date || student.date === date;

        return matchesDate;
      });
    },
    totalPages() {
      return Math.ceil(this.filteredlogs.length / this.itemsPerPage);
    },
    paginatedlogs() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredlogs.slice(start, start + this.itemsPerPage);
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
