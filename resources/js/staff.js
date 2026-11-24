const formatNumber = new Intl.NumberFormat('id-ID', {
    maximumFractionDigits: 0,
});

const renderCounter = (element, value) => {
    const formattedValue = formatNumber.format(Math.round(value));
    const prefix = element.dataset.prefix || '';

    element.textContent = `${prefix}${formattedValue}`;
};

const animateCounter = (element) => {
    const target = Number(element.dataset.counter);

    if (!Number.isFinite(target)) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        renderCounter(element, target);
        return;
    }

    const duration = 850;
    const startedAt = performance.now();

    const tick = (now) => {
        const progress = Math.min((now - startedAt) / duration, 1);
        const easedProgress = 1 - (1 - progress) ** 3;

        renderCounter(element, target * easedProgress);

        if (progress < 1) {
            requestAnimationFrame(tick);
        }
    };

    requestAnimationFrame(tick);
};

const filterTableRows = (input) => {
    const targetSelector = input.dataset.tableTarget;
    const tables = targetSelector
        ? [document.querySelector(targetSelector)].filter(Boolean)
        : [...document.querySelectorAll('[data-searchable-table]')];
    const searchTerm = input.value.trim().toLocaleLowerCase('id-ID');

    tables.forEach((table) => {
        const rows = [...table.querySelectorAll('[data-search-row]')];
        let visibleRows = 0;

        rows.forEach((row) => {
            const matches = row.textContent.toLocaleLowerCase('id-ID').includes(searchTerm);

            row.hidden = !matches;
            visibleRows += Number(matches);
        });

        const status = input.closest('.panel-heading')?.querySelector('[data-search-status]');

        if (status) {
            status.textContent = searchTerm
                ? `${visibleRows} hasil ditemukan`
                : status.dataset.originalText || status.textContent;
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-counter]').forEach(animateCounter);

    const liveClock = document.querySelector('[data-live-clock]');

    if (liveClock) {
        const updateClock = () => {
            const now = new Date();
            const formattedDate = new Intl.DateTimeFormat('id-ID', {
                weekday: 'short',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                timeZone: 'Asia/Jakarta',
            }).format(now);
            const formattedTime = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hourCycle: 'h23',
                timeZone: 'Asia/Jakarta',
            }).format(now);

            liveClock.dateTime = now.toISOString();
            liveClock.textContent = `${formattedDate} · ${formattedTime} WIB`;
        };

        updateClock();
        window.setInterval(updateClock, 60_000);
    }

    document.querySelectorAll('[data-table-search]').forEach((input) => {
        const status = input.closest('.panel-heading')?.querySelector('[data-search-status]');

        if (status) {
            status.dataset.originalText = status.textContent;
        }

        input.addEventListener('input', () => filterTableRows(input));
    });

    document.querySelectorAll('[data-service-search]').forEach((input) => {
        const cards = [...document.querySelectorAll('[data-service-card]')];
        const emptyState = document.querySelector('[data-service-empty]');
        const count = document.querySelector('[data-service-count]');

        input.addEventListener('input', () => {
            const searchTerm = input.value.trim().toLocaleLowerCase('id-ID');
            let visibleCards = 0;

            cards.forEach((card) => {
                const matches = card.textContent.toLocaleLowerCase('id-ID').includes(searchTerm);

                card.hidden = !matches;
                visibleCards += Number(matches);
            });

            if (count) {
                count.textContent = String(visibleCards);
            }

            if (emptyState) {
                emptyState.hidden = visibleCards > 0;
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (!(event.ctrlKey || event.metaKey) || event.key.toLowerCase() !== 'k') {
            return;
        }

        event.preventDefault();
        document.querySelector('[data-table-global-search]')?.focus();
    });

    document.querySelectorAll('[data-price-form]').forEach((form) => {
        const serviceInput = form.querySelector('[name="layanan_id"]');
        const weightInput = form.querySelector('[name="berat"]');
        const totalOutput = form.querySelector('[data-price-total]');

        if (!serviceInput || !weightInput || !totalOutput) {
            return;
        }

        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        });

        const updateTotal = () => {
            const price = Number(serviceInput.selectedOptions[0]?.dataset.harga || 0);
            const weight = Number(weightInput.value || 0);

            totalOutput.textContent = formatter.format(price * weight);
        };

        serviceInput.addEventListener('change', updateTotal);
        weightInput.addEventListener('input', updateTotal);
        updateTotal();
    });

    document.querySelectorAll('[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('[data-loading-button]');

            if (!button) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            const loadingLabel = button.dataset.loadingLabel || 'Menyimpan...';

            button.innerHTML = `<span class="loading-spinner" aria-hidden="true"></span> ${loadingLabel}`;
        });
    });

    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const passwordInput = button.closest('form')?.querySelector('[name="password"]');

            if (!passwordInput) {
                return;
            }

            const shouldReveal = passwordInput.type === 'password';

            passwordInput.type = shouldReveal ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(shouldReveal));
            button.setAttribute('aria-label', shouldReveal ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });

    document.querySelectorAll('[data-print-report]').forEach((button) => {
        button.addEventListener('click', () => {
            const printDate = document.querySelector('[data-print-date]');

            if (printDate) {
                const now = new Date();

                printDate.dateTime = now.toISOString();
                printDate.textContent = new Intl.DateTimeFormat('id-ID', {
                    dateStyle: 'long',
                    timeStyle: 'short',
                }).format(now);
            }

            window.print();
        });
    });
});
