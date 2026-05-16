@php use App\Models\Beneficiary; @endphp

<div class="mx-auto w-75">
    @if ($beneficiaries->lastPage() > 1)
        <div class="pagination-wrapper">
            {{ $beneficiaries->links() }}
        </div>
    @endif

    <table class="table table-striped table-bordered align-middle">
        <thead>
            <th>Nom moche</th>
            <th>Nom joli</th>
            <th>Catégorie par défaut</th>
            <th>Description</th>
            <th style="width: 6rem;"></th>
        </thead>

        <tbody>
            @php /** @var Beneficiary $beneficiary */ @endphp
            @foreach ($beneficiaries as $beneficiary)
                <tr>
                    <td>{{ $beneficiary->raw_name }}</td>
                    <td>{{ $beneficiary->pretty_name }}</td>
                    <td>{{ $beneficiary->c_appellation }}</td>
                    <td>{{ $beneficiary->description }}</td>

                    <td class="text-center">
                        <a
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-benef-form"
                            data-action="edit"
                            data-type="bénéficiaire"
                            data-url="{{ route('benef_get', $beneficiary->id) }}"
                        >
                            <i class="fas fa-pencil"></i>
                        </a>

                        <a
                            class="ms-1 btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-benef-delete"
                            data-url="{{ route('benef_get', $beneficiary->id) }}"
                            data-action="delete"
                            data-type="bénéficiaire"
                        >
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($beneficiaries->lastPage() > 1)
        <div class="pagination-wrapper">
            {{ $beneficiaries->links() }}
        </div>
    @endif
</div>
