<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateInsatituteAPIRequest;
use App\Http\Requests\API\UpdateInsatituteAPIRequest;
use App\Models\Insatitute;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\InsatituteResource;
use Response;

/**
 * Class InsatituteController
 * @package App\Http\Controllers\API
 */

class InsatituteAPIController extends AppBaseController
{
    /**
     * Display a listing of the Insatitute.
     * GET|HEAD /insatitutes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $query = Insatitute::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }

        $insatitutes = $query->get();

        return $this->sendResponse(InsatituteResource::collection($insatitutes), 'Insatitutes retrieved successfully');
    }

    /**
     * Store a newly created Insatitute in storage.
     * POST /insatitutes
     *
     * @param CreateInsatituteAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateInsatituteAPIRequest $request)
    {
        $input = $request->all();

        /** @var Insatitute $insatitute */
        $insatitute = Insatitute::create($input);

        return $this->sendResponse(new InsatituteResource($insatitute), 'Insatitute saved successfully');
    }

    /**
     * Display the specified Insatitute.
     * GET|HEAD /insatitutes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Insatitute $insatitute */
        $insatitute = Insatitute::find($id);

        if (empty($insatitute)) {
            return $this->sendError('Insatitute not found');
        }

        return $this->sendResponse(new InsatituteResource($insatitute), 'Insatitute retrieved successfully');
    }

    /**
     * Update the specified Insatitute in storage.
     * PUT/PATCH /insatitutes/{id}
     *
     * @param int $id
     * @param UpdateInsatituteAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInsatituteAPIRequest $request)
    {
        /** @var Insatitute $insatitute */
        $insatitute = Insatitute::find($id);

        if (empty($insatitute)) {
            return $this->sendError('Insatitute not found');
        }

        $insatitute->fill($request->all());
        $insatitute->save();

        return $this->sendResponse(new InsatituteResource($insatitute), 'Insatitute updated successfully');
    }

    /**
     * Remove the specified Insatitute from storage.
     * DELETE /insatitutes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Insatitute $insatitute */
        $insatitute = Insatitute::find($id);

        if (empty($insatitute)) {
            return $this->sendError('Insatitute not found');
        }

        $insatitute->delete();

        return $this->sendSuccess('Insatitute deleted successfully');
    }
}
