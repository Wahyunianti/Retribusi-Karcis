@if($errors->any())
    <div id="warning-info" class="warning-area">
        <div class="data-warning">
        <a href="#" class="close-warning"  onclick="closeWarning()">&times;</a>

            @foreach($errors->all() as $pesan)

                <p>
                    {{ $pesan }} <i class="fa-solid fa-exclamation"></i>
                </p>
            @endforeach
        </div>
    </div>
@endif

@if(session('message'))
    <div id="warning-info" class="warning-area">
        <div class="data-warning">
        <a href="#" class="close-warning" onclick="closeWarning()">&times;</a>

            <p>
                {{ session('message') }} <i style="color: #4867a4;" class="fa-solid fa-check"></i>
            </p>
        </div>
    </div>
@endif

<script>
    setTimeout(function () {
        var errorlo = document.getElementById('warning-info');
        if (errorlo) {
            errorlo.style.display = 'none';
        }
    }, 5000);

    function closeWarning() {
        var warningInfo = document.getElementById('warning-info');
        warningInfo.style.display = 'none';
    }
</script>