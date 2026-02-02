<x-admin.header :title="'Edit User'" />

<div class="container-fluid">

    <!-- Page title -->
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Edit User</h4>
        <div class="page-title-right">
            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('users.update', $user) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Name + Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Username (readonly) -->
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                            <small class="text-muted">Username cannot be changed</small>
                        </div>

                        <!-- Mobile + WhatsApp -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="mobile"
                                       class="form-control @error('mobile') is-invalid @enderror"
                                       value="{{ old('mobile', $user->mobile) }}">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">WhatsApp Number</label>
                                <input type="text"
                                       name="whatsapp_number"
                                       class="form-control @error('whatsapp_number') is-invalid @enderror"
                                       value="{{ old('whatsapp_number', $user->whatsapp_number) }}">
                                @error('whatsapp_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Country + State -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text"
                                       name="country"
                                       class="form-control @error('country') is-invalid @enderror"
                                       value="{{ old('country', $user->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">State / Province</label>
                                <input type="text"
                                       name="state"
                                       class="form-control @error('state') is-invalid @enderror"
                                       value="{{ old('state', $user->state) }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Area -->
                        <div class="mb-3">
                            <label class="form-label">Area</label>
                            <input type="text"
                                   name="area"
                                   class="form-control @error('area') is-invalid @enderror"
                                   value="{{ old('area', $user->area) }}">
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label class="form-label">Address</label>
                            <textarea name="address"
                                      rows="3"
                                      class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Role -->
                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role"
                                    class="form-select @error('role') is-invalid @enderror"
                                    required>
                                @foreach($roles as $id => $name)
                                    <option value="{{ $name }}"
                                        {{ old('role', $userRole) == $id ? 'selected' : '' }}>
                                        {{ ucfirst($name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <!-- Status switches -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Account is Active
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Access Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_approved"
                                           value="1"
                                           {{ old('is_approved', $user->is_approved) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        User Access is Approved
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update User
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />
