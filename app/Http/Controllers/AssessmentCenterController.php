<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAssessmentCenterRequest;
use App\Http\Requests\UpdateAssessmentCenterRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\AssessmentCenter;
use Illuminate\Http\Request;
use Flash;
use Response;

class AssessmentCenterController extends AppBaseController
{
    /**
     * Display a listing of the AssessmentCenter.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var AssessmentCenter $assessmentCenters */
        $assessmentCenters = AssessmentCenter::paginate(10);

        return view('assessment_centers.index')
            ->with('assessmentCenters', $assessmentCenters);
    }

    /**
     * Show the form for creating a new AssessmentCenter.
     *
     * @return Response
     */
    public function create()
    {
        return view('assessment_centers.create');
    }

    /**
     * Store a newly created AssessmentCenter in storage.
     *
     * @param CreateAssessmentCenterRequest $request
     *
     * @return Response
     */
    public function store(CreateAssessmentCenterRequest $request)
    {
        $input = $request->all();

        /** @var AssessmentCenter $assessmentCenter */
        $assessmentCenter = AssessmentCenter::create($input);

        Flash::success('Assessment Center saved successfully.');

        return redirect(route('assessmentCenters.index'));
    }

    /**
     * Display the specified AssessmentCenter.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AssessmentCenter $assessmentCenter */
        $assessmentCenter = AssessmentCenter::find($id);

        if (empty($assessmentCenter)) {
            Flash::error('Assessment Center not found');

            return redirect(route('assessmentCenters.index'));
        }

        return view('assessment_centers.show')->with('assessmentCenter', $assessmentCenter);
    }

    /**
     * Show the form for editing the specified AssessmentCenter.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var AssessmentCenter $assessmentCenter */
        $assessmentCenter = AssessmentCenter::find($id);

        if (empty($assessmentCenter)) {
            Flash::error('Assessment Center not found');

            return redirect(route('assessmentCenters.index'));
        }

        return view('assessment_centers.edit')->with('assessmentCenter', $assessmentCenter);
    }

    /**
     * Update the specified AssessmentCenter in storage.
     *
     * @param int $id
     * @param UpdateAssessmentCenterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAssessmentCenterRequest $request)
    {
        /** @var AssessmentCenter $assessmentCenter */
        $assessmentCenter = AssessmentCenter::find($id);

        if (empty($assessmentCenter)) {
            Flash::error('Assessment Center not found');

            return redirect(route('assessmentCenters.index'));
        }

        $assessmentCenter->fill($request->all());
        $assessmentCenter->save();

        Flash::success('Assessment Center updated successfully.');

        return redirect(route('assessmentCenters.index'));
    }

    /**
     * Remove the specified AssessmentCenter from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var AssessmentCenter $assessmentCenter */
        $assessmentCenter = AssessmentCenter::find($id);

        if (empty($assessmentCenter)) {
            Flash::error('Assessment Center not found');

            return redirect(route('assessmentCenters.index'));
        }

        $assessmentCenter->delete();

        Flash::success('Assessment Center deleted successfully.');

        return redirect(route('assessmentCenters.index'));
    }
}
