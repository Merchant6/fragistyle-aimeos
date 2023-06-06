@extends('shop::base')

@section('aimeos_header')
    <?= $aiheader['locale/select'] ?? '' ?>
    <?= $aiheader['basket/mini'] ?? '' ?>
    <?= $aiheader['catalog/search'] ?? '' ?>
    <?= $aiheader['catalog/tree'] ?? '' ?>
    <?= $aiheader['catalog/home'] ?? '' ?>
    <?= $aiheader['cms/page'] ?? '' ?>
@stop

@section('aimeos_head_basket')
    <?= $aibody['basket/mini'] ?? '' ?>
@stop

@section('aimeos_head_nav')
    <?= $aibody['catalog/tree'] ?? '' ?>
@stop


@section('aimeos_head_search')
    <?= $aibody['catalog/search'] ?? '' ?>
@stop

@section('aimeos_body')

<style>
    body > .content
    {
        margin-top: 0rem;
    }
</style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tui-image-editor@3.2.2/dist/tui-image-editor.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href=" https://cdn.jsdelivr.net/npm/tui-color-picker@2.2.8/dist/tui-color-picker.min.css " rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tui-code-snippet/2.2.0/tui-code-snippet.js" integrity="sha512-mAvab4Oz45PrynpT7P2J0XwsPy6k8gh0kf88n6GNaMpNGg0vwjTx58jUHvUmDgG/745tlS+rsGfaqtdKHGHSzw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src=" https://cdn.jsdelivr.net/npm/tui-color-picker@2.2.8/dist/tui-color-picker.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.js" integrity="sha512-UNbeFrHORGTzMn3HTt00fvdojBYHLPxJbLChmtoyDwB6P9hX5mah3kMKm0HHNx/EvSPJt14b+SlD8xhuZ4w9Lg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
    <script src="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="">Design Lab</h1>
            </div>
            <div class="col-lg-12">
                <h3>Design your products with love.</h3>
            </div>
            <div class="col-lg-12 mt-3">
                <form id="formTUI" action="/designlab" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="label" value="{{$imageInfo['label']}}">
                    <input type="hidden" name="refId" value="{{$mediaItem->id}}">
                    <input id="imgTUI" type="hidden" name="image" value="">
                    <div id="outerDiv">
                        <div id="tui-image-editor-container" class="col-lg-12 col-md-12" name="image">
                        </div>
                    </div>
                    <button type="submit" id="saveImage">Submit</button>
                    <p id="successMsg" class="text-success"></p>
                </form>
            </div>
        </div>
        
    </div>

    <script>

        console.log({!! json_encode($mediaItem->id) !!})

        // JavaScript code goes here
        var imageEditor = new tui.ImageEditor('#tui-image-editor-container', {
            includeUI: {
                // theme: whiteTheme,
                menuBarPosition: 'bottom',
                menu: ['shape','text', 'icon'],
                loadImage: false,
                loadImage: {
                        path:{!! json_encode($imageInfo['link']) !!}, // Set the image URL
                        name:{!! json_encode($imageInfo['label'])!!} // Set the image label
                },
                initMenu: 'text',
                uiSize: {
                    height: '70vh',
                } // Initial menu option to be selected
            },
            cssMaxWidth: 600,
            cssMaxHeight: 400,
            padding: 100,
            selectionStyle: {
                cornerSize: 50,
                rotatingPointOffset: 100,
            },
        });
        $('.tui-image-editor-load-btn').parent().css('display','none');
        $('.tui-image-editor-header-logo').css('display','none');
        
        try {
            
            $('#formTUI').submit(function(e){
                e.preventDefault();

                var image64 = imageEditor.toDataURL();
                // console.log(image64);

                var imgElement = $('#imgTUI').attr('value', image64);
                // console.log('Image Source '+ $('#imgTUI'));

                var formData = $(this).serialize();
                // console.log("Form Data: "+ formData);

                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                jQuery.ajax({
                    url: '/designlab',
                    type: 'POST',
                    data: formData,
                    success: function(response) 
                    {
                        // Handle the server response
                        console.log(response.success);
                        $('#saveImage').css('display','none');
                        $('#successMsg').text(response.success);
                    },
                    error: function(xhr, status, error, response) 
                    {
                        // Handle the error
                        console.error(response.error);
                        // $('#successMsg').text(response.error);
                    }
                });

                
            })
           
        } catch (error) {
            console(error)
        }
        

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
@stop