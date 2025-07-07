<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
</script>

    <template>
        <nav class="flex justify-between p-4 bg-red-800">
            <div class="text-lg text-white">
                USePass
            </div>
            <div>
                <ul class="flex gap-x-4 me-auto mb-2 mb-lg-0 ">
                    <li class="nav-item">
                        <Link class="text-white" :href="route('home')">Home</Link>
                    </li>

                </ul>
            </div>
        </nav>
    </template>

<style scoped>

</style>
