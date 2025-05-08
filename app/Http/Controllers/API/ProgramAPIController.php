<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProgramAPIRequest;
use App\Http\Requests\API\UpdateProgramAPIRequest;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ProgramResource;
use Response;

/**
 * Class ProgramController
 * @package App\Http\Controllers\API
 */

class ProgramAPIController extends AppBaseController
{
    /**
     * Display a listing of the Program.
     * GET|HEAD /programs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $query = Program::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }

        $programs = $query->get();

        return $this->sendResponse(ProgramResource::collection($programs), 'Programs retrieved successfully');
    }

    /**
     * Store a newly created Program in storage.
     * POST /programs
     *
     * @param CreateProgramAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProgramAPIRequest $request)
    {
        $input = $request->all();

        /** @var Program $program */
        $program = Program::create($input);

        return $this->sendResponse(new ProgramResource($program), 'Program saved successfully');
    }

    /**
     * Display the specified Program.
     * GET|HEAD /programs/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Program $program */
        $program = Program::find($id);

        if (empty($program)) {
            return $this->sendError('Program not found');
        }

        return $this->sendResponse(new ProgramResource($program), 'Program retrieved successfully');
    }

    /**
     * Update the specified Program in storage.
     * PUT/PATCH /programs/{id}
     *
     * @param int $id
     * @param UpdateProgramAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProgramAPIRequest $request)
    {
        /** @var Program $program */
        $program = Program::find($id);

        if (empty($program)) {
            return $this->sendError('Program not found');
        }

        $program->fill($request->all());
        $program->save();

        return $this->sendResponse(new ProgramResource($program), 'Program updated successfully');
    }

    /**
     * Remove the specified Program from storage.
     * DELETE /programs/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Program $program */
        $program = Program::find($id);

        if (empty($program)) {
            return $this->sendError('Program not found');
        }

        $program->delete();

        return $this->sendSuccess('Program deleted successfully');
    }
}
