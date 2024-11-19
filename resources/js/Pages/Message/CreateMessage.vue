<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 text-gray-600">
        <section class="mx-auto w-[80%] bg-white p-8 ringed_rounded max-w-4xl">
            <h1 class="text-center font-medium text-2xl uppercase ">Отправка сообщений</h1>
            <section class="flex items-center space-x-4 mt-4">
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    v-model="number"
                    required
                    :class="{'is-invalid': !is_valid}"
                    placeholder="Номер для отправки сообщения через WhatsApp в формате 922-143-1522"
                    class="form_element text-sm"
                    @input="clearValidationError"
                >
                <SecondaryButton class="shrink-0"
                                 @click="validatePhoneNumber">Добавить в рассылку</SecondaryButton>
            </section>
            <InputError :message="form.errors.numbers"
                        class="my-2" />
            <span v-if="!is_valid"
                  class="text-red-500 text-sm">Неверный формат номера</span>
	        
            <section class="grid md:grid-cols-6 grid-cols-3 gap-4 ringed_rounded my-4"
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
</script>
<style>
.ringed_rounded {
	@apply ring-1 ring-black ring-opacity-5 rounded-lg
}
</style>