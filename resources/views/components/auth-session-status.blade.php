@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success border-0 small']) }}>
        {{ $status }}
    </div>
@endif