<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOccupationRequest;
use App\Http\Requests\UpdateOccupationRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Occupation;
use Illuminate\Http\Request;
use Flash;
use Response;

class OccupationController extends AppBaseController
{
    /**
     * Display a listing of the Occupation.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Occupation $occupations */
        $occupations = Occupation::paginate(10);

        return view('occupations.index')
            ->with('occupations', $occupations);
    }

    /**
     * Show the form for creating a new Occupation.
     *
     * @return Response
     */
    public function create()
    {
        return view('occupations.create');
    }

    /**
     * Store a newly created Occupation in storage.
     *
     * @param CreateOccupationRequest $request
     *
     * @return Response
     */
    public function store(CreateOccupationRequest $request)
    {
        $input = $request->all();

        /** @var Occupation $occupation */
        $occupation = Occupation::create($input);

        Flash::success('Occupation saved successfully.');

        return redirect(route('occupations.index'));
    }

    /**
     * Display the specified Occupation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Occupation $occupation */
        $occupation = Occupation::find($id);

        if (empty($occupation)) {
            Flash::error('Occupation not found');

            return redirect(route('occupations.index'));
        }

        return view('occupations.show')->with('occupation', $occupation);
    }

    /**
     * Show the form for editing the specified Occupation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Occupation $occupation */
        $occupation = Occupation::find($id);

        if (empty($occupation)) {
            Flash::error('Occupation not found');

            return redirect(route('occupations.index'));
        }

        return view('occupations.edit')->with('occupation', $occupation);
    }

    /**
     * Update the specified Occupation in storage.
     *
     * @param int $id
     * @param UpdateOccupationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOccupationRequest $request)
    {
        /** @var Occupation $occupation */
        $occupation = Occupation::find($id);

        if (empty($occupation)) {
            Flash::error('Occupation not found');

            return redirect(route('occupations.index'));
        }

        $occupation->fill($request->all());
        $occupation->save();

        Flash::success('Occupation updated successfully.');

        return redirect(route('occupations.index'));
    }

    /**
     * Remove the specified Occupation from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Occupation $occupation */
        $occupation = Occupation::find($id);

        if (empty($occupation)) {
            Flash::error('Occupation not found');

            return redirect(route('occupations.index'));
        }

        $occupation->delete();

        Flash::success('Occupation deleted successfully.');

        return redirect(route('occupations.index'));
    }
}
