<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $person->name }}</title>

    @vite(['resources/css/app.css'])

    <link rel="stylesheet" href="https://unpkg.com/primeicons/primeicons.css">
</head>

<body class="bg-gray-50">

    <div class="min-h-screen px-4 py-6 flex items-center justify-center">

        <div class="w-full max-w-md">

            <div class="bg-white rounded-2xl shadow-sm p-5">

                {{-- PROFILE IMAGE --}}
                <div class="flex justify-center">
                    <div class="h-20 w-20 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                        @if (!empty($person->profile_picture))
                            <img src="{{ Storage::url($person->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-indigo-600 text-xl font-bold">
                                {{ strtoupper(substr($person->name ?? '?', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- NAME --}}
                <div class="text-center mt-1">
                    <h1 class="text-base font-semibold text-gray-900">
                        {{ strtoupper($person->name ?? 'UNKNOWN') }}
                    </h1>
                </div>

                {{-- SUMMARY --}}
                @if (!empty($person->summary))
                    <div class="text-center mt-0">
                        <p class="text-xs text-gray-500 leading-snug">
                            {{ $person->summary }}
                        </p>
                    </div>
                @endif

                {{-- ROLES --}}
                @if (!empty($person->roles) && $person->roles->count())
                    <div class="flex flex-wrap justify-center gap-1 mt-2">
                        @foreach ($person->roles as $role)
                            <span class="px-2 py-0.5 text-[10px] bg-indigo-100 text-indigo-700 rounded-full">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                @endif


                {{-- NOTES --}}
                @if (!empty($person->notes))
                    <div class="text-xs text-gray-600 leading-relaxed text-center whitespace-pre-line">
                        {{ $person->notes }}
                    </div>
                @endif




                {{-- CONTACT --}}
                {{-- <div class="mt-4 text-center">

                    @if ($person->show_email_publicly && !empty($person->email))
                        <div class="flex items-center justify-center gap-2 text-xs text-gray-600">
                            <i class="pi pi-envelope text-[10px] text-gray-400"></i>
                            <span class="truncate">{{ $person->email }}</span>
                        </div>
                    @endif

                    @if ($person->show_phone_publicly && !empty($person->phone))
                        <div class="flex items-center justify-center gap-2 text-xs text-gray-600">
                            <i class="pi pi-phone text-[10px] text-gray-400"></i>
                            <span>{{ $person->phone }}</span>
                        </div>
                    @endif

                </div> --}}
                
                {{-- SOCIAL --}}
                @if (!empty($person->socials) && $person->socials->count())
                    <div class="flex justify-center gap-2 mt-5 mb-1">
                        @foreach ($person->socials as $social)
                            @php
                                $platform = strtolower($social->platform ?? '');
                                $iconClass = optional($social->socialSelect)->icon;

                                if (!$iconClass) {
                                    $iconMap = [
                                        'facebook' => 'pi pi-facebook',
                                        'fb' => 'pi pi-facebook',
                                        'messenger' => 'pi pi-facebook',
                                        'instagram' => 'pi pi-instagram',
                                        'twitter' => 'pi pi-twitter',
                                        'x' => 'pi pi-twitter',
                                        'telegram' => 'pi pi-telegram',
                                        'tiktok' => 'pi pi-tiktok',
                                        'youtube' => 'pi pi-youtube',
                                        'linkedin' => 'pi pi-linkedin',
                                        'github' => 'pi pi-github',
                                        'website' => 'pi pi-globe',
                                        'web' => 'pi pi-globe',
                                    ];

                                    $iconClass = $iconMap[$platform] ?? 'pi pi-globe';
                                }
                            @endphp

                            <a href="{{ $social->url }}" target="_blank"
                                class="text-sm text-gray-500 hover:text-indigo-600 transition">
                                <i class="{{ $iconClass }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif



                



            </div>
            <p class="mt-4 flex items-center justify-center gap-1 text-[10px] text-gray-400">
                <span class="tracking-wide">POWERED BY PROJECT NAIT</span>
                <span class="flex items-center gap-1 font-medium text-gray-500">
                    <img src="{{ asset('projectnait.png') }}" alt="Project NAIT" class="h-3.5 w-3.5 object-contain">

                </span>
            </p>

        </div>

    </div>

</body>

</html>
