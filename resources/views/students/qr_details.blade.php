<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .profile-card {
            border-radius: 1rem;
            overflow: hidden;
        }

        .profile-header {
            background: #8dc542;
            color: white;
            padding: 2rem;
            display: flex;
            align-items: center;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            margin-right: 2rem;
        }

        .badge-custom {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <div class="card profile-card shadow">
            <div class="profile-header">
                <img src="{{ isset($student) && $student->image && file_exists(public_path($student->image)) ? asset($student->image) : asset('images/noimage.jpg') }}" alt="Student Photo">
                <div>

                    <h4 class="mb-1">{{ $student->candidate_name }}</h4>
                    <p class="mb-0">Reg. No: <strong>{{ $student->registration_number }}</strong></p>
                    <p class="mb-0">Occupation: <strong>{{ $student->occupation }}</strong></p>
                    <span class="badge bg-info text-dark badge-custom mt-2">{{ $student->exam_status }}</span>
                </div>
            </div>

            <div class="card-body">
                <!-- Action Buttons -->
                <div class="mb-4 text-end">
                    <button class="btn btn-outline-primary btn-sm" onclick="window.print()"><i
                            class="bi bi-printer"></i> Print</button>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="profileTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info"
                            type="button">Profile Info</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#assessment"
                            type="button">Assessment</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="profileTabsContent">
                    <!-- Profile Info -->
                    <div class="tab-pane fade show active" id="info">
                        <div class="row mb-3">
                            <div class="col-md-6"><strong>Father's Name:</strong> {{ $student->father_name }}</div>
                            <div class="col-md-6"><strong>Mother's Name:</strong> {{ $student->mother_name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6"><strong>NID:</strong> {{ $student->nid }}</div>
                            <div class="col-md-6"><strong>Mobile:</strong> 0{{ $student->mobile_number }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6"><strong>Email:</strong> {{ $student->email }}</div>
                            <div class="col-md-6"><strong>Date of Birth:</strong> {{ $student->date_of_birth }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6"><strong>District:</strong> {{ $student->district }}</div>
                            <div class="col-md-6"><strong>Address:</strong> {{ $student->address }}</div>
                        </div>
                    </div>

                    <!-- Assessment Info -->
                    <div class="tab-pane fade" id="assessment">
                        <div class="row mb-3">
                            <div class="col-md-6"><strong>Assessment Date:</strong> {{ $student->assessment_date }}
                            </div>
                            <div class="col-md-6"><strong>Center ID:</strong> {{ $student->assessment_center }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6"><strong>Center Reg. No:</strong>
                                {{ $student->assessment_center_registration_number }}</div>
                            <div class="col-md-6"><strong>Chairman's Status:</strong> {{ $student->chairmen_status }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><strong>Admin Status:</strong> {{ $student->districts_admin_status }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
