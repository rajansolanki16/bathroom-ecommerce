<x-admin.header :title="'Site Env'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">Environment Settings</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('settings.env.save') }}" method="post">
                @csrf

                <div class="card-header d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="mb-0 card-title">Edit Environment Variables</h5>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-baseline bi bi-arrow-clockwise me-1"></i> Update
                        </button>
                         <input type="hidden" name="save_env" value="1">
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped-columns">
                            <thead class="table-light">
                                <tr>
                                    <th>Variable</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($envSettings as $key => $value)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        @if (in_array($key, ['APP_KEY', 'SESSION_DRIVER', 'SESSION_LIFETIME', 'SESSION_ENCRYPT','SESSION_PATH','SESSION_DOMAIN','SESSION_EXPIRE_ON_CLOSE','VITE_APP_NAME', 'CACHE_STORE']))
                                            <td>
                                                <input type="text" value="{{ $value }}" class="form-control" disabled />
                                                <input type="hidden" name="env[{{ $key }}]" value="{{ $value }}" />
                                            </td>
                                        @elseif ($key == 'PAYMENTS_MODE')
                                            <td>
                                                <select name="env[{{ $key }}]" class="form-control">
                                                    <option value="TEST" {{ $value == 'TEST' ? 'selected' : '' }}>TEST</option>
                                                    <option value="PRODUCTION" {{ $value == 'PRODUCTION' ? 'selected' : '' }}>PRODUCTION</option>
                                                </select>
                                            </td>
                                        @elseif ($key == 'APP_HOSTING_MODE')
                                            <td>
                                                <select name="env[{{ $key }}]" class="form-control">
                                                    <option value="LOCALHOST" {{ $value == 'LOCALHOST' ? 'selected' : '' }}>LOCALHOST</option>
                                                    <option value="WEBHOST" {{ $value == 'WEBHOST' ? 'selected' : '' }}>WEBHOST</option>
                                                </select>
                                            </td>
                                        @elseif ($key == "APP_DEBUG") 
                                            <td>
                                                <select name="env[{{ $key }}]" class="form-control">
                                                    <option value="TRUE" {{ $value == 'TRUE' ? 'selected' : '' }}>TRUE</option>
                                                    <option value="FALSE" {{ $value == 'FALSE' ? 'selected' : '' }}>FALSE</option>
                                                </select>
                                            </td>
                                        @else
                                            <td>
                                                <input type="text" name="env[{{ $key }}]" value="{{ $value }}" class="form-control" />
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>                            
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<x-admin.footer />
