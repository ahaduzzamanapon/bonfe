<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompetenceRequest;
use App\Http\Requests\UpdateCompetenceRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Competence;
use Illuminate\Http\Request;
use Flash;
use Response;

class CompetenceController extends AppBaseController
{
    /**
     * Display a listing of the Competence.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Competence $competences */
        $competences = Competence::select('competences.*', 'occupations.title as occupation_title')
            ->join('occupations', 'competences.occupation_id', '=', 'occupations.id')
            ->get();

        return view('competences.index')
            ->with('competences', $competences);
    }

    /**
     * Show the form for creating a new Competence.
     *
     * @return Response
     */
    public function create()
    {
        return view('competences.create');
    }

    /**
     * Store a newly created Competence in storage.
     *
     * @param CreateCompetenceRequest $request
     *
     * @return Response
     */
    public function store(CreateCompetenceRequest $request)
    {
        $input = $request->all();

        /** @var Competence $competence */
        $competence = Competence::create($input);

        Flash::success('Competence saved successfully.');

        return redirect(route('competences.index'));
    }

    /**
     * Display the specified Competence.
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
            Flash::error('Competence not found');

            return redirect(route('competences.index'));
        }

        return view('competences.show')->with('competence', $competence);
    }

    /**
     * Show the form for editing the specified Competence.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Competence $competence */
        $competence = Competence::find($id);

        if (empty($competence)) {
            Flash::error('Competence not found');

            return redirect(route('competences.index'));
        }

        return view('competences.edit')->with('competence', $competence);
    }

    /**
     * Update the specified Competence in storage.
     *
     * @param int $id
     * @param UpdateCompetenceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompetenceRequest $request)
    {
        /** @var Competence $competence */
        $competence = Competence::find($id);

        if (empty($competence)) {
            Flash::error('Competence not found');

            return redirect(route('competences.index'));
        }

        $competence->fill($request->all());
        $competence->save();

        Flash::success('Competence updated successfully.');

        return redirect(route('competences.index'));
    }

    /**
     * Remove the specified Competence from storage.
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
            Flash::error('Competence not found');

            return redirect(route('competences.index'));
        }

        $competence->delete();

        Flash::success('Competence deleted successfully.');

        return redirect(route('competences.index'));
    }
}
