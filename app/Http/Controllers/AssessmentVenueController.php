<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAssessmentVenueRequest;
use App\Http\Requests\UpdateAssessmentVenueRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\AssessmentVenue;
use Illuminate\Http\Request;
use Flash;
use Response;

class AssessmentVenueController extends AppBaseController
{
    /**
     * Display a listing of the AssessmentVenue.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var AssessmentVenue $assessmentVenues */
        $assessmentVenues = AssessmentVenue::paginate(10);

        return view('assessment_venues.index')
            ->with('assessmentVenues', $assessmentVenues);
    }

    /**
     * Show the form for creating a new AssessmentVenue.
     *
     * @return Response
     */
    public function create()
    {
        return view('assessment_venues.create');
    }

    /**
     * Store a newly created AssessmentVenue in storage.
     *
     * @param CreateAssessmentVenueRequest $request
     *
     * @return Response
     */
    public function store(CreateAssessmentVenueRequest $request)
    {
        $input = $request->all();

        /** @var AssessmentVenue $assessmentVenue */
        $assessmentVenue = AssessmentVenue::create($input);

        Flash::success('Assessment Venue saved successfully.');

        return redirect(route('assessmentVenues.index'));
    }

    /**
     * Display the specified AssessmentVenue.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AssessmentVenue $assessmentVenue */
        $assessmentVenue = AssessmentVenue::find($id);

        if (empty($assessmentVenue)) {
            Flash::error('Assessment Venue not found');

            return redirect(route('assessmentVenues.index'));
        }

        return view('assessment_venues.show')->with('assessmentVenue', $assessmentVenue);
    }

    /**
     * Show the form for editing the specified AssessmentVenue.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var AssessmentVenue $assessmentVenue */
        $assessmentVenue = AssessmentVenue::find($id);

        if (empty($assessmentVenue)) {
            Flash::error('Assessment Venue not found');

            return redirect(route('assessmentVenues.index'));
        }

        return view('assessment_venues.edit')->with('assessmentVenue', $assessmentVenue);
    }

    /**
     * Update the specified AssessmentVenue in storage.
     *
     * @param int $id
     * @param UpdateAssessmentVenueRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAssessmentVenueRequest $request)
    {
        /** @var AssessmentVenue $assessmentVenue */
        $assessmentVenue = AssessmentVenue::find($id);

        if (empty($assessmentVenue)) {
            Flash::error('Assessment Venue not found');

            return redirect(route('assessmentVenues.index'));
        }

        $assessmentVenue->fill($request->all());
        $assessmentVenue->save();

        Flash::success('Assessment Venue updated successfully.');

        return redirect(route('assessmentVenues.index'));
    }

    /**
     * Remove the specified AssessmentVenue from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var AssessmentVenue $assessmentVenue */
        $assessmentVenue = AssessmentVenue::find($id);

        if (empty($assessmentVenue)) {
            Flash::error('Assessment Venue not found');

            return redirect(route('assessmentVenues.index'));
        }

        $assessmentVenue->delete();

        Flash::success('Assessment Venue deleted successfully.');

        return redirect(route('assessmentVenues.index'));
    }
}
