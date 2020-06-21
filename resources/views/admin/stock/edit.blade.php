@extends('admin.layouts.app')
@section('title', 'Магазин - Акции')
@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css">
  <style>
    .dz-image > img {
      width: 100%;
      height: auto;
    }
    .dropzone {
      background: white;
      border-radius: 5px;
      border: 2px dashed rgb(0, 135, 247);
      border-image: none;
      max-width: 100%;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
@endsection
@section('content')
  <div class="container-fluid pt-5 px-4">
    <div class="row">
      <div class="col-12">
        <h2>Акции</h2>
      </div>
    </div>
    <div class="row mt-0 pt-0">
      <div class="card border-0 w-100 rounded-0" style="z-index: 90;box-shadow: 0 18px 19px rgba(0, 0, 0, 0.25)">
        <div class="card-header">
          <div class="row">
            <div class="col-auto">
              <a href="{{ url()->previous() }}" class="h4 d-flex align-content-center"><i class="fal fa-long-arrow-left mr-2"></i> Назад</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.store.stock.update', $st->id) }}" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="image" value="{{ old('image') ? old('image') : $st->image }}">
            <div class="row justify-content-end">
              <div class="col-auto">
                <button class="btn btn-dark rounded-0 border-0 px-3 py-2" type="submit">Обновить</button>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <label for="title">Заголовок*</label>
                    <input type="text" name="title" id="title" class="w-100 px-2 form-control rounded-0 {{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') ? old('title') : $st->title }}" required>
                    <span id="name-error" class="error invalid-feedback">{{ $errors->first('title') }}</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <label for="description">Описание</label>
                    <input name="description" id="description" class="w-100 px-2 form-control rounded-0 {{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description') ? old('description') : $st->description }}">
                    <span id="descriptio-error" class="error invalid-feedback">{{ $errors->first('descriptio') }}</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <label for="description">Ссылка</label>
                    <input name="link" id="link" class="w-100 px-2 form-control rounded-0 {{ $errors->has('link') ? ' is-invalid' : '' }}" value="{{ old('link') ? old('link') : $st->link }}">
                    <span id="link-error" class="error invalid-feedback">{{ $errors->first('link') }}</span>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="row mt-3">
            <div class="col-md-8">
              <form id="upload-widget" method="post" action="{{route('admin.store.stock.photoCreate')}}" class="dropzone"></form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>

  <script>

    Dropzone.autoDiscover = false;
    var fileList = {};
    const uploader = new Dropzone('#upload-widget', {
      maxFiles: 1,
      init: function() {

        // Hack: Add the dropzone class to the element
        $(this.element).addClass("dropzone");
        this.on("addedfile", function(event) {
          while (this.files.length > this.options.maxFiles) {
            this.removeFile(this.files[0]);
          }
        });

        this.on("sending", function(file, xhr, formData){
          formData.append("id", "{{ $st->id }}");
        });

        this.on("success", function (file, serverFileName) {
          fileList = {"serverFileName": file.upload.filename, "fileName": file.name};
          serverFileName = file.upload.filename;
          $('input[name="image"]').val(serverFileName)
        });
        this.on("removedfile", function(file) {
          var rmvFile = fileList.serverFileName;
          $('input[name="image"]').val(null)
          console.log(123)
          if (rmvFile){
            console.log(rmvFile)
            axios.post("{{route('admin.store.stock.photoDelete')}}", {
              name: rmvFile,
            })
              .then(response => {
                console.log(response)
              })
          }
        });
      },
      paramName: 'file',
      dictDefaultMessage: 'Drag an image here to upload, or click to select one',
      headers: {
        'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
      },
      acceptedFiles: 'image/*',
      url: "{{route('admin.store.stock.photoCreate')}}",
      renameFile: function (file) {
        let newName = new Date().getTime() + '_' + file.name;
        return newName;
      },
      addRemoveLinks: true,
    });

    @if(old('image'))
      fileList = {"serverFileName": '{{ old('image') }}', "fileName": '{{ old('image') }}'};
      let mockFile = { name: '{{ old('image') }}', size: 0 };
      uploader.emit("addedfile", mockFile);
      uploader.emit("thumbnail", mockFile, '{{ asset('storage/stocks/') . '/' . old('image') }}');
      uploader.emit("complete", mockFile);
      uploader.files.push(mockFile)
    @elseif($st->image)
      fileList = {"serverFileName": "{{ $st->image }}", "fileName": "{{ $st->image }}"};
      let mockFile = { name: '{{ $st->image }}', size: 0 };
      uploader.emit("addedfile", mockFile);
      uploader.emit("thumbnail", mockFile, '{{ asset('storage/stocks/') . '/' . $st->image }}');
      uploader.emit("complete", mockFile);
      uploader.files.push(mockFile)
    @endif
  </script>
@endsection