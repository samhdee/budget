<ul id="transactions-tabs" class="mt-4 nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button
            id="general-tab"
            class="nav-link active"
            data-bs-toggle="tab"
            data-bs-target="#general-tab-pane"
            type="button"
            role="tab"
            aria-controls="general-tab-pane"
            aria-selected="true"
        >
            Vue d'ensemble
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button
            id="transac-comparisons-tab"
            class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#transac-comparisons-tab-pane"
            type="button"
            role="tab"
            aria-controls="transac-comparisons-tab-pane"
            aria-selected="false"
        >
            Comparaisons
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button
            id="transac-goals-tab"
            class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#transac-goals-tab-pane"
            type="button"
            role="tab"
            aria-controls="transac-goals-tab-pane"
            aria-selected="false"
        >
            Goals
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button
            id="recurrences-tab"
            class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#recurrences-tab-pane"
            type="button"
            role="tab"
            aria-controls="recurrences-tab-pane"
            aria-selected="false"
        >
            Récurrences
        </button>
    </li>
</ul>

<div id="recurrences-tab-content" class="tab-content">
    <div
        id="general-tab-pane"
        class="tab-pane fade container show active"
        role="tabpanel"
        aria-labelledby="general-tab"
        tabindex="0"
    >
        <div id="general-wrapper" class="mt-5 list-wrapper">
            @include('dashboard.graphs')
        </div>
    </div>

    <div
        id="transac-comparisons-tab-pane"
        class="tab-pane fade container"
        role="tabpanel"
        aria-labelledby="transac-comparisons-tab"
        tabindex="0"
    >
        <div class="mt-4">
            @include('dashboard.comparisons')
        </div>
    </div>

    <div
        id="transac-goals-tab-pane"
        class="tab-pane fade container"
        role="tabpanel"
        aria-labelledby="transac-goals-tab"
        tabindex="0"
    >
        <div class="mt-4">
            @include('dashboard.goals')
        </div>
    </div>

    <div
        id="recurrences-tab-pane"
        class="tab-pane fade container"
        role="tabpanel"
        aria-labelledby="recurrences-tab"
        tabindex="0"
    >
        <div class="mt-4">
            @include('dashboard.recurrences')
        </div>
    </div>
</div>
