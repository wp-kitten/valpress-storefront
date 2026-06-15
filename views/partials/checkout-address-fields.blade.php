<div class="mb-3">
    <label class="form-label">{{ __('valpress-shop::messages.address_line1') }}</label>
    <input type="text" name="{{ $field }}[line1]" class="form-control @error($field . '.line1') is-invalid @enderror" value="{{ old($field . '.line1') }}" required>
    @error($field . '.line1')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">{{ __('valpress-shop::messages.address_line2') }}</label>
    <input type="text" name="{{ $field }}[line2]" class="form-control" value="{{ old($field . '.line2') }}">
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('valpress-shop::messages.city') }}</label>
        <input type="text" name="{{ $field }}[city]" class="form-control @error($field . '.city') is-invalid @enderror" value="{{ old($field . '.city') }}" required>
        @error($field . '.city')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('valpress-shop::messages.state') }}</label>
        <input type="text" name="{{ $field }}[state]" class="form-control" value="{{ old($field . '.state') }}">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('valpress-shop::messages.postal_code') }}</label>
        <input type="text" name="{{ $field }}[postal_code]" class="form-control @error($field . '.postal_code') is-invalid @enderror" value="{{ old($field . '.postal_code') }}" required>
        @error($field . '.postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('valpress-shop::messages.country') }}</label>
        <input type="text" name="{{ $field }}[country]" class="form-control @error($field . '.country') is-invalid @enderror" value="{{ old($field . '.country', 'US') }}" maxlength="2" required>
        @error($field . '.country')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
