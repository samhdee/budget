<nav class="navbar navbar-expand-md">
    <div class="container justify-content-start">
        <a class="text-light navbar-brand" href="{{ route('transac_index') }}">
            <i class="fas fa-home"></i>
        </a>

        <ul class="navbar-nav">
            <li>
                <a
                    class="me-2 nav-link {{ str_starts_with(Route::currentRouteName(), 'transac_') || str_starts_with(Route::currentRouteName(), 'transactions') ? 'active' : '' }}"
                    href="{{ route('transac_index') }}"
                >
                    Transactions
                </a>
            </li>

            <li>
                <a
                    class="me-2 nav-link {{ str_starts_with(Route::currentRouteName(), 'dashboard_') || str_starts_with(Route::currentRouteName(), 'transactions') ? 'active' : '' }}"
                    href="{{ route('dashboard_index') }}"
                >
                    Dashboard
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                   aria-expanded="false">
                    Gérer
                </a>

                <ul class="dropdown-menu">
                    <li>
                        <a
                            class="me-2 dropdown-item {{ str_starts_with (Route::currentRouteName(), 'recurrences_') ? 'active' : '' }}"
                            href="{{ route('recurrences_index') }}"
                        >
                            Récurrences
                        </a>
                    </li>

                    <li>
                        <a
                            class="me-2 dropdown-item {{ str_starts_with (Route::currentRouteName(), 'benef_') ? 'active' : '' }}"
                            href="{{ route('benef_index') }}"
                        >
                            Bénéficiaires
                        </a>
                    </li>

                    <li>
                        <a
                            class="me-2 dropdown-item {{ str_starts_with (Route::currentRouteName(), 'categ_') ? 'active' : '' }}"
                            href="{{ route('categ_index') }}"
                        >
                            Catégories
                        </a>
                    </li>

                    <li>
                        <a
                            class="me-2 dropdown-item {{ str_starts_with (Route::currentRouteName(), 'labels_') ? 'active' : '' }}"
                            href="{{ route('labels_index') }}"
                        >
                            Labels
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a
                    class="nav-link {{ str_starts_with (Route::currentRouteName(), 'import_') ? 'active' : '' }}"
                    href="{{ route('import_index') }}"
                >
                    Import
                </a>
            </li>
        </ul>
    </div>
</nav>
