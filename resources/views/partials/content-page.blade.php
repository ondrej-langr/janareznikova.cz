<article class="page-content content container py-10 min-h-[50vh]">
  @php the_content() @endphp
</article>

{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
