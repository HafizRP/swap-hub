@section('title', 'Swap Opportunity')
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
            <div class="flex-grow-1">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('skill-swaps.index') }}"
                                class="text-decoration-none text-secondary hover-emerald small fw-bold">Marketplace</a>
                        </li>
                        <li class="breadcrumb-item active small fw-bold text-white" aria-current="page">Opportunity</li>
                    </ol>
                </nav>
                <h2 class="h3 fw-black text-white mb-0">
                    Swap Opportunity
                </h2>
            </div>

            @if(auth()->id() !== $skillSwapRequest->requester_id && $skillSwapRequest->status === 'pending')
                <form action="{{ route('skill-swaps.accept', $skillSwapRequest) }}" method="POST">
                    @csrf
                    <button class="btn btn-emerald rounded-pill px-5 py-3 fw-black shadow-lg">Accept & Start Chat</button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-sm overflow-hidden rounded-5">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-5">
                            <img src="{{ $skillSwapRequest->requester->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($skillSwapRequest->requester->name) . '&background=4f46e5&color=fff' }}"
                                class="rounded-circle border border-4 border-dark shadow-lg mb-3" width="96"
                                height="96">
                            <h3 class="fw-black text-white mb-1">{{ $skillSwapRequest->requester->name }}</h3>
                            <p class="small text-secondary fw-bold text-uppercase tracking-widest">
                                {{ $skillSwapRequest->requester->university }} •
                                {{ $skillSwapRequest->requester->major }}</p>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="p-4 rounded-4 position-relative border border-primary border-opacity-25"
                                    style="background-color: rgba(79, 70, 229, 0.05);">
                                    <span
                                        class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-primary px-3 py-2 fw-black text-uppercase tracking-widest ms-5 mt-1"
                                        style="font-size: 8px;">Needs Talent</span>
                                    <div class="d-flex align-items-center gap-3 mt-2">
                                        <div
                                            class="bg-dark bg-opacity-50 rounded-3 p-3 shadow-sm border border-white border-opacity-10">
                                            <svg style="width: 24px;" class="text-primary" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="fw-black text-white mb-0">
                                            {{ $skillSwapRequest->requestedSkill->name }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-4 rounded-4 position-relative border border-emerald border-opacity-25"
                                    style="background-color: rgba(16, 185, 129, 0.05);">
                                    <span
                                        class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-emerald px-3 py-2 fw-black text-uppercase tracking-widest ms-5 mt-1"
                                        style="font-size: 8px;">Offers Talent</span>
                                    <div class="d-flex align-items-center gap-3 mt-2">
                                        <div
                                            class="bg-dark bg-opacity-50 rounded-3 p-3 shadow-sm border border-white border-opacity-10">
                                            <svg style="width: 24px;" class="text-emerald" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                                </path>
                                            </svg>
                                        </div>
                                        <h5 class="fw-black text-white mb-0">{{ $skillSwapRequest->offeredSkill->name }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label
                                class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-3">The
                                Pitch</label>
                            <div
                                class="p-4 p-md-5 bg-dark bg-opacity-25 border border-white border-opacity-10 rounded-5 text-secondary fs-5 italic shadow-inner lh-lg">
                                "{{ $skillSwapRequest->description }}"
                            </div>
                        </div>

                        <div
                            class="bg-dark rounded-5 p-4 p-md-5 d-flex flex-column flex-md-row align-items-center justify-content-between shadow-lg border border-white border-opacity-10">
                            <div class="d-flex align-items-center gap-4 mb-4 mb-md-0">
                                <div class="p-3 bg-white bg-opacity-5 rounded-4 border border-white border-opacity-10">
                                    <svg style="width: 32px;" class="text-emerald" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6.267 3.455a.75.75 0 01.47.925L5.235 8h4.08l.334-1.334a.75.75 0 011.455.364l-.425 1.701h4.08l.334-1.334a.75.75 0 111.455.364l-.425 1.701h1.161a.75.75 0 010 1.5H15.01l-.5 2h1.16a.75.75 0 010 1.5h-1.661l-.334 1.334a.75.75 0 11-1.455-.364l.425-1.701h-4.08l-.334 1.334a.75.75 0 11-1.455-.364l.425-1.701H5.49l-.5 2h-1.16a.75.75 0 010-1.5h1.66l.5-2H3.83a.75.75 0 110-1.5h1.161l.425-1.701H4.08a.75.75 0 01-.737-.864l.425-1.701a.75.75 0 01.925-.47zm2.469 6.045h4.08l.5-2h-4.08l-.5 2z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="small fw-black text-secondary text-uppercase mb-1">Contribution Bounty
                                    </h6>
                                    <div class="h3 fw-black text-emerald mb-0">{{ $skillSwapRequest->points_offered }}
                                        CP <span class="small fw-normal text-secondary opacity-50 ms-2"
                                            style="font-size: 0.6em;">awarded on completion</span></div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                @if(auth()->id() === $skillSwapRequest->requester_id)
                                    @if($skillSwapRequest->status === 'accepted')
                                        <form action="{{ route('skill-swaps.complete', $skillSwapRequest) }}" method="POST">
                                            @csrf
                                            <button
                                                class="btn btn-emerald rounded-pill px-4 py-3 fw-black small shadow-lg">Completed
                                                & Award Points</button>
                                        </form>
                                    @else
                                        <form action="{{ route('skill-swaps.destroy', $skillSwapRequest) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="btn btn-outline-danger rounded-pill px-4 py-3 fw-black small border-opacity-25 shadow-sm">Cancel
                                                Request</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-emerald:hover {
            color: #10b981 !important;
        }

        .bg-emerald {
            background-color: #10b981;
        }

        .text-emerald {
            color: #10b981;
        }

        .btn-emerald {
            background-color: #10b981;
            border-color: #10b981;
            color: #fff;
            transition: all 0.3s;
        }

        .btn-emerald:hover {
            background-color: #059669;
            border-color: #059669;
            color: #fff;
            transform: translateY(-3px);
        }

        .rounded-5 {
            border-radius: 2rem !important;
        }
    </style>
</x-app-layout>