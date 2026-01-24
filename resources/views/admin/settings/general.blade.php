<x-admin.header :title="'Site General'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">General Settings</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('settings.general.save') }}" method="post" enctype="multipart/form-data">
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
                                    @if ($setting->slug == 'site_social_links')
                                        <tr>
                                            <td data-slug="{{ $setting->slug }}">{{ $setting->name }}</td>
                                            <td data-value="{{ htmlspecialchars($setting->value, ENT_QUOTES, 'UTF-8') }}">
                                                @php
                                                    $s_media = json_decode($setting->value, true);
                                                @endphp
                                                @if (!empty($s_media))
                                                    @foreach ($s_media as $index => $media)
                                                        <a href="{{ $media['link'] }}" target="_blank"
                                                            style="margin-right: 10px;">
                                                            <img src="{{ publicPath($media['icon']) }}" alt="Social Icon" width="30"
                                                                height="30">
                                                        </a>
                                                    @endforeach
                                                @else
                                                        <div id="ko_settings_no_media"> no media found </div>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-light ko_settings_btn"
                                                    id="ko_settings_table_{{ $setting->slug }}" data-asset_url="{{ publicPath("/") }}/" data-links="{{ $setting->value }}" >Edit</button>
                                            </td>
                                        </tr>
                                    @elseif($setting->slug == 'site_icon' || $setting->slug == 'site_logo_light' || $setting->slug == 'site_logo_dark')
                                        <tr>
                                            <td data-slug="{{ $setting->slug }}">{{ $setting->name }}</td>
                                            <td><img src="{{ publicPath($setting->value) }}" alt="{{ $setting->slug }}" height="100"></td>
                                            <td><button type="button" class="btn btn-sm btn-light ko_settings_btn"
                                                    id="ko_settings_table_{{ $setting->type }}">Edit</button></td>
                                        </tr>
                                    @elseif($setting->type == 'textarea')
                                        <tr>
                                            <td data-slug="{{ $setting->slug }}">{{ $setting->name }}</td>
                                            <td>{!! $setting->value !!}</td>
                                            <td><button type="button" class="btn btn-sm btn-light ko_settings_btn"
                                                    id="ko_settings_table_{{ $setting->type }}">Edit</button></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td data-slug="{{ $setting->slug }}">{{ $setting->name }}</td>
                                            <td>{{ $setting->value }}</td>
                                            <td><button type="button" class="btn btn-sm btn-light ko_settings_btn"
                                                    id="ko_settings_table_{{ $setting->type }}">Edit</button></td>
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
