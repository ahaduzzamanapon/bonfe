@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="icon im im-icon-Security-Settings me-2"></i> Audit Logs</h5>
    </div>
    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small mb-1">User</label>
                    <select name="user_id" class="form-control form-control-sm">
                        @foreach($users as $k=>$v)<option value="{{ $k }}" @selected(request('user_id')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Action</label>
                    <input type="text" name="action" class="form-control form-control-sm" placeholder="e.g. student.approved" value="{{ request('action') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Model</label>
                    <input type="text" name="model_type" class="form-control form-control-sm" placeholder="e.g. Student" value="{{ request('model_type') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary btn-sm w-100">Filter</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('audit_logs.index') }}" class="btn btn-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:12px;">
                <thead class="table-dark">
                    <tr>
                        <th>#</th><th>User</th><th>Action</th><th>Model</th><th>Record ID</th><th>Description</th><th>IP</th><th>Time</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $i=>$log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ optional($log->user)->name ?? '<em>System</em>' }}</td>
                        <td><code class="small">{{ $log->action }}</code></td>
                        <td><small>{{ class_basename($log->model_type) }}</small></td>
                        <td>{{ $log->model_id ?? '—' }}</td>
                        <td>{{ Str::limit($log->description, 60) }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ optional($log->created_at)->format('d-m-Y H:i') }}</td>
                        <td><a href="{{ route('audit_logs.show', $log->id) }}" class="btn btn-xs btn-outline-secondary">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-3">No audit logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-2 d-flex justify-content-end">{{ $logs->links() }}</div>
</div>
@endsection
