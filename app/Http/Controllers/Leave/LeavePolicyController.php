<?php

namespace App\Http\Controllers\Leave;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Leave\LeavePolicyResource;
use App\Http\Requests\Leave\LeavePolicyFormRequest;
use App\Repositories\Contracts\Leave\LeavePolicyRepository;

class LeavePolicyController extends Controller
{
    protected $policy;

    public function __construct(LeavePolicyRepository $policy)
    {
        $this->policy = $policy;
        
        $this->middleware('permission:create_leaves')->only('store');
        $this->middleware('permission:update_leaves')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $policy = $this->policy->all();

        return response()->json([
            'data' => LeavePolicyResource::collection($policy)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeavePolicyFormRequest $request)
    {
        $policy = $this->policy->create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Leave policy created successfully!',
            'data' => new LeavePolicyResource($policy)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $policy = $this->policy->find($id);

        return response()->json([
            'data' => new LeavePolicyResource($policy)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $policy = $this->policy->find($id);

        $policy->name = $request->get('name', $policy->name);
        $policy->description = $request->get('description', $policy->description);

        $policy->save();

        return response()->json([
            'message' => 'Leave policy updated successfully!',
            'data' => new LeavePolicyResource($policy)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->policy->permanentDelete('id', $id);

        return response()->json([
            'message' => 'Leave policy deleted permanently!'
        ], 200);
    }
}
