<x-admin.header :title="'Site About Us'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">About Us</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('settings.about.save') }}" method="post" enctype="multipart/form-data">
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
                                    @if ($setting->slug == 'about_amenities')
                                    @php
                                        $edit_amenities = json_decode($setting->value);
                                        if (empty($edit_amenities) || count($edit_amenities) == 0) {
                                            $edit_amenities[] = 0;
                                        }
                                    @endphp
                                        <tr>
                                            <td data-slug="{{ $setting->slug }}">{{ $setting->name }}</td>
                                            <td>
                                                @php
                                                    $counterForId=1;
                                                @endphp
                                                @forelse ($amenities as $amenity)
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="amenities_{{ $counterForId }}" type="checkbox" name="{{ $setting->slug }}[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, old($setting->slug , $edit_amenities)) ? 'checked' : '' }} >
                                                        <label class="form-check-label" for="amenities_{{ $counterForId }}">{{ $amenity->name }}</label>
                                                    </div>
                                                    @php
                                                        $counterForId++
                                                    @endphp
                                                @empty
                                                    <div class="form-check"> No amenities found </div>
                                                @endforelse
                                                </td>
                                            <td></td>
                                        </tr>
                                    @elseif($setting->type == 'img')
                                        <tr>
                                            <td data-slug="{{ $setting->slug }}">{{ $setting->name }}</td>
                                            <td><img src="{{ publicPath($setting->value) }}" alt="{{ $setting->slug }}" height="100"></td>
                                            <td><button type="button" class="btn btn-sm btn-light ko_settings_btn" id="ko_settings_table_{{ $setting->type }}">Edit</button></td>
                                        </tr>
                                    @elseif($setting->type == 'textarea')
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

<script src="{{ publicPath('admin/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<x-admin.footer />