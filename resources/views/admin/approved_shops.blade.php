@extends('admin.layout')

@section('content')
    <h2>Approved Refilling Stations</h2>
    <p>List of shop owners already approved.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($approvedOwners->isEmpty())
        <div class="alert alert-info">No approved stations found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle text-nowrap small">
                <thead class="table-dark">
                    <tr>
                        <th>Shop</th>
                        <th>Owner</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Visibility</th>
                        <th>DTI</th>
                        <th>Permit</th>
                        <th>Approved</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvedOwners as $owner)
                        <tr>
                            <td>{{ $owner->shop_name }}</td>
                            <td>{{ $owner->name }}</td>
                            <td>{{ $owner->email }}<br>{{ $owner->phone }}</td>
                            <td>{{ $owner->address }}</td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($owner->status) }}</span>
                            </td>
                            <td>
                                <span class="{{ $owner->is_visible ? 'text-success' : 'text-muted' }}">
                                    {{ $owner->is_visible ? 'Visible' : 'Paused' }}
                                </span>
                            </td>
                            <td>
                                <img src="{{ asset('storage/' . $owner->dti_permit_path) }}"
                                     alt="DTI" class="img-thumbnail" style="max-width: 35px; cursor:pointer;"
                                     data-bs-toggle="modal" data-bs-target="#dtiModal"
                                     onclick="document.getElementById('dtiModalImg').src=this.src;">
                            </td>
                            <td>
                                <img src="{{ asset('storage/' . $owner->business_permit_path) }}"
                                     alt="Permit" class="img-thumbnail" style="max-width: 35px; cursor:pointer;"
                                     data-bs-toggle="modal" data-bs-target="#permitModal"
                                     onclick="document.getElementById('permitModalImg').src=this.src;">
                            </td>
                            <td>{{ $owner->updated_at->format('M d, Y g:i A') }}</td>
                            <!-- <td>
                                @if($owner->is_visible)
                                    <form action="{{ route('admin.owners.pause', $owner->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Pause</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.owners.continue', $owner->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Continue</button>
                                    </form>
                                @endif
                            </td> -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- DTI Modal -->
        <div class="modal fade" id="dtiModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <img id="dtiModalImg" src="" alt="DTI" class="img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>

        <!-- Permit Modal -->
        <div class="modal fade" id="permitModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <img id="permitModalImg" src="" alt="Permit" class="img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
