@extends('admin.layouts.admin')

@section('title', trans('review::admin.plugin.name'))

@section('content')
    <div class="col-12 mb-3 d-flex flex-column gap-2">
        <ul class="list-unstyled d-flex flex-wrap gap-2">
            <li>
                <a href="https://github.com/Bricec6/Azuriom-Plugin-Review" target="_blank" class="btn bg-white text-black fw-bold rounded-4 text-uppercase px-3"><i class="bi bi-github me-1"></i>{{trans('review::admin.contribute')}}</a>
            </li>
            <li>
                <a href="https://discord.gg/Gh2yBxUWvV" target="_blank" class="btn btn-primary fw-bold rounded-4 text-uppercase px-3"><i class="bi bi-discord me-1"></i>{{trans('review::admin.support')}}</a>
            </li>
            <li>
                <a href="https://www.serveurliste.com" target="_blank" class="btn btn-warning fw-bold rounded-4 text-uppercase px-3"><i class="bi bi-search-heart-fill me-1"></i>{{trans('review::admin.serveurliste')}}</a>
            </li>
        </ul>
        <hr>
    </div>

    <form class="row row-cols-lg-auto g-3 align-items-center" action="{{ route('review.admin.index') }}" method="GET" role="search">
        <div class="mb-3">
            <label for="searchInput" class="visually-hidden">
                {{ trans('messages.actions.search') }}
            </label>

            <div class="input-group">
                <input type="search" class="form-control" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="{{ trans('messages.actions.search') }}">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="card shadow mb-4">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{ trans('messages.fields.user') }}</th>
                    <th scope="col">{{ trans('review::admin.table.rating') }}</th>
                    <th scope="col">{{ trans('messages.fields.title') }}</th>
                    <th scope="col">{{ trans('messages.fields.content') }}</th>
                    <th scope="col">{{ trans('messages.fields.date') }}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($reviews as $review)
                    <tr>
                        <th scope="row">{{ $review->id }}</th>
                        <td>
                            <a href="{{ route('admin.users.edit', $review->author) }}">
                                {{ $review->author->name }}
                            </a>
                        </td>
                        <td>{{ $review->rating }}/5</td>
                        <td>{{ $review->title }}</td>
                        <td>{{ Str::limit($review->content, 50) }}</td>
                        <td>{{ format_date_compact($review->created_at) }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        {{ $reviews->withQueryString()->links() }}
    </div>
@endsection
