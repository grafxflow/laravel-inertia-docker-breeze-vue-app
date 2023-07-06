<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

defineProps({
  users: {
    type: Object,
  },
});

const confirmingUserRestore = ref(false);
const confirmingUserPermanentlyDelete = ref(false);
const userId = ref(null);

const form = useForm({});

const confirmUserRestore = (id) => {
  userId.value = id;
  confirmingUserRestore.value = true;
};

const confirmUserPermanentlyDelete = (id) => {
  userId.value = id;
  confirmingUserPermanentlyDelete.value = true;
};

const restoreUser = () => {
  form.patch(route('users.restore', {'user': userId.value}), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onFinish: () => form.reset(),
  });
};

const permanentlyDeleteUser = () => {
  form.delete(route('users.destroy', {'user': userId.value}), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserRestore.value = false;
  confirmingUserPermanentlyDelete.value = false;
  form.reset();
};
</script>

<template>
  <Head title="Users Trashed" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users Trashed</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-md sm:rounded-lg">
          <div class="flex flex-col">
            <div class="overflow-x-auto -my-2 sm:-mx-6 lg:-mx-8">
              <div class="inline-block py-2 min-w-full align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">

                  <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-indigo-500">
                      <tr>
                        <th scope="col" class="w-3/12 text-xs font-semibold tracking-wider text-left text-white uppercase">
                          <span class="inline-flex py-3 px-6 w-full justify-between" @click="sort('id')">
                            ID
                          </span>
                        </th>
                        <th scope="col" class="w-3/12 text-xs font-semibold tracking-wider text-left text-white uppercase">
                          <span class="inline-flex py-3 px-6 w-full justify-between" @click="sort('email')">
                            Email
                          </span>
                        </th>
                        <th scope="col" class="w-3/12 text-xs font-semibold tracking-wider text-left text-white uppercase">
                          <span class="inline-flex py-3 px-6 w-full justify-between" @click="sort('firstname')">
                            Firstname
                          </span>
                        </th>
                        <th scope="col" class="w-3/12 text-xs font-semibold tracking-wider text-left text-white uppercase">
                          <span class="inline-flex py-3 px-6 w-full justify-between" @click="sort('lastname')">
                            Lastname
                          </span>
                        </th>
                        <th scope="col" class="w-3/12 text-xs font-semibold tracking-wider text-left text-white uppercase">
                          <span class="inline-flex py-3 px-6 w-full justify-between">
                            Actions
                          </span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="(user, index) in users" :key="user.id">
                        <td class="py-4 px-6 whitespace-nowrap">
                          {{ user.id }}
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                          {{ user.email }}
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                          {{ user.firstname }}
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                          {{ user.lastname }}
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                          <!-- Start Actions -->
                          <!-- Start Restore User -->
                          <PrimaryButton @click="confirmUserRestore(user.id)">
                            Restore
                          </PrimaryButton>
                          <!-- End Restore User -->
                          <!-- Start Permanently Delete User -->
                          <DangerButton @click="confirmUserPermanentlyDelete(user.id)">
                            Permanently Delete
                          </DangerButton>
                          <!-- End Permanently Delete User -->
                          <!-- End Actions -->
                        </td>
                      </tr>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Modal :show="confirmingUserRestore" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          Are you sure you want to restore the user?
        </h2>
        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
          <PrimaryButton
          class="ml-3"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          @click="restoreUser(id)"
          >
            Restore User
          </PrimaryButton>
        </div>
      </div>
    </Modal>

    <Modal :show="confirmingUserPermanentlyDelete" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          Are you sure you want to permanently delete the user?
        </h2>
        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
          <DangerButton
          class="ml-3"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          @click="permanentlyDeleteUser(id)"
          >
            Permanently Delete
          </DangerButton>
        </div>
      </div>
    </Modal>

  </AuthenticatedLayout>
</template>
