<x-admin.header :title="'Edit User'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-sm-0">Edit User</h4>
        <div class="page-title-right">
            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="{{ $user->username }}" disabled>
                            <small class="text-muted">Username cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" 
                                   id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" required>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                            <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" 
                                   id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}">
                            @error('whatsapp_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                   id="country" name="country" value="{{ old('country', $user->country) }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="state" class="form-label">State/Province</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                   id="state" name="state" value="{{ old('state', $user->state) }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="area" class="form-label">Area</label>
                            <input type="text" class="form-control @error('area') is-invalid @enderror" 
                                   id="area" name="area" value="{{ old('area', $user->area) }}">
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Account Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Account is Active
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Access Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" 
                                       value="1" {{ old('is_approved', $user->is_approved) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_approved">
                                    User Access is Approved
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
