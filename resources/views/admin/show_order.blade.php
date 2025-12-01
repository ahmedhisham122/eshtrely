@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Invoice')}}</title>
@endsection

@section('style')
<style>
  @media print {
      .section-header,
      .order-status,
      #sidebar-wrapper,
      .print-area,
      .main-footer,
      .additional_info {
          display:none!important;
      }

  }
</style>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Invoice')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Invoice')}}</div>
            </div>
          </div>
          <div class="section-body">
            <div class="invoice">
              <div class="invoice-print">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                      <h2><img src="{{ asset($setting->logo) }}" alt="" width="120px"></h2>
                      <div class="invoice-number">Order #{{ $order->order_id }}</div>
                    </div>
                    <hr>
                    @php
                        $orderAddress = $order->orderAddress;
                    @endphp
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong>{{__('admin.Billing Information')}}:</strong><br>
                            {{ $orderAddress->billing_name }}<br>
                            @if ($orderAddress->billing_email)
                            {{ $orderAddress->billing_email }}<br>
                            @endif
                            @if ($orderAddress->billing_phone)
                            {{ $orderAddress->billing_phone }}<br>
                            @endif
                            {{ $orderAddress->billing_address }},
                            {{ $orderAddress->billing_city.', '. $orderAddress->billing_state.', '.$orderAddress->billing_country }}<br>
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong>{{__('admin.Shipping Information')}} :</strong><br>
                          {{ $orderAddress->shipping_name }}<br>
                            @if ($orderAddress->shipping_email)
                            {{ $orderAddress->shipping_email }}<br>
                            @endif
                            @if ($orderAddress->shipping_phone)
                            {{ $orderAddress->shipping_phone }}<br>
                            @endif
                            {{ $orderAddress->shipping_address }},
                            {{ $orderAddress->shipping_city.', '. $orderAddress->shipping_state.', '.$orderAddress->shipping_country }}<br>
                        </address>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong>{{__('admin.Payment Information')}}:</strong><br>
                          {{__('admin.Method')}}: {{ $order->payment_method }}<br>
                          {{__('admin.Status')}} : @if ($order->payment_status == 1)
                              <span class="badge badge-success">{{__('admin.Success')}}</span>
                              @else
                              <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                          @endif <br>
                          {{__('admin.Transaction')}}: {!! clean(nl2br($order->transection_id)) !!}
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong>{{__('admin.Order Information')}}:</strong><br>
                          {{__('admin.Date')}}: {{ $order->created_at->format('d F, Y') }}<br>
                          {{__('admin.Shipping')}}: {{ $order->shipping_method }}<br>
                          {{__('admin.Status')}} :
                          @if ($order->order_status == 1)
                          <span class="badge badge-success">{{__('admin.Pregress')}} </span>
                          @elseif ($order->order_status == 2)
                          <span class="badge badge-success">{{__('admin.Delivered')}} </span>
                          @elseif ($order->order_status == 3)
                          <span class="badge badge-success">{{__('admin.Completed')}} </span>
                          @elseif ($order->order_status == 4)
                          <span class="badge badge-danger">{{__('admin.Declined')}} </span>
                          @else
                          <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                        @endif
                        </address>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                   <div class="row">
                    <div class="col-md-6">
                      <div class="section-title">{{__('admin.Order Summary')}}</div>
                    </div>
                    <div class="col-md-6">
                      <div class="d-flex flex-column align-items-end">
                        <div class="text-md-right print-area pt-4">
                          <button type="button" class="btn btn-success btn-icon icon-left"  data-toggle="modal"
                          data-target="#exampleModalLong-2">
                            {{__('Add New Product') }} 
                          </button>
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong-3">
                            {{__('Add Item') }}  
                          </button>
                        </div>

                        





                    </div>
                    </div>
                   </div>
                    
                    <div class="table-responsive">
                      <table class="table table-striped table-hover table-md">
                        <tr>
                          <th width="5%">#</th>
                          <th width="25%">{{__('admin.Product')}}</th>
                          <th width="10%">{{__('admin.Variant')}}</th>
                          @if ($setting->enable_multivendor == 1)
                          <th width="10%">{{__('admin.Shop Name')}}</th>
                          @endif
                          <th width="15%" class="text-center">{{__('admin.Unit Price')}}</th>
                          <th width="15%" class="text-center">{{__('admin.Quantity')}}</th>
                          <th width="10%" class="text-center">{{__('admin.Total')}}</th>
                          <th width="10%" class="text-right action-btn">{{__('admin.Action')}}</th>
                        </tr>
                        @php
                            $subTotal = 0;
                        @endphp
                        @foreach ($order->orderProducts as $index => $orderProduct)
                            @php
                                $variantPrice = 0;
                                $totalVariant = $orderProduct->orderProductVariants->count();
                            @endphp
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td><a href="">{{ $orderProduct->product_name }}</a></td>
                                <td>
                                    @foreach ($orderProduct->orderProductVariants as $indx => $variant)
                                        {{ $variant->variant_name.' : '.$variant->variant_value }}{{ $totalVariant == ++$indx ? '' : ',' }}
                                        <br>
                                        @php
                                            $variantPrice += $variant->variant_price;
                                        @endphp
                                    @endforeach

                                </td>
                                @if ($setting->enable_multivendor == 1)
                                <td>
                                    @if ($orderProduct->seller)
                                        <a href="{{ route('admin.seller-show',$orderProduct->seller->id) }}">{{  $orderProduct->seller->shop_name }}</a>
                                    @endif
                                </td>
                                @endif
                                <td class="text-center">{{ $setting->currency_icon }}{{ $orderProduct->unit_price }}</td>
                                <td class="text-center">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <a href="{{route('admin.order-quantity-decrement',[$orderProduct->id,$order->id])}}">
                                                <button class="btn btn-outline-secondary" type="button" id="decrementBtn">-</button>
                                            </a>
                                        </div>
                                        <input style="height: 35px;" type="text"  class="form-control text-center" id="counterInput" value="{{ $orderProduct->qty }}" readonly>
                                        <a href="{{route('admin.order-quantity-increment',[$orderProduct->id,$order->id])}}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="incrementBtn">+</button>
                                            </div>
                                        </a>
                                    </div>
                                </td>
                                @php
                                    $total = ($orderProduct->unit_price * $orderProduct->qty)
                                @endphp
                                <td class="text-center">{{ $setting->currency_icon }}{{ $total }}</td>
                                <td class="text-right delete-icon">
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $orderProduct->id}},{{$order->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                            @php
                                $totalVariant = 0;
                            @endphp
                        @endforeach
                      </table>
                    </div>
                    @if ($order->additional_info)
                    <div class="row additional_info">
                        <div class="col">
                            <hr>
                            <h5>{{__('admin.Additional Information')}}: </h5>
                            <p>{!! clean(nl2br($order->additional_info)) !!}</p>
                            <hr>
                        </div>
                    </div>
                    @endif
                
                    <div class="row mt-3">
                      <div class="col-lg-6 order-status">
                        <div class="section-title">{{__('admin.Order Status')}}</div>

                        <form action="{{ route('admin.update-order-status',$order->id) }}" method="POST">
                          @csrf
                          @method("PUT")
                          <div class="form-group">
                              <label for="">{{__('admin.Payment')}}</label>
                            <select name="payment_status" id="" class="form-control">
                                <option {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                <option {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Success')}}</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.Order')}}</label>
                            <select name="order_status" id="" class="form-control">
                              <option {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                              <option {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('admin.In Progress')}}</option>
                              <option {{ $order->order_status == 2 ? 'selected' : '' }}  value="2">{{__('admin.Delivered')}}</option>
                              <option {{ $order->order_status == 3 ? 'selected' : '' }} value="3">{{__('admin.Completed')}}</option>
                              <option {{ $order->order_status == 4 ? 'selected' : '' }} value="4">{{__('admin.Declined')}}</option>
                            </select>
                          </div>

                          <button class="btn btn-primary" type="submit">{{__('admin.Update Status')}}</button>
                        </form>
                      </div>

                      <div class="col-lg-6 text-right">
                        @php
                            $sub_total = $order->total_amount;
                            $sub_total = $sub_total - $order->shipping_cost;
                            $sub_total = $sub_total + $order->coupon_coast;

                        @endphp
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">{{__('admin.Subtotal')}} : {{ $setting->currency_icon }}{{ round($sub_total, 2) }}</div>
                        </div>
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">{{__('admin.Discount')}}(-) : {{ $setting->currency_icon }}{{ round($order->coupon_coast, 2) }}</div>
                        </div>
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">{{__('admin.Shipping')}} : {{ $setting->currency_icon }}{{ round($order->shipping_cost, 2) }}</div>
                        </div>

                        <hr class="mt-2 mb-2">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-value invoice-detail-value-lg">{{__('admin.Total')}} : {{ $setting->currency_icon }}{{ round($order->total_amount, 2) }}</div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>

              <div class="text-md-right print-area">
                <hr>
                <button class="btn btn-success btn-icon icon-left print_btn"><i class="fas fa-print"></i> {{__('admin.Print')}}</button>
                <button class="btn btn-danger btn-icon icon-left" data-toggle="modal" data-target="#deleteModal" onclick="deleteData({{ $order->id }})"><i class="fas fa-times"></i> {{__('admin.Delete')}}</button>
              </div>
            </div>
          </div>

        </section>
      </div>
       <!-- Add Product Modal -->
       <div class="modal fade" id="exampleModalLong-2"  role="dialog"
       aria-labelledby="exampleModalLongTitle" aria-hidden="true">
       <div class="modal-dialog modal-dialog-two modal-lg" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLongTitle-1"> {{__('admin.Add New Product') }}</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <div class="modal-from">
                       <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                       @csrf
                       <div class="row">
                           <div class="form-group col-12">
                               <label>{{__('admin.Thumbnail Image Preview')}}</label>
                               <div>
                                   <img id="preview-img" class="admin-img" src="{{ asset('uploads/website-images/preview.png') }}" alt="">
                               </div>

                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Thumnail Image')}} <span class="text-danger">*</span></label>
                               <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)" required>
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Short Name')}} <span class="text-danger">*</span></label>
                               <input type="text" id="short_name" class="form-control"  name="short_name" value="{{ old('short_name') }}" required>
                           </div>

                           <div class="form-group col-12">
                               <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                               <input type="text" id="name" class="form-control"  name="name" value="{{ old('name') }}" required>
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                               <input type="text" id="slug" class="form-control"  name="slug" value="{{ old('slug') }}">
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                               <select name="category" class="form-control select2" id="category" required>
                                   <option value="">{{__('admin.Select Category')}}</option>
                                   @foreach ($categories as $category)
                                       <option value="{{ $category->id }}">{{ $category->name }}</option>
                                   @endforeach
                               </select>
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Sub Category')}}</label>
                               <select name="sub_category" class="form-control select2" id="sub_category">
                                   <option value="">{{__('admin.Select Sub Category')}}</option>
                               </select>
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Child Category')}}</label>
                               <select name="child_category" class="form-control select2" id="child_category">
                                   <option value="">{{__('admin.Select Child Category')}}</option>
                               </select>
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Brand')}} </label>
                               <select name="brand" class="form-control select2" id="brand">
                                   <option value="">{{__('admin.Select Brand')}}</option>
                                   @foreach ($brands as $brand)
                                       <option {{ old('brand') == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                   @endforeach
                               </select>
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.SKU')}} </label>
                               <input type="text" class="form-control" name="sku">
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('Price')}} <span class="text-danger">*</span></label>
                               <input type="text" class="form-control" name="price" value="{{ old('price') }}" required>
                           </div>
                           <div class="form-group col-6">
                               <label>{{__('admin.Offer Price')}}</label>
                               <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price') }}">
                           </div>



                           <div class="form-group col-6">
                               <label>{{__('admin.Stock Quantity')}} <span class="text-danger">*</span></label>
                               <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}" required>
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Weight')}} <span class="text-danger">*</span></label>
                               <input type="text" class="form-control" name="weight" value="{{ old('weight') }}" required>
                           </div>

                           <div class="form-group col-6">
                               <label>{{__('admin.Short Description')}} <span class="text-danger">*</span></label>
                               <textarea name="short_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('short_description') }}</textarea>
                           </div>
                           
                           <div class="form-group col-6">
                               <label>{{__('admin.Long Description')}} <span class="text-danger">*</span></label>
                               <textarea name="long_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('long_description') }}</textarea>
                           </div>

                           <div class="form-group col-12">
                               <label>{{__('admin.Highlight')}}</label>
                               <div>
                                   <input type="checkbox"name="top_product" id="top_product"> <label for="top_product" class="mr-3" >{{__('admin.Top Product')}}</label>

                                   <input type="checkbox" name="new_arrival" id="new_arrival"> <label for="new_arrival" class="mr-3" >{{__('admin.New Arrival')}}</label>

                                   <input type="checkbox" name="best_product" id="best_product"> <label for="best_product" class="mr-3" >{{__('admin.Best Product')}}</label>

                                   <input type="checkbox" name="is_featured" id="is_featured"> <label for="is_featured" class="mr-3" >{{__('admin.Featured Product')}}</label>
                               </div>
                           </div>

                           <div class="form-group col-12">
                               <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                               <select name="status" class="form-control" required>
                                   <option value="1">{{__('admin.Active')}}</option>
                                   <option value="0">{{__('admin.Inactive')}}</option>
                               </select>
                           </div>




                           <div class="form-group col-12">
                               <label>{{__('admin.SEO Title')}}</label>
                               <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title') }}">
                           </div>

                           <div class="form-group col-12">
                               <label>{{__('admin.SEO Description')}}</label>
                               <textarea name="seo_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('seo_description') }}</textarea>
                           </div>
                       </div>
                       <div class="row">
                           <div class="col-12">
                                 <button class="btn btn-primary" type="submit" class="modal-from-btm-btn">{{__('admin.Save') }}</button>
                           </div>
                       </div>
                   </form>
                                           
                   </div>
               </div>
           </div>
       </div>
     </div>
     <!-- Add New Product Order Modal -->
     <div class="modal fade" id="exampleModalLong-3"  role="dialog"
     aria-labelledby="exampleModalLongTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-two modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLongTitle-1"> {{__('Add New Product in Order') }}</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="modal-from">
                     <form action="{{route('admin.add-new-product-in-order',$order->id)}}" method="POST" >
                     @csrf
                     <div class="row">
                         <div class="form-group col-12">
                             <label>{{__('admin.Product')}} <span class="text-danger">*</span></label>
                             <select name="product_id" class="form-control select2" id="product_id" required>
                                 <option value="">{{__('admin.Select Product')}}</option>
                                 @foreach($products as $product)
                                 <option value="{{$product->id}}">{{$product->name}}</option>
                                 @endforeach
                             </select>
                         </div> 
                         <div class="form-group col-12">
                            <label>{{__('admin.Quantity')}} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="quantity" required>
                        </div> 
                     </div>
                     <div class="row">
                         <div class="col-12">
                               <button class="btn btn-primary" type="submit" class="modal-from-btm-btn">{{__('admin.Add Product') }}</button>
                         </div>
                     </div>
                 </form>
                                         
                 </div>
             </div>
         </div>
     </div>
   </div>

    <script>
        function deleteData(id){
            $("#deleteForm").attr("action",'{{ url("admin/delete-order/") }}'+"/"+id)
        }

        (function($) {
            "use strict";
            $(document).ready(function() {

                $(".print_btn").on("click", function(){
                    $(".custom_click").click();
                    window.print()
                })

            });
        })(jQuery);

    </script>

