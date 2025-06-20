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

<li class="{!! (Request::is('students*') ? 'active' : '' ) !!}">
    <a href="{{ route('students.index') }}">
        <span class="mm-text ">Lerner</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('occupations*') ? 'active' : '' ) !!}">
    <a href="{{ route('occupations.index') }}">
        <span class="mm-text ">Occupations</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('assessmentVenues*') ? 'active' : '' ) !!}">
    <a href="{{ route('assessmentVenues.index') }}">
        <span class="mm-text ">Assessment Venues</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('assessmentCenters*') ? 'active' : '' ) !!}">
    <a href="{{ route('assessmentCenters.index') }}">
        <span class="mm-text ">Assessment Centers</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('programs*') ? 'active' : '' ) !!}">
    <a href="{{ route('programs.index') }}">
        <span class="mm-text ">Programs</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('upazilas*') ? 'active' : '' ) !!}">
    <a href="{{ route('upazilas.index') }}">
        <span class="mm-text ">Upazilas</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('competences*') ? 'active' : '' ) !!}">
    <a href="{{ route('competences.index') }}">
        <span class="mm-text ">Competences</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

