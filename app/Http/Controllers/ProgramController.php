<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Program;
use Illuminate\Http\Request;
use Flash;
use Response;

class ProgramController extends AppBaseController
{
    /**
     * Display a listing of the Program.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Program $programs */
        $programs = Program::paginate(10);

        return view('programs.index')
            ->with('programs', $programs);
    }

    /**
     * Show the form for creating a new Program.
     *
     * @return Response
     */
    public function create()
    {
        return view('programs.create');
    }

    /**
     * Store a newly created Program in storage.
     *
     * @param CreateProgramRequest $request
     *
     * @return Response
     */
    public function store(CreateProgramRequest $request)
    {
        $input = $request->all();

        /** @var Program $program */
        $program = Program::create($input);

        Flash::success('Program saved successfully.');

        return redirect(route('programs.index'));
    }

    /**
     * Display the specified Program.
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
            Flash::error('Program not found');

            return redirect(route('programs.index'));
        }

        return view('programs.show')->with('program', $program);
    }

    /**
     * Show the form for editing the specified Program.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Program $program */
        $program = Program::find($id);

        if (empty($program)) {
            Flash::error('Program not found');

            return redirect(route('programs.index'));
        }

        return view('programs.edit')->with('program', $program);
    }

    /**
     * Update the specified Program in storage.
     *
     * @param int $id
     * @param UpdateProgramRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProgramRequest $request)
    {
        /** @var Program $program */
        $program = Program::find($id);

        if (empty($program)) {
            Flash::error('Program not found');

            return redirect(route('programs.index'));
        }

        $program->fill($request->all());
        $program->save();

        Flash::success('Program updated successfully.');

        return redirect(route('programs.index'));
    }

    /**
     * Remove the specified Program from storage.
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
            Flash::error('Program not found');

            return redirect(route('programs.index'));
        }

        $program->delete();

        Flash::success('Program deleted successfully.');

        return redirect(route('programs.index'));
    }
}
