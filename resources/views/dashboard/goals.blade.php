@php
    use App\Models\Category;
    use App\Models\Label;
@endphp

<div class="row justify-content-between">
    <div class="col-5">
        <h2 class="text-center">Catégories</h2>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Catégorie</th>
                <th>But</th>
                <th>Réel</th>
            </tr>
            </thead>

            <tbody>
            @php /** @var Category $category */ @endphp
            @foreach($categories as $category)
                @if (empty($category->goal))
                    @continue
                @endif

                @php $total = abs($category->monthExpanses()->sum('amount')) @endphp

                <tr>
                    <td @if ($total > $category->goal) class="text-danger" @endif>{{ $category->appellation }}</td>

                    <td @if ($total > $category->goal) class="text-danger" @endif>
                        {{ formatAmount($category->goal) }}€
                    </td>

                    <td @if ($total > $category->goal) class="text-danger" @endif>
                        {{ formatAmount($total) }}€
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-5">
        <h2 class="text-center">Labels</h2>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Label</th>
                <th>But</th>
                <th>Réel</th>
            </tr>
            </thead>

            <tbody>
            @php /** @var Label $label */ @endphp
            @foreach($labels as $label)
                @if (empty($label->goal))
                    @continue
                @endif

                @php $total = abs($label->monthExpanses()->sum('amount')) @endphp

                <tr>
                    <td @if ($total > $label->goal) class="text-danger" @endif>{{ $label->appellation }}</td>

                    <td @if ($total > $label->goal) class="text-danger" @endif>
                        {{ formatAmount($label->goal) }}€
                    </td>

                    <td @if ($total > $label->goal) class="text-danger" @endif>
                        {{ formatAmount($total) }}€
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
