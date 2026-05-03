@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('certificate_corrections.index') }}" class="btn btn-secondary btn-sm me-3">← Back</a>
        <h5 class="fw-bold mb-0">Correction Application #{{ $correction->id }}</h5>
    </div>
    <div class="row g-3">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Correction Details</div>
                <div class="card-body" style="font-size:13px;">
                    <p><strong>Student:</strong> {{ optional($correction->student)->candidate_name }}</p>
                    <p><strong>Registration No.:</strong> {{ optional($correction->student)->registration_number }}</p>
                    <p><strong>Applied:</strong> {{ optional($correction->application_date)->format('d-m-Y') }}</p>
                    <p><strong>Reason:</strong> {{ $correction->reason }}</p>
                    <hr>
                    <h6 class="fw-semibold">Fields to be Corrected</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light"><tr><th>Field</th><th>Old Value</th><th>New Value</th></tr></thead>
                        <tbody>
                            @foreach($correction->correction_fields ?? [] as $field => $diff)
                            <tr>
                                <td>{{ $field }}</td>
                                <td class="text-danger">{{ $diff['old'] ?? '—' }}</td>
                                <td class="text-success fw-semibold">{{ $diff['new'] ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(!empty($correction->supporting_documents))
                    <h6 class="fw-semibold mt-3">Supporting Documents</h6>
                    @foreach($correction->supporting_documents as $doc)
                        <a href="{{ asset($doc) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-1"><i class="icon im im-icon-File me-1"></i>View Document</a>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Approval Status</div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="fw-semibold">Current Status: </span>
                        @php $colors=['pending'=>'secondary','controller_approved'=>'info','chairman_approved'=>'success','rejected'=>'danger']; @endphp
                        <span class="badge bg-{{ $colors[$correction->status]??'secondary' }}">{{ ucwords(str_replace('_',' ',$correction->status)) }}</span>
                    </div>
                    @if($correction->controller)
                    <p class="small"><strong>Controller:</strong> {{ $correction->controller->name }}<br>
                    <em>{{ $correction->controller_remarks }}</em><br>
                    <small class="text-muted">{{ optional($correction->controller_approved_at)->format('d-m-Y H:i') }}</small></p>
                    @endif
                    @if($correction->chairman)
                    <p class="small"><strong>Chairman:</strong> {{ $correction->chairman->name }}<br>
                    <em>{{ $correction->chairman_remarks }}</em><br>
                    <small class="text-muted">{{ optional($correction->chairman_approved_at)->format('d-m-Y H:i') }}</small></p>
                    @endif
                    <hr>
                    @if($correction->status === 'pending' && (can('assessment_controller')||can('chairman')))
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="doControllerAction({{ $correction->id }},'approve')">Approve (Controller)</button>
                        <button class="btn btn-danger btn-sm" onclick="doControllerAction({{ $correction->id }},'reject')">Reject</button>
                    </div>
                    @elseif($correction->status === 'controller_approved' && can('chairman'))
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="doChairmanAction({{ $correction->id }},'approve')">Chairman Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="doChairmanAction({{ $correction->id }},'reject')">Reject</button>
                    </div>
                    @endif
                </div>
            </div>
            @if($correction->status === 'chairman_approved')
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body text-center">
                    <p class="text-success fw-semibold mb-2">✓ Correction approved. Certificate updated.</p>
                    <a href="{{ route('certificate_corrections.versions', $correction->student_id) }}" class="btn btn-outline-primary btn-sm">View Certificate History</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<script>
function doControllerAction(id, action){
    var r = prompt('Remarks (optional):',''); if(r===null) return;
    $.post('/certificate-corrections/'+id+'/controller-approve',{_token:'{{ csrf_token() }}',action:action,remarks:r},function(res){if(res.success){alert(res.message);location.reload();}});
}
function doChairmanAction(id, action){
    var r = prompt('Remarks (optional):',''); if(r===null) return;
    $.post('/certificate-corrections/'+id+'/chairman-approve',{_token:'{{ csrf_token() }}',action:action,remarks:r},function(res){if(res.success){alert(res.message);location.reload();}});
}
</script>
@endsection
