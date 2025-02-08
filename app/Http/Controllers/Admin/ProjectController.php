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

class ProjectController extends Controller
{

    protected $config_data;

    public function __construct()
    {
        $this->config_data = (object) [
            "module_name"=>"Project",
            "module_perm_name"=>"project",
            "module_route"=>"admin.projects",
        ];

        view()->share('config_data', $this->config_data);
    }

    public function index()
    {
        abort_if(Gate::denies($this->config_data->module_perm_name.'_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hidden_columns = [""];
        $custom_columns = [
            'name' => 'Project Name',
            'client_id' => 'Client Name',
            'status_id' => 'Status',
        ];
        $data = new Project();
        $columns = array_diff($data->getFillable(), $hidden_columns, $data->getDates());
        $data_items = [
            "data" => $data->all(),
            "columns" => $columns,
            "custom_columns" => $custom_columns,
        ];

        return view($this->config_data->module_route.'.index', compact('data_items'));
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies($this->config_data->module_perm_name.'_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hidden_columns = [""];
        $custom_columns = [
            'name' => 'Project Name',
            'client_id' => 'Client Name',
            'status_id' => 'Status',
        ];
        $data = $project;
        $columns = array_diff($data->getFillable(), $hidden_columns, $data->getDates());
        $data_items = [
            "data" => $project->load('client', 'status'),
            "columns" => $columns,
            "custom_columns" => $custom_columns,
        ];

        return view($this->config_data->module_route.'.show', compact('data_items'));
    }

    public function create()
    {
        abort_if(Gate::denies($this->config_data->module_perm_name.'_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all()->pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $statuses = ProjectStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $data_items = [
            "clients" => $clients,
            "statuses" => $statuses,
        ];
        return view($this->config_data->module_route.'.create_update', compact('data_items'));
    }

    public function store(StoreProjectRequest $request)
    {
        $data = Project::create($request->all());
        return redirect()->route($this->config_data->module_route.'.index');
    }

    public function edit(Project $project)
    {
        abort_if(Gate::denies($this->config_data->module_perm_name.'_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all()->pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $statuses = ProjectStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $project->load('client', 'status');
        $data = $project->load('client', 'status');

        $data_items = [
            "clients" => $clients,
            "statuses" => $statuses,
            "project" => $project,
            "data" => $data,
        ];
        return view($this->config_data->module_route.'.create_update', compact('data_items'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        return redirect()->route($this->config_data->module_route.'.index');
    }

    public function destroy(Project $project)
    {
        abort_if(Gate::denies($this->config_data->module_perm_name.'_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        Project::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
