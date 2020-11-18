<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Address;
use App\Models\ProjectDetail;
use App\Models\ProjectDetailStyle;
use App\Http\Controllers\LeadController;

class ProjectController extends Controller
{
    protected $LeadController;
    public function __construct(LeadController $LeadController)
    {
        $this->LeadController = $LeadController;
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['updated_by'] = $user->id;

        $leadid = $request['leadid'];
        $lead = $this->LeadController->show($leadid);
        $addresses = $lead['address'];
        foreach($addresses as $address){
            if($address['primary'] == true){
                $input['addressid'] = $address['id'];
                break;
            }
        }

        $project = Project::create($input);
        $response = $project;
        return $response;
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $res = Project::find($id)->update($input);
        $project = Project::find($id);
        $response = $project;
        return $response;
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        $projects = Project::where('created_by', $user->id)->get();

        foreach($projects as $project){
            $lead = $this->LeadController->show($project['leadid']);
            $project['person'] = $lead['person'];
            $address = Address::find($project['addressid']);
            $project['address'] = $address;
        }

        $response = $projects;
        return $projects;
    }

    public function show($id)
    {
        $project = Project::find($id);
        $lead = $this->LeadController->show($project['leadid']);
        $project['person'] = $lead['person'];
        $address = Address::find($project['addressid']);
        $project['address'] = $address;
        $projectdetails = ProjectDetail::where('projectid', $project['id'])->get();
        foreach($projectdetails as $projectdetail){
            $projectdetailstyles = ProjectDetailStyle::where('projectdetailid', $projectdetail['id'])->get();
            $projectdetail['projectdetailstyles'] = $projectdetailstyles;
        }
        $project['projectdetails'] = $projectdetails;
        return $project;
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        $projectdetails = ProjectDetail::where('projectid', $project['id'])->get();
        foreach($projectdetails as $projectdetail){
            $projectdetailstyles = ProjectDetailStyle::where('projectdetailid', $projectdetail['id'])->get();
            foreach($projectdetailstyles as $projectdetailstyle) $projectdetailstyle->delete();
            $projectdetail->delete();
        }
        $project->delete();
        $project['projectdetails'] = $projectdetails;
        return $project;
    }

    public function getByLeadId($leadid){
        $project = Project::where('leadid', $leadid)->first();
        if($project){
            $res_project = $this->show($project->id);
            return $res_project;
        }
        $res['error'] = true;
        return $res;
    }
    public function getByLeadIdProjectStatus(Request $request){
        $input = $request->all();
        $project = Project::where('leadid', $input['leadid'])->where('projectstatus', $input['projectstatus'])
            ->where('active', $input['active'])->first();
        if($project){
            $res_project = $this->show($project->id);
            return $res_project;
        }
        $res['error'] = true;
        return $res;
    }

    public function getProjectsByProjectStatus($status){
        $user = auth()->user();
        $projects = Project::where('created_by', $user->id)->where('projectstatus', $status)->get();
        $res_projects = array();
        foreach($projects as $project) array_push($res_projects, $this->show($project->id));
        return $res_projects;
    }

}
