// Reusable Media Picker Logic
window.initMediaPicker = function(options) {
    var pickerBtn = $(options.pickerBtnSelector);
    var modalBody = $(options.modalBodySelector);
    var modal = $(options.modalSelector);
    var hiddenInput = $(options.hiddenInputSelector);
    var previewDiv = $(options.previewSelector);
    var selectedMediaId = hiddenInput.val() || null;

    function openMediaPicker() {
        modal.attr('data-multi', options.multi ? '1' : '0');

        $.ajax({
            url: options.pickerUrl,
            type: "GET",
            success: function (html) {
                modalBody.html(html);

                if (options.multi) {
                    var selectedIds = [];
                    var existing = (hiddenInput.val() || '');
                    if (existing) {
                        // Try to read JSON first, then fall back to CSV.
                        try {
                            var parsed = JSON.parse(existing);
                            if (Array.isArray(parsed)) {
                                selectedIds = parsed.map(String);
                            } else if (parsed) {
                                selectedIds = [String(parsed)];
                            }
                        } catch (e) {
                            selectedIds = String(existing)
                                .split(',')
                                .map(function(id) {
                                    return String(id).replace(/[\[\]"]/g, '').trim();
                                })
                                .filter(function(id) {
                                    return id !== '';
                                });
                        }
                    }

                    modalBody.find('.media-thumb').each(function() {
                        var id = String($(this).data('id'));
                        if (selectedIds.indexOf(id) !== -1) {
                            $(this).addClass('picker-selected').css('outline', '3px solid #0d6efd');
                        }
                    });

                    modalBody.on('click', '.media-thumb', function (e) {
                        var $thumb = $(this);
                        var id = String($thumb.data('id'));
                        var idx = selectedIds.indexOf(id);
                        if (idx === -1) {
                            selectedIds.push(id);
                            $thumb.addClass('picker-selected').css('outline', '3px solid #0d6efd');
                        } else {
                            selectedIds.splice(idx, 1);
                            $thumb.removeClass('picker-selected').css('outline', '');
                        }
                    });

                    // ensure modal footer has confirm button
                    var $footer = modal.find('.modal-footer');
                    if (!$footer.length) {
                        $footer = $('<div class="modal-footer"></div>');
                        modal.find('.modal-content').append($footer);
                    }
                    $footer.html('');
                    var $confirm = $('<button type="button" class="btn btn-primary">Confirm selection</button>');
                    var $cancel = $('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>');
                    $footer.append($cancel).append($confirm);

                    $confirm.on('click', function () {
                        // Always normalize and store the IDs on the hidden input
                        hiddenInput.val(selectedIds.join(','));

                        // If a custom handler is provided (e.g. product edit gallery),
                        // delegate rendering/handling to that instead of duplicating UI logic here.
                        if (typeof options.onMediaSelected === 'function') {
                            var selectedMediaData = selectedIds.map(function(id) {
                                var $thumb = modalBody.find('.media-thumb').filter(function () {
                                    return String($(this).data('id')) === String(id);
                                });
                                return {
                                    id: id,
                                    url: $thumb.find('img').attr('src') || ''
                                };
                            });

                            options.onMediaSelected(selectedMediaData);
                        } else {
                            // Default behavior: render simple thumbnails with a remove button
                            previewDiv.empty();
                            if (selectedIds.length === 0) {
                                previewDiv.html('<div class="text-muted">No images selected</div>');
                            } else {
                                selectedIds.forEach(function(id) {
                                    var $thumb = $('.media-thumb').filter(function () {
                                        return String($(this).data('id')) === String(id);
                                    });
                                    var img = $thumb.find('img').attr('src') || '';
                                    var $card = $('<div class="position-relative me-2 mb-2" style="width:100px;height:100px;border:1px solid #e9e9e9;border-radius:6px;overflow:hidden">');
                                    if (img) {
                                        $card.append(
                                            $('<img>')
                                                .attr('src', img)
                                                .css({width: '100%', height: '100%', objectFit: 'cover'})
                                        );
                                    }
                                    var $btn = $('<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-gallery-image" data-id="'+id+'">✕</button>');
                                    $card.append($btn);
                                    previewDiv.append($card);
                                });
                            }
                        }

                        modal.find('.btn-close').trigger('click');
                    });

                } else {
                    var existing = hiddenInput.val() || '';
                    if (existing) {
                        modalBody.find('.media-thumb').each(function() {
                            if (String($(this).data('id')) === String(existing)) {
                                $(this).addClass('picker-selected').css('outline', '3px solid #0d6efd');
                                modal.data('picker-selected-id', existing);
                            }
                        });
                    }

                    // Clean previous footer and add Select/Cancel
                    var $footer = modal.find('.modal-footer');
                    if (!$footer.length) {
                        $footer = $('<div class="modal-footer"></div>');
                        modal.find('.modal-content').append($footer);
                    }
                    $footer.html('');
                    var $selectBtn = $('<button type="button" class="btn btn-primary">Select</button>');
                    var $cancelBtn = $('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>');
                    $footer.append($cancelBtn).append($selectBtn);

                    $selectBtn.on('click', function () {
                        var sel = modal.data('picker-selected-id') || null;
                        if (!sel) {
                            var $first = modalBody.find('.media-thumb').first();
                            if ($first.length) sel = $first.data('id');
                        }
                        if (!sel) {
                            return;
                        }

                        hiddenInput.val(sel);
                        var $thumb = modalBody.find('.media-thumb[data-id="' + sel + '"]');
                        var imgUrl = $thumb.find('img').attr('src');
                        if (imgUrl) previewDiv.html(`<img src="${imgUrl}" style="height:100px;width:100px;object-fit:cover;border-radius:4px;">`);
                        else previewDiv.html('<span class="badge bg-secondary">No Image</span>');
                        modal.find('.btn-close').trigger('click');
                    });
                }

            }
        });
    }

    pickerBtn.on('click', function () {
        openMediaPicker();
    });

    modal.on('hidden.bs.modal', function () {
        modal.removeData('picker-selected-id');
        modal.find('.picker-temp-preview').remove();
        modal.find('.modal-footer').html('');
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

    const colorSelect = document.querySelector('#productColors');
    if (colorSelect) {
        new Choices(colorSelect, {
            searchEnabled: true,
            removeItemButton: true,
            shouldSort: false,
            placeholderValue: 'Select colors',
        });
    }

    const productStockSelect = document.querySelector('#productStock');
    if (productStockSelect) {
        new Choices(productStockSelect, {
            searchEnabled: true,
            removeItemButton: true,
            shouldSort: false,
            placeholderValue: 'Select product',
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



/* =====================
   ACTION BUTTONS
===================== */
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


document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('.choices');
    elements.forEach(function(el) {
        new Choices(el, {
            removeItemButton: true,
            searchEnabled: true,
            shouldSort: false,
        });
    });
});