<div class="main">
    <div class="card-isi">
        <div class="header-card">
            <p>{{ $editMode ? 'Edit Data Karcis Masuk' : 'Tambah Data Karcis Masuk' }}</p>
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
                                <p>Nama Penyetok</p>
                                <select class="input-t" wire:model="penyetok" name="penyetok" id="users">
                                    <option value="" selected>Pilih Penyetok</option>
                                    @foreach($datau as $user)
                                        <option value="{{ $user->nama }}">{{ $user->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-ctn">
                                <p>Nama Penerima</p>
                                <select class="input-t" wire:model="penerima" name="penerima" id="users">
                                    <option value="" selected>Pilih Penerima</option>
                                    @foreach($datau as $user)
                                        <option value="{{ $user->nama }}">{{ $user->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-ctn">
                                <p>Tanggal Masuk</p>
                                <input class="input-t" placeholder="Tanggal" type="date" wire:model="tgl_masuk"
                                    name="tgl_masuk" id="" />
                            </div>
                            <div class="input-ctn">
                                <p>File Terkait</p>
                                @if($editMode && $oldFile)

                                    <input class="input-t" placeholder="File Terkait" type="file" wire:model="file_foto"
                                        name="file" accept="*/*" id="" />
                                @else

                                    <input class="input-t" placeholder="File Terkait" type="file" wire:model="file_foto"
                                        name="file" accept="*/*" id="" />
                                @endif
                            </div>
                        </div>
                        <div class="row-area">
                            <div class="input-ctn">
                                <p>Jenis Karcis</p>
                                <select class="input-t" wire:model="jenis_id" name="jenis_id" id="jenis">
                                    <option value="" selected>Pilih Jenis Karcis</option>
                                    @foreach($jenis as $jns)
                                        <option value="{{ $jns->id }}">{{ $jns->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-ctn">
                                <p>Jumlah</p>
                                <div class="jumlah-lembar">
                                    <input class="input-t" placeholder="Jumlah" type="text" wire:model="jml" name="jml"
                                        id="" />
                                    <select class="input-t" wire:model="satuan" name="satuan" id="satuan">
                                        <option value="" selected>Pilih Satuan</option>
                                        <option value="lembar">Lembar</option>
                                        <option value="buku">Buku</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-ctn">
                                <p>Nomor Karcis</p>
                                <input class="input-t" placeholder="Nomor Karcis" type="text" wire:model="nomor"
                                    name="nomor" id="" />
                            </div>
                        </div>
                    </div>

                    <div class="box-image">
                        <div class="image-upload">
                            <div class="cover">
                                @if ($penyetok || $penerima || $tgl_masuk)
                                    <p>Nama Penyetok : {{ $penyetok }}</p>
                                    <p>Nama Penerima : {{ $penerima }}</p>
                                    <p>Tanggal : {{ $tgl_masuk }}</p>
                                    <p>Jenis : {{ $jemnis }}</p>
                                    <p>Jumlah : {{ number_format($jml, 0, ',', '.') }} Lembar</p>
                                    <p>Total : {{'Rp ' . number_format($totall, 0, ',', '.')}}</p>
                                    <p>Nomor : {{ $nomor }}</p>
                                @endif
                                @if($editMode && $oldFile)
                                    <a href="{{ $oldFile }}" target="_blank">
                                        <p>File Terkait : Lihat <i class="fa-solid fa-up-right-from-square"></i></p>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="submit-area">
                    @if($editMode)
                        <button type="button" wire:click="cancel" class="button-xr ahref-cari">Batal</button>
                        <button type="button" wire:click="cetak_satuan" class="button-xr ahref-cari">Download</button>
                        @if($downloadUrl)
                            <a href="{{ $downloadUrl }}" target="_blank" class="button-xr ahref-cari">Klik disini untuk
                                mengunduh</a>
                        @endif
                    @endif
                    <button class="button-xr" type="submit">{{ $editMode ? 'Update' : 'Simpan' }}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-isi">
        <div class="header-card">
            <p>Tabel Data Karcis Masuk</p>
            <hr />
        </div>
        <div class="content-card">
            <div class="cari-data">
                <p>Total : {{$ctKcs}}</p>
            </div>
            <div class="cari-data">
                <form wire:submit.prevent="searchData">
                    <input wire:model.debounce.300ms="search" class="input-t" id="cariData" placeholder="Cari.."
                        type="text">
                    <select class="input-t" wire:model="role">
                        <option value="">Jenis</option>
                        @foreach($jenis as $jns)
                            <option value="{{ $jns->id }}">{{ $jns->nama }}</option>
                        @endforeach
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
                                <th>Penyetok</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Penerima</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karcis as $kcs)
                                <tr>
                                    <td>{{ $kcs->penyetok }}</td>
                                    <td>{{ $kcs->tgl_masuk }}</td>
                                    <td>{{ $kcs->jenis->nama}}</td>
                                    <td>{{ 'Rp ' . number_format($kcs->jenis->harga, 0, ',', '.') }}</td>
                                    <td>{{ $kcs->jml }} lembar</td>
                                    <td>{{ 'Rp ' . number_format($kcs->total, 0, ',', '.') }}</td>
                                    <td>{{ $kcs->users_id }}</td>
                                    <td>
                                        <div class="aksi">
                                            <a href="" wire:click.prevent="edit({{ $kcs->id }})"
                                                class="button-xr ahref-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                            @if (auth()->user()->isAdministrator())

                                                <a href="#modal" class="button-xr ahref-hapus delete-row"
                                                    onclick="confirmDelete({{ $kcs->id }})"><i
                                                        class="fa-solid fa-trash-can"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $karcis->links() }}
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
            @if (auth()->user()->isAdmin())
                <div class="cari-data">
                    <p>*Hubungi Administrator untuk proses hapus data</p>
                </div>
            @endif
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

    document.addEventListener('DOMContentLoaded', function () {

        window.searchData = function () {
            @this.call('searchData');
        };
    });
</script>