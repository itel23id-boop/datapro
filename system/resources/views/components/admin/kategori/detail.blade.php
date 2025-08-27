@extends('main-admin', ['activeMenu' => 'produk', 'activeSubMenu' => 'produk.kategori'])

@section('content')
<!-- start page title -->
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Detail Kategori</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="">Admin</a></li>
									<li class="breadcrumb-item active" aria-current="page">
										/kategori/{{$data->id}}/detail
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <a href="{{ route('kategori') }}" class="btn btn-danger w-50">Kembali</a>
                <div class="row mt-4">
                    <div class="col-lg-4">
                        <div class="card-box mb-30">
                            <h4 class="card-header mt-0 mb-1 ">Thumbnail & Deskripsi <i class="fa fa-question-circle cursor-pointer" data-toggle="modal" data-target="#petunjukModal"></i></h4>
                            <div class="card-body">
                                <img src="{{$data->thumbnail}}" alt="Card image cap" style="width:auto;height:100%;">
                                <hr>
                                <?php echo nl2br($data->deskripsi_game); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box mb-30">
                            <h4 class="card-header">Extra <i class="fa fa-question-circle cursor-pointer" data-toggle="modal" data-target="#petunjuk1Modal"></i></h4>
                            <div class="card-body">
                                <div class="py-2">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary shadow-md mr-2" onclick="modal_open('add', '{{ route('input.create', [$data->id]) }}')">Tambah input</a>
                                </div>
                                <ul class="list-group">
                                @forelse ($data->kategori_input as $item)
									<li class="list-group-item d-flex justify-content-between align-items-center">
										{{ $item->name }}
										@if($item->dropdown != null)
										@php
                                        $dropdownValues = json_decode($item->dropdown);
                                        @endphp
                                        <div class='col-lg-5'>
                                        <select class="form-control">
										@foreach ($dropdownValues as $dropdownValue)
										<option value="{{ $dropdownValue }}">{{ $dropdownValue }}</option>
										@endforeach
										</select>
										</div>
										@endif
										<div class="dropdown">
                							<a class="btn btn-info dropdown-toggle" href="javascript:void(0);" role="button" data-toggle="dropdown">
                								Aksi
                							</a>
                							<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                								<a class="dropdown-item" href="javascript:void(0);" onclick="modal_open('edit', '{{ route('input.create-edit', [$item->id]) }}')"><i class="dw dw-edit2"></i> Edit</a>
                								@if($item->server_id == "zone_value")
                								<a class="dropdown-item" href="javascript:void(0);" onclick="modal_open('add', '{{ route('input.create-pilihan', [$item->id]) }}')"><i class="dw dw-edit1"></i> Tambah Server ID</a>
                								@endif
                								<a class="dropdown-item" href="{{ route('input.destroy', [$item->id]) }}"><i class="dw dw-delete-3"></i> Delete</a>
                							</div>
                						</div>
									</li>
                                @empty
                                <span>Input Masih Kosong</span>
                                @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="card-box mb-30">
                            <h4 class="card-header mt-0 mb-1">Deskripsi Field User ID & Zone ID <i class="fa fa-question-circle cursor-pointer" data-toggle="modal" data-target="#petunjuk2Modal"></i></h4>
                            <div class="card-body">
                                {{ $data->deskripsi_field }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box mb-30">
                            <h4 class="card-header mt-0 mb-1">Banner & Petunjuk <i class="fa fa-question-circle cursor-pointer" data-toggle="modal" data-target="#petunjuk3Modal"></i></h4>
                            <div class="card-body">
                                <img src="{{$data->banner}}" alt="Card image cap" style="width:100%;height:20%;">
                                <hr>
                                <img src="{!! $data->petunjuk !!}" alt="Card image cap" style="width:100%;height:20%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="petunjukModal" tabindex="-1" role="dialog" ria-labelledby="petunjukModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini untuk <b>THUMBNAIL & DESCRIPTION</b> pada layout/tampilan order website contoh :
								</p>
								<img src="{{ url('') }}/assets/fitur_website/Screenshot_4.png?v=<?= time() ?>" alt="" style="width:auto;height:100%;">
								<div class="mt-1">
    								<button type="button" class="btn btn-light" data-dismiss="modal">Ok, Paham</button>
    							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="petunjuk1Modal" tabindex="-1" role="dialog" ria-labelledby="petunjuk1ModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini untuk <b>EXTRA</b> pada layout/tampilan order website contoh :
								</p>
								<img src="{{ url('') }}/assets/fitur_website/Screenshot_5.png?v=<?= time() ?>" alt="" style="width:auto;height:100%;">
								<div class="mt-1">
    								<button type="button" class="btn btn-light" data-dismiss="modal">Ok, Paham</button>
    							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="petunjuk2Modal" tabindex="-1" role="dialog" ria-labelledby="petunjuk2ModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini untuk <b>DESCRIPTION FIELD USERID & ZONEID</b> pada layout/tampilan order website contoh :
								</p>
								<img src="{{ url('') }}/assets/fitur_website/Screenshot_6.png?v=<?= time() ?>" alt="" style="width:auto;height:100%;">
								<div class="mt-1">
    								<button type="button" class="btn btn-light" data-dismiss="modal">Ok, Paham</button>
    							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="petunjuk3Modal" tabindex="-1" role="dialog" ria-labelledby="petunjuk3ModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content bg-warning text-white">
							<div class="modal-body text-center">
								<h3 class="text-white mb-15">
									<i class="fa fa-exclamation-triangle"></i> INFORMATION
								</h3>
								<p>
								    Fitur ini untuk <b>BANNER & PETUNJUK</b> pada layout/tampilan order website contoh :
								</p>
								<img src="{{ url('') }}/assets/fitur_website/Screenshot_7.png?v=<?= time() ?>" alt="" style="width:auto;height:100%;">
								<div class="mt-1">
    								<button type="button" class="btn btn-light" data-dismiss="modal">Ok, Paham</button>
    							</div>
							</div>
						</div>
					</div>
				</div>
<div id="modal-pilihan-tambah" class="modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <a data-tw-dismiss="modal" href="javascript:;">
        <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
      </a>
      <div class="modal-body p-10">
        <h2 class="text-base font-medium">Pilihan</h2>
        <hr>

        <div class="mt-2">
          <input type="hidden" name="id" id="id">
          <label for="regular-form-1" class="form-label">Pilihan (Masukkan Dengan Format cth: server,mythic,master
            gunakan koma</label>
          <textarea id="regular-form-1" type="text" name="name" class="form-control"></textarea>
        </div>
        <div class="px-5 pb-8">
          <button class="btn btn-primary w-24 store-pilihan">Simpan</button>
        </div>

      </div>
    </div>
  </div>
</div>
<script>
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Fungsi untuk menampilkan modal
    function showModal(modalId) {
      const el = document.getElementById(modalId);
      const modal = tailwind.Modal.getOrCreateInstance(el);
      modal.show();
    }

    // Fungsi untuk menyembunyikan modal
    function hideModal(modalId) {
      const el = document.getElementById(modalId);
      const modal = tailwind.Modal.getOrCreateInstance(el);
      modal.hide();
    }

    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('tambah-input')) {
        e.preventDefault();
        showModal('modal-input-tambah');
      }

      if (e.target.classList.contains('hapus-input')) {
        e.preventDefault();
        let id = e.target.getAttribute('data-id');
        let row = e.target.closest('tr');

        Swal.fire({
          title: 'Konfirmasi',
          text: "Hapus Input",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            axios.delete("{{ route('input.destroy', '') }}/" + id, {
                data: {
                  _token: csrfToken
                }
              })
              .then(function(response) {
                if (response.data.status === 'true') {
                  Swal.fire(
                    response.data.title,
                    response.data.description,
                    response.data.icon
                  );
                  location.reload()
                } else {
                  Swal.fire(
                    response.data.title,
                    response.data.description,
                    response.data.icon
                  );
                }
              })
              .catch((err) => {
                console.log(err);
                Swal.fire(
                  'Gagal',
                  'Terjadi kesalahan saat menghapus Input.',
                  'error'
                );
              });
          }
        });
      }

      if (e.target.classList.contains('ubah-input')) {
        e.preventDefault();
        let id = e.target.getAttribute('data-id');

        axios.get(`/kategori-input/${id}/edit`)
          .then(function(response) {
            if (response.data.status === 'true') {
              // console.log(response);
              showModal('modal-input-ubah');
              let input = response.data.data;
              // console.log(inputmination);
              document.querySelector('#modal-input-ubah input[name="name"]').value = input.name;
              document.querySelector('#modal-input-ubah input[name="id"]').value = input.id;
              document.querySelector('#modal-input-ubah input[name="kategori_id"]').value = input.kategori_id;
            } else {
              Swal.fire(
                'Gagal',
                'Terjadi kesalahan saat mengambil data input.',
                'error'
              );
            }
          })
          .catch((err) => {
            console.log(err);
            Swal.fire(
              'Gagal',
              'Terjadi kesalahan saat mengambil input.',
              'error'
            );
          });
      }

      if (e.target.classList.contains('update-input')) {
        e.preventDefault();
        let id = document.querySelector('#modal-input-ubah input[name="id"]').value;
        let name = document.querySelector('#modal-input-ubah input[name="name"]').value;
        let kategori_id = document.querySelector('#modal-input-ubah input[name="kategori_id"]').value;


        axios.put("{{ route('input.update', '') }}/" + id, {
            name: name,
            kategori_id: kategori_id,
          }, {
            headers: {
              'X-CSRF-TOKEN': csrfToken
            }
          })
          .then(function(response) {
            if (response.data.status === 'true') {
              Swal.fire({
                title: response.data.title,
                text: response.data.description,
                icon: response.data.icon
              }).then(function() {
                location.reload();
              });
              hideModal('modal-input-ubah');
            } else {
              Swal.fire({
                title: response.data.title,
                text: response.data.description,
                icon: response.data.icon
              });
            }
          })
          .catch(function() {
            Swal.fire({
              title: 'Error',
              text: 'Terjadi kesalahan saat mengubah inputmination.',
              icon: 'error'
            });
          });
      }
    });
  </script>
@endsection