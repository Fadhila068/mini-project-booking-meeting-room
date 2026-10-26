@props(['name', 'label', 'id' => null, 'required' => true])
@php($inputId = $id ?: $name)

<div class="auth-field-group">
    <div class="flex items-center justify-between mb-1.5">
        <label for="{{ $inputId }}" class="auth-label !mb-0">{{ $label }}</label>
    </div>
    <div class="password-control relative">
        <input 
            id="{{ $inputId }}" 
            class="field password-input @error($name) border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror" 
            name="{{ $name }}" 
            type="password" 
            autocomplete="{{ $name === 'password_confirmation' ? 'new-password' : ($name === 'password' ? 'current-password' : 'off') }}" 
            {{ $required ? 'required' : '' }}
        >
        <button 
            type="button" 
            class="password-toggle" 
            aria-label="Tampilkan {{ strtolower($label) }}" 
            aria-pressed="false" 
            data-password-toggle
        >
            <svg class="eye-icon w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg class="eye-off-icon hidden w-5 h-5 text-brand-600" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
            </svg>
        </button>
    </div>
    @error($name)
        <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
