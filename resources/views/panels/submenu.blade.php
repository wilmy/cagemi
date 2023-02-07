{{-- For submenu --}}
<ul class="menu-content">
    @if (isset($menu))
    @foreach ($menu as $submenu)

    @if(Auth::user()->can($submenu->ver))
    <li @if ($submenu->slug === Route::currentRouteName()) class="active" @endif>
        <a onclick="backblock()" href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}"
            class="d-flex align-items-center"
            target="{{ isset($submenu->newTab) && $submenu->newTab === true ? '_blank' : '_self' }}">
            @if (isset($submenu->icono))
            <i data-feather="{{ $submenu->icono }}"></i>
            @endif
            <span class="menu-item text-truncate">{{ __($submenu->nombre) }}</span>
        </a>

        @php
        $listMenuHijos_hijos = Helper::listMenu($submenu->cod_pantalla);
        @endphp
        @if (count($listMenuHijos_hijos) > 0)
        @include('panels/submenu', ['menu' => $listMenuHijos_hijos])
        @endif
    </li>
    @endif
    @endforeach
    @endif
</ul>