@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ $config_data->module_name }} List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Project">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                            <th>ID</th>
                        @foreach ($data_items["columns"] as $col)
                            <th>{{ $data_items["custom_columns"][$col] ?? ucfirst(str_replace('_', ' ', $col)) }}</th>
                        @endforeach
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_items["data"] as $key => $dataValue)
                        <tr data-entry-id="{{ $dataValue->id }}">
                            <td>

                            </td>
                            <td> {{ $dataValue->id }} </td>
                            @foreach ($data_items["columns"] as $col)
                                <td>
                                    @if(str_contains($col, '_id')) 
                                        {{ optional($dataValue->{str_replace('_id', '', $col)})->first_name }}
                                        {{ optional($dataValue->{str_replace('_id', '', $col)})->name }}
                                    @else
                                        {{ $dataValue->$col }}
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                @can($config_data->module_perm_name.'_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route($config_data->module_route.'.show', $dataValue->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  $('.datatable-Project:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection