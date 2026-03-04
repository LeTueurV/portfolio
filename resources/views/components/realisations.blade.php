<section id="realisations" class="py-28 px-8" style="background: linear-gradient(180deg, #f7f8ff 0%, #fff 100%);">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-20">
            <div class="flex items-center justify-center gap-3 mb-5">
                <div class="h-px w-8" style="background: #6B73FF"></div>
                <span style="font-family: 'DM Sans', sans-serif; color: #6B73FF; font-size: 0.78rem; letter-spacing: 0.15em; text-transform: uppercase;">
                    Réalisations
                </span>
                <div class="h-px w-8" style="background: #6B73FF"></div>
            </div>
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 600; color: #0d0d1a; line-height: 1.15;">
                Mes <span style="font-style: italic; color: #6B73FF;">expériences</span>
            </h2>
        </div>

        <!-- Stages Timeline -->
        <div class="mb-20">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; font-weight: 600; color: #0d0d1a; margin-bottom: 3rem;">
                Stages en <span style="font-style: italic; color: #6B73FF;">entreprise</span>
            </h3>

            <div class="relative">
                <!-- Vertical line -->
                <div
                    class="absolute left-1/2 top-0 bottom-0 w-px hidden md:block"
                    style="background: linear-gradient(to bottom, #6B73FF, #000DFF); transform: translateX(-50%); opacity: 0.2;"
                ></div>

                <div class="space-y-12">
                    @foreach($stages as $index => $stage)
                    <div class="relative grid md:grid-cols-2 gap-8 items-center {{ $index % 2 === 1 ? 'md:flex-row-reverse' : '' }}">
                        <!-- Timeline dot -->
                        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 hidden md:block z-10">
                            <div
                                class="w-4 h-4 rounded-full"
                                style="background: linear-gradient(135deg, #000DFF, #6B73FF); box-shadow: 0 0 0 4px rgba(107,115,255,0.15);"
                            ></div>
                        </div>

                        <!-- Content -->
                        <div class="{{ $index % 2 === 1 ? 'md:col-start-2' : '' }}">
                            <div
                                class="rounded-3xl p-8 transition-all duration-300 hover:-translate-y-1"
                                style="background: #fff; border: 1px solid rgba(107,115,255,0.12); box-shadow: 0 4px 32px rgba(107,115,255,0.07);"
                            >
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-5">
                                    <div>
                                        <p style="font-family: 'DM Sans', sans-serif; font-size: 0.75rem; color: #6B73FF; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 6px;">
                                            {{ $stage->start_date->translatedFormat('F') }} — {{ $stage->end_date->translatedFormat('F Y') }}
                                        </p>
                                        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 600; color: #0d0d1a;">
                                            {{ $stage->company->name }}
                                        </h3>
                                        <p style="font-family: 'DM Sans', sans-serif; font-size: 0.85rem; color: #888; margin-top: 2px;">
                                            {{ $stage->company->sector }} — {{ $stage->role }}
                                        </p>
                                    </div>
                                    <div
                                        class="shrink-0 px-3 py-1.5 rounded-xl"
                                        style="background: rgba(107,115,255,0.08); border: 1px solid rgba(107,115,255,0.15);"
                                    >
                                        <p style="font-family: 'DM Sans', sans-serif; font-size: 0.75rem; color: #6B73FF; font-weight: 600;">
                                            {{ $stage->duration }}
                                        </p>
                                    </div>
                                </div>

                                <p style="font-family: 'DM Sans', sans-serif; color: #555; font-size: 0.875rem; line-height: 1.8; margin-bottom: 1.2rem;">
                                    {{ $stage->description }}
                                </p>

                                <!-- Competences -->
                                <div class="flex flex-wrap gap-2 pt-5" style="border-top: 1px solid #f0f0fa;">
                                    <span style="font-family: 'DM Sans', sans-serif; font-size: 0.72rem; color: #aaa; margin-right: 4px; display: flex; align-items: center;">
                                        COMPÉTENCES:
                                    </span>
                                    @foreach($stage->competences as $comp)
                                    <span
                                        style="
                                            font-family: 'DM Sans', sans-serif;
                                            font-size: 0.75rem;
                                            color: #000DFF;
                                            background: rgba(0,13,255,0.07);
                                            border-radius: 6px;
                                            padding: 3px 9px;
                                            font-weight: 600;
                                        "
                                    >
                                        {{ $comp->code }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="{{ $index % 2 === 1 ? 'md:col-start-1 md:row-start-1' : '' }}"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Projects Realizizations -->
        <div>
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; font-weight: 600; color: #0d0d1a; margin-bottom: 3rem;">
                Réalisations par <span style="font-style: italic; color: #6B73FF;">entreprise</span>
            </h3>

            @foreach($companies as $company)
                @if($realisationsByCompany->has($company->id))
                <div class="mb-16">
                    <div class="flex items-center gap-3 mb-8">
                        <img
                            src="{{ asset('storage/' . $company->photo_url) }}"
                            alt="{{ $company->name }}"
                            class="w-16 h-16 rounded-xl object-cover"
                        />
                        <div>
                            <h4 style="font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; font-weight: 600; color: #0d0d1a;">
                                {{ $company->name }}
                            </h4>
                            <p style="font-family: 'DM Sans', sans-serif; font-size: 0.85rem; color: #888;">
                                {{ $company->sector }}
                            </p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($realisationsByCompany[$company->id] as $realisation)
                        <div
                            class="rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1"
                            style="border: 1px solid rgba(107,115,255,0.12); box-shadow: 0 4px 24px rgba(107,115,255,0.06);"
                        >
                            <span
                                class="inline-block mb-3 px-3 py-1 rounded-full"
                                style="
                                    font-family: 'DM Sans', sans-serif;
                                    font-size: 0.72rem;
                                    letter-spacing: 0.08em;
                                    color: #6B73FF;
                                    background: rgba(107,115,255,0.12);
                                    border: 1px solid rgba(107,115,255,0.25);
                                "
                            >
                                @if($realisation->type === 'stage') Stage @else Projet @endif
                            </span>

                            <h4 style="font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; font-weight: 600; color: #0d0d1a;">
                                {{ $realisation->title }}
                            </h4>

                            <p style="font-family: 'DM Sans', sans-serif; color: #666; font-size: 0.875rem; line-height: 1.75; margin-top: 0.6rem;">
                                {{ $realisation->description }}
                            </p>

                            <!-- Tags -->
                            @if(count($realisation->tags))
                            <div class="flex flex-wrap gap-2 mt-4">
                                @foreach($realisation->tags as $tag)
                                <span
                                    style="
                                        font-family: 'DM Sans', sans-serif;
                                        font-size: 0.7rem;
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
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
