<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
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
        <div class="col-sm-4">
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
        <div class="col-sm-4">
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
        <div class="col-sm-4">
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

        <div class="col-sm-4">
            <div class="form-group">
                <label for="">Modalidad</label>        
                <select name="tax_modalidad" id="tax_modalidad" class="form-control">
                    <option value="MOD_ELECTRONICA_ENLINEA" @if($business->tax_modalidad=='MOD_ELECTRONICA_ENLINEA') selected @endif>Electronica</option>
                    <option value="MOD_COMPUTARIZADA_ENLINEA"  @if($business->tax_modalidad=='MOD_COMPUTARIZADA_ENLINEA') selected @endif>Computarizada</option>
                </select>         
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="">Abiemte</label>              
                <select name="tax_ambiente" id="tax_ambiente" class="form-control">
                    <option value="AMBIENTE_PRUEBAS" @if($business->tax_ambiente=='AMBIENTE_PRUEBAS') selected @endif>Pruebas</option>
                    <option value="AMBIENTE_PRODUCCION" @if($business->tax_ambiente=='AMBIENTE_PRODUCCION') selected @endif>Produccion</option>
                </select>               
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="">Municipio</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" class="form-control" name="tax_municipio">
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="">Telefono</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" class="form-control" name="tax_telefono">
                </div>
            </div>
        </div>

        <div class="col-sm-12">
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