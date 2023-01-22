<footer class="w-full bg-site-blue footer">
    <div class="container grid sm:grid-cols-2 md:grid-cols-3 gap-14">
        <div id="kontakt">
            @php dynamic_sidebar('sidebar-with-contact') @endphp
        </div>
        <div>
            @php dynamic_sidebar('sidebar-with-billing') @endphp
        </div>
        <div>
            @php dynamic_sidebar('sidebar-with-documents') @endphp
        </div>
    </div>
</footer>
