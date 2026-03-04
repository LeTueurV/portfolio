<section id="profil" class="py-28 px-8" style="background: #fff;">
    <div class="max-w-6xl mx-auto">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <!-- Left: Photo -->
            <div class="relative flex justify-center">
                <!-- Outer ring -->
                <div
                    class="absolute w-80 h-80 rounded-full"
                    style="border: 2px solid rgba(107,115,255,0.2); top: 50%; left: 50%; transform: translate(-50%, -50%);"
                ></div>
                <!-- Inner decoration -->
                <div
                    class="absolute w-64 h-64 rounded-full"
                    style="background: linear-gradient(135deg, #6B73FF10, #000DFF08); top: 50%; left: 50%; transform: translate(-50%, -50%);"
                ></div>
                <!-- Photo -->
                <div
                    class="relative w-64 h-64 rounded-full overflow-hidden"
                    style="border: 4px solid #fff; box-shadow: 0 20px 60px rgba(107,115,255,0.25);"
                >
                    <img
                        src="{{ $portfolio->photo_url }}"
                        alt="Photo de profil"
                        class="w-full h-full object-cover"
                    />
                    <div
                        class="absolute inset-0"
                        style="background: linear-gradient(135deg, rgba(0,13,255,0.2) 0%, transparent 60%);"
                    ></div>
                </div>

                <!-- Floating badge -->
                <div
                    class="absolute bottom-6 right-8 px-4 py-3 rounded-2xl"
                    style="background: linear-gradient(135deg, #000DFF, #6B73FF); box-shadow: 0 8px 24px rgba(0,13,255,0.3);"
                >
                    <p style="font-family: 'DM Sans', sans-serif; color: #fff; font-size: 0.75rem; opacity: 0.8;">Promotion</p>
                    <p style="font-family: 'Cormorant Garamond', serif; color: #fff; font-size: 1.3rem; font-weight: 600;">{{ $portfolio->year_start }} — {{ $portfolio->year_end }}</p>
                </div>
            </div>

            <!-- Right: Text -->
            <div>
                <!-- Section label -->
                <div class="flex items-center gap-3 mb-5">
                    <div class="h-px w-8" style="background: #6B73FF"></div>
                    <span style="font-family: 'DM Sans', sans-serif; color: #6B73FF; font-size: 0.78rem; letter-spacing: 0.15em; text-transform: uppercase;">
                        À propos
                    </span>
                </div>

                <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 600; color: #0d0d1a; line-height: 1.15;">
                    Un étudiant passionné
                    <span style="font-style: italic; color: #6B73FF;">par le développement</span>
                </h2>

                <p style="font-family: 'DM Sans', sans-serif; color: #555; font-size: 0.95rem; line-height: 1.8; margin-top: 1.2rem;">
                    {{ $portfolio->bio }}
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-6 mt-12">
                    <div>
                        <p style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 600; color: #000DFF;">2</p>
                        <p style="font-family: 'DM Sans', sans-serif; color: #888; font-size: 0.9rem; margin-top: 0.5rem;">Années de formation</p>
                    </div>
                    <div>
                        <p style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 600; color: #6B73FF;">{{ count($competences) }}</p>
                        <p style="font-family: 'DM Sans', sans-serif; color: #888; font-size: 0.9rem; margin-top: 0.5rem;">Compétences</p>
                    </div>
                    <div>
                        <p style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 600; color: #3b44d1;">{{ count($projects) }}</p>
                        <p style="font-family: 'DM Sans', sans-serif; color: #888; font-size: 0.9rem; margin-top: 0.5rem;">Projets</p>
                    </div>
                    <div>
                        <p style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 600; color: #8b92ff;">{{ count($stages) }}</p>
                        <p style="font-family: 'DM Sans', sans-serif; color: #888; font-size: 0.9rem; margin-top: 0.5rem;">Stages</p>
                    </div>
                </div>

                <!-- CTA -->
                <a
                    href="#realisations"
                    style="
                        font-family: 'DM Sans', sans-serif;
                        display: inline-block;
                        margin-top: 2rem;
                        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
                        color: #fff;
                        border-radius: 50px;
                        padding: 12px 28px;
                        font-size: 0.9rem;
                        font-weight: 500;
                        letter-spacing: 0.04em;
                        text-decoration: none;
                        box-shadow: 0 8px 24px rgba(107,115,255,0.3);
                        transition: all 0.3s ease;
                    "
                >
                    Découvrir mes réalisations
                </a>
            </div>
        </div>
    </div>
</section>
