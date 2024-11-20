<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 text-gray-600">
        <h1 class="text-center font-medium text-2xl uppercase ">Отправленные сообщения</h1>
	    
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
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">number</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">status</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="number in queue.numbers"
                                :key="number">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ findFromMessages('id', number) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ findFromMessages('status', number) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ findFromMessages('created_at', number) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <SecondaryButton class="mt-4"
                         @click="router.get(route('message.sent_queues'))">Список рассылок</SecondaryButton>
    </div>
</template>
<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {router} from '@inertiajs/vue3';

const props = defineProps({
    queue: {
        type: Object,
        required: true
    }
});

function findFromMessages(key, number) {
    if (!props.queue?.messages?.length) return null;
	
    let founded = props.queue.messages.find(row => row?.chat_id.split('@')[0] === number);
	
    if (key === 'status') {
        return defineStatus(founded);
    } else {
        return founded ? founded[key] : null;
    }
}

function defineStatus(founded) {
    if (!founded) {
        return 'Unprocessed';
    } else {
        return founded.status ? 'Success' : 'Fail';
    }
}
</script>