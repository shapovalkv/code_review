import './assetTheme'
import './utils'
import './bootstrap';
import './advance-ajax-table';
import './anchor';
import './bottom-bar';
import './bulk-select';
import $ from 'jquery'
import toastr from 'toastr';
import Chart from 'chart.js/auto';
import Alpine from 'alpinejs';

window.toastr = toastr;
window.Chart = Chart;
window.jQuery = window.$ = $
window.Alpine = Alpine;

Alpine.start();

import './custom.js';

window.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const optionValue = urlParams.get("option");

    if (optionValue === "free_consultation") {
        const subjectInput = document.querySelector("input[name='subject']");
        if (subjectInput) {
            subjectInput.value = "Free consultation";
        }
    }
});

$(function() {
   $('body').on('click', function (e) {
       if ($(e.target).closest('.navbar-standard').length == 0) {
           e.stopPropagation();
           $('.navbar-standard .navbar-toggler').addClass('collapsed')
           $('.navbar-standard .scrollbar').removeClass('show')
       }
   })
});

$(function () {
    $('#registerForm').submit(function (e) {
        e.preventDefault();
        let formData = $(this).serializeArray();
        $(".invalid-feedback").children("strong").text("");
        $("#registerForm input").removeClass("is-invalid");

        $.ajax({
            method: "POST",
            headers: {
                Accept: "application/json"
            },
            url: "/modal-register",
            data: formData,
            success: (response) => {
                window.location.assign(response)
            },
            error: (response) => {
                let errors = response.responseJSON.errors;
                console.log(errors)
                Object.keys(errors).forEach(function (key) {
                    $("#" + key + "Input").addClass("is-invalid");
                    $("#" + key + "Error").children("strong").text(errors[key][0]);
                });
            }
        })
    });
})
