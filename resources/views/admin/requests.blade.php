@extends('admin.layout')

@section('content')
    <h2>Pending Refilling Station Requests</h2>
    <p>List of shop owners awaiting approval.</p>

    @if($pendingOwners->isEmpty())
        <div class="alert alert-info">No pending requests yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle text-nowrap small">
                <thead class="table-dark">
                    <tr>
                        <th>Shop</th>
                        <th>Owner</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>TaC</th>
                        <th>DTI</th>
                        <th>Permit</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingOwners as $owner)
                        <tr>
                            <td>{{ $owner->shop_name }}</td>
                            <td>{{ $owner->name }}</td>
                            <td>
                                {{ $owner->email }}<br>{{ $owner->phone }}
                            </td>
                            <td>{{ $owner->address }}</td>
                            <td>
                                <span class="badge bg-{{ $owner->agreed_to_terms ? 'success' : 'secondary' }}">
                                    {{ $owner->agreed_to_terms ? 'Agreed' : 'No' }}
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


                            <td>{{ $owner->created_at->format('M d, Y g:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.approve', $owner->id) }}"
                                   class="btn btn-success btn-sm ">Approve</a>
                                <!-- Trigger Decline Modal -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#declineModal{{ $owner->id }}">
                                    Decline
                                </button>
                            </td>
                            <!-- Decline Modal -->
                            <div class="modal fade" id="declineModal{{ $owner->id }}" tabindex="-1" aria-labelledby="declineModalLabel{{ $owner->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <form action="{{ route('admin.decline-owner') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="declineModalLabel{{ $owner->id }}">
                                                Decline {{ $owner->shop_name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="reason">Reason for Declining:</label>
                                            <textarea name="decline_reason" class="form-control" rows="3" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                              </div>
                            </div>

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
