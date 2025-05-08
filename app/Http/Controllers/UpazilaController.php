<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUpazilaRequest;
use App\Http\Requests\UpdateUpazilaRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Flash;
use Response;

class UpazilaController extends AppBaseController
{
    /**
     * Display a listing of the Upazila.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Upazila $upazilas */
        $upazilas = Upazila::select('upazilas.*', 'districts.name_en as district_name')
            ->leftJoin('districts', 'upazilas.dis_id', '=', 'districts.id')
            ->get();
        return view('upazilas.index')
            ->with('upazilas', $upazilas);
    }

    /**
     * Show the form for creating a new Upazila.
     *
     * @return Response
     */
    public function create()
    {
        return view('upazilas.create');
    }

    /**
     * Store a newly created Upazila in storage.
     *
     * @param CreateUpazilaRequest $request
     *
     * @return Response
     */
    public function store(CreateUpazilaRequest $request)
    {
        $input = $request->all();

        /** @var Upazila $upazila */
        $upazila = Upazila::create($input);

        Flash::success('Upazila saved successfully.');

        return redirect(route('upazilas.index'));
    }

    /**
     * Display the specified Upazila.
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
            Flash::error('Upazila not found');

            return redirect(route('upazilas.index'));
        }

        return view('upazilas.show')->with('upazila', $upazila);
    }

    /**
     * Show the form for editing the specified Upazila.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Upazila $upazila */
        $upazila = Upazila::find($id);

        if (empty($upazila)) {
            Flash::error('Upazila not found');

            return redirect(route('upazilas.index'));
        }

        return view('upazilas.edit')->with('upazila', $upazila);
    }

    /**
     * Update the specified Upazila in storage.
     *
     * @param int $id
     * @param UpdateUpazilaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUpazilaRequest $request)
    {
        /** @var Upazila $upazila */
        $upazila = Upazila::find($id);

        if (empty($upazila)) {
            Flash::error('Upazila not found');

            return redirect(route('upazilas.index'));
        }

        $upazila->fill($request->all());
        $upazila->save();

        Flash::success('Upazila updated successfully.');

        return redirect(route('upazilas.index'));
    }

    /**
     * Remove the specified Upazila from storage.
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
            Flash::error('Upazila not found');

            return redirect(route('upazilas.index'));
        }

        $upazila->delete();

        Flash::success('Upazila deleted successfully.');

        return redirect(route('upazilas.index'));
    }
}
