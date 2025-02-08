<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use App\ProjectStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{

    protected $config_data;

    public function __construct()
    {
        $this->config_data = (object) [
            "module_name"=>"Activity Logs",
            "module_perm_name"=>"activitylog",
            "module_route"=>"admin.activitylog",
        ];

        view()->share('config_data', $this->config_data);
    }

    public function index()
    {
        abort_if(Gate::denies($this->config_data->module_perm_name.'_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hidden_columns = ["id", "causer_type", "subject_type", "batch_uuid", "properties"];
        $custom_columns = [
            'log_name' => 'Where',
            'subject_id' => 'Item Title',
            'causer_id' => 'User',
        ];
        $data = new Activity();
        $columns = array_diff(Schema::getColumnListing('activity_log'), $hidden_columns, $data->getDates());
        $data_items = [
            "data" => $data->all(),
            "columns" => $columns,
            "custom_columns" => $custom_columns,
        ];

        return view($this->config_data->module_route.'.index', compact('data_items'));
    }

    public function show($id)
    {
        abort_if(Gate::denies($this->config_data->module_perm_name.'_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hidden_columns = ["id"];
        $custom_columns = [
            'name' => 'Project Name',
            'client_id' => 'Client Name',
            'status_id' => 'Status',
        ];
        $data = Activity::findOrFail($id);;
        $columns = array_diff(Schema::getColumnListing('activity_log'), $hidden_columns);
        $data_items = [
            "data" => $data,
            "columns" => $columns,
            "custom_columns" => $custom_columns,
        ];

        return view($this->config_data->module_route.'.show', compact('data_items'));
    }

}
