@if(!auth()->user()->isSuperAdmin())
    <div class="row">
        <div class="col-lg-12">
            <div class="border-head text-center">
                @if(auth()->user()->isProUnlimitedClients())
                    <img src="{{ asset('img/icons/Pro-Unlimited-Color.png') }}" src="" height="100" width="200" style="margin-top: 30px; margin-bottom: 20px;">
                @else
                    <img src="{{ asset('img/icons/UCC-Logo-dark.png') }}" src="" height="120" width="200">
                @endif
            </div>
        </div>
    </div>
@endif
