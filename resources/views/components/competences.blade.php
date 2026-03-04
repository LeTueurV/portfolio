<section id="competences" class="py-28 px-8" style="background: #fff;">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="flex items-center justify-center gap-3 mb-5">
                <div class="h-px w-8" style="background: #6B73FF"></div>
                <span style="font-family: 'DM Sans', sans-serif; color: #6B73FF; font-size: 0.78rem; letter-spacing: 0.15em; text-transform: uppercase;">Compétences</span>
                <div class="h-px w-8" style="background: #6B73FF"></div>
            </div>
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 600; color: #0d0d1a; line-height: 1.15;">
                Blocs de <span style="font-style: italic; color: #6B73FF;">compétences BTS SIO</span>
            </h2>
        </div>

        <!-- Competences Grid -->
        <div class="grid md:grid-cols-3 gap-6">
            @php
                $blocColors = [
                    'B1' => ['bg' => 'rgba(0,13,255,0.07)', 'text' => '#000DFF', 'border' => 'rgba(0,13,255,0.2)'],
                    'B2' => ['bg' => 'rgba(107,115,255,0.1)', 'text' => '#6B73FF', 'border' => 'rgba(107,115,255,0.25)'],
                    'B3' => ['bg' => 'rgba(59,68,209,0.08)', 'text' => '#3b44d1', 'border' => 'rgba(59,68,209,0.2)'],
                ];

                $competencesByBloc = $competences->groupBy('bloc');
            @endphp

            @foreach($competencesByBloc as $bloc => $comps)
            <div
                class="rounded-3xl p-8 transition-all duration-300 hover:-translate-y-1"
                style="
                    background: {{ $blocColors[$bloc]['bg'] }};
                    border: 2px solid {{ $blocColors[$bloc]['border'] }};
                    box-shadow: 0 4px 24px rgba(0,0,0,0.05);
                "
            >
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-10 h-10 rounded-lg flex items-center justify-center"
                        style="background: {{ $blocColors[$bloc]['text'] }};"
                    >
                        <span style="color: #fff; font-weight: 600; font-size: 1.1rem;">{{ $bloc }}</span>
                    </div>
                    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.2rem; font-weight: 600; color: {{ $blocColors[$bloc]['text'] }};">
                        Bloc {{ $bloc }}
                    </h3>
                </div>

                <div class="space-y-3">
                    @foreach($comps as $comp)
                    <div style="padding-bottom: 1rem; border-bottom: 1px solid {{ $blocColors[$bloc]['border'] }};">
                        <div class="flex items-start gap-2 mb-1">
                            <span
                                style="
                                    font-family: 'DM Sans', sans-serif;
                                    font-size: 0.7rem;
                                    font-weight: 700;
                                    color: #fff;
                                    background: {{ $blocColors[$bloc]['text'] }};
                                    padding: 2px 6px;
                                    border-radius: 3px;
                                "
                            >
                                {{ $comp->code }}
                            </span>
                        </div>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 0.9rem; color: #333; line-height: 1.5;">
                            {{ $comp->label }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
