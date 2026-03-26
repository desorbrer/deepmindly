<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HALT Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Custom styles that extend Tailwind */
        :root {
            --bg-primary: #0d1117;
            --bg-card: #161b22;
            --bg-card-hover: #1c2128;
            --border-color: #30363d;
        }

        body {
            background-color: var(--bg-primary);
        }

        /* Custom range slider styles */
        .halt-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 4px;
            border-radius: 9999px;
            outline: none;
            cursor: pointer;
        }

        .halt-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid rgba(255,255,255,0.15);
            box-shadow: 0 0 8px rgba(0,0,0,0.5);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .halt-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
            box-shadow: 0 0 14px rgba(0,0,0,0.6);
        }

        .halt-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid rgba(255,255,255,0.15);
            box-shadow: 0 0 8px rgba(0,0,0,0.5);
        }

        /* Hungry — amber */
        .slider-hungry {
            background: linear-gradient(to right, #f59e0b var(--val, 0%), #2d3748 var(--val, 0%));
        }
        .slider-hungry::-webkit-slider-thumb { background: #f59e0b; }
        .slider-hungry::-moz-range-thumb     { background: #f59e0b; }

        /* Angry — red */
        .slider-angry {
            background: linear-gradient(to right, #ef4444 var(--val, 0%), #2d3748 var(--val, 0%));
        }
        .slider-angry::-webkit-slider-thumb { background: #ef4444; }
        .slider-angry::-moz-range-thumb     { background: #ef4444; }

        /* Lonely — purple */
        .slider-lonely {
            background: linear-gradient(to right, #a855f7 var(--val, 0%), #2d3748 var(--val, 0%));
        }
        .slider-lonely::-webkit-slider-thumb { background: #a855f7; }
        .slider-lonely::-moz-range-thumb     { background: #a855f7; }

        /* Tired — cyan */
        .slider-tired {
            background: linear-gradient(to right, #22d3ee var(--val, 0%), #2d3748 var(--val, 0%));
        }
        .slider-tired::-webkit-slider-thumb { background: #22d3ee; }
        .slider-tired::-moz-range-thumb     { background: #22d3ee; }

        /* Circular progress rings */
        .progress-ring__circle {
            transition: stroke-dashoffset 0.6s ease;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        /* Card hover */
        .halt-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .halt-card:hover {
            background-color: var(--bg-card-hover);
            border-color: #4a5568;
        }

        /* Recent entry progress bars */
        .entry-bar {
            height: 6px;
            border-radius: 9999px;
            transition: width 0.5s ease;
        }

        /* Glow on save button */
        .btn-save {
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.35);
            transition: box-shadow 0.2s ease, transform 0.15s ease;
        }
        .btn-save:hover {
            box-shadow: 0 0 32px rgba(6, 182, 212, 0.55);
            transform: translateY(-1px);
        }
        .btn-save:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="min-h-screen font-sans text-gray-100 antialiased">

{{-- ─── Header ─────────────────────────────────────────────── --}}
<div class="max-w-5xl mx-auto px-4 py-10">

    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold tracking-tight text-cyan-400 mb-2">HALT Tracker</h1>
        <p class="text-gray-400 text-sm">Monitor your emotional and physical states. Drag the sliders to indicate intensity.</p>
    </div>

    {{-- ─── Check-in Form ──────────────────────────────────── --}}
    <form method="POST" action="{{ route('halt.store') }}" id="halt-form">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            {{-- Hungry --}}
            <div class="halt-card rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-[#1f2937] flex items-center justify-center text-xl">🍽️</div>
                        <div>
                            <p class="font-semibold text-white">Hungry</p>
                            <p class="text-xs text-gray-500" id="label-hungry">None</p>
                        </div>
                    </div>
                    <span class="text-gray-400 text-sm font-medium" id="pct-hungry">0%</span>
                </div>
                <input
                    type="range" min="0" max="100" value="{{ old('hungry', 0) }}"
                    name="hungry"
                    class="halt-slider slider-hungry"
                    data-key="hungry"
                    style="--val: {{ old('hungry', 0) }}%"
                >
            </div>

            {{-- Angry --}}
            <div class="halt-card rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-[#1f2937] flex items-center justify-center text-xl">🔥</div>
                        <div>
                            <p class="font-semibold text-white">Angry</p>
                            <p class="text-xs text-gray-500" id="label-angry">None</p>
                        </div>
                    </div>
                    <span class="text-gray-400 text-sm font-medium" id="pct-angry">0%</span>
                </div>
                <input
                    type="range" min="0" max="100" value="{{ old('angry', 0) }}"
                    name="angry"
                    class="halt-slider slider-angry"
                    data-key="angry"
                    style="--val: {{ old('angry', 0) }}%"
                >
            </div>

            {{-- Lonely --}}
            <div class="halt-card rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-[#1f2937] flex items-center justify-center text-xl">🫀</div>
                        <div>
                            <p class="font-semibold text-white">Lonely</p>
                            <p class="text-xs text-gray-500" id="label-lonely">None</p>
                        </div>
                    </div>
                    <span class="text-gray-400 text-sm font-medium" id="pct-lonely">0%</span>
                </div>
                <input
                    type="range" min="0" max="100" value="{{ old('lonely', 0) }}"
                    name="lonely"
                    class="halt-slider slider-lonely"
                    data-key="lonely"
                    style="--val: {{ old('lonely', 0) }}%"
                >
            </div>

            {{-- Tired --}}
            <div class="halt-card rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-[#1f2937] flex items-center justify-center text-xl">🌙</div>
                        <div>
                            <p class="font-semibold text-white">Tired</p>
                            <p class="text-xs text-gray-500" id="label-tired">None</p>
                        </div>
                    </div>
                    <span class="text-gray-400 text-sm font-medium" id="pct-tired">0%</span>
                </div>
                <input
                    type="range" min="0" max="100" value="{{ old('tired', 0) }}"
                    name="tired"
                    class="halt-slider slider-tired"
                    data-key="tired"
                    style="--val: {{ old('tired', 0) }}%"
                >
            </div>

        </div>

        {{-- Save button --}}
        <div class="flex justify-center mb-12">
            <button type="submit" class="btn-save text-white font-semibold px-8 py-3 rounded-xl flex items-center gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                Save Check-in
            </button>
        </div>
    </form>

    {{-- ─── Statistics ─────────────────────────────────────── --}}
    <div class="halt-card rounded-2xl p-6 mb-4">
        <h2 class="text-lg font-bold text-white mb-6">Your Statistics</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">

            @php
                $stats = [
                    ['key' => 'hungry', 'label' => 'Hungry',  'color' => '#f59e0b', 'avg' => $avgHungry ?? 0],
                    ['key' => 'angry',  'label' => 'Angry',   'color' => '#ef4444', 'avg' => $avgAngry  ?? 0],
                    ['key' => 'lonely', 'label' => 'Lonely',  'color' => '#a855f7', 'avg' => $avgLonely ?? 0],
                    ['key' => 'tired',  'label' => 'Tired',   'color' => '#22d3ee', 'avg' => $avgTired  ?? 0],
                ];
                $r = 36; $circ = 2 * M_PI * $r;
            @endphp

            @foreach ($stats as $s)
                @php $offset = $circ - ($s['avg'] / 100) * $circ; @endphp
                <div class="flex flex-col items-center gap-2">
                    <div class="relative w-24 h-24">
                        <svg class="w-full h-full" viewBox="0 0 88 88">
                            {{-- track --}}
                            <circle cx="44" cy="44" r="{{ $r }}" fill="none" stroke="#2d3748" stroke-width="7"/>
                            {{-- progress --}}
                            <circle
                                class="progress-ring__circle"
                                cx="44" cy="44" r="{{ $r }}"
                                fill="none"
                                stroke="{{ $s['color'] }}"
                                stroke-width="7"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circ }}"
                                stroke-dashoffset="{{ $offset }}"
                            />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-lg font-bold" style="color: {{ $s['color'] }}">{{ round($s['avg']) }}%</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-white">{{ $s['label'] }}</p>
                        <p class="text-xs text-gray-500">Average</p>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="border-t border-[#30363d] pt-4 text-center text-sm text-gray-500">
            Total check-ins: <span class="text-cyan-400 font-semibold">{{ $totalCheckins ?? 0 }}</span>
        </div>
    </div>

    {{-- ─── Recent Entries ─────────────────────────────────── --}}
    <div class="halt-card rounded-2xl p-6">
        <h2 class="text-lg font-bold text-white mb-5">Recent Entries</h2>

        @forelse ($recentEntries ?? [] as $entry)
            <div class="bg-[#1c2128] border border-[#30363d] rounded-xl p-4 mb-3 last:mb-0">
                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                    <span>{{ $entry->created_at->format('M d, Y') }}</span>
                    <span>{{ $entry->created_at->format('g:i A') }}</span>
                </div>

                @php
                    $fields = [
                        'hungry' => ['label' => 'Hungry',  'color' => '#f59e0b', 'val' => $entry->hungry],
                        'angry'  => ['label' => 'Angry',   'color' => '#ef4444', 'val' => $entry->angry],
                        'lonely' => ['label' => 'Lonely',  'color' => '#a855f7', 'val' => $entry->lonely],
                        'tired'  => ['label' => 'Tired',   'color' => '#22d3ee', 'val' => $entry->tired],
                    ];
                    $active = array_filter($fields, fn($f) => $f['val'] > 0);
                @endphp

                @foreach ($active as $f)
                    <div class="flex items-center gap-3 mb-2 last:mb-0">
                        <span class="text-xs font-medium text-white w-14 shrink-0">{{ $f['label'] }}</span>
                        <div class="flex-1 bg-[#2d3748] rounded-full h-1.5 overflow-hidden">
                            <div class="entry-bar h-full rounded-full" style="width: {{ $f['val'] }}%; background-color: {{ $f['color'] }};"></div>
                        </div>
                        <span class="text-xs font-semibold w-9 text-right" style="color: {{ $f['color'] }}">{{ $f['val'] }}%</span>
                    </div>
                @endforeach

            </div>
        @empty
            <p class="text-gray-500 text-sm text-center py-6">No entries yet. Save your first check-in above.</p>
        @endforelse
    </div>

</div>{{-- /max-w-5xl --}}

{{-- ─── JavaScript ─────────────────────────────────────────── --}}
<script>
    (function () {
        const labels = {
            0:  'None',
            1:  'Minimal',
            25: 'Low',
            50: 'Moderate',
            75: 'High',
            90: 'Very High',
            100:'Extreme',
        };

        function getLabel(val) {
            const keys = Object.keys(labels).map(Number).sort((a, b) => a - b);
            let result = labels[0];
            for (const k of keys) {
                if (val >= k) result = labels[k];
            }
            return result;
        }

        document.querySelectorAll('.halt-slider').forEach(slider => {
            const key = slider.dataset.key;

            function update() {
                const val = parseInt(slider.value);
                slider.style.setProperty('--val', val + '%');
                document.getElementById('pct-' + key).textContent = val + '%';
                document.getElementById('label-' + key).textContent = getLabel(val);
            }

            slider.addEventListener('input', update);
            update(); // init
        });
    })();
</script>

</body>
</html>
