<template>
    <Frontend>
        <div class="px-3 pt-6 min-h-screen overflow-auto space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Logs</h1>

                <div class="inline-flex justify-center text-[0.775rem] bg-gray-100 p-0.5 rounded-md shadow max-w-fit max-h-8">
                    <button
                        @click="selectUnit('Tagum')"
                        :class="[
                            'px-2 py-1 rounded-[5px] font-semibold transition text-center',
                            selectedUnit === 'Tagum'
                              ? 'bg-white text-black shadow'
                              : 'bg-transparent text-black'
                          ]"
                        >
                        Tagum
                    </button>
                    <button
                        @click="selectUnit('Mabini')"
                        :class="[
                            'px-2 py-1 rounded-[5px] font-semibold transition text-center',
                            selectedUnit === 'Mabini'
                              ? 'bg-white text-black shadow'
                              : 'bg-transparent text-black'
                          ]"
                        >
                        Mabini
                    </button>
                </div>

            </div>

            <div>
                <TableLogs/>
            </div>

        </div>

    </Frontend>
</template>



<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import TableLogs from "@/Components/TableLogs.vue";
import {ref} from "vue";
import {Head} from "@inertiajs/vue3";
defineProps({
    title: String
})

// const selectedLocation = ref('Tagum');
const selectedUnit = ref('Tagum')
function selectUnit(unit) {
    selectedUnit.value = unit
}
</script>
