@if (backpack_theme_config('show_powered_by') || backpack_theme_config('developer_link'))
    <footer class="d-print-none {{ backpack_theme_config('classes.footer') ?? 'footer app-footer sticky-footer bg-transparent p-3 border-top-0' }}">
        <div class="{{ backpack_theme_config('options.useFluidContainers') ? 'container-fluid' : 'container-xl' }}">

        </div>
    </footer>
@endif
