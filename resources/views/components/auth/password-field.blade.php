@props(['name', 'label', 'id' => null])
@php($inputId = $id ?: $name)
<div class="auth-field-group">
    <label for="{{ $inputId }}" class="auth-label">{{ $label }}</label>
    <div class="password-control">
        <input id="{{ $inputId }}" class="field password-input" name="{{ $name }}" type="password" autocomplete="{{ $name === 'password_confirmation' ? 'new-password' : 'current-password' }}" required>
        <button type="button" class="password-toggle" aria-label="Tampilkan {{ strtolower($label) }}" aria-pressed="false" data-password-toggle>
            <svg class="eye-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="eye-off-icon hidden" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 4.2A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a18.4 18.4 0 0 1-3 3.9M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a10 10 0 0 0 3.4-.6"/></svg>
        </button>
    </div>
    @error($name)<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
