@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php the_post() @endphp

        <article class="page-content content">
            @php the_content() @endphp

            <section id="uvod" class="min-h-[70vh] relative flex flex-col page-header"  x-data="{activeImage: 0}">
              @php
                $section = get_field('hero_banner_slider');
                $title = $section["title"];
                $description = $section["description"];
                $images = acf_photo_gallery('images', $post->ID);
              @endphp
              {{-- carousel --}}
              <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
                @foreach (array_reverse($images) as $image)
                  @php
                      $imageContent = wp_get_attachment_image($image['id'], "large", false, ['class' => 'absolute left-0 top-0 w-full h-full object-cover']);
                  @endphp
                  <div class="w-screen h-full flex-none absolute left-0 top-0" x-show="activeImage === {{ $loop->index }}" x-transition:enter.scale.80 x-transition.duration.1000ms x-transition.opacity>
                    {!! $imageContent !!}
                  </div>
                @endforeach
              </div>
              <div class="absolute top-0 left-0 w-full h-full bg-black opacity-60">/</div>

              {{-- Text --}}
              <div class="py-20 md:py-40 relative my-auto flex justify-between text-white">
                <button @click="activeImage = activeImage == 0 ? {{ count($images)-1 }} : activeImage-1" class="mx-5 group hidden sm:block hover:scale-110 active:scale-95 duration-200">
                  <div class="border-2 border-white p-4 rounded-full duration-150 group-hover:bg-white group-hover:text-black group-active:bg-white group-active:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                  </div>
                </button>

                <div class="text-center mx-5">
                  <h1 class="text-4xl uppercase font-semibold">{{ $title }}</h1>
                  <p class="mt-8 leading-9 text-lg max-w-4xl mx-auto">{{ $description }}</p>
                </div>

                <button @click="activeImage = activeImage == {{ count($images)-1 }} ? 0 : activeImage+1" class="mx-5 group hidden sm:block hover:scale-110 active:scale-95 duration-200">
                  <div class="border-2 border-white p-4 rounded-full duration-150 group-hover:bg-white group-hover:text-black group-active:bg-white group-active:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                  </div>
                </button>
              </div>

              {{-- Navigation --}}
              <div class="relative mx-auto flex pb-10 justify-center">
                @foreach ($images as $image)
                  <button @click="activeImage = {{ $loop->index }}" class="p-2 group">
                    <div class="h-1 bg-white duration-200" :class="activeImage === {{ $loop->index }} ? 'w-20' : 'w-12 group-hover:scale-x-110 opacity-40'"></div>
                  </button>
                @endforeach
              </div>
            </section>

            <section class="md:flex py-14 container" id="o-mne">
                @php
                    $section = get_field('section-about-me');
                    $title = $section['title'];
                    $text = $section['text'];
                    $image = $section['image'];
                @endphp
                <div class="w-full typography">
                    <h3 class="!text-4xl mt-16 uppercase">{{ $title }}</h3>
                    <p class="text-md mt-7 leading-loose">{{ $text }}</p>
                </div>
                <div class="flex-none w-full md:max-w-[540px] aspect-square relative mt-10 sm:mt-0">
                    @php
                        $image_size = 'main-page-about-me-section-image';
                        $imageContent = wp_get_attachment_image($image['ID'], $image_size, false, ['class' => 'absolute left-0 top-0 w-full h-full object-cover']);
                    @endphp

                    {!! $imageContent !!}
                </div>
            </section>

            <section class="section dark sm:-mt-40" id="sluzby">
                {{-- Prepare --}}
                @php
                    $section = get_field('section-services');
                    $title = $section['title'];
                    $args = [
                        'post_type' => 'services',
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'posts_per_page' => 999,
                    ];
                    $query = new WP_Query($args);
                @endphp
                {{-- Content --}}
                <div class="container">
                    <h3 class="text-white text-4xl uppercase">{{ $title }}</h3>
                    <div class="grid md:grid-cols-2 gap-x-20 lg:gap-x-40 gap-y-16 text-white mt-12">
                        @if ($query->have_posts())
                            @while ($query->have_posts())
                                @php
                                    $query->the_post();
                                @endphp

                                <article>
                                    <h1 class="text-2xl font-semibold uppercase">{{ get_the_title() }}</h1>
                                    <div class="typography dark mt-6">
                                        {{ the_content() }}
                                    </div>
                                </article>
                            @endwhile
                        @else
                            <p>Zatím žádné položky</p>
                        @endif
                    </div>
                </div>
                {{-- Cleanup --}}
                @php
                    wp_reset_postdata();
                @endphp
            </section>

            <section class="section" id="reference">
                {{-- Prepare --}}
                @php
                    $referencesSection = get_field('section-references');
                    $title = $referencesSection['title'];
                    $args = [
                        'post_type' => 'references',
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'posts_per_page' => 999,
                    ];
                    $query = new WP_Query($args);
                @endphp
                {{-- Content --}}
                <div class="container">
                    <h3 class="text-black text-4xl text-center uppercase">{{ $title }}</h3>
                    <div class="divide-y-2 space-y-2 mt-9 typography">
                        @if ($query->have_posts())
                            @while ($query->have_posts())
                                @php
                                    $query->the_post();
                                @endphp

                                <article x-data="dropdown()" class="">
                                    <div x-on:click="toggle"
                                        class="py-7 group cursor-pointer flex justify-between items-center">
                                        <h1 class="group-hover:underline text-3xl font-semibold !mb-0">{{ get_the_title() }}</h1>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-6 h-6" :class="open ? 'rotate-180' : ''">
                                            <path fill-rule="evenodd"
                                                d="M12.53 16.28a.75.75 0 01-1.06 0l-7.5-7.5a.75.75 0 011.06-1.06L12 14.69l6.97-6.97a.75.75 0 111.06 1.06l-7.5 7.5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="px-3 sm:px-8" x-show="open" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-5"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-5">
                                        {{ the_content() }}
                                        <span class="block pt-5"></span>
                                    </div>
                                </article>
                            @endwhile
                        @else
                            <p>Zatím žádné položky</p>
                        @endif
                    </div>
                </div>
                {{-- Cleanup --}}
                @php
                    wp_reset_postdata();
                @endphp
            </section>

            <section class="section" id="galerie">
              {{-- Prepare --}}
              @php
                  $section = get_field('section-fotogallery');
                  $title = $section['title'];
                  $args = [
                    // Should be a page "Fotogalerie"
                    'p'         => 11,
                    'post_type' => 'any'
                  ];
                  $query = new WP_Query($args);
              @endphp
              {{-- Content --}}
              <div class="container">
                  <h3 class="text-black text-4xl text-center uppercase">{{ $title }}</h3>
                  <div class="mt-20 relative">
                    <div class="h-[388px] relative keen-slider" x-data="slider" x-ref="root">
                      @if ($query->have_posts())
                        @while ($query->have_posts())
                          @php
                              $query->the_post();
                              $items = acf_photo_gallery('images', $post->ID);
                          @endphp
                          @foreach ($items as $item)
                            @php
                                $imageContent = wp_get_attachment_image($image['id'], "large", false, ['class' => 'absolute left-0 top-0 w-full h-full object-cover']);
                            @endphp
                            <div data-index="{{ $loop->index }}" class="relative flex-none w-full sm:w-[33.33%] snap-center duration-200 keen-slider__slide">
                              <div class="relative w-full h-full flex items-center justify-center aspect-square">
                                {!! $imageContent  !!}
                              </div>
                            </div>
                          @endforeach
                        @endwhile
                      @endif
                    </div>

                    <button @click="slider.prev()" class="absolute left-1.5 top-1/2 -translate-y-1/2 hover:scale-110 active:scale-95 duration-200 bg-white rounded-full p-2">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                      </svg>
                    </button>

                    <button @click="slider.next()" class="absolute right-1.5 top-1/2 -translate-y-1/2 hover:scale-110 active:scale-95 duration-200 bg-white rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                  </div>
              </div>
              {{-- Cleanup --}}
              @php
                wp_reset_postdata();
              @endphp
            </section>
        </article>



        {{-- "Druhy dokumentací modal"´ --}}
        {{-- Prepare --}}
        @php
            $args = [
              // Should be a page "Druhy dokumentací"
              'p'         => 71,
              'post_type' => 'any'
            ];
            $query = new WP_Query($args);
        @endphp
        @if ($query->have_posts())
          @while ($query->have_posts())
            @php
                $query->the_post();
            @endphp

            <dialog
              @close="history.replaceState(null, null, ' ');"
              x-data
              @hashchange.window="location.hash === '#druhy-dokumentace' ? $refs.root.showModal() : null"
              x-ref="root"
              x-init="$nextTick(() => { if (location.hash === '#druhy-dokumentace') { $refs.root.showModal(); } })"
            >
                <div @click="$refs.form.submit()" class="bg-black fixed inset-0 backdrop-blur-sm opacity-40"></div>
                <form x-ref="form" method="dialog" class="container mx-5 bg-white rounded py-10 lg:py-16 px-5 sm:px-10 lg:px-16 relative typography">
                  {{ the_content() }}
                </form>
                <button @click="$refs.form.submit()" class="absolute top-0 right-0 m-2 md:m-10 bg-white rounded-full shadow-lg p-3">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red"
                      class="w-8 h-8">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
              </button>
            </dialog>
          @endwhile
        @endif
        {{-- Cleanup --}}
        @php
          wp_reset_postdata();
        @endphp
    @endwhile
@endsection
