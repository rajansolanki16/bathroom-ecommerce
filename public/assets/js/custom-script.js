$(document).ready(function () {
    hide_loader();
    Scroll();


    $('.ko-home-room-select').on('change', function () {
        max_qry = $(this).find('option:selected').data('max_qty');
        $('#ko-home-room-qty').attr('max', max_qry);
    });

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

    /* date picker js start */
    var checkinInput = $(".checkin_date_picker");
    var checkoutInput = $(".checkout_date_picker");

    if (checkinInput && checkoutInput) {
        var checkinDate = checkinInput.val()
            ? new Date(checkinInput.val())
            : null;
        var minCheckoutDate = new Date(checkinDate);

        var checkinPicker = $(".checkin_date_picker").flatpickr({
            dateFormat: "Y-m-d",
            minDate: "today",
            defaultDate: $(".checkin_date_picker").data("old"),
            onChange: function (selectedDates) {
                if (selectedDates.length > 0) {
                    var minCheckoutDate = new Date(selectedDates[0]);
                    minCheckoutDate.setDate(minCheckoutDate.getDate() + 1);
                    checkoutPicker.set("minDate", minCheckoutDate);
                    checkoutPicker.setDate(minCheckoutDate);
                }
                $(".checkout_date_picker").focus();
            },
        });

        $(".checkin_date_picker").on("change", function () {
            $(".checkout_date_picker").focus();
        });

        var checkoutPicker = $(".checkout_date_picker").flatpickr({
            dateFormat: "Y-m-d",
            minDate: new Date().fp_incr(1),
            defaultDate: $(".checkout_date_picker").data("old"),
        });
    }
    /* date picker js end */

    /* cart page js start */
    $("#ko_cart_room_count_inc").click(function (e) {
        e.preventDefault();
        var counter = $("#ko_cart_room_count");
        counter.val(Number(counter.val()) + 1);
        update_cart_page_price();
    });

    $("#ko_cart_room_count_dec").click(function (e) {
        e.preventDefault();
        var counter = $("#ko_cart_room_count");
        if (Number(counter.val()) > 1) {
            counter.val(Number(counter.val()) - 1);
            update_cart_page_price();
        }
    });

    $("#ko_cart_remove_room").click(function (e) {
        e.preventDefault();
        $(this).css("opacity", "0.5");

        ajax_url = $(this).data("url");
        var token = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: ajax_url,
            type: "POST",
            data: {
                _token: token,
            },
            success: function (response) {
                if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                }
            },
            error: function (xhr) {
                console.error("Submission error:", xhr);
            },
        });
    });

    $("#add_customer_note").on("change", function () {
        if ($(this).is(":checked")) {
            $('[name="guest_note"]').css("display", "block");
        } else {
            $('[name="guest_note"]').css("display", "none");
        }
    });

    /* cart page js end */

    /* booking page js start */

    $("#ko_booking_form").on("submit", function (e) {
        e.preventDefault();

        var cin = $("#booking-data-check_in").val();
        var cout = $("#booking-data-check_out").val();
        var qty = $("#booking-data-quantity");
        var rslug = $("#booking-data-hiddens").val();
        var ajax_url = $("#booking-data-hiddens").data("url");
        var token = $('meta[name="csrf-token"]').attr("content");
        var submit_btn = $("#ko-book-form-sumbit");

        submit_btn
            .prop("disabled", true)
            .addClass("loading")
            .text("Checking Dates..");

        $.ajax({
            url: ajax_url,
            type: "POST",
            data: {
                room: rslug,
                check_in: cin,
                check_out: cout,
                quantity: qty.val(),
                _token: token,
            },
            success: function (response) {
                submit_btn
                    .prop("disabled", false)
                    .removeClass("loading")
                    .text("Book Your Stay");

                if (response.status == 1) {
                    $("#ko_booking_form")[0].submit();
                } else {
                    console.log(response.message);
                    qty.attr("data-disable", "1");
                    $(".ko-room-page-errors").html("<div class='invalid-response ko-room-error' style='font-size: 1rem; display:block;' >"+response.message+"</div>").show();
                }
            },
            error: function (xhr) {
                error
                $(".ko-room-page-errors").html("<div class='invalid-response ko-room-error' style='font-size: 1rem; display:block;' >An error occurred. Please try again.</div>").show();

                submit_btn
                    .prop("disabled", false)
                    .removeClass("loading")
                    .text("Book Your Stay");
            },
        });
    });

    $(".qty-btn-plus").click(function () {
        const container = $(this).closest(".qty-container");
        const inputQty = container.find(".input-qty");
        inputQty.val(Number(inputQty.val()) + 1);

        updateGrandTotal();
        updateHomeRoomQty();
    });

    $(".qty-btn-minus").click(function () {
        const container = $(this).closest(".qty-container");
        const inputQty = container.find(".input-qty");
        const amount = Number(inputQty.val());
        if (amount > 0) {
            inputQty.val(amount - 1);
        }

        updateGrandTotal();
    });

    $(
        "#booking-data-adults, #booking-data-children, #booking-data-quantity, #booking-data-extra_beds"
    ).on("change blur", updateGrandTotal);

    $('.ko-deluxe-reserve input[type="checkbox"]').on(
        "change",
        updateGrandTotal
    );
    $('.ko-deluxe-reserve input[type="text"]').on("change", updateGrandTotal);

    if ($("#booking-data-check_in, #booking-data-check_out")) {
        updateGrandTotal();
    }

    /* booking page js end */

    if (window.location.hash === "#bookings") {
        $(".ko-myacc-tab-wrap div button:eq(1)").addClass("active");
        $("#tab2").css("display", "block");
    }

    /* tabs js start */
    let check_tab_elm = setInterval(() => {
        if (
            document.querySelectorAll("[data-hunter-tabs]").length > 0 &&
            document.querySelectorAll("[data-target-tabs]").length > 0
        ) {
            clearInterval(check_tab_elm);
            document
                .querySelectorAll("[data-hunter-tabs]")
                .forEach((hunterelm, hunterindex) => {
                    if (
                        hunterelm.querySelectorAll("[data-hunter-item]")
                            .length > 0
                    ) {
                        hunterelm
                            .querySelectorAll("[data-hunter-item]")
                            .forEach((itemelm, itemindex) => {
                                itemelm.addEventListener("click", () => {
                                    let targetval =
                                        itemelm.getAttribute(
                                            "data-hunter-item"
                                        );
                                    if (
                                        document.querySelectorAll(
                                            `[data-target-tabs] [data-target-item="${targetval}"]`
                                        ).length > 0
                                    ) {
                                        hunterelm
                                            .querySelector(
                                                "[data-hunter-item].active"
                                            )
                                            .classList.remove("active");
                                        itemelm.classList.add("active");
                                        hunterelm.parentNode
                                            .querySelector(
                                                "[data-target-tabs] [data-target-item].active"
                                            )
                                            .classList.remove("active");
                                        hunterelm.parentNode
                                            .querySelector(
                                                `[data-target-tabs] [data-target-item="${targetval}"]`
                                            )
                                            .classList.add("active");
                                    }
                                });
                            });
                    }
                });
        }
    });
    /* tabs js end */

    if (document.querySelectorAll(".ko-accordion-item-header").length > 0) {
        const accordionItemHeaders = document.querySelectorAll(
            ".ko-accordion-item-header"
        );
        accordionItemHeaders.forEach((accordionItemHeader) => {
            accordionItemHeader.addEventListener("click", (event) => {
                // Remove active class from all other accordion headers
                accordionItemHeaders.forEach((item) => {
                    if (item !== accordionItemHeader) {
                        item.classList.remove("active");
                        item.nextElementSibling.style.maxHeight = 0;
                    }
                });

                // Toggle active class for clicked header
                accordionItemHeader.classList.toggle("active");
                const accordionItemBody =
                    accordionItemHeader.nextElementSibling;
                if (accordionItemHeader.classList.contains("active")) {
                    accordionItemBody.style.maxHeight =
                        accordionItemBody.scrollHeight + "px";
                } else {
                    accordionItemBody.style.maxHeight = 0;
                }
            });
        });
    }
});

