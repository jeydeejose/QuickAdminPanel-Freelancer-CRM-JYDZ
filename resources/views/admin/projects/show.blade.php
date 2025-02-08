@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Show {{ $config_data->module_name }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.project.fields.id') }}</th>
                        <td>{{ $data_items["data"]->id }}</td>
                    </tr>
                    @foreach ($data_items["columns"] as $col)
                        <tr>
                            <th>
                                {{ $data_items["custom_columns"][$col] ?? ucfirst(str_replace('_', ' ', $col)) }}
                            </th>
                            <td>
                                @if(str_contains($col, '_id')) 
                                    {{ optional($data_items["data"]->{str_replace('_id', '', $col)})->name ?? '' }}
                                    {{ optional($data_items["data"]->{str_replace('_id', '', $col)})->first_name ?? '' }}
                                @else
                                    {{ $data_items["data"]->$col }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@endsection