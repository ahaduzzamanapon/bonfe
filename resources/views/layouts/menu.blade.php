<li class="{!! (Request::is('districts*') ? 'active' : '' ) !!}">
    <a href="{{ route('districts.index') }}">
        <span class="mm-text ">Districts</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('chairmen*') ? 'active' : '' ) !!}">
    <a href="{{ route('chairmen.index') }}">
        <span class="mm-text ">Chairmen</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

