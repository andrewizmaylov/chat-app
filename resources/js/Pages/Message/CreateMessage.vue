<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 text-gray-600">
        <section class="relative mx-auto w-[80%] bg-white p-8 ringed_rounded max-w-4xl">
            <div class="absolute inset-0 bg-white opacity-90 grid place-content-center cursor-pointer"
                 v-if="license_expired"
                 @click="router.get(route('message.sent_queues'))">
                <h5 class="text-5xl font-bold uppercase text-center mb-4">License expired</h5>
                <p>To work with the application, you need to obtain a new LicenseKey and update the data in the .env file</p>
            </div>
            <h1 class="text-center font-medium text-2xl uppercase ">Отправка сообщений</h1>
            <section class="mt-4">
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    v-model="number"
                    required
                    :class="{'is-invalid': !is_valid}"
                    placeholder="Номер для отправки сообщения через WhatsApp в формате +7-922-112-10-23"
                    class="form_element text-sm"
                    @input="clearValidationError"
                >
                <section class="flex items-center space-x-4 mt-4">
                    <SecondaryButton class="shrink-0"
                                     @click="validatePhoneNumber">Добавить в рассылку</SecondaryButton>
	                
                    <form method="post"
                          enctype="multipart/form-data">
	                    
                        <label for="file"
                               class="shrink-0 cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Загрузить из файла
                        </label>
                        <input type="file"
                               id="file"
                               name="file"
                               class="hidden"
                               @change="proceedFile($event)"
                               accept=".doc,.xml,.csv,.txt" />
                    </form>
                </section>
            </section>
            <InputError :message="form.errors.numbers"
                        class="my-2" />
            <span v-if="!is_valid"
                  class="text-red-500 text-sm">Неверный формат номера</span>
	        
            <div class="mt-4 text-blue-500"
                 v-if="count">Импортировано {{ count }} записей</div>
            <section class="grid md:grid-cols-6 grid-cols-3 gap-4 ringed_rounded my-4 max-h-[260px] overflow-y-auto"
                     v-if="form.numbers.length">
                <span class="py-2 col-span-1 text-center"
                      v-for="number in form.numbers"
                      :key="number">
                    +{{ number }}
                </span>
            </section>
            <textarea name="message"
                      id="message"
                      cols="30"
                      rows="10"
                      v-model="form.message"
                      @input="clearFormError('message')"
                      class="block w-full my-4 rounded-lg border border-gray-200"/>
            <InputError :message="form.errors.message"
                        class="mt-2 my-4" />
            <SecondaryButton class="shrink-0"
                             @click="sendMessage">Отправить сообщение</SecondaryButton>
        </section>
    </div>
</template>
<script setup>
import {router, useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    license_expired: {
        type: Boolean,
    }
});

let number = ref('');

let is_valid = ref(true);
function clearValidationError() {
    is_valid.value = true;
}
function validatePhoneNumber() {
    clearFormError('numbers');
    let validated_number = number.value.replace(/[^0-9]/g, '');

    const regex = /^[0-9]{11,12}$/;
    is_valid.value = regex.test(validated_number);
    if (is_valid.value) {
        updateNumberList(validated_number);
    }
}


let form = useForm({
    _method: 'POST',
    numbers: [],
    message: '',
});
function updateNumberList(sanitizedNumber) {
    form.numbers.push(sanitizedNumber);
    number.value = '';
    is_valid.value = true;
}
function sendMessage() {
    form.post(route('message.create_message_queue'), {
        errorBag: 'message.create_message_queue',
        preserveScroll: true,
        onSuccess: () => {
            router.get(route('message.sent_queues'));
        },
    });
}
function clearFormError(field) {
    delete form.errors[field];
}

let count = ref(0);
let file = ref(null);
function proceedFile($event) {
    const {target} = $event;
    if (target && target.files) {
        [file.value] = target.files;
        const formData = new FormData();
        formData.append('file', file.value);
        axios.post(route('message.create_list_from_file'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data', 
            }
        }).then((response) => {
            count.value = response.data.count;
            form.numbers = response.data.phone_numbers;
            console.log(response.data);
        });
    }
}
</script>
<style>
.ringed_rounded {
	@apply ring-1 ring-black ring-opacity-5 rounded-lg
}
</style>