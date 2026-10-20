import './bootstrap';

document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-password-toggle]');
    if (!button) return;

    const control = button.closest('.password-control');
    const input = control?.querySelector('.password-input');
    if (!input) return;

    const isVisible = input.type === 'text';
    input.type = isVisible ? 'password' : 'text';
    button.setAttribute('aria-pressed', String(!isVisible));
    button.setAttribute('aria-label', `${isVisible ? 'Tampilkan' : 'Sembunyikan'} ${input.labels?.[0]?.textContent?.trim().toLowerCase() || 'kata sandi'}`);
    button.querySelector('.eye-icon')?.classList.toggle('hidden', !isVisible);
    button.querySelector('.eye-off-icon')?.classList.toggle('hidden', isVisible);
});

document.querySelectorAll('[data-booking-decision]').forEach((container) => {
    const select = container.querySelector('[data-decision-select]');
    const message = container.querySelector('[data-decision-message]');
    const reason = container.querySelector('[data-rejection-reason]');
    const input = container.querySelector('[data-rejection-input]');

    const syncDecision = () => {
        const decision = select.value;
        reason.classList.toggle('hidden', decision !== 'reject');
        input.required = decision === 'reject';

        if (decision === 'approve') {
            message.textContent = 'Booking akan disetujui setelah Anda menyimpan perubahan.';
            message.className = 'mt-2 text-sm text-emerald-700';
        } else if (decision === 'reject') {
            message.textContent = 'Booking akan ditolak. Masukkan alasan penolakan sebelum menyimpan.';
            message.className = 'mt-2 text-sm text-red-700';
        } else {
            message.textContent = 'Pilih keputusan untuk booking ini.';
            message.className = 'mt-2 text-sm text-slate-600';
        }
    };

    select.addEventListener('change', syncDecision);
    syncDecision();
});
