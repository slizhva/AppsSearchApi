@extends('layouts.admin')

@section('container-class')
    container
@endsection

@section('body-class')
    col-md-8
@endsection

@section('admin-title')
    <div>
        <span><a class="btn btn-link p-0" href="{{ route('sets') }}">Dashboard</a>/Set/</span><strong>{{ $set['name'] }}</strong>
    </div>
@endsection

@section('admin-body')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <hr>
                <span>---Recipe get link---</span>
                <p class="mb-1">
                    <strong id="appLinkText">{{ route('recipes.get', [$set['id'], $token]) }}</strong>
                </p>
                <input style="display: none" id="copyLinkButton" type="submit" value="Copy Link">

                <hr class="mt-4 mb-4">

                <strong>---Add recipe:---</strong>
                <div class="container">
                    <form class="row" method="POST" action="{{ route('recipe.add', $set['id']) }}" >
                        {{ csrf_field() }}
                        <textarea rows="5" name="value" placeholder="Value" class="col-md-12" required></textarea>
                        <input name="code" type="text" value="" placeholder="Code" class="col-md-9" required>
                        <input type="submit" value="Add" class="col-md-3">
                    </form>
                </div>

                <hr class="mt-4 mb-4">

                <table id="recipesTable" class="table table-sm">
                    <thead class="table-primary">
                    <tr>
                        <th class="table-secondary align-middle text-center">Code</th>
                        <th class="table-secondary align-middle text-center">Value</th>
                        <th class="table-secondary align-middle text-center">Code</th>
                        <th class="table-secondary align-middle text-center">Value</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($recipes as $recipe)
                        @if ($loop->index % 2 === 0)
                            <tr>
                        @endif
                            <td class="table-primary fw-bold align-middle text-center table-hover">{{ $recipe['code'] }}</td>
                            <td class="p-0 table-hover">
                                <form action="{{ route('recipe.update', $set['id']) }}" method="post">
                                    {{ csrf_field() }}
                                    <input name="recipe_id" type="hidden" value="{{ $recipe['id'] }}">
                                    <input name="code" type="hidden" value="{{ $recipe['code'] }}">
                                    <textarea class="form-control" rows="7" class="w-100" name="value">{{ $recipe['value'] }}</textarea>
                                </form>
                                <form class="delete-form" method="POST" action="{{ route('recipe.delete', $set['id']) }}">
                                    {{ csrf_field() }}
                                    <input name="recipe_id" type="hidden" value="{{ $recipe['id']}}">
                                    <input name="dangerous_actions_key" class="dangerous-action-key-value" type="text" value="" hidden>
                                    <input class="col-md-12 pt-0 pb-0 dangerous-action-button" type="submit" value="Delete" disabled>
                                </form>
                            </td>
                        @if ($loop->index % 2 !== 0)
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <hr>
            <div class="col-md-9">
                @include('components.dangerous_action_form')
            </div>
        </div>
    </div>
@endsection
