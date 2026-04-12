<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import ThemeSwitcher from '@/Components/Frontend/ThemeSwitcher.vue';

defineOptions({ layout: FrontendLayout });

defineProps({
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
});
</script>

<template>
  <Head title="Welcome" />

  <div class="absolute top-4 right-4 z-10">
    <ThemeSwitcher />
  </div>

  <!-- Animated Gradient Background -->
  <div class="fixed inset-0 overflow-hidden">
    <div class="gradient-bg" />
    <div class="gradient-orb gradient-orb-1" />
    <div class="gradient-orb gradient-orb-2" />
    <div class="gradient-orb gradient-orb-3" />
  </div>

  <!-- Content -->
  <div class="relative flex items-center justify-center min-h-screen px-4">
    <div class="text-center">
      <!-- Main Title with Gradient Text and Border Animation -->
      <div class="relative inline-block mb-8">
        <h1 class="hero-text">
          Laravel Boilerplate
        </h1>
        <div class="hero-border" />
      </div>

      <!-- Subtitle -->
      <p class="text-xl md:text-2xl text-white/80 mb-4 font-light">
        Modern Full-Stack Development
      </p>

      <p class="text-sm md:text-base text-white/60 mb-12">
        Laravel v{{ laravelVersion }} • PHP v{{ phpVersion }} • Vue 3 • TypeScript
      </p>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <Link
          href="/login"
          class="cta-button cta-button-primary"
        >
          Get Started
          <svg
            class="w-5 h-5 ml-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 7l5 5m0 0l-5 5m5-5H6"
            />
          </svg>
        </Link>

        <Link
          href="/register"
          class="cta-button cta-button-secondary"
        >
          Create Account
        </Link>
      </div>

      <!-- Features Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-20 max-w-4xl mx-auto">
        <div class="feature-card">
          <div class="feature-icon">
            <svg
              class="w-8 h-8"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 10V3L4 14h7v7l9-11h-7z"
              />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-white mb-2">
            Lightning Fast
          </h3>
          <p class="text-white/60 text-sm">
            Built with modern tools for optimal performance
          </p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <svg
              class="w-8 h-8"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
              />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-white mb-2">
            Secure by Default
          </h3>
          <p class="text-white/60 text-sm">
            Enterprise-grade security and authentication
          </p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <svg
              class="w-8 h-8"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"
              />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-white mb-2">
            Fully Customizable
          </h3>
          <p class="text-white/60 text-sm">
            Tailored to your specific needs and workflows
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Animated Gradient Background */
.gradient-bg {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
  background-size: 400% 400%;
  animation: gradientShift 15s ease infinite;
}

.gradient-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.6;
  animation: float 20s ease-in-out infinite;
}

.gradient-orb-1 {
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(102, 126, 234, 0.8) 0%, transparent 70%);
  top: -10%;
  left: -10%;
  animation-delay: 0s;
}

.gradient-orb-2 {
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(240, 147, 251, 0.8) 0%, transparent 70%);
  bottom: -10%;
  right: -10%;
  animation-delay: 5s;
}

.gradient-orb-3 {
  width: 450px;
  height: 450px;
  background: radial-gradient(circle, rgba(79, 172, 254, 0.8) 0%, transparent 70%);
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation-delay: 10s;
}

@keyframes gradientShift {

  0%,
  100% {
    background-position: 0% 50%;
  }

  50% {
    background-position: 100% 50%;
  }
}

@keyframes float {

  0%,
  100% {
    transform: translate(0, 0) scale(1);
  }

  33% {
    transform: translate(30px, -30px) scale(1.1);
  }

  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
}

/* Hero Text with Gradient and Border Animation */
.hero-text {
  font-size: clamp(3rem, 10vw, 7rem);
  font-weight: 900;
  background: linear-gradient(135deg, #ffffff 0%, #a78bfa 25%, #ec4899 50%, #f59e0b 75%, #ffffff 100%);
  background-size: 200% 200%;
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: gradientText 8s ease infinite;
  letter-spacing: -0.02em;
  line-height: 1.1;
  position: relative;
  padding: 0.2em 0.4em;
}

@keyframes gradientText {

  0%,
  100% {
    background-position: 0% 50%;
  }

  50% {
    background-position: 100% 50%;
  }
}

/* Animated Border */
.hero-border {
  position: absolute;
  inset: -4px;
  border-radius: 16px;
  padding: 2px;
  background: linear-gradient(90deg,
      #667eea 0%,
      #764ba2 25%,
      #f093fb 50%,
      #4facfe 75%,
      #00f2fe 100%);
  background-size: 200% 200%;
  animation: borderRotate 4s linear infinite;
  -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  pointer-events: none;
  opacity: 0.8;
}

@keyframes borderRotate {
  0% {
    background-position: 0% 50%;
  }

  100% {
    background-position: 200% 50%;
  }
}

/* CTA Buttons */
.cta-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 1rem 2rem;
  font-size: 1.125rem;
  font-weight: 600;
  border-radius: 12px;
  transition: all 0.3s ease;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  min-width: 200px;
}

.cta-button::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.cta-button:hover::before {
  opacity: 1;
}

.cta-button-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
}

.cta-button-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 50px rgba(102, 126, 234, 0.6);
}

.cta-button-secondary {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(10px);
}

.cta-button-secondary:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.5);
  transform: translateY(-2px);
}

/* Feature Cards */
.feature-card {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  padding: 2rem;
  transition: all 0.3s ease;
}

.feature-card:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.2);
  transform: translateY(-4px);
}

.feature-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
  border-radius: 16px;
  color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .hero-text {
    font-size: clamp(2.5rem, 8vw, 4rem);
  }

  .feature-card {
    padding: 1.5rem;
  }
}
</style>