/* Header JS start */
document.addEventListener("touchmove", Scroll, false);
document.addEventListener("scroll", Scroll, false);
document.body.addEventListener("scroll", Scroll, false);
window.addEventListener("resize", Scroll, false);

/* JS : Mobile menu */
var oepnMenu = document.querySelector(".ko-toogle-btn");
var closeMenu = document.querySelector(".ko-close-btn");
var sideBar = document.querySelector(".ko-header-menu");
var haederEl = document.querySelector(".site-header");
oepnMenu.addEventListener("click", show);
closeMenu.addEventListener("click", hide);

const accordionItemHeaders = document.querySelectorAll(
    ".ko-has-dropdown > a, .ko-cartTotals-head"
);
accordionItemHeaders.forEach((accordionItemHeader) => {
    accordionItemHeader.addEventListener("click", (event) => {
        accordionItemHeader.classList.toggle("active");
        const accordionItemBody = accordionItemHeader.nextElementSibling;
        if (accordionItemHeader.classList.contains("active")) {
            accordionItemBody.style.maxHeight =
                accordionItemBody.scrollHeight + "px";
        } else {
            accordionItemBody.style.maxHeight = 0;
        }
    });
});
/* Header JS End */

/* testimonials slider script */
if (document.getElementsByClassName("ko-splide-testimonials").length > 0) {
    document.addEventListener("DOMContentLoaded", function () {
        var splidetesto = new Splide(".ko-splide-testimonials", {
            perPage: 1,
            rewind: true,
            arrows: false,
            pagination: true,
            perMove: 1,
            type: "loop",
        });
        splidetesto.mount();
    });
}

