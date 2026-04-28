@extends('includes.layout')

@section('title')
    Importer un CSV
@endsection

@section('content')
    <div id="import-wrapper">
        <h1>Importer un CSV</h1>

        <form id="import-form" method="POST" action="{{ route('import_store') }}" enctype="multipart/form-data">
            @csrf

            <div class="w-25">
                <input
                    id="import-file"
                    name="files"
                    type="file"
                    class="form-control"
                    accept="text/csv"
                    multiple
                    required
                />
            </div>

            <div class="mt-3">
                <input type="submit" class="btn btn-success" value="Envoyer" />
            </div>
        </form>
    </div>
@endsection
