<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container justify-content-start">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-home"></i>
        </a>

        <div class="navbar-nav">
            <a class="nav-link" href="{{ route('transac_index') }}">Transactions</a>
            <a class="nav-link" href="{{ route('categ_index') }}">Catégories</a>
            <a class="nav-link" href="{{ route('labels_index') }}">Labels</a>
        </div>
    </div>
</nav>