if (document.getElementsByClassName("ko-splide-rooms").length > 0) {
    document.addEventListener("DOMContentLoaded", function () {
        var spliderooms = new Splide(".ko-splide-rooms", {
            type: "loop",
            perPage: 1,
            arrows: true,
            pagination: false,
            focus: "center",
            autoWidth: true,
            perMove: 1,
        });
        spliderooms.mount();
    });
}

var scrollToTopBtn = document.querySelector(".scrollToTopBtn");
var rootElement = document.documentElement;

scrollToTopBtn.addEventListener("click", scrollToTop);
document.addEventListener("scroll", handleScroll);

/* Golbel Loader*/
window.addEventListener("load", function () {
    setTimeout(function () {
        document.querySelector(".loader-wrap").classList.add("loaded");
    }, 1200);
});

/* Login Register Password JS */
var hideShowEl = document.querySelector("#pass_hideShow");
var loginPassEl = document.getElementById("ko_loginRegister_input");
if (loginPassEl != null) {
    hideShowEl.addEventListener("click", function () {
        loginPassword();
    });
}

/* signup js start */
$("#country_code").select2({
    placeholder: "Search country code...",
    allowClear: true,
    width: "100%",
});

$("#country_code").change(function () {
    get_states();
    var countryCode = $("#country_code option:selected").data("country-code");
    $("#ko-register-mobile").val(countryCode);
});

function get_states() {
    const ajax_url = $("#country_code").data("url");
    var token = $('meta[name="csrf-token"]').attr("content");
    var countryName = $("#country_code").val();
    var countryCode = $("#country_code option:selected").data("country-code");
    var state = $("#state");
    var oldState = state.data("value");
    state.empty();
    state.append(
        '<option value="" disabled selected>Please select state</option>'
    );

    if (countryCode && countryName) {
        $.ajax({
            url: ajax_url,
            type: "post",
            data: {
                country_code: countryCode,
                country_name: countryName,
                _token: token,
            },
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    var selected = key == oldState ? "selected" : "";
                    state.append(
                        '<option value="' +
                            key +
                            '" ' +
                            selected +
                            ">" +
                            value +
                            "</option>"
                    );
                });
            },
            error: function () {
                console.log("Error fetching states");
            },
        });
    }
}

/* signup js end */

function updateGrandTotal() {
    let roomPrice = parseFloat($("#booking-data-quantity").data("price"));
    let bedPrice = parseFloat($("#booking-data-extra_beds").data("price"));
    var extraBeds = parseInt($("#booking-data-extra_beds").val());
    var adult_count = parseInt($("#booking-data-adults").val());
    var extra_beds = parseInt($("#booking-data-extra_beds").val());
    var children_count = parseInt($("#booking-data-children").val());
    var roomQuantity = parseInt($("#booking-data-quantity").val());
    var checkIn = $("#booking-data-check_in").val();
    var checkOut = $("#booking-data-check_out").val();
    var max_guest = parseInt($("#booking-data-hiddens").data("max_guest"));
    var max_rooms = parseInt($("#booking-data-hiddens").data("max_rooms"));
    var max_extra_beds = parseInt($("#booking-data-hiddens").data("max_extra_beds"));
    let total = 0;

    if (roomQuantity > max_rooms) {
        $("#booking-data-quantity").val(max_rooms);
        roomQuantity = parseInt($("#booking-data-quantity").val());
    }
    if (roomQuantity == 0) {
        $("#booking-data-quantity").val(1);
        roomQuantity = parseInt($("#booking-data-quantity").val());
    }

    if (adult_count > roomQuantity * max_guest + extraBeds) {
        $("#booking-data-adults").val(roomQuantity * max_guest + extraBeds);
    }
    if (adult_count <= 0) {
        $("#booking-data-adults").val(1);
    }

    if (extra_beds > max_extra_beds * roomQuantity) {
        $("#booking-data-extra_beds").val(max_extra_beds);
    }

    if (extraBeds > roomQuantity * 3) {
        $("#booking-data-extra_beds").val(roomQuantity * 3);
    }

    if (children_count > roomQuantity * 3) {
        $("#booking-data-children").val(roomQuantity * 3);
    }

    extraBeds = parseInt($("#booking-data-extra_beds").val()) || 0;

    if (checkIn && checkOut) {
        var checkInDate = new Date(checkIn);
        var checkOutDate = new Date(checkOut);
        var timeDiff = checkOutDate - checkInDate;
        var dayGap = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
        var days = dayGap > 0 ? dayGap : 1;

        $('input[name="services[]"]:checked:not(:disabled)').each(function () {
            let servicePrice = parseFloat($(this).data("price")) || 0;
            total += servicePrice;
        });

        total += roomPrice * roomQuantity;
        total += bedPrice * extraBeds;

        var grand_total = days * total;

        $("#booking-grand-total").text(grand_total.toFixed(0));
    }
}

