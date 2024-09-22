<div class="main">
    <div class="card-isi">
        <div class="header-card">
            <p>{{ $editMode ? 'Edit Data Area Karcis' : 'Tambah Data Area Karcis' }}</p>
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
                                <p>Nama Area</p>
                                <input class="input-t" placeholder="Nama Area" type="text" wire:model="nama" name="nama"
                                    id="" />
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
            <p>Tabel Data Area Karcis</p>
            <hr />
        </div>
        <div class="content-card">
            <div class="cari-data">
                <p>Total : {{$ctArea}}</p>
            </div>
            <div class="content-table">
                <div class="table-container">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karcis as $kcs)
                                <tr>
                                    <td>{{ $kcs->nama }}</td>
                                    <td>
                                        <div class="aksi">
                                            <a href="" wire:click.prevent="edit({{ $kcs->id }})"
                                                class="button-xr ahref-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="#modal" class="button-xr ahref-hapus delete-row"
                                                onclick="confirmDelete({{ $kcs->id }})"><i
                                                    class="fa-solid fa-trash-can"></i></a>
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