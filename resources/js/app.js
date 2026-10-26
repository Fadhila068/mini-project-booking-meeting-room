import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------
    // 1. Mobile Menu Drawer Navigation
    // -------------------------------------------------------------
    const mobileDrawer = document.getElementById('mobile-menu-drawer');
    const mobileOpenBtn = document.getElementById('mobile-menu-open-btn');
    const mobileCloseBtn = document.getElementById('mobile-menu-close-btn');
    const mobileBackdrop = document.getElementById('mobile-menu-backdrop');

    const openDrawer = () => {
        if (!mobileDrawer) return;
        mobileDrawer.classList.remove('hidden');
        mobileOpenBtn?.setAttribute('aria-expanded', 'true');
        document.body.classList.add('overflow-hidden');
    };

    const closeDrawer = () => {
        if (!mobileDrawer) return;
        mobileDrawer.classList.add('hidden');
        mobileOpenBtn?.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');
    };

    mobileOpenBtn?.addEventListener('click', openDrawer);
    mobileCloseBtn?.addEventListener('click', closeDrawer);
    mobileBackdrop?.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileDrawer && !mobileDrawer.classList.contains('hidden')) {
            closeDrawer();
        }
    });

    // -------------------------------------------------------------
    // 2. Auto-dismiss Toast Notification
    // -------------------------------------------------------------
    const toast = document.getElementById('toast-success');
    const toastCloseBtn = document.getElementById('toast-close-btn');

    if (toast) {
        const dismissToast = () => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 300);
        };

        toastCloseBtn?.addEventListener('click', dismissToast);

        // Auto dismiss setelah 4 detik
        setTimeout(() => {
            if (document.body.contains(toast)) {
                dismissToast();
            }
        }, 4000);
    }

    // -------------------------------------------------------------
    // 3. Password Visibility Toggle
    // -------------------------------------------------------------
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

    // -------------------------------------------------------------
    // 4. Form Booking Helpers: Auto +1 Jam, Quick Duration & Warnings
    // -------------------------------------------------------------
    const bookingForm = document.querySelector('[data-booking-form]');
    if (bookingForm) {
        const roomSelect = bookingForm.querySelector('[data-room-select]');
        const startInput = bookingForm.querySelector('[data-start-input]');
        const endInput = bookingForm.querySelector('[data-end-input]');
        const participantInput = bookingForm.querySelector('[data-participant-input]');
        const capacityWarning = bookingForm.querySelector('[data-capacity-warning]');
        const hoursWarning = bookingForm.querySelector('[data-hours-warning]');
        const durationBtns = bookingForm.querySelectorAll('[data-duration-btn]');

        // Format helper: Date -> YYYY-MM-DDTHH:mm
        const toLocalIso = (date) => {
            const pad = (n) => String(n).padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        };

        // Otomatis isi jam selesai +1 jam jika jam mulai diubah
        startInput?.addEventListener('change', () => {
            if (!startInput.value) return;
            const startDate = new Date(startInput.value);
            if (isNaN(startDate.getTime())) return;

            if (!endInput.value || new Date(endInput.value) <= startDate) {
                const endDate = new Date(startDate.getTime() + 60 * 60 * 1000);
                endInput.value = toLocalIso(endDate);
            }
            checkWarnings();
        });

        // Tombol durasi cepat (+30 min, +1 jam, +2 jam)
        durationBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const minutes = parseInt(btn.dataset.durationMinutes || '60', 10);
                let baseDate = startInput.value ? new Date(startInput.value) : new Date();

                // Jika start input kosong, isi dengan waktu sekarang (dibulatkan ke jam berikutnya)
                if (!startInput.value) {
                    baseDate.setMinutes(0, 0, 0);
                    baseDate.setHours(baseDate.getHours() + 1);
                    startInput.value = toLocalIso(baseDate);
                }

                const endDate = new Date(baseDate.getTime() + minutes * 60 * 1000);
                endInput.value = toLocalIso(endDate);
                checkWarnings();

                // Highlight tombol aktif
                durationBtns.forEach((b) => b.classList.remove('pill-tab-active', 'bg-brand-600', 'text-white'));
                btn.classList.add('pill-tab-active', 'bg-brand-600', 'text-white');
            });
        });

        // Cek peringatan kapasitas & jam operasional
        const checkWarnings = () => {
            const selectedOption = roomSelect?.options[roomSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                if (capacityWarning) capacityWarning.classList.add('hidden');
                if (hoursWarning) hoursWarning.classList.add('hidden');
                return;
            }

            const capacity = parseInt(selectedOption.dataset.capacity || '0', 10);
            const opensAt = selectedOption.dataset.opens || '08:00';
            const closesAt = selectedOption.dataset.closes || '18:00';
            const participants = parseInt(participantInput?.value || '0', 10);

            // Validasi kapasitas
            if (capacityWarning) {
                if (capacity > 0 && participants > capacity) {
                    capacityWarning.textContent = `Peringatan: Jumlah peserta (${participants}) melebihi kapasitas ruangan ini (${capacity} orang).`;
                    capacityWarning.classList.remove('hidden');
                } else {
                    capacityWarning.classList.add('hidden');
                }
            }

            // Validasi jam operasional
            if (hoursWarning && startInput.value && endInput.value) {
                const startTime = startInput.value.split('T')[1]?.substring(0, 5) || '';
                const endTime = endInput.value.split('T')[1]?.substring(0, 5) || '';

                if (startTime < opensAt || endTime > closesAt) {
                    hoursWarning.textContent = `Peringatan: Jam yang dipilih (${startTime} - ${endTime}) di luar jam operasional ruangan (${opensAt} - ${closesAt}).`;
                    hoursWarning.classList.remove('hidden');
                } else {
                    hoursWarning.classList.add('hidden');
                }
            }
        };

        roomSelect?.addEventListener('change', checkWarnings);
        participantInput?.addEventListener('input', checkWarnings);
        endInput?.addEventListener('change', checkWarnings);
        checkWarnings();
    }

    // -------------------------------------------------------------
    // 5. Admin Booking Decision: 3 Pilihan Kartu & Toggle Alasan Penolakan
    // -------------------------------------------------------------
    document.querySelectorAll('[data-booking-decision]').forEach((container) => {
        const select = container.querySelector('[data-decision-select]');
        const message = container.querySelector('[data-decision-message]');
        const reasonContainer = container.querySelector('[data-rejection-reason]');
        const reasonInput = container.querySelector('[data-rejection-input]');
        const decisionCards = container.querySelectorAll('[data-decision-card]');

        const syncUI = (decision) => {
            // Update kartu visual
            decisionCards.forEach((card) => {
                const cardValue = card.dataset.decisionValue;
                const isSelected = cardValue === decision;
                card.setAttribute('aria-checked', String(isSelected));

                card.classList.toggle('ring-2', isSelected);
                card.classList.toggle('ring-brand-600', isSelected && decision !== 'reject');
                card.classList.toggle('ring-rose-500', isSelected && decision === 'reject');
                card.classList.toggle('border-brand-600', isSelected && decision !== 'reject');
                card.classList.toggle('border-rose-500', isSelected && decision === 'reject');
                card.classList.toggle('bg-brand-50/50', isSelected && decision === 'approve');
                card.classList.toggle('bg-rose-50/50', isSelected && decision === 'reject');
                card.classList.toggle('bg-ink-100/50', isSelected && decision === '');
            });

            // Update pesan dan input alasan penolakan
            const isReject = decision === 'reject';
            if (reasonContainer) {
                reasonContainer.classList.toggle('hidden', !isReject);
            }
            if (reasonInput) {
                reasonInput.required = isReject;
                if (isReject) {
                    reasonInput.focus();
                }
            }

            if (message) {
                if (decision === 'approve') {
                    message.textContent = 'Booking akan disetujui setelah Anda menyimpan perubahan.';
                    message.className = 'mt-3 text-sm font-medium text-brand-700 flex items-center gap-1.5';
                } else if (decision === 'reject') {
                    message.textContent = 'Booking akan ditolak. Masukkan alasan penolakan sebelum menyimpan.';
                    message.className = 'mt-3 text-sm font-medium text-rose-700 flex items-center gap-1.5';
                } else {
                    message.textContent = 'Pilih keputusan untuk booking ini.';
                    message.className = 'mt-3 text-sm text-ink-500';
                }
            }
        };

        // Sinkronisasi saat kartu diklik
        decisionCards.forEach((card) => {
            card.addEventListener('click', () => {
                if (card.getAttribute('aria-disabled') === 'true') return;
                const val = card.dataset.decisionValue;
                if (select) {
                    select.value = val;
                }
                syncUI(val);
            });
        });

        // Sinkronisasi jika select berubah
        if (select) {
            select.addEventListener('change', () => syncUI(select.value));
            syncUI(select.value);
        }
    });
});
