<footer
    class="py-8 px-8"
    style="background: #0a0a1a; border-top: 1px solid rgba(107,115,255,0.1)"
>
    <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <p
            style="
                font-family: 'Cormorant Garamond', serif;
                color: rgba(255,255,255,0.4);
                font-size: 1.1rem;
                font-style: italic;
            "
        >
            Portfolio BTS SIO SLAM — {{ $portfolio->full_name }}
        </p>
        <p
            style="
                font-family: 'DM Sans', sans-serif;
                color: rgba(255,255,255,0.25);
                font-size: 0.78rem;
                letter-spacing: 0.06em;
            "
        >
            © {{ $portfolio->year_start }} — {{ $portfolio->year_end }}
        </p>
    </div>
</footer>
