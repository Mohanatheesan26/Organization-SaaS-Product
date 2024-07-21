@extends('layouts.main')

@section('content')
<section id="locations">
    <h2>Locations</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#locationModal">Add Location</button>
    <div id="location-list" class="mb-3"></div>

    <!-- Modal for Adding Location -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationModalLabel">Add Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="location-form" onsubmit="addLocation(event)">
                        <div class="form-group">
                            <input type="hidden" id="organization-id" value="{{ $organization_id }}">
                            <input type="text" id="location-serial_number" class="form-control" placeholder="Serial Number" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="location-name" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="location-ipv4_address" class="form-control" placeholder="IPv4 Address" required>
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
    const organizationId = {{ $organization_id }};

    function addLocation(event) {
        event.preventDefault();
        const serialNumber = document.getElementById('location-serial_number').value;
        const name = document.getElementById('location-name').value;
        const ipv4Address = document.getElementById('location-ipv4_address').value;

        fetch(`${apiUrl}/locations`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                organization_id: organizationId,
                serial_number: serialNumber,
                name: name,
                ipv4_address: ipv4Address
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            $('#locationModal').modal('hide');
            showMessage('Location added successfully', 'success');
            loadLocations();
        })
        .catch(error => {
            showMessage('Error adding location: ' + error.message, 'danger');
            console.error('Error:', error);
        });
    }

    function loadLocations() {
        fetch(`${apiUrl}/organizations/${organizationId}/locations`)
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            const list = document.getElementById('location-list');
            list.innerHTML = '';
            data.forEach(location => {
                const div = document.createElement('div');
                div.classList.add('card', 'mb-3');
                div.innerHTML = `
                    <div class="card-body">
                        <h5 class="card-title">${location.name}</h5>
                        <p class="card-text">Serial Number: ${location.serial_number}</p>
                        <p class="card-text">IPv4 Address: ${location.ipv4_address}</p>
                        <a href="{{ url('/locations/${location.id}/devices') }}" class="btn btn-secondary">View Devices</a>
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

    document.addEventListener('DOMContentLoaded', loadLocations);
</script>
@endpush

<style>
    .modal-dialog {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }
</style>
