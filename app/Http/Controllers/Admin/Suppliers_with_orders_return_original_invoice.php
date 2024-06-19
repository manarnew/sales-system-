<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Suppliers_with_orders;
use App\Models\Suppliers_with_orders_details;
use App\Models\Inv_itemCard;
use App\Models\Inv_uom;
use App\Models\Store;
use App\Models\Admins_Shifts;
use App\Models\Treasuries;
use App\Models\Treasuries_transactions;
use App\Models\Inv_itemcard_movements;
use App\Models\Account;
use App\Models\Supplier;
use App\Models\Admin_panel_setting;
use App\Models\Inv_itemcard_batches;

class Suppliers_with_orders_return_original_invoice extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Suppliers_with_orders(), array("*"), array("com_code" => $com_code, 'order_type' => 2), 'id', 'DESC', PAGINATION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                $info->supplier_name = Supplier::where('suuplier_code', $info->suuplier_code)->value('name');
                $info->store_name = Store::where('id', $info->store_id)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        }
        $suupliers = get_cols_where(new Supplier(), array('suuplier_code', 'name'), array('com_code' => $com_code), 'id', 'DESC');
        $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        return view('admin.suppliers_with_orders_return_original_invoice.index', ['data' => $data, 'suupliers' => $suupliers, 'stores' => $stores]);
    }

    public function create()
    {
        $com_code = auth()->user()->com_code;
        $suppliers = get_cols_where(new Supplier(), array('suuplier_code', 'name'), array('com_code' => $com_code), 'id', 'DESC');
        return view('admin.suppliers_with_orders_return_original_invoice.create', ['suppliers' => $suppliers]);
    }

    public function get_supplierPurchaseInvoiceList(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $suppliers_code = $request->supplier_code;
            $supplierPurchaseInvoiceList = get_cols_where(new Suppliers_with_orders(), array('id', 'auto_serial', 'order_date'), array('com_code' => $com_code, 'suuplier_code' => $suppliers_code, 'is_approved' => 1, 'order_type' => 1));
        }

        return view('admin.suppliers_with_orders_return_original_invoice.get_supplierPurchaseInvoiceList', ['supplierPurchaseInvoiceList' => $supplierPurchaseInvoiceList]);
    }
    public function get_SuppliersBurchasesInvoicesDetails(Request $request)
    {
        if ($request->ajax()) {
            $auto_serial = $request->auto_serial;
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_with_orders(), array("*"), array("auto_serial" => $auto_serial, "com_code" => $com_code, 'order_type' => 1, 'is_approved' => 1));
            if (!empty($data)) {
                $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
                $data['supplier_name'] = Supplier::where('suuplier_code', $data['suuplier_code'])->value('name');
                $data['store_name'] = Store::where('id', $data['store_id'])->value('name');
                if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
                    $data['updated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
                }
                $details = get_cols_where(new Suppliers_with_orders_details(), array("*"), array('suppliers_with_orders_auto_serial' => $data['auto_serial'], 'order_type' => 1, 'com_code' => $com_code), 'id', 'DESC');
                if (!empty($details)) {
                    foreach ($details as $info) {
                        $info->item_card_name = Inv_itemCard::where('item_code', $info->item_code)->value('name');
                        $info->returned_quantity_before = get_sum_where(new Suppliers_with_orders_details, 'deliverd_quantity', array("com_code" => $com_code, 'suppliers_with_orders_details_id' => $info->id, 'order_type' => 2));
                        $info->batch_data = get_cols_where_row(new Inv_itemcard_batches(), array('quantity'), array('auto_serial' => $info->batch_auto_serial, 'item_code' => $info->item_code, 'com_code' => $com_code));
                        $info->allowed_return_balance = $info->deliverd_quantity - $info->returned_quantity_before;
                        if ($info->isparentuom == 0) {
                            $retail_uom_qtyToParent  = Inv_itemCard::where('item_code', $info->item_code)->value('retail_uom_quntToParent');
                            $info->batch_quantity = $info->batch_data['quantity'] * $retail_uom_qtyToParent;
                        } else {
                            $info->batch_quantity = $info->batch_data['quantity'];
                        }
                        $info->uom_name = get_field_value(new Inv_uom(), "name", array("id" => $info->uom_id));
                        $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
                        if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
                            $data['updated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
                        }
                    }
                }
                $itemsCounter = get_sum_where(new Suppliers_with_orders_details(), 'deliverd_quantity', array('suppliers_with_orders_auto_serial' => $data['auto_serial'], 'order_type' => 1, 'com_code' => $com_code));
                if ($data['tax_value']) {
                    $item_tax_value =  $data['tax_value'] / $itemsCounter;
                } else {
                    $item_tax_value = 0;
                }
                if ($data['discount_value']) {
                    $item_discount_value =  $data['discount_value'] / $itemsCounter;
                } else {
                    $item_discount_value = 0;
                }
                return view("admin.suppliers_with_orders_return_original_invoice.get_SuppliersBurchasesInvoicesDetails", ['data' => $data, 'details' => $details, 'item_discount_value' => $item_discount_value, 'item_tax_value' => $item_tax_value]);
            }
        }
    }
    public function do_approve(Request $request)
    {
        $com_code = auth()->user()->com_code;
        $original_invoice_id_for_return = $request->original_invoice_id_for_return;
        $original_invoice_data = get_cols_where_row(new Suppliers_with_orders(), array("id"), array("id" => $original_invoice_id_for_return, "com_code" => $com_code, 'order_type' => 1, 'is_approved' => 1));
        if (empty($original_invoice_data)) {
            return redirect()->back()->with(['error'=>'عفوا غير قادر علي الوصول الي بيانات فاتورة المشتريات الاصل']);
        }
        return "1 done";
    }
}
