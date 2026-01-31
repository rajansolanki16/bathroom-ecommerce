// Reusable Media Picker Logic
window.initMediaPicker = function(options) {
    var pickerBtn = $(options.pickerBtnSelector);
    var modalBody = $(options.modalBodySelector);
    var modal = $(options.modalSelector);
    var hiddenInput = $(options.hiddenInputSelector);
    var previewDiv = $(options.previewSelector);
    var selectedMediaId = hiddenInput.val() || null;

    function openMediaPicker() {
        $.ajax({
            url: options.pickerUrl,
            type: "GET",
            success: function (html) {
                modalBody.html(html);
                modalBody.find('.media-thumb').on('click', function () {
                    selectedMediaId = $(this).data('id');
                    hiddenInput.val(selectedMediaId);
                    var imgUrl = $(this).find('img').attr('src');
                    previewDiv.html(`<img src="${imgUrl}" style="height:100px;width:100px;object-fit:cover;border-radius:4px;">`);
                    modal.find('.btn-close').trigger('click');
                });
            }
        });
    }

    pickerBtn.on('click', function () {
        openMediaPicker();
    });

    $(options.formSelector).on('submit', function () {
        hiddenInput.val(selectedMediaId || '');
    });
};
// Admin JS
function setDeleteFormAction(element) {
    let deleteUrl = element.getAttribute("data-delete-url");
    $("#deleteForm").attr("action", deleteUrl);
    $("#deleteRecordModal").modal("show");
}

