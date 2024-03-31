function addMenuItem(value) {
    console.log("masuk sini");
    $("#mdlForm").modal("show");
    var form = $("#formMenuVendor");

    $("#mdlFormTitle").html("Form Menu Vendor");
    $("#mdlFormContent").empty;

    // Membuat input elemen form asinkron

    var form = $('<form id="formMenuVendor"></form>');
    form.append(
        '<div class="form-group mb-3" style="display:none;"><label for="id" class="form-label">Id</label><input type="hidden" class="form-control" id="id" name="id"' +
        (value != null ? ' value="' + value.id + '"' : "") +
        "></div>"
    );
    form.append(
        '<div class="form-group mb-3"><label for="menu_name" class="form-label">Menu Name</label><input type="text" class="form-control" id="menuName" name="menu_name"' +
        (value != null ? ' value="' + value.menu_name + '"' : "") +
        "></div>"
    );
    form.append(`
        <div class="form-group mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        `);
    form.append(`
            <div class="form-group mb-4">
                <label class="form-label text-muted opacity-75 fw-medium" for="formMenuVendor">Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
        `);
    form.append(`
        <div class="form-check">
            <input class="form-check-input" type="radio" name="spicy" id="spicy">
            <label class="form-check-label" for="spicy">
                Ya
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="not_spicy" id="not_spicy">
            <label class="form-check-label" for="not_spicy">
            Tidak
            </label>
        </div>
    `);
    form.append(`
                <div id="formMultiple" class="form_multiple">
                    <!-- Initial set of fields -->
                    <div class="row classformMultiple" id="row_1">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label">Size:</label>
                                <input type="text" class="form-control" name="size[]" id="size_1">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label">Price:</label>
                                <input type="text" class="form-control price-input" name="price[]" id="price_1">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label"></label>
                                <button type="button" class="btn btn-success addButton"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            `);

    form.append(
        '<button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>'
    );

    // Append the form container to the form
    const formContainer = document.createElement("div");
    formContainer.id = "formContainer";
    form.append(formContainer);
    $("#mdlFormContent").append(form);

    $("#formMultiple")
        .on("click", ".addButton", function () {
            const counter = $(".classformMultiple").length + 1;
            console.log("counter", counter);
            const newInputSet = $("#row_1").clone();
            newInputSet.attr("id", `row_${counter}`);
            newInputSet
                .find('[id^="size_"]')
                .attr("id", `size_${counter}`)
                .val("");
            newInputSet
                .find('[id^="price_"]')
                .attr("id", `price_${counter}`)
                .val("");
            $(".classformMultiple").last().after(newInputSet);
            if (counter !== 1) {
                newInputSet
                    .find(".addButton")
                    .replaceWith(
                        '<button type="button" class="btn btn-danger removeButton"><i class="fas fa-minus"></i></button>'
                    );
            }
        })
        .on("keyup", 'input[name^="price[]"]', function () {
            // Get the current value of the input field
            let priceText = $(this).val();
            // Remove all dots from the input value
            priceText = priceText.replace(/\./g, "");
            // Remove non-numeric characters from the input value
            let priceValue = parseFloat(priceText.replace(/[^\d.-]/g, ""));
            // If the parsed value is a valid number, format it into IDR format and set it back to the input field
            if (!isNaN(priceValue)) {
                $(this).val(formatRupiah(priceValue));
            }
        });

    $(document).on("click", ".removeButton", function () {
        $(this).closest(".row").remove();
    });

    $("#formMenuVendor").on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        // Get CSRF token value
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Append CSRF token to the form data
        formData.append('_token', csrfToken);

        $.ajax({
            url: '/users/menu/store',
            type: 'POST',
            data: formData,
            success: function (data) {
                console.log(data);
                $("#mdlForm").modal("hide");
                table.ajax.reload();
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
}
