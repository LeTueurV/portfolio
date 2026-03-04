<section id="projets" class="py-28 px-8" style="background: #fff;">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16 gap-6">
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="h-px w-8" style="background: #6B73FF"></div>
                    <span style="font-family: 'DM Sans', sans-serif; color: #6B73FF; font-size: 0.78rem; letter-spacing: 0.15em; text-transform: uppercase;">
                        Portfolio
                    </span>
                </div>
                <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 600; color: #0d0d1a; line-height: 1.15;">
                    Mes <span style="font-style: italic; color: #6B73FF;">réalisations</span>
                </h2>
            </div>
            <p style="font-family: 'DM Sans', sans-serif; color: #888; font-size: 0.9rem; max-width: 320px; line-height: 1.7;">
                Projets réalisés en formation, en stage et en autonomie, couvrant les différents blocs de compétences du BTS SIO.
            </p>
        </div>

        @php
            $typeColors = [
                'Application Web' => '#000DFF',
                'Application Mobile' => '#6B73FF',
                'Script & Automatisation' => '#3b44d1',
                'Base de données' => '#8b92ff',
            ];
        @endphp

        <!-- Projects grid -->
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($projects as $projet)
            <div
                class="group rounded-3xl overflow-hidden transition-all duration-300 hover:-translate-y-1"
                style="border: 1px solid rgba(107,115,255,0.12); box-shadow: 0 4px 24px rgba(107,115,255,0.06);"
            >
                <!-- Image placeholder -->
                <div
                    class="relative h-48 flex items-center justify-center overflow-hidden"
                    style="background: linear-gradient(135deg, {{ $typeColors[$projet->type] ?? '#6B73FF' }}15, {{ $typeColors[$projet->type] ?? '#6B73FF' }}05);"
                >
                    <!-- Abstract decoration -->
                    <div
                        class="absolute -top-8 -right-8 w-40 h-40 rounded-full opacity-10"
                        style="background: {{ $typeColors[$projet->type] ?? '#6B73FF' }}"
                    ></div>
                    <div
                        class="absolute -bottom-6 -left-6 w-28 h-28 rounded-full opacity-8"
                        style="background: {{ $typeColors[$projet->type] ?? '#6B73FF' }}"
                    ></div>

                    <!-- Icon -->
                    <div
                        class="w-16 h-16 rounded-2xl flex items-center justify-center"
                        style="background: {{ $typeColors[$projet->type] ?? '#6B73FF' }}; box-shadow: 0 8px 24px {{ $typeColors[$projet->type] ?? '#6B73FF' }}40;"
                    >
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3M3 16v3a2 2 0 002 2h3m8 0h3a2 2 0 002-2v-3" stroke="white" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>

                    <!-- Year badge -->
                    <div
                        class="absolute top-4 right-4 px-3 py-1 rounded-full"
                        style="background: rgba(255,255,255,0.85); backdrop-filter: blur(8px);"
                    >
                        <span style="font-family: 'DM Sans', sans-serif; font-size: 0.75rem; color: {{ $typeColors[$projet->type] ?? '#6B73FF' }}; font-weight: 600;">
                            {{ $projet->year }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-7">
                    <!-- Type badge -->
                    <span
                        class="inline-block mb-3 px-3 py-1 rounded-full"
                        style="
                            font-family: 'DM Sans', sans-serif;
                            font-size: 0.72rem;
                            letter-spacing: 0.08em;
                            color: {{ $typeColors[$projet->type] ?? '#6B73FF' }};
                            background: {{ $typeColors[$projet->type] ?? '#6B73FF' }}12;
                            border: 1px solid {{ $typeColors[$projet->type] ?? '#6B73FF' }}25;
                        "
                    >
                        {{ $projet->type }}
                    </span>

                    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 600; color: #0d0d1a;">
                        {{ $projet->title }}
                    </h3>

                    <p style="font-family: 'DM Sans', sans-serif; color: #666; font-size: 0.875rem; line-height: 1.75; margin-top: 0.6rem;">
                        {{ $projet->description }}
                    </p>

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mt-5">
                        @foreach($projet->tags as $tag)
                        <span
                            style="
                                font-family: 'DM Sans', sans-serif;
                                font-size: 0.75rem;
                                color: #6B73FF;
                                background: rgba(107,115,255,0.1);
                                padding: 4px 8px;
                                border-radius: 4px;
                                font-weight: 500;
                            "
                        >
                            {{ $tag->tag }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
