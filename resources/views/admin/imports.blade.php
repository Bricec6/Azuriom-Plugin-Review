@extends('admin.layouts.admin')

@section('title', trans('review::admin.imports.title'))

@section('content')
    <form action="{{ route('review.admin.imports.save') }}" method="POST">
        @csrf

        <div class="card shadow mb-4">
            <div class="card-body">
                <h2 class="h5 card-title">{{ trans('review::admin.sources.title') }}</h2>
                <p class="text-muted">{{ trans('review::admin.sources.description') }}</p>

                @foreach($sources as $domain => $source)
                    @php($id = Str::slug($domain))

                    <div class="card card-body mb-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <div>
                                <span class="fw-bold">{{ $source->name() }}</span>
                                <span class="text-muted">{{ $domain }}</span>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @if(($importedCounts[$domain] ?? 0) > 0)
                                    <span class="badge text-bg-secondary">
                                        {{ trans_choice('review::admin.sources.imported-count', $importedCounts[$domain], ['count' => $importedCounts[$domain]]) }}
                                    </span>
                                @endif

                                @if($source->lastSyncAt() !== null)
                                    <span class="text-muted small">
                                        {{ trans('review::admin.sources.last-sync', ['date' => format_date_compact($source->lastSyncAt())]) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(! $source->isSupported())
                            <div class="alert alert-secondary mb-0" role="alert">
                                <i class="bi bi-slash-circle"></i> {{ $source->unsupportedReason() }}
                            </div>
                        @else
                            @if($source->lastError() !== null)
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i> {{ $source->lastError() }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label" for="token-{{ $id }}">{{ trans('review::admin.sources.token') }}</label>

                                <div class="input-group">
                                    <input type="text" class="form-control" id="token-{{ $id }}"
                                           name="sources[{{ $domain }}][token]"
                                           value="{{ setting($source->settingKey('token')) }}"
                                           placeholder="{{ $source->voteToken() !== null ? trans('review::admin.sources.vote-placeholder') : '' }}">

                                    <button type="button" class="btn btn-outline-secondary" data-test="{{ route('review.admin.imports.test', $domain) }}" data-token="token-{{ $id }}" data-result="result-{{ $id }}">
                                        {{ trans('review::admin.sources.test') }}
                                    </button>

                                    <button type="button" class="btn btn-primary" data-sync="{{ route('review.admin.imports.sync', $domain) }}" data-result="result-{{ $id }}">
                                        <i class="bi bi-arrow-repeat"></i> {{ trans('review::admin.sources.sync') }}
                                    </button>
                                </div>

                                @if($source->voteSite() !== null)
                                    <div class="form-text text-info">
                                        <i class="bi bi-hand-thumbs-up"></i>
                                        {{ trans('review::admin.sources.vote-token', ['site' => $source->voteSite()->name]) }}
                                    </div>
                                @endif

                                <div id="result-{{ $id }}" class="form-text d-none"></div>
                            </div>

                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="enabled-{{ $id }}"
                                       name="sources[{{ $domain }}][enabled]" @checked(setting($source->settingKey('enabled')))>
                                <label class="form-check-label" for="enabled-{{ $id }}">{{ trans('review::admin.sources.enable') }}</label>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
        </button>
    </form>
@endsection

@push('footer-scripts')
    <script>
        const reviewHeaders = {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        };

        function displayResult(element, success, message) {
            element.textContent = message;
            element.classList.remove('d-none', 'text-success', 'text-danger');
            element.classList.add(success ? 'text-success' : 'text-danger');
        }

        document.querySelectorAll('[data-test]').forEach(function (button) {
            button.addEventListener('click', function () {
                const result = document.getElementById(button.dataset.result);
                const token = document.getElementById(button.dataset.token).value;

                button.disabled = true;

                axios.post(button.dataset.test, { token: token }, { headers: reviewHeaders })
                    .then(function (response) {
                        displayResult(result, response.data.success, response.data.message);
                    })
                    .catch(function (error) {
                        displayResult(result, false, error.message);
                    })
                    .finally(function () {
                        button.disabled = false;
                    });
            });
        });

        document.querySelectorAll('[data-sync]').forEach(function (button) {
            button.addEventListener('click', function () {
                const result = document.getElementById(button.dataset.result);

                button.disabled = true;

                axios.post(button.dataset.sync, {}, { headers: reviewHeaders })
                    .then(function (response) {
                        displayResult(result, response.data.success, response.data.message);
                    })
                    .catch(function (error) {
                        displayResult(result, false, error.message);
                    })
                    .finally(function () {
                        button.disabled = false;
                    });
            });
        });
    </script>
@endpush
