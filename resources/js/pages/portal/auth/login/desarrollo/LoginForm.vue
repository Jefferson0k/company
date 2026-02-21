<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"
import {
  Field,
  FieldDescription,
  FieldGroup,
  FieldLabel,
  FieldSeparator,
} from "@/components/ui/field"
import { Input } from "@/components/ui/input"

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <form :class="cn('flex flex-col gap-6', props.class)">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">Iniciar sesión</h1>
        <p class="text-muted-foreground text-sm text-balance">
          Ingresa tu correo electrónico para acceder a tu cuenta
        </p>
      </div>

      <Field>
        <FieldLabel for="email">Correo electrónico</FieldLabel>
        <Input id="email" type="email" placeholder="m@ejemplo.com" required />
      </Field>

      <Field>
        <div class="flex items-center">
          <FieldLabel for="password">Contraseña</FieldLabel>
          <a href="#" class="ml-auto text-sm underline-offset-4 hover:underline">
            ¿Olvidaste tu contraseña?
          </a>
        </div>
        <Input id="password" type="password" required />
      </Field>

      <Field>
        <Button type="submit">Iniciar sesión</Button>
      </Field>

      <Field>
        <FieldDescription class="text-center">
          ¿No tienes una cuenta?
          <Link href="/portal/registro" class="underline underline-offset-4 hover:text-primary">
            Regístrate
          </Link>
        </FieldDescription>
      </Field>
    </FieldGroup>

    <FieldDescription class="text-center text-muted-foreground text-xs">
      Al hacer clic en continuar, aceptas nuestros
      <Link href="/portal/terminos-condiciones" class="underline underline-offset-4 hover:text-primary">
        Términos de Servicio
      </Link>
      y
      <Link href="/portal/politicas-privacidad" class="underline underline-offset-4 hover:text-primary">
        Política de Privacidad
      </Link>.
    </FieldDescription>
  </form>
</template>