$(document).ready(function () {

    $('input[type="tel"]').on("input", function () {
        let value = $(this).val();

        if (!/^\+?\d*$/.test(value) || value.length > 14) {
            $(this).val(value.slice(0, -1));
        }
    });

    $('input[type="tel"]').on("keydown", function (e) {
        if (
            !/[\d]/.test(e.key) &&
            e.key !== "Backspace" &&
            e.key !== "Delete" &&
            e.key !== "ArrowLeft" &&
            e.key !== "ArrowRight" &&
            !(e.key === "+" && this.selectionStart === 0)
        ) {
            e.preventDefault();
        }
    });
    
    var checkinPicker = $(".checkin_date_picker").flatpickr({
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: $(".checkin_date_picker").data('old'),
        onChange: function (selectedDates) {
            var minCheckoutDate = new Date(selectedDates[0]);
            minCheckoutDate.setDate(minCheckoutDate.getDate());
            checkoutPicker.set('minDate', minCheckoutDate);
            checkoutPicker.setDate(minCheckoutDate);
        }
    });

    $(".checkin_date_picker").on('change' , function(){
        $(".checkout_date_picker").focus();
    });

    var checkoutPicker = $(".checkout_date_picker").flatpickr({
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(0),
        defaultDate: $(".checkout_date_picker").data('old'),
    });
    /* date picker js end */

    $("input[type=number]").on("wheel", function (event) {
        event.preventDefault();
    });

    $(".search").on("keyup", function () {
        var searchValue = $(this).val().toLowerCase();
        var hasResults = false;
        $(".list.form-check-all tr").filter(function () {
            var title = $(this).find(".products h6 a").text().toLowerCase();
            var description = $(this)
                .find("td:nth-child(3)")
                .text()
                .toLowerCase();

            var isVisible =
                title.indexOf(searchValue) > -1 ||
                description.indexOf(searchValue) > -1;
            $(this).toggle(isVisible);
            if (isVisible) {
                hasResults = true;
            }
        });
        if (hasResults) {
            $(".noresult").hide();
        } else {
            $(".noresult").show();
        }
    });

    var selectedRowForSettings;
    var social_link_counter = 0;
    var site_reviews_counter = 0;

    $("#ko_settings_table").on("click", ".ko_settings_btn", function (event) {
        var button = $(this);
        selectedRowForSettings = button.closest("tr");
        var valueCell = selectedRowForSettings.find("td").eq(1);
        var slugCell = selectedRowForSettings.find("td").eq(0);
        var currentValue = valueCell.text();
        var currentSlug = slugCell.data('slug');

        if (button.attr("id") == "ko_settings_table_text") {
            valueCell.html(
                '<input type="text" name="' + currentSlug + '" class="form-control" value="' + currentValue +'" required>'
            );
        }

        if (button.attr("id") == "ko_settings_table_img" ) {
            valueCell.html(
                '<input type="file" name="' + currentSlug + '" class="form-control" accept="image/*" ><small class="text-muted d-flex justify-content-center mt-2">please leave it blank if you do not wants to change.</small>'
            );
        }

        if (button.attr("id") == "ko_settings_table_map_link" ) {
            valueCell.html(
                '<input type="text" name="' + currentSlug + '" class="form-control" value="' + currentValue +'" required>'
            );
        }

        if (button.attr("id") == "ko_settings_table_textarea") {
            let textareaSelector = 'textarea[name="' + currentSlug + '"]';

            valueCell.html(
                '<textarea name="' + currentSlug + '" class="form-control" >' + escapeHtml(currentValue) + '</textarea>'
            );
            
            initTinyMCE(textareaSelector);
        }

        if (button.attr("id") == "ko_settings_table_code") {
            let textareaSelector = 'textarea[name="' + currentSlug + '"]';

            valueCell.html(
                '<textarea name="' + currentSlug + '" class="form-control ko-code-snippet" >' + escapeHtml(currentValue) + '</textarea>'
            );
        }

        if (button.attr("id") === "ko_settings_table_site_social_links") {
            var old_links = button.data('links');
            var asset_url = button.data('asset_url');
            var html_string = "<input type='hidden' name='settings_social_link_edited' value='true' />";
            
            if ($("#add_new_social_link").length === 0) {
                button.parent().append("<div class='row'><button class='btn btn-sm btn-light mt-3 ko_setting_add_btn' id='add_new_social_link'>Add New</button></div>");
            }
    
            if (old_links && Array.isArray(old_links) && old_links.length !== 0) {
                old_links.forEach(function (item) {
                    social_link_counter++;
                    html_string += generateSocialLinkRow(social_link_counter, asset_url + item.icon, item.link, item.id);
                });
            } else {
                social_link_counter++;
                html_string += generateSocialLinkRow(social_link_counter);
            }
    
            valueCell.html(html_string);
        }

        if (button.attr("id") == "ko_settings_table_home_review_area") {
            var old_reviews = button.data('reviews');
            html_string="<input type='hidden' name='home_review_area_edited' value='true' />";
            if ($("#add_new_home_review").length === 0) {
                button.parent().append("<div class='row'><button type='button' class='btn btn-sm btn-light mt-3 ko_setting_add_btn' id='add_new_home_review'>add new</button></div>");
            }
            console.log(old_reviews);
            if(old_reviews && old_reviews.length  != 0 && Array.isArray(old_reviews)){
                old_reviews.forEach(function(item) {
                    site_reviews_counter += 1;
                    html_string +="<div class='row'><button class='btn btn-subtle-danger mx-auto d-block' style='width: 98%;' id='remove_setting_table_row'>Remove</button>";
                    html_string +="<div class='col-lg-12 mt-1'><div class='row'>";
                    html_string +="<div class='col-md-6'><input type='text' class='form-control' required name='name_"+site_reviews_counter+"' value='" +item.name +"' placeholder='Name'></div>";
                    html_string +="<div class='col-md-6'><input type='number' class='form-control' required name='rate_"+site_reviews_counter+"' value='" +item.rate +"' placeholder='Rating'></div>";
                    html_string +="</div><div class='row mt-2'><div class='col-md-12'><textarea class='form-control' name='review_"+site_reviews_counter+"' placeholder='Write the review here' required>" +item.review +"</textarea>";
                    html_string +="</div></div></div></div>";
                });
            }else{
                site_reviews_counter += 1;
                html_string +="<div class='row'><button class='btn btn-subtle-danger mx-auto d-block' style='width: 98%;' id='remove_setting_table_row'>Remove</button>";
                html_string +="<div class='col-lg-12 mt-1'><div class='row'>";
                html_string +="<div class='col-md-6'><input type='text' class='form-control' required name='name_"+site_reviews_counter+"' placeholder='Name'></div>";
                html_string +="<div class='col-md-6'><input type='number' class='form-control' required name='rate_"+site_reviews_counter+"' placeholder='Rating'></div>";
                html_string +="</div><div class='row mt-2'><div class='col-md-12'><textarea class='form-control' name='review_"+site_reviews_counter+"' placeholder='Review' required></textarea>";
                html_string +="</div></div></div></div>";
            }

            valueCell.html(html_string);
        }
    });

    
        
    $("#pageSettingForm").on("submit", function (event) {
        if ($(this).data("submitted")) {
            return true; 
        }

        event.preventDefault();

        let elements = document.querySelectorAll(".ko-code-snippet");

        if (elements.length > 0) {
            elements.forEach((element) => {
                let originalValue = element.value;
                let charCodeArray = [];

                for (let i = 0; i < originalValue.length; i++) {
                    charCodeArray.push(originalValue.charCodeAt(i));
                }

                let encodedValue = charCodeArray.join("-");
                element.value = encodedValue;
            });
        }

        $(this).data("submitted", true);

        setTimeout(() => {
            $(this).submit();
        }, 500);
    });
   

    $("#ko_settings_table").on("click", "#add_new_social_link", function (event) {
        event.preventDefault();
        $("#ko_settings_no_media").remove();
        var button = $(this);
        selectedRowForSettings = button.closest("tr");
        var valueCell = selectedRowForSettings.find("td").eq(1);

        social_link_counter++;
        valueCell.append(generateSocialLinkRow(social_link_counter));
    });

    $("#ko_settings_table").on("click", "#add_new_home_review", function (event) {
        event.preventDefault();
        $("#ko_settings_no_review").remove();
        var button = $(this);
        selectedRowForSettings = button.closest("tr");
        var valueCell = selectedRowForSettings.find("td").eq(1);

        site_reviews_counter += 1;
        var new_html ="<div class='row'><button class='btn btn-subtle-danger mx-auto d-block mt-2' style='width: 98%;' id='remove_setting_table_row'>Remove</button>";
        new_html +="<div class='col-lg-12 mt-1'><div class='row'>";
        new_html +="<div class='col-md-6'><input required type='text' class='form-control' name='name_"+site_reviews_counter+"' placeholder='Name'></div>";
        new_html +="<div class='col-md-6'><input required type='number' class='form-control' name='rate_"+site_reviews_counter+"' placeholder='Rating'></div>";
        new_html +="</div><div class='row mt-2'><div class='col-md-12'><textarea class='form-control' required name='review_"+site_reviews_counter+"' placeholder='Review'></textarea>";
        new_html +="</div></div></div></div>";
        valueCell.append(new_html);
    });



    $("#ko_settings_table").on("click", "#remove_setting_table_row", function () {
        $(this).parent().remove();
    });

    $(".remove-room-media").on("click", function () {
        $this = $(this);
        var parent_div = $this.parent().parent();
        var mediaElement = $this.closest(".position-relative");
        var media_url = $this.data("media");
        var admin_url = parent_div.data("url");
        var rid = parent_div.data("id");
        var token = $('meta[name="csrf-token"]').attr('content');
        var media_type = parent_div.data("type");

        $.ajax({
            url: admin_url,
            type: "POST",
            data: {
                media: media_url,
                room : rid,
                type : media_type,
                _token: token
            },
            success: function (response) {
                if (response.success) {
                mediaElement.fadeOut(300, function () {$(this).remove();});
            } else {
                console.warn("Unexpected response received.");
            }
            },
            error: function (xhr) {
                let errorMessage = "ERROR : ";
                let response = JSON.parse(xhr.responseText);
                if (response.error) {
                    errorMessage += response.error;
                } else {
                    errorMessage += "Unknown error occurred.";
                }
                console.warn(errorMessage);
            }
        });
    });

    $("#booking_room_type").on('change', function() {
        $('.booking_form_services').prop('checked', false).prop('disabled', false);
        get_room_services();
    });

    function generateSocialLinkRow(counter, iconSrc = '', url = '', id = '') {
        return `
            <div class='row align-items-center'>
                <button class='btn btn-subtle-danger mb-2 mt-2' id="remove_setting_table_row">Remove</button>
                <div class='col-xl-1 text-center'>
                    <img id='icon_preview_${counter}' src='${iconSrc}' alt='Icon Preview' style='max-width: 30px; display: ${iconSrc ? 'block' : 'none'}; margin-top: 5px;'/>
                </div>
                <div class='col-xl-5'>
                    <div class='mb-3'>
                        <label class='form-label'>Icon</label>
                        <input type='file' accept='image/*, .ico' class='form-control' name='icon_${counter}' ${iconSrc ? '' : 'required'} onchange='previewImage(event, ${counter})'>
                    </div>
                </div>
                <div class='col-xl-6'>
                    <div class='mb-3'>
                        <label class='form-label'>Link</label>
                        <input type='url' required class='form-control' name='url_${counter}' value='${url}' placeholder='https://'>
                        <input type='hidden' name='social_link_id_${counter}' value='${id}'>
                    </div>
                </div>
            </div>`;
    }

    function escapeHtml(text) {
        return text.replace(/&/g, "&amp;")
                   .replace(/</g, "&lt;")
                   .replace(/>/g, "&gt;")
                   .replace(/"/g, "&quot;")
                   .replace(/'/g, "&#039;");
    }
    
    function previewImage(event, counter) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('icon_preview_' + counter);
            output.src = reader.result;
            output.style.display = "block";
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    

    function initTinyMCE(selector) {
        tinymce.init({
            selector: selector,
            height: 300,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code',
            content_style: "body { font-family: Arial, sans-serif; font-size: 14px; }",
            setup: function (editor) {
                editor.on('init', function () {
                    editor.setMode('design');
                });
            }
        });
    }

    function destroyTinyMCE(selector) {
        if (tinymce.get(selector)) {
            tinymce.get(selector).remove();
        }
    }

    function get_room_services(){
        let $this = $("#booking_room_type");
        var token = $('meta[name="csrf-token"]').attr('content');
        let room_id =  $this.val();

        let ajax_url = $('#get_room_services_url').data('url');

        if(room_id !== null){
            $.ajax({
                url: ajax_url,
                type: "POST",
                data: {
                    rid : room_id,
                    _token: token
                },
                dataType: "json",
                success: function (response) {
                    if (Array.isArray(response) && response.length > 0) {
                        response.forEach(function (id) {
                            $('.booking_form_services[value="' + id + '"]').prop('checked', true).prop('disabled', true);
                        });
                    }
                }
            });
        }
    }
    get_room_services();

});

//admin panel e-commerce

function previewSingleImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('productImagePreview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function previewMultipleImages(event) {
    const preview = document.getElementById('galleryPreview');
    preview.innerHTML = '';

    Array.from(event.target.files).forEach(file => {
        const reader = new FileReader();

        reader.onload = function () {
            const col = document.createElement('div');
            col.classList.add('col-md-3', 'mb-3');

            col.innerHTML = `
                <div class="card shadow-sm">
                    <img src="${reader.result}" class="card-img-top" style="height:150px;object-fit:cover">
                </div>
            `;
            preview.appendChild(col);
        };

        reader.readAsDataURL(file);
    });
}

// product type hide/show
function toggleSections() {
    const type = $('#productType').val();
    console.log('Product Type:', type); 

    if (type == 1) {
        $('#vec_shipping_section').hide();
        $('#vec_variantSection').stop(true, true).slideDown();
        $('#vec_general_Info_Section').hide();
    } else {
        $('#vec_variantSection').hide();
        $('#vec_general_Info_Section').stop(true, true).slideDown();
        $('#vec_shipping_section').show();
    }
}

toggleSections();
$('#productType').on('change', toggleSections);


document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.querySelector('#productCategories');
    if (categorySelect) {
        new Choices(categorySelect, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
            removeItemButton: true,
            placeholderValue: 'Select categories',
        });
    }

    const tagsSelect = document.querySelector('#productTags');
    if (tagsSelect) {
        new Choices(tagsSelect, {
            searchEnabled: true,
            removeItemButton: true,
            shouldSort: false,
            placeholderValue: 'Select tags',
        });
    }
    const brandSelect = document.querySelector('#productBrands');
    if (brandSelect) {
        new Choices(brandSelect, {
            searchEnabled: true,
            removeItemButton: true,
            shouldSort: false,
            placeholderValue: 'Select brands',
        });
    }


    const productTypeSelect = document.querySelector('#productType');
    if (productTypeSelect) {
        new Choices(productTypeSelect, {
            searchEnabled: true,
            removeItemButton: true,
            shouldSort: false,
            placeholderValue: 'Select tags',
        });
    }
    
    const attrSelect = document.querySelector('#variantAttributesSelect');
    if (attrSelect) {
        new Choices(attrSelect, {
            searchEnabled: true,
            removeItemButton: true,
            shouldSort: false,
            placeholderValue: 'Select variant attributes',
        });
    }
    const productStatusSelect = document.querySelector('#productStatus');
    if (productStatusSelect) { new Choices(productStatusSelect, {}); }

    const productVisibilitySelect = document.querySelector('#productVisibility');
    if (productVisibilitySelect) { new Choices(productVisibilitySelect, {}); }
});
    
