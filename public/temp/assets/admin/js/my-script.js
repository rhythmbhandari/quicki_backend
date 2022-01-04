$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on("click", ".item-delete", function () {

    var $button = $(this);
    var url_id = $button.attr("id");

    $row = $(this).closest("tr");
    
    Swal.fire({
        title: "Delete?",
        text: "Please ensure and then confirm!",
        type: "warning",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: !0
    }).then(function (e) {
        if (e.value === true) {
            $.ajax({
                type: 'GET',
                url: $button.data("url"),
                dataType: 'JSON',
                success: function (results) {
                    Swal.fire("Done!", "record has been deleted");
                    $row.addClass("danger").fadeOut();
                },
                error: function (results) {
                    Swal.fire("Error!", "failed to delete record");
                }
            });
        } else {
            e.dismiss;
        }
    }, function (dismiss) {
        return false;
    })
});

$(document).on("click", ".switch_btn", function () {

    var $button = $(this);
    var url_id = $button.attr("id");

    // $row = $(this).closest("tr");
    
    Swal.fire({
        title: "Switch?",
        text: "Please ensure and then confirm!",
        type: "warning",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonText: "Yes, switch to this vendor!",
        cancelButtonText: "No, cancel!",
        reverseButtons: !0
    }).then(function (e) {
        if (e.value === true) {
            $.ajax({
                type: 'GET',
                url: $button.data("url"),
                dataType: 'JSON',
                success: function (results) {
                    console.log(results)
                    Swal.fire("Done!", "Switched to vendor!");
                    window.location = "/admin/dashboard";
                    // $row.addClass("danger").fadeOut();
                },
                error: function (results) {
                    console.log(results)
                    Swal.fire("Error!", "failed to switch to vendor");
                }
            });
        } else {
            e.dismiss;
        }
    }, function (dismiss) {
        return false;
    })
});

$(document).on("click", '.toggle-status', function () {
    var $button = $(this);
    var url = $button.data("toggle-url");
    var data = $button.data("toggle-data");
    $row = $(this).closest("tr");
    $.ajax({
        "type": "POST",
        "url": url,
        "data": {
            obj: data

        },
        "success": function (data) {
            $row.addClass("danger").fadeOut();
        },
        "error": function (err) {
            bootbox.alert("Delete failed!");
        }
    });

});

$(document).on('click', '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});

$(window).on('load', function () {
    // Animate loader off screen
    $(".se-pre-con").fadeOut("slow");
});

// $(document).ready(function () { $(".custom-validation").parsley() });