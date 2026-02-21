<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { GalleryVerticalEnd } from "lucide-vue-next"
import { ref } from 'vue'
import NaturalSignupForm from "./desarrollo/NaturalSignupForm.vue";
import LegalSignupForm from "./desarrollo/LegalSignupForm.vue";

const tab = ref<'natural' | 'juridica'>('natural')
</script>

<template>
  <Head title="Registro" />
  <div class="grid min-h-svh lg:grid-cols-2">
    <div class="flex flex-col gap-4 p-6 md:p-10">
      <div class="flex justify-center gap-2 md:justify-start">
        <Link href="/" class="flex items-center gap-2 font-medium">
          <div class="bg-primary text-primary-foreground flex size-6 items-center justify-center rounded-md">
            <GalleryVerticalEnd class="size-4" />
          </div>
          Atrevia.
        </Link>
      </div>

      <div class="flex flex-1 items-center justify-center">
        <div class="w-full max-w-md">

          <!-- Tabs -->
          <div class="mb-6 flex rounded-lg border p-1">
            <button
              type="button"
              @click="tab = 'natural'"
              :class="[
                'flex-1 rounded-md py-2 text-sm font-medium transition-colors',
                tab === 'natural'
                  ? 'bg-primary text-primary-foreground shadow-sm'
                  : 'text-muted-foreground hover:text-foreground'
              ]"
            >
              Persona Natural
            </button>
            <button
              type="button"
              @click="tab = 'juridica'"
              :class="[
                'flex-1 rounded-md py-2 text-sm font-medium transition-colors',
                tab === 'juridica'
                  ? 'bg-primary text-primary-foreground shadow-sm'
                  : 'text-muted-foreground hover:text-foreground'
              ]"
            >
              Persona Jurídica
            </button>
          </div>

          <NaturalSignupForm v-if="tab === 'natural'" />
          <LegalSignupForm v-if="tab === 'juridica'" />

        </div>
      </div>
    </div>

    <div class="bg-muted relative hidden lg:block">
      <img src="/img/placeholder.svg" alt="Image"
        class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale">
    </div>
  </div>
</template>