@section('title', 'Skill Marketplace')
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h2 class="h3 fw-black text-white mb-0">
                {{ __('Skill Marketplace') }}
            </h2>
            <a href="{{ route('skill-swaps.create') }}" class="btn btn-emerald rounded-pill px-4 fw-black">
                + Post Swap Request
            </a>
        </div>
    </x-slot>

    <div class="container py-4">

        <!-- Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 text-white position-relative overflow-hidden"
                    style="background: linear-gradient(135deg, #10b981, #059669);">
                    <div class="d-flex justify-content-between align-items-center position-relative z-1">
                        <div>
                            <h6 class="text-uppercase small fw-black opacity-75">Global Payout</h6>
                            <div class="display-5 fw-black my-2">12,450 CP</div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center"
                            style="width: 64px; height: 64px;">
                            <svg style="width: 32px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 text-white position-relative overflow-hidden"
                    style="background: linear-gradient(135deg, #4f46e5, #4338ca);">
                    <div class="d-flex justify-content-between align-items-center position-relative z-1">
                        <div>
                            <h6 class="text-uppercase small fw-black opacity-75">Active Swaps</h6>
                            <div class="display-5 fw-black my-2">86 Requests</div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center"
                            style="width: 64px; height: 64px;">
                            <svg style="width: 32px;" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Swaps List -->
        <div class="d-flex flex-column gap-4">
            @forelse($swaps as $swap)
                <div class="card border-0 shadow-sm overflow-hidden feature-card-hover">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center g-4">
                            <!-- Profile -->
                            <div class="col-md-2 text-center border-end border-white border-opacity-10">
                                <img src="{{ $swap->requester->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($swap->requester->name) . '&background=10b981&color=fff' }}"
                                    class="rounded-circle border border-2 border-dark mb-2 shadow-sm" width="64"
                                    height="64">
                                <h6 class="fw-bold text-white mb-0 text-truncate">{{ $swap->requester->name }}</h6>
                                <span class="small text-secondary text-uppercase fw-black opacity-50"
                                    style="font-size: 9px; letter-spacing: 0.1em;">{{ $swap->requester->major }}</span>
                            </div>

                            <!-- Content -->
                            <div class="col-md-7 ps-md-5">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                    <div
                                        class="bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill small fw-black text-uppercase border border-primary border-opacity-25">
                                        <span class="opacity-50 me-1">Needs</span> {{ $swap->requestedSkill->name }}
                                    </div>
                                    <svg style="width: 16px;" class="text-secondary opacity-50 mx-2" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <div
                                        class="bg-emerald bg-opacity-10 text-emerald px-3 py-1 rounded-pill small fw-black text-uppercase border border-emerald border-opacity-25">
                                        <span class="opacity-50 me-1">Offers</span> {{ $swap->offeredSkill->name }}
                                    </div>
                                </div>
                                <p class="text-secondary small mb-0 lh-lg line-clamp-2">
                                    {{ $swap->description }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="col-md-3 text-center text-md-end">
                                <div class="h3 fw-black text-emerald mb-3">+{{ $swap->points_offered }} CP</div>
                                <a href="{{ route('skill-swaps.show', $swap) }}"
                                    class="btn btn-outline-light rounded-pill px-4 py-2 small fw-black text-uppercase shadow-sm">
                                    View Opportunity
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="card border-0 shadow-sm p-5 text-center bg-transparent border-2 border-dashed border-white border-opacity-10">
                    <p class="text-secondary mb-3 fw-bold">No skill swap requests yet.</p>
                    <a href="{{ route('skill-swaps.create') }}"
                        class="btn btn-primary rounded-pill px-4 py-2 small fw-black">Post the first one!</a>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $swaps->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .feature-card-hover {
            transition: all 0.3s;
        }

        .feature-card-hover:hover {
            background-color: rgba(255, 255, 255, 0.03) !important;
            transform: translateY(-5px);
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
        }

        .btn-emerald:hover {
            background-color: #059669;
            border-color: #059669;
            color: #fff;
        }
    </style>
</x-app-layout>