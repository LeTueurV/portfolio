<section
    id="hero"
    class="relative min-h-screen flex items-center justify-center overflow-hidden"
    style="background: linear-gradient(135deg, #000DFF 0%, #6B73FF 60%, #a8adff 100%);"
>
    <!-- Background image overlay -->
    <div
        class="absolute inset-0 opacity-10"
        style="
            background-image: url('https://images.unsplash.com/photo-1772037441269-947195bb80f0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=1080');
            background-size: cover;
            background-position: center;
        "
    ></div>

    <!-- Decorative circles -->
    <div
        class="absolute top-20 right-20 w-96 h-96 rounded-full opacity-10"
        style="background: radial-gradient(circle, #fff 0%, transparent 70%); filter: blur(40px);"
    ></div>
    <div
        class="absolute bottom-32 left-10 w-72 h-72 rounded-full opacity-10"
        style="background: radial-gradient(circle, #fff 0%, transparent 70%); filter: blur(30px);"
    ></div>

    <!-- Geometric line decorations -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
        <svg width="100%" height="100%" class="absolute inset-0 opacity-10">
            <defs>
                <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                    <path d="M 60 0 L 0 0 0 60" fill="none" stroke="white" stroke-width="0.5" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

    <!-- Content -->
    <div class="relative z-10 text-center px-8 max-w-4xl mx-auto">
        <!-- Badge -->
        <div
            class="inline-flex items-center gap-2 mb-8 px-5 py-2 rounded-full"
            style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);"
        >
            <span
                class="w-2 h-2 rounded-full animate-pulse"
                style="background: #fff"
            ></span>
            <span
                style="
                    font-family: 'DM Sans', sans-serif;
                    color: #fff;
                    font-size: 0.82rem;
                    letter-spacing: 0.12em;
                    text-transform: uppercase;
                "
            >
                BTS SIO — Option SLAM
            </span>
        </div>

        <!-- Name -->
        <h1
            style="
                font-family: 'Cormorant Garamond', serif;
                color: #fff;
                font-size: clamp(3.5rem, 9vw, 7rem);
                font-weight: 300;
                line-height: 1.05;
                letter-spacing: -0.01em;
            "
        >
            {{ $portfolio->first_name }}
            <span style="font-style: italic; font-weight: 600;">{{ $portfolio->last_name }}</span>
        </h1>

        <!-- Subtitle -->
        <p
            style="
                font-family: 'Cormorant Garamond', serif;
                color: rgba(255,255,255,0.85);
                font-size: clamp(1.3rem, 3vw, 1.9rem);
                font-weight: 300;
                font-style: italic;
                margin-top: 1rem;
                letter-spacing: 0.02em;
            "
        >
            Étudiant passionné par le développement logiciel
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-12">
            <a
                href="#profil"
                style="
                    font-family: 'DM Sans', sans-serif;
                    background: transparent;
                    color: #fff;
                    border: 2px solid #fff;
                    border-radius: 50px;
                    padding: 14px 36px;
                    font-size: 0.95rem;
                    font-weight: 500;
                    letter-spacing: 0.04em;
                    text-decoration: none;
                    display: inline-block;
                    transition: all 0.3s ease;
                "
                onmouseover="this.style.background='rgba(255,255,255,0.1)';"
                onmouseout="this.style.background='transparent';"
            >
                Découvrir
            </a>
            <a
                href="#contact"
                style="
                    font-family: 'DM Sans', sans-serif;
                    background: linear-gradient(135deg, #fff 0%, #f0f0ff 100%);
                    color: #000DFF;
                    border-radius: 50px;
                    padding: 14px 36px;
                    font-size: 0.95rem;
                    font-weight: 500;
                    letter-spacing: 0.04em;
                    text-decoration: none;
                    display: inline-block;
                    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
                    transition: all 0.3s ease;
                "
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 16px 48px rgba(0,0,0,0.2)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 12px 40px rgba(0,0,0,0.15)';"
            >
                Me contacter
            </a>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M12 5v14M5 12l7 7 7-7" />
            </svg>
        </div>
    </div>
</section>
