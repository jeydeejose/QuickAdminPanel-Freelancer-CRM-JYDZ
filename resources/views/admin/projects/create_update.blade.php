@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        @if(isset($data_items["data"]))
            Update
        @else
            Create 
        @endif
        {{ $config_data->module_name }}
    </div>

    <div class="card-body">
        <form action="{{ isset($data_items["data"]) ? route("$config_data->module_route.update", [$data_items["data"]->id]) : route("$config_data->module_route.store") }}" method="POST" enctype="multipart/form-data">
            @csrf

                @if(isset($data_items["data"]))
                    @method('PUT')
                @endif
            
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.project.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($data_items["data"]) ? $data_items["data"]->name : '') }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                <label for="client">{{ trans('cruds.project.fields.client') }}*</label>
                <select name="client_id" id="client" class="form-control select2" required>
                    @foreach($data_items["clients"] as $id => $client)
                        <option value="{{ $id }}" {{ (isset($data_items["data"]) && $data_items["data"]->client ? $data_items["data"]->client->id : old('client_id')) == $id ? 'selected' : '' }}>{{ $client }}</option>
                    @endforeach
                </select>
                @if($errors->has('client_id'))
                    <p class="help-block">
                        {{ $errors->first('client_id') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">{{ trans('cruds.project.fields.description') }}</label>
                <textarea id="description" name="description" class="form-control ">{{ old('description', isset($data_items["data"]) ? $data_items["data"]->description : '') }}</textarea>
                @if($errors->has('description'))
                    <p class="help-block">
                        {{ $errors->first('description') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.description_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('start_date') ? 'has-error' : '' }}">
                <label for="start_date">{{ trans('cruds.project.fields.start_date') }}</label>
                <input type="text" id="start_date" name="start_date" class="form-control date" value="{{ old('start_date', isset($data_items["data"]) ? $data_items["data"]->start_date : '') }}">
                @if($errors->has('start_date'))
                    <p class="help-block">
                        {{ $errors->first('start_date') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.start_date_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }}">
                <label for="budget">{{ trans('cruds.project.fields.budget') }}</label>
                <input type="number" id="budget" name="budget" class="form-control" value="{{ old('budget', isset($data_items["data"]) ? $data_items["data"]->budget : '') }}" step="0.01">
                @if($errors->has('budget'))
                    <p class="help-block">
                        {{ $errors->first('budget') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.budget_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }}">
                <label for="status">{{ trans('cruds.project.fields.status') }}</label>
                <select name="status_id" id="status" class="form-control select2">
                    @foreach($data_items["statuses"] as $id => $status)
                        <option value="{{ $id }}" {{ (isset($data_items["data"]) && $data_items["data"]->status ? $data_items["data"]->status->id : old('status_id')) == $id ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @if($errors->has('status_id'))
                    <p class="help-block">
                        {{ $errors->first('status_id') }}
                    </p>
                @endif
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                <a class="btn btn-default" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection