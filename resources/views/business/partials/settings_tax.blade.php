<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('tax_label_1','Titular' . ':') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    {!! Form::text('tax_label_1', $business->tax_label_1, ['class' => 'form-control','placeholder' => "Titular"]); !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('tax_number_1', 'NIT'. ':') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    {!! Form::text('tax_number_1', $business->tax_number_1, ['class' => 'form-control']); !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('tax_label_2', 'Usuario' . ':') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    {!! Form::text('tax_label_2', $business->tax_label_2, ['class' => 'form-control','placeholder' => 'Usuario']); !!}
                </div>
            </div>
        </div>
        {{-- <div class="clearfix"></div> --}}
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('tax_number_2', 'Clave' . ':') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    {!! Form::text('tax_number_2', $business->tax_number_2, ['class' => 'form-control', 'placeholder' => 'Clave']); !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                <br>
                  <label>
                    {!! Form::checkbox('enable_inline_tax', 1, $business->enable_inline_tax , 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.enable_inline_tax' ) }}
                  </label>
                </div>
            </div>
        </div>
    </div>
</div>