function Scroll() {
    let logoLink = document.getElementById("ko_header_logo_link");
    let allow_b_w = document.getElementById("ko_header_allow_b_w");
    let darkLogo = logoLink.querySelector("img:nth-child(1)");
    let lightLogo = logoLink.querySelector("img:nth-child(2)");
    let menuLinks = document.querySelectorAll(".ko-header-menu a");
    var mainElementPosition = document.querySelector(".site-header");
    var elementPosition = mainElementPosition.offsetTop;
    var windowScroll = window.scrollY;
    var bodyScroll = document.body.scrollTop;
    var checkScroll = windowScroll > bodyScroll ? windowScroll : bodyScroll;
    if (Number(allow_b_w.value) == 1) {
        if (checkScroll > elementPosition) {
            document.body.classList.add("sticky-mode");
            darkLogo.style.display = "block";
            lightLogo.style.display = "none";
            menuLinks.forEach((link, index) => {
                if (index != menuLinks.length - 1) {
                    link.classList.add("ko-header-text-dark");
                    link.classList.remove("ko-header-text-light");
                }
            });
        } else {
            document.body.classList.remove("sticky-mode");
            darkLogo.style.display = "none";
            lightLogo.style.display = "block";
            menuLinks.forEach((link, index) => {
                if (index != menuLinks.length - 1) {
                    link.classList.remove("ko-header-text-dark");
                    link.classList.add("ko-header-text-light");
                }
            });
        }
    } else {
        document.body.classList.add("sticky-mode");
        darkLogo.style.display = "block";
        lightLogo.style.display = "none";
        menuLinks.forEach((link, index) => {
            if (index != menuLinks.length - 1) {
                link.classList.add("ko-header-text-dark");
                link.classList.remove("ko-header-text-light");
            }
        });
    }
}

function loginPassword() {
    if (loginPassEl.type === "password") {
        loginPassEl.type = "text";
    } else {
        loginPassEl.type = "password";
    }
}

function handleScroll() {
    var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight;
    if (rootElement.scrollTop / scrollTotal > 0.08) {
        scrollToTopBtn.classList.add("showBtn");
    } else {
        scrollToTopBtn.classList.remove("showBtn");
    }
}

function scrollToTop() {
    rootElement.scrollTo({
        top: 0,
        behavior: "smooth",
    });
}

function show() {
    haederEl.classList.toggle("open-menu");
    sideBar.classList.add("active");
}
function hide() {
    haederEl.classList.remove("open-menu");
    sideBar.classList.remove("active");
}

function hide_loader() {
    $(".loader-wrap").css("display", "none");
}

function update_cart_page_price() {
    var count = $("#ko_cart_room_count").val(); //current room count
    var ct = $("#ko_cart_cost_total");  // total cost
    var st = $("#ko_cart_sub_total");
    var gt = $("#ko_cart_grand_total");
    var data = $("#cart-data-hiddens");
    var max_allow = $("#ko_cart_room_count").data("max");
    let qty = data.data("qty");

    if (count > max_allow) {
        $("#ko_cart_room_count").val(max_allow);
    }
    var count = $("#ko_cart_room_count").val();

    var checkInDate = new Date(data.data("c_in"));
    var checkOutDate = new Date(data.data("c_out"));
    var timeDiff = checkOutDate - checkInDate;
    var dayGap = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
    var days = dayGap > 0 ? dayGap : 1;

    let room_charges = Number(data.data("rp")) * days;
    let twrc = Number(data.data("total_cost"));
    let total = twrc + ( room_charges * (count - qty));
    ct.text(total);
    gt.text(total);
    st.text(total);
}

function updateHomeRoomQty(){
    allow_max_qty = $('#ko-home-room-qty').attr('max');
    current_qty = $('#ko-home-room-qty').val();

    if(current_qty > allow_max_qty){
        $('#ko-home-room-qty').val(allow_max_qty);
    }
}

window.addEventListener('scroll', () => {
    document.querySelector('.scrollToTopBtn').style.display =
        window.scrollY > 300 ? 'flex' : 'none';
});

document.querySelector('.scrollToTopBtn')?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});