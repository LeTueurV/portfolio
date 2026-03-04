<section
    id="contact"
    class="py-28 px-8 relative overflow-hidden"
    style="background: linear-gradient(135deg, #000DFF 0%, #6B73FF 100%);"
>
    <!-- Decorations -->
    <div
        class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-10"
        style="background: radial-gradient(circle, #fff 0%, transparent 70%); filter: blur(40px); transform: translate(30%, -30%);"
    ></div>
    <div
        class="absolute bottom-0 left-0 w-72 h-72 rounded-full opacity-10"
        style="background: radial-gradient(circle, #fff 0%, transparent 70%); filter: blur(30px); transform: translate(-30%, 30%);"
    ></div>

    <!-- Grid pattern -->
    <svg class="absolute inset-0 w-full h-full opacity-5 pointer-events-none">
        <defs>
            <pattern id="cgrid" width="50" height="50" patternUnits="userSpaceOnUse">
                <path d="M 50 0 L 0 0 0 50" fill="none" stroke="white" stroke-width="0.8" />
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#cgrid)" />
    </svg>

    <div class="relative z-10 max-w-4xl mx-auto text-center">
        <!-- Label -->
        <div class="flex items-center justify-center gap-3 mb-6">
            <div class="h-px w-8" style="background: rgba(255,255,255,0.4)"></div>
            <span style="font-family: 'DM Sans', sans-serif; color: rgba(255,255,255,0.7); font-size: 0.78rem; letter-spacing: 0.15em; text-transform: uppercase;">
                Contact
            </span>
            <div class="h-px w-8" style="background: rgba(255,255,255,0.4)"></div>
        </div>

        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 300; color: #fff; line-height: 1.1;">
            Entrons en <span style="font-style: italic; font-weight: 600;">contact</span>
        </h2>

        <p style="font-family: 'DM Sans', sans-serif; color: rgba(255,255,255,0.7); font-size: 1rem; line-height: 1.8; margin-top: 1.2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
            {{ $portfolio->contact_message }}
        </p>

        <!-- Contact cards -->
        <div class="grid sm:grid-cols-3 gap-4 mt-12 mb-12">
            <!-- Email -->
            <div
                class="rounded-2xl p-6 flex flex-col items-center gap-3 transition-all duration-200 hover:scale-105"
                style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);"
            >
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.15);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="white" stroke-width="1.5" />
                        <polyline points="22,6 12,13 2,6" stroke="white" stroke-width="1.5" />
                    </svg>
                </div>
                <div>
                    <p style="font-family: 'DM Sans', sans-serif; color: rgba(255,255,255,0.6); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em;">
                        Email
                    </p>
                    <p style="font-family: 'DM Sans', sans-serif; color: #fff; font-size: 0.85rem; margin-top: 2px;">
                        {{ $portfolio->email }}
                    </p>
                </div>
            </div>

            <!-- LinkedIn -->
            <div
                class="rounded-2xl p-6 flex flex-col items-center gap-3 transition-all duration-200 hover:scale-105"
                style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);"
            >
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.15);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z" stroke="white" stroke-width="1.5" />
                        <rect x="2" y="9" width="4" height="12" stroke="white" stroke-width="1.5" />
                        <circle cx="4" cy="4" r="2" stroke="white" stroke-width="1.5" />
                    </svg>
                </div>
                <div>
                    <p style="font-family: 'DM Sans', sans-serif; color: rgba(255,255,255,0.6); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em;">
                        LinkedIn
                    </p>
                    <p style="font-family: 'DM Sans', sans-serif; color: #fff; font-size: 0.85rem; margin-top: 2px;">
                        <a href="{{ $portfolio->linkedin_url }}" target="_blank" style="color: #fff; text-decoration: none;">Profil</a>
                    </p>
                </div>
            </div>

            <!-- GitHub -->
            <div
                class="rounded-2xl p-6 flex flex-col items-center gap-3 transition-all duration-200 hover:scale-105"
                style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);"
            >
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.15);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <p style="font-family: 'DM Sans', sans-serif; color: rgba(255,255,255,0.6); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em;">
                        GitHub
                    </p>
                    <p style="font-family: 'DM Sans', sans-serif; color: #fff; font-size: 0.85rem; margin-top: 2px;">
                        <a href="{{ $portfolio->github_url }}" target="_blank" style="color: #fff; text-decoration: none;">Profil</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <a
            href="mailto:{{ $portfolio->email }}"
            style="
                font-family: 'DM Sans', sans-serif;
                background: #fff;
                color: #000DFF;
                border-radius: 50px;
                padding: 16px 42px;
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
            Envoyer un email
        </a>
    </div>
</section>
