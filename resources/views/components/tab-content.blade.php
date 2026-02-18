@props(['tab'])

<div x-show="tab === '{{ $tab }}'" x-cloak x-transition>
    {{ $slot }}
</div>
