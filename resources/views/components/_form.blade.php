@can('review.create')
    <div class="card mt-4">
        <div class="card-body">
            <h3 class="card-title">
                {{ trans('review::messages.form.title') }}
            </h3>

            <form action="{{ route('review.review.store') }}" method="POST">
                @csrf

                <div class="d-flex items-center flex-column flex-md-row flex-wrap gap-3">
                    <div class="mb-3">
                        <label class="form-label" for="rating">{{ trans('review::messages.form.rating') }}</label>

                        <select class="form-select @error('currency') is-invalid @enderror" id="rating" name="rating">
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div class="flex-grow-1 mb-3">
                        <label class="form-label" for="title">{{ trans('review::messages.form.title_field') }}</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                               name="title" required>

                        @error('title')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="content">{{ trans('review::messages.form.content') }}</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                              name="content" rows="4" required maxlength="250"></textarea>
                    <div class="form-text text-end">
                        <span id="contentCounter">0</span>/<span id="contentCounterMax"></span>
                    </div>

                    @error('content')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus"></i> {{ trans('review::messages.form.add_review') }}
                </button>
            </form>
        </div>
    </div>
@endcan

@guest
    <div class="alert alert-info" role="alert">
        {{ trans('review::messages.guest') }}
    </div>
@endguest
