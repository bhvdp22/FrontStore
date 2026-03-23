@props([
    'title',
    'value',
    'subtitle' => null,
    'valueColor' => '#0f1111',
    'subtitleColor' => '#565959',
    'background' => 'white',
    'borderColor' => '#d5d9d9'
])

<div style="background: {{ $background }}; border: 1px solid {{ $borderColor }}; border-radius: 8px; padding: 20px; text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: center;">
    <div style="color: #565959; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">{{ $title }}</div>
    <div style="font-size: 26px; font-weight: 700; color: {{ $valueColor }};">{{ $value }}</div>
    @if($subtitle)
        <div style="color: {{ $subtitleColor }}; font-size: 11px; margin-top: 4px;">{!! $subtitle !!}</div>
    @endif
    {{ $slot ?? '' }}
</div>
