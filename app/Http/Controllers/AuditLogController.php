<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('id', 'desc');

        if ($request->filled('user_id'))    $query->where('user_id', $request->user_id);
        if ($request->filled('action'))     $query->where('action', 'like', '%'.$request->action.'%');
        if ($request->filled('model_type')) $query->where('model_type', 'like', '%'.$request->model_type.'%');
        if ($request->filled('date_from'))  $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))    $query->whereDate('created_at', '<=', $request->date_to);

        $logs  = $query->paginate(50)->withQueryString();
        $users = \App\Models\User::pluck('name', 'id')->prepend('All Users', '');

        return view('audit_logs.index', compact('logs', 'users'));
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return view('audit_logs.show', compact('log'));
    }
}