function setDeleteFormAction(element) {
    const deleteUrl = element.getAttribute('data-delete-url');
    const form = document.getElementById('deleteForm');

    form.action = deleteUrl;

    const modal = new bootstrap.Modal(
        document.getElementById('deleteRecordModal')
    );
    modal.show();
}


function vec_generate_coupon_code() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';

    for (let i = 0; i < 9; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('vec_coupon_code').value = code;
}

//Start attribute values crud ajax
let table;
let attributeName = '';

$(document).ready(function () {

    /* =====================
       DATATABLE (INIT ONCE)
    ===================== */
    
    // Get attribute name from the first row if it exists
    if ($('#vec_attribute_value tr').length > 0) {
        attributeName = $('#vec_attribute_value tr:first td:nth-child(3)').text().trim();
    }
    
    // table = $('#fixed-header').DataTable({
    //     pageLength: 10
    // });
    if (!$.fn.DataTable.isDataTable('#fixed-header')) {
        // Disable DataTables' default search box and entries selector since we use server-side filters on the page
        table = $('#fixed-header').DataTable({
            pageLength: 10,
            searching: false,
            lengthChange: false,
            // 'l' = length changing input, 'f' = filtering input — removed by using this dom
            dom: 'lrtip'
        });
    } else {
        table = $('#fixed-header').DataTable();
    }

    /* =====================
       FORM SUBMIT HANDLER (CREATE/UPDATE)
    ===================== */
    $('#vec_attribute_value_form').on('submit', function (e) {
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);
        let editId = $(form).data('edit-id');
        let updateUrl = $(form).data('update-url');
        let isEditMode = editId && updateUrl;
        
        let url = isEditMode ? updateUrl : $(form).data('store-url');
        let submitButton = $(form).find('button[type="submit"]');
        let originalButtonText = submitButton.text();

        // Disable button and show loading
        //submitButton.prop('disabled', true).text(isEditMode ? 'Updating...' : 'Submitting...');
        
        // Clear previous errors
        $('#valueError').text('').hide();
        $(form).find('.is-invalid').removeClass('is-invalid');

        // Add _method for update
        if (isEditMode) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function (data) {
                if (isEditMode) {
                    // Update existing row in DataTable
                    table.rows().every(function() {
                        let rowData = this.data();
                        if (rowData[0] == data.id) {
                            this.data([
                                data.id,
                                data.value,
                                data.attribute || attributeName,
                                actionButtons(data.id, data.attribute || attributeName)
                            ]).draw(false);
                            return false;
                        }
                    });
                    
                    // Reset form from edit mode
                    $(form).removeData('edit-id');
                    $(form).removeData('update-url');
                    submitButton.text('Submit');
                    $('.cancel-edit').remove();
                } else {
                    // Add new row to DataTable
                    let displayAttribute = data.attribute || attributeName || '';
                    table.row.add([
                        data.id,
                        data.value,
                        displayAttribute,
                        actionButtons(data.id, data.attribute || attributeName)
                    ]).draw(false);
                }

                form.reset();
                $('#valueError').text('').hide();
                submitButton.prop('disabled', false).text('Submit');
            },
            error: function (xhr) {
                submitButton.prop('disabled', false).text(originalButtonText);
                var err = xhr && xhr.responseJSON ? xhr.responseJSON : {};

                if (err.errors) {
                    $.each(err.errors, function(key, messages) {
                        if (key === 'value') {
                            $('#valueError').text(messages[0]).show();
                            $(form).find('[name="value"]').addClass('is-invalid');
                        }
                    });
                } else {
                    console.warn('An error occurred: ' + (err.message || 'Unknown error'));
                }
            }
        });
    });

    /* =====================
       EDIT VALUE HANDLER
    ===================== */
    $(document).on('click', '.editValue', function(e) {
        e.preventDefault();
        let editUrl = $(this).data('edit-url');
        let updateUrl = $(this).data('update-url');
        let row = $(this).closest('tr');
        let valueId = row.find('td:first').text();
        let currentValue = row.find('td:nth-child(2)').text();

        // Populate form with current value
        $('#values').val(currentValue);
        $('#vec_attribute_value_form').data('edit-id', valueId);
        $('#vec_attribute_value_form').data('update-url', updateUrl);
        
        // Change form to update mode
        let submitButton = $('#vec_attribute_value_form').find('button[type="submit"]');
        let cancelButton = $('#vec_attribute_value_form').find('.cancel-edit');
        
        if (cancelButton.length === 0) {
            submitButton.after('<a href="javascript:void(0);" class="btn btn-danger cancel-edit">Cancel</a>');
        }
        
        submitButton.text('Update');
        $('#values').focus();
    });

    /* =====================
       CANCEL EDIT HANDLER
    ===================== */
    $(document).on('click', '.cancel-edit', function(e) {
        e.preventDefault();
        $('#vec_attribute_value_form')[0].reset();
        $('#vec_attribute_value_form').removeData('edit-id');
        $('#vec_attribute_value_form').removeData('update-url');
        $('#vec_attribute_value_form').find('button[type="submit"]').text('Submit');
        $(this).remove();
        $('#valueError').text('').hide();
    });

    /* =====================
       DELETE VALUE HANDLER
    ===================== */
    $(document).on('click', '.deleteValue', function(e) {
        e.preventDefault();
        let deleteUrl = $(this).data('url');
        let row = $(this).closest('tr');
        let valueId = row.find('td:first').text();
        
        //if (confirm('Are you sure you want to delete this attribute value?')) {
            let formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function (data) {
                    if (data.status) {
                        // Find and remove the row from DataTable
                        table.rows().every(function() {
                            let rowData = this.data();
                            if (rowData[0] == valueId) {
                                this.remove().draw(false);
                                return false;
                            }
                        });
                    }
                },
                error: function (xhr) {
                    var errMsg = 'Failed to delete item';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.message) errMsg = xhr.responseJSON.message;
                    console.warn(errMsg);
                }
            });
            
        //}
    });
});


