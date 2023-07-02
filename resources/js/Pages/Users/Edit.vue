<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    default: () => ({}),
  },
});

const form = useForm({
  userId: props.user.id,
  name: props.user.name,
  email: props.user.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.patch(route('users.update', {'user': props.user.id}), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <Head title="User Edit" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Edit</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

          <form @submit.prevent="submit">

            <div class="mt-4">
              <InputLabel for="name" value="Name" />

              <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
              />

              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
              <InputLabel for="email" value="Email" />

              <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
                autocomplete="username"
              />

              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
              <InputLabel for="password" value="Password" />

              <TextInput
                id="password"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password"
                autocomplete="new-password"
              />

              <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
              <InputLabel for="password_confirmation" value="Confirm Password" />

              <TextInput
                id="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password_confirmation"
                autocomplete="new-password"
              />

              <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
              <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Edit User
              </PrimaryButton>
            </div>
          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
