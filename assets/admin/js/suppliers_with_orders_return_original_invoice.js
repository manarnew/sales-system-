$(document).ready(function () {

    $(document).on('click', '#ajax_pagination_in_search a ', function (e) {
        e.preventDefault();
        var searchbyradio = $("input[type=radio][name=searchbyradio]:checked").val();
        var suuplier_code = $("#suuplier_code_search").val();
        var search_by_text = $("#search_by_text").val();
        var store_id = $("#store_id_search").val();
        var order_date_form = $("#order_date_form").val();
        var order_date_to = $("#order_date_to").val();
        var token_search = $("#token_search").val();
        var url = $(this).attr("href");
        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search,
                searchbyradio: searchbyradio,
                suuplier_code: suuplier_code,
                store_id: store_id,
                order_date_form: order_date_form,
                order_date_to: order_date_to,
                search_by_text: search_by_text
            },
            success: function (data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function () {}
        });
    });
    $(document).on('change', '#suppliers_code_filter', function (e) {
        var token_search = $("#token_search").val();
        var url = $("#ajax_get_supplierPurchaseInvoiceList").val();
        var supplier_code = $(this).val();
        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search,
                supplier_code: supplier_code,
            },
            success: function (data) {
                $("#SuppliersBurchasesInvoicesDiv").html(data);
                $(".select2").select2();
            },
            error: function () {}
        });
    });
    $(document).on('input', '#searchByOriginalBurchasesCode', function (e) {
        if ($("#ajax_auto_serial").length) {
            var result = confirm("هل انت متاكد لانه سيتم مسح المحتوى الحالي ان وجد");
            if (!result) {
                return false;
            }
        }
        var auto_serial = $(this).val();
        if (auto_serial == "") {
            $("SuppliersBurchasesInvoicesDetails").html("");
        } else {
            var token_search = $("#token_search").val();
            var url = $("#ajax_get_SuppliersBurchasesInvoicesDetails").val();
            jQuery.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": token_search,
                    auto_serial: auto_serial,
                },
                success: function (data) {
                    $("#SuppliersBurchasesInvoicesDetails").html(data);
                },
                error: function () {}
            });
        }
    });
    $(document).on('change', '#supplierPurchaseInvoiceListId', function (e) {
        if ($("#ajax_auto_serial").length) {
            var result = confirm("هل انت متاكد لانه سيتم مسح المحتوى الحالي ان وجد");
            if (!result) {
                return false;
            }
        }
        var auto_serial = $(this).val();
        if (auto_serial == "") {
            $("SuppliersBurchasesInvoicesDetails").html("");
        } else {
            var token_search = $("#token_search").val();
            var url = $("#ajax_get_SuppliersBurchasesInvoicesDetails").val();
            jQuery.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": token_search,
                    auto_serial: auto_serial,
                },
                success: function (data) {
                    $("#SuppliersBurchasesInvoicesDetails").html(data);
                },
                error: function () {}
            });
        }
    });

    $(document).on('input', '.returned_quantity_now', function (e) {
        var returned_quantity_now = $(this).val();
        if (returned_quantity_now == "") returned_quantity_now = 0;
        var allowed_return_balance = $(this).closest("tr").find(".allowed_return_balance").val();
        if (allowed_return_balance == "") allowed_return_balance = 0;
        var batch_quantity = $(this).closest("tr").find(".batch_quantity").val();
        if (batch_quantity == "") batch_quantity = 0;
        if (parseFloat(returned_quantity_now) > parseFloat(allowed_return_balance)) {
            alert("عفوا الكمية المرتجعة اكبر من الرصيد المتاح ارجاعه بالفاتورة");
            var allowed_return_balance = $(this).closest("tr").find(".returned_quantity_now").val(0);
            var allowed_return_balance = $(this).closest("tr").find(".returned_quantity_now").focus();
            $(this).closest("tr").find(".sup_total_row").val(0);
            return false;
        }
        if (parseFloat(returned_quantity_now) > parseFloat(batch_quantity)) {
            alert("عفوا الكمية المرتجعة اكبر من الرصيد المتاح بالباتش الحاليه");
            var allowed_return_balance = $(this).closest("tr").find(".returned_quantity_now").val(0);
            var allowed_return_balance = $(this).closest("tr").find(".returned_quantity_now").focus();
            $(this).closest("tr").find(".sup_total_row").val(0);
            return false;
        }
        var unit_price = $(this).closest("tr").find(".unit_price").val();
        if (unit_price == "") unit_price = 0;
        $(this).closest("tr").find(".sup_total_row").val(parseFloat((returned_quantity_now * unit_price) * 1));
        recalculate_invoice();

    });
    $(document).on('change', '.returned_quantity_now', function (e) {
        var returned_quantity_now = $(this).val();
        if (returned_quantity_now == "") {
            returned_quantity_now = 0;
            $(this).val(0);
        };
        var allowed_return_balance = $(this).closest("tr").find(".allowed_return_balance").val();
        if (allowed_return_balance == "") allowed_return_balance = 0;
        var batch_quantity = $(this).closest("tr").find(".batch_quantity").val();
        if (batch_quantity == "") batch_quantity = 0;
        if (parseFloat(returned_quantity_now) > parseFloat(allowed_return_balance)) {
            alert("عفوا الكمية المرتجعة اكبر من الرصيد المتاح ارجاعه بالفاتورة");
            var allowed_return_balance = $(this).closest("tr").find(".returned_quantity_now").val(0);
            var allowed_return_balance = $(this).closest("tr").find(".returned_quantity_now").focus();
            $(this).closest("tr").find(".sup_total_row").val(0);
            return false;
        }
        if (parseFloat(returned_quantity_now) > parseFloat(batch_quantity)) {
            alert("عفوا الكمية المرتجعة اكبر من الرصيد المتاح بالباتش الحاليه");
            var allowed_return_balance = $(this).closest("tr").find(".returned_quantity_now").val(0);
            var allowed_return_balance = $(this).closest("tr").find(".returned_quantity_now").focus();
            $(this).closest("tr").find(".sup_total_row").val(0);
            return false;
        }
        var unit_price = $(this).closest("tr").find(".unit_price").val();
        if (unit_price == "") unit_price = 0;
        $(this).closest("tr").find(".sup_total_row").val(parseFloat((returned_quantity_now * unit_price) * 1));
        recalculate_invoice();

    });

    function recalculate_invoice() {
        var sup_total_all_row = 0;
        $(".sup_total_row").each(function () {
            if($(this).val()!=''&&$(this).val()!=null&&$(this).val()!=NaN){
                sup_total_all_row += parseFloat($(this).val());
            }
            
        });
        var returned_quantity_now_all = 0;
        $(".returned_quantity_now").each(function () {
            if($(this).val()!=''&&$(this).val()!=null&&$(this).val()!=NaN){
                returned_quantity_now_all += parseFloat($(this).val());
            }
            
        });
        $("#total_items").val(sup_total_all_row * 1);
        var tax_percent = $("#tax_percent_original").val();
        tax_value = (sup_total_all_row * tax_percent) / 100;
        $("#tax_value").val(tax_value * 1);
        total_befor_discount = tax_value + sup_total_all_row;
        $("#total_befor_discount").val(total_befor_discount);
        var discount_value_original = $("#discount_value_original").val();
        if (discount_value_original > 0) {

            var discount_type = $("#discount_type_original").val();
            if (discount_type == 1) {
                var discount_percent = $("#discount_percent_original").val();
                var discount_value = (total_befor_discount * discount_percent) / 100;
            } else {
                var item_discount_value = $("#item_discount_value").val();
                var discount_value = (item_discount_value * returned_quantity_now_all);
            }
        } else {
            var discount_value = 0;
        }
        $("#discount_value").val(Math.floor(discount_value) * 1);
        $("#total_cost").val(Math.ceil(total_befor_discount - discount_value) * 1);
    }
    $(document).on('click', '#do_add_approve_invoice', function (e) {
        var returned_quantity_now_all = 0;
        $(".returned_quantity_now").each(function () {
            if($(this).val()!=''&&$(this).val()!=null&&$(this).val()!=NaN){
                returned_quantity_now_all += parseFloat($(this).val());
            }
        });
        if(returned_quantity_now_all == 0){
            alert("عفوا يجب ادخال علي الاقل كمية واحده مرتجعة");
            e.preventDefault();
            return false;
        }
    });
});
