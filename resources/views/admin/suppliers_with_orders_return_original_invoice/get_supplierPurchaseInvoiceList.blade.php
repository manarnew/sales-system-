@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
<div class="form-group">
    <label> فوتير المشتريات لهذا المورد   </label>
    <select name="supplierPurchaseInvoiceListId" id="supplierPurchaseInvoiceListId" class="form-control select2">
       <option value=""> اختر الفاتورة </option>
       @if (@isset($supplierPurchaseInvoiceList) && !@empty($supplierPurchaseInvoiceList))
       @foreach ($supplierPurchaseInvoiceList as $info )
       <option   value="{{ $info->auto_serial }}">  فاتورة رقم {{ $info->auto_serial }} بتاريخ {{$info->order_date}}</option>
       @endforeach
       @endif
    </select>
 </div>

 @section('script')
<script  src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
   //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });
</script>
@endsection