<nav class="navbar navbar-expand-lg">
    <div class="container justify-content-start">
        <a class="text-light navbar-brand" href="{{ route('transac_index') }}">
            <i class="fas fa-home"></i>
        </a>

        <div class="navbar-nav">
            <a
                class="me-2 nav-link {{ str_starts_with(Route::currentRouteName(), 'transac_index') || str_starts_with(Route::currentRouteName(), 'transactions') ? 'active' : '' }}"
                href="{{ route('transac_index') }}"
            >
                Transactions
            </a>

            <a
                class="me-2 nav-link {{ str_starts_with (Route::currentRouteName(), 'categ_') ? 'active' : '' }}"
                href="{{ route('categ_index') }}"
            >
                Catégories
            </a>

            <a
                class="me-2 nav-link {{ str_starts_with (Route::currentRouteName(), 'labels_') ? 'active' : '' }}"
                href="{{ route('labels_index') }}"
            >
                Labels
            </a>

            <a
                class="nav-link {{ str_starts_with (Route::currentRouteName(), 'import_') ? 'active' : '' }}"
                href="{{ route('import_index') }}"
            >
                Importer un CSV
            </a>
        </div>
    </div>
</nav>
