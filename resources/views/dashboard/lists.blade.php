<ul id="transactions-tabs" class="mt-4 nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button
            id="general-tab"
            class="nav-link {{$active_tab === 'general-tab' ? 'active' : '' }}"
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
            id="transac-goals-tab"
            class="nav-link {{ $active_tab === 'transac-goals-tab' ? 'active' : '' }}"
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
            id="transac-expanses-tab"
            class="nav-link {{ !empty($active_tab) && $active_tab === 'transac-expanses-tab' ? 'active' : '' }}"
            data-bs-toggle="tab"
            data-bs-target="#transac-expanses-tab-pane"
            type="button"
            role="tab"
            aria-controls="transac-expanses-tab-pane"
            aria-selected="false"
        >
            Dépenses
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button
            id="transac-revenus-tab"
            class="nav-link {{ !empty($active_tab) && $active_tab === 'transac-revenus-tab' ? 'active' : '' }}"
            data-bs-toggle="tab"
            data-bs-target="#transac-revenus-tab-pane"
            type="button"
            role="tab"
            aria-controls="transac-revenus-tab-pane"
            aria-selected="false"
        >
            Revenus
        </button>
    </li>
</ul>

<div id="recurrences-tab-content" class="tab-content">
    <div
        id="general-tab-pane"
        class="tab-pane fade container {{ empty($active_tab) || $active_tab === 'general-tab' ? 'show active' : '' }}"
        role="tabpanel"
        aria-labelledby="general-tab"
        tabindex="0"
    >
        <div id="general-wrapper" class="mt-5 list-wrapper">
            @include('dashboard.graphs')
        </div>
    </div>

    <div
        id="transac-goals-tab-pane"
        class="tab-pane fade container {{ !empty($active_tab) && $active_tab === 'transac-goals-tab' ? 'show active' : '' }}"
        role="tabpanel"
        aria-labelledby="transac-goals-tab"
        tabindex="0"
    >
        <div class="mt-4">
            @include('dashboard.goals')
        </div>
    </div>

    <div
        id="transac-expanses-tab-pane"
        class="tab-pane fade container {{ !empty($active_tab) && $active_tab === 'transac-expanses-tab' ? 'show active' : '' }}"
        role="tabpanel"
        aria-labelledby="transac-expanses-tab"
        tabindex="0"
    >
        <div class="mt-4">
            @include('dashboard.expanses-filters')
        </div>

        <div id="expanses-list-wrapper" class="mt-4 list-wrapper">
            @include('dashboard.expanses-list')
        </div>
    </div>

    <div
        id="transac-revenus-tab-pane"
        class="tab-pane fade container {{ !empty($active_tab) && $active_tab === 'transac-revenus-tab' ? 'show active' : '' }}"
        role="tabpanel"
        aria-labelledby="transac-revenus-tab"
        tabindex="0"
    >
        <div class="mt-4 list-wrapper">
            @include('dashboard.revenus-list')
        </div>
    </div>
</div>
