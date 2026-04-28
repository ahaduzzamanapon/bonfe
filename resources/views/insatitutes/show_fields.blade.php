<tr>
    <th scope="row"><strong>ID:</strong></th>
    <td>{{ $insatitute->id }}</td>
</tr>
<tr>
    <th scope="row"><strong>Institute Name:</strong></th>
    <td>{{ $insatitute->insatitute_name }}</td>
</tr>
<tr>
    <th scope="row"><strong>District:</strong></th>
    <td>{{ $insatitute->district }}</td>
</tr>
<tr>
    <th scope="row"><strong>Address:</strong></th>
    <td>{{ $insatitute->address }}</td>
</tr>
<tr>
    <th scope="row"><strong>Center Reg. No:</strong></th>
    <td>{{ $insatitute->center_reg_num }}</td>
</tr>
<tr>
    <th scope="row"><strong>Status:</strong></th>
    <td>
        <span class="badge {{ $insatitute->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
            {{ $insatitute->status }}
        </span>
    </td>
</tr>
<tr>
    <th scope="row"><strong>Description:</strong></th>
    <td>{{ $insatitute->description }}</td>
</tr>
<tr>
    <th scope="row"><strong>Created At:</strong></th>
    <td>{{ $insatitute->created_at ? $insatitute->created_at->format('d M Y, h:i A') : '—' }}</td>
</tr>
<tr>
    <th scope="row"><strong>Updated At:</strong></th>
    <td>{{ $insatitute->updated_at ? $insatitute->updated_at->format('d M Y, h:i A') : '—' }}</td>
</tr>
