@extends('layouts.main')

@section('content')
<section id="organizations">
    <h2>Organizations</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#organizationModal">Add Organization</button>
    <div id="organization-list" class="mb-3"></div>
    
    <!-- Modal for Adding Organization -->
    <div class="modal fade" id="organizationModal" tabindex="-1" role="dialog" aria-labelledby="organizationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="organizationModalLabel">Add Organization</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="organization-form" onsubmit="addOrganization(event)">
                        <div class="form-group">
                            <input type="text" id="organization-unique_code" class="form-control" placeholder="Unique Code" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="organization-name" class="form-control" placeholder="Name" required>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="messageContent">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const apiUrl = '{{ url('/api') }}';

    function addOrganization(event) {
        event.preventDefault();
        const uniqueCode = document.getElementById('organization-unique_code').value;
        const name = document.getElementById('organization-name').value;

        fetch(`${apiUrl}/organizations`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ unique_code: uniqueCode, name: name })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            $('#organizationModal').modal('hide');
            showMessage('Organization added successfully', 'success');
            loadOrganizations();
        })
        .catch(error => {
            showMessage('Error adding organization: ' + error.message, 'danger');
            console.error('Error:', error);
        });
    }

    function loadOrganizations() {
        fetch(`${apiUrl}/organizations`)
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            const list = document.getElementById('organization-list');
            list.innerHTML = '';
            data.forEach(org => {
                const div = document.createElement('div');
                div.classList.add('card', 'mb-3');
                div.innerHTML = `
                    <div class="card-body">
                        <h5 class="card-title">${org.name}</h5>
                        <p class="card-text">Code: ${org.unique_code}</p>
                        <button class="btn btn-secondary" onclick="loadLocations(${org.id})">View Locations</button>
                    </div>
                `;
                list.appendChild(div);
            });
        });
    }

    function showMessage(message, type) {
        const messageContent = document.getElementById('messageContent');
        messageContent.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        $('#messageModal').modal('show');
    }

    document.addEventListener('DOMContentLoaded', loadOrganizations);
</script>
@endpush

<style>
    .modal-dialog {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }
</style>
