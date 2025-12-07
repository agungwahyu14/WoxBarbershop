@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel' || trim($slot) === config('app.name'))
                <img src="/images/Logo.png" class="logo" alt="WOX Barbershop Logo">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
