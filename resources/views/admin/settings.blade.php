@extends('admin.layouts.admin')

@section('title', trans('review::admin.settings.title'))

@section('content')
    <form action="{{ route('review.admin.settings.save') }}" method="POST">
        @csrf

        <div class="card shadow mb-4">
            <div class="card-body">
                <h2 class="h5 card-title">{{ trans('review::admin.settings.display.title') }}</h2>

                <div class="mb-3">
                    <label class="form-label" for="perPageInput">{{ trans('review::admin.settings.display.per-page') }}</label>
                    <input type="number" class="form-control @error('per-page') is-invalid @enderror" id="perPageInput"
                           name="per-page" min="1" max="100" value="{{ old('per-page', $perPage) }}" required>

                    @error('per-page')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input" id="displayImported" name="display-imported" @checked($displayImported)>
                    <label class="form-check-label" for="displayImported">{{ trans('review::admin.settings.display.imported') }}</label>
                </div>

                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="averageImported" name="average-imported" @checked($averageImported)>
                    <label class="form-check-label" for="averageImported">{{ trans('review::admin.settings.display.average') }}</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
        </button>
    </form>
@endsection
