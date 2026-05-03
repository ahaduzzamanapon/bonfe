@extends('layouts.default')
@section('content')
<div class="content">
    @include('flash::message')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0 text-white"><i class="icon im im-icon-Student-Hat me-2"></i> Re-Assessment Management</h5>
            <form method="GET" class="d-flex align-items-center" style="gap:8px;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or reg no." value="{{ request('search') }}" style="width:200px;">
                <button class="btn btn-sm btn-light text-primary fw-bold">Search</button>
            </form>
        </section>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Registration No.</th>
                            <th>District</th>
                            <th>Occupation</th>
                            <th>Exam Status</th>
                            <th>Re-Assessment Status</th>
                            <th>Attempt</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $i=>$student)
                        @php $ra = $student->reassessments->first(); @endphp
                        <tr>
                            <td>{{ $students->firstItem() + $i }}</td>
                            <td>{{ $student->candidate_name }}<br><small class="text-muted">{{ $student->candidate_name_bn }}</small></td>
                            <td>{{ $student->registration_number }}</td>
                            <td>{{ optional($student->district)->name_en }}</td>
                            <td>{{ optional($student->occupation)->title }}</td>
                            <td><span class="badge bg-danger">NYC / Fail</span></td>
                            <td>
                                @if($ra)
                                    @php
                                        $colors = ['pending'=>'secondary','scheduled'=>'info','result_entered'=>'warning','waiting_controller'=>'warning','waiting_chairman'=>'primary','approved'=>'success','rejected'=>'danger'];
                                    @endphp
                                    <span class="badge bg-{{ $colors[$ra->status] ?? 'secondary' }}">{{ ucwords(str_replace('_',' ',$ra->status)) }}</span>
                                @else
                                    <span class="text-muted">No application</span>
                                @endif
                            </td>
                            <td>{{ $ra ? $ra->attempt_number : '—' }}</td>
                            <td class="text-end pe-3">
                                @if(!$ra || $ra->status === 'rejected')
                                    @if(can('apply_reassessment') || can('district_admin'))
                                    <button class="btn btn-xs btn-outline-primary" onclick="applyReassessment({{ $student->id }})">Apply</button>
                                    @endif
                                @elseif($ra->status === 'pending')
                                    @if(can('schedule_reassessment') || can('assessment_controller'))
                                    <button class="btn btn-xs btn-outline-info" onclick="scheduleRA({{ $ra->id }})">Schedule</button>
                                    @endif
                                @elseif($ra->status === 'scheduled')
                                    @if(can('enter_reassessment_result') || can('assessment_centers_controller'))
                                    <button class="btn btn-xs btn-outline-warning" onclick="enterRAResult({{ $ra->id }})">Enter Result</button>
                                    @endif
                                @elseif($ra->status === 'waiting_chairman' && can('chairman'))
                                    <button class="btn btn-xs btn-outline-success" onclick="chairmanApproveRA({{ $ra->id }})">Approve</button>
                                @elseif($ra->status === 'approved' && $ra->exam_status === 'Passed')
                                    <a href="{{ route('reassessments.certificate', $ra->id) }}" class="btn btn-xs btn-outline-success" target="_blank">Certificate</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-3">No NYC students found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-2">{{ $students->links() }}</div>
</div>

{{-- Apply Modal --}}
<div class="modal fade" id="applyRAModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Apply Re-Assessment</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <input type="hidden" id="ra_student_id">
            <div class="form-group"><label>Reason (optional)</label><textarea id="ra_reason" class="form-control" rows="3"></textarea></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary btn-sm" onclick="submitApplyRA()">Submit Application</button>
        </div>
    </div></div>
</div>

{{-- Schedule Modal --}}
<div class="modal fade" id="scheduleRAModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Schedule Re-Assessment</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <input type="hidden" id="ra_id_schedule">
            <div class="form-group mb-2"><label>Scheduled Date</label><input type="date" id="ra_scheduled_date" class="form-control"></div>
            <div class="form-group"><label>Assessment Center</label>
                <select id="ra_center_id" class="form-control">
                    <option value="">Select Center</option>
                    @foreach($centers as $id=>$name)<option value="{{ $id }}">{{ $name }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary btn-sm" onclick="submitScheduleRA()">Schedule</button>
        </div>
    </div></div>
</div>

{{-- Enter Result Modal --}}
<div class="modal fade" id="resultRAModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Enter Re-Assessment Result</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <input type="hidden" id="ra_id_result">
            <div class="form-group mb-2"><label>Result</label>
                <select id="ra_exam_status" class="form-control">
                    <option value="Passed">Competent (Passed)</option>
                    <option value="Fail">Not Yet Competent (Fail)</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>
            <div class="form-group"><label>Result Sheet</label><input type="file" id="ra_result_sheet" class="form-control"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary btn-sm" onclick="submitResultRA()">Save Result</button>
        </div>
    </div></div>
</div>

<script>
function applyReassessment(studentId){ $('#ra_student_id').val(studentId); $('#applyRAModal').modal('show'); }
function scheduleRA(raId){ $('#ra_id_schedule').val(raId); $('#scheduleRAModal').modal('show'); }
function enterRAResult(raId){ $('#ra_id_result').val(raId); $('#resultRAModal').modal('show'); }

function submitApplyRA(){
    $.post('{{ route("reassessments.apply") }}', {
        _token:'{{ csrf_token() }}', student_id:$('#ra_student_id').val(), reason:$('#ra_reason').val()
    }, function(r){ if(r.success){ alert(r.message); location.reload(); } else { alert(r.message); } });
}
function submitScheduleRA(){
    $.post('{{ route("reassessments.schedule") }}', {
        _token:'{{ csrf_token() }}', reassessment_id:$('#ra_id_schedule').val(),
        scheduled_date:$('#ra_scheduled_date').val(), scheduled_center_id:$('#ra_center_id').val()
    }, function(r){ if(r.success){ alert(r.message); location.reload(); } });
}
function submitResultRA(){
    var fd = new FormData();
    fd.append('_token','{{ csrf_token() }}');
    fd.append('reassessment_id',$('#ra_id_result').val());
    fd.append('exam_status',$('#ra_exam_status').val());
    if($('#ra_result_sheet')[0].files[0]) fd.append('exam_result_sheet',$('#ra_result_sheet')[0].files[0]);
    $.ajax({ url:'{{ route("reassessments.enter_result") }}', type:'POST', data:fd, processData:false, contentType:false,
        success:function(r){ if(r.success){ alert(r.message); location.reload(); } }
    });
}
function chairmanApproveRA(raId){
    if(!confirm('Approve this re-assessment?')) return;
    $.post('{{ route("reassessments.chairman_approve") }}', {
        _token:'{{ csrf_token() }}', reassessment_ids:[raId]
    }, function(r){ if(r.success){ alert(r.message); location.reload(); } });
}
</script>
@endsection
