<ul id="recurrences-tabs" class="mt-3 nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button
            class="nav-link active"
            id="recurrences-active-tab"
            data-bs-toggle="tab"
            data-bs-target="#recurrences-active-tab-pane"
            type="button"
            role="tab"
            aria-controls="recurrences-active-tab-pane"
            aria-selected="true"
        >
            Récurrences actives
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button
            class="nav-link"
            id="recurreces-inactive-tab"
            data-bs-toggle="tab"
            data-bs-target="#recurreces-inactive-tab-pane"
            type="button"
            role="tab"
            aria-controls="recurreces-inactive-tab-pane"
            aria-selected="false"
        >
            Récurrences passées/inactives
        </button>
    </li>
</ul>

<div class="tab-content" id="recurreces-tab-content">
    <div
        id="recurrences-active-tab-pane"
        class="tab-pane fade show active"
        role="tabpanel"
        aria-labelledby="recurrences-active-tab"
        tabindex="0"
    >
        <div class="mt-4 list-wrapper">
            @include('recurrences.active-list')
        </div>
    </div>

    <div
        id="recurreces-inactive-tab-pane"
        class="tab-pane fade"
        role="tabpanel"
        aria-labelledby="recurreces-inactive-tab"
        tabindex="0"
    >
        <div class="mt-4 mx-auto w-75 list-wrapper">
            @include('recurrences.inactive-list')
        </div>
    </div>
</div>
