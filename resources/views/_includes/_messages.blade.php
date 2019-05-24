@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        <strong>Success::</strong> {{Session::get('success')}}
    </div>

@endif
@if(count($errors) > 0)
    <?php
        $string = '<ul>';
      ?>
        @foreach ($errors->all() as $erro)
          <?php $string.='<li>'.$erro.'</li>'; ?>
        @endforeach
        <?php $string.='<ul>'; ?>
    <div class="alert alert-danger" role="alert">

        <strong>Errors:</strong>
        <ul>
            {!! implode('',$errors->all('<li>:message</li>')) !!}
        </ul>

    </div>
    <flash message="{!! $string !!}}" level="danger"></flash>
@endif



<flash message="{{ session('flash') }}"></flash>
