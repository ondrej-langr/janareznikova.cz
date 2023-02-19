<header
  class="site-header duration-300"
  x-data="{ isActive: false, disapearAfter: 0 }"
  x-init="$nextTick(() => {
      const size = Array.from(document.body.querySelectorAll('.page-header'))[0]?.offsetHeight ?? 0;
      disapearAfter = size ? size - (document.body.querySelector('header.site-header')?.offsetHeight * 2) : size;
   })"
  :class=" isActive ? 'opacity-0 -translate-y-full' : 'opacity-100' "
  @scroll.window.debounce.50ms="disapearAfter ? (isActive=window.scrollY >= disapearAfter) : null"
>
  <div class="max-w-7xl px-5 mx-auto flex justify-between items-center md:flex-row-reverse" x-data="{ isMobileOpen: false }" @resize.window="isMobileOpen = false" @scroll.window.debounce.150ms="isMobileOpen = false">
    <nav x-bind:data-open="isMobileOpen">
      <div :class="isMobileOpen ? 'pt-10 !text-black' : 'text-white'">
        @if (has_nav_menu('primary_navigation'))
          {!! wp_nav_menu(['theme_location' => 'primary_navigation']) !!}
        @endif
      </div>
    </nav>
    {!! \App\Controllers\AppController::custom_logo() !!}
    <button class="bg-white rounded-lg p-2 md:hidden shadow-lg relative w-12 h-12" @click="isMobileOpen = !isMobileOpen">
      <svg x-show="!isMobileOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 absolute top-1 left-1">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
      </svg>
      <svg x-show="isMobileOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 absolute top-1 left-1">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</header>