<script>
  $(document).ready(function () {
      var buttonPlus = $(".qty-btn-plus");
      var buttonMinus = $(".qty-btn-minus");

      buttonPlus.click(function () {
          var $n = $(this).parent(".qty-container").find(".input-qty");
          $n.val(Number($n.val()) + 1);
      });

      buttonMinus.click(function () {
          var $n = $(this).parent(".qty-container").find(".input-qty");
          var amount = Number($n.val());
          if (amount > 0) {
              $n.val(amount - 1);
          }
      });

      // Ensure the quantity value is initially set
      var $initialQty = $(".input-qty");
      $initialQty.val(Number($initialQty.val()));
  });
</script>
<script>
  (function($) {
      "use strict";
      var specification = true;
      $(document).ready(function () {
          $("#name").on("focusout",function(e){
              $("#slug").val(convertToSlug($(this).val()));
          })

          $("#category").on("change",function(){
              var categoryId = $("#category").val();
              if(categoryId){
                  $.ajax({
                      type:"get",
                      url:"{{url('/admin/subcategory-by-category/')}}"+"/"+categoryId,
                      success:function(response){
                          $("#sub_category").html(response.subCategories);
                          var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                          $("#child_category").html(response);
                      },
                      error:function(err){
                          console.log(err);

                      }
                  })
              }else{
                  var response= "<option value=''>{{__('admin.Select Sub Category')}}</option>";
                  $("#sub_category").html(response);
                  var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                  $("#child_category").html(response);
              }


          })

          $("#sub_category").on("change",function(){
              var SubCategoryId = $("#sub_category").val();
              if(SubCategoryId){
                  $.ajax({
                      type:"get",
                      url:"{{url('/admin/childcategory-by-subcategory/')}}"+"/"+SubCategoryId,
                      success:function(response){
                          $("#child_category").html(response.childCategories);
                      },
                      error:function(err){
                          console.log(err);

                      }
                  })
              }else{
                  var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                  $("#child_category").html(response);
              }

          })

          $("#is_return").on('change',function(){
              var returnId = $("#is_return").val();
              if(returnId == 1){
                  $("#policy_box").removeClass('d-none');
              }else{
                  $("#policy_box").addClass('d-none');
              }

          })

          $("#addNewSpecificationRow").on('click',function(){
              var html = $("#hidden-specification-box").html();
              $("#specification-box").append(html);
          })

          $(document).on('click', '.deleteSpeceficationBtn', function () {
              $(this).closest('.delete-specification-row').remove();
          });


          $("#manageSpecificationBox").on("click",function(){
              if(specification){
                  specification = false;
                  $("#specification-box").addClass('d-none');
              }else{
                  specification = true;
                  $("#specification-box").removeClass('d-none');
              }


          })

      });
  })(jQuery);

  function convertToSlug(Text){
          return Text
              .toLowerCase()
              .replace(/[^\w ]+/g,'')
              .replace(/ +/g,'-');
  }

  function previewThumnailImage(event) {
      var reader = new FileReader();
      reader.onload = function(){
          var output = document.getElementById('preview-img');
          output.src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
  };

</script>
<script>
    function deleteData(id,order_id){
      console.log(order_id);
        $("#deleteForm").attr("action",'{{ url("admin/delete-order-product/") }}'+"/"+id+"/"+order_id)
    }
</script>


@endsection
