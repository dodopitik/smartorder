@php
    /** @var string $name */
    /** @var string|null $class */
    $class = $class ?? 'h-8 w-8';
@endphp
@switch($name)
    @case('bolt')
        <svg viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" aria-hidden="true">
            <path d="M13.8 2 4 13h6.7L9.9 22 20 10.6h-6.8L13.8 2Z" />
        </svg>
        @break

    @case('bell')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <path d="M6.8 10.4a5.2 5.2 0 0 1 10.4 0v4.2l1.8 2.2H5l1.8-2.2v-4.2Z" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" />
            <path d="M10 19a2.2 2.2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            <path d="M12 3v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        @break

    @case('bars')
        <svg viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" aria-hidden="true">
            <rect x="4" y="12" width="4" height="8" rx="1.2" />
            <rect x="10" y="8" width="4" height="12" rx="1.2" />
            <rect x="16" y="4" width="4" height="16" rx="1.2" />
        </svg>
        @break

    @case('shield')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <path d="M12 3 19 6v5.7c0 4.2-2.7 7.2-7 9.3-4.3-2.1-7-5.1-7-9.3V6l7-3Z" stroke="currentColor"
                stroke-width="2" stroke-linejoin="round" />
            <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        @break

    @case('qr')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <path
                d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm11 1h2v2h-2v-2Zm4 0h1v5h-5v-1h4v-4Zm-7-3h2v2h-2v-2Zm0 5h2v3h-2v-3Z"
                stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
        </svg>
        @break

    @case('cloud')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <path
                d="M8 18H6.8A4.8 4.8 0 1 1 8 8.6a6 6 0 0 1 11.2 2.8A3.5 3.5 0 0 1 18.5 18H16"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 13v8m0 0-3-3m3 3 3-3" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        @break

    @case('screen')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <rect x="4" y="5" width="16" height="11" rx="1.5" stroke="currentColor" stroke-width="2" />
            <path d="M9 20h6m-3-4v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        @break

    @case('chef')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <path
                d="M7.5 10.4A3 3 0 1 1 10 5.5a3.3 3.3 0 0 1 6.1 0 3 3 0 1 1 .4 5.9v7.1h-9v-8.1Z"
                stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
            <path d="M9 15h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        @break

    @case('whatsapp')
        <svg viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" aria-hidden="true">
            <path
                d="M12.04 2a9.86 9.86 0 0 0-8.46 14.94L2.2 22l5.18-1.36A9.94 9.94 0 1 0 12.04 2Zm0 1.8a8.12 8.12 0 1 1-3.96 15.2l-.36-.2-3.08.8.82-3-.24-.38a8.07 8.07 0 0 1 6.82-12.42Zm-3.5 3.9c-.18 0-.48.06-.74.34-.26.28-.98.96-.98 2.34 0 1.38 1 2.72 1.14 2.9.14.18 1.94 3.1 4.82 4.22 2.38.94 2.86.76 3.38.72.52-.04 1.66-.68 1.9-1.34.24-.66.24-1.22.16-1.34-.08-.12-.26-.2-.56-.34-.3-.14-1.66-.82-1.94-.92-.26-.1-.46-.14-.66.14-.18.28-.76.92-.92 1.1-.18.2-.34.22-.64.08-.3-.14-1.24-.46-2.36-1.46-.88-.78-1.46-1.74-1.64-2.04-.16-.28-.02-.44.12-.58.14-.14.3-.34.44-.5.14-.18.18-.3.28-.5.1-.2.04-.38-.02-.52-.08-.14-.66-1.58-.9-2.16-.24-.56-.48-.48-.66-.5h-.56Z" />
        </svg>
        @break

    @case('instagram')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <rect x="4" y="4" width="16" height="16" rx="4.5" stroke="currentColor" stroke-width="2" />
            <circle cx="12" cy="12" r="3.4" stroke="currentColor" stroke-width="2" />
            <circle cx="16.8" cy="7.2" r="1.1" fill="currentColor" />
        </svg>
        @break

    @case('mail')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <rect x="4" y="6" width="16" height="12" rx="2" stroke="currentColor" stroke-width="2" />
            <path d="m5 8 7 5 7-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        @break

    @case('check')
        <svg viewBox="0 0 24 24" fill="none" class="{{ $class }}" aria-hidden="true">
            <path d="m6 12 4 4 8-8" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        @break
@endswitch
