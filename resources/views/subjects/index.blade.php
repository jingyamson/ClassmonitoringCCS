@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
    <style>
        body {
            background-color: #F6F9FF;
        }

        .card {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .table {
            background-color: #ffffff;
        }

        .table th {
            background-color: #E3A833;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .btn-primary {
            background-color: #E3A833;
            border-color: #E3A833;
        }

        .btn-danger {
            background-color: #ff4d4d;
            border-color: #ff4d4d;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Success/Error Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card" style="background-color: #fff; border: 1px solid #cddfff;">
                    <div class="card-header">
                        <h5 class="card-title text-center">Subjects</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <!-- Choose Subjects and Enroll Students Buttons Side by Side -->
                            <a href="{{ route('subjects.choose') }}" class="btn btn-primary me-2">
                                Choose Subjects
                            </a>
                        </div>
                    
                        <!-- Subject Table -->
                        <table class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="col-1">ID</th>
                                    <th class="col-2">Course Code</th>
                                    <th class="col-3">Name</th>
                                    <th class="col-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->course_code }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td class="text-center" style="display: flex; justify-content: space-between; ">
                                            <!-- <button type="button" class="btn btn-sm btn-primary" onclick="showEditModal({{ json_encode($subject) }})">Edit</button> -->
                                            
                                            <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal({{ $subject->id }})">Delete</button>
                                            <button class="btn btn-sm btn-success dropdown-toggle ddother" type="button" id="otherDropdown_{{ $subject->subject_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Other
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="otherDropdown_{{ $subject->subject_id }}">
                                                <!-- <li><a class="dropdown-item" href=>Check Attendance</a></li> -->
                                                <li><a class="dropdown-item" href="{{ route('students.shuffle', ['subject_id' => $subject->subject_id]) }}">Manage Recitation</a></li>
                                                <li><a class="dropdown-item" href="{{ route('class-card.index', ['subject_id' => $subject->subject_id]) }}">Record Grades</a></li>
                                            </ul>
                                            <button class="btn btn-sm btn-success dropdown-toggle ddexport" type="button" id="exportDropdown_{{ $subject->subject_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Export
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="exportDropdown_{{ $subject->subject_id }}">
                                                <li><a class="dropdown-item" href="{{ route('students.exportPrelim', ['subject_id' => $subject->subject_id]) }}">Export Prelim</a></li>
                                                <li><a class="dropdown-item" href="{{ route('students.exportMidterm', ['subject_id' => $subject->subject_id]) }}">Export Midterm</a></li>
                                                <li><a class="dropdown-item" href="{{ route('students.exportFinals', ['subject_id' => $subject->subject_id]) }}">Export Finals</a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteSelectedSubjectModal" tabindex="-1" aria-labelledby="deleteSelectedSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSelectedSubjectModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this subject? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <form id="deleteSelectedSubjectForm" style="width: 100%;" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="row">
                            <div class="col-6 text-start">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Enroll Students Modal -->
    <div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollModalLabel">Enroll Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="enrollForm" action="{{ route('subjects.enrollStudents') }}" method="POST">
                        @csrf
                        <input type="hidden" name="subject_id" id="subject_id">
                        
                        <div class="mb-3">
                            <label for="section_id" class="form-label">Section</label>
                            <select class="form-select" id="section_id" name="section_id" required>
                                <option value="">Select Section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">BSIT - {{ $section->name }}{{ $section->description }}</option>
                                @endforeach 
                            </select>
                        </div>
                        
                </div>
                <div class="modal-footer">
                    <div class="row" style="width: 100%;">
                        <div class="col-6 text-start">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <div class="col-6 text-end">
                            <button type="submit" class="btn btn-primary">Enroll</button>    
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    // Add event listener for the "Other" dropdown buttons
    var otherDropdownButtons = document.querySelectorAll('.ddother');
    otherDropdownButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var bootstrapDropdown = new bootstrap.Dropdown(button);
            bootstrapDropdown.toggle();  // Toggle the dropdown menu
        });
    });

    // Add event listener for the "Export" dropdown buttons
    var exportDropdownButtons = document.querySelectorAll('.ddexport');
    exportDropdownButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var bootstrapDropdown = new bootstrap.Dropdown(button);
            bootstrapDropdown.toggle();  // Toggle the dropdown menu
        });
    });
});
    </script>

    <script>
    // Show Enroll Modal
    function showEnrollModal(subjectId) {
        const enrollForm = document.getElementById('enrollForm');
        const subjectIdInput = document.getElementById('subject_id');
        subjectIdInput.value = subjectId;

        var enrollModal = new bootstrap.Modal(document.getElementById('enrollModal'));
        enrollModal.show();
    }

    // Handle Year Level change event to dynamically load sections
    document.getElementById('year_level').addEventListener('change', function() {
        let yearLevel = this.value;
        const sectionDropdown = document.getElementById('section');

        // Clear the section dropdown before populating
        sectionDropdown.innerHTML = '<option value="">Select Section</option>';

        // Only fetch sections if a valid year level is selected
        if (yearLevel) {
            fetch(`/sections/by-year/${yearLevel}`)
                .then(response => response.json())
                .then(data => {
                    if (data.sections.length > 0) {
                        data.sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.textContent = section.name;
                            sectionDropdown.appendChild(option);
                        });
                    } else {
                        // Optionally handle the case where no sections are found
                        const noSectionOption = document.createElement('option');
                        noSectionOption.textContent = 'No sections available';
                        sectionDropdown.appendChild(noSectionOption);
                    }
                })
                .catch(error => console.error('Error fetching sections:', error));
        }
    });

    // Show Delete Modal and set the form action dynamically
    function showDeleteModal(Id) {
        const formAction = `/subjects/selected/${Id}`;
        const deleteForm = document.getElementById('deleteSelectedSubjectForm');
        deleteForm.action = formAction;

        // Show the delete confirmation modal
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteSelectedSubjectModal'));
        deleteModal.show();
    }
    </script>

@endsection

