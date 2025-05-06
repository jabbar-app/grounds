<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, LogIn } from 'lucide-vue-next';

defineProps<{
  status?: string;
  canResetPassword: boolean;
}>();

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <AuthBase title="Login to your account" description="Enter your email and password below to log in">

    <Head title="Login" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
      <div class="grid gap-4">
        <div class="grid gap-2">
          <Label for="email">Email address</Label>
          <Input id="email" type="email" required autofocus :tabindex="1" autocomplete="email" v-model="form.email"
            placeholder="email@example.com" />
          <InputError :message="form.errors.email" />
        </div>

        <div class="grid gap-2">
          <div class="flex items-center justify-between">
            <Label for="password">Password</Label>
            <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
              Forgot password?
            </TextLink>
          </div>
          <Input id="password" type="password" required :tabindex="2" autocomplete="current-password"
            v-model="form.password" placeholder="Password" />
          <InputError :message="form.errors.password" />
        </div>

        <div class="flex items-center justify-between">
          <Label for="remember" class="flex items-center space-x-3">
            <Checkbox id="remember" v-model="form.remember" :tabindex="3" />
            <span>Remember me</span>
          </Label>
        </div>

        <!-- Submit Button -->
        <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">
          <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
          <span v-else>Log in</span>
        </Button>

        <!-- Login via Inisiator -->
        <!-- <a href="/auth/inisiator/redirect"
          class="relative inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
          <LogIn class="w-4 h-4" />
          Login via Inisiator
          <span
            class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full shadow-md">
            SSO
          </span>
        </a> -->
      </div>

      <div class="text-center text-sm text-muted-foreground">
        Don't have an account?
        <TextLink :href="route('register')" :tabindex="5">Sign up</TextLink>
      </div>
    </form>
  </AuthBase>
</template>
