<form action="{{ route('search') }}" method="GET" class="vs-site-search {{ $class ?? '' }}" role="search">
    <label class="visually-hidden" for="vs-site-search">{{ __('Search') }}</label>
    <div class="input-group input-group-sm">
        <input type="search" name="s" id="vs-site-search" class="form-control" placeholder="{{ __('Search…') }}" value="{{ $query ?? request('s') }}">
        <button class="btn btn-outline-secondary" type="submit" aria-label="{{ __('Search') }}">
            <i class="bi bi-search"></i>
        </button>
    </div>
</form>
