<x-admin.header :title="'Create New User'" />

<div class="container-fluid">

    <!-- Page title -->
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Create New User</h4>
        <div class="page-title-right">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('users.store') }}" method="POST" novalidate>
                        @csrf

                        <!-- Name + Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"  pattern="[A-Za-z ]+"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Mobile + WhatsApp -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="number" name="mobile"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    value="{{ old('mobile') }}"
                                    onkeydown="return !['e','E','+','-'].includes(event.key)">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">WhatsApp Number</label>
                                <input type="text" name="whatsapp_number"
                                    class="form-control @error('whatsapp_number') is-invalid @enderror"
                                    value="{{ old('whatsapp_number') }}">
                                @error('whatsapp_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Country + State -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country"
                                    class="form-control @error('country') is-invalid @enderror"
                                    value="{{ old('country') }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">State / Province</label>
                                <input type="text" name="state"
                                    class="form-control @error('state') is-invalid @enderror"
                                    value="{{ old('state') }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Area -->
                        <div class="mb-3">
                            <label class="form-label">Area</label>
                            <input type="text" name="area"
                                class="form-control @error('area') is-invalid @enderror" value="{{ old('area') }}">
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label class="form-label">Address</label>
                            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select id="role" name="role"
                                class="form-select choices @error('role') is-invalid @enderror" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $id => $name)
                                    <option value="{{ $name }}" {{ old('role') == $name ? 'selected' : '' }}>
                                        {{ ucfirst($name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="btnText">
                                    <i class="bi bi-save me-1"></i> Create User
                                </span>
                                <span id="btnLoader" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Saving...
                                </span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <!-- Info box -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Note:</strong>
                A random password will be generated and sent to the user's email address.
            </div>
        </div>
    </div>
</div>
<script>
      document.querySelector('form').addEventListener('submit', function () {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('btnText').classList.add('d-none');
        document.getElementById('btnLoader').classList.remove('d-none');
    });

    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.querySelector('input[name="name"]');

        nameInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^A-Za-z ]/g, '');
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

    function allowOnlyNumbers(selector) {
        document.querySelectorAll(selector).forEach(input => {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 10);
            });
        });
    }

    allowOnlyNumbers('input[name="mobile"]');
    allowOnlyNumbers('input[name="whatsapp_number"]');

});
</script>

<x-admin.footer />
