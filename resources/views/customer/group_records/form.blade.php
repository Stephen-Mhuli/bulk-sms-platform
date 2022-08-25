<div class="row mt-3">
    <div class="col-sm-4 col-4"><label for="">Lead List</label></div>
    <div class="col-sm-8 col-8">

        <select name="group_ids[]" class="form-control" multiple="multiple" id="groups">
            @foreach(auth('customer')->user()->numbers()->where('expire_date','>', now())->get() as $key=>$number)
                <option value="{{$number->number}}">{{$number->number}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-2 mt-3"><label for="">First Name</label></div>
    <div class="col-sm-2 mt-3">
        <select name="first_name_type" class="form-control" >
            <option value="=">=</option>
            <option value="!=">!=</option>
        </select>
    </div>
    <div class="col-sm-8 mt-3">
        <textarea name="first_name" class="form-control" id="" cols="3" rows="3"></textarea>
    </div>


    <div class="col-sm-2 mt-3"><label for="">Last Name</label></div>
    <div class="col-sm-2 mt-3">
        <select name="last_name_type" class="form-control" >
            <option value="=">=</option>
            <option value="!=">!=</option>
        </select>
    </div>
    <div class="col-sm-8 mt-3">
        <textarea name="last_name" class="form-control" id="" cols="3" rows="3"></textarea>
    </div>

    <div class="col-sm-2 mt-3"><label for="">Phone</label></div>
    <div class="col-sm-2 mt-3">
        <select name="phone_number_type" class="form-control" >
            <option value="=">=</option>
            <option value="!=">!=</option>
        </select>
    </div>
    <div class="col-sm-8 mt-3">
        <textarea name="phone_number" class="form-control" id="" cols="3" rows="3"></textarea>
    </div>

    <div class="col-sm-2 mt-3"><label for="">Address</label></div>
    <div class="col-sm-2 mt-3">
        <select name="" class="form-control" >
            <option value="=">=</option>
            <option value="!=">!=</option>
        </select>
    </div>
    <div class="col-sm-8 mt-3">
        <textarea name="" class="form-control" id="" cols="3" rows="3"></textarea>
    </div>

    <div class="col-sm-2 mt-3"><label for="">City</label></div>
    <div class="col-sm-2 mt-3">
        <select name="" class="form-control" >
            <option value="=">=</option>
            <option value="!=">!=</option>
        </select>
    </div>
    <div class="col-sm-8 mt-3">
        <textarea name="" class="form-control" id="" cols="3" rows="3"></textarea>
    </div>

    <div class="col-sm-2 mt-3"><label for="">State</label></div>
    <div class="col-sm-2 mt-3">
        <select name="" class="form-control" >
            <option value="=">=</option>
            <option value="!=">!=</option>
        </select>
    </div>
    <div class="col-sm-8 mt-3">
        <textarea name="" class="form-control" id="" cols="3" rows="3"></textarea>
    </div>

    <div class="col-sm-2 mt-3"><label for="">Zip</label></div>
    <div class="col-sm-2 mt-3">
        <select name="zip_code_type" class="form-control" >
            <option value="=">=</option>
            <option value="!=">!=</option>
        </select>
    </div>
    <div class="col-sm-8 mt-3">
        <textarea name="zip_code" class="form-control" id="" cols="3" rows="3"></textarea>
    </div>

    <div class="col-sm-2 mt-3"><label for="">Email</label></div>
    <div class="col-sm-2 mt-3">
        <select name="email_type" class="form-control" >
            <option value="">=</option>
        </select>
    </div>
    <div class="col-sm-8 mt-3">
        <textarea name="email" class="form-control" id="" cols="3" rows="3"></textarea>
    </div>

</div>
