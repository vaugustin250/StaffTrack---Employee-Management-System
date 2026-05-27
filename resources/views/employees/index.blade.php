@extends('app')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
    <div>
        <p class="text-uppercase text-muted fw-semibold small mb-1">StaffTrack</p>
        <h1 class="h3 mb-1">Employee Management</h1>
        <p class="text-muted mb-0">Manage employee records with AJAX, DataTables, Bootstrap 5, and SweetAlert2.</p>
    </div>
    <button type="button" class="btn btn-primary" id="addEmployeeBtn">
        Add Employee
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <span>Total Employees</span>
            <strong id="statTotal">0</strong>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-active">
            <span>Active</span>
            <strong id="statActive">0</strong>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-muted">
            <span>Inactive</span>
            <strong id="statInactive">0</strong>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-dept">
            <span>Departments</span>
            <strong id="statDepartments">0</strong>
        </div>
    </div>
</div>

<section class="employee-panel">
    <div class="row g-3 align-items-end mb-3">
        <div class="col-md-4 col-lg-3">
            <label for="departmentFilter" class="form-label">Department</label>
            <select class="form-select" id="departmentFilter">
                <option value="">All Departments</option>
                @foreach ($departments as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle w-100" id="employeesTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Salary</th>
                <th>Join Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>
    </div>
</section>

<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" id="employeeForm">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="employeeId">
                <div class="alert alert-danger d-none" id="formErrors"></div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" list="departmentOptions" required>
                        <datalist id="departmentOptions">
                            @foreach ($departments as $department)
                                <option value="{{ $department }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-6">
                        <label for="salary" class="form-label">Salary</label>
                        <input type="number" min="0" step="0.01" class="form-control" id="salary" name="salary" required>
                    </div>
                    <div class="col-md-6">
                        <label for="join_date" class="form-label">Join Date</label>
                        <input type="date" class="form-control" id="join_date" name="join_date" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveEmployeeBtn">Save Employee</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    var employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));
    var table = $('#employeesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/employees/data',
            data: function (data) {
                data.department = $('#departmentFilter').val();
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'department', name: 'department'},
            {data: 'salary', name: 'salary'},
            {data: 'join_date', name: 'join_date'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        dom: "<'row g-3 align-items-center mb-2'<'col-md-6'B><'col-md-6'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row g-3 align-items-center mt-2'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            {extend: 'csvHtml5', text: 'Export CSV', className: 'btn btn-outline-secondary btn-sm'},
            {extend: 'pdfHtml5', text: 'Export PDF', className: 'btn btn-outline-secondary btn-sm'}
        ]
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function refreshStats() {
        $.get('/employees/stats', function (stats) {
            $('#statTotal').text(stats.total);
            $('#statActive').text(stats.active);
            $('#statInactive').text(stats.inactive);
            $('#statDepartments').text(stats.departments);
        });
    }

    function resetForm() {
        $('#employeeForm')[0].reset();
        $('#employeeId').val('');
        $('#formErrors').addClass('d-none').empty();
        $('#employeeModalLabel').text('Add Employee');
    }

    function renderErrors(errors) {
        var messages = [];
        $.each(errors, function (field, fieldMessages) {
            messages = messages.concat(fieldMessages);
        });
        $('#formErrors').removeClass('d-none').html(messages.join('<br>'));
    }

    function ensureDepartmentOption(department) {
        if (!department || $('#departmentFilter option[value="' + department + '"]').length) {
            return;
        }

        $('#departmentFilter').append($('<option>', {
            value: department,
            text: department
        }));
        $('#departmentOptions').append($('<option>', {
            value: department
        }));
    }

    $('#departmentFilter').on('change', function () {
        table.ajax.reload();
    });

    $('#addEmployeeBtn').on('click', function () {
        resetForm();
        employeeModal.show();
    });

    $('#employeesTable').on('click', '.edit-employee', function () {
        resetForm();
        var employeeId = $(this).data('id');

        $.get('/employees/' + employeeId, function (employee) {
            $('#employeeModalLabel').text('Edit Employee');
            $('#employeeId').val(employee.id);
            $('#name').val(employee.name);
            $('#email').val(employee.email);
            $('#department').val(employee.department);
            $('#salary').val(employee.salary);
            $('#join_date').val(employee.join_date);
            $('#status').val(employee.status);
            employeeModal.show();
        });
    });

    $('#employeeForm').on('submit', function (event) {
        event.preventDefault();

        var employeeId = $('#employeeId').val();
        var url = employeeId ? '/employees/' + employeeId : '/employees';
        var method = employeeId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function (response) {
                ensureDepartmentOption(response.employee.department);
                employeeModal.hide();
                table.ajax.reload(null, false);
                refreshStats();
                Swal.fire('Success', response.message, 'success');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    renderErrors(xhr.responseJSON.errors);
                    return;
                }
                Swal.fire('Error', 'Something went wrong while saving the employee.', 'error');
            }
        });
    });

    $('#employeesTable').on('click', '.delete-employee', function () {
        var employeeId = $(this).data('id');

        Swal.fire({
            title: 'Delete employee?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: '#dc3545'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '/employees/' + employeeId,
                type: 'DELETE',
                success: function (response) {
                    table.ajax.reload(null, false);
                    refreshStats();
                    Swal.fire('Deleted', response.message, 'success');
                },
                error: function () {
                    Swal.fire('Error', 'Employee could not be deleted.', 'error');
                }
            });
        });
    });

    refreshStats();
});
</script>
@endpush