/* =====================
   ACTION BUTTONS
===================== */
function actionButtons(id, attributeName) {
    // Get base URL from the form store URL
    let storeUrl = $('#vec_attribute_value_form').data('store-url') || '';
    // Extract base path (remove /attribute-values from end)
    let basePath = storeUrl.replace(/\/attribute-values\/?$/, '');
    
    // Construct URLs following Laravel resource route pattern
    let editUrl = basePath + '/attribute-values/' + id + '/edit';
    let updateUrl = basePath + '/attribute-values/' + id;
    let deleteUrl = basePath + '/attribute-values/' + id;
    
    return `
    <div class="dropdown position-static">
        <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-three-dots-vertical"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a href="javascript:void(0);"
                   class="dropdown-item editValue"
                   data-edit-url="${editUrl}"
                   data-update-url="${updateUrl}">
                   <i class="align-middle ph-pencil me-1"></i> Edit
                </a>
            </li>
            <li>
                <a href="javascript:void(0);"
                   class="dropdown-item deleteValue"
                   data-url="${deleteUrl}">
                   <i class="align-middle ph-trash me-1"></i> Remove
                </a>
            </li>
        </ul>
    </div>`;
}
//End attribute values crud ajax

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.querySelector('#vec_store_country');
    if (categorySelect) {
        new Choices(categorySelect, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
            removeItemButton: true,
            placeholderValue: 'Select Country',
        });
    }
});


