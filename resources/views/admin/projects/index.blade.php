@extends('layouts.admin')
@section('content')
@can($config_data->module_perm_name.'_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("$config_data->module_route.create") }}">
                Add {{ $config_data->module_name }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.project.title_singular') }} {{ trans('global.list') }}
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

                                @can($config_data->module_perm_name.'_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route($config_data->module_route.'.edit', $dataValue->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can($config_data->module_perm_name.'_delete')
                                    <form action="{{ route($config_data->module_route.'.destroy', $dataValue->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
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
@can($config_data->module_perm_name.'_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route($config_data->module_route.'.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

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