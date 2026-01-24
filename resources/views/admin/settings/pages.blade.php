<x-admin.header :title="'Site Pages'" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/javascript/javascript.min.js"></script>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">All Pages</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form id="pageSettingForm" action="{{ route('settings.pages.save') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="card-header d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="mb-0 card-title">Settings</h5>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="flex-wrap gap-2 d-flex align-items-start">
                            <button type="submit" class="btn btn-primary add-btn" ><i class="align-baseline bi bi-arrow-clockwise me-1"></i> Update</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table mt-3 mb-0 table-nowrap table-striped-columns" id="ko_settings_table">
                            <thead class="table-light">
                                <tr>
                                    <th class="col-type">Type</th>
                                    <th class="col-value">Value</th>
                                    <th class="col-action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($settings as $setting)
                                    @if($setting->type == 'textarea')
                                        <tr>
                                            <td data-slug="{{ $setting->slug }}">{{ $setting->name }}</td>
                                            <td>{!! $setting->value !!}</td>
                                            <td><button type="button" class="btn btn-sm btn-light ko_settings_btn" id="ko_settings_table_{{ $setting->type }}">Edit</button></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td data-slug="{{ $setting->slug }}">{{ $setting->name }}</td>
                                            <td>{{ $setting->value }}</td>
                                            <td><button type="button" class="btn btn-sm btn-light ko_settings_btn" id="ko_settings_table_{{ $setting->type }}">Edit</button></td>
                                        </tr>
                                    @endif
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