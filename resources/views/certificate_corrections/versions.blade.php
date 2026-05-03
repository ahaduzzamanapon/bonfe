@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('certificate_corrections.index') }}" class="btn btn-secondary btn-sm me-3">← Back</a>
        <h5 class="fw-bold mb-0">Certificate Version History — {{ $student->candidate_name }}</h5>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0" style="font-size:13px;">
                <thead class="table-dark">
                    <tr>
                        <th>Version</th>
                        <th>Issued At</th>
                        <th>Issued By</th>
                        <th>Linked Correction</th>
                        <th>Key Data Snapshot</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($versions as $v)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $loop->last ? 'success' : 'secondary' }}">v{{ $v->version }}</span>
                            @if($loop->last) <small class="text-success ms-1">Current</small> @endif
                        </td>
                        <td>{{ optional($v->issued_at)->format('d-m-Y H:i') }}</td>
                        <td>{{ optional($v->issuer)->name ?? '—' }}</td>
                        <td>
                            @if($v->correction_id)
                                <a href="{{ route('certificate_corrections.show', $v->correction_id) }}">#{{ $v->correction_id }}</a>
                            @else
                                <span class="text-muted">Original</span>
                            @endif
                        </td>
                        <td>
                            @php $snap = $v->snapshot_data ?? []; @endphp
                            <small>
                                <strong>Name:</strong> {{ $snap['candidate_name'] ?? '—' }}<br>
                                <strong>Father:</strong> {{ $snap['father_name'] ?? '—' }}<br>
                                <strong>Mother:</strong> {{ $snap['mother_name'] ?? '—' }}<br>
                                <strong>DOB:</strong> {{ $snap['date_of_birth'] ?? '—' }}<br>
                                <strong>Cert No.:</strong> {{ $snap['certificate_number'] ?? '—' }}
                            </small>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No version history found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
