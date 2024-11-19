<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 text-gray-600">
        <h1 class="text-center font-medium text-2xl uppercase ">Отправленные рассылки</h1>
	    
        <div class="mt-8 flow-root bg-white rounded-lg p-6"
             style="width: 80%;">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">id</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">message</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">amount</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="row in queues.data"
                                :key="row.id"
                                class="cursor-pointer"
                                @click="router.get(route('message.queue_details', {id: row.id}))">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ row.id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ row.message }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ row.numbers.length }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ row.created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {router} from '@inertiajs/vue3';

defineProps({
    queues: {
        type: Object,
        required: true,
    }
});
</script>