<div class="main">
    <div class="card-isi">
        <div class="header-card">
            <p>{{ $editMode ? 'Edit Data Kolektor' : 'Tambah Data Kolektor' }}</p>
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
                                <p>Area</p>
                                <select id="areaSelect" class="input-t" wire:model="area_id" name="area_id" id="area">
                                    <option value="" selected>Pilih Area Karcis</option>
                                    @foreach($area as $ara)
                                        <option value="{{ $ara->nama }}">{{ $ara->nama }}</option>
                                    @endforeach
                                </select>

                                <div id="selectedAreas">
                                    @foreach ($selectedAreas as $area)
                                        <div class="area-tag">
                                            <span>{{ $area }}</span>
                                            <button type="button" class="remove-btn"
                                                wire:click="removeArea('{{ $area }}')">×</button>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <div class="input-ctn">
                                <p>Masa</p>
                                <select class="input-t" wire:model="masa" name="masa" id="masa">
                                    <option value="" selected>Pilih Masa Berlaku</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Non Aktif">Non Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row-area">
                            <div class="input-ctn">
                                <p>NIP</p>
                                <input class="input-t" placeholder="NIP" type="text" wire:model="nip" name="nip"
                                    id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Keterangan</p>
                                <input class="input-t" placeholder="Keterangan" type="text" wire:model="keterangan"
                                    name="keterangan" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>Status</p>
                                <select class="input-t" wire:model="status" name="status" id="role">
                                    <option value="" selected>Pilih Status</option>
                                    <option value="PNS">PNS</option>
                                    <option value="Honorer">Honorer</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box-image">
                        <div class="image-upload">
                            <input type="file" id="file-input" wire:model="file_foto" name="file" accept="*/*">
                            <div class="cover">

                                @if ($file_foto)
                                    <img src="{{ asset('image/file.png') }}" alt="Preview Image" id="previewImage">
                                    <p>{{ $file_foto->getClientOriginalName() }}</p>
                                @elseif($editMode && $oldFile)
                                    <img src="{{ asset('image/preview.png') }}" alt="Preview Image" id="previewImage">

                                    <a href="{{ $oldFile }}" target="_blank">
                                        <p>Lihat File Terkait <i class="fa-solid fa-up-right-from-square"></i></p>
                                    </a>
                                @else
                                    <img src="{{ asset('image/file.png') }}" alt="Preview Image" id="previewImage">
                                    <p>Lampirkan file terkait (Opsional)</p>
                                @endif
                                <label for="file-input" class="button-xr">Upload File</label>
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
            <p>Tabel Data Kolektor</p>
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
                                <th>Masa Berlaku</th>
                                <th>Status</th>
                                <th>Area</th>
                                <th>Surat Tugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->nip }}</td>
                                    <td>
                                        <div class="bagian-warna {{ $user->masa == 'Aktif' ? 'hijau' : 'merah' }}">
                                            <p>{{ $user->masa}}</p>
                                        </div>
                                    </td>
                                    <td>{{ $user->status }}</td>
                                    <td>{{ str_replace('|', ', ', $user->area) }}</td>
                                    <td>
                                        @if ($user->file)
                                            <a href="{{ asset('storage/' . $user->file) }}" target="_blank">
                                                Lihat File Surat Perintah <i style= "color: var(--bg-dark1);" class="fa-solid fa-up-right-from-square"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
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
                            <button class="button-xr ahref-hapus" onclick="deleteUser()"
                                type="button">Hapus</button>
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

    document.addEventListener('DOMContentLoaded', function () {
        const areaSelect = document.getElementById('areaSelect');
        const selectedAreasDiv = document.getElementById('selectedAreas');

        areaSelect.addEventListener('change', function () {
            const selectedValue = areaSelect.value;
            if (selectedValue) {
                @this.call('addArea', selectedValue);
                areaSelect.value = '';
            }
        });

        function addAreaTag(area) {
            const areaTag = document.createElement('div');
            areaTag.classList.add('area-tag');

            const areaName = document.createElement('span');
            areaName.textContent = area;

            const removeButton = document.createElement('button');
            removeButton.classList.add('remove-btn');
            removeButton.textContent = '×';
            removeButton.addEventListener('click', function () {
                selectedAreasDiv.removeChild(areaTag);
                @this.call('removeArea', area);
            });

            areaTag.appendChild(areaName);
            areaTag.appendChild(removeButton);
            selectedAreasDiv.appendChild(areaTag);
        }
    });
</script>