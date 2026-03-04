<nav
    id="navbar"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
>
    <div class="max-w-6xl mx-auto px-8 flex items-center justify-between h-20">
        <!-- Logo -->
        <a
            href="#hero"
            id="logo"
            class="text-2xl font-semibold tracking-wide transition-colors duration-300"
            style="font-family: 'Cormorant Garamond', serif;"
        >
            Portfolio
            <span class="italic font-light">BTS SIO</span>
        </a>

        <!-- Desktop Links -->
        <ul class="hidden md:flex gap-8 items-center">
            @foreach($navLinks as $link)
                <li>
                    <a
                        href="{{ $link['href'] }}"
                        class="nav-link relative group transition-colors duration-200 hover:opacity-80"
                        style="font-family: 'DM Sans', sans-serif; font-size: 0.93rem; letter-spacing: 0.04em;"
                    >
                        {{ $link['label'] }}
                        <span
                            class="absolute -bottom-1 left-0 h-[2px] w-0 group-hover:w-full transition-all duration-300 rounded-full"
                            style="background: #6B73FF"
                        ></span>
                    </a>
                </li>
            @endforeach
            <li>
                <a
                    href="#contact"
                    class="transition-opacity duration-200 hover:opacity-85"
                    style="
                        font-family: 'DM Sans', sans-serif;
                        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
                        color: #fff;
                        border-radius: 50px;
                        padding: 10px 24px;
                        font-size: 0.88rem;
                        letter-spacing: 0.05em;
                    "
                >
                    Me contacter
                </a>
            </li>
        </ul>

        <!-- Mobile Hamburger -->
        <button
            id="hamburger"
            class="md:hidden flex flex-col gap-1.5 p-2"
            aria-label="Toggle menu"
        >
            <span class="hamburger-line block w-6 h-0.5 transition-all duration-300"></span>
            <span class="hamburger-line block w-6 h-0.5 transition-all duration-300"></span>
            <span class="hamburger-line block w-6 h-0.5 transition-all duration-300"></span>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden px-8 pb-6 hidden" style="background: rgba(255,255,255,0.98); backdrop-filter: blur(12px);">
        @foreach($navLinks as $link)
            <a
                href="{{ $link['href'] }}"
                class="block py-3 border-b nav-mobile-link"
                style="
                    font-family: 'DM Sans', sans-serif;
                    color: #1a1a2e;
                    border-color: #e8eaff;
                    font-size: 0.95rem;
                "
            >
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>
</nav>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        const logo = document.getElementById('logo');
        const navLinks = document.querySelectorAll('.nav-link');
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerLines = document.querySelectorAll('.hamburger-line');

        // Gestion du scroll
        window.addEventListener('scroll', function() {
            const scrolled = window.scrollY > 0;

            if (scrolled) {
                navbar.style.background = 'rgba(255,255,255,0.95)';
                navbar.style.backdropFilter = 'blur(12px)';
                navbar.style.boxShadow = '0 2px 32px rgba(107,115,255,0.10)';
                logo.style.color = '#000DFF';
                navLinks.forEach(link => link.style.color = '#1a1a2e');
                hamburgerLines.forEach(line => line.style.background = '#000DFF');
            } else {
                navbar.style.background = 'transparent';
                navbar.style.backdropFilter = 'none';
                navbar.style.boxShadow = 'none';
                logo.style.color = '#fff';
                navLinks.forEach(link => link.style.color = '#fff');
                hamburgerLines.forEach(line => line.style.background = '#fff');
            }
        });

        // Gestion du menu mobile
        hamburger.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });

        // Fermer le menu mobile au clic sur un lien
        document.querySelectorAll('.nav-mobile-link').forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
            });
        });
    });
</script>
@endpush