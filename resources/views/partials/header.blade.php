<header
  class="site-header duration-300"
  x-data="{ isActive: false, disapearAfter: 0 }"
  x-init="$nextTick(() => {
    const size = Array.from(document.body.querySelectorAll('.page-header'))[0]?.offsetHeight ?? 0;
      disapearAfter = size ? size - (document.body.querySelector('header.site-header')?.offsetHeight * 2): size;
   })"
  :class=" isActive ? 'opacity-0 -translate-y-full' : 'opacity-100' "
  @scroll.window.debounce.150ms="disapearAfter ? (isActive=window.scrollY >= disapearAfter) : null"
>
    <nav class="max-w-7xl px-5 mx-auto">
      {!! \App\Controllers\AppController::custom_logo() !!}
      @if (has_nav_menu('primary_navigation')) {!! wp_nav_menu(['theme_location' => 'primary_navigation']) !!}
      @endif
    </nav>
</header>
