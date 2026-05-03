@extends('layouts.default')
@section('content')
<div class="content">
    @include('flash::message')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0 text-white"><i class="icon im im-icon-Diploma-1 me-2"></i> Certificate Correction Applications</h5>
            <form method="GET" class="d-flex align-items-center" style="gap:8px;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name/reg no." value="{{ request('search') }}" style="width:160px;">
                <select name="status" class="form-control form-control-sm" style="width:160px;">
                    <option value="">All Statuses</option>
                    <option value="pending" @selected(request('status')=='pending')>Pending</option>
                    <option value="controller_approved" @selected(request('status')=='controller_approved')>Controller Approved</option>
                    <option value="chairman_approved" @selected(request('status')=='chairman_approved')>Chairman Approved</option>
                    <option value="rejected" @selected(request('status')=='rejected')>Rejected</option>
                </select>
                <button class="btn btn-sm btn-light text-primary fw-bold">Filter</button>
                <a href="{{ route('certificate_corrections.index') }}" class="btn btn-sm btn-outline-light ms-1">Reset</a>
            </form>
        </section>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>Student</th><th>Reg. No.</th><th>Applied Date</th>
                            <th>Fields to Correct</th><th>Status</th><th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($corrections as $i=>$c)
                        @php
                            $colors = ['pending'=>'secondary','controller_approved'=>'info','chairman_approved'=>'success','rejected'=>'danger'];
                        @endphp
                        <tr>
                            <td>{{ $corrections->firstItem()+$i }}</td>
                            <td>{{ optional($c->student)->candidate_name }}</td>
                            <td>{{ optional($c->student)->registration_number }}</td>
                            <td>{{ optional($c->application_date)->format('d-m-Y') }}</td>
                            <td>
                                @foreach(array_keys($c->correction_fields ?? []) as $f)
                                    <span class="badge bg-light text-dark border">{{ $f }}</span>
                                @endforeach
                            </td>
                            <td><span class="badge bg-{{ $colors[$c->status] ?? 'secondary' }}">{{ ucwords(str_replace('_',' ',$c->status)) }}</span></td>
                            <td class="text-end pe-3">
                                <a href="{{ route('certificate_corrections.show', $c->id) }}" class="btn btn-xs btn-outline-primary">View</a>
                                @if($c->status === 'pending' && (can('assessment_controller') || can('chairman')))
                                    <button class="btn btn-xs btn-outline-success" onclick="controllerAction({{ $c->id }},'approve')">Approve</button>
                                    <button class="btn btn-xs btn-outline-danger" onclick="controllerAction({{ $c->id }},'reject')">Reject</button>
                                @endif
                                @if($c->status === 'controller_approved' && can('chairman'))
                                    <button class="btn btn-xs btn-success" onclick="chairmanAction({{ $c->id }},'approve')">Chairman Approve</button>
                                    <button class="btn btn-xs btn-danger" onclick="chairmanAction({{ $c->id }},'reject')">Reject</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">No correction applications found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-2">{{ $corrections->links() }}</div>
</div>
<script>
function controllerAction(id, action){
    var remarks = prompt('Remarks (optional):','');
    if(remarks === null) return;
    $.post('/certificate-corrections/'+id+'/controller-approve', {_token:'{{ csrf_token() }}', action:action, remarks:remarks},
        function(r){ if(r.success){ alert(r.message); location.reload(); } else { alert(r.message); } });
}
function chairmanAction(id, action){
    var remarks = prompt('Remarks (optional):','');
    if(remarks === null) return;
    $.post('/certificate-corrections/'+id+'/chairman-approve', {_token:'{{ csrf_token() }}', action:action, remarks:remarks},
        function(r){ if(r.success){ alert(r.message); location.reload(); } else { alert(r.message); } });
}
</script>
@endsection
