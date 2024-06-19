@if (@isset($data) && !@empty($data) )
<form action="{{ route('admin.Suppliers_orders_return_original_invoice.do_approve')}}" method="POST">
@csrf
<div class="row"> 
  <div class="col-md-12 bg-info">
    <p class="text-center">تفاصيل فاتورة المشتريات الاصل</p>
  </div>
  <div class="col-md-3">
    <label for="">كود الفاتورة الآلي</label>
    <input id="ajax_auto_serial" type="text" class="form-control" readonly value="{{$data['auto_serial']}}">
    <input type="hidden" name="original_invoice_id_for_return" value="{{$data['id']}}">

  </div>

 <div class="col-md-3">
    <label for="">تاريخ الفاتورة </label>
    <input type="text" class="form-control" readonly value="{{$data['order_date']}}">
  </div>
  <div class="col-md-3">
    <label for="">اسم المورد </label>
    <input type="text"class="form-control" readonly value="{{$data['supplier_name']}}">
  </div>

  <div class="col-md-3"> 
    <label>   نوع الفاتورة</label>
    <select disabled name="pill_type" id="pill_type" class="form-control">
    <option   @if($data['pill_type']==1) selected="selected"  @endif value="1">  كاش</option>
    <option @if($data['pill_type']==2 ) selected="selected"   @endif value="2">  اجل</option>
    </select>
 </div>
 <div class="col-md-3">
    <label for="">اسم المخرن المستلم </label>
    <input type="text"class="form-control" readonly value="{{$data['store_name']}}">
  </div>
  <div class="col-md-3">
    <label for="">اجمالي الاصناف </label>
    <input type="text"class="form-control" readonly value="{{$data['total_cost_items']*1}}">
  </div>
  <div class="col-md-3">
    <label for=""> القيمة المضافة </label>
    <input type="text"class="form-control" readonly value="{{$data['tax_value']*1}}">
  </div>
  <div class="col-md-3">
    <label for="">اجمالي الفاتورة قبل الخصم </label>
    <input type="text"class="form-control" readonly value="{{$data['total_befor_discount']*1}}">
  </div>
  <div class="col-md-3">
    <label for="">الخصم علي الفاتورة   </label>
    <input type="text"class="form-control" readonly value="{{$data['discount_value']*1}}">
  </div>
  <div class="col-md-3">
    <label for="">اجمالي  الفاتورة   </label>
    <input type="text"class="form-control" readonly value="{{$data['total_cost']*1}}">
  </div>
 </div>
 <p style="text-align: center;color: brown">الاصناف المضافة علي الفاتورة</p>
 <input type="hidden"class="form-control" id="item_discount_value" value="{{$item_discount_value}}">
 <input type="hidden"class="form-control" id="item_tax_value" value="{{$item_tax_value}}">
 <input type="hidden"class="form-control" id="tax_percent_original" value="{{$data['tax_percent']}}">
 <input type="hidden"class="form-control" id="discount_type_original" value="{{$data['discount_type']}}">
 <input type="hidden"class="form-control" id="discount_percent_original" value="{{$data['discount_percent']}}">
 <input type="hidden"class="form-control" id="discount_value_original" value="{{$data['discount_value']}}">

 @if (@isset($details) && !@empty($details) )
 <table id="example2" class="table table-bordered table-hover">
  <thead class="custom_thead">
     <th>اسم الصنف</th>
     <th> الوحدة</th>
     <th> الكمية بالاصل</th>
     <th>  الكمية المرتجعه سابقا</th>
     <th>   الرصيد المتاح ارجاعه</th>
     <th>    الكمية بالباتش حاليا</th>
     <th>  الكمية المرتجعه الان</th>
     <th> سعر الوحدة</th>
     <th>  الاجمالي</th>
  </thead>
  <tbody>
     @foreach ($details as $info )
     <tr>
        <td>{{$info->item_card_name}}</td>
        <td>{{$info->uom_name}}</td>
        <td>{{$info->deliverd_quantity *1}}</td>
        <td> <input style="width: 5vw" readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{$info->returned_quantity_before*1}}" type="text"class="returned_quantity_before"name="returned_quantity_before"  ></td>
        <td> <input  style="width: 5vw"  readonly value="{{$info->allowed_return_balance *1}}"  type="text"class="allowed_return_balance" name="allowed_return_balance[]" ></td>
        <td> <input  style="width: 5vw" readonly value="{{$info->batch_quantity *1}}"  type="text"class="batch_quantity" name="batch_quantity[]" ></td>
        <td> <input  style="width: 5vw" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="0"  type="text"class="returned_quantity_now" name="returned_quantity_now[]" ></td>
        <td> <input  style="width: 5vw" readonly type="text"class="unit_price" name="unit_price[]" value="{{$info->unit_price*1}}"></td>
        <td> <input  style="width: 5vw" readonly type="text" class="sup_total_row" name="sup_total_row[]" value="0"></td>
     </tr>                  
     @endforeach
  </tbody>
</table>

 <div class="row">
 <div class="col-md-3">
    <label for="">اجمالي الاصناف </label>
    <input type="text"class="form-control" id="total_items" name="total_items" readonly value="0">
  </div>
  <div class="col-md-3">
    <label for=""> القيمة المضافة </label>
    <input type="text"class="form-control" readonly id="tax_value" name="tax_value" value="0">
  </div>
  <div class="col-md-3">
    <label for="">اجمالي الفاتورة قبل الخصم </label>
    <input type="text"class="form-control" readonly id="total_befor_discount" name="total_befor_discount" value="0">
  </div>
  <div class="col-md-3">
    <label for="">الخصم علي الفاتورة   </label>
    <input type="text"class="form-control" readonly id="discount_value" name="discount_value" value="0">
  </div>
  <div class="col-md-3">
    <label for="">اجمالي  الفاتورة   </label>
    <input type="text"class="form-control" readonly id="total_cost" name="total_cost" value="0">
  </div>

   <div class="col-md-3">
    <button type="submit" style="margin-top: 35px" id="do_add_approve_invoice" class="btn btn-sm  btn-danger">اضافة واعتماد الفاتورة</button>
  </div>
</div>
 @else
 <div class="alert alert-danger">
    عفوا لاتوجد اصناف لعرضها !!
 </div>
 @endif
 @else
<div class="alert alert-danger">
   عفوا لاتوجد بيانات لعرضها !!
</div>
</form>
@endif