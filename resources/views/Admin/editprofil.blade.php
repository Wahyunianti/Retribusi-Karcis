@extends('Layout.sidebar')

@section('content')
<div class="main">
    <div class="card-isi">
        <div class="header-card" style="display:flex; flex-direction:row; justify-content: center; align-items: end;">
            @if(auth()->user()->foto)
                <img id="previewImage" src="{{ asset('storage/' . auth()->user()->foto) }}"
                    style="object-fit: cover; object-position: center top; width: 100px; height: 100px; margin-right: -5px; border-radius: 50%; border: 2px solid #529471; ">
            @else
                <img id="previewImage" src="{{ asset('image/user.png')}}"
                    style="object-fit: cover; object-position: center top; width: 100px; height: 100px; margin-right: -5px; border-radius: 50%; border: 2px solid #fff; ">

            @endif              
            <a href=""><label for="file-input" ><i style="color: #529471; cursor: pointer" class="fa-solid fa-pencil"></i></label></a>
        </div>

        <div class="content-card">
            <form action="{{route('editProfil')}}" method="post" enctype="multipart/form-data">
            @csrf
                <div class="warning-isi">
                    @include('Layout.warning')
                </div>

                <div class="input-area">
                    <div class="row-input">
                        <div class="row-area">
                            <div class="input-ctn">
                                <p>Nama</p>
                                <input class="input-t" placeholder="Nama" type="text" value="{{ auth()->user()->nama }}"
                                    name="nama" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Username</p>
                                <input class="input-t" placeholder="Username" type="text"
                                    value="{{ auth()->user()->username }}" name="username" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Password</p>
                                <input class="input-t" placeholder="Ganti Password" type="text" name="password" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Bagian</p>
                                <input class="input-t" placeholder="Bagian" type="text"
                                    value="{{ auth()->user()->bagian }}" name="bagian" id="" />
                            </div>
                        </div>
                        <div class="row-area">
                            <div class="input-ctn">
                                <p>NIP</p>
                                <input class="input-t" placeholder="NIP" type="text" value="{{ auth()->user()->nip }}"
                                    name="nip" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Nomor Telepon</p>
                                <input class="input-t" placeholder="Nomor Telepon" type="text"
                                    value="{{ auth()->user()->no_telp }}" name="no_telp" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Status</p>
                                <select class="input-t" name="status"  id="role">
                                    <option value="" {{ auth()->user()->status == '' ? 'selected' : '' }}>Pilih Status</option>
                                    <option value="PNS" {{ auth()->user()->status == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="Honorer" {{ auth()->user()->status == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                </select>
                            </div>
                            <div class="input-ctn">
                                <p>Level</p>
                                <select class="input-t" name="role_id" id="role" disabled>
                                    <option value="" {{ auth()->user()->role_id == '' ? 'selected' : '' }}>Pilih Level
                                    </option>
                                    <option value="1" {{ auth()->user()->role_id == 1 ? 'selected' : '' }}>Administrator
                                    </option>
                                    <option value="2" {{ auth()->user()->role_id == 2 ? 'selected' : '' }}>Admin</option>
                                    <option value="3" {{ auth()->user()->role_id == 3 ? 'selected' : '' }}>Kepala</option>

                                </select>
                            </div>
                            <input onchange="previewFile()" type="file" id="file-input" name="foto"
                            accept="image/jpeg, image/png">
                        </div>
                    </div>
                </div>
                <div class="submit-area">
                    <button class="button-xr" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        <footer>
            <p>Copyright @2024 . Robby S</p>
        </footer>
    </div>
</div>
@endsection

<script>
function previewFile() {
    var preview = document.getElementById('previewImage');
    var file = document.querySelector('input[type=file]').files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "{{ asset('image/photo.png') }}";
    }
};
</script>