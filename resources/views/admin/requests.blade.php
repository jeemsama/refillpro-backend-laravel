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
                                <!-- Approve Button -->
                                <button class="btn btn-success btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#approveModal{{ $owner->id }}">
                                    Approve
                                </button>

                                <!-- Trigger Decline Modal -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#declineModal{{ $owner->id }}">
                                    Decline
                                </button>
                            </td>
                           <!-- Decline Modal -->
                            <div class="modal fade" id="declineModal{{ $owner->id }}" tabindex="-1"
                                 aria-labelledby="declineModalLabel{{ $owner->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <form action="{{ route('admin.decline-owner') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="declineModalLabel{{ $owner->id }}">
                                        Decline "{{ $owner->shop_name }}"
                                      </h5>
                                      <button type="button" class="btn-close"
                                              data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                      <p>Please select one reason for declining:</p>

                                      <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="decline_reason"
                                               id="reason1-{{ $owner->id }}"
                                               value="Incomplete documentation" required>
                                        <label class="form-check-label" for="reason1-{{ $owner->id }}">
                                          Incomplete documentation
                                        </label>
                                      </div>

                                      <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="decline_reason"
                                               id="reason2-{{ $owner->id }}"
                                               value="Invalid address / location">
                                        <label class="form-check-label" for="reason2-{{ $owner->id }}">
                                          Invalid address / location
                                        </label>
                                      </div>

                                      <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="decline_reason"
                                               id="reason3-{{ $owner->id }}"
                                               value="Terms & Conditions not agreed">
                                        <label class="form-check-label" for="reason3-{{ $owner->id }}">
                                          Terms &amp; Conditions not agreed
                                        </label>
                                      </div>

                                      <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="decline_reason"
                                               id="reason4-{{ $owner->id }}"
                                               value="Business permit expired or invalid">
                                        <label class="form-check-label" for="reason4-{{ $owner->id }}">
                                          Business permit expired or invalid
                                        </label>
                                      </div>

                                      <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="decline_reason"
                                               id="reason5-{{ $owner->id }}"
                                               value="Other">
                                        <label class="form-check-label" for="reason5-{{ $owner->id }}">
                                          Other
                                        </label>
                                      </div>
                                    </div>

                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-danger">Yes, Decline</button>
                                      <button type="button" class="btn btn-secondary"
                                              data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>


                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $owner->id }}" tabindex="-1"
                                 aria-labelledby="approveModalLabel{{ $owner->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <form action="{{ route('admin.approve-owner') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="approveModalLabel{{ $owner->id }}">
                                        Approve "{{ $owner->shop_name }}"
                                      </h5>
                                      <button type="button" class="btn-close"
                                              data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <p>Are you sure you want to <strong>Approve</strong> this shop?</p>
                                      <ul class="list-unstyled small">
                                        <li><strong>Owner:</strong> {{ $owner->name }}</li>
                                        <li><strong>Contact:</strong> {{ $owner->email }} / {{ $owner->phone }}</li>
                                        <li><strong>Address:</strong> {{ $owner->address }}</li>
                                      </ul>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-success">Yes, Approve</button>
                                      <button type="button" class="btn btn-secondary"
                                              data-bs-dismiss="modal">Cancel</button>
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
