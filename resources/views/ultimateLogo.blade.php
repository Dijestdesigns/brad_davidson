@if(!auth()->user()->isSuperAdmin())
    <div class="row">
        <div class="col-lg-12">
            <div class="border-head text-center">
                <img src="{{ asset('img/icons/UCC-Logo-dark.png') }}" src="" height="120" width="200">
            </div>
        </div>
    </div>
@endif
