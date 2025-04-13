<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChairmanRequest;
use App\Http\Requests\UpdateChairmanRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Chairman;
use Illuminate\Http\Request;
use Flash;
use Response;

class ChairmanController extends AppBaseController
{
    /**
     * Display a listing of the Chairman.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Chairman $chairmen */
        $chairmen = Chairman::all();

        return view('chairmen.index')
            ->with('chairmen', $chairmen);
    }

    /**
     * Show the form for creating a new Chairman.
     *
     * @return Response
     */
    public function create()
    {
        return view('chairmen.create');
    }

    /**
     * Store a newly created Chairman in storage.
     *
     * @param CreateChairmanRequest $request
     *
     * @return Response
     */
    public function store(CreateChairmanRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $folder = 'images/signature';
            $customName = 'signature-'.time();
            $input['signature'] = uploadFile($file, $folder, $customName);
        }

        /** @var Chairman $chairman */
        $chairman = Chairman::create($input);

        Flash::success('Chairman saved successfully.');

        return redirect(route('chairmen.index'));
    }

    /**
     * Display the specified Chairman.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Chairman $chairman */
        $chairman = Chairman::find($id);

        if (empty($chairman)) {
            Flash::error('Chairman not found');

            return redirect(route('chairmen.index'));
        }

        return view('chairmen.show')->with('chairman', $chairman);
    }

    /**
     * Show the form for editing the specified Chairman.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Chairman $chairman */
        $chairman = Chairman::find($id);

        if (empty($chairman)) {
            Flash::error('Chairman not found');

            return redirect(route('chairmen.index'));
        }

        return view('chairmen.edit')->with('chairman', $chairman);
    }

    /**
     * Update the specified Chairman in storage.
     *
     * @param int $id
     * @param UpdateChairmanRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChairmanRequest $request)
    {
        /** @var Chairman $chairman */
        $chairman = Chairman::find($id);

        if (empty($chairman)) {
            Flash::error('Chairman not found');

            return redirect(route('chairmen.index'));
        }

        $input = $request->all();

        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $folder = 'images/signature';
            $customName = 'signature-'.time();
            $input['signature'] = uploadFile($file, $folder, $customName);
        }else{
            unset($input['signature']);
        }

        $chairman->fill($input);
        $chairman->save();

        Flash::success('Chairman updated successfully.');

        return redirect(route('chairmen.index'));
    }

    /**
     * Remove the specified Chairman from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Chairman $chairman */
        $chairman = Chairman::find($id);

        if (empty($chairman)) {
            Flash::error('Chairman not found');

            return redirect(route('chairmen.index'));
        }

        $chairman->delete();

        Flash::success('Chairman deleted successfully.');

        return redirect(route('chairmen.index'));
    }
}
