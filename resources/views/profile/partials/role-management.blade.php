<section>
    <header>
        <h2 class="text-lg font-medium text-black ">
            {{ __('Role Management') }}
        </h2>
        <p class="mt-1 text-sm text-black ">
            {{ __("Manage your account role. Current Role:") }} 
            <span class="font-bold text-brand-500 uppercase">
                @if(Auth::user()->hasRole('admin'))
                    Admin
                @elseif(Auth::user()->hasRole('organizer'))
                    Organizer
                @else
                    User
                @endif
            </span>
        </p>
    </header>

    @if(session('error'))
        <div class="mt-4 p-4 bg-red-500/10 border-2 border-red-500/30 text-red-500 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="mt-4 p-4 bg-emerald-500/10 border-2 border-emerald-500/30 text-emerald-500 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @php
        $pendingRequest = \App\Models\OrganizerRequest::where('user_id', Auth::user())->where('status', 'pending')->first();
    @endphp

    @if(!Auth::user()->hasRole('admin'))
        <div class="mt-6">
            @if($pendingRequest)
                <div class="p-4 bg-amber-500/10 border-2 border-amber-500/30 text-amber-500 rounded-lg">
                    You have a pending <strong>{{ $pendingRequest->type }}</strong> request awaiting admin approval.
                </div>
            @else
                <form action="{{ route('user.organizer.request') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="reason"="__('Reason / Motivation (Required)')" />
                        <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full border-gray-100    focus focus rounded-md shadow-sm" required placeholder="Explain why you want to change your role..."></textarea>
                        <x-input-error class="mt-2"="$errors->get('reason')" />
                    </div>

                    @if(Auth::user()->hasRole('organizer'))
                        <div class="text-sm text-black  mb-4">
                            Note: You cannot downgrade if you have any ongoing events. Once approved, all your past events will be deleted.
                        </div>
                        <x-primary-button onclick="return confirm('Are you sure you want to request a downgrade? This will delete all your past events once approved.')">
                            {{ __('Request Downgrade to User') }}
                        </x-primary-button>
                    @else
                        <x-primary-button>
                            {{ __('Request Organizer Role') }}
                        </x-primary-button>
                    @endif
                </form>
            @endif
        </div>
    @endif
</section>
