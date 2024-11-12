@extends('admin.layouts.master')
@section('title') Direct Seller Contract @endsection

@section('content')
    @include('admin.breadcrumbs', [
          'crumbs' => [
              'Direct Seller Contract'
          ]
     ])
    <div class="row">
        <div class="col-md-5">
           <div class="card">
               <div class="card-body">
                   <form method="post" action="" class="filePondForm">
                       @csrf
                       <div class="form-group">
                           <label class="required">Upload PDF File</label>
                           <input type="file" name="direct_seller_contract" class="filePondInput"
                                  value="{{  optional($direct_seller_pdf)->getFirstMediaUrl(\App\Models\DirectSellerContract::MC_DIRECT_SELLER_CONTRACT) ?: '' }}"
                                  required accept="application/pdf">
                           @foreach($errors->get('direct_seller_contract') as $error)
                               <span class="text-danger">{{$error}}</span>
                           @endforeach
                       </div>

                       <div class="form-group">
                           <label for="inputEmail4" class="col-form-label">Status<span class="text-danger">*</span></label><br>
                           <div class="radio radio-primary form-check-inline">
                               <input type="radio" id="inlineRadio1" value="1"
                                      name="status" {{ (old('status',optional($direct_seller_pdf)->status) == 1 ? 'checked' : '') }} required>
                               <label for="inlineRadio1"> Active </label>
                           </div>
                           <div class="radio radio-primary form-check-inline">
                               <input type="radio" id="inlineRadio2" value="2"
                                      name="status" {{ (old('status',optional($direct_seller_pdf)->status) == 2 ? 'checked' : '') }} required>
                               <label for="inlineRadio2"> In-Active </label>
                           </div>
                       </div>
                       @foreach($errors->get('status') as $error)
                           <span class="text-danger">{{$error}}</span>
                       @endforeach
                       <div class="form-group  mt-4">
                           <button type="submit" class="btn btn-primary waves-effect waves-light">
                               Save Changes
                           </button>
                       </div>
                   </form>
               </div>
           </div>
        </div>
    </div>
@endsection

@push('page-javascript')
    <script src="{{ asset('js/vapor.min.js') }}"></script>
    <script src="{{ asset('js/filepond.min.js') }}"></script>
@endpush
