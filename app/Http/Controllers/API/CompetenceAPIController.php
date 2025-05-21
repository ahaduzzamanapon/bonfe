<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCompetenceAPIRequest;
use App\Http\Requests\API\UpdateCompetenceAPIRequest;
use App\Models\Competence;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CompetenceResource;
use Response;

/**
 * Class CompetenceController
 * @package App\Http\Controllers\API
 */

class CompetenceAPIController extends AppBaseController
{
    /**
     * Display a listing of the Competence.
     * GET|HEAD /competences
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $query = Competence::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }

        $competences = $query->get();

        return $this->sendResponse(CompetenceResource::collection($competences), 'Competences retrieved successfully');
    }

    /**
     * Store a newly created Competence in storage.
     * POST /competences
     *
     * @param CreateCompetenceAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCompetenceAPIRequest $request)
    {
        $input = $request->all();

        /** @var Competence $competence */
        $competence = Competence::create($input);

        return $this->sendResponse(new CompetenceResource($competence), 'Competence saved successfully');
    }

    /**
     * Display the specified Competence.
     * GET|HEAD /competences/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Competence $competence */
        $competence = Competence::find($id);

        if (empty($competence)) {
            return $this->sendError('Competence not found');
        }

        return $this->sendResponse(new CompetenceResource($competence), 'Competence retrieved successfully');
    }

    /**
     * Update the specified Competence in storage.
     * PUT/PATCH /competences/{id}
     *
     * @param int $id
     * @param UpdateCompetenceAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompetenceAPIRequest $request)
    {
        /** @var Competence $competence */
        $competence = Competence::find($id);

        if (empty($competence)) {
            return $this->sendError('Competence not found');
        }

        $competence->fill($request->all());
        $competence->save();

        return $this->sendResponse(new CompetenceResource($competence), 'Competence updated successfully');
    }

    /**
     * Remove the specified Competence from storage.
     * DELETE /competences/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Competence $competence */
        $competence = Competence::find($id);

        if (empty($competence)) {
            return $this->sendError('Competence not found');
        }

        $competence->delete();

        return $this->sendSuccess('Competence deleted successfully');
    }
}
