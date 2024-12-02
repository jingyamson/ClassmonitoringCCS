@extends('layouts.app')

@section('title', 'Choose Subjects')

@section('content')

    <div class="container">
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

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="background-color: #fff; border: 1px solid #cddfff;">
                    <div class="card-body">
                        <h5 class="card-title">Choose Subjects</h5>
                        
                        <!-- Import Button -->
                        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                            Add Subject
                        </button>

                        <!-- Form for selecting multiple subjects -->
                        <form action="{{ route('subjects.addSelected') }}" method="POST">
                            @csrf

                            <!-- Table for displaying subjects with checkboxes -->
                            <table class="table table-bordered" style="width: 100%;" id="subjectsTable">
                                <thead>
                                    <tr>
                                        <th class="col-1">Select</th>
                                        <th class="col-1">ID</th>
                                        <th class="col-2">Course Code</th>
                                        <th class="col-3">Name</th>
                                        <th class="col-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                        <tr class="subject-row">
                                            <td>
                                                <!-- Checkbox for selecting a subject -->
                                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}">
                                            </td>
                                           <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subject->course_code }}</td>
                                            <td class="subject-name">{{ $subject->name }}</td>
                                            <td class="text-center">
                                                <!-- Enroll Students Button -->
                                                <button type="button" class="btn btn-warning btn-sm" onclick="showEnrollModal({{ $subject->id }})">
                                                    Enroll Students
                                                </button>

                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSubjectModal" data-id="{{ $subject->id }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Submit Button for the selected subjects -->
                            <button type="submit" class="btn btn-primary">Add Selected Subjects</button>
                        </form>
                    </div>
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
                <form id="enrollForm" action="{{ route('subjects.enrollStudents') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="subject_id" id="subject_id">
                        <div class="mb-3">
                            <label for="section_id" class="form-label">Section</label>
                            <select class="form-select" id="section_id" name="section_id" required>
                                <option value="">Select Section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">BSIT - {{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Enroll</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Other Modals (Add/Delete) -->
    <!-- Add Subject Modal and Delete Modal are included here -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the modal and form
            const deleteSubjectModal = document.getElementById('deleteSubjectModal');
            const deleteSubjectForm = document.getElementById('deleteSubjectForm');

            // Add event listener for when the modal is shown
            deleteSubjectModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const subjectId = button.getAttribute('data-id');
                deleteSubjectForm.action = `/subjects/destroy/${subjectId}`;
            });
        });

        // Show Enroll Students Modal
        function showEnrollModal(subjectId) {
            document.getElementById('subject_id').value = subjectId;
            var enrollModal = new bootstrap.Modal(document.getElementById('enrollModal'));
            enrollModal.show();
        }
    </script>

@endsection
