{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> Irányítópult</a></li>

<x-backpack::menu-dropdown title="Települések" icon="la la-globe">
    <x-backpack::menu-dropdown-item title="Települések" icon="la la-question" :link="backpack_url('settlement')" />
    <x-backpack::menu-dropdown-item title="Település részek" icon="la la-question" :link="backpack_url('settlement-part')" />
    <x-backpack::menu-dropdown-item title="Település csoportok" icon="la la-question" :link="backpack_url('settlement-group')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Ingatlanok" icon="la la-home">
    <x-backpack::menu-dropdown-header title="Ingatlanok" />
    <x-backpack::menu-dropdown-item title="Ingatlanok kezelése" icon="la la-question" :link="backpack_url('property')" />
    <x-backpack::menu-dropdown-header title="Mezők" />
    <x-backpack::menu-dropdown-item title="Tulajdonság kategóriák" icon="la la-folder-open" :link="backpack_url('property-attribute-category')" />
    <x-backpack::menu-dropdown-item title="Tulajdonságok" icon="la la-file-alt" :link="backpack_url('property-attribute')" />
    <x-backpack::menu-dropdown-item title="Ingatlan típusok" icon="la la-question" :link="backpack_url('property-type')" />
    <x-backpack::menu-dropdown-item title="Ingatlan altípusok" icon="la la-question" :link="backpack_url('property-subtype')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-item title="Címkék" icon="la la-tags" :link="backpack_url('label')" />

<x-backpack::menu-dropdown title="Felhasználók" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-header title="Authentication" />
    <x-backpack::menu-dropdown-item title="Dolgozók" icon="la la-user" :link="backpack_url('user')" />
    <x-backpack::menu-dropdown-item title="Szerepkörök" icon="la la-group" :link="backpack_url('role')" />
    <x-backpack::menu-dropdown-item title="Jogosultságok" icon="la la-key" :link="backpack_url('permission')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Vevők" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-item title="Új vevő felvitele" icon="la la-user" :link="backpack_url('customers/create')" />
    <x-backpack::menu-dropdown-item title="Vevők karbantartása" icon="la la-key" :link="backpack_url('customers')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Szolgáltatások" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-item title="Szolgáltatások" icon="la la-group" :link="backpack_url('service')" />
    <x-backpack::menu-dropdown-item title="Csoportok" icon="la la-question" :link="backpack_url('service-category')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Információk" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-item title="Információk" icon="la la-group" :link="backpack_url('information')" />
    <x-backpack::menu-dropdown-item title="Csoportok" icon="la la-question" :link="backpack_url('information-category')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-item title="Statikus oldalak" icon="la la-question" :link="backpack_url('static-page')" />
<x-backpack::menu-item title="Projektek" icon="la la-question" :link="backpack_url('project')" />
<x-backpack::menu-item title="Képletöltés" icon="la la-question" :link="backpack_url('property-image-downloader')" />

<x-backpack::menu-item title="Slider images" icon="la la-question" :link="backpack_url('slider-images')" />
<x-backpack::menu-item title="Kiajánlások" icon="la la-question" :link="backpack_url('customer-search')" />
