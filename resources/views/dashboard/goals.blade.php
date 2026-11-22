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
                    <th style="width: 6rem;">But</th>
                    <th style="width: 6rem;">Réel</th>
                    <th style="width: 3rem;">%</th>
                </tr>
            </thead>

            <tbody>
                @php /** @var Category $category */ @endphp
                @foreach($categories as $category)
                    @if (empty($category->goal))
                        @continue
                    @endif

                    @php
                        $total = abs($category->monthExpanses()->sum('amount'));
                        $percentage = 100 * $total / $category->goal;
                        $color_class = 'text-';

                        if ($percentage > 99) {
                            $color_class .= 'danger';
                        } elseif ($percentage > 90) {
                            $color_class .= 'warning';
                        } elseif ($percentage > 80) {
                            $color_class .= 'light-warning';
                        } else {
                            $color_class .= 'success';
                        }
                    @endphp

                    <tr>
                        <td class="{{ $color_class }}">{{ $category->appellation }}</td>

                        <td class="{{ $color_class }}">
                            {{ formatAmount($category->goal, 0) }}€
                        </td>

                        <td class="{{ $color_class }}">
                            {{ formatAmount($total) }}€
                        </td>

                        <td class="{{ $color_class }}">
                            {{ formatAmount($percentage, 0) }}%
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
                    <th style="width: 6rem;">But</th>
                    <th style="width: 6rem;">Réel</th>
                    <th style="width: 3rem;">%</th>
                </tr>
            </thead>

            <tbody>
                @php /** @var Label $label */ @endphp
                @foreach($labels as $label)
                    @if (empty($label->goal))
                        @continue
                    @endif

                    @php
                        $total = abs($label->monthExpanses()->sum('amount'));
                        $percentage = 100 * $total / $label->goal;
                        $color_class = 'text-';

                        if ($percentage > 99) {
                            $color_class .= 'danger';
                        } elseif ($percentage > 90) {
                            $color_class .= 'warning';
                        } elseif ($percentage > 80) {
                            $color_class .= 'light-warning';
                        } else {
                            $color_class .= 'success';
                        }
                    @endphp

                    <tr>
                        <td class="{{ $color_class }}">{{ $label->appellation }}</td>

                        <td class="{{ $color_class }}">
                            {{ formatAmount($label->goal, 0) }}€
                        </td>

                        <td class="{{ $color_class }}">
                            {{ formatAmount($total) }}€
                        </td>

                        <td class="{{ $color_class }}">
                            {{ formatAmount($percentage, 0) }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
