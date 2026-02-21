<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Input } from '@/components/ui/input'
import {
  Select, SelectContent, SelectGroup,
  SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select'
import { DEPARTAMENTOS } from './departamentos'

const props = defineProps<{ class?: HTMLAttributes['class'] }>()

const aceptaTerminos = ref(false)
const comprobante = ref<File | null>(null)

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  comprobante.value = target.files?.[0] ?? null
}
</script>

<template>
  <form :class="cn('flex flex-col gap-5', props.class)">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">Persona Jurídica</h1>
        <p class="text-muted-foreground text-sm">Completa el formulario para registrar tu empresa</p>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <Field>
          <FieldLabel for="nombres-j">Nombres</FieldLabel>
          <Input id="nombres-j" type="text" placeholder="Juan" required />
        </Field>
        <Field>
          <FieldLabel for="apellidos-j">Apellidos</FieldLabel>
          <Input id="apellidos-j" type="text" placeholder="Pérez" required />
        </Field>
      </div>

      <Field>
        <FieldLabel for="ruc">RUC</FieldLabel>
        <Input id="ruc" type="text" placeholder="20123456789" maxlength="11" required />
      </Field>

      <div class="grid grid-cols-2 gap-4">
        <Field>
          <FieldLabel for="celular-j">Celular</FieldLabel>
          <Input id="celular-j" type="tel" placeholder="987 654 321" required />
        </Field>
        <Field>
          <FieldLabel>Departamento</FieldLabel>
          <Select required>
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Selecciona" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem v-for="dep in DEPARTAMENTOS" :key="dep" :value="dep">
                  {{ dep }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </Field>
      </div>

      <Field>
        <FieldLabel for="email-j">Correo electrónico</FieldLabel>
        <Input id="email-j" type="email" placeholder="empresa@ejemplo.com" required />
        <FieldDescription>
          Usaremos este correo para contactarte. No lo compartiremos con nadie.
        </FieldDescription>
      </Field>

      <Field>
        <FieldLabel>Comprobante de pago</FieldLabel>
        <label
          for="comprobante-j"
          class="border-input bg-background hover:bg-muted flex cursor-pointer flex-col items-center justify-center rounded-md border border-dashed px-4 py-6 text-sm transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="text-muted-foreground mb-2 size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
          </svg>
          <span v-if="!comprobante" class="text-muted-foreground">
            Haz clic para subir o arrastra tu archivo aquí
          </span>
          <span v-else class="text-primary font-medium">{{ comprobante.name }}</span>
          <input id="comprobante-j" type="file" accept="image/*,.pdf" class="hidden" @change="onFileChange" required />
        </label>
        <FieldDescription>Formatos aceptados: JPG, PNG o PDF. Máximo 5 MB.</FieldDescription>
      </Field>

      <Field>
        <div class="flex items-start gap-3">
          <Checkbox id="terminos-j" v-model:checked="aceptaTerminos" />
          <label for="terminos-j" class="text-sm leading-snug cursor-pointer">
            Acepto los
            <Link href="/portal/terminos-condiciones" class="underline underline-offset-4 hover:text-primary">
              Términos y Condiciones
            </Link>
            y la
            <Link href="/portal/politicas-privacidad" class="underline underline-offset-4 hover:text-primary">
              Política de Privacidad
            </Link>.
          </label>
        </div>
      </Field>

      <Button type="submit" class="w-full" :disabled="!aceptaTerminos">
        Crear cuenta
      </Button>

      <FieldDescription class="text-center">
        ¿Ya tienes una cuenta?
        <Link href="/portal/login" class="underline underline-offset-4 hover:text-primary">
          Inicia sesión
        </Link>
      </FieldDescription>
    </FieldGroup>
  </form>
</template>