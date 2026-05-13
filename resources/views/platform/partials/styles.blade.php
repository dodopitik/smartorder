<style>
    .platform-shell {
        display: grid;
        gap: 1.5rem;
    }

    .platform-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 1.75rem;
        background:
            radial-gradient(circle at top right, rgba(249, 115, 22, 0.1), transparent 40%),
            radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.06), transparent 40%),
            var(--admin-surface);
        border: 1px solid var(--admin-border);
        box-shadow: var(--admin-shadow);
        backdrop-filter: blur(14px);
    }

    .platform-hero::after {
        content: "";
        position: absolute;
        inset: auto -60px -60px auto;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.15), transparent 70%);
        pointer-events: none;
    }

    .platform-kpis {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }

    .platform-kpi {
        border-radius: 24px;
        border: 1px solid var(--admin-border);
        background: var(--admin-surface);
        backdrop-filter: blur(14px);
        padding: 1.25rem;
        box-shadow: var(--admin-shadow);
    }

    .platform-kpi .label {
        color: var(--admin-muted);
        font-size: .9rem;
        margin-bottom: .5rem;
    }

    .platform-kpi .value {
        font-size: clamp(1.6rem, 2vw, 2.4rem);
        font-weight: 800;
        color: var(--admin-text);
        margin: 0;
    }

    .platform-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
    }

    .platform-card {
        grid-column: span 12;
        border-radius: 24px;
        border: 1px solid var(--admin-border);
        background: var(--admin-surface);
        backdrop-filter: blur(14px);
        box-shadow: var(--admin-shadow);
    }

    .platform-card .card-head {
        padding: 1.25rem 1.25rem 0;
    }

    .platform-card .card-head h4 {
        margin: 0;
        color: var(--admin-text);
    }

    .platform-card .card-body {
        padding: 1.25rem;
    }

    .platform-table {
        width: 100%;
    }

    .platform-table th {
        color: var(--admin-muted);
        font-weight: 700;
        white-space: nowrap;
    }

    .platform-table td,
    .platform-table th {
        padding: .9rem .75rem;
        vertical-align: middle;
    }

    .platform-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .38rem .7rem;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
    }

    .platform-badge.active {
        background: rgba(22, 163, 74, 0.12);
        color: #166534;
    }

    .platform-badge.inactive {
        background: rgba(239, 68, 68, 0.12);
        color: #991b1b;
    }

    .platform-stat-list {
        display: grid;
        gap: .85rem;
    }

    .platform-stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border: 1px solid var(--admin-border);
        border-radius: 18px;
        padding: .95rem 1rem;
    }

    .platform-form-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
    }

    .platform-form-grid .span-6,
    .platform-form-grid .span-12 {
        grid-column: span 12;
    }

    .platform-empty {
        border: 1px dashed var(--admin-border);
        border-radius: 18px;
        padding: 1rem;
        color: var(--admin-muted);
        background: var(--admin-surface);
    }

    @media (min-width: 768px) {
        .platform-card.span-7 {
            grid-column: span 7;
        }

        .platform-card.span-5 {
            grid-column: span 5;
        }

        .platform-form-grid .span-6 {
            grid-column: span 6;
        }
    }
</style>
