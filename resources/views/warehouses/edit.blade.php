@extends('layouts.app', ['title' => $title, 'subTitle' => $subTitle])

@push('css')
<style>
    #map {
        height: 400px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
    .map-container {
        margin-top: 15px;
    }
    .search-container {
        margin-bottom: 15px;
    }
    .search-container input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header">Edit Warehouse</div>
            <div class="card-body">
                <form id="warehouseForm" method="POST" action="{{ route('warehouses.update', encrypt($warehouse->id)) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $warehouse->code) }}" required>
                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $warehouse->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $warehouse->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $warehouse->contact_number) }}" required>
                                @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address_line_1" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('address_line_1') is-invalid @enderror" id="address_line_1" name="address_line_1" value="{{ old('address_line_1', $warehouse->address_line_1) }}" required>
                        @error('address_line_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="address_line_2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control @error('address_line_2') is-invalid @enderror" id="address_line_2" name="address_line_2" value="{{ old('address_line_2', $warehouse->address_line_2) }}">
                        @error('address_line_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="country_id" class="form-label">Country <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('country_id') is-invalid @enderror" id="country_id" name="country_id" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $id => $name)
                                        <option value="{{ $id }}" {{ old('country_id', $warehouse->country_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="state_id" class="form-label">State <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('state_id') is-invalid @enderror" id="state_id" name="state_id" required>
                                    <option value="">Select State</option>
                                </select>
                                @error('state_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city_id" class="form-label">City <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="zipcode" class="form-label">Zipcode <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" value="{{ old('zipcode', $warehouse->zipcode) }}" required>
                                @error('zipcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fax" class="form-label">Fax</label>
                                <input type="text" class="form-control @error('fax') is-invalid @enderror" id="fax" name="fax" value="{{ old('fax', $warehouse->fax) }}">
                                @error('fax')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Map Location</label>
                        <div class="search-container">
                            <input type="text" id="address_search" placeholder="Search for address..." class="form-control">
                        </div>
                        <div class="map-container">
                            <div id="map"></div>
                        </div>
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $warehouse->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $warehouse->longitude) }}">
                        <small class="form-text text-muted">Click on the map to set the exact location coordinates.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Warehouse</button>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/js/jquery-validate.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
let map;
let marker;
let geocoder;

function initMap() {
    const initialLat = {{ $warehouse->latitude ?? '40.7128' }};
    const initialLng = {{ $warehouse->longitude ?? '-74.0060' }};
    
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: initialLat, lng: initialLng },
        zoom: 15,
    });

    geocoder = new google.maps.Geocoder();
    marker = new google.maps.Marker({
        position: { lat: initialLat, lng: initialLng },
        map: map,
        draggable: true,
    });

    map.addListener("click", (e) => {
        placeMarkerAndPanTo(e.latLng, map);
    });

    marker.addListener("dragend", () => {
        const position = marker.getPosition();
        document.getElementById("latitude").value = position.lat();
        document.getElementById("longitude").value = position.lng();
    });
}

function placeMarkerAndPanTo(latLng, map) {
    marker.setPosition(latLng);
    map.panTo(latLng);
    document.getElementById("latitude").value = latLng.lat();
    document.getElementById("longitude").value = latLng.lng();
}

$(document).ready(function() {

    $('#country_id, #state_id, #city_id').select2({
        placeholder: 'Select option',
        width: '100%'
    });

    $('#country_id').on('change', function() {
        let countryId = $(this).val();
        $('#state_id').empty().append('<option value="">Select State</option>');
        $('#city_id').empty().append('<option value="">Select City</option>');
        
        if (countryId) {
            $.ajax({
                url: '{{ route("state-list") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    country_id: countryId,
                    searchQuery: ''
                },
                success: function(response) {
                    if (response.items && response.items.length > 0) {
                        $.each(response.items, function(index, item) {
                            $('#state_id').append('<option value="' + item.id + '">' + item.text + '</option>');
                        });
                    }
                }
            });
        }
    });

    $('#state_id').on('change', function() {
        let stateId = $(this).val();
        $('#city_id').empty().append('<option value="">Select City</option>');
        
        if (stateId) {
            $.ajax({
                url: '{{ route("city-list") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    state_id: stateId,
                    searchQuery: ''
                },
                success: function(response) {
                    if (response.items && response.items.length > 0) {
                        $.each(response.items, function(index, item) {
                            $('#city_id').append('<option value="' + item.id + '">' + item.text + '</option>');
                        });
                    }
                }
            });
        }
    });

    function loadStatesAndCities() {
        let countryId = $('#country_id').val();
        let stateId = {{ $warehouse->state_id ?? 'null' }};
        let cityId = {{ $warehouse->city_id ?? 'null' }};

        if (countryId) {
            $.ajax({
                url: '{{ route("state-list") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    country_id: countryId,
                    searchQuery: ''
                },
                success: function(response) {
                    if (response.items && response.items.length > 0) {
                        $.each(response.items, function(index, item) {
                            let selected = (item.id == stateId) ? 'selected' : '';
                            $('#state_id').append('<option value="' + item.id + '" ' + selected + '>' + item.text + '</option>');
                        });
                        
                        if (stateId) {
                            loadCities(stateId, cityId);
                        }
                    }
                }
            });
        }
    }

    function loadCities(stateId, cityId) {
        $.ajax({
            url: '{{ route("city-list") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                state_id: stateId,
                searchQuery: ''
            },
            success: function(response) {
                if (response.items && response.items.length > 0) {
                    $.each(response.items, function(index, item) {
                        let selected = (item.id == cityId) ? 'selected' : '';
                        $('#city_id').append('<option value="' + item.id + '" ' + selected + '>' + item.text + '</option>');
                    });
                }
            }
        });
    }

    loadStatesAndCities();

    $('#address_search').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            const address = $(this).val();
            if (address) {
                geocoder.geocode({ address: address }, (results, status) => {
                    if (status === "OK") {
                        const location = results[0].geometry.location;
                        placeMarkerAndPanTo(location, map);
                        map.setZoom(15);
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }
        }
    });

    $('#warehouseForm').validate({
        rules: {
            code: { required: true },
            name: { required: true },
            address_line_1: { required: true },
            country_id: { required: true },
            state_id: { required: true },
            city_id: { required: true },
            zipcode: { required: true },
            email: { required: true, email: true },
            contact_number: { required: true }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});

window.initMap = initMap;
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_maps_api_key', 'YOUR_API_KEY') }}&callback=initMap"></script>
@endpush
