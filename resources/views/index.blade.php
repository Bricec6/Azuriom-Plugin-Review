@extends('layouts.app')

@section('title', trans('review::messages.title'))

@section('content')
    <h1>{{ trans('review::messages.section.title') }}</h1>

    <div class="row gy-4">
        @foreach($reviews as $review)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">{{ $review->title }}</div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>

                        <p class="card-text">"{{ $review->content }}"</p>

                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $review->author->getAvatar(32) }}" width="32" height="32"
                                 class="rounded-1"
                                 alt="{{ $review->author->name }}">
                            <div class="d-flex flex-column align-items-start gap-1">
                                <span>{{ $review->author->name }}</span>
                                <span class="badge" style="{{ $review->author->role->getBadgeStyle() }}">
                                    @if($review->author->role->icon)
                                        <i class="{{ $review->author->role->icon }}"></i>
                                    @endif
                                    {{ $review->author->role->name }}
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <small
                                class="text-muted">{{ trans('review::messages.posted', ['date' => format_date($review->created_at), 'user' => $review->author->name]) }}  </small>

                            @can('delete', $review)
                                <a class="text-danger" href="{{ route('review.review.destroy', $review) }}"
                                   data-confirm="delete" title="{{ trans('messages.actions.delete') }}">
                                    <i class="bi bi-trash"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete confirm modal -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" id="confirmDeleteLabel">{{ trans('review::messages.delete.title') }}</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">{{ trans('review::messages.delete.confirm') }}</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button"
                                    data-bs-dismiss="modal">{{ trans('messages.actions.cancel') }}</button>

                            <form id="confirmDeleteForm" action="{{ route('review.review.destroy', $review) }}" method="POST">
                                @method('DELETE')
                                @csrf

                                <button class="btn btn-danger" type="submit">{{ trans('messages.actions.delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $reviews->links() }}

    @include('review::components._form')

@endsection

@push('footer-scripts')
    <script>
        const contentInput = document.getElementById('content');
        const contentCounter = document.getElementById('contentCounter');
        const contentCounterMax = document.getElementById('contentCounterMax');


        contentCounterMax.textContent = contentInput.getAttribute('maxlength');

        contentInput.addEventListener('input', function () {
            contentCounter.textContent = contentInput.value.length;
        });
    </script>
@endpush
