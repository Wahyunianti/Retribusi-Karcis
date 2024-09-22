<div class="main">
    <div class="card-isi">
        <div class="header-card">
            <p>{{ $editMode ? 'Edit Data Pengguna' : 'Tambah Data Pengguna' }}</p>
            <hr />
        </div>
        <div class="content-card">
            <form action="" wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}" method="post"
                enctype="multipart/form-data">
                <div class="warning-isi">
                    @include('Layout.warning')
                </div>

                <div class="input-area">
                    <div class="row-input">
                        <div class="row-area">
                            <div class="input-ctn">
                                <p>Nama</p>
                                <input class="input-t" placeholder="Nama" type="text" wire:model="nama" name="nama"
                                    id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Username</p>
                                <input class="input-t" placeholder="Username" type="text" wire:model="username"
                                    name="username" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Password</p>
                                <input class="input-t" placeholder="Password" type="text" wire:model="password"
                                    name="password" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Bagian</p>
                                <input class="input-t" placeholder="Bagian" type="text" wire:model="bagian"
                                    name="bagian" id="" />
                            </div>
                        </div>
                        <div class="row-area">
                            <div class="input-ctn">
                                <p>NIP</p>
                                <input class="input-t" placeholder="NIP" type="text" wire:model="nip" name="nip"
                                    id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Nomor Telepon</p>
                                <input class="input-t" placeholder="Nomor Telepon" type="text" wire:model="no_telp"
                                    name="no_telp" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Status</p>
                                <select class="input-t" wire:model="status" name="status" id="role">
                                    <option value="" selected>Pilih Status</option>
                                    <option value="PNS">PNS</option>
                                    <option value="Honorer">Honorer</option>
                                </select>
                            </div>
                            <div class="input-ctn">
                                <p>Level</p>
                                <select class="input-t" wire:model="role_id" name="role_id" id="role">
                                    <option value="" selected>Pilih Level</option>
                                    <option value="1">Administrator</option>
                                    <option value="2">Admin</option>
                                    <option value="3">Kepala</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box-image">
                        <div class="image-upload">
                            <input onchange="previewFile()" type="file" id="file-input" wire:model="foto" name="foto"
                                accept="image/jpeg, image/png">
                            <div class="cover">
                                @if ($foto)
                                    <img src="{{ $foto->temporaryUrl() }}" alt="Preview Image" id="previewImage">
                                @elseif($editMode && $oldFoto)
                                    <img src="{{ Storage::url($oldFoto) }}" alt="Preview Image" id="previewImage">
                                @else
                                    <img src="{{ asset('image/photo.png') }}" alt="Preview Image" id="previewImage">
                                @endif
                                <label for="file-input" class="button-xr">Upload Foto</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="submit-area">
                    @if($editMode)
                        <button type="button" wire:click="cancel" class="button-xr ahref-cari">Batal</button>
                    @endif
                    <button class="button-xr" type="submit">{{ $editMode ? 'Update' : 'Simpan' }}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-isi">
        <div class="header-card">
            <p>Tabel Data Pengguna</p>
            <hr />
        </div>
        <div class="content-card">
            <div class="cari-data">
                <p>Total : {{$ctUser}}</p>
            </div>
            <div class="cari-data">
                <form wire:submit.prevent="searchData">
                    <input wire:model.debounce.300ms="search" class="input-t" id="cariData" placeholder="Cari.."
                        type="text">
                    <select class="input-t" wire:model="role">
                        <option value="">Semua Level</option>
                        <option value="1">Administrator</option>
                        <option value="2">Admin</option>
                        <option value="3">Kepala</option>

                    </select>
                    <button type="submit" class="button-xr ahref-cari">CARI <i
                            class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div class="content-table">
                <div class="table-container">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Nomor</th>
                                <th>Bagian</th>
                                <th>Level</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->nip }}</td>
                                    <td>{{ $user->no_telp }}</td>
                                    <td>{{ $user->bagian }}</td>
                                    <td>{{ $user->role_id == 1 ? 'Administrator' : 'Admin' }}</td>
                                    <td>
                                        <div class="aksi">
                                            <a href="" wire:click.prevent="edit({{ $user->id }})"
                                                class="button-xr ahref-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="#modal" class="button-xr ahref-hapus delete-row"
                                                onclick="confirmDelete({{ $user->id }})"><i
                                                    class="fa-solid fa-trash-can"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $users->links() }}
                </div>

                <div id="modal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <div class="confirm">
                            <h2 id="modalTitle">Hapus Data?</h2>
                            <a href="#" class="close-modal" onclick="closeModal()">&times;</a>
                        </div>
                        <p>Apakah kamu ingin menghapus data ini?</p>
                        <form id="delete-form">
                            @csrf
                            <input type="hidden" id="deleteUserId" name="deleteUserId">
                            <button class="button-xr ahref-hapus" onclick="deleteUser()" type="button">Hapus</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="footer">
        <footer>
            <p>Copyright @2024 . Robby S</p>
        </footer>
    </div>
</div>

<script>
    function confirmDelete(userId) {
        document.getElementById('deleteUserId').value = userId;
        document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function deleteUser() {
        var userId = document.getElementById('deleteUserId').value;
        @this.call('deleteUser', userId);
        closeModal();
    }

    function previewFile() {
        var preview = document.getElementById('previewImage');
        var file = document.getElementById('file-input').files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('image/photo.png') }}";
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        window.searchData = function () {
            @this.call('searchData');
        };
    });
</script>