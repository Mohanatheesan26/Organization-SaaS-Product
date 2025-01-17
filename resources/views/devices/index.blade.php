@extends('layouts.main')

@section('content')
<section id="devices">
    <h2>Devices</h2>
    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" data-toggle="modal" data-target="#deviceModal">Add Device</button>
        <button class="btn btn-secondary" onclick="window.location.href='{{ url('/organizations') }}'">Back</button>
    </div>
    <div id="device-list" class="mb-3"></div>

    <!-- Modal for Adding Device -->
    <div class="modal fade" id="deviceModal" tabindex="-1" role="dialog" aria-labelledby="deviceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deviceModalLabel">Add Device</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="device-form" onsubmit="addDevice(event)" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="hidden" id="location-id" value="{{ $location_id }}">
                            <input type="text" id="device-unique_number" class="form-control" placeholder="Unique Number" required>
                        </div>
                        <div class="form-group">
                            <select id="device-type" class="form-control" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="pos">POS</option>
                                <option value="kiosk">Kiosk</option>
                                <option value="digital signage">Digital Signage</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="file" id="device-image" class="form-control" placeholder="Image" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <select id="device-status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
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
                <div class="modal-body" id="messageContent"></div>
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
    const locationId = {{ $location_id }};

    function addDevice(event) {
        event.preventDefault();
        const uniqueNumber = document.getElementById('device-unique_number').value;
        const type = document.getElementById('device-type').value;
        const image = document.getElementById('device-image').files[0];
        const status = document.getElementById('device-status').value;

        const formData = new FormData();
        formData.append('location_id', locationId);
        formData.append('unique_number', uniqueNumber);
        formData.append('type', type);
        formData.append('image', image);
        formData.append('status', status);

        fetch(`${apiUrl}/devices`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            $('#deviceModal').modal('hide');
            showMessage('Device added successfully', 'success');
            loadDevices();
        })
        .catch(error => {
            if (error.error) {
                if (typeof error.error === 'object') {
                    const errorMessages = Object.values(error.error).flat().join(', ');
                    showMessage(`${errorMessages}`, 'danger');
                } else {
                    showMessage(`${error.error}`, 'danger');
                }
            } else {
                showMessage('An unknown error occurred', 'danger');
            }
            console.error('Error:', error);
        });
    }

    function loadDevices() {
        fetch(`${apiUrl}/locations/${locationId}/devices`)
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            const list = document.getElementById('device-list');
            list.innerHTML = '';
            data.forEach(device => {
                const div = document.createElement('div');
                div.classList.add('card', 'mb-3', 'shadow-sm', 'rounded');
                div.innerHTML = `
                    <div class="row no-gutters">
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="text-center">
                                <img src="/storage/${device.image}" alt="Device Image" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <p class="card-text">Unique Number: ${device.unique_number}</p>
                                <p class="card-text">Type: ${device.type}</p>
                                <p class="card-text">Date Created: ${device.date_created}</p>
                                <p class="card-text">Status: ${device.status}</p>
                                <button class="btn btn-danger d-block mt-2" onclick="removeDevice(${locationId}, ${device.id})">Remove Device</button>
                            </div>
                        </div>
                    </div>
                `;
                list.appendChild(div);
            });
        });
    }

    function removeDevice(locationId, deviceId) {
        fetch(`${apiUrl}/devices/${deviceId}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            showMessage('Device removed successfully', 'success');
            loadDevices();
        })
        .catch(error => {
            showMessage('Error removing device: ' + error.message, 'danger');
            console.error('Error:', error);
        });
    }

    function showMessage(message, type) {
        const messageContent = document.getElementById('messageContent');
        messageContent.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        $('#messageModal').modal('show');
    }

    document.addEventListener('DOMContentLoaded', loadDevices);
</script>
@endpush

