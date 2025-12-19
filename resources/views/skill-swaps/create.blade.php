@section('title', 'Post Swap Request')
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 fw-black text-white mb-0">
            {{ __('Post Talent Swap') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="p-1" style="background: linear-gradient(to right, #10b981, #3b82f6);"></div>
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('skill-swaps.store') }}">
                            @csrf

                            <div class="row g-4 mb-4">
                                <!-- Offered Skill -->
                                <div class="col-md-6">
                                    <label for="offered_skill_id"
                                        class="form-label small fw-black text-uppercase text-emerald tracking-widest mb-2">Skill
                                        I Can Provide</label>
                                    <select id="offered_skill_id" name="offered_skill_id" required
                                        class="form-select form-select-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-emerald transition shadow-inner"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                        <option value="" disabled selected>Select a skill...</option>
                                        @foreach($skills as $category => $skillList)
                                            <optgroup label="{{ $category }}">
                                                @foreach($skillList as $skill)
                                                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('offered_skill_id')" class="mt-2" />
                                </div>

                                <!-- Requested Skill -->
                                <div class="col-md-6">
                                    <label for="requested_skill_id"
                                        class="form-label small fw-black text-uppercase text-primary tracking-widest mb-2">Skill
                                        I Need Help With</label>
                                    <select id="requested_skill_id" name="requested_skill_id" required
                                        class="form-select form-select-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary transition shadow-inner"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                        <option value="" disabled selected>Select a skill...</option>
                                        @foreach($skills as $category => $skillList)
                                            <optgroup label="{{ $category }}">
                                                @foreach($skillList as $skill)
                                                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('requested_skill_id')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description"
                                    class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Collaboration
                                    Details</label>
                                <textarea id="description" name="description" rows="5" required
                                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white placeholder-secondary focus:ring-2 focus:ring-primary transition shadow-inner"
                                    placeholder="Example: I'll design your landing page in Figma if you can help me with my Laravel backend setup..."
                                    style="background-color: rgba(255,255,255,0.05) !important;">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Points Payout -->
                            <div class="mb-5" style="max-width: 300px;">
                                <label for="points_offered"
                                    class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Reputation
                                    Points Bounty</label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-dark border-0 text-primary fw-black rounded-start-4"
                                        style="background-color: rgba(255,255,255,0.05) !important;">CP</span>
                                    <input type="number" id="points_offered" name="points_offered" value="20" min="10"
                                        max="100" required
                                        class="form-control form-control-lg bg-dark border-0 text-white focus:ring-2 focus:ring-primary transition shadow-inner fw-black fs-4 rounded-end-4"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                </div>
                                <p class="small text-secondary mt-2 mb-0 fw-bold opacity-50"
                                    style="font-size: 10px; text-transform: uppercase;">Higher bounty attracts faster
                                    talent matching</p>
                                <x-input-error :messages="$errors->get('points_offered')" class="mt-2" />
                            </div>

                            <div class="pt-4 border-top border-white border-opacity-10 mt-5">
                                <button type="submit"
                                    class="btn btn-emerald w-100 rounded-4 py-3 fw-black fs-5 shadow-lg transition">
                                    Broadcast Talent Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-emerald {
            color: #10b981;
        }

        .focus-ring-emerald:focus {
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
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
    </style>
</x-app-layout>