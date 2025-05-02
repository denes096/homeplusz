{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-dropdown title="Települések" icon="la la-globe">
    <x-backpack::menu-dropdown-item title="Settlements" icon="la la-question" :link="backpack_url('settlement')" />
    <x-backpack::menu-dropdown-item title="Settlement parts" icon="la la-question" :link="backpack_url('settlement-part')" />
    <x-backpack::menu-dropdown-item title="Settlement groups" icon="la la-question" :link="backpack_url('settlement-group')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Ingatlanok" icon="la la-home">
    <x-backpack::menu-dropdown-header title="Ingatlanok" />
    <x-backpack::menu-dropdown-item title="Properties" icon="la la-question" :link="backpack_url('property')" />
    <x-backpack::menu-dropdown-header title="Mezők" />
    <x-backpack::menu-dropdown-item title="Property attribute categories" icon="la la-folder-open" :link="backpack_url('property-attribute-category')" />
    <x-backpack::menu-dropdown-item title="Property attributes" icon="la la-file-alt" :link="backpack_url('property-attribute')" />
    <x-backpack::menu-dropdown-item title="Property types" icon="la la-question" :link="backpack_url('property-type')" />
    <x-backpack::menu-dropdown-item title="Property subtypes" icon="la la-question" :link="backpack_url('property-subtype')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-item title="Labels" icon="la la-tags" :link="backpack_url('label')" />


<x-backpack::menu-dropdown title="Felhasználók" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-header title="Authentication" />
    <x-backpack::menu-dropdown-item title="Users" icon="la la-user" :link="backpack_url('user')" />
    <x-backpack::menu-dropdown-item title="Roles" icon="la la-group" :link="backpack_url('role')" />
    <x-backpack::menu-dropdown-item title="Permissions" icon="la la-key" :link="backpack_url('permission')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-item title="Settlement groups" icon="la la-question" :link="backpack_url('settlement-group')" />

<x-backpack::menu-item title="Service categories" icon="la la-question" :link="backpack_url('service-category')" />
<x-backpack::menu-item title="Services" icon="la la-question" :link="backpack_url('service')" />
<x-backpack::menu-item title="Information categories" icon="la la-question" :link="backpack_url('information-category')" />
<x-backpack::menu-item title="Information" icon="la la-question" :link="backpack_url('information')" />
<x-backpack::menu-item title="Static pages" icon="la la-question" :link="backpack_url('static-page')" />