@extends('layouts.admin')
@section('title')
مرتجع المشتريات
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('contentheader')
حركات مخزنية
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.Suppliers_orders_return_original_invoice.index') }}">  فواتير مرتجع المشتريات باصل الفاتورة </a>
@endsection
@section('contentheaderactive')
اضافة
@endsection
@section('content')
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center"> اضافة  فاتورة مرتجع مشتريات باصل الفاتورة  </h3>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <form action="{{ route('admin.Suppliers_orders_return_original_invoice.store') }}" method="post" >
               <input type="hidden" id="token_search" value="{{csrf_token() }}">
               <input type="hidden" id="ajax_get_supplierPurchaseInvoiceList" value="{{ route('admin.Suppliers_orders_return_original_invoice.get_supplierPurchaseInvoiceList') }}">
               <input type="hidden" id="ajax_get_SuppliersBurchasesInvoicesDetails" value="{{ route('admin.Suppliers_orders_return_original_invoice.get_SuppliersBurchasesInvoicesDetails') }}">

               @csrf


               <div class="row">
               <div class="col-md-4">
                  <label>  بحث بكود فاتورة المشتريات الالي</label>
                  <input name="searchByOriginalBurchasesCode" id="searchByOriginalBurchasesCode" type="text"  class="form-control"    >
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>   بحث بالموردين</label>
                     <select name="suppliers_code_filter" id="suppliers_code_filter" class="form-control select2">
                        <option value="">اختر المورد</option>
                        @if (@isset($suppliers) && !@empty($suppliers))
                        @foreach ($suppliers as $info )
                        <option @if(old('suuplier_code')==$info->suuplier_code) selected="selected" @endif value="{{ $info->suuplier_code }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
                  </div>
               </div>
               <div class="col-md-4" id="SuppliersBurchasesInvoicesDiv">

               </div>
              
            </div>
        
            </form>
            <div  id="SuppliersBurchasesInvoicesDetails">

            </div>
         </div>
      </div>
   </div>
</div>
</div>
@endsection
@section("script")
<script src="{{ asset('assets/admin/js/suppliers_with_orders_return_original_invoice.js') }}"></script>
<script  src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
   //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });
</script>
@endsection