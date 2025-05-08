<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUpazilaAPIRequest;
use App\Http\Requests\API\UpdateUpazilaAPIRequest;
use App\Models\Upazila;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UpazilaResource;
use Response;

/**
 * Class UpazilaController
 * @package App\Http\Controllers\API
 */

class UpazilaAPIController extends AppBaseController
{
    /**
     * Display a listing of the Upazila.
     * GET|HEAD /upazilas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $query = Upazila::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }

        $upazilas = $query->get();

        return $this->sendResponse(UpazilaResource::collection($upazilas), 'Upazilas retrieved successfully');
    }

    /**
     * Store a newly created Upazila in storage.
     * POST /upazilas
     *
     * @param CreateUpazilaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUpazilaAPIRequest $request)
    {
        $input = $request->all();

        /** @var Upazila $upazila */
        $upazila = Upazila::create($input);

        return $this->sendResponse(new UpazilaResource($upazila), 'Upazila saved successfully');
    }

    /**
     * Display the specified Upazila.
     * GET|HEAD /upazilas/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Upazila $upazila */
        $upazila = Upazila::find($id);

        if (empty($upazila)) {
            return $this->sendError('Upazila not found');
        }

        return $this->sendResponse(new UpazilaResource($upazila), 'Upazila retrieved successfully');
    }

    /**
     * Update the specified Upazila in storage.
     * PUT/PATCH /upazilas/{id}
     *
     * @param int $id
     * @param UpdateUpazilaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUpazilaAPIRequest $request)
    {
        /** @var Upazila $upazila */
        $upazila = Upazila::find($id);

        if (empty($upazila)) {
            return $this->sendError('Upazila not found');
        }

        $upazila->fill($request->all());
        $upazila->save();

        return $this->sendResponse(new UpazilaResource($upazila), 'Upazila updated successfully');
    }

    /**
     * Remove the specified Upazila from storage.
     * DELETE /upazilas/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Upazila $upazila */
        $upazila = Upazila::find($id);

        if (empty($upazila)) {
            return $this->sendError('Upazila not found');
        }

        $upazila->delete();

        return $this->sendSuccess('Upazila deleted successfully');
    }
}
