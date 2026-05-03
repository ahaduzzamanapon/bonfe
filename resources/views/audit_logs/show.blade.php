@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('audit_logs.index') }}" class="btn btn-secondary btn-sm me-3">← Back</a>
        <h5 class="fw-bold mb-0">Audit Log #{{ $log->id }}</h5>
    </div>
    <div class="row g-3">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Event Info</div>
                <div class="card-body" style="font-size:13px;">
                    <table class="table table-sm mb-0">
                        <tr><th style="width:40%">Action</th><td><code>{{ $log->action }}</code></td></tr>
                        <tr><th>User</th><td>{{ optional($log->user)->name ?? 'System' }} (ID: {{ $log->user_id ?? '—' }})</td></tr>
                        <tr><th>Model</th><td>{{ class_basename($log->model_type) }} #{{ $log->model_id ?? '—' }}</td></tr>
                        <tr><th>Description</th><td>{{ $log->description }}</td></tr>
                        <tr><th>IP Address</th><td>{{ $log->ip_address }}</td></tr>
                        <tr><th>User Agent</th><td><small>{{ $log->user_agent }}</small></td></tr>
                        <tr><th>Time</th><td>{{ optional($log->created_at)->format('d-m-Y H:i:s') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            @if($log->old_values || $log->new_values)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Data Changes</div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <h6 class="text-danger fw-semibold">Before</h6>
                            @if($log->old_values)
                                <table class="table table-sm table-bordered" style="font-size:11px;">
                                    @foreach($log->old_values as $k=>$v)
                                    <tr><th>{{ $k }}</th><td>{{ is_array($v) ? json_encode($v) : $v }}</td></tr>
                                    @endforeach
                                </table>
                            @else
                                <p class="text-muted small">No previous data recorded.</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success fw-semibold">After</h6>
                            @if($log->new_values)
                                <table class="table table-sm table-bordered" style="font-size:11px;">
                                    @foreach($log->new_values as $k=>$v)
                                    <tr><th>{{ $k }}</th><td>{{ is_array($v) ? json_encode($v) : $v }}</td></tr>
                                    @endforeach
                                </table>
                            @else
                                <p class="text-muted small">No new data recorded.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
