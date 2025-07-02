<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInsatituteRequest;
use App\Http\Requests\UpdateInsatituteRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Insatitute;
use Illuminate\Http\Request;
use Flash;
use Response;

class InsatituteController extends AppBaseController
{
    /**
     * Display a listing of the Insatitute.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Insatitute $insatitutes */
        $insatitutes = Insatitute::paginate(10);

        return view('insatitutes.index')
            ->with('insatitutes', $insatitutes);
    }

    /**
     * Show the form for creating a new Insatitute.
     *
     * @return Response
     */
    public function create()
    {
        return view('insatitutes.create');
    }

    /**
     * Store a newly created Insatitute in storage.
     *
     * @param CreateInsatituteRequest $request
     *
     * @return Response
     */
    public function store(CreateInsatituteRequest $request)
    {
        $input = $request->all();

        /** @var Insatitute $insatitute */
        $insatitute = Insatitute::create($input);

        Flash::success('Insatitute saved successfully.');

        return redirect(route('insatitutes.index'));
    }

    /**
     * Display the specified Insatitute.
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
            Flash::error('Insatitute not found');

            return redirect(route('insatitutes.index'));
        }

        return view('insatitutes.show')->with('insatitute', $insatitute);
    }

    /**
     * Show the form for editing the specified Insatitute.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Insatitute $insatitute */
        $insatitute = Insatitute::find($id);

        if (empty($insatitute)) {
            Flash::error('Insatitute not found');

            return redirect(route('insatitutes.index'));
        }

        return view('insatitutes.edit')->with('insatitute', $insatitute);
    }

    /**
     * Update the specified Insatitute in storage.
     *
     * @param int $id
     * @param UpdateInsatituteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInsatituteRequest $request)
    {
        /** @var Insatitute $insatitute */
        $insatitute = Insatitute::find($id);

        if (empty($insatitute)) {
            Flash::error('Insatitute not found');

            return redirect(route('insatitutes.index'));
        }

        $insatitute->fill($request->all());
        $insatitute->save();

        Flash::success('Insatitute updated successfully.');

        return redirect(route('insatitutes.index'));
    }

    /**
     * Remove the specified Insatitute from storage.
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
            Flash::error('Insatitute not found');

            return redirect(route('insatitutes.index'));
        }

        $insatitute->delete();

        Flash::success('Insatitute deleted successfully.');

        return redirect(route('insatitutes.index'));
    }
